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
  $wood = wp_insert_term(
    'Wood',
    'uclwp_listing_category', 
    array(
      'description' => 'Wood comes from trees'
    )
  );
  if ( !is_wp_error($wood) ) {
    // add image
    $dir = plugin_dir_path( __FILE__ );
    $filename = $dir . "assets/images/categories/wood.jpg";

   //Customize this post data as you wish
    $my_post_data = array(
        'post_title' => basename( $filename ),
        'post_type' => 'post',
        'post_category' => array('1'),
        'post_author'   => 1,
        'post_status' => 'publish'
    );

    // We need the ID for the attachment
    $post_id = wp_insert_post($my_post_data);

    $filetype = wp_check_filetype( basename( $filename ), null );
    $upload_dir = wp_upload_dir();
    var_dump($upload_dir);
    $args = array(
      'guid'           => $upload_dir['url'] . '/' . basename( $filename ), 
      'post_mime_type' => $filetype['type'],
      'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
      'post_content'   => '',
      'post_status'    => 'inherit'
    );
    $image_id = wp_insert_attachment( $args, $filename, $post_id);
    $attach_data = wp_generate_attachment_metadata( $image_id, $filename );
    wp_update_attachment_metadata( $image_id, $attach_data);
    //$image_src_id = wp_get_attachment_image_src( $image_id );


    // add term meta
    var_dump( $wood );
    add_term_meta( $wood['term_id'], 'ucl_category_image', $upload_dir['url']);
  }

  wp_insert_term(
    'Metal',
    'uclwp_listing_category', 
    array(
      'description' => 'Metal comes from the earth.'
    )
  );
}
//add_action( 'activate_circular_arts_network/ultimate-classified-listings.php', 'add_categories');
add_action( 'admin_init', 'add_categories');
  
