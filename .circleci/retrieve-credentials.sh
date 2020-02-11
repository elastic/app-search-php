#!/bin/bash
function wait_for_as {
  local AS_URL=${AS_URL:-"http://localhost:3002"}
  curl --connect-timeout 5 --max-time 10 --retry 10 --retry-delay 0 --retry-max-time 120 --retry-connrefuse -s -o /dev/null ${AS_URL}/login
}

function load_api_keys {
  local AS_URL=${AS_URL:-"http://localhost:3002"}
  local SEARCH_URL="${AS_URL}/as/credentials/collection?page%5Bcurrent%5D=1"
  echo $(curl -uapp_search:password -s ${SEARCH_URL} | sed -E "s/.*(${1}-[[:alnum:]]{24}).*/\1/")
}

wait_for_as
export AS_URL=${AS_URL:-"http://localhost:3002"}
export AS_PRIVATE_KEY=`load_api_keys private`
export AS_SEARCH_KEY=`load_api_keys search`
unset -f load_api_keys
