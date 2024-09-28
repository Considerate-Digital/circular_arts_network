<?php

/**
 * WPML
 * registering and translating strings input by users
 */
if( ! function_exists('can_wpml_register') ) {
    function can_wpml_register($field_value, $domain, $field_name = '') {
        $field_name = ($field_name == '') ? $field_value : $field_name ;
        do_action( 'wpml_register_single_string', $domain, $field_name, $field_value );
    }
}

if( ! function_exists('can_wpml_translate') ) {
    function can_wpml_translate($field_value, $domain, $field_name = '', $language = '') {
        $field_name = ($field_name == '') ? $field_value : $field_name ;
        return apply_filters('wpml_translate_single_string', stripcslashes($field_value), $domain, $field_name, $language );
    }
}

/**
 * Return specific option from settings against key provided.
 * @since  1.0.0
 * @return string
 */
function can_get_option($key, $default = '') {
    $can_settings = get_option( 'can_all_settings' );
    if (isset($can_settings[$key]) && $can_settings[$key] != '') {
        return apply_filters( 'can_get_option_'.$key, $can_settings[$key], $default );
    } else {
        return $default;
    }
}

function can_load_basic_styles(){
    wp_enqueue_style('can-bs', CAN_URL."/assets/libs/css/bootstrap.css");
    wp_enqueue_style('can-icons', CAN_URL."/assets/libs/icons/bootstrap-icons.css");
    wp_enqueue_style('can-main', CAN_URL."/assets/css/main.css");

    ob_start();
        include_once CAN_PATH . '/assets/css/styles.php';
    $custom_css = ob_get_clean();
    wp_add_inline_style( 'can-main', $custom_css );
}

function can_can_user_access($section, $listing_id = ''){
    $accessibility = (isset($section['accessibility'])) ? $section['accessibility'] : 'public' ;
    switch ($accessibility) {
        case 'public':
            $is_accessible = true;
            break;

        case 'disable':
            $is_accessible = false;
            break;

        case 'admin':
            $is_accessible = false;
            if (is_user_logged_in() && current_user_can('administrator')) {
                $is_accessible = true;
            }
            break;

        case 'registered':
            $is_accessible = false;
            if (is_user_logged_in()) {
                $is_accessible = true;
            }
            break;

        case 'seller':
            $is_accessible = false;
            if (is_user_logged_in()) {
                $current_user_data = wp_get_current_user();
                if ($listing_id == '' || get_post_field( 'post_author', $listing_id ) == $current_user_data->ID || current_user_can('administrator')) {
                    $is_accessible = true;
                }
            }
            break;
        
        default:
            $is_accessible = true;
            break;
    }
    
    return apply_filters( 'can_can_user_access'.$section['key'], $is_accessible, $section, $listing_id );
}

/**
 * Get all the listing fields
 */
function can_get_listing_fields(){
    $saved_fields = get_option( 'can_listing_fields' );
    $inputFields  = array();
    if ($saved_fields != '' && is_array($saved_fields)) {
        $inputFields = $saved_fields;
    } else {
        include CAN_PATH.'/inc/arrays/listing-fields.php';
    }

    if(has_filter('can_all_listing_fields')) {
        $inputFields = apply_filters('can_all_listing_fields', $inputFields);
    }

    return $inputFields;
}

function can_is_default_section($section){
    $def_keys = array('description', 'gallery_images', 'location', 'tags');
    if (in_array($section['key'], $def_keys)) {
        return true;
    }
    return false;
}

function can_get_icons_list(){
    $icons = array();
        include CAN_PATH.'/inc/arrays/icons.php';
    return apply_filters( 'can_font_icons', $icons );
}

function can_get_column_classes($columns){
    switch ($columns) {
        case '1':
            $classes = 'col-sm-12';
            break;
        case '2':
            $classes = 'col-sm-6';
            break;
        case '3':
            $classes = 'col-sm-4';
            break;
        case '4':
            $classes = 'col-sm-3';
            break;
        
        default:
            $classes = 'col';
            break;
    }

    return apply_filters( 'can_column_classes', $classes, $columns );
}

/**
 * Renders the listing section for editing fields
 */
