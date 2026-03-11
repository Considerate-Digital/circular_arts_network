<?php
declare(strict_types=1);

define('DB_NAME', getenv('WP_TEST_DB_NAME') ?: 'wordpress_test');
define('DB_USER', getenv('WP_TEST_DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('WP_TEST_DB_PASSWORD') ?: '');
define('DB_HOST', getenv('WP_TEST_DB_HOST') ?: '127.0.0.1');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

define('WP_TESTS_DOMAIN', getenv('WP_TESTS_DOMAIN') ?: 'example.org');
define('WP_TESTS_EMAIL', getenv('WP_TESTS_EMAIL') ?: 'admin@example.org');
define('WP_TESTS_TITLE', getenv('WP_TESTS_TITLE') ?: 'CAN Test Blog');
define('WP_PHP_BINARY', getenv('WP_PHP_BINARY') ?: 'php');

define('WP_DEBUG', true);
define('SCRIPT_DEBUG', true);
define('ABSPATH', getenv('WP_CORE_DIR') ?: '/tmp/wordpress/');

$table_prefix = 'wptests_';
