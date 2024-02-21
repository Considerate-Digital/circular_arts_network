<?php

/**
 * WPML
 * registering and translating strings input by users
 */
if( ! function_exists('uclwp_wpml_register') ) {
    function uclwp_wpml_register($field_value, $domain, $field_name = '') {
        $field_name = ($field_name == '') ? $field_value : $field_name ;
        do_action( 'wpml_register_single_string', $domain, $field_name, $field_value );
    }
}

if( ! function_exists('uclwp_wpml_translate') ) {
    function uclwp_wpml_translate($field_value, $domain, $field_name = '', $language = '') {
        $field_name = ($field_name == '') ? $field_value : $field_name ;
        return apply_filters('wpml_translate_single_string', stripcslashes($field_value), $domain, $field_name, $language );
    }
}

/**
 * Return specific option from settings against key provided.
 * @since  1.0.0
 * @return string
 */
function uclwp_get_option($key, $default = '') {
    $ucl_settings = get_option( 'uclwp_all_settings' );
    if (isset($ucl_settings[$key]) && $ucl_settings[$key] != '') {
        return apply_filters( 'ucl_get_option_'.$key, $ucl_settings[$key], $default );
    } else {
        return $default;
    }
}

function uclwp_load_basic_styles(){
    wp_enqueue_style('uclwp-bs', UCLWP_URL."/assets/libs/css/bootstrap.css");
    wp_enqueue_style('uclwp-icons', UCLWP_URL."/assets/libs/icons/bootstrap-icons.css");
    wp_enqueue_style('uclwp-main', UCLWP_URL."/assets/css/main.css");

    ob_start();
        include_once UCLWP_PATH . '/assets/css/styles.php';
    $custom_css = ob_get_clean();
    wp_add_inline_style( 'uclwp-main', $custom_css );
}

function uclwp_can_user_access($section, $listing_id = ''){
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
    
    return apply_filters( 'uclwp_can_user_access'.$section['key'], $is_accessible, $section, $listing_id );
}

/**
 * Get all the listing fields
 */
function uclwp_get_listing_fields(){
    $saved_fields = get_option( 'uclwp_listing_fields' );
    $inputFields  = array();
    if ($saved_fields != '' && is_array($saved_fields)) {
        $inputFields = $saved_fields;
    } else {
        include UCLWP_PATH.'/inc/arrays/listing-fields.php';
    }

    if(has_filter('uclwp_all_listing_fields')) {
        $inputFields = apply_filters('uclwp_all_listing_fields', $inputFields);
    }

    return $inputFields;
}

function uclwp_is_default_section($section){
    $def_keys = array('description', 'gallery_images', 'location', 'tags');
    if (in_array($section['key'], $def_keys)) {
        return true;
    }
    return false;
}

function uclwp_get_icons_list(){
    $icons = array();
        include UCLWP_PATH.'/inc/arrays/icons.php';
    return apply_filters( 'uclwp_font_icons', $icons );
}

function uclwp_get_column_classes($columns){
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

    return apply_filters( 'uclwp_column_classes', $classes, $columns );
}

/**
 * Renders the listing section for editing fields
 */