function can_render_listing_section($section, $listing_id = 0){

    if (!can_can_user_access($section, $listing_id)) {
        return;
    }

    if (!$listing_id) {
        global $post;
        $listing_id = (isset($post->ID) && $post->post_type == 'can_listing') ? $post->ID : 0 ;
    }

    switch ($section['key']) {
        case 'description':
            if (!is_admin()) {
                $listing_data = get_post( $listing_id ); ?>
                <div class="card mb-2">
                    <h5 class="card-header"><?php echo esc_attr( $section['title'] ); ?></h5>
                    <div class="card-body">
                        <?php do_action( 'can_before_section_edit_'.$section['key'] ); ?>
                            <input value="<?php echo ($listing_id) ? $listing_data->post_title : ''; ?>" id="listing_title" class="form-control mb-3" type="text" required placeholder="<?php _e( 'Listing Title', 'circular-arts-network' ); ?>" name="listing_title">
                            <?php wp_editor( ($listing_id) ? $listing_data->post_content : '', 'can-description', array(
                                'quicktags' => array( 'buttons' => 'strong,em,del,ul,ol,li,close' ),
                                'textarea_name' => 'description',
                                'editor_height' => 350,
				'media_buttons' => false
                            ) ); ?>

                        <?php do_action( 'can_after_section_edit_'.$section['key'] );
                        ?>
                    </div>
                </div>
            <?php }
            break;
	//TODO use render_listing_field as an example, but essentially render the categories here
	case 'category':
		$required = true;
		$categories = get_terms("can_listing_category", array( 'hide_empty'=> false));
?>
		
                <div class="card mb-2">
                    <h5 class="card-header"><?php echo esc_attr( $section['title'] ); ?></h5>
                    <div class="card-body">
		<div class="row mb-3">
                <label class="col-sm-4 col-form-label" for="can_listing_category">
                    <?php echo esc_attr(($section['title'])); ?>
                    <?php echo ($required) ? '<span title="'.__( 'Required', 'circular-arts-network' ).'" class="glyphicon glyphicon-asterisk"></span>' : '' ; ?>
                </label>
                <div class="col-sm-8">
                    <select name="can_listing_category" class="form-control form-control-sm">
                        <?php
                            foreach ($categories as $term) {
                                echo '<option value="'.$term->name.'">'.$term->name.'</option>';
                            }
                        ?>
                    </select>
                    <span class="help-block">Select a category</span>
                </div>
            </div>
            </div>
            </div>


		<?php
		break;
        case 'tags':
            if (!is_admin()) { ?>
                
            <?php }
            break;

        case 'gallery_images':

            if ($listing_id) {
                $savedImages = get_post_meta( $listing_id, 'can_'.$section['key'], true );
            }
            wp_enqueue_script('can-field-image', CAN_URL."/assets/fields/images.js", array( 'jquery' ));
            wp_enqueue_style('can-field-image', CAN_URL."/assets/fields/images.css");
            ?>
            <div class="card mb-2">
                <h5 class="card-header"><?php echo esc_attr( $section['title'] ); ?></h5>
                <div class="card-body">
                    <div class="can-images-field text-center" id="images-<?php echo esc_attr( $section['key'] ); ?>">
                        <button class="btn btn-primary btn-sm upload_image_button"
                            data-title="<?php _e( 'Select Images', 'circular-arts-network' ); ?>"
                            data-btntext="<?php _e( 'Add', 'circular-arts-network' ); ?>"
                            data-fieldname="<?php echo esc_attr( $section['key'] ); ?>"
                        >
                            <span class="dashicons dashicons-images-alt2"></span>
                            <?php _e( 'Select Images', 'circular-arts-network' ) ?>
                        </button>
                        
                        <div class="row thumbs-prev mt-3">
                            <?php if (isset($savedImages) && is_array($savedImages)) {
                                foreach ($savedImages as $image_id) {
                                    $image_url = wp_get_attachment_image_src( $image_id, 'thumbnail' );
                                    ?>
                                        <div class="col-sm-3">
                                            <div class="can-preview-image">
                                                <input type="hidden" name="<?php echo esc_attr( $section['key'] ); ?>[<?php echo esc_attr( $image_id ); ?>]" value="<?php echo esc_attr( $image_id ); ?>">
                                                <div class="can-image-wrap">
                                                    <img src="<?php echo esc_url( $image_url[0] ); ?>">
                                                </div>
                                                <div class="can-actions-wrap">
                                                    <a href="javascript:void(0)" class="btn remove-image btn-sm">
                                                        <i class="bi bi-trash3"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>

                
            <?php break;

        case 'location':

            if ($listing_id) {
                $savedLatitude = get_post_meta( $listing_id, 'can_listing_latitude', true );
                $savedLongitude = get_post_meta( $listing_id, 'can_listing_longitude', true );
            }

            if (can_get_option('use_map_from', 'leaflet') == 'leaflet') {
                wp_enqueue_style( 'can-leaflet-css', CAN_URL . '/assets/leaflet/leaflet.css');
                wp_enqueue_script( 'can-leaflet-js', CAN_URL . '/assets/leaflet/leaflet.js', array('jquery'));
                wp_enqueue_style( 'can-leaflet-geo-css', CAN_URL . '/assets/leaflet/Control.Geocoder.css');
                wp_enqueue_script( 'can-leaflet-geo-js', CAN_URL . '/assets/leaflet/Control.Geocoder.js');
            } else {
                $maps_api_key = can_get_option('maps_api_key');
                if (is_ssl()) {
                    wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key='.$maps_api_key.'&libraries=places' );
                } else {
                    wp_enqueue_script( 'google-maps', 'http://maps.googleapis.com/maps/api/js?key='.$maps_api_key.'&libraries=places' );
                }
            }
            wp_enqueue_script('can-field-location', CAN_URL."/assets/fields/location.js", array( 'jquery' ));
            $localize_vars = array(
                'use_map_from' => can_get_option('use_map_from', 'leaflet'),
                'def_lat' => isset($savedLatitude) ? $savedLatitude : can_get_option('default_map_lat', '55.8617'),
                'def_long' => isset($savedLongitude) ? $savedLongitude : can_get_option('default_map_long', '-4.2583'),
                'leaflet_styles' => can_get_leaflet_provider(1),
                'zoom_level' => can_get_option('maps_zoom_level', 5),
                'drag_icon' => can_get_option('maps_drag_image', CAN_URL.'/assets/images/pin-drag.png') ,
            );

            wp_localize_script( 'can-field-location', 'can_map_settings', $localize_vars );
            wp_enqueue_style('can-field-location', CAN_URL."/assets/fields/images.css");
            ?>
            <div class="card mb-2">
                <h5 class="card-header"><?php echo esc_attr( $section['title'] ); ?></h5>
                <div class="card-body">
                    <input type="hidden" class="can_listing_latitude" value="<?php echo isset($savedLatitude) ? $savedLatitude : ''; ?>" name="can_listing_latitude">
                    <input type="hidden" class="can_listing_longitude" value="<?php echo isset($savedLongitude) ? $savedLongitude : ''; ?>" name="can_listing_longitude">
                    <?php if (can_get_option('use_map_from', 'leaflet') == 'google_maps') { ?>
                    <input type="text" class="form-control" id="search-map" placeholder="<?php _e( 'Type to Search...', 'circular-arts-network' ); ?>">
                    <?php } ?>
                    <div id="map-canvas" style="height: 300px"></div>
                    <div id="position" class="alert alert-info mb-0 py-2 mt-2">
                        <?php
                            _e( 'Search the address on the search bar. ', 'circular-arts-network' );
                            _e( 'Drag the pin to the location on the map', 'circular-arts-network' );
                        ?>
                    </div>
                </div>
            </div>
		<script>
			setTimeout(() => {
				let postcode = document.getElementById("listing_zipcode");
				let location_search = document.querySelector(".leaflet-control-geocoder-form input");
				console.log(location_search);
				    postcode.addEventListener("blur", () => {
					console.log("setting location search");
					console.log(postcode.value);
					location_search.value = postcode.value;	
					location_search.dispatchEvent(new KeyboardEvent("keydown", {
						code: 'Enter',
					    key: 'Enter',
					    charCode: 13,
					    keyCode: 13,
					    view: window,
					    bubbles: true
					}));
					});
			    }, 3000);
		
		</script>

                
            <?php break;
        
        default:
            $inputFields = can_get_listing_fields(); ?>
            <div class="card mb-2">
                <h5 class="card-header"><?php echo esc_attr( $section['title'] ); ?></h5>
                <div class="card-body">
                    <?php
                        do_action( 'can_before_section_edit_'.$section['key'] );

                        foreach ($inputFields as $field) {
                            
                            if($field['tab'] == $section['key']){
                                can_render_listing_field($field, $listing_id);
                            }
                        }

                        do_action( 'can_after_section_edit_'.$section['key'] );
                    ?>
                </div>
            </div>
            <?php
            break;
    }
}

function can_render_search_field($field, $label = false, $icon = true){
    if ($label) {
        $label = $field['title'];
        $label = apply_filters( 'can_search_field_label', $label, $field );
        echo "<label>".esc_attr( $label )."</label>";
    }

    if ($icon && $field['type'] != 'price') {
        echo "<i class='".esc_attr( $field['icon'] )."'></i>";
    }

    $field_value = $field['default'];

    if (isset($_GET[$field['key']])) {
        $field_value = $_GET[$field['key']];
    }

    $html  = '';
    switch ($field['type']) {
        case 'price':
            $html = '<div class="can-price-search-wrap">';
                $html .= '<input type="text" class="can-input-field" name="'.esc_attr( $field['key'] ).'[min]" placeholder="'.__( 'Minimum', 'circular-arts-network' ).'">';
                $html .= '<span class="can-price-label">'.esc_attr( $field['title'] ).' <span class="p-symbol">('.can_get_currency_symbol().')</span></span>';
                $html .= '<input type="text" class="can-input-field" name="'.esc_attr( $field['key'] ).'[max]" placeholder="'.__( 'Maximum', 'circular-arts-network' ).'">';
            $html .= '</div>';
            break;

        case 'select':
            $html = '<select class="can-select-field" name='.esc_attr( $field['key'] ).'>';
                $options = (is_array($field['options'])) ? $field['options'] : explode("\n", $field['options']);
                foreach ($options as $name) {
                    $translated_label = can_wpml_translate($name, 'circular-arts-network-fields');
                    $html .= '<option value="'.$name.'" '.selected( $field_value, $name, false ).'>'.$translated_label.'</option>';
                }

            $html .= '</select>';
            break;
        
        default:
            $html =  "<input class='can-input-field' type=".esc_attr( $field['type'] )." name=".esc_attr( $field['key'] )." />";
            break;
    }

    return apply_filters( 'can_search_field_html', $html, $field );
}

/**
 * Renders the listing form fields
 */
function can_render_listing_field($field, $listing_id = 0){

    if (!can_can_user_access($field, $listing_id)) {
        return;
    }
    
    $field_type = $field['type'];
    $field_id = $field['key'];
    $field_name = 'can_data['.$field_id.']';
    $field_title = $field['title'];
    $field_help = $field['help'];
    $field_value = $field['default'];
    $required = (isset($field['required']) && $field['required'] == 'true' ) ? true : false;

    if (!$listing_id) {
        global $post;
        $listing_id = (isset($post->ID)) ? $post->ID : 0 ;
    }

    if ($listing_id) {
        $field_value = get_post_meta( $listing_id, 'can_'.$field_id, true );
    }

    switch ($field_type) {


        case 'checkboxes':
            ?>

            <div class="can-checkboxes-wrap">
                <p class="fw-bold"><?php echo can_wpml_translate($field_title, 'circular-arts-network-fields'); ?></p>
                <div class="row">
                    <?php foreach ($field['options'] as $key => $option) {
                        $translated_label = can_wpml_translate($option, 'circular-arts-network-fields');
                        $cb_id = 'can-'.$field_id.'-'.$key;
                        $value = (isset($field_value[$option])) ? $field_value[$option] : ''; ?>
                        <div class="col-sm-6">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    value="1"
                                    <?php checked( $value, '1', true); ?>
                                    name="<?php echo esc_attr( $field_name ); ?>[<?php echo esc_attr( $translated_label ); ?>]"
                                    id="<?php echo esc_attr( $cb_id ); ?>">
                                <label class="form-check-label" for="<?php echo esc_attr( $cb_id ); ?>">
                                    <?php echo esc_attr( $translated_label ); ?>
                                </label>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

                
            <?php break;


        case 'select':
            ?>

            <div class="row mb-3">
                <label class="col-sm-4 col-form-label" for="<?php echo esc_attr( $field_id ); ?>">
                    <?php echo can_wpml_translate($field_title, 'circular-arts-network-fields'); ?>
                    <?php echo ($required) ? '<span title="'.__( 'Required', 'circular-arts-network' ).'" class="glyphicon glyphicon-asterisk"></span>' : '' ; ?>
                </label>
                <div class="col-sm-8">
                    <select name="<?php echo esc_attr( $field_name ); ?>" <?php echo esc_attr( $field_id ); ?> class="form-control form-control-sm">
                        <?php
                            $options = (is_array($field['options'])) ? $field['options'] : explode("\n", $field['options']);
                            foreach ($options as $name) {
                                $translated_label = can_wpml_translate($name, 'circular-arts-network-fields');
                                echo '<option value="'.$name.'" '.selected( $field_value, $name, false ).'>'.$translated_label.'</option>';
                            }
                        ?>
                    </select>
                    <span class="help-block"><?php echo esc_attr( $field_help ); ?></span>
                </div>
            </div>

                
            <?php break;


        case 'textarea':
            ?>
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label" for="<?php echo esc_attr( $field_id ); ?>">
                    <?php echo can_wpml_translate($field_title, 'circular-arts-network-fields'); ?>
                    <?php echo ($required) ? '<span title="'.__( 'Required', 'circular-arts-network' ).'" class="glyphicon glyphicon-asterisk"></span>' : '' ; ?>
                </label>
                <div class="col-sm-8">
                   <textarea
                        name="<?php echo esc_attr( $field_name ); ?>"
                        class="form-control form-control-sm"
                        id="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_attr( $field_value ); ?></textarea> 
                    <span class="help-block"><?php echo esc_attr( $field_help ); ?></span>
                </div>
            </div>
  
            <?php break;


        case 'price':
            $before_value   =   get_post_meta( $listing_id, 'can_'.$field_id.'_before', true );
            $after_value    =   get_post_meta( $listing_id, 'can_'.$field_id.'_after', true );
	    if (!$field_value) {
		    $field_value = 0;
	   }
            ?>
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label" for="<?php echo esc_attr( $field_id ); ?>">
                    <?php echo can_wpml_translate($field_title, 'circular-arts-network-fields'); ?>
                    <?php echo ($required) ? '<span title="'.__( 'Required', 'circular-arts-network' ).'" class="glyphicon glyphicon-asterisk"></span>' : '' ; ?>
                </label>
                
                <div class="col-sm-8">
                   <input
			pattern="^\d*(\.\d{0,2})?$"
                        name="<?php echo esc_attr( $field_name ); ?>"
                        class="form-control form-control-sm"
                        id="<?php echo esc_attr( $field_id ); ?>"
                        value="<?php echo esc_attr( $field_value ); ?>"> 
                    <span class="help-block"><?php echo esc_attr( $field_help ); ?></span>
                </div>
                           </div>
  
            <?php break;


        case 'number':
            ?>
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label" for="<?php echo esc_attr( $field_id ); ?>">
                    <?php echo can_wpml_translate($field_title, 'circular-arts-network-fields'); ?>
                    <?php echo ($required) ? '<span title="'.__( 'Required', 'circular-arts-network' ).'" class="glyphicon glyphicon-asterisk"></span>' : '' ; ?>
                </label>
                <div class="col-sm-8">
                   <input
                        min="<?php echo esc_attr( $field['min_value'] ); ?>"
                        max="<?php echo esc_attr( $field['max_value'] ); ?>"
                        type="<?php echo esc_attr( $field_type ); ?>"
                        name="<?php echo esc_attr( $field_name ); ?>"
                        class="form-control form-control-sm"
                        id="<?php echo esc_attr( $field_id ); ?>"
                        value="<?php echo esc_attr( $field_value ); ?>"> 
                    <span class="help-block"><?php echo esc_attr( $field_help ); ?></span>
                </div>
            </div>
  
            <?php break;

        
        default: ?>
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label" for="<?php echo esc_attr( $field_id ); ?>">
                    <?php echo can_wpml_translate($field_title, 'circular-arts-network-fields'); ?>
                    <?php echo ($required) ? '<span title="'.__( 'Required', 'circular-arts-network' ).'" class="glyphicon glyphicon-asterisk"></span>' : '' ; ?>
                </label>
                <div class="col-sm-8">
                   <input type="<?php echo esc_attr( $field_type ); ?>" name="<?php echo esc_attr( $field_name ); ?>" class="form-control form-control-sm" id="<?php echo esc_attr( $field_id ); ?>" value="<?php echo esc_attr( $field_value ); ?>"> 
                    <span class="help-block"><?php echo esc_attr( $field_help ); ?></span>
                </div>
            </div>
            <?php break;
    }
}

function can_get_field_value($listing_id, $field, $value = ''){

    if (!$value) {
        $value = get_post_meta( $listing_id, 'can_'.$field['key'], true );
    }

    $value = can_wpml_translate($value, 'circular-arts-network-fields');

    if (isset($field['type']) && $field['type'] == 'date') {
        $format = can_get_option('date_format', 'd-m-Y');
        $value = date($format, strtotime($value));
    }

    if (isset($field['type']) && $field['type'] == 'price') {
        $value = can_get_listing_price($value);

        $before_value   =   get_post_meta( $listing_id, 'can_'.$field['key'].'_before', true );
        $after_value    =   get_post_meta( $listing_id, 'can_'.$field['key'].'_after', true );
        if ($before_value) {
            $value = "<span class='can-before-text'>{$before_value}</span> ".$value;
        }
        if ($after_value) {
            $value = $value." <span class='can-after-text'>{$after_value}</span>";
        }
    }

    return apply_filters( 'can_listing_field_value', $value, $field, $listing_id );
}

function can_get_section_title($tabData){
    $title = __( $tabData['title'], 'circular-arts-network' );
    $tab_key = $tabData['key'];
    $icon = '';

    if (isset($tabData['icon']) && $tabData['icon'] != '') {
        if (strpos($tabData['icon'], "http://") !== false || strpos($tabData['icon'], "https://") !== false) {
            $icon = '<img class="can-sec-icon" src= "'.esc_url( $tabData['icon'] ).'">';
        } else {
            $icon = '<i class="'.esc_attr( $tabData['icon'] ).'"></i>';
        }
    }

    $icon = apply_filters( 'can_listing_section_title_icon', $icon,  $tabData);

    $wrap = apply_filters( 'can_listing_section_title_wrap', 'h2', $tab_key );
    return "<$wrap class='title'>$icon ".stripcslashes($title)."</$wrap>";
}

function can_count_user_listings($user_id, $status = 'all'){
    $args  = array(
        'post_type' =>'can_listing',
        'author' => $user_id,
    );

    if ($status != 'all') {
        $args['post_status'] = $status;
    }

    $listings = get_posts($args);
    return count($listings);
}

/**
 * Format the price with a currency symbol.
 *
 * @param float $price
 * @param array $args (default: array())
 * @return string
 */
function can_get_listing_price( $price, $args = array() ) {
    $price_digits = $price;
    extract( apply_filters( 'can_price_args', wp_parse_args( $args, array(
        'currency'           => can_get_option('currency', 'GBP'),
        'decimal_separator'  => can_get_price_decimal_separator(),
        'thousand_separator' => can_get_price_thousand_separator(),
        'decimals'           => can_get_price_decimals(),
        'price_format'       => can_get_price_format()
    ) ) ) );
    $negative        = $price < 0;
    $price           = apply_filters( 'raw_can_price', floatval( $negative ? $price * -1 : $price ) );
    $price           = apply_filters( 'formatted_can_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

    if ( apply_filters( 'can_price_trim_zeros', false ) && $decimals > 0 ) {
        $price = wc_trim_zeros( $price );
    }

    $formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, '<span class="can-currency-symbol">' . can_get_currency_symbol( $currency ) . '</span>', $price );
    $return          = '<span class="can-price-amount">' . $formatted_price . '</span>';

    return apply_filters( 'can_property_price', $return, $price, $args, $price_digits );
}

/**
 * Get full list of currency codes.
 *
 * @return array
 */
function can_get_all_currencies() {
    return array_unique(
        apply_filters( 'can_all_currencies',
            array(
                'AED' => __( 'United Arab Emirates dirham', 'circular-arts-network' ),
                'AFN' => __( 'Afghan afghani', 'circular-arts-network' ),
                'ALL' => __( 'Albanian lek', 'circular-arts-network' ),
                'AMD' => __( 'Armenian dram', 'circular-arts-network' ),
                'ANG' => __( 'Netherlands Antillean guilder', 'circular-arts-network' ),
                'AOA' => __( 'Angolan kwanza', 'circular-arts-network' ),
                'ARS' => __( 'Argentine peso', 'circular-arts-network' ),
                'AUD' => __( 'Australian dollar', 'circular-arts-network' ),
                'AWG' => __( 'Aruban florin', 'circular-arts-network' ),
                'AZN' => __( 'Azerbaijani manat', 'circular-arts-network' ),
                'BAM' => __( 'Bosnia and Herzegovina convertible mark', 'circular-arts-network' ),
                'BBD' => __( 'Barbadian dollar', 'circular-arts-network' ),
                'BDT' => __( 'Bangladeshi taka', 'circular-arts-network' ),
                'BGN' => __( 'Bulgarian lev', 'circular-arts-network' ),
                'BHD' => __( 'Bahraini dinar', 'circular-arts-network' ),
                'BIF' => __( 'Burundian franc', 'circular-arts-network' ),
                'BMD' => __( 'Bermudian dollar', 'circular-arts-network' ),
                'BND' => __( 'Brunei dollar', 'circular-arts-network' ),
                'BOB' => __( 'Bolivian boliviano', 'circular-arts-network' ),
                'BRL' => __( 'Brazilian real', 'circular-arts-network' ),
                'BSD' => __( 'Bahamian dollar', 'circular-arts-network' ),
                'BTC' => __( 'Bitcoin', 'circular-arts-network' ),
                'BTN' => __( 'Bhutanese ngultrum', 'circular-arts-network' ),
                'BWP' => __( 'Botswana pula', 'circular-arts-network' ),
                'BYR' => __( 'Belarusian ruble', 'circular-arts-network' ),
                'BZD' => __( 'Belize dollar', 'circular-arts-network' ),
                'CAD' => __( 'Canadian dollar', 'circular-arts-network' ),
                'CDF' => __( 'Congolese franc', 'circular-arts-network' ),
                'CHF' => __( 'Swiss franc', 'circular-arts-network' ),
                'CLP' => __( 'Chilean peso', 'circular-arts-network' ),
                'CNY' => __( 'Chinese yuan', 'circular-arts-network' ),
                'COP' => __( 'Colombian peso', 'circular-arts-network' ),
                'CRC' => __( 'Costa Rican col&oacute;n', 'circular-arts-network' ),
                'CUC' => __( 'Cuban convertible peso', 'circular-arts-network' ),
                'CUP' => __( 'Cuban peso', 'circular-arts-network' ),
                'CVE' => __( 'Cape Verdean escudo', 'circular-arts-network' ),
                'CZK' => __( 'Czech koruna', 'circular-arts-network' ),
                'DJF' => __( 'Djiboutian franc', 'circular-arts-network' ),
                'DKK' => __( 'Danish krone', 'circular-arts-network' ),
                'DOP' => __( 'Dominican peso', 'circular-arts-network' ),
                'DZD' => __( 'Algerian dinar', 'circular-arts-network' ),
                'EGP' => __( 'Egyptian pound', 'circular-arts-network' ),
                'ERN' => __( 'Eritrean nakfa', 'circular-arts-network' ),
                'ETB' => __( 'Ethiopian birr', 'circular-arts-network' ),
                'EUR' => __( 'Euro', 'circular-arts-network' ),
                'FJD' => __( 'Fijian dollar', 'circular-arts-network' ),
                'FKP' => __( 'Falkland Islands pound', 'circular-arts-network' ),
                'GBP' => __( 'Pound sterling', 'circular-arts-network' ),
                'GEL' => __( 'Georgian lari', 'circular-arts-network' ),
                'GGP' => __( 'Guernsey pound', 'circular-arts-network' ),
                'GHS' => __( 'Ghana cedi', 'circular-arts-network' ),
                'GIP' => __( 'Gibraltar pound', 'circular-arts-network' ),
                'GMD' => __( 'Gambian dalasi', 'circular-arts-network' ),
                'GNF' => __( 'Guinean franc', 'circular-arts-network' ),
                'GTQ' => __( 'Guatemalan quetzal', 'circular-arts-network' ),
                'GYD' => __( 'Guyanese dollar', 'circular-arts-network' ),
                'HKD' => __( 'Hong Kong dollar', 'circular-arts-network' ),
                'HNL' => __( 'Honduran lempira', 'circular-arts-network' ),
                'HRK' => __( 'Croatian kuna', 'circular-arts-network' ),
                'HTG' => __( 'Haitian gourde', 'circular-arts-network' ),
                'HUF' => __( 'Hungarian forint', 'circular-arts-network' ),
                'IDR' => __( 'Indonesian rupiah', 'circular-arts-network' ),
                'ILS' => __( 'Israeli new shekel', 'circular-arts-network' ),
                'IMP' => __( 'Manx pound', 'circular-arts-network' ),
                'INR' => __( 'Indian rupee', 'circular-arts-network' ),
                'IQD' => __( 'Iraqi dinar', 'circular-arts-network' ),
                'IRR' => __( 'Iranian rial', 'circular-arts-network' ),
                'ISK' => __( 'Icelandic kr&oacute;na', 'circular-arts-network' ),
                'JEP' => __( 'Jersey pound', 'circular-arts-network' ),
                'JMD' => __( 'Jamaican dollar', 'circular-arts-network' ),
                'JOD' => __( 'Jordanian dinar', 'circular-arts-network' ),
                'JPY' => __( 'Japanese yen', 'circular-arts-network' ),
                'KES' => __( 'Kenyan shilling', 'circular-arts-network' ),
                'KGS' => __( 'Kyrgyzstani som', 'circular-arts-network' ),
                'KHR' => __( 'Cambodian riel', 'circular-arts-network' ),
                'KMF' => __( 'Comorian franc', 'circular-arts-network' ),
                'KPW' => __( 'North Korean won', 'circular-arts-network' ),
                'KRW' => __( 'South Korean won', 'circular-arts-network' ),
                'KWD' => __( 'Kuwaiti dinar', 'circular-arts-network' ),
                'KYD' => __( 'Cayman Islands dollar', 'circular-arts-network' ),
                'KZT' => __( 'Kazakhstani tenge', 'circular-arts-network' ),
                'LAK' => __( 'Lao kip', 'circular-arts-network' ),
                'LBP' => __( 'Lebanese pound', 'circular-arts-network' ),
                'LKR' => __( 'Sri Lankan rupee', 'circular-arts-network' ),
                'LRD' => __( 'Liberian dollar', 'circular-arts-network' ),
                'LSL' => __( 'Lesotho loti', 'circular-arts-network' ),
                'LYD' => __( 'Libyan dinar', 'circular-arts-network' ),
                'MAD' => __( 'Moroccan dirham', 'circular-arts-network' ),
                'MDL' => __( 'Moldovan leu', 'circular-arts-network' ),
                'MGA' => __( 'Malagasy ariary', 'circular-arts-network' ),
                'MKD' => __( 'Macedonian denar', 'circular-arts-network' ),
                'MMK' => __( 'Burmese kyat', 'circular-arts-network' ),
                'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', 'circular-arts-network' ),
                'MOP' => __( 'Macanese pataca', 'circular-arts-network' ),
                'MRO' => __( 'Mauritanian ouguiya', 'circular-arts-network' ),
                'MUR' => __( 'Mauritian rupee', 'circular-arts-network' ),
                'MVR' => __( 'Maldivian rufiyaa', 'circular-arts-network' ),
                'MWK' => __( 'Malawian kwacha', 'circular-arts-network' ),
                'MXN' => __( 'Mexican peso', 'circular-arts-network' ),
                'MYR' => __( 'Malaysian ringgit', 'circular-arts-network' ),
                'MZN' => __( 'Mozambican metical', 'circular-arts-network' ),
                'NAD' => __( 'Namibian dollar', 'circular-arts-network' ),
                'NGN' => __( 'Nigerian naira', 'circular-arts-network' ),
                'NIO' => __( 'Nicaraguan c&oacute;rdoba', 'circular-arts-network' ),
                'NOK' => __( 'Norwegian krone', 'circular-arts-network' ),
                'NPR' => __( 'Nepalese rupee', 'circular-arts-network' ),
                'NZD' => __( 'New Zealand dollar', 'circular-arts-network' ),
                'OMR' => __( 'Omani rial', 'circular-arts-network' ),
                'PAB' => __( 'Panamanian balboa', 'circular-arts-network' ),
                'PEN' => __( 'Peruvian nuevo sol', 'circular-arts-network' ),
                'PGK' => __( 'Papua New Guinean kina', 'circular-arts-network' ),
                'PHP' => __( 'Philippine peso', 'circular-arts-network' ),
                'PKR' => __( 'Pakistani rupee', 'circular-arts-network' ),
                'PLN' => __( 'Polish z&#x142;oty', 'circular-arts-network' ),
                'PRB' => __( 'Transnistrian ruble', 'circular-arts-network' ),
                'PYG' => __( 'Paraguayan guaran&iacute;', 'circular-arts-network' ),
                'QAR' => __( 'Qatari riyal', 'circular-arts-network' ),
                'RON' => __( 'Romanian leu', 'circular-arts-network' ),
                'RSD' => __( 'Serbian dinar', 'circular-arts-network' ),
                'RUB' => __( 'Russian ruble', 'circular-arts-network' ),
                'RWF' => __( 'Rwandan franc', 'circular-arts-network' ),
                'SAR' => __( 'Saudi riyal', 'circular-arts-network' ),
                'SBD' => __( 'Solomon Islands dollar', 'circular-arts-network' ),
                'SCR' => __( 'Seychellois rupee', 'circular-arts-network' ),
                'SDG' => __( 'Sudanese pound', 'circular-arts-network' ),
                'SEK' => __( 'Swedish krona', 'circular-arts-network' ),
                'SGD' => __( 'Singapore dollar', 'circular-arts-network' ),
                'SHP' => __( 'Saint Helena pound', 'circular-arts-network' ),
                'SLL' => __( 'Sierra Leonean leone', 'circular-arts-network' ),
                'SOS' => __( 'Somali shilling', 'circular-arts-network' ),
                'SRD' => __( 'Surinamese dollar', 'circular-arts-network' ),
                'SSP' => __( 'South Sudanese pound', 'circular-arts-network' ),
                'STD' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'circular-arts-network' ),
                'SYP' => __( 'Syrian pound', 'circular-arts-network' ),
                'SZL' => __( 'Swazi lilangeni', 'circular-arts-network' ),
                'THB' => __( 'Thai baht', 'circular-arts-network' ),
                'TJS' => __( 'Tajikistani somoni', 'circular-arts-network' ),
                'TMT' => __( 'Turkmenistan manat', 'circular-arts-network' ),
                'TND' => __( 'Tunisian dinar', 'circular-arts-network' ),
                'TOP' => __( 'Tongan pa&#x2bb;anga', 'circular-arts-network' ),
                'TRY' => __( 'Turkish lira', 'circular-arts-network' ),
                'TTD' => __( 'Trinidad and Tobago dollar', 'circular-arts-network' ),
                'TWD' => __( 'New Taiwan dollar', 'circular-arts-network' ),
                'TZS' => __( 'Tanzanian shilling', 'circular-arts-network' ),
                'UAH' => __( 'Ukrainian hryvnia', 'circular-arts-network' ),
                'UGX' => __( 'Ugandan shilling', 'circular-arts-network' ),
                'USD' => __( 'United States dollar', 'circular-arts-network' ),
                'UYU' => __( 'Uruguayan peso', 'circular-arts-network' ),
                'UZS' => __( 'Uzbekistani som', 'circular-arts-network' ),
                'VEF' => __( 'Venezuelan bol&iacute;var', 'circular-arts-network' ),
                'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', 'circular-arts-network' ),
                'VUV' => __( 'Vanuatu vatu', 'circular-arts-network' ),
                'WST' => __( 'Samoan t&#x101;l&#x101;', 'circular-arts-network' ),
                'XAF' => __( 'Central African CFA franc', 'circular-arts-network' ),
                'XCD' => __( 'East Caribbean dollar', 'circular-arts-network' ),
                'XOF' => __( 'West African CFA franc', 'circular-arts-network' ),
                'XPF' => __( 'CFP franc', 'circular-arts-network' ),
                'YER' => __( 'Yemeni rial', 'circular-arts-network' ),
                'ZAR' => __( 'South African rand', 'circular-arts-network' ),
                'ZMW' => __( 'Zambian kwacha', 'circular-arts-network' ),
            )
        )
    );
}

