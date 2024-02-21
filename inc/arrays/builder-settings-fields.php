<?php
    foreach ($sectionTabs as $tabData) {
        if (!uclwp_is_default_section($tabData)) {
            $tabOptions[$tabData['key']] = $tabData['title'];
        }   
    }
	$fields = array(
        array(
            'type' => 'text',
            'name' => 'title',
            'title' => __( 'Label', 'ultimate-classified-listings' ),
        ),
        array(
            'type' => 'text',
            'name' => 'key',
            'title' => __( 'Data Name (lowercase without spaces)', 'ultimate-classified-listings' ),
        ),
        array(
            'type' => 'textarea',
            'name' => 'options',
            'title' => __( 'Options (each per line)', 'ultimate-classified-listings' ),
            'show_if' => array('select', 'select2', 'checkboxes'),
        ),
        array(
            'type' => 'text',
            'name' => 'default',
            'title' => __( 'Default Value', 'ultimate-classified-listings' ),
        ),
        array(
            'type' => 'textarea',
            'name' => 'help',
            'title' => __( 'Help Text', 'ultimate-classified-listings' ),
        ),
        array(
            'type' => 'select',
            'name' => 'tab',
            'options' => $tabOptions,
            'title' => __( 'Section or Tab', 'ultimate-classified-listings' ),
        ),
        array(
            'type' => 'select',
            'name' => 'accessibility',
            'options' => array(
            	'public' => __( 'Public', 'ultimate-classified-listings' ),
            	'seller' => __( 'Seller', 'ultimate-classified-listings' ),
            	'admin' => __( 'Administrator', 'ultimate-classified-listings' ),
            	'disable' => __( 'Disable', 'ultimate-classified-listings' ),
            ),
            'title' => __( 'Accessibility', 'ultimate-classified-listings' ),
        ),
        array(
            'type' => 'checkbox',
            'name' => 'required',
            'title' => __( 'Required', 'ultimate-classified-listings' ),
        ),
        array(
            'type' => 'number',
            'name' => 'min_value',
            'title' => __( 'Minimum Value', 'ultimate-classified-listings' ),
            'show_if' => array('number'),
        ),
        array(
            'type' => 'number',
            'name' => 'max_value',
            'title' => __( 'Maximum Value', 'ultimate-classified-listings' ),
            'show_if' => array('number'),
        ),
        array(
            'type' => 'icon',
            'name' => 'icon',
            'title' => __( 'Icon', 'ultimate-classified-listings' ),
        ),
	);
?>