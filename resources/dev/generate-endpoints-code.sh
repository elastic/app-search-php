#!/bin/bash
set -e

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
ROOT_DIR=`cd $SCRIPT_DIR/../..; pwd`
GENERATOR_IMAGE=swiftype/swiftype-php-openapi-generator

FIXER_IMAGE=herloct/php-cs-fixer
FIXER_RULES="@Symfony,-phpdoc_no_package,-phpdoc_annotation_without_dot"

cd ${SCRIPT_DIR} && docker build --target runner -t ${GENERATOR_IMAGE} swiftype-php-codegen

docker run --rm -v ${ROOT_DIR}:/local ${GENERATOR_IMAGE} generate -g swiftype-php \
                                                                  -i /local/resources/api/api-spec.yml \
                                                                  -o /local/ \
                                                                  -c /local/resources/api/config.json


docker run --rm -v ${ROOT_DIR}:/project ${FIXER_IMAGE} fix --config=.php_cs.dist \
                                                           --rules=${FIXER_RULES}