/**
 * Get Currency symbol.
 *
 * @param string $currency (default: '')
 * @return string
 */
function can_get_currency_symbol( $currency = '' ) {
    if ( ! $currency ) {
        $currency = can_get_option('currency', 'GBP');
    }

    $symbols = apply_filters( 'can_all_currency_symbols', array(
        'AED' => '&#x62f;.&#x625;',
        'AFN' => '&#x60b;',
        'ALL' => 'L',
        'AMD' => 'AMD',
        'ANG' => '&fnof;',
        'AOA' => 'Kz',
        'ARS' => '&#36;',
        'AUD' => '&#36;',
        'AWG' => '&fnof;',
        'AZN' => 'AZN',
        'BAM' => 'KM',
        'BBD' => '&#36;',
        'BDT' => '&#2547;&nbsp;',
        'BGN' => '&#1083;&#1074;.',
        'BHD' => '.&#x62f;.&#x628;',
        'BIF' => 'Fr',
        'BMD' => '&#36;',
        'BND' => '&#36;',
        'BOB' => 'Bs.',
        'BRL' => '&#82;&#36;',
        'BSD' => '&#36;',
        'BTC' => '&#3647;',
        'BTN' => 'Nu.',
        'BWP' => 'P',
        'BYR' => 'Br',
        'BZD' => '&#36;',
        'CAD' => '&#36;',
        'CDF' => 'Fr',
        'CHF' => '&#67;&#72;&#70;',
        'CLP' => '&#36;',
        'CNY' => '&yen;',
        'COP' => '&#36;',
        'CRC' => '&#x20a1;',
        'CUC' => '&#36;',
        'CUP' => '&#36;',
        'CVE' => '&#36;',
        'CZK' => '&#75;&#269;',
        'DJF' => 'Fr',
        'DKK' => 'DKK',
        'DOP' => 'RD&#36;',
        'DZD' => '&#x62f;.&#x62c;',
        'EGP' => 'EGP',
        'ERN' => 'Nfk',
        'ETB' => 'Br',
        'EUR' => '&euro;',
        'FJD' => '&#36;',
        'FKP' => '&pound;',
        'GBP' => '&pound;',
        'GEL' => '&#x10da;',
        'GGP' => '&pound;',
        'GHS' => '&#x20b5;',
        'GIP' => '&pound;',
        'GMD' => 'D',
        'GNF' => 'Fr',
        'GTQ' => 'Q',
        'GYD' => '&#36;',
        'HKD' => '&#36;',
        'HNL' => 'L',
        'HRK' => 'Kn',
        'HTG' => 'G',
        'HUF' => '&#70;&#116;',
        'IDR' => 'Rp',
        'ILS' => '&#8362;',
        'IMP' => '&pound;',
        'INR' => '&#8377;',
        'IQD' => '&#x639;.&#x62f;',
        'IRR' => '&#xfdfc;',
        'ISK' => 'kr.',
        'JEP' => '&pound;',
        'JMD' => '&#36;',
        'JOD' => '&#x62f;.&#x627;',
        'JPY' => '&yen;',
        'KES' => 'KSh',
        'KGS' => '&#x441;&#x43e;&#x43c;',
        'KHR' => '&#x17db;',
        'KMF' => 'Fr',
        'KPW' => '&#x20a9;',
        'KRW' => '&#8361;',
        'KWD' => '&#x62f;.&#x643;',
        'KYD' => '&#36;',
        'KZT' => 'KZT',
        'LAK' => '&#8365;',
        'LBP' => '&#x644;.&#x644;',
        'LKR' => '&#xdbb;&#xdd4;',
        'LRD' => '&#36;',
        'LSL' => 'L',
        'LYD' => '&#x644;.&#x62f;',
        'MAD' => '&#x62f;. &#x645;.',
        'MAD' => '&#x62f;.&#x645;.',
        'MDL' => 'L',
        'MGA' => 'Ar',
        'MKD' => '&#x434;&#x435;&#x43d;',
        'MMK' => 'Ks',
        'MNT' => '&#x20ae;',
        'MOP' => 'P',
        'MRO' => 'UM',
        'MUR' => '&#x20a8;',
        'MVR' => '.&#x783;',
        'MWK' => 'MK',
        'MXN' => '&#36;',
        'MYR' => '&#82;&#77;',
        'MZN' => 'MT',
        'NAD' => '&#36;',
        'NGN' => '&#8358;',
        'NIO' => 'C&#36;',
        'NOK' => '&#107;&#114;',
        'NPR' => '&#8360;',
        'NZD' => '&#36;',
        'OMR' => '&#x631;.&#x639;.',
        'PAB' => 'B/.',
        'PEN' => 'S/.',
        'PGK' => 'K',
        'PHP' => '&#8369;',
        'PKR' => '&#8360;',
        'PLN' => '&#122;&#322;',
        'PRB' => '&#x440;.',
        'PYG' => '&#8370;',
        'QAR' => '&#x631;.&#x642;',
        'RMB' => '&yen;',
        'RON' => 'lei',
        'RSD' => '&#x434;&#x438;&#x43d;.',
        'RUB' => '&#8381;',
        'RWF' => 'Fr',
        'SAR' => '&#x631;.&#x633;',
        'SBD' => '&#36;',
        'SCR' => '&#x20a8;',
        'SDG' => '&#x62c;.&#x633;.',
        'SEK' => '&#107;&#114;',
        'SGD' => '&#36;',
        'SHP' => '&pound;',
        'SLL' => 'Le',
        'SOS' => 'Sh',
        'SRD' => '&#36;',
        'SSP' => '&pound;',
        'STD' => 'Db',
        'SYP' => '&#x644;.&#x633;',
        'SZL' => 'L',
        'THB' => '&#3647;',
        'TJS' => '&#x405;&#x41c;',
        'TMT' => 'm',
        'TND' => '&#x62f;.&#x62a;',
        'TOP' => 'T&#36;',
        'TRY' => '&#8378;',
        'TTD' => '&#36;',
        'TWD' => '&#78;&#84;&#36;',
        'TZS' => 'Sh',
        'UAH' => '&#8372;',
        'UGX' => 'UGX',
        'USD' => '&#36;',
        'UYU' => '&#36;',
        'UZS' => 'UZS',
        'VEF' => 'Bs F',
        'VND' => '&#8363;',
        'VUV' => 'Vt',
        'WST' => 'T',
        'XAF' => 'Fr',
        'XCD' => '&#36;',
        'XOF' => 'Fr',
        'XPF' => 'Fr',
        'YER' => '&#xfdfc;',
        'ZAR' => '&#82;',
        'ZMW' => 'ZK',
    ) );

    $currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

    return apply_filters( 'can_currency_symbol', $currency_symbol, $currency );
}

