<?php
$leaflet_map_styles = array();

for ($style=1; $style <= 23 ; $style++) {
    $leaflet_map_styles[$style] =  __( 'Style', 'circular-arts-network' ).' '.$style;
}

$fieldsData = array(

    array(
        'panel_title'   =>  __( 'Currency Options', 'circular-arts-network' ),
        'panel_name'   =>  'currency_options',
        'icon'   =>  '<i class="bi bi-currency-exchange"></i>',

        'fields'        => array(

            array(
                'type' => 'currency',
                'name' => 'currency',
                'title' => __( 'Currency', 'circular-arts-network' ),
                'help' => __( 'Choose the default currency for the listings.', 'circular-arts-network' ),
            ),


            array(
                'type' => 'select',
                'name' => 'currency_position',
                'title' => __( 'Currency Position', 'circular-arts-network' ),
                'options' => array(
                    'left' => __( 'Left', 'circular-arts-network' ),
                    'right' => __( 'Right', 'circular-arts-network' ),
                    'left_space' => __( 'Left with Space', 'circular-arts-network' ),
                    'right_space' => __( 'Right with Space', 'circular-arts-network' ),
                ),
                'help' => __( 'Position of the Currency Symbol', 'circular-arts-network' ),
            ),

                array(
                    'type' => 'text',
                    'name' => 'thousand_separator',
                    'title' => __( 'Thousand Separator', 'circular-arts-network' ),
                    'help' => __( 'Thousand separator of display price', 'circular-arts-network' ),
                    'default' => ',',
                ),

                array(
                    'type' => 'text',
                    'name' => 'decimal_separator',
                    'title' => __( 'Decimal Separator', 'circular-arts-network' ),
                    'help' => __( 'Decimal separator of display price', 'circular-arts-network' ),
                    'default' => '.',
                ),

                array(
                    'type' => 'text',   
                    'name' => 'decimal_points',
                    'title' => __( 'Number of Decimals', 'circular-arts-network' ),
                    'help' => __( 'Number of decimal points shown in display price', 'circular-arts-network' ),
                    'default' => '2',
                ),
        ),

    ),

    array(
        
        'panel_title'   =>  __( 'Templates Settings', 'circular-arts-network' ),
        'panel_name'   =>  'template_settings',
        'icon'   =>  '<i class="bi bi-file-earmark-richtext"></i>',
        'fields'        => array(
            array(
                'type' => 'select',
                'name' => 'listings_base_page',
                'title' => __( 'Listing Base Page', 'circular-arts-network' ),
                'help' => __( 'If you choose custom, create a page having slug', 'circular-arts-network' ).
                ' <code>'.get_option( 'can_listing_permalink', 'listing' ).'</code> '.
                __( 'and it will be used as the listing base page. After changing this, go to Settings -> Permalinks and click save changes button.', 'circular-arts-network' ),
                'options' => array(
                    'default' => __( 'Default', 'circular-arts-network' ),
                    'custom' => __( 'Custom', 'circular-arts-network' ),
                ),
            ),
            array(
                'type'  => 'select',
                'name'  => 'seller_info',
                'title' => __( 'Listing Page Seller Info', 'circular-arts-network' ),
                'help'  => __( 'Enable or disable default seller info area and contact form on the listing page', 'circular-arts-network' ),
                'options' => array(
                    'enable' => __( 'Enable', 'circular-arts-network' ),
                    'disable' => __( 'Disable', 'circular-arts-network' ),
                ),
            ),
            array(
                'type'  => 'widget',
                'name'  => 'listing_page_sidebar',
                'title' => __( 'Listing Page Sidebar', 'circular-arts-network' ),
                'help'  => __( 'You can add your own widgets in the selected sidebar to display them with the listings.', 'circular-arts-network' ),
            ),
            array(
                'type' => 'select',
                'name' => 'gallery_type',
                'title' => __( 'Gallery Type', 'circular-arts-network' ),
                'help' => __( 'How you want to display gallery images on the single listing page', 'circular-arts-network' ),
                'options' => array(
                    'slick' => __( 'Simple Slider', 'circular-arts-network' ),
                    'grid' => __( 'Grid with Popup', 'circular-arts-network' ),
                ),
            ),
            array(
                'type' => 'text',
                'name' => 'grid_view_txt',
                'default' => 'View all %count% images',
                'title' => __( 'View All Images Text', 'circular-arts-network' ),
                'help' => __( 'If there are more than 5 images, this title will appear.', 'circular-arts-network' ),
                'show_if'  => array('gallery_type', 'grid'),
            ),

            array(
                'type' => 'select',
                'name' => 'slider_featured_image',
                'title' => __( 'Gallery Featured Image', 'circular-arts-network' ),
                'help' => __( 'Enable to display featured image in slider', 'circular-arts-network' ),
                'options' => array(
                    'enable' => __( 'Enable', 'circular-arts-network' ),
                    'disable' => __( 'Disable', 'circular-arts-network' ),
                ),
            ),

            array(
                'type'  => 'image_sizes',
                'name'  => 'gallery_image_size',
                'title' => __( 'Gallery Images Size', 'circular-arts-network' ),
                'help'  => __( 'Choose size for the gallery images', 'circular-arts-network' ),
            ),

            array(
                'type' => 'text',
                'name' => 'date_format',
                'title' => __( 'Date Field Format', 'circular-arts-network' ),
                'help' => __( 'Provide date format if you are using date field. Eg: ', 'circular-arts-network' ).' d-M-Y',
            ),
        ),

    ),

    array(
        'panel_title'   =>  __( 'Listings', 'circular-arts-network' ),
        'panel_name'   =>  'listings',
        'icon'   =>  '<i class="bi bi-columns-gap"></i>',

        'fields'        => array(
            array(
                'type' => 'text',
                'name' => 'listings_per_page',
                'title' => __( 'Listings Per Page', 'circular-arts-network' ),
                'help' => __( 'Number of listings you want to display on archive pages. (tags etc)', 'circular-arts-network' ),
            ),

            array(
                'type'  => 'image_sizes',
                'name'  => 'featured_image_size',
                'title' => __( 'Featured Image Size', 'circular-arts-network' ),
                'help'  => __( 'Choose size of featured image to use', 'circular-arts-network' ),
            ),

            array(
                'type'  => 'image',
                'name'  => 'placeholder_image',
                'title' => __( 'Featured Image Placeholder', 'circular-arts-network' ),
                'help'  => __( 'This image will be used for the listings without a featured image', 'circular-arts-network' ),
            ),
            array(
                'type' => 'select',
                'name' => 'enable_compare',
                'title' => __( 'Compare Listings', 'circular-arts-network' ),
                'help' => __( 'Choose either to enable or disable the compare listings feature', 'circular-arts-network' ),
                'options' => array(
                    'enable' => __( 'Enable', 'circular-arts-network' ),
                    'disable' => __( 'Disable', 'circular-arts-network' ),
                ),
            ),
            array(
                'type' => 'textarea',
                'name' => 'listing_compare_columns',
                'title' => __( 'Comparison Fields', 'circular-arts-network' ),
                'help' => __( 'Provide label and field key each per line to display in the compare screen. Eg:', 'circular-arts-network' ).'<code>Price|regular_price</code>',
                'show_if'  => array('enable_compare', 'enable'),
            ),            
        ),

    ),

    array(
        'panel_title'   =>  __( 'Search Settings', 'circular-arts-network' ),
        'panel_name'   =>  'search_settings',
        'icon'   =>  '<i class="bi bi-search"></i>',

        'fields'        => array(
            array(
                'type' => 'text',
                'name' => 'listings_per_results_page',
                'title' => __( 'Total Listings', 'circular-arts-network' ),
                'help' => __( 'Number of listings you want to display on search results', 'circular-arts-network' ),
            ),

            array(
                'type' => 'select',
                'name' => 'searched_listings_target',
                'title' => __( 'Search Results Link Target', 'circular-arts-network' ),
                'help' => __( 'How you want to open the listings when user clicks on the search results.', 'circular-arts-network' ),
                'options' => array(
                    '_blank' => __( 'New Tab', 'circular-arts-network' ),
                    '_self' => __( 'Same Tab', 'circular-arts-network' ),
                ),
            ),
        ),

    ),

    array(

        'panel_title'   =>  __( 'Email Messages', 'circular-arts-network' ),
        'panel_name'   =>  'email_messages',
        'icon'   =>  '<i class="bi bi-envelope"></i>',
        'fields'        => array(

            array(
                'type' => 'textarea',
                'name' => 'to_admin_on_seller_register',
                'title' => __( 'To Admin on Seller Registered', 'circular-arts-network' ),
                'help' => __( 'This message will sent to ', 'circular-arts-network' ).'<b>'.get_bloginfo('admin_email').'</b>'.__( ' when new seller is registered. You can use %username% and %email% for details', 'circular-arts-network' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'to_seller_registered',
                'title' => __( 'To Registered Seller', 'circular-arts-network' ),
                'help' => __( 'This message will be sent to the newly regisreted sellers. You can use %username% and %email% for details', 'circular-arts-network' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'to_seller_approved',
                'title' => __( 'To Approved Seller', 'circular-arts-network' ),
                'help' => __( 'This message will be sent to the approved seller. You can use %username% and %email% for details', 'circular-arts-network' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'to_seller_rejected',
                'title' => __( 'To Rejected Seller', 'circular-arts-network' ),
                'help' => __( 'This message will be sent to the rejected seller. You can use %username% and %email% for details', 'circular-arts-network' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'to_admin_submission',
                'title' => __( 'To Admin on Submission', 'circular-arts-network' ),
                'help' => __( 'This message will be sent to ', 'circular-arts-network' ).'<b>'.get_bloginfo('admin_email').'</b>'.__( ' when new listing is submitted. You can use variables %username% %approve_url% and %email% for details', 'circular-arts-network' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'to_seller_submission',
                'title' => __( 'To Seller on Submission', 'circular-arts-network' ),
                'help' => __( 'This message will be sent to the seller when a new listing is submitted.', 'circular-arts-network' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'to_seller_submission_approved',
                'title' => __( 'To Seller on Submission Approved', 'circular-arts-network' ),
                'help' => __( 'This message will be sent to seller when his listing is approved.', 'circular-arts-network' ),
            ),

            array(
                'type' => 'select',
                'name' => 'email_br',
                'title' => __( 'Line Breaks in Emails', 'circular-arts-network' ),
                'options' => array(
                    'enable' => __( 'Enable', 'circular-arts-network' ),
                    'disable' => __( 'Disable', 'circular-arts-network' ),
                ),
                'help' => __( 'Enable to inserts HTML line breaks before all newlines in the Email message.', 'circular-arts-network' ),
            ),

        ),

    ),

    array(
        'panel_title'   =>  __( 'Agent Contact Form', 'circular-arts-network' ),
        'panel_name'   =>  'agent_contact_form',
        'icon'   =>  '<i class="bi bi-person-badge"></i>',
        'fields'        => array(
            array(
                'type' => 'text',
                'name' => 'email_subject',
                'title' => __( 'Email Subject', 'circular-arts-network' ),
                'help' => __( 'Provide email subject here if someone contacts seller through listing page. You can also use these special tags.', 'circular-arts-network' ).' <code>%listing_title%</code>, <code>%listing_id%</code>',
            ),

            array(
                'type' => 'textarea',
                'name' => 'email_message',
                'title' => __( 'Email Format', 'circular-arts-network' ),
                'help' => __( 'Provide email markup here. You can also use these special tags.', 'circular-arts-network' ). '<code>%listing_title%</code>, <code>%listing_id%</code>, <code>%listing_url%</code>, <code>%client_message%</code>, <code>%client_email%</code>, <code>%client_name%</code>, <code>%client_phone%</code>',
            ),

            array(
                'type' => 'textarea',
                'name' => 'email_addresses',
                'title' => __( 'Seller Contact Email Addresses', 'circular-arts-network' ),
                'help' => __( 'Provide Additional Email addresses each per line to cc mail when visitor fills the contact seller form.', 'circular-arts-network' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'gdpr_message',
                'default' => 'I consent to have this site collect my Name, Email, and Phone.',
                'title' => __( 'GDPR Message', 'circular-arts-network' ),
                'help' => __( 'Provide the message to display with the contact form with a required checkbox.', 'circular-arts-network' ),
            ),
        ),
    ),

    array(

        'panel_title'   =>  __( 'reCAPTCHA V2', 'circular-arts-network' ),
        'panel_name'   =>  'recaptcha',
        'icon'   =>  '<i class="bi bi-shield-check"></i>',
        'fields'        => array(

            array(
                'type' => 'text',
                'name' => 'captcha_site_key',
                'title' => __( 'Site key', 'circular-arts-network' ),
                'help' => __( 'Provide Google reCAPTCHA V2 Site Key. You can create Site key ', 'circular-arts-network' ).'<a target="_blank" href="https://www.google.com/recaptcha/admin">'.__( 'here', 'circular-arts-network' ).'</a>',
            ),
            array(
                'type' => 'text',
                'name' => 'captcha_secret_key',
                'title' => __( 'Secret key', 'circular-arts-network' ),
                'help' => __( 'Provide Google reCAPTCHA V2 Secret Key. You can create Secret key ', 'circular-arts-network' ).'<a target="_blank" href="https://www.google.com/recaptcha/admin">'.__( 'here', 'circular-arts-network' ).'</a>',
            ),
            array(
                'type' => 'checkbox',
                'name' => 'captcha_on_registration',
                'title' => __( 'Seller Registration', 'circular-arts-network' ),
                'help' => __( 'Check to enable captcha on the registration form.', 'circular-arts-network' ),
            ),
            array(
                'type' => 'checkbox',
                'name' => 'captcha_on_login',
                'title' => __( 'Seller Login', 'circular-arts-network' ),
                'help' => __( 'Check to enable captcha on login form.', 'circular-arts-network' ),
            ),
            array(
                'type' => 'checkbox',
                'name' => 'captcha_on_contact',
                'title' => __( 'Contact Seller', 'circular-arts-network' ),
                'help' => __( 'Check to enable captcha on contact form.', 'circular-arts-network' ),
            ),

        ),

    ),

    array(

        'panel_title'   =>  __( 'Labels and Headings', 'circular-arts-network' ),
        'panel_name'   =>  'labels_headings',
        'icon'   =>  '<i class="bi bi-blockquote-left"></i>',
        'fields'        => array(

            array(
                'type' => 'text',
                'name' => 'archive_title',
                'title' => __( 'Heading for Listing Base Page', 'circular-arts-network' ),
                'help' => __( 'Provide heading for listings archive', 'circular-arts-network' ),
            ),

            array(
                'type' => 'text',
                'name' => 'category_title',
                'title' => __( 'Heading for Category Base Page', 'circular-arts-network' ),
                'help' => __( 'You can use %category% for category name', 'circular-arts-network' ),
            ),

            array(
                'type' => 'text',
                'name' => 'tag_title',
                'title' => __( 'Heading for Tag Base page', 'circular-arts-network' ),
                'help' => __( 'You can use %tag% for tag name', 'circular-arts-network' ),
            ),


            array(
                'type' => 'text',
                'name' => 'search_results_title',
                'title' => __( 'Search Results Title', 'circular-arts-network' ),
                'default' => 'Search Results (%count%)',
                'help' => __( 'Provide text to display above the AJAX search results, you can use the variable', 'circular-arts-network' ).'<code>%count%</code>',
            ),

            array(
                'type' => 'text',
                'name' => 'no_results_message',
                'title' => __( 'No Results Found Message', 'circular-arts-network' ),
                'help' => __( 'Provide custom message when no listings found in search results', 'circular-arts-network' ),
            ),
        ),

    ),

    array(

        'panel_title'   =>  __( 'Maps Settings', 'circular-arts-network' ),
        'panel_name'   =>  'maps_settings',
        'icon'   =>  '<i class="bi bi-geo-alt"></i>',
        'fields'        => array(

            array(
                'type' => 'select',
                'name' => 'use_map_from',
                'title' => __( 'Use Map From', 'circular-arts-network' ),
                'options' => array(
                    'leaflet' => __( 'Leaflet', 'circular-arts-network' ),
                    'google_maps' => __( 'Google Maps', 'circular-arts-network' ),
                ),                
                'help' => __( 'Choose map provider', 'circular-arts-network' ),
            ),

            array(
                'type' => 'text',
                'name' => 'maps_api_key',
                'title' => __( 'Google Maps API Key', 'circular-arts-network' ),
                'help' => __( 'Provide Google Maps API key here. You can create API key ', 'circular-arts-network' ).'<a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key">'.__( 'here', 'circular-arts-network' ).'</a>',
                'show_if'  => array('use_map_from', 'google_maps'),
            ),

            array(
                'type' => 'select',
                'name' => 'maps_type',
                'title' => __( 'Map Type', 'circular-arts-network' ),
                'options' => array(
                    'roadmap' => __( 'Road Map', 'circular-arts-network' ),
                    'satellite' => __( 'Google Earth', 'circular-arts-network' ),
                    'hybrid' => __( 'Hybrid', 'circular-arts-network' ),
                    'terrain' => __( 'Terrain', 'circular-arts-network' ),
                ),                
                'help' => __( 'Choose default map type here', 'circular-arts-network' ),
                'show_if'  => array('use_map_from', 'google_maps'),
            ),

            array(
                'type' => 'text',
                'name' => 'maps_zoom_level',
                'title' => __( 'Map Zoom Level', 'circular-arts-network' ),
                'help' => __( 'Provide Zoom level between 0 and 21+ for single listing map', 'circular-arts-network' ),
            ),

            array(
                'type' => 'image',
                'name' => 'maps_drag_image',
                'title' => __( 'Drag Icon URL', 'circular-arts-network' ),
                'help' => __( 'Upload custom icon for dragging on map while creating new listing. Recommended size: 72x60', 'circular-arts-network' ),
            ),

            array(
                'type' => 'image',
                'name' => 'maps_location_image',
                'title' => __( 'Location Icon URL', 'circular-arts-network' ),
                'help' => __( 'Upload custom icon for location on map when visiting listing page. Recommended size: 72x60', 'circular-arts-network' ),
            ),

            array(
                'type' => 'text',
                'name' => 'leaflet_icons_size',
                'title' => __( 'Icons Size', 'circular-arts-network' ),
                'help' => __( 'Provide custom icons size. Default is ', 'circular-arts-network' ).'<code>43x47</code>',
                'show_if'  => array('use_map_from', 'leaflet'),
            ),

            array(
                'type' => 'text',
                'name' => 'leaflet_icons_anchor',
                'title' => __( 'Icons Anchor', 'circular-arts-network' ),
                'help' => __( 'Provide custom anchor point for the icons. Default is ', 'circular-arts-network' ).'<code>18x47</code>',
                'show_if'  => array('use_map_from', 'leaflet'),
            ),

            array(
                'type' => 'image',
                'name' => 'maps_listing_image_hover',
                'title' => __( 'Property Icon URL (Hover)', 'circular-arts-network' ),
                'help' => __( 'Upload custom icon for listing location marker on large map for hover state.', 'circular-arts-network' ),
                'show_if'  => array('use_map_from', 'google_maps'),
            ),

            array(
                'type' => 'image',
                'name' => 'maps_circle_image',
                'title' => __( 'Circle Icon URL', 'circular-arts-network' ),
                'help' => __( 'Upload custom icon for circle counter marker on large map.', 'circular-arts-network' ),
                'show_if'  => array('use_map_from', 'google_maps'),
            ),

            array(
                'type' => 'image',
                'name' => 'maps_my_location_image',
                'title' => __( 'My Location Icon URL', 'circular-arts-network' ),
                'help' => __( 'Upload custom icon for my location marker on large map.', 'circular-arts-network' ),
                'show_if'  => array('use_map_from', 'google_maps'),
            ),
            
            array(
                'type' => 'text',
                'name' => 'default_map_lat',
                'title' => __( 'Default Latitude', 'circular-arts-network' ),
                'help' => __( 'Provide latitude for default map location on create listing page', 'circular-arts-network' ),
            ),

            array(
                'type' => 'text',
                'name' => 'default_map_long',
                'title' => __( 'Default Longitude', 'circular-arts-network' ),
                'help' => __( 'Provide longitude for default map location on create listing page', 'circular-arts-network' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'maps_styles',
                'title' => __( 'Map Styles Object', 'circular-arts-network' ),
                'help' => __( 'Provide map styles here.', 'circular-arts-network' ).' <a target="_blank" href="https://webcodingplace.com/15000-pre-made-map-styles-circular-arts-network/">'.__( 'Help', 'circular-arts-network' ).'</a>',
                'show_if'  => array('use_map_from', 'google_maps'),
            ),

            array(
                'type' => 'select',
                'name' => 'leaflet_style',
                'title' => __( 'Map Style', 'circular-arts-network' ),
                'options' => $leaflet_map_styles,
                'help' => __( 'Choose style for leaflet map. ', 'circular-arts-network' ).'<a target="_blank" href="https://webcodingplace.com/circular-arts-network-wordpress-plugin/leaflet-map-styles-for-circular-arts-network-wp-plugin/">'.__( 'Preview', 'circular-arts-network' ).'</a>',
                'show_if'  => array('use_map_from', 'leaflet'),
            ), 

            array(
                'type' => 'select',
                'name' => 'listing_map_location_style',
                'title' => __( 'Display location as', 'circular-arts-network' ),
                'options' => array(
                    'pin' => __( 'Exact Pin', 'circular-arts-network' ),
                    'circle' => __( 'Radius Circle', 'circular-arts-network' ),
                    ),
                'help' => __( 'How you want to display location on the single listing page', 'circular-arts-network' ),
            ),            

            array(
                'type' => 'number',
                'name' => 'listing_map_radius',
                'title' => __( 'Circle Radius', 'circular-arts-network' ),
                'help' => __( 'If above is set to Radius Circle, provide the radius in meters here', 'circular-arts-network' ),
                'show_if'  => array('listing_map_location_style', 'circle'),
            ),
        ),

    ),

    array(

        'panel_title'   =>  __( 'Colors and CSS', 'circular-arts-network' ),
        'panel_name'   =>  'colors_css',
        'icon'   =>  '<i class="bi bi-palette"></i>',
        'fields'        => array(

            array(
                'type' => 'color',
                'name' => 'can_primary_color',
                'title' => __( 'Primary Color', 'circular-arts-network' ),
                'default' => '#f85c70',
                'help' => __( 'Choose main theme color for templates', 'circular-arts-network' ),
            ),

            array(
                'type' => 'color',
                'name' => 'can_secondary_color',
                'title' => __( 'Secondary Color', 'circular-arts-network' ),
                'default' => '#0d1927',
                'help' => __( 'Choose secondary color for templates', 'circular-arts-network' ),
            ),

            array(
                'type' => 'textarea',
                'name' => 'custom_css',
                'title' => __( 'Custom CSS Code', 'circular-arts-network' ),
                'default' => '',
                'help' => __( 'Paste your custom css code here, you can prefix with', 'circular-arts-network' ).'<code>.can-bs-wrapper</code>',
            ),

            array(
                'type' => 'textarea',
                'name' => 'custom_js',
                'title' => __( 'Custom JavaScript Code', 'circular-arts-network' ),
                'default' => '',
                'help' => __( 'Please keep this box empty if you are not sure what you are doing','circular-arts-network' ),
            ),

        ),

    ),


    array(
        'panel_title'   =>  __( 'Advanced Settings', 'circular-arts-network' ),
        'panel_name'   =>  'advanced_settings',
        'icon'   =>  '<i class="bi bi-gear-wide-connected"></i>',

        'fields'        => array(
            array(
                'type' => 'select',
                'name' => 'listing_submission_mode',
                'title' => __( 'Listing Submission Mode', 'circular-arts-network' ),
                'options' => array(
                    'publish' => __( 'Publish Right Away', 'circular-arts-network' ),
                    'approve' => __( 'Approve by Administrator', 'circular-arts-network' ),
                ),
                'help' => __( 'Set permission for seller for creating new listings', 'circular-arts-network' ),
            ),
            array(
                'type' => 'select',
                'name' => 'listing_deletion',
                'options' => array(
                    'delete' => __( 'Delete Permanently', 'circular-arts-network' ),
                    'trash' => __( 'Move to Trash', 'circular-arts-network' ),
                ),                
                'title' => __( 'Property Deletion', 'circular-arts-network' ),
                'help' => __( 'What to do when a seller deletes a listing.', 'circular-arts-network' ),
            ),
            array(
                'type' => 'select',
                'name' => 'attachment_deletion',
                'options' => array(
                    'remain' => __( 'Keep', 'circular-arts-network' ),
                    'delete' => __( 'Delete', 'circular-arts-network' ),
                ),                
                'title' => __( 'Attachments Deletion', 'circular-arts-network' ),
                'help' => __( 'What to do with gallery images after deleting listing.', 'circular-arts-network' ),
            ),
            array(
                'type' => 'select',
                'name' => 'seller_approval',
                'title' => __( 'Seller Approval', 'circular-arts-network' ),
                'options' => array(
                    'manual' => __( 'Manual', 'circular-arts-network' ),
                    'auto' => __( 'Automatic', 'circular-arts-network' ),
                ),
                'help' => __( 'We recommend you to use manual method', 'circular-arts-network' ),
            ),
            array(
                'type' => 'select',
                'name' => 'auto_login',
                'title' => __( 'Auto Login', 'circular-arts-network' ),
                'options' => array(
                    'disable' => __( 'Disable', 'circular-arts-network' ),
                    'enable' => __( 'Enable', 'circular-arts-network' ),
                ),
                'help' => __( 'Auto-login newly registered seller.', 'circular-arts-network' ),
                'show_if'  => array('seller_approval', 'auto'),
            ),
        ),
    ),
);

$fieldsData = apply_filters( 'can_admin_settings_fields', $fieldsData );
?>