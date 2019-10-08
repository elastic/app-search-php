export AS_URL=${AS_URL:-"http://localhost:3002"}

#!/bin/bash
function wait_for_as {
  curl --connect-timeout 5 --max-time 10 --retry 10 --retry-delay 0 --retry-max-time 120 --retry-connrefuse -s -o /dev/null ${AS_URL}/login
}

function load_api_keys {
  local ES_URL=${ES_URL:-"http://localhost:9200"}
  local API_TOKEN_INDEX=.app-search-actastic-loco_moco_api_tokens
  local SEARCH_URL="${ES_URL}/${API_TOKEN_INDEX}/_search?filter_path=hits.hits._source.authentication_token"
  echo $(curl -s "${SEARCH_URL}&q=authentication_token:${1}-*" | sed -E "s/.*(${1}-[[:alnum:]]*).*/\1/")
}

wait_for_as
export AS_PRIVATE_KEY=`load_api_keys private`
export AS_SEARCH_KEY=`load_api_keys search`
unset -f load_api_keys
