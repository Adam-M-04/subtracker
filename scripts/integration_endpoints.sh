#!/usr/bin/env bash
set -euo pipefail

BASE_URL="${BASE_URL:-http://localhost}"
COOKIE_JAR="${COOKIE_JAR:-/tmp/subtracker_cookies.txt}"

extract_csrf_token() {
  local page="$1"
  printf '%s' "$page" | sed -n 's/.*name="_csrf_token" value="\([^"]*\)".*/\1/p' | head -n1
}

echo "[1/5] Fetch login form"
login_page="$(curl -sS -c "$COOKIE_JAR" "$BASE_URL/login")"
csrf_token="$(extract_csrf_token "$login_page")"

if [[ -z "$csrf_token" ]]; then
  echo "CSRF token not found on login page"
  exit 1
fi

echo "[2/5] Login as admin"
curl -sS -b "$COOKIE_JAR" -c "$COOKIE_JAR" -X POST "$BASE_URL/login" \
  --data-urlencode "email=admin@subtracker.test" \
  --data-urlencode "password=Test123#" \
  --data-urlencode "_csrf_token=$csrf_token" > /dev/null

echo "[3/5] Call protected endpoint /subscriptions"
status_subs="$(curl -sS -o /dev/null -w '%{http_code}' -b "$COOKIE_JAR" "$BASE_URL/subscriptions")"
echo "HTTP /subscriptions => $status_subs"

echo "[4/5] Call admin endpoint /users"
status_users="$(curl -sS -o /dev/null -w '%{http_code}' -b "$COOKIE_JAR" "$BASE_URL/users")"
echo "HTTP /users => $status_users"

echo "[5/5] Call unknown route (expect 404)"
status_404="$(curl -sS -o /dev/null -w '%{http_code}' -b "$COOKIE_JAR" "$BASE_URL/route-does-not-exist")"
echo "HTTP /route-does-not-exist => $status_404"

echo "Integration smoke test finished."