/**
 * Get the price format depending on the currency position.
 *
 * @return string
 */
function can_get_price_format() {
    $currency_pos = can_get_option( 'currency_position', 'left' );
    $format = '%1$s%2$s';

    switch ( $currency_pos ) {
        case 'left' :
            $format = '%1$s%2$s';
        break;
        case 'right' :
            $format = '%2$s%1$s';
        break;
        case 'left_space' :
            $format = '%1$s&nbsp;%2$s';
        break;
        case 'right_space' :
            $format = '%2$s&nbsp;%1$s';
        break;
    }

    return apply_filters( 'can_price_format', $format, $currency_pos );
}

/**
 * Return the thousand separator for prices.
 * @since  4.1
 * @return string
 */
function can_get_price_thousand_separator() {
    $separator = stripslashes( can_get_option( 'thousand_separator' ) );
    return $separator;
}

/**
 * Return the decimal separator for prices.
 * @since  4.1
 * @return string
 */
function can_get_price_decimal_separator() {
    $separator = stripslashes( can_get_option( 'decimal_separator' ) );
    return $separator ? $separator : '.';
}

/**
 * Return the number of decimals after the decimal point.
 * @since  4.1
 * @return int
 */
function can_get_price_decimals() {
    return absint( can_get_option( 'decimal_points', 2 ) );
}


