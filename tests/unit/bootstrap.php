<?php
declare(strict_types=1);

define('ABSPATH', __DIR__);
define('CIRCARTSNET_PATH', dirname(__DIR__, 2));
define('CIRCARTSNET_URL', 'http://example.com/wp-content/plugins/circular-arts-network');
define('CIRCARTSNET_VERSION', '0.2');

$GLOBALS['__can_test_options'] = array();
$GLOBALS['__can_test_query_vars'] = array();
$GLOBALS['__can_test_current_user'] = (object) array(
    'ID' => 0,
    'roles' => array(),
);
$GLOBALS['__can_test_current_user_id'] = 0;
$GLOBALS['__can_test_user_caps'] = array();
$GLOBALS['__can_test_post'] = null;
$GLOBALS['__can_test_post_type_object'] = (object) array('publicly_queryable' => true);
$GLOBALS['__can_test_dropdown_users_return'] = '<select name="post_author_override_replaced"></select>';
$GLOBALS['__can_test_registered_post_types'] = array();
$GLOBALS['__can_test_registered_taxonomies'] = array();
$GLOBALS['__can_test_actions'] = array();
$GLOBALS['__can_test_sent_emails'] = array();
$GLOBALS['__can_test_wp_mail_result'] = true;
$GLOBALS['__can_test_is_singular'] = '';
$GLOBALS['__can_test_is_archive'] = false;
$GLOBALS['__can_test_roles'] = array();
$GLOBALS['__can_test_added_roles'] = array();

if (!function_exists('get_option')) {
    function get_option($key) {
        return $GLOBALS['__can_test_options'][$key] ?? false;
    }
}

if (!function_exists('update_option')) {
    function update_option($key, $value) {
        $GLOBALS['__can_test_options'][$key] = $value;
        return true;
    }
}

if (!function_exists('delete_option')) {
    function delete_option($key) {
        unset($GLOBALS['__can_test_options'][$key]);
        return true;
    }
}

if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
        return true;
    }
}

if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {
        return true;
    }
}

