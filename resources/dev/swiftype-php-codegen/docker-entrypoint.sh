#!/usr/bin/env bash

set -euo pipefail

# GEN_DIR allows to share the entrypoint between Dockerfile and run-in-docker.sh (backward compatible)
#GEN_DIR=${GEN_DIR:-/opt/openapi-generator}
JAVA_OPTS=${JAVA_OPTS:-"-Xmx1024M -DloggerPath=conf/log4j.properties"}
classPath=$(find $OPENAPI_GEN_JARS_DIR -name *.jar -print0 |  tr '\0' ':' | head -c -1)
#echo $classPath
#cli="${GEN_DIR}/modules/openapi-generator-cli"
codegen="org.openapitools.codegen.OpenAPIGenerator"

# We code in a list of commands here as source processing is potentially buggy (requires undocumented conventional use of annotations).
# A list of known commands helps us determine if we should compile CLI. There's an edge-case where a new command not added to this
# list won't be considered a "real" command. We can get around that a bit by checking CLI completions beforehand if it exists.
commands="list,generate,meta,langs,help,config-help,validate,version"

# if CLI jar exists, check $1 against completions available in the CLI
if [[ -n "$(java ${JAVA_OPTS} -cp ${classPath} ${codegen} completion | grep "^$1\$" )" ]]; then
    command=$1
    shift
    exec java ${JAVA_OPTS} -cp ${classPath} ${codegen} "${command}" "$@"
elif [[ -n "$(echo commands | tr ',' '\n' | grep "^$1\$" )" ]]; then
    command=$1
    shift
    exec java ${JAVA_OPTS} -cp ${classPath} ${codegen} "${command}" "$@"
else
    # Pass args as linux commands. This allows us to do something like: docker run -it (-e…, -v…) image ls -la
    exec "$@"
fi