/**
 * Getting Leaflet map styles and attribution
 * @param  [type] $map_id [description]
 * @since 1.0.0
 */
function can_get_leaflet_provider($map_id){

    switch ($map_id) {
        case '1':
            $provider = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
            break;
            
        case '2':
            $provider = 'http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png';
            break;

        case '3':
            $provider = 'https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png';
            break;

        case '4':
            $provider = 'https://tile.osm.ch/switzerland/{z}/{x}/{y}.png';
            break;

        case '5':
            $provider = 'https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png';
            break;

        case '6':
            $provider = 'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png';
            break;

        case '7':
            $provider = 'https://tile.openstreetmap.bzh/br/{z}/{x}/{y}.png';
            break;

        case '8':
            $provider = 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png';
            break;

        case '9':
            $provider = 'https://{s}.tile.openstreetmap.se/hydda/full/{z}/{x}/{y}.png';
            break;

        case '10':
            $provider = 'https://{s}.tile.openstreetmap.se/hydda/base/{z}/{x}/{y}.png';
            break;

        case '11':
            $provider = 'https://stamen-tiles-{s}.a.ssl.fastly.net/toner/{z}/{x}/{y}{r}.png';
            break;

        case '12':
            $provider = 'https://stamen-tiles-{s}.a.ssl.fastly.net/toner-background/{z}/{x}/{y}{r}.png';
            break;

        case '13':
            $provider = 'https://stamen-tiles-{s}.a.ssl.fastly.net/toner-lite/{z}/{x}/{y}{r}.png';
            break;

        case '14':
            $provider = 'https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.png';
            break;

        case '15':
            $provider = 'https://stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}{r}.png';
            break;

        case '16':
            $provider = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}';
            break;

        case '17':
            $provider = 'https://server.arcgisonline.com/ArcGIS/rest/services/Specialty/DeLorme_World_Base_Map/MapServer/tile/{z}/{y}/{x}';
            break;

        case '18':
            $provider = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}';
            break;

        case '19':
            $provider = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';
            break;

        case '20':
            $provider = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Shaded_Relief/MapServer/tile/{z}/{y}/{x}';
            break;

        case '21':
            $provider = 'https://server.arcgisonline.com/ArcGIS/rest/services/NatGeo_World_Map/MapServer/tile/{z}/{y}/{x}';
            break;

        case '22':
            $provider = 'https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}';
            break;

        case '23':
            $provider = 'https://stamen-tiles-{s}.a.ssl.fastly.net/terrain-background/{z}/{x}/{y}{r}.png';
            break;
        
        default:
            $provider = 'https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png';
            break;
    }

    $resp = array(
        'provider' => $provider
    );

    return apply_filters( 'can_leaflet_provider', $resp, $map_id );
}

