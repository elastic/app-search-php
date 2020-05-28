#!/bin/bash
function wait_for_as {
  local AS_URL=${AS_URL:-"http://localhost:3002"}
  local continue=1
  set +e
  while [ $continue -gt 0 ]; do 
    curl --connect-timeout 5 --max-time 10 --retry 10 --retry-delay 0 --retry-max-time 120 --retry-connrefuse -s -o /dev/null ${AS_URL}/login
    continue=$?
    if [ $continue -gt 0 ]; then
      sleep 1
    fi
  done
}

function load_api_keys {
  local AS_USERNAME=${AS_USERNAME:-"enterprise_search"}
  local AS_PASSWORD=${AS_PASSWORD:-"password"}
  local AS_URL=${AS_URL:-"http://localhost:3002"}
  local SEARCH_URL="${AS_URL}/as/credentials/collection?page%5Bcurrent%5D=1"
  echo $(curl -u${AS_USERNAME}:${AS_PASSWORD} -s ${SEARCH_URL} | sed -E "s/.*(${1}-[[:alnum:]]{24}).*/\1/")
}

wait_for_as
export AS_URL=${AS_URL:-"http://localhost:3002"}
export AS_PRIVATE_KEY=`load_api_keys private`
export AS_SEARCH_KEY=`load_api_keys search`
unset -f load_api_keys
