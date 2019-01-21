#!/bin/bash
set -e
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
ROOT_DIR=`cd $SCRIPT_DIR/../..; pwd`
IMAGE=swiftype/swiftype-php-openapi-generator

cd $SCRIPT_DIR && docker build --target runner -t $IMAGE swiftype-php-codegen

docker run --rm -v ${ROOT_DIR}:/local $IMAGE generate -g swiftype-php \
                                                      -i /local/resources/api/api-spec.yml \
                                                      -o /local/ \
                                                      -c /local/resources/api/config.json

#SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
#ROOT_DIR=`cd $SCRIPT_DIR/../..; pwd`
#IMAGE=openapitools/openapi-generator-cli:v3.3.4

#docker run --rm -v ${ROOT_DIR}:/local $IMAGE generate -g php \
#                                                       -i /local/resources/api/api-spec.yml \
#                                                       -o /local/ \
#                                                       -c /local/resources/api/config.json \
#                                                       -t /local/resources/dev/codegen-php-template/
#
#
# docker run --rm -v ${ROOT_DIR}:/local $IMAGE cat /local/composer.json
