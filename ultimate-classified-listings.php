<?php
/**
 * Plugin Name: Circular Arts Network 
 * Plugin URI: https://www.wpclassifiedlistings.com/
 * Description: A simple yet complete classifieds and listings system for WordPress.
 * Version: 1.2
 * Author: WebCodingPlace
 * Author URI: https://webcodingplace.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ultimate-classified-listings
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define('UCLWP_PATH', untrailingslashit(plugin_dir_path( __FILE__ )) );
define('UCLWP_URL', untrailingslashit(plugin_dir_url( __FILE__ )) );
define('UCLWP_VERSION', '1.2' );

require_once( UCLWP_PATH.'/inc/helpers.php' );
require_once( UCLWP_PATH.'/classes/class-admin-settings.php' );
require_once( UCLWP_PATH.'/classes/class-ucl-init.php' );
require_once( UCLWP_PATH.'/classes/class-register-cpt.php' );
require_once( UCLWP_PATH.'/classes/class-shortcodes.php' );
require_once( UCLWP_PATH.'/classes/class-email.php' );
require_once( UCLWP_PATH.'/classes/class-front-templates.php' );

function add_categories() {
  wp_insert_term(
    'Wood',
    'uclwp_listing_category', 
    array(
      'description' => 'Wood comes from trees',
    )
  );
}
add_action( 'after_setup_theme', 'add_categories');
  
