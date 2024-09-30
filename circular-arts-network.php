<?php
/**
 * Plugin Name: Circular Arts Network 
 * Plugin URI: https://canarts.org.uk
 * Description: A circular arts network for WordPress.
 * Version: 0.2
 * Author: Considerate Digital
 * Author URI: https://considerate.digital
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: circular-arts-network
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define('CAN_PATH', untrailingslashit(plugin_dir_path( __FILE__ )) );
define('CAN_URL', untrailingslashit(plugin_dir_url( __FILE__ )) );
define('CAN_VERSION', '1.2' );

require_once( CAN_PATH.'/inc/helpers.php' );
require_once( CAN_PATH.'/classes/class-admin-settings.php' );
require_once( CAN_PATH.'/classes/class-can-init.php' );
require_once( CAN_PATH.'/classes/class-register-cpt.php' );
require_once( CAN_PATH.'/classes/class-shortcodes.php' );
require_once( CAN_PATH.'/classes/class-email.php' );
require_once( CAN_PATH.'/classes/class-front-templates.php' );

function add_categories() {
  //sleep();
  //print_r("plugin activated");
	$args = array(
    'taxonomy' => 'can_listing_category',
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
        'can_listing_category', 
        array(
          'description' => $name 
        )
      );
    
    }
  }
}
//add_action( 'activate_circular_arts_network/circular-arts-network.php', 'add_categories');
add_action( 'admin_init', 'add_categories');
  
function hide_admin_bar_for_specific_roles() {
	    $user = wp_get_current_user();
	    if (in_array('can_listing_seller', (array) $user->roles)) {
		show_admin_bar(false);
	    }
}
add_action('after_setup_theme', 'hide_admin_bar_for_specific_roles');

