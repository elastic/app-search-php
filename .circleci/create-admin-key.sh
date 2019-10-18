#!/bin/bash
export AS_URL=${AS_URL:-"http://localhost:3002"}

function wait_for_as {
  curl --connect-timeout 5 --max-time 10 --retry 10 --retry-delay 0 --retry-max-time 120 --retry-connrefuse -s -o /dev/null ${AS_URL}/login
}

function create_admin_key {
  local ES_URL=${ES_URL:-"http://localhost:9200"}
  # retrieve an existing private key
  local JSON=$(curl -s "${ES_URL}/.app-search-actastic-loco_moco_api_tokens/_search?q=authentication_token:private-*")
  # rewrite to an admin key
  local ADMIN_JSON=$(echo "$JSON"|jq -c '.hits.hits[0]._source'|sed s/private/admin/g)
  curl -s -X PUT -H "Content-Type: application/json" "${ES_URL}/.app-search-actastic-loco_moco_api_tokens/_doc/phpintegrationtesting?pretty&refresh=true" \
    -d "$ADMIN_JSON"
}

#wait_for_as
create_admin_key
unset -f create_admin_key
