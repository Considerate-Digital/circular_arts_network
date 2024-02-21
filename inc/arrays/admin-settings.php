<?php
$leaflet_map_styles = array();

for ($style=1; $style <= 23 ; $style++) {
    $leaflet_map_styles[$style] =  __( 'Style', 'ultimate-classified-listings' ).' '.$style;
}

$fieldsData = array(

    array(
        'panel_title'   =>  __( 'Currency Options', 'ultimate-classified-listings' ),
        'panel_name'   =>  'currency_options',
        'icon'   =>  '<i class="bi bi-currency-exchange"></i>',

        'fields'        => array(

            array(
                'type' => 'currency',
                'name' => 'currency',
                'title' => __( 'Currency', 'ultimate-classified-listings' ),
                'help' => __( 'Choose the default currency for the listings.', 'ultimate-classified-listings' ),
            ),


            array(
                'type' => 'select',
                'name' => 'currency_position',
                'title' => __( 'Currency Position', 'ultimate-classified-listings' ),
                'options' => array(
                    'left' => __( 'Left', 'ultimate-classified-listings' ),
                    'right' => __( 'Right', 'ultimate-classified-listings' ),
                    'left_space' => __( 'Left with Space', 'ultimate-classified-listings' ),
                    'right_space' => __( 'Right with Space', 'ultimate-classified-listings' ),
                ),
                'help' => __( 'Position of the Currency Symbol', 'ultimate-classified-listings' ),
            ),

                array(
                    'type' => 'text',
                    'name' => 'thousand_separator',
                    'title' => __( 'Thousand Separator', 'ultimate-classified-listings' ),
                    'help' => __( 'Thousand separator of display price', 'ultimate-classified-listings' ),
                    'default' => ',',
                ),

                array(
                    'type' => 'text',
                    'name' => 'decimal_separator',
                    'title' => __( 'Decimal Separator', 'ultimate-classified-listings' ),
                    'help' => __( 'Decimal separator of display price', 'ultimate-classified-listings' ),
                    'default' => '.',
                ),

                array(
                    'type' => 'text',   
                    'name' => 'decimal_points',
                    'title' => __( 'Number of Decimals', 'ultimate-classified-listings' ),
                    'help' => __( 'Number of decimal points shown in display price', 'ultimate-classified-listings' ),
                    'default' => '2',
                ),
        ),

    ),

    array(
        
        'panel_title'   =>  __( 'Templates Settings', 'ultimate-classified-listings' ),
        'panel_name'   =>  'template_settings',
        'icon'   =>  '<i class="bi bi-file-earmark-richtext"></i>',
        'fields'        => array(
            array(
                'type' => 'select',
                'name' => 'listings_base_page',
                'title' => __( 'Listing Base Page', 'ultimate-classified-listings' ),
                'help' => __( 'If you choose custom, create a page having slug', 'ultimate-classified-listings' ).
                ' <code>'.get_option( 'uclwp_listing_permalink', 'listing' ).'</code> '.
                __( 'and it will be used as the listing base page. After changing this, go to Settings -> Permalinks and click save changes button.', 'ultimate-classified-listings' ),
                'options' => array(
                    'default' => __( 'Default', 'ultimate-classified-listings' ),
                    'custom' => __( 'Custom', 'ultimate-classified-listings' ),
                ),
            ),
            array(
                'type'  => 'select',
                'name'  => 'seller_info',
                'title' => __( 'Listing Page Seller Info', 'ultimate-classified-listings' ),
                'help'  => __( 'Enable or disable default seller info area and contact form on the listing page', 'ultimate-classified-listings' ),
                'options' => array(
                    'enable' => __( 'Enable', 'ultimate-classified-listings' ),
                    'disable' => __( 'Disable', 'ultimate-classified-listings' ),
                ),
            ),
            array(
                'type'  => 'widget',
                'name'  => 'listing_page_sidebar',
                'title' => __( 'Listing Page Sidebar', 'ultimate-classified-listings' ),
                'help'  => __( 'You can add your own widgets in the selected sidebar to display them with the listings.', 'ultimate-classified-listings' ),
            ),
            array(
                'type' => 'select',
                'name' => 'gallery_type',
                'title' => __( 'Gallery Type', 'ultimate-classified-listings' ),
                'help' => __( 'How you want to display gallery images on the single listing page', 'ultimate-classified-listings' ),
                'options' => array(
                    'slick' => __( 'Simple Slider', 'ultimate-classified-listings' ),
                    'grid' => __( 'Grid with Popup', 'ultimate-classified-listings' ),
                ),
            ),
            array(
                'type' => 'text',
                'name' => 'grid_view_txt',
                'default' => 'View all %count% images',
                'title' => __( 'View All Images Text', 'ultimate-classified-listings' ),
                'help' => __( 'If there are more than 5 images, this title will appear.', 'ultimate-classified-listings' ),
                'show_if'  => array('gallery_type', 'grid'),
            ),

            array(
                'type' => 'select',
                'name' => 'slider_featured_image',
                'title' => __( 'Gallery Featured Image', 'ultimate-classified-listings' ),
                'help' => __( 'Enable to display featured image in slider', 'ultimate-classified-listings' ),
                'options' => array(
                    'enable' => __( 'Enable', 'ultimate-classified-listings' ),
                    'disable' => __( 'Disable', 'ultimate-classified-listings' ),
                ),
            ),

            array(
                'type'  => 'image_sizes',
                'name'  => 'gallery_image_size',
                'title' => __( 'Gallery Images Size', 'ultimate-classified-listings' ),
                'help'  => __( 'Choose size for the gallery images', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'text',
                'name' => 'date_format',
                'title' => __( 'Date Field Format', 'ultimate-classified-listings' ),
                'help' => __( 'Provide date format if you are using date field. Eg: ', 'ultimate-classified-listings' ).' d-M-Y',
            ),
        ),

    ),

    array(
        'panel_title'   =>  __( 'Listings', 'ultimate-classified-listings' ),
        'panel_name'   =>  'listings',
        'icon'   =>  '<i class="bi bi-columns-gap"></i>',

        'fields'        => array(
            array(
                'type' => 'text',
                'name' => 'listings_per_page',
                'title' => __( 'Listings Per Page', 'ultimate-classified-listings' ),
                'help' => __( 'Number of listings you want to display on archive pages. (tags etc)', 'ultimate-classified-listings' ),
            ),

            array(
                'type'  => 'image_sizes',
                'name'  => 'featured_image_size',
                'title' => __( 'Featured Image Size', 'ultimate-classified-listings' ),
                'help'  => __( 'Choose size of featured image to use', 'ultimate-classified-listings' ),
            ),

            array(
                'type'  => 'image',
                'name'  => 'placeholder_image',
                'title' => __( 'Featured Image Placeholder', 'ultimate-classified-listings' ),
                'help'  => __( 'This image will be used for the listings without a featured image', 'ultimate-classified-listings' ),
            ),
            array(
                'type' => 'select',
                'name' => 'enable_compare',
                'title' => __( 'Compare Listings', 'ultimate-classified-listings' ),
                'help' => __( 'Choose either to enable or disable the compare listings feature', 'ultimate-classified-listings' ),
                'options' => array(
                    'enable' => __( 'Enable', 'ultimate-classified-listings' ),
                    'disable' => __( 'Disable', 'ultimate-classified-listings' ),
                ),
            ),
            array(
                'type' => 'textarea',
                'name' => 'listing_compare_columns',
                'title' => __( 'Comparison Fields', 'ultimate-classified-listings' ),
                'help' => __( 'Provide label and field key each per line to display in the compare screen. Eg:', 'ultimate-classified-listings' ).'<code>Price|regular_price</code>',
                'show_if'  => array('enable_compare', 'enable'),
            ),            
        ),

    ),

    array(
        'panel_title'   =>  __( 'Search Settings', 'ultimate-classified-listings' ),
        'panel_name'   =>  'search_settings',
        'icon'   =>  '<i class="bi bi-search"></i>',

        'fields'        => array(
            array(
                'type' => 'text',
                'name' => 'listings_per_results_page',
                'title' => __( 'Total Listings', 'ultimate-classified-listings' ),
                'help' => __( 'Number of listings you want to display on search results', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'select',
                'name' => 'searched_listings_target',
                'title' => __( 'Search Results Link Target', 'ultimate-classified-listings' ),
                'help' => __( 'How you want to open the listings when user clicks on the search results.', 'ultimate-classified-listings' ),
                'options' => array(
                    '_blank' => __( 'New Tab', 'ultimate-classified-listings' ),
                    '_self' => __( 'Same Tab', 'ultimate-classified-listings' ),
                ),
            ),
        ),

    ),

    array(

        'panel_title'   =>  __( 'Email Messages', 'ultimate-classified-listings' ),
        'panel_name'   =>  'email_messages',
        'icon'   =>  '<i class="bi bi-envelope"></i>',
        'fields'        => array(

            array(
                'type' => 'textarea',
                'name' => 'to_admin_on_seller_register',
                'title' => __( 'To Admin on Seller Registered', 'ultimate-classified-listings' ),
                'help' => __( 'This message will sent to ', 'ultimate-classified-listings' ).'<b>'.get_bloginfo('admin_email').'</b>'.__( ' when new seller is registered. You can use %username% and %email% for details', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'to_seller_registered',
                'title' => __( 'To Registered Seller', 'ultimate-classified-listings' ),
                'help' => __( 'This message will be sent to the newly regisreted sellers. You can use %username% and %email% for details', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'to_seller_approved',
                'title' => __( 'To Approved Seller', 'ultimate-classified-listings' ),
                'help' => __( 'This message will be sent to the approved seller. You can use %username% and %email% for details', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'to_seller_rejected',
                'title' => __( 'To Rejected Seller', 'ultimate-classified-listings' ),
                'help' => __( 'This message will be sent to the rejected seller. You can use %username% and %email% for details', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'to_admin_submission',
                'title' => __( 'To Admin on Submission', 'ultimate-classified-listings' ),
                'help' => __( 'This message will be sent to ', 'ultimate-classified-listings' ).'<b>'.get_bloginfo('admin_email').'</b>'.__( ' when new listing is submitted. You can use variables %username% %approve_url% and %email% for details', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'to_seller_submission',
                'title' => __( 'To Seller on Submission', 'ultimate-classified-listings' ),
                'help' => __( 'This message will be sent to the seller when a new listing is submitted.', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'to_seller_submission_approved',
                'title' => __( 'To Seller on Submission Approved', 'ultimate-classified-listings' ),
                'help' => __( 'This message will be sent to seller when his listing is approved.', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'select',
                'name' => 'email_br',
                'title' => __( 'Line Breaks in Emails', 'ultimate-classified-listings' ),
                'options' => array(
                    'enable' => __( 'Enable', 'ultimate-classified-listings' ),
                    'disable' => __( 'Disable', 'ultimate-classified-listings' ),
                ),
                'help' => __( 'Enable to inserts HTML line breaks before all newlines in the Email message.', 'ultimate-classified-listings' ),
            ),

        ),

    ),

    array(
        'panel_title'   =>  __( 'Agent Contact Form', 'ultimate-classified-listings' ),
        'panel_name'   =>  'agent_contact_form',
        'icon'   =>  '<i class="bi bi-person-badge"></i>',
        'fields'        => array(
            array(
                'type' => 'text',
                'name' => 'email_subject',
                'title' => __( 'Email Subject', 'ultimate-classified-listings' ),
                'help' => __( 'Provide email subject here if someone contacts seller through listing page. You can also use these special tags.', 'ultimate-classified-listings' ).' <code>%listing_title%</code>, <code>%listing_id%</code>',
            ),

            array(
                'type' => 'textarea',
                'name' => 'email_message',
                'title' => __( 'Email Format', 'ultimate-classified-listings' ),
                'help' => __( 'Provide email markup here. You can also use these special tags.', 'ultimate-classified-listings' ). '<code>%listing_title%</code>, <code>%listing_id%</code>, <code>%listing_url%</code>, <code>%client_message%</code>, <code>%client_email%</code>, <code>%client_name%</code>, <code>%client_phone%</code>',
            ),

            array(
                'type' => 'textarea',
                'name' => 'email_addresses',
                'title' => __( 'Seller Contact Email Addresses', 'ultimate-classified-listings' ),
                'help' => __( 'Provide Additional Email addresses each per line to cc mail when visitor fills the contact seller form.', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'gdpr_message',
                'default' => 'I consent to have this site collect my Name, Email, and Phone.',
                'title' => __( 'GDPR Message', 'ultimate-classified-listings' ),
                'help' => __( 'Provide the message to display with the contact form with a required checkbox.', 'ultimate-classified-listings' ),
            ),
        ),
    ),

    array(

        'panel_title'   =>  __( 'reCAPTCHA V2', 'ultimate-classified-listings' ),
        'panel_name'   =>  'recaptcha',
        'icon'   =>  '<i class="bi bi-shield-check"></i>',
        'fields'        => array(

            array(
                'type' => 'text',
                'name' => 'captcha_site_key',
                'title' => __( 'Site key', 'ultimate-classified-listings' ),
                'help' => __( 'Provide Google reCAPTCHA V2 Site Key. You can create Site key ', 'ultimate-classified-listings' ).'<a target="_blank" href="https://www.google.com/recaptcha/admin">'.__( 'here', 'ultimate-classified-listings' ).'</a>',
            ),
            array(
                'type' => 'text',
                'name' => 'captcha_secret_key',
                'title' => __( 'Secret key', 'ultimate-classified-listings' ),
                'help' => __( 'Provide Google reCAPTCHA V2 Secret Key. You can create Secret key ', 'ultimate-classified-listings' ).'<a target="_blank" href="https://www.google.com/recaptcha/admin">'.__( 'here', 'ultimate-classified-listings' ).'</a>',
            ),
            array(
                'type' => 'checkbox',
                'name' => 'captcha_on_registration',
                'title' => __( 'Seller Registration', 'ultimate-classified-listings' ),
                'help' => __( 'Check to enable captcha on the registration form.', 'ultimate-classified-listings' ),
            ),
            array(
                'type' => 'checkbox',
                'name' => 'captcha_on_login',
                'title' => __( 'Seller Login', 'ultimate-classified-listings' ),
                'help' => __( 'Check to enable captcha on login form.', 'ultimate-classified-listings' ),
            ),
            array(
                'type' => 'checkbox',
                'name' => 'captcha_on_contact',
                'title' => __( 'Contact Seller', 'ultimate-classified-listings' ),
                'help' => __( 'Check to enable captcha on contact form.', 'ultimate-classified-listings' ),
            ),

        ),

    ),

    array(

        'panel_title'   =>  __( 'Labels and Headings', 'ultimate-classified-listings' ),
        'panel_name'   =>  'labels_headings',
        'icon'   =>  '<i class="bi bi-blockquote-left"></i>',
        'fields'        => array(

            array(
                'type' => 'text',
                'name' => 'archive_title',
                'title' => __( 'Heading for Listing Base Page', 'ultimate-classified-listings' ),
                'help' => __( 'Provide heading for listings archive', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'text',
                'name' => 'category_title',
                'title' => __( 'Heading for Category Base Page', 'ultimate-classified-listings' ),
                'help' => __( 'You can use %category% for category name', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'text',
                'name' => 'tag_title',
                'title' => __( 'Heading for Tag Base page', 'ultimate-classified-listings' ),
                'help' => __( 'You can use %tag% for tag name', 'ultimate-classified-listings' ),
            ),


            array(
                'type' => 'text',
                'name' => 'search_results_title',
                'title' => __( 'Search Results Title', 'ultimate-classified-listings' ),
                'default' => 'Search Results (%count%)',
                'help' => __( 'Provide text to display above the AJAX search results, you can use the variable', 'ultimate-classified-listings' ).'<code>%count%</code>',
            ),

            array(
                'type' => 'text',
                'name' => 'no_results_message',
                'title' => __( 'No Results Found Message', 'ultimate-classified-listings' ),
                'help' => __( 'Provide custom message when no listings found in search results', 'ultimate-classified-listings' ),
            ),
        ),

    ),

    array(

        'panel_title'   =>  __( 'Maps Settings', 'ultimate-classified-listings' ),
        'panel_name'   =>  'maps_settings',
        'icon'   =>  '<i class="bi bi-geo-alt"></i>',
        'fields'        => array(

            array(
                'type' => 'select',
                'name' => 'use_map_from',
                'title' => __( 'Use Map From', 'ultimate-classified-listings' ),
                'options' => array(
                    'leaflet' => __( 'Leaflet', 'ultimate-classified-listings' ),
                    'google_maps' => __( 'Google Maps', 'ultimate-classified-listings' ),
                ),                
                'help' => __( 'Choose map provider', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'text',
                'name' => 'maps_api_key',
                'title' => __( 'Google Maps API Key', 'ultimate-classified-listings' ),
                'help' => __( 'Provide Google Maps API key here. You can create API key ', 'ultimate-classified-listings' ).'<a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key">'.__( 'here', 'ultimate-classified-listings' ).'</a>',
                'show_if'  => array('use_map_from', 'google_maps'),
            ),

            array(
                'type' => 'select',
                'name' => 'maps_type',
                'title' => __( 'Map Type', 'ultimate-classified-listings' ),
                'options' => array(
                    'roadmap' => __( 'Road Map', 'ultimate-classified-listings' ),
                    'satellite' => __( 'Google Earth', 'ultimate-classified-listings' ),
                    'hybrid' => __( 'Hybrid', 'ultimate-classified-listings' ),
                    'terrain' => __( 'Terrain', 'ultimate-classified-listings' ),
                ),                
                'help' => __( 'Choose default map type here', 'ultimate-classified-listings' ),
                'show_if'  => array('use_map_from', 'google_maps'),
            ),

            array(
                'type' => 'text',
                'name' => 'maps_zoom_level',
                'title' => __( 'Map Zoom Level', 'ultimate-classified-listings' ),
                'help' => __( 'Provide Zoom level between 0 and 21+ for single listing map', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'image',
                'name' => 'maps_drag_image',
                'title' => __( 'Drag Icon URL', 'ultimate-classified-listings' ),
                'help' => __( 'Upload custom icon for dragging on map while creating new listing. Recommended size: 72x60', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'image',
                'name' => 'maps_location_image',
                'title' => __( 'Location Icon URL', 'ultimate-classified-listings' ),
                'help' => __( 'Upload custom icon for location on map when visiting listing page. Recommended size: 72x60', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'text',
                'name' => 'leaflet_icons_size',
                'title' => __( 'Icons Size', 'ultimate-classified-listings' ),
                'help' => __( 'Provide custom icons size. Default is ', 'ultimate-classified-listings' ).'<code>43x47</code>',
                'show_if'  => array('use_map_from', 'leaflet'),
            ),

            array(
                'type' => 'text',
                'name' => 'leaflet_icons_anchor',
                'title' => __( 'Icons Anchor', 'ultimate-classified-listings' ),
                'help' => __( 'Provide custom anchor point for the icons. Default is ', 'ultimate-classified-listings' ).'<code>18x47</code>',
                'show_if'  => array('use_map_from', 'leaflet'),
            ),

            array(
                'type' => 'image',
                'name' => 'maps_listing_image_hover',
                'title' => __( 'Property Icon URL (Hover)', 'ultimate-classified-listings' ),
                'help' => __( 'Upload custom icon for listing location marker on large map for hover state.', 'ultimate-classified-listings' ),
                'show_if'  => array('use_map_from', 'google_maps'),
            ),

            array(
                'type' => 'image',
                'name' => 'maps_circle_image',
                'title' => __( 'Circle Icon URL', 'ultimate-classified-listings' ),
                'help' => __( 'Upload custom icon for circle counter marker on large map.', 'ultimate-classified-listings' ),
                'show_if'  => array('use_map_from', 'google_maps'),
            ),

            array(
                'type' => 'image',
                'name' => 'maps_my_location_image',
                'title' => __( 'My Location Icon URL', 'ultimate-classified-listings' ),
                'help' => __( 'Upload custom icon for my location marker on large map.', 'ultimate-classified-listings' ),
                'show_if'  => array('use_map_from', 'google_maps'),
            ),
            
            array(
                'type' => 'text',
                'name' => 'default_map_lat',
                'title' => __( 'Default Latitude', 'ultimate-classified-listings' ),
                'help' => __( 'Provide latitude for default map location on create listing page', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'text',
                'name' => 'default_map_long',
                'title' => __( 'Default Longitude', 'ultimate-classified-listings' ),
                'help' => __( 'Provide longitude for default map location on create listing page', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'maps_styles',
                'title' => __( 'Map Styles Object', 'ultimate-classified-listings' ),
                'help' => __( 'Provide map styles here.', 'ultimate-classified-listings' ).' <a target="_blank" href="https://webcodingplace.com/15000-pre-made-map-styles-ultimate-classified-listings/">'.__( 'Help', 'ultimate-classified-listings' ).'</a>',
                'show_if'  => array('use_map_from', 'google_maps'),
            ),

            array(
                'type' => 'select',
                'name' => 'leaflet_style',
                'title' => __( 'Map Style', 'ultimate-classified-listings' ),
                'options' => $leaflet_map_styles,
                'help' => __( 'Choose style for leaflet map. ', 'ultimate-classified-listings' ).'<a target="_blank" href="https://webcodingplace.com/ultimate-classified-listings-wordpress-plugin/leaflet-map-styles-for-ultimate-classified-listings-wp-plugin/">'.__( 'Preview', 'ultimate-classified-listings' ).'</a>',
                'show_if'  => array('use_map_from', 'leaflet'),
            ), 

            array(
                'type' => 'select',
                'name' => 'listing_map_location_style',
                'title' => __( 'Display location as', 'ultimate-classified-listings' ),
                'options' => array(
                    'pin' => __( 'Exact Pin', 'ultimate-classified-listings' ),
                    'circle' => __( 'Radius Circle', 'ultimate-classified-listings' ),
                    ),
                'help' => __( 'How you want to display location on the single listing page', 'ultimate-classified-listings' ),
            ),            

            array(
                'type' => 'number',
                'name' => 'listing_map_radius',
                'title' => __( 'Circle Radius', 'ultimate-classified-listings' ),
                'help' => __( 'If above is set to Radius Circle, provide the radius in meters here', 'ultimate-classified-listings' ),
                'show_if'  => array('listing_map_location_style', 'circle'),
            ),
        ),

    ),

    array(

        'panel_title'   =>  __( 'Colors and CSS', 'ultimate-classified-listings' ),
        'panel_name'   =>  'colors_css',
        'icon'   =>  '<i class="bi bi-palette"></i>',
        'fields'        => array(

            array(
                'type' => 'color',
                'name' => 'ucl_primary_color',
                'title' => __( 'Primary Color', 'ultimate-classified-listings' ),
                'default' => '#f85c70',
                'help' => __( 'Choose main theme color for templates', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'color',
                'name' => 'ucl_secondary_color',
                'title' => __( 'Secondary Color', 'ultimate-classified-listings' ),
                'default' => '#0d1927',
                'help' => __( 'Choose secondary color for templates', 'ultimate-classified-listings' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'custom_css',
                'title' => __( 'Custom CSS Code', 'ultimate-classified-listings' ),
                'default' => '',
                'help' => __( 'Paste your custom css code here, you can prefix with', 'ultimate-classified-listings' ).'<code>.uclwp-bs-wrapper</code>',
            ),

            array(
                'type' => 'textarea',
                'name' => 'custom_js',
                'title' => __( 'Custom JavaScript Code', 'ultimate-classified-listings' ),
                'default' => '',
                'help' => __( 'Please keep this box empty if you are not sure what you are doing','ultimate-classified-listings' ),
            ),

        ),

    ),


    array(
        'panel_title'   =>  __( 'Advanced Settings', 'ultimate-classified-listings' ),
        'panel_name'   =>  'advanced_settings',
        'icon'   =>  '<i class="bi bi-gear-wide-connected"></i>',

        'fields'        => array(
            array(
                'type' => 'select',
                'name' => 'listing_submission_mode',
                'title' => __( 'Listing Submission Mode', 'ultimate-classified-listings' ),
                'options' => array(
                    'publish' => __( 'Publish Right Away', 'ultimate-classified-listings' ),
                    'approve' => __( 'Approve by Administrator', 'ultimate-classified-listings' ),
                ),
                'help' => __( 'Set permission for seller for creating new listings', 'ultimate-classified-listings' ),
            ),
            array(
                'type' => 'select',
                'name' => 'listing_deletion',
                'options' => array(
                    'delete' => __( 'Delete Permanently', 'ultimate-classified-listings' ),
                    'trash' => __( 'Move to Trash', 'ultimate-classified-listings' ),
                ),                
                'title' => __( 'Property Deletion', 'ultimate-classified-listings' ),
                'help' => __( 'What to do when a seller deletes a listing.', 'ultimate-classified-listings' ),
            ),
            array(
                'type' => 'select',
                'name' => 'attachment_deletion',
                'options' => array(
                    'remain' => __( 'Keep', 'ultimate-classified-listings' ),
                    'delete' => __( 'Delete', 'ultimate-classified-listings' ),
                ),                
                'title' => __( 'Attachments Deletion', 'ultimate-classified-listings' ),
                'help' => __( 'What to do with gallery images after deleting listing.', 'ultimate-classified-listings' ),
            ),
            array(
                'type' => 'select',
                'name' => 'seller_approval',
                'title' => __( 'Seller Approval', 'ultimate-classified-listings' ),
                'options' => array(
                    'manual' => __( 'Manual', 'ultimate-classified-listings' ),
                    'auto' => __( 'Automatic', 'ultimate-classified-listings' ),
                ),
                'help' => __( 'We recommend you to use manual method', 'ultimate-classified-listings' ),
            ),
            array(
                'type' => 'select',
                'name' => 'auto_login',
                'title' => __( 'Auto Login', 'ultimate-classified-listings' ),
                'options' => array(
                    'disable' => __( 'Disable', 'ultimate-classified-listings' ),
                    'enable' => __( 'Enable', 'ultimate-classified-listings' ),
                ),
                'help' => __( 'Auto-login newly registered seller.', 'ultimate-classified-listings' ),
                'show_if'  => array('seller_approval', 'auto'),
            ),
        ),
    ),
);

$fieldsData = apply_filters( 'ucl_admin_settings_fields', $fieldsData );
?>