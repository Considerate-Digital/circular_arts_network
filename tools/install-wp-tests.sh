#!/usr/bin/env bash

set -eu

if [ $# -lt 3 ]; then
  echo "Usage: $0 <db_name> <db_user> <db_pass> [db_host] [wp_version]"
  exit 1
fi

DB_NAME="$1"
DB_USER="$2"
DB_PASS="$3"
DB_HOST="${4-localhost}"
WP_VERSION="${5-latest}"
WP_TESTS_DIR="${WP_TESTS_DIR-/tmp/wordpress-tests-lib}"
WP_CORE_DIR="${WP_CORE_DIR-/tmp/wordpress/}"

mkdir -p "$WP_TESTS_DIR"
mkdir -p "$WP_CORE_DIR"

download() {
  if command -v curl >/dev/null 2>&1; then
    curl -s "$1"
  elif command -v wget >/dev/null 2>&1; then
    wget -q -O - "$1"
  else
    echo "curl or wget is required"
    exit 1
  fi
}

if [ ! -d "$WP_CORE_DIR/wp-includes" ]; then
  download "https://wordpress.org/wordpress-${WP_VERSION}.tar.gz" | tar zx --strip-components=1 -C "$WP_CORE_DIR"
fi

if [ ! -f "$WP_TESTS_DIR/includes/functions.php" ]; then
  svn co --quiet "https://develop.svn.wordpress.org/tags/${WP_VERSION}/tests/phpunit/includes/" "$WP_TESTS_DIR/includes"
  svn co --quiet "https://develop.svn.wordpress.org/tags/${WP_VERSION}/tests/phpunit/data/" "$WP_TESTS_DIR/data"
fi

if [ ! -f "$WP_TESTS_DIR/wp-tests-config.php" ]; then
  download "https://develop.svn.wordpress.org/tags/${WP_VERSION}/wp-tests-config-sample.php" > "$WP_TESTS_DIR/wp-tests-config.php"
fi

sed -i "s/youremptytestdbnamehere/$DB_NAME/" "$WP_TESTS_DIR/wp-tests-config.php"
sed -i "s/yourusernamehere/$DB_USER/" "$WP_TESTS_DIR/wp-tests-config.php"
sed -i "s/yourpasswordhere/$DB_PASS/" "$WP_TESTS_DIR/wp-tests-config.php"
sed -i "s|localhost|$DB_HOST|" "$WP_TESTS_DIR/wp-tests-config.php"
sed -i "s|dirname( __FILE__ ) . '/src/'|'$WP_CORE_DIR/'|" "$WP_TESTS_DIR/wp-tests-config.php"

echo "WordPress tests configured:"
echo "  WP_TESTS_DIR=$WP_TESTS_DIR"
echo "  WP_CORE_DIR=$WP_CORE_DIR"
