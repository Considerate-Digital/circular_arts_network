<?php
/**
 * UCL: Renders all the Frontend Templates
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CIRCARTSNET_Front_Templates
{
	
	function __construct(){
		add_filter( 'template_include', array($this, 'front_templates'), 99 );
		add_action( 'wp_enqueue_scripts', array($this, 'front_scripts' ) );

		add_action( 'circartsnet_listing_content', array($this, 'render_listing_content' ) );
		add_action( 'circartsnet_listing_sidebar', array($this, 'render_listing_sidebar' ) );

        // Pagination
        add_action( 'circartsnet_pagination', array($this, 'render_pagination' ), 10, 2 );

        add_action( 'circartsnet_listing_box', array($this, 'listing_box'), 10, 4 );
        add_action( 'circartsnet_archive_topbar', array($this, 'archive_topbar'), 10 );
        add_action( 'circartsnet_featured_image', array($this, 'featured_image'), 10, 2 );

        add_filter( 'get_the_archive_title', array($this, 'custom_archive_title' ), 10, 1 );

        add_action( 'wp_footer', array($this, 'render_compare_box') );

        add_action( 'wp_ajax_circartsnet_compare_listings', array($this, 'listings_compare_table' ) );
        add_action( 'wp_ajax_nopriv_circartsnet_compare_listings', array($this, 'listings_compare_table' ) );
	}

	function front_templates($template){

		if (is_singular('circartsnet_listing')) {
			$template = CIRCARTSNET_PATH . '/templates/single-listing.php';
		}

		if (is_archive()) {

			if (is_tax('circartsnet_listing_category') || is_tax('circartsnet_listing_tag')) {
				$template = CIRCARTSNET_PATH . '/templates/archive.php';
			}

            global $post;

            if (isset($post->post_type) && $post->post_type == 'circartsnet_listing') {
                $template = CIRCARTSNET_PATH . '/templates/archive.php';
            }
		}

		return $template;
	}

    function custom_archive_title($title){
        if (is_post_type_archive( 'circartsnet_listing' )) {
            $title = (circartsnet_get_option('archive_title') != '') ? circartsnet_get_option('archive_title') : __( 'Listings:', 'circular-arts-network' );
        }
        if( is_tax('circartsnet_listing_tag') ) {
            $title = (circartsnet_get_option('tag_title') != '') ? str_replace('%tag%', single_cat_title( '', false ), circartsnet_get_option('tag_title')) : __( 'Tag:', 'circular-arts-network' ).' '.single_cat_title( '', false ) ;
        }
        if( is_tax('circartsnet_listing_category') ) {
            $title = (circartsnet_get_option('category_title') != '') ? str_replace('%category%', single_cat_title( '', false ), circartsnet_get_option('category_title')) : __( 'Category:', 'circular-arts-network' ).' '.single_cat_title( '', false ) ;
        }
        return $title;        
    }

    function render_compare_box(){
        if (circartsnet_get_option('enable_compare', 'enable') == 'enable') {

            $in_theme = get_stylesheet_directory().'/can/compare-box.php';
            if (file_exists($in_theme)) {
                include $in_theme;
            } else {
                include CIRCARTSNET_PATH . '/templates/compare-box.php';
            }
        }

        if (circartsnet_get_option('custom_js', '') != '') {
            ob_start(); 
            // TODO: implement custom JS options
        ?>
                
                <!--script type="text/javascript">
                    jQuery(document).ready(function($) {
                        <?php echo esc_html(stripcslashes(circartsnet_get_option('custom_js'))); ?>
                    });             
                </script-->              
            <?php echo esc_html(ob_get_clean());            
        }
    }

    function listings_compare_table(){
        $nonce_success = false;
        if (isset($_REQUEST['_wpnonce'])) {
          $nonce_success = wp_verify_nonce( sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'compare' ); 
        }
        if (!$nonce_success) {
            wp_nonce_ays('log-out');
        }
        if (isset($_REQUEST['listing_ids'])) {
            $listing_ids = array_map( 'sanitize_text_field', sanitize_text_field((wp_unslash($_REQUEST['listing_ids'])) ));

            $saved_table_label = circartsnet_get_option('listing_compare_columns');
            if (!empty($saved_table_label)) {
                $array_value = explode("\n", $saved_table_label);
                foreach ($array_value as $value) {
                    $column_value = explode( "|", $value);
                    $table_columns_labels[] = $column_value['1'];
                }
            } else {
                $default_labels = array(
                    'regular_price',
                    'purpose',
                    'condition',
                    'build_date',
                );
                $default_labels = apply_filters( 'circartsnet_compare_table_default_fields', $default_labels );
                $table_columns_labels = $default_labels;
            }
            $tr = "";
            foreach ($listing_ids as $id) { 
                
                $tr .= "<tr>";
                    $tr .= "<th class='fixed-row'><a href='".get_permalink( $id )."'>".get_the_title( $id )."</a></th>";
                    foreach ($table_columns_labels as $field_key) {
                        $field_key = trim($field_key);
                        $tr .= "<td>".circartsnet_get_field_value($id, array('key' => $field_key))."</td>";
                    }
                $tr .= "</tr>";
             }
            wp_send_json($tr);
        }
    }

	function featured_image($id = '', $size = 'full'){
		if ($id == '') {
			global $post;
			$id = $post->ID;
		}

        $image_size = circartsnet_get_option('featured_image_size', $size);

        $image_size = apply_filters( 'circartsnet_featured_image_size', $image_size, $id );

        $attr = array('class' => 'can-featured-image', 'data-lid' => $id );

        if( has_post_thumbnail($id) ){
            echo get_the_post_thumbnail( $id, $image_size, $attr );
        } elseif (circartsnet_get_option('placeholder_image', '') != '') {
            echo esc_html('<img class="can-featured-image" data-pid="'.$id.'" src="'.circartsnet_get_option('placeholder_image').'">');
        } else {

        // Use the first gallery picture
        $listing_images = get_post_meta( $id, 'circartsnet_gallery_images', true );
            if (is_array($listing_images)) {
                foreach ($listing_images as $image_id) {
                    echo wp_get_attachment_image( $image_id, $image_size, false, $attr );
                    break;
                }
            }
        }
	}

    function render_pagination($paged = '', $max_page = ''){
        global $wp_query;
        $big = 999999999; // need an unlikely integer
        if( ! $paged )
            $paged = get_query_var('paged');
        if( ! $max_page )
            $max_page = $wp_query->max_num_pages;
        echo '<div class="can-pagination">';
        $search_for   = array( $big, '#038;' );
        $replace_with = array( '%#%', '&' );          
        echo esc_html(paginate_links( array(
            'base'       => str_replace($search_for, $replace_with, esc_url(get_pagenum_link( $big ))),
            'format'     => '?paged=%#%',
            'current'    => max( 1, $paged ),
            'total'      => $max_page,
            'mid_size'   => 1,
            'prev_text'  => __('«', 'circular-arts-network'),
            'next_text'  => __('»', 'circular-arts-network'),
            'type'       => 'list'
        ) ));
        echo '</div>';
    }

    function archive_topbar(){

    	wp_enqueue_style('nice-select', CIRCARTSNET_URL . "/assets/libs/css/nice-select.css", array(), CIRCARTSNET_VERSION);
    	wp_enqueue_script('nice-select', CIRCARTSNET_URL . "/assets/libs/js/jquery.nice-select.min.js", array('jquery'), CIRCARTSNET_VERSION, true);
    	wp_enqueue_script('trigger-nice-select', CIRCARTSNET_URL . "/assets/js/trigger-nice-select.js", array('jquery'), CIRCARTSNET_VERSION, true);

        $in_theme = get_stylesheet_directory().'/can/top-bar.php';

        if (file_exists($in_theme)) {
            $file_path = $in_theme;
        } else {
            $file_path = CIRCARTSNET_PATH . '/templates/top-bar.php';
        }

        if (file_exists($file_path)) {
          include $file_path;
        }    	
    }

	function lists_sorting_options(){
		$options = array(
			array(
				'title' => __( 'Sort By Date', 'circular-arts-network' ),
				'value' => 'date-desc',
			),
			array(
				'title' => __( 'Sort By Title', 'circular-arts-network' ),
				'value' => 'title-asc',
			),
			array(
				'title' => __( 'Sort By Price : High to Low', 'circular-arts-network' ),
				'value' => 'price-desc',
			),
			array(
				'title' => __( 'Sort By Price : Low to High', 'circular-arts-network' ),
				'value' => 'price-asc',
			),
		);

		return apply_filters( 'circartsnet_lists_sorting_options', $options );
	}

    function lists_status_options(){
        $options = array(
            array(
                'title' => __( 'All Listings', 'circular-arts-network' ),
                'value' => 'all',
            ),
            array(
                'title' => __( 'Published', 'circular-arts-network' ),
                'value' => 'publish',
            ),
            array(
                'title' => __( 'Drafts', 'circular-arts-network' ),
                'value' => 'draft',
            ),
            array(
                'title' => __( 'Pending', 'circular-arts-network' ),
                'value' => 'pending',
            ),
        );

        return apply_filters( 'circartsnet_lists_status_options', $options );
    }

    function listing_box($listing_id, $style = '1', $layout='grid', $target=''){
      $layout = 'grid';
      if (get_query_var('layout')) {
        $layout = get_query_var('layout');
      }
        
        $in_theme = get_stylesheet_directory().'/can/loop/'.$style.'/'.$layout.'.php';

        if (file_exists($in_theme)) {
            $file_path = $in_theme;
        } else {
            $file_path = CIRCARTSNET_PATH . '/templates/loop/'.$style.'/'.$layout.'.php';
        }

        if (file_exists($file_path)) {
         	include $file_path;
        } else {
        	echo esc_html_e( 'Template Not Found!', 'circular-arts-network' );
        }
    }

	function front_scripts(){
		if (is_singular( 'circartsnet_listing' )) {
			circartsnet_load_basic_styles();
			wp_enqueue_style('can-single', CIRCARTSNET_URL . "/assets/css/single-listing.css", array(), CIRCARTSNET_VERSION);
            wp_enqueue_script('can-single-listing', CIRCARTSNET_URL . "/assets/js/single-listing.js", array('jquery'), CIRCARTSNET_VERSION, true);
		}
		if (is_archive() && is_tax('circartsnet_listing_category')) {
			circartsnet_load_basic_styles();
			wp_enqueue_style('can-archive', CIRCARTSNET_URL . "/assets/css/archive.css", array(), CIRCARTSNET_VERSION);
		}
		if (is_archive() && is_tax('circartsnet_listing_tag')) {
			circartsnet_load_basic_styles();
			wp_enqueue_style('can-archive', CIRCARTSNET_URL . "/assets/css/archive.css", array(), CIRCARTSNET_VERSION);
		}

        if (circartsnet_get_option('enable_compare', 'enable') == 'enable') {
            wp_enqueue_style( 'listing-compare', CIRCARTSNET_URL . '/assets/css/compare.css', array(), CIRCARTSNET_VERSION );
            wp_enqueue_style( 'iziModal', CIRCARTSNET_URL . '/assets/libs/css/iziModal.min.css', array(), CIRCARTSNET_VERSION );
            wp_enqueue_script( 'iziModal', CIRCARTSNET_URL . '/assets/libs/js/iziModal.min.js', array('jquery'), CIRCARTSNET_VERSION, true );
            wp_enqueue_script( 'can-compare', CIRCARTSNET_URL . '/assets/js/compare.js', array('jquery'), CIRCARTSNET_VERSION, true );
            wp_localize_script( 'can-compare', 'circartsnet_compare', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' )
            ) );
        }
	}

	function render_listing_content(){
		global $circartsnet_admin_settings;
		$field_sections = $circartsnet_admin_settings->get_fields_sections();
		$listing_id = get_the_id();

		foreach ($field_sections as $section) {
			if (circartsnet_circartsnet_user_access($section)) {
				$this->render_section_front($section, $listing_id);
			}
		}
	}

	function render_section_front($section, $listing_id){
		
				
		$template = '';
		
		// Title and Content
		if (isset($section['key']) && $section['key'] == 'description') {
			$template = CIRCARTSNET_PATH . '/templates/content-single/description.php';
		}

		// Gallery Images
		if (isset($section['key']) && $section['key'] == 'gallery_images') {
			$galleryimages = get_post_meta( $listing_id, 'circartsnet_'.$section['key'], true );
			if (!empty($galleryimages)) {

				$gallery_type = circartsnet_get_option('gallery_type', 'slick');

				$gallery_type = apply_filters( 'circartsnet_single_listing_gallery_type', $gallery_type, $listing_id);
				$featured_image = (has_post_thumbnail( $listing_id ) && circartsnet_get_option('slider_featured_image', 'enable') == 'enable');
				$image_size = circartsnet_get_option('gallery_image_size', 'full');

	            if ($gallery_type == 'slick') {

                wp_register_script( 'can-carousel-js', CIRCARTSNET_URL . '/assets/libs/js/carousel.js', array(), CIRCARTSNET_VERSION, true );
                wp_enqueue_script( 'can-carousel-js' );

                wp_register_style( 'can-carousel-css', CIRCARTSNET_URL . '/assets/libs/css/carousel.css', array(), CIRCARTSNET_VERSION ); 
                wp_enqueue_style( 'can-carousel-css' );


	                wp_enqueue_script( 'can-trigger-slick', CIRCARTSNET_URL . '/assets/js/trigger-slick.js', array('jquery'), CIRCARTSNET_VERSION, true);
	            }

	            if ($gallery_type == 'grid') {
	                wp_enqueue_style( 'can-grid-css', CIRCARTSNET_URL . '/assets/libs/css/images-grid.css', array(), CIRCARTSNET_VERSION );
	                wp_enqueue_script( 'can-grid-js', CIRCARTSNET_URL . '/assets/libs/js/images-grid.js', array('jquery'), CIRCARTSNET_VERSION, true);
	                wp_enqueue_script( 'can-trigger-grid', CIRCARTSNET_URL . '/assets/js/trigger-grid.js', array('jquery'), CIRCARTSNET_VERSION, true);
	                wp_localize_script( 'can-trigger-grid', 'circartsnet_grid_vars', array('grid_view_txt' => circartsnet_get_option('grid_view_txt', 'View all %count% images')) );
	            }

				$template = CIRCARTSNET_PATH . '/templates/content-single/gallery_images.php';
			} else {
				return;
			}
		}

		// Tags
		if (isset($section['key']) && $section['key'] == 'tags') {
			$terms = wp_get_post_terms( $listing_id ,'circartsnet_listing_tag' );
			if (!empty($terms)) {
				$template = CIRCARTSNET_PATH . '/templates/content-single/tags.php';
			} else {
				return;
			}
		}

		// Map Leaflet or Google Map
		if (isset($section['key']) && $section['key'] == 'location') {
			$latitude = get_post_meta( $listing_id, 'circartsnet_listing_latitude', true );
			$longitude = get_post_meta( $listing_id, 'circartsnet_listing_longitude', true );
			if ($latitude && $longitude) {

                if (circartsnet_get_option('use_map_from', 'leaflet') == 'leaflet') {

                  wp_register_script( 'can-leaflet-js', CIRCARTSNET_URL .'/assets/libs/js/leaflet.js', array(), CIRCARTSNET_VERSION, true );
                  wp_enqueue_script( 'can-leaflet-js' );

                  wp_register_style( 'can-leaflet-css', CIRCARTSNET_URL . '/assets/libs/css/leaflet.css', array(), CIRCARTSNET_VERSION );
                  wp_enqueue_style( 'can-leaflet-css' );
                } else {
                	$maps_api_key = circartsnet_get_option('maps_api_key');
                    if (is_ssl()) {
                        wp_enqueue_script( 'can-single-listing-map', 'https://maps.googleapis.com/maps/api/js?key='.$maps_api_key, array(), CIRCARTSNET_VERSION, true);
                    } else {
                        wp_enqueue_script( 'can-single-listing-map', 'https://maps.googleapis.com/maps/api/js?key='.$maps_api_key, array(), CIRCARTSNET_VERSION, true);
                    }
                }

                $icons_size = circartsnet_get_option('leaflet_icons_size', '43x47');
                $icons_anchor = circartsnet_get_option('leaflet_icons_anchor', '18x47');

                $localize_vars = array(
                    'use_map_from' => circartsnet_get_option('use_map_from', 'leaflet'),
                    'grid_view_txt' => circartsnet_get_option('grid_view_txt'),
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'zoom' => circartsnet_get_option('maps_zoom_level', 5),
                    'map_type' => circartsnet_get_option('maps_type'),
                    'leaflet_styles' => circartsnet_get_leaflet_provider(circartsnet_get_option('leaflet_style')),
                    'maps_icon_url' => circartsnet_get_option('maps_location_image', CIRCARTSNET_URL.'/assets/images/pin-location.png'),
                    'icons_size' => explode("x", $icons_size),
                    'icons_anchor' => explode("x", $icons_anchor),
                    'maps_styles' => stripcslashes(circartsnet_get_option('maps_styles')),
                );

                wp_enqueue_script( 'can-location-js', CIRCARTSNET_URL . '/assets/js/location.js', array('jquery'), CIRCARTSNET_VERSION, true);
                wp_localize_script( 'can-location-js', 'circartsnet_location_settings', $localize_vars );

				$template = CIRCARTSNET_PATH . '/templates/content-single/location.php';
			} else {
				return;
			}
		}

		// Default Section
		if($template == ''){
			$template = CIRCARTSNET_PATH . '/templates/content-single/section.php';
		}

		if (file_exists($template)) {
			include $template;
		}
	}

    function slick_data_attrs(){   
        $attrs = array(
            'adaptiveHeight' => true,
            'arrows' => true,
        );
        $attrs = apply_filters( 'circartsnet_single_listing_slick_attrs', $attrs );
        $data_attrs = 'data-slick='.wp_json_encode($attrs);
        return $data_attrs;
    }

    function grid_data_attrs(){
        $attrs = array(
            'cells' => 5,
            'align' => true,
        );
        $attrs = apply_filters( 'circartsnet_single_listing_grid_attrs', $attrs );
        $data_attrs = 'data-grid='.wp_json_encode($attrs);
        return $data_attrs;
    }

    function render_single_field($listing_id, $field, $cols = 'col-sm-4'){
    	$value = get_post_meta($listing_id, 'circartsnet_'.$field['key'], true);
    	if (!$value) {
    		return;
    	}
    	$template = CIRCARTSNET_PATH . '/templates/content-single/'.$field['type'].'.php';
    	if (file_exists($template)) {
    		include $template;
    	} else {
    		include CIRCARTSNET_PATH . '/templates/content-single/field.php';
    	}
    }

    function render_listing_sidebar(){
		global $post;
		$author_id = $post->post_author;
		$author_info = get_userdata($author_id);
		wp_enqueue_script( 'can-contact-seller', CIRCARTSNET_URL . '/assets/js/contact-seller.js', array('jquery'), CIRCARTSNET_VERSION, true);
    	include CIRCARTSNET_PATH . '/templates/sidebar/default.php';
		$p_sidebar = circartsnet_get_option('listing_page_sidebar', '');
		if ( is_active_sidebar( $p_sidebar )  ) {
			dynamic_sidebar( $p_sidebar );
		}
    }

    function render_listing_meta($listing_id){
    	$enabledFields = array('purpose', 'condition', 'model', 'listing_city', 'listing_country');
    	$inputFields = circartsnet_get_listing_fields();
    	echo '<div class="row can-meta">';
    		foreach ($inputFields as $field) {
    			if (in_array($field['key'], $enabledFields)) {
    				$this->render_single_field($listing_id, $field, 'col');
    			}
    		}
    	echo '</div>';
    }

    function render_action_buttons($listing_id){
        $actions = array();

        if (circartsnet_get_option('enable_compare', 'enable') == 'enable') {
            $full_url = wp_nonce_url( '#', 'compare');
            $actions['compare'] = array(
                'href' => $full_url,
                'title' => __( 'Add to compare', 'circular-arts-network' ),
                'icon' => 'bi bi-plus',
                'class' => 'can-compare-btn',
                'data-listing-id' => $listing_id,
            );
        }

    	$actions['link'] = array(
			'href' => get_the_permalink( $listing_id ),
			'title' => __( 'Details', 'circular-arts-network' ),
			'icon' => 'bi bi-box-arrow-up-right',
            'class' => 'can-link-btn',
		);

        $allowed_html = array(
            'a' => array(
                'href' => array(),
                'title' => array(),
                'class' => array(),
                'data-listing-id' => array()
            ),
            'i' => array(
                'class' => array()
            ),
        );

    	foreach ($actions as $key => $data) {
            $output = "<a ";

            foreach($data as $key => $value){
                $output .= $key.'="'.$value.'" ';
            }

            $output .= "><i class='".esc_attr( $data['icon'] )."'></i></a>";

            echo wp_kses( $output, $allowed_html );
    	}
    }

    function render_ribbon($listing_id){
        $ribbon_text = get_post_meta( $listing_id, 'circartsnet_listing_ribbon', true );
        if ($ribbon_text) {
    	   echo esc_html('<div class="can-ribbon">'.$ribbon_text.'</div>');
        }
    }

    function render_categories($listing_id){
    	$terms = wp_get_post_terms( $listing_id ,'circartsnet_listing_category' );
    		if (!empty($terms)) { ?>
    			<p class="cats d-none d-lg-block">
    				<?php
    				    foreach ( $terms as $term ) {
    				        $term_link = get_term_link( $term );
    				        if ( is_wp_error( $term_link ) ) {
    				            continue;
    				        }
    				        echo '<a class="can-category" href="' . esc_url( $term_link ) . '">' . esc_html($term->name) . ' </a></li>';
    				    }
    				?>
    			</p>
    	<?php }
    }
	function get_category_name($listing_id){
    	$terms = wp_get_post_terms( $listing_id ,'circartsnet_listing_category' );
    		if (!empty($terms)) { 
		    foreach ( $terms as $term ) {
			return strtolower($term->name);
		    }
		 }
	    }
	}

new CIRCARTSNET_Front_Templates();
?>
