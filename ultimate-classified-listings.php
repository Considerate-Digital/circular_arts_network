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
  //sleep();
  //print_r("plugin activated");
	$args = array(
    'taxonomy' => 'uclwp_listing_category',
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
      'Wanted'
    );

    for ($i = 0; $i < count( $standard_categories ); $i++) {
      $name = $standard_categories[$i]; 

      wp_insert_term(
        $name,
        'uclwp_listing_category', 
        array(
          'description' => $name 
        )
      );
    
    }
  }
}
//add_action( 'activate_circular_arts_network/ultimate-classified-listings.php', 'add_categories');
add_action( 'admin_init', 'add_categories');
  