function uclwp_render_listing_section($section, $listing_id = 0){

    if (!uclwp_can_user_access($section, $listing_id)) {
        return;
    }

    if (!$listing_id) {
        global $post;
        $listing_id = (isset($post->ID) && $post->post_type == 'uclwp_listing') ? $post->ID : 0 ;
    }

    switch ($section['key']) {
        case 'description':
            if (!is_admin()) {
                $listing_data = get_post( $listing_id ); ?>
                <div class="card mb-2">
                    <h5 class="card-header"><?php echo esc_attr( $section['title'] ); ?></h5>
                    <div class="card-body">
                        <?php do_action( 'uclwp_before_section_edit_'.$section['key'] ); ?>
                            <input value="<?php echo ($listing_id) ? $listing_data->post_title : ''; ?>" id="listing_title" class="form-control mb-3" type="text" required placeholder="<?php _e( 'Listing Title', 'ultimate-classified-listings' ); ?>" name="listing_title">
                            <?php wp_editor( ($listing_id) ? $listing_data->post_content : '', 'ucl-description', array(
                                'quicktags' => array( 'buttons' => 'strong,em,del,ul,ol,li,close' ),
                                'textarea_name' => 'description',
                                'editor_height' => 350
                            ) ); ?>

                        <?php do_action( 'uclwp_after_section_edit_'.$section['key'] );
                        ?>
                    </div>
                </div>
            <?php }
            break;

        case 'tags':
            if (!is_admin()) { ?>
                
            <?php }
            break;

        case 'gallery_images':

            if ($listing_id) {
                $savedImages = get_post_meta( $listing_id, 'ucl_'.$section['key'], true );
            }
            wp_enqueue_script('uclwp-field-image', UCLWP_URL."/assets/fields/images.js", array( 'jquery' ));
            wp_enqueue_style('uclwp-field-image', UCLWP_URL."/assets/fields/images.css");
            ?>
            <div class="card mb-2">
                <h5 class="card-header"><?php echo esc_attr( $section['title'] ); ?></h5>
                <div class="card-body">
                    <div class="uclwp-images-field text-center" id="images-<?php echo esc_attr( $section['key'] ); ?>">
                        <button class="btn btn-primary btn-sm upload_image_button"
                            data-title="<?php _e( 'Select Images', 'ultimate-classified-listings' ); ?>"
                            data-btntext="<?php _e( 'Add', 'ultimate-classified-listings' ); ?>"
                            data-fieldname="<?php echo esc_attr( $section['key'] ); ?>"
                        >
                            <span class="dashicons dashicons-images-alt2"></span>
                            <?php _e( 'Select Images', 'ultimate-classified-listings' ) ?>
                        </button>
                        
                        <div class="row thumbs-prev mt-3">
                            <?php if (isset($savedImages) && is_array($savedImages)) {
                                foreach ($savedImages as $image_id) {
                                    $image_url = wp_get_attachment_image_src( $image_id, 'thumbnail' );
                                    ?>
                                        <div class="col-sm-3">
                                            <div class="ucl-preview-image">
                                                <input type="hidden" name="<?php echo esc_attr( $section['key'] ); ?>[<?php echo esc_attr( $image_id ); ?>]" value="<?php echo esc_attr( $image_id ); ?>">
                                                <div class="ucl-image-wrap">
                                                    <img src="<?php echo esc_url( $image_url[0] ); ?>">
                                                </div>
                                                <div class="ucl-actions-wrap">
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
                $savedLatitude = get_post_meta( $listing_id, 'ucl_listing_latitude', true );
                $savedLongitude = get_post_meta( $listing_id, 'ucl_listing_longitude', true );
            }

            if (uclwp_get_option('use_map_from', 'leaflet') == 'leaflet') {
                wp_enqueue_style( 'ucl-leaflet-css', UCLWP_URL . '/assets/leaflet/leaflet.css');
                wp_enqueue_script( 'ucl-leaflet-js', UCLWP_URL . '/assets/leaflet/leaflet.js', array('jquery'));
                wp_enqueue_style( 'ucl-leaflet-geo-css', UCLWP_URL . '/assets/leaflet/Control.Geocoder.css');
                wp_enqueue_script( 'ucl-leaflet-geo-js', UCLWP_URL . '/assets/leaflet/Control.Geocoder.js');
            } else {
                $maps_api_key = uclwp_get_option('maps_api_key');
                if (is_ssl()) {
                    wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key='.$maps_api_key.'&libraries=places' );
                } else {
                    wp_enqueue_script( 'google-maps', 'http://maps.googleapis.com/maps/api/js?key='.$maps_api_key.'&libraries=places' );
                }
            }
            wp_enqueue_script('uclwp-field-location', UCLWP_URL."/assets/fields/location.js", array( 'jquery' ));
            $localize_vars = array(
                'use_map_from' => uclwp_get_option('use_map_from', 'leaflet'),
                'def_lat' => isset($savedLatitude) ? $savedLatitude : '37.0902',
                'def_long' => isset($savedLongitude) ? $savedLongitude :'95.7129',
                'leaflet_styles' => uclwp_get_leaflet_provider(1),
                'zoom_level' => uclwp_get_option('maps_zoom_level', 5),
                'drag_icon' => uclwp_get_option('maps_drag_image', UCLWP_URL.'/assets/images/pin-drag.png') ,
            );

            wp_localize_script( 'uclwp-field-location', 'ucl_map_settings', $localize_vars );
            wp_enqueue_style('uclwp-field-location', UCLWP_URL."/assets/fields/images.css");
            ?>
            <div class="card mb-2">
                <h5 class="card-header"><?php echo esc_attr( $section['title'] ); ?></h5>
                <div class="card-body">
                    <input type="hidden" class="ucl_listing_latitude" value="<?php echo isset($savedLatitude) ? $savedLatitude : ''; ?>" name="ucl_listing_latitude">
                    <input type="hidden" class="ucl_listing_longitude" value="<?php echo isset($savedLongitude) ? $savedLongitude : ''; ?>" name="ucl_listing_longitude">
                    <?php if (uclwp_get_option('use_map_from', 'leaflet') == 'google_maps') { ?>
                    <input type="text" class="form-control" id="search-map" placeholder="<?php _e( 'Type to Search...', 'ultimate-classified-listings' ); ?>">
                    <?php } ?>
                    <div id="map-canvas" style="height: 300px"></div>
                    <div id="position" class="alert alert-info mb-0 py-2 mt-2">
                        <?php
                            _e( 'Search the address on the search bar. ', 'ultimate-classified-listings' );
                            _e( 'Drag the pin to the location on the map', 'ultimate-classified-listings' );
                        ?>
                    </div>
                </div>
            </div>

                
            <?php break;
        
        default:
            $inputFields = uclwp_get_listing_fields(); ?>
            <div class="card mb-2">
                <h5 class="card-header"><?php echo esc_attr( $section['title'] ); ?></h5>
                <div class="card-body">
                    <?php
                        do_action( 'uclwp_before_section_edit_'.$section['key'] );

                        foreach ($inputFields as $field) {
                            
                            if($field['tab'] == $section['key']){
                                uclwp_render_listing_field($field, $listing_id);
                            }
                        }

                        do_action( 'uclwp_after_section_edit_'.$section['key'] );
                    ?>
                </div>
            </div>
            <?php
            break;
    }
}

function uclwp_render_search_field($field, $label = false, $icon = true){
    if ($label) {
        $label = $field['title'];
        $label = apply_filters( 'uclwp_search_field_label', $label, $field );
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
            $html = '<div class="ucl-price-search-wrap">';
                $html .= '<input type="text" class="ucl-input-field" name="'.esc_attr( $field['key'] ).'[min]" placeholder="'.__( 'Minimum', 'ultimate-classified-listings' ).'">';
                $html .= '<span class="ucl-price-label">'.esc_attr( $field['title'] ).' <span class="p-symbol">('.uclwp_get_currency_symbol().')</span></span>';
                $html .= '<input type="text" class="ucl-input-field" name="'.esc_attr( $field['key'] ).'[max]" placeholder="'.__( 'Maximum', 'ultimate-classified-listings' ).'">';
            $html .= '</div>';
            break;

        case 'select':
            $html = '<select class="ucl-select-field" name='.esc_attr( $field['key'] ).'>';
                $options = (is_array($field['options'])) ? $field['options'] : explode("\n", $field['options']);
                foreach ($options as $name) {
                    $translated_label = uclwp_wpml_translate($name, 'ultimate-classified-listings-fields');
                    $html .= '<option value="'.$name.'" '.selected( $field_value, $name, false ).'>'.$translated_label.'</option>';
                }

            $html .= '</select>';
            break;
        
        default:
            $html =  "<input class='ucl-input-field' type=".esc_attr( $field['type'] )." name=".esc_attr( $field['key'] )." />";
            break;
    }

    return apply_filters( 'uclwp_search_field_html', $html, $field );
}

/**
 * Renders the listing form fields
 */
function uclwp_render_listing_field($field, $listing_id = 0){

    if (!uclwp_can_user_access($field, $listing_id)) {
        return;
    }
    
    $field_type = $field['type'];
    $field_id = $field['key'];
    $field_name = 'uclwp_data['.$field_id.']';
    $field_title = $field['title'];
    $field_help = $field['help'];
    $field_value = $field['default'];
    $required = (isset($field['required']) && $field['required'] == 'true' ) ? true : false;

    if (!$listing_id) {
        global $post;
        $listing_id = (isset($post->ID)) ? $post->ID : 0 ;
    }

    if ($listing_id) {
        $field_value = get_post_meta( $listing_id, 'ucl_'.$field_id, true );
    }

    switch ($field_type) {


        case 'checkboxes':
            ?>

            <div class="uclwp-checkboxes-wrap">
                <p class="fw-bold"><?php echo uclwp_wpml_translate($field_title, 'ultimate-classified-listings-fields'); ?></p>
                <div class="row">
                    <?php foreach ($field['options'] as $key => $option) {
                        $translated_label = uclwp_wpml_translate($option, 'ultimate-classified-listings-fields');
                        $cb_id = 'uclwp-'.$field_id.'-'.$key;
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
                    <?php echo uclwp_wpml_translate($field_title, 'ultimate-classified-listings-fields'); ?>
                    <?php echo ($required) ? '<span title="'.__( 'Required', 'ultimate-classified-listings' ).'" class="glyphicon glyphicon-asterisk"></span>' : '' ; ?>
                </label>
                <div class="col-sm-8">
                    <select name="<?php echo esc_attr( $field_name ); ?>" <?php echo esc_attr( $field_id ); ?> class="form-control form-control-sm">
                        <?php
                            $options = (is_array($field['options'])) ? $field['options'] : explode("\n", $field['options']);
                            foreach ($options as $name) {
                                $translated_label = uclwp_wpml_translate($name, 'ultimate-classified-listings-fields');
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
                    <?php echo uclwp_wpml_translate($field_title, 'ultimate-classified-listings-fields'); ?>
                    <?php echo ($required) ? '<span title="'.__( 'Required', 'ultimate-classified-listings' ).'" class="glyphicon glyphicon-asterisk"></span>' : '' ; ?>
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
            $before_value   =   get_post_meta( $listing_id, 'ucl_'.$field_id.'_before', true );
            $after_value    =   get_post_meta( $listing_id, 'ucl_'.$field_id.'_after', true );
            ?>
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label" for="<?php echo esc_attr( $field_id ); ?>">
                    <?php echo uclwp_wpml_translate($field_title, 'ultimate-classified-listings-fields'); ?>
                    <?php echo ($required) ? '<span title="'.__( 'Required', 'ultimate-classified-listings' ).'" class="glyphicon glyphicon-asterisk"></span>' : '' ; ?>
                </label>
                <div class="col-sm-3">
                    <input type="text"
                        name="uclwp_data[<?php echo esc_attr( $field_id ); ?>_before]"
                        class="form-control form-control-sm"
                        value="<?php echo esc_attr( $before_value ); ?>"
                    >
                    <span class="help-block"><?php _e( 'Before Text', 'ultimate-classified-listings' ) ?></span>
                </div>
                <div class="col-sm-2">
                   <input
                        type="number"
                        name="<?php echo esc_attr( $field_name ); ?>"
                        class="form-control form-control-sm"
                        id="<?php echo esc_attr( $field_id ); ?>"
                        value="<?php echo esc_attr( $field_value ); ?>"> 
                    <span class="help-block"><?php echo esc_attr( $field_help ); ?></span>
                </div>
                <div class="col-sm-3">
                    <input type="text"
                        name="uclwp_data[<?php echo esc_attr( $field_id ); ?>_after]"
                        class="form-control form-control-sm"
                        value="<?php echo esc_attr( $after_value ); ?>"
                    >
                    <span class="help-block"><?php _e( 'After Text', 'ultimate-classified-listings' ) ?></span>
                </div>
            </div>
  
            <?php break;


        case 'number':
            ?>
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label" for="<?php echo esc_attr( $field_id ); ?>">
                    <?php echo uclwp_wpml_translate($field_title, 'ultimate-classified-listings-fields'); ?>
                    <?php echo ($required) ? '<span title="'.__( 'Required', 'ultimate-classified-listings' ).'" class="glyphicon glyphicon-asterisk"></span>' : '' ; ?>
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
                    <?php echo uclwp_wpml_translate($field_title, 'ultimate-classified-listings-fields'); ?>
                    <?php echo ($required) ? '<span title="'.__( 'Required', 'ultimate-classified-listings' ).'" class="glyphicon glyphicon-asterisk"></span>' : '' ; ?>
                </label>
                <div class="col-sm-8">
                   <input type="<?php echo esc_attr( $field_type ); ?>" name="<?php echo esc_attr( $field_name ); ?>" class="form-control form-control-sm" id="<?php echo esc_attr( $field_id ); ?>" value="<?php echo esc_attr( $field_value ); ?>"> 
                    <span class="help-block"><?php echo esc_attr( $field_help ); ?></span>
                </div>
            </div>
            <?php break;
    }
}

function uclwp_get_field_value($listing_id, $field, $value = ''){

    if (!$value) {
        $value = get_post_meta( $listing_id, 'ucl_'.$field['key'], true );
    }

    $value = uclwp_wpml_translate($value, 'ultimate-classified-listings-fields');

    if (isset($field['type']) && $field['type'] == 'date') {
        $format = uclwp_get_option('date_format', 'd-m-Y');
        $value = date($format, strtotime($value));
    }

    if (isset($field['type']) && $field['type'] == 'price') {
        $value = uclwp_get_listing_price($value);

        $before_value   =   get_post_meta( $listing_id, 'ucl_'.$field['key'].'_before', true );
        $after_value    =   get_post_meta( $listing_id, 'ucl_'.$field['key'].'_after', true );
        if ($before_value) {
            $value = "<span class='ucl-before-text'>{$before_value}</span> ".$value;
        }
        if ($after_value) {
            $value = $value." <span class='ucl-after-text'>{$after_value}</span>";
        }
    }

    return apply_filters( 'uclwp_listing_field_value', $value, $field, $listing_id );
}

function uclwp_get_section_title($tabData){
    $title = __( $tabData['title'], 'ultimate-classified-listings' );
    $tab_key = $tabData['key'];
    $icon = '';

    if (isset($tabData['icon']) && $tabData['icon'] != '') {
        if (strpos($tabData['icon'], "http://") !== false || strpos($tabData['icon'], "https://") !== false) {
            $icon = '<img class="ucl-sec-icon" src= "'.esc_url( $tabData['icon'] ).'">';
        } else {
            $icon = '<i class="'.esc_attr( $tabData['icon'] ).'"></i>';
        }
    }

    $icon = apply_filters( 'uclwp_listing_section_title_icon', $icon,  $tabData);

    $wrap = apply_filters( 'uclwp_listing_section_title_wrap', 'h2', $tab_key );
    return "<$wrap class='title'>$icon ".stripcslashes($title)."</$wrap>";
}

function uclwp_count_user_listings($user_id, $status = 'all'){
    $args  = array(
        'post_type' =>'uclwp_listing',
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
function uclwp_get_listing_price( $price, $args = array() ) {
    $price_digits = $price;
    extract( apply_filters( 'uclwp_price_args', wp_parse_args( $args, array(
        'currency'           => uclwp_get_option('currency', 'USD'),
        'decimal_separator'  => uclwp_get_price_decimal_separator(),
        'thousand_separator' => uclwp_get_price_thousand_separator(),
        'decimals'           => uclwp_get_price_decimals(),
        'price_format'       => uclwp_get_price_format()
    ) ) ) );
    $negative        = $price < 0;
    $price           = apply_filters( 'raw_uclwp_price', floatval( $negative ? $price * -1 : $price ) );
    $price           = apply_filters( 'formatted_uclwp_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

    if ( apply_filters( 'uclwp_price_trim_zeros', false ) && $decimals > 0 ) {
        $price = wc_trim_zeros( $price );
    }

    $formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, '<span class="uclwp-currency-symbol">' . uclwp_get_currency_symbol( $currency ) . '</span>', $price );
    $return          = '<span class="uclwp-price-amount">' . $formatted_price . '</span>';

    return apply_filters( 'uclwp_property_price', $return, $price, $args, $price_digits );
}

/**
 * Get full list of currency codes.
 *
 * @return array
 */
function uclwp_get_all_currencies() {
    return array_unique(
        apply_filters( 'uclwp_all_currencies',
            array(
                'AED' => __( 'United Arab Emirates dirham', 'ultimate-classified-listings' ),
                'AFN' => __( 'Afghan afghani', 'ultimate-classified-listings' ),
                'ALL' => __( 'Albanian lek', 'ultimate-classified-listings' ),
                'AMD' => __( 'Armenian dram', 'ultimate-classified-listings' ),
                'ANG' => __( 'Netherlands Antillean guilder', 'ultimate-classified-listings' ),
                'AOA' => __( 'Angolan kwanza', 'ultimate-classified-listings' ),
                'ARS' => __( 'Argentine peso', 'ultimate-classified-listings' ),
                'AUD' => __( 'Australian dollar', 'ultimate-classified-listings' ),
                'AWG' => __( 'Aruban florin', 'ultimate-classified-listings' ),
                'AZN' => __( 'Azerbaijani manat', 'ultimate-classified-listings' ),
                'BAM' => __( 'Bosnia and Herzegovina convertible mark', 'ultimate-classified-listings' ),
                'BBD' => __( 'Barbadian dollar', 'ultimate-classified-listings' ),
                'BDT' => __( 'Bangladeshi taka', 'ultimate-classified-listings' ),
                'BGN' => __( 'Bulgarian lev', 'ultimate-classified-listings' ),
                'BHD' => __( 'Bahraini dinar', 'ultimate-classified-listings' ),
                'BIF' => __( 'Burundian franc', 'ultimate-classified-listings' ),
                'BMD' => __( 'Bermudian dollar', 'ultimate-classified-listings' ),
                'BND' => __( 'Brunei dollar', 'ultimate-classified-listings' ),
                'BOB' => __( 'Bolivian boliviano', 'ultimate-classified-listings' ),
                'BRL' => __( 'Brazilian real', 'ultimate-classified-listings' ),
                'BSD' => __( 'Bahamian dollar', 'ultimate-classified-listings' ),
                'BTC' => __( 'Bitcoin', 'ultimate-classified-listings' ),
                'BTN' => __( 'Bhutanese ngultrum', 'ultimate-classified-listings' ),
                'BWP' => __( 'Botswana pula', 'ultimate-classified-listings' ),
                'BYR' => __( 'Belarusian ruble', 'ultimate-classified-listings' ),
                'BZD' => __( 'Belize dollar', 'ultimate-classified-listings' ),
                'CAD' => __( 'Canadian dollar', 'ultimate-classified-listings' ),
                'CDF' => __( 'Congolese franc', 'ultimate-classified-listings' ),
                'CHF' => __( 'Swiss franc', 'ultimate-classified-listings' ),
                'CLP' => __( 'Chilean peso', 'ultimate-classified-listings' ),
                'CNY' => __( 'Chinese yuan', 'ultimate-classified-listings' ),
                'COP' => __( 'Colombian peso', 'ultimate-classified-listings' ),
                'CRC' => __( 'Costa Rican col&oacute;n', 'ultimate-classified-listings' ),
                'CUC' => __( 'Cuban convertible peso', 'ultimate-classified-listings' ),
                'CUP' => __( 'Cuban peso', 'ultimate-classified-listings' ),
                'CVE' => __( 'Cape Verdean escudo', 'ultimate-classified-listings' ),
                'CZK' => __( 'Czech koruna', 'ultimate-classified-listings' ),
                'DJF' => __( 'Djiboutian franc', 'ultimate-classified-listings' ),
                'DKK' => __( 'Danish krone', 'ultimate-classified-listings' ),
                'DOP' => __( 'Dominican peso', 'ultimate-classified-listings' ),
                'DZD' => __( 'Algerian dinar', 'ultimate-classified-listings' ),
                'EGP' => __( 'Egyptian pound', 'ultimate-classified-listings' ),
                'ERN' => __( 'Eritrean nakfa', 'ultimate-classified-listings' ),
                'ETB' => __( 'Ethiopian birr', 'ultimate-classified-listings' ),
                'EUR' => __( 'Euro', 'ultimate-classified-listings' ),
                'FJD' => __( 'Fijian dollar', 'ultimate-classified-listings' ),
                'FKP' => __( 'Falkland Islands pound', 'ultimate-classified-listings' ),
                'GBP' => __( 'Pound sterling', 'ultimate-classified-listings' ),
                'GEL' => __( 'Georgian lari', 'ultimate-classified-listings' ),
                'GGP' => __( 'Guernsey pound', 'ultimate-classified-listings' ),
                'GHS' => __( 'Ghana cedi', 'ultimate-classified-listings' ),
                'GIP' => __( 'Gibraltar pound', 'ultimate-classified-listings' ),
                'GMD' => __( 'Gambian dalasi', 'ultimate-classified-listings' ),
                'GNF' => __( 'Guinean franc', 'ultimate-classified-listings' ),
                'GTQ' => __( 'Guatemalan quetzal', 'ultimate-classified-listings' ),
                'GYD' => __( 'Guyanese dollar', 'ultimate-classified-listings' ),
                'HKD' => __( 'Hong Kong dollar', 'ultimate-classified-listings' ),
                'HNL' => __( 'Honduran lempira', 'ultimate-classified-listings' ),
                'HRK' => __( 'Croatian kuna', 'ultimate-classified-listings' ),
                'HTG' => __( 'Haitian gourde', 'ultimate-classified-listings' ),
                'HUF' => __( 'Hungarian forint', 'ultimate-classified-listings' ),
                'IDR' => __( 'Indonesian rupiah', 'ultimate-classified-listings' ),
                'ILS' => __( 'Israeli new shekel', 'ultimate-classified-listings' ),
                'IMP' => __( 'Manx pound', 'ultimate-classified-listings' ),
                'INR' => __( 'Indian rupee', 'ultimate-classified-listings' ),
                'IQD' => __( 'Iraqi dinar', 'ultimate-classified-listings' ),
                'IRR' => __( 'Iranian rial', 'ultimate-classified-listings' ),
                'ISK' => __( 'Icelandic kr&oacute;na', 'ultimate-classified-listings' ),
                'JEP' => __( 'Jersey pound', 'ultimate-classified-listings' ),
                'JMD' => __( 'Jamaican dollar', 'ultimate-classified-listings' ),
                'JOD' => __( 'Jordanian dinar', 'ultimate-classified-listings' ),
                'JPY' => __( 'Japanese yen', 'ultimate-classified-listings' ),
                'KES' => __( 'Kenyan shilling', 'ultimate-classified-listings' ),
                'KGS' => __( 'Kyrgyzstani som', 'ultimate-classified-listings' ),
                'KHR' => __( 'Cambodian riel', 'ultimate-classified-listings' ),
                'KMF' => __( 'Comorian franc', 'ultimate-classified-listings' ),
                'KPW' => __( 'North Korean won', 'ultimate-classified-listings' ),
                'KRW' => __( 'South Korean won', 'ultimate-classified-listings' ),
                'KWD' => __( 'Kuwaiti dinar', 'ultimate-classified-listings' ),
                'KYD' => __( 'Cayman Islands dollar', 'ultimate-classified-listings' ),
                'KZT' => __( 'Kazakhstani tenge', 'ultimate-classified-listings' ),
                'LAK' => __( 'Lao kip', 'ultimate-classified-listings' ),
                'LBP' => __( 'Lebanese pound', 'ultimate-classified-listings' ),
                'LKR' => __( 'Sri Lankan rupee', 'ultimate-classified-listings' ),
                'LRD' => __( 'Liberian dollar', 'ultimate-classified-listings' ),
                'LSL' => __( 'Lesotho loti', 'ultimate-classified-listings' ),
                'LYD' => __( 'Libyan dinar', 'ultimate-classified-listings' ),
                'MAD' => __( 'Moroccan dirham', 'ultimate-classified-listings' ),
                'MDL' => __( 'Moldovan leu', 'ultimate-classified-listings' ),
                'MGA' => __( 'Malagasy ariary', 'ultimate-classified-listings' ),
                'MKD' => __( 'Macedonian denar', 'ultimate-classified-listings' ),
                'MMK' => __( 'Burmese kyat', 'ultimate-classified-listings' ),
                'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', 'ultimate-classified-listings' ),
                'MOP' => __( 'Macanese pataca', 'ultimate-classified-listings' ),
                'MRO' => __( 'Mauritanian ouguiya', 'ultimate-classified-listings' ),
                'MUR' => __( 'Mauritian rupee', 'ultimate-classified-listings' ),
                'MVR' => __( 'Maldivian rufiyaa', 'ultimate-classified-listings' ),
                'MWK' => __( 'Malawian kwacha', 'ultimate-classified-listings' ),
                'MXN' => __( 'Mexican peso', 'ultimate-classified-listings' ),
                'MYR' => __( 'Malaysian ringgit', 'ultimate-classified-listings' ),
                'MZN' => __( 'Mozambican metical', 'ultimate-classified-listings' ),
                'NAD' => __( 'Namibian dollar', 'ultimate-classified-listings' ),
                'NGN' => __( 'Nigerian naira', 'ultimate-classified-listings' ),
                'NIO' => __( 'Nicaraguan c&oacute;rdoba', 'ultimate-classified-listings' ),
                'NOK' => __( 'Norwegian krone', 'ultimate-classified-listings' ),
                'NPR' => __( 'Nepalese rupee', 'ultimate-classified-listings' ),
                'NZD' => __( 'New Zealand dollar', 'ultimate-classified-listings' ),
                'OMR' => __( 'Omani rial', 'ultimate-classified-listings' ),
                'PAB' => __( 'Panamanian balboa', 'ultimate-classified-listings' ),
                'PEN' => __( 'Peruvian nuevo sol', 'ultimate-classified-listings' ),
                'PGK' => __( 'Papua New Guinean kina', 'ultimate-classified-listings' ),
                'PHP' => __( 'Philippine peso', 'ultimate-classified-listings' ),
                'PKR' => __( 'Pakistani rupee', 'ultimate-classified-listings' ),
                'PLN' => __( 'Polish z&#x142;oty', 'ultimate-classified-listings' ),
                'PRB' => __( 'Transnistrian ruble', 'ultimate-classified-listings' ),
                'PYG' => __( 'Paraguayan guaran&iacute;', 'ultimate-classified-listings' ),
                'QAR' => __( 'Qatari riyal', 'ultimate-classified-listings' ),
                'RON' => __( 'Romanian leu', 'ultimate-classified-listings' ),
                'RSD' => __( 'Serbian dinar', 'ultimate-classified-listings' ),
                'RUB' => __( 'Russian ruble', 'ultimate-classified-listings' ),
                'RWF' => __( 'Rwandan franc', 'ultimate-classified-listings' ),
                'SAR' => __( 'Saudi riyal', 'ultimate-classified-listings' ),
                'SBD' => __( 'Solomon Islands dollar', 'ultimate-classified-listings' ),
                'SCR' => __( 'Seychellois rupee', 'ultimate-classified-listings' ),
                'SDG' => __( 'Sudanese pound', 'ultimate-classified-listings' ),
                'SEK' => __( 'Swedish krona', 'ultimate-classified-listings' ),
                'SGD' => __( 'Singapore dollar', 'ultimate-classified-listings' ),
                'SHP' => __( 'Saint Helena pound', 'ultimate-classified-listings' ),
                'SLL' => __( 'Sierra Leonean leone', 'ultimate-classified-listings' ),
                'SOS' => __( 'Somali shilling', 'ultimate-classified-listings' ),
                'SRD' => __( 'Surinamese dollar', 'ultimate-classified-listings' ),
                'SSP' => __( 'South Sudanese pound', 'ultimate-classified-listings' ),
                'STD' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'ultimate-classified-listings' ),
                'SYP' => __( 'Syrian pound', 'ultimate-classified-listings' ),
                'SZL' => __( 'Swazi lilangeni', 'ultimate-classified-listings' ),
                'THB' => __( 'Thai baht', 'ultimate-classified-listings' ),
                'TJS' => __( 'Tajikistani somoni', 'ultimate-classified-listings' ),
                'TMT' => __( 'Turkmenistan manat', 'ultimate-classified-listings' ),
                'TND' => __( 'Tunisian dinar', 'ultimate-classified-listings' ),
                'TOP' => __( 'Tongan pa&#x2bb;anga', 'ultimate-classified-listings' ),
                'TRY' => __( 'Turkish lira', 'ultimate-classified-listings' ),
                'TTD' => __( 'Trinidad and Tobago dollar', 'ultimate-classified-listings' ),
                'TWD' => __( 'New Taiwan dollar', 'ultimate-classified-listings' ),
                'TZS' => __( 'Tanzanian shilling', 'ultimate-classified-listings' ),
                'UAH' => __( 'Ukrainian hryvnia', 'ultimate-classified-listings' ),
                'UGX' => __( 'Ugandan shilling', 'ultimate-classified-listings' ),
                'USD' => __( 'United States dollar', 'ultimate-classified-listings' ),
                'UYU' => __( 'Uruguayan peso', 'ultimate-classified-listings' ),
                'UZS' => __( 'Uzbekistani som', 'ultimate-classified-listings' ),
                'VEF' => __( 'Venezuelan bol&iacute;var', 'ultimate-classified-listings' ),
                'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', 'ultimate-classified-listings' ),
                'VUV' => __( 'Vanuatu vatu', 'ultimate-classified-listings' ),
                'WST' => __( 'Samoan t&#x101;l&#x101;', 'ultimate-classified-listings' ),
                'XAF' => __( 'Central African CFA franc', 'ultimate-classified-listings' ),
                'XCD' => __( 'East Caribbean dollar', 'ultimate-classified-listings' ),
                'XOF' => __( 'West African CFA franc', 'ultimate-classified-listings' ),
                'XPF' => __( 'CFP franc', 'ultimate-classified-listings' ),
                'YER' => __( 'Yemeni rial', 'ultimate-classified-listings' ),
                'ZAR' => __( 'South African rand', 'ultimate-classified-listings' ),
                'ZMW' => __( 'Zambian kwacha', 'ultimate-classified-listings' ),
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
function uclwp_get_currency_symbol( $currency = '' ) {
    if ( ! $currency ) {
        $currency = uclwp_get_option('currency', 'USD');
    }

    $symbols = apply_filters( 'uclwp_all_currency_symbols', array(
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

    return apply_filters( 'uclwp_currency_symbol', $currency_symbol, $currency );
}

/**
 * Get the price format depending on the currency position.
 *
 * @return string
 */
function uclwp_get_price_format() {
    $currency_pos = uclwp_get_option( 'currency_position', 'left' );
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

    return apply_filters( 'uclwp_price_format', $format, $currency_pos );
}

/**
 * Return the thousand separator for prices.
 * @since  4.1
 * @return string
 */
function uclwp_get_price_thousand_separator() {
    $separator = stripslashes( uclwp_get_option( 'thousand_separator' ) );
    return $separator;
}

/**
 * Return the decimal separator for prices.
 * @since  4.1
 * @return string
 */
function uclwp_get_price_decimal_separator() {
    $separator = stripslashes( uclwp_get_option( 'decimal_separator' ) );
    return $separator ? $separator : '.';
}

/**
 * Return the number of decimals after the decimal point.
 * @since  4.1
 * @return int
 */
function uclwp_get_price_decimals() {
    return absint( uclwp_get_option( 'decimal_points', 2 ) );
}


/**
 * Getting Leaflet map styles and attribution
 * @param  [type] $map_id [description]
 * @since 1.0.0
 */
function uclwp_get_leaflet_provider($map_id){

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

    return apply_filters( 'uclwp_leaflet_provider', $resp, $map_id );
}

function uclwp_get_search_query($data){
    $ppp = uclwp_get_option('listings_per_results_page', 10);

    $args = array(
        'post_type' =>  'uclwp_listing',
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
            $args['meta_key'] = 'ucl_regular_price';           
        }
    }

    if (isset($data['orderby_custom']) && $data['orderby_custom'] != '') {
        $args['orderby'] = 'meta_value';
        $args['meta_key'] = 'ucl_'.$data['orderby_custom'];
    }

    if (isset($data['tag']) && $data['tag'] != '') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'uclwp_listing_tag',
                'field'    => 'term_id',
                'terms'    => $data['tag'],
            ),
        );        
    }

    if (isset($cats) && $cats != '') {
        $p_cats = array_map('trim', explode(',', $cats));
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'uclwp_listing_category',
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
    $inputFields = uclwp_get_listing_fields();
    foreach ($inputFields as $field) {
        if (isset($data[$field['key']]) && $data[$field['key']] != '' && $field['type'] != 'price') {
            if (preg_match('/^\d{1,}\+/', $data[$field['key']])) {
                $numb = intval($data[$field['key']]);
                $args['meta_query'][] = array(
                    array(
                        'key'     => 'ucl_'.$field['key'],
                        'value'   => $numb,
                        'type'    => 'numeric',
                        'compare' => '>=',
                    ),
                );
            } elseif (preg_match('/^\d{1,}-\d{1,}/', $data[$field['key']])) {
                $area_arr = explode('-', $data[$field['key']]);
                $args['meta_query'][] = array(
                    array(
                        'key'     => 'ucl_'.$field['key'],
                        'value'   => array( $area_arr[0], $area_arr[1] ),
                        'type'    => 'numeric',
                        'compare' => 'BETWEEN',
                    ),
                );
            } elseif (strpos($data[$field['key']], '!') !== false) {
                $args['meta_query'][] = array(
                    array(
                        'key'     => 'ucl_'.$field['key'],
                        'value'   => ltrim($data[$field['key']],"!"),
                        'compare' => 'NOT LIKE',
                    ),
                );
            } elseif (strpos($field['key'], '_id') !== false) {
                $args['meta_query'][] = array(
                    array(
                        'key'     => 'ucl_'.$field['key'],
                        'value'   => stripcslashes($data[$field['key']]),
                        'compare' => '=',
                    ),
                );
            } else {
                $args['meta_query'][] = array(
                    array(
                        'key'     => 'ucl_'.$field['key'],
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
                $range_min = ($values['min'] == '') ? '0' : ucl_range_into_int($values['min']);
                $range_max = ($values['max'] == '') ? '999999999999' : ucl_range_into_int($values['max']);
                $args['meta_query'][] = array(
                    array(
                        'key'     => 'ucl_'.$range_key,
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
                'key'     => 'ucl_regular_price',
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
                    'key'     => 'ucl_property_detail_cbs',
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

    $args = apply_filters( 'uclwp_search_listings_query_args', $args, $data );

    return $args;
}
?>