function can_get_search_query($data){
    $ppp = can_get_option('listings_per_results_page', 10);

    $args = array(
        'post_type' =>  'can_listing',
        'post_status' => 'publish',
        'posts_per_page' => $ppp
    );
    if (isset($data['offset'])) {
        $args['offset'] = $data['offset'];
    }
    if (isset($data['listing_id']) && $data['listing_id'] != '') {
        $args['post__in'] = array(intval($data['listing_id']));
    }

    if (isset($data['seller_id']) && $data['seller_id'] != '') {
        $args['author'] = $data['seller_id'];
    }

    if (isset($data['order']) && $data['order'] != '') {
        $args['order'] = $data['order'];
    }

    if (isset($data['orderby']) && $data['orderby'] != '') {
        $args['orderby'] = $data['orderby'];
        if ($data['orderby'] == 'price') {
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = 'can_regular_price';           
        }
    }

    if (isset($data['orderby_custom']) && $data['orderby_custom'] != '') {
        $args['orderby'] = 'meta_value';
        $args['meta_key'] = 'can_'.$data['orderby_custom'];
    }

    if (isset($data['tag']) && $data['tag'] != '') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'can_listing_tag',
                'field'    => 'term_id',
                'terms'    => $data['tag'],
            ),
        );        
    }

    if (isset($cats) && $cats != '') {
        $p_cats = array_map('trim', explode(',', $cats));
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'can_listing_category',
                'field'    => 'name',
                'terms'    => $p_cats,
            ),
        );
    }
    
    if (isset($data['keywords']) && $data['keywords'] != '') {
        $args['s'] = $data['keywords'];
    }
    
    /**
     * Searching for custom fields
     */
    $inputFields = can_get_listing_fields();
    foreach ($inputFields as $field) {
        if (isset($data[$field['key']]) && $data[$field['key']] != '' && $field['type'] != 'price') {
            if (preg_match('/^\d{1,}\+/', $data[$field['key']])) {
                $numb = intval($data[$field['key']]);
                $args['meta_query'][] = array(
                    array(
                        'key'     => 'can_'.$field['key'],
                        'value'   => $numb,
                        'type'    => 'numeric',
                        'compare' => '>=',
                    ),
                );
            } elseif (preg_match('/^\d{1,}-\d{1,}/', $data[$field['key']])) {
                $area_arr = explode('-', $data[$field['key']]);
                $args['meta_query'][] = array(
                    array(
                        'key'     => 'can_'.$field['key'],
                        'value'   => array( $area_arr[0], $area_arr[1] ),
                        'type'    => 'numeric',
                        'compare' => 'BETWEEN',
                    ),
                );
            } elseif (strpos($data[$field['key']], '!') !== false) {
                $args['meta_query'][] = array(
                    array(
                        'key'     => 'can_'.$field['key'],
                        'value'   => ltrim($data[$field['key']],"!"),
                        'compare' => 'NOT LIKE',
                    ),
                );
            } elseif (strpos($field['key'], '_id') !== false) {
                $args['meta_query'][] = array(
                    array(
                        'key'     => 'can_'.$field['key'],
                        'value'   => stripcslashes($data[$field['key']]),
                        'compare' => '=',
                    ),
                );
            } else {
                $args['meta_query'][] = array(
                    array(
                        'key'     => 'can_'.$field['key'],
                        'value'   => stripcslashes($data[$field['key']]),
                        'compare' => 'LIKE',
                    ),
                );
            }
        }
    }

    if ( isset($data['range']) && !empty($data['range']) ) {
        foreach ($data['range'] as $range_key => $values) {
            if ($values['min'] != '' || $values['max'] != '') {
                $range_min = ($values['min'] == '') ? '0' : can_range_into_int($values['min']);
                $range_max = ($values['max'] == '') ? '999999999999' : can_range_into_int($values['max']);
                $args['meta_query'][] = array(
                    array(
                        'key'     => 'can_'.$range_key,
                        'value'   => array( intval($range_min), intval($range_max) ),
                        'type'    => 'numeric',
                        'compare' => 'BETWEEN',
                    ),
                );
            }
        }
    }    

    /**
     * Searching for Price
     */
    if (isset($data['regular_price']['min'])) {
        $price_min = ($data['regular_price']['min'] == '') ? '0' : $data['regular_price']['min'];
        $price_max = ($data['regular_price']['max'] == '') ? '9999999999' : $data['regular_price']['max'];

        $args['meta_query'][] = array(
            array(
                'key'     => 'can_regular_price',
                'value'   => array( intval($price_min), intval($price_max) ),
                'type'    => 'numeric',
                'compare' => 'BETWEEN',
            ),
        );
    }

    /**
     * Searching for Features
     */
    if (isset($data['detail_cbs']) && $data['detail_cbs'] != '') {

        foreach ($data['detail_cbs'] as $cbname => $value) {
            $args['meta_query'][] = array(
                array(
                    'key'     => 'can_property_detail_cbs',
                    'value'   => $cbname,
                    'compare' => 'LIKE',
                ),
            );
        }
    }

    // WPML Support
    if (isset($data['lang'])) {
        do_action( 'wpml_switch_language',  $data['lang'] );
    }

    $args = apply_filters( 'can_search_listings_query_args', $args, $data );

    return $args;
}
?>
