#!/bin/bash

set -e

source .circleci/retrieve-credentials.sh

echo "Running unit tests"
vendor/bin/phpunit -c phpunit.xml.dist --testsuite unit

echo "Running integration tests"
vendor/bin/phpunit -c phpunit.xml.dist --testsuite integration