if (!function_exists('add_shortcode')) {
    function add_shortcode($tag, $callback) {
        return true;
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters($hook_name, $value) {
        return $value;
    }
}

if (!function_exists('do_action')) {
    function do_action($hook_name, ...$args) {
        $GLOBALS['__can_test_actions'][] = array(
            'hook' => $hook_name,
            'args' => $args,
        );
        return null;
    }
}

if (!function_exists('has_filter')) {
    function has_filter($hook_name) {
        return false;
    }
}

if (!function_exists('__')) {
    function __($text, $domain = null) {
        return $text;
    }
}

if (!function_exists('_x')) {
    function _x($text, $context, $domain = null) {
        return $text;
    }
}

if (!function_exists('esc_html')) {
    function esc_html($text) {
        return (string) $text;
    }
}

if (!function_exists('esc_html_e')) {
    function esc_html_e($text, $domain = null) {
        return (string) $text;
    }
}

if (!function_exists('esc_attr')) {
    function esc_attr($text) {
        return (string) $text;
    }
}

if (!function_exists('esc_url')) {
    function esc_url($url) {
        return (string) $url;
    }
}

if (!function_exists('wp_parse_args')) {
    function wp_parse_args($args, $defaults = array()) {
        return array_merge($defaults, $args);
    }
}

if (!function_exists('shortcode_atts')) {
    function shortcode_atts($pairs, $atts) {
        return array_merge($pairs, $atts);
    }
}

if (!function_exists('absint')) {
    function absint($value) {
        return abs((int) $value);
    }
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($text) {
        return is_scalar($text) ? trim((string) $text) : '';
    }
}

if (!function_exists('sanitize_key')) {
    function sanitize_key($key) {
        return strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', (string) $key));
    }
}

if (!function_exists('wp_unslash')) {
    function wp_unslash($value) {
        return $value;
    }
}

if (!function_exists('sanitize_html')) {
    function sanitize_html($text) {
        return (string) $text;
    }
}

if (!function_exists('get_query_var')) {
    function get_query_var($key) {
        return $GLOBALS['__can_test_query_vars'][$key] ?? '';
    }
}

if (!function_exists('is_user_logged_in')) {
    function is_user_logged_in() {
        return $GLOBALS['__can_test_current_user_id'] > 0;
    }
}

if (!function_exists('wp_get_current_user')) {
    function wp_get_current_user() {
        return $GLOBALS['__can_test_current_user'];
    }
}

if (!function_exists('get_current_user_id')) {
    function get_current_user_id() {
        return (int) $GLOBALS['__can_test_current_user_id'];
    }
}

if (!function_exists('current_user_can')) {
    function current_user_can($cap) {
        return in_array($cap, $GLOBALS['__can_test_user_caps'], true);
    }
}

if (!function_exists('get_post')) {
    function get_post($post_id = null) {
        return $GLOBALS['__can_test_post'];
    }
}

if (!function_exists('get_post_type')) {
    function get_post_type($post = null) {
        if (is_object($post) && isset($post->post_type)) {
            return $post->post_type;
        }
        return '';
    }
}

if (!function_exists('get_post_type_object')) {
    function get_post_type_object($type) {
        return $GLOBALS['__can_test_post_type_object'];
    }
}

if (!function_exists('get_permalink')) {
    function get_permalink($id = 0) {
        return 'https://example.com/?p=' . (int) $id;
    }
}

if (!function_exists('get_the_title')) {
    function get_the_title($post_id = 0) {
        return 'Listing #' . (int) $post_id;
    }
}

if (!function_exists('admin_url')) {
    function admin_url($path = '') {
        return 'https://example.com/wp-admin/' . ltrim((string) $path, '/');
    }
}

if (!function_exists('get_bloginfo')) {
    function get_bloginfo($show = '') {
        if ($show === 'admin_email') {
            return 'admin@example.com';
        }
        return 'CAN Test Site';
    }
}

if (!function_exists('wp_mail')) {
    function wp_mail($to, $subject, $message, $headers = array()) {
        $GLOBALS['__can_test_sent_emails'][] = array(
            'to' => $to,
            'subject' => $subject,
            'message' => $message,
            'headers' => $headers,
        );
        return (bool) $GLOBALS['__can_test_wp_mail_result'];
    }
}

if (!function_exists('wp_json_encode')) {
    function wp_json_encode($value) {
        return json_encode($value);
    }
}

if (!function_exists('get_post_field')) {
    function get_post_field($field, $post_id) {
        if ($field === 'post_author') {
            return 5;
        }
        return null;
    }
}

if (!function_exists('get_userdata')) {
    function get_userdata($user_id) {
        return (object) array(
            'ID' => (int) $user_id,
            'user_email' => 'seller@example.com',
        );
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        return false;
    }
}

if (!function_exists('add_query_arg')) {
    function add_query_arg($key, $value, $url) {
        return $url . (str_contains($url, '?') ? '&' : '?') . $key . '=' . $value;
    }
}

if (!function_exists('wp_post_revision_title')) {
    function wp_post_revision_title($revision_id, $deprecated = false) {
        return 'Revision ' . (int) $revision_id;
    }
}

if (!function_exists('date_i18n')) {
    function date_i18n($format, $timestamp) {
        return date($format, (int) $timestamp);
    }
}

if (!function_exists('wp_dropdown_users')) {
    function wp_dropdown_users($args = array()) {
        return $GLOBALS['__can_test_dropdown_users_return'];
    }
}

if (!function_exists('register_post_type')) {
    function register_post_type($post_type, $args = array()) {
        $GLOBALS['__can_test_registered_post_types'][$post_type] = $args;
        return true;
    }
}

if (!function_exists('register_taxonomy')) {
    function register_taxonomy($taxonomy, $object_type, $args = array()) {
        $GLOBALS['__can_test_registered_taxonomies'][$taxonomy] = array(
            'object_type' => $object_type,
            'args' => $args,
        );
        return true;
    }
}

if (!function_exists('is_post_type_archive')) {
    function is_post_type_archive($post_type = '') {
        return ($GLOBALS['__can_test_query_vars']['is_post_type_archive'] ?? '') === $post_type;
    }
}

if (!function_exists('is_tax')) {
    function is_tax($taxonomy = '') {
        return ($GLOBALS['__can_test_query_vars']['is_tax'] ?? '') === $taxonomy;
    }
}

if (!function_exists('single_cat_title')) {
    function single_cat_title($prefix = '', $display = true) {
        return $GLOBALS['__can_test_query_vars']['single_cat_title'] ?? 'Sample';
    }
}

if (!function_exists('wp_get_post_terms')) {
    function wp_get_post_terms($post_id, $taxonomy) {
        return $GLOBALS['__can_test_query_vars']['terms'] ?? array();
    }
}

if (!function_exists('load_plugin_textdomain')) {
    function load_plugin_textdomain($domain, $deprecated = false, $plugin_rel_path = false) {
        return true;
    }
}

if (!function_exists('basename')) {
    function basename($path, $suffix = '') {
        return \basename($path, $suffix);
    }
}

if (!function_exists('flush_rewrite_rules')) {
    function flush_rewrite_rules() {
        return true;
    }
}

if (!function_exists('add_role')) {
    function add_role($role, $display_name, $capabilities = array()) {
        $GLOBALS['__can_test_added_roles'][$role] = array(
            'display_name' => $display_name,
            'capabilities' => $capabilities,
        );
        return true;
    }
}

if (!function_exists('get_role')) {
    function get_role($role) {
        if (!isset($GLOBALS['__can_test_roles'][$role])) {
            $GLOBALS['__can_test_roles'][$role] = new class {
                public array $caps = array();
                public function add_cap($cap): void {
                    $this->caps[] = $cap;
                }
                public function remove_cap($cap): void {
                    $this->caps = array_values(array_filter(
                        $this->caps,
                        static fn ($existing): bool => $existing !== $cap
                    ));
                }
            };
        }
        return $GLOBALS['__can_test_roles'][$role];
    }
}

if (!function_exists('is_singular')) {
    function is_singular($post_type = '') {
        return ($GLOBALS['__can_test_is_singular'] ?? '') === $post_type;
    }
}

if (!function_exists('is_archive')) {
    function is_archive() {
        return (bool) ($GLOBALS['__can_test_is_archive'] ?? false);
    }
}

if (!function_exists('get_stylesheet_directory')) {
    function get_stylesheet_directory() {
        return '/tmp/nonexistent-theme';
    }
}

require_once CIRCARTSNET_PATH . '/inc/helpers.php';
require_once CIRCARTSNET_PATH . '/classes/class-register-cpt.php';
require_once CIRCARTSNET_PATH . '/classes/class-can-init.php';
require_once CIRCARTSNET_PATH . '/classes/class-front-templates.php';
require_once CIRCARTSNET_PATH . '/classes/class-shortcodes.php';
require_once CIRCARTSNET_PATH . '/classes/class-email.php';
require_once CIRCARTSNET_PATH . '/classes/class-admin-settings.php';
