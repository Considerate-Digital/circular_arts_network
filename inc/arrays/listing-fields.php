<?php
    $inputFields = array(
	array(
            'key' => 'purpose',
            'type' => 'select',
            'tab' => 'details',
            'options' => array(
                __( 'Give', 'circular-arts-network' ),
                __( 'Sell', 'circular-arts-network' ),
                __( 'Lend', 'circular-arts-network' ),
                __( 'Rent', 'circular-arts-network' ),
                __( 'Wanted', 'circular-arts-network' ),
            ),
            'default' => 'Give',
            'icon' => 'bi bi-gift',
            'title' => __( 'Purpose', 'circular-arts-network' ),
            'help' => __( 'Why you are listing the item', 'circular-arts-network' ),
        ),

        array(
            'key' => 'regular_price',
            'type' => 'price',
            'tab' => 'details',
            'default' => '0',
            'icon' => 'bi bi-currency-dollar',
            'title' => __( 'Price', 'circular-arts-network' ),
            'help' => __( 'Price for the listing', 'circular-arts-network' ),
        ),
	array(
            'key' => 'condition',
            'type' => 'select',
            'tab' => 'details',
            'options' => array(
                __( 'Used', 'circular-arts-network' ),
                __( 'New', 'circular-arts-network' ),
            ),
            'default' => 'Used',
            'icon' => 'bi bi-box-seam',
            'title' => __( 'Condition', 'circular-arts-network' ),
            'help' => __( 'Visual condition of the item', 'circular-arts-network' ),
        ),
	/*
        array(
            'key' => 'build_date',
            'type' => 'date',
            'tab' => 'details',
            'default' => '',
            'icon' => 'bi bi-calendar-day',
            'title' => __( 'Build Date', 'circular-arts-network' ),
            'help' => __( 'Build date of the item if applicable', 'circular-arts-network' ),
        ),
	 */

        array(
            'key' => 'model',
            'type' => 'text',
            'tab' => 'details',
            'default' => '',
            'icon' => 'bi bi-app-indicator',
            'title' => __( 'Model', 'circular-arts-network' ),
            'help' => __( 'Model of the item if applicable', 'circular-arts-network' ),
        ),
	/*
        array(
            'key' => 'listing_country',
            'type' => 'text',
            'tab' => 'details',
            'default' => '',
            'icon' => 'bi bi-map',
            'title' => __( 'Country', 'circular-arts-network' ),
            'help' => __( 'Country', 'circular-arts-network' ),
        ),
	*/
        
        array(
            'key' => 'listing_address',
            'type' => 'text',
            'tab' => 'details',
            'default' => '',
            'icon' => 'bi bi-geo-alt',
            'title' => __( 'Address', 'circular-arts-network' ),
            'help' => __( 'Do not provide your address here if you do not want it publicly visible.', 'circular-arts-network' ),
        ),

	array(
            'key' => 'listing_city',
            'type' => 'text',
            'tab' => 'details',
            'default' => '',
            'icon' => 'bi bi-geo',
            'title' => __( 'City', 'circular-arts-network' ),
            'help' => __( 'City', 'circular-arts-network' ),
        ),

        array(
            'key' => 'listing_zipcode',
            'type' => 'text',
            'tab' => 'details',
            'default' => '',
            'icon' => 'bi bi-bounding-box',
            'title' => __( 'Postcode', 'circular-arts-network' ),
            'help' => __( 'Postcode or Zipcode', 'circular-arts-network' ),
        ),

        array(
            'key' => 'listing_features',
            'type' => 'checkboxes',
            'tab' => 'features',
            'default' => '',
            'options' => array(
                __( 'Free delivery available', 'circular-arts-network' ),
                __( 'Paid delivery available', 'circular-arts-network' ),
            ),
            'icon' => 'bi bi-boxes',
            'title' => __( 'Collection/Delivery', 'circular-arts-network' ),
            'help' => __( 'Choose your collection and delivery options', 'circular-arts-network' ),
        ),
        array(
            'key' => 'listing_video',
            'type' => 'video',
            'tab' => 'video',
            'default' => '',
            'icon' => 'bi bi-play-btn',
            'title' => __( 'Video URL', 'circular-arts-network' ),
            'help' => __( 'Provide video URL', 'circular-arts-network' ),
        ),
        array(
            'key' => 'listing_ribbon',
            'type' => 'text',
            'tab' => 'details',
            'default' => '',
            'accessibility' => 'admin',
            'icon' => '',
            'title' => __( 'Ribbon Text', 'circular-arts-network' ),
            'help' => __( 'Provide text for the ribbon', 'circular-arts-network' ),
        ),
    );
?>
