<?php
/**
 * Plugin Name: Circular Arts Network
 * Plugin URI: https://canarts.org.uk
 * Description: A circular arts network for WordPress.
 * Version: 0.2
 * Author: CIRCARTSNETARTS
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: circular-arts-network
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define('CIRCARTSNET_PATH', untrailingslashit(plugin_dir_path( __FILE__ )) );
define('CIRCARTSNET_URL', untrailingslashit(plugin_dir_url( __FILE__ )) );
define('CIRCARTSNET_VERSION', '0.2' );

require_once( CIRCARTSNET_PATH.'/inc/helpers.php' );
require_once( CIRCARTSNET_PATH.'/classes/class-admin-settings.php' );
require_once( CIRCARTSNET_PATH.'/classes/class-can-init.php' );
require_once( CIRCARTSNET_PATH.'/classes/class-register-cpt.php' );
require_once( CIRCARTSNET_PATH.'/classes/class-shortcodes.php' );
require_once( CIRCARTSNET_PATH.'/classes/class-email.php' );
require_once( CIRCARTSNET_PATH.'/classes/class-front-templates.php' );

function circartsnet_add_categories() {
  //sleep();
  //print_r("plugin activated");
	$args = array(
    'taxonomy' => 'circartsnet_listing_category',
    'hide_empty' => false
  );
  $count_categories = get_terms( $args );
  // if any categories already exist, then don't add the categories
  if ( count($count_categories) == 0 ) {

    $standard_categories = array(
      'Materials',
      'Equipment',
      'Transport',
      'Furniture',
      'Packing',
      'Time',
      'Skills',
      'Everything Else',
      'Wanted',
      'Stories'
    );

    for ($i = 0; $i < count( $standard_categories ); $i++) {
      $name = $standard_categories[$i]; 

      wp_insert_term(
        $name,
        'circartsnet_listing_category', 
        array(
          'description' => $name 
        )
      );
    }
  }
}
add_action( 'admin_init', 'circartsnet_add_categories');
  
function hide_admin_bar_for_specific_roles() {
	    $user = wp_get_current_user();
	    if (in_array('circartsnet_listing_seller', (array) $user->roles)) {
		show_admin_bar(false);
	    }
}
add_action('after_setup_theme', 'hide_admin_bar_for_specific_roles');
