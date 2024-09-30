<?php
    foreach ($sectionTabs as $tabData) {
        if (!can_is_default_section($tabData)) {
            $tabOptions[$tabData['key']] = $tabData['title'];
        }   
    }
	$fields = array(
        array(
            'type' => 'text',
            'name' => 'title',
            'title' => __( 'Label', 'circular-arts-network' ),
        ),
        array(
            'type' => 'text',
            'name' => 'key',
            'title' => __( 'Data Name (lowercase without spaces)', 'circular-arts-network' ),
        ),
        array(
            'type' => 'textarea',
            'name' => 'options',
            'title' => __( 'Options (each per line)', 'circular-arts-network' ),
            'show_if' => array('select', 'select2', 'checkboxes'),
        ),
        array(
            'type' => 'text',
            'name' => 'default',
            'title' => __( 'Default Value', 'circular-arts-network' ),
        ),
        array(
            'type' => 'textarea',
            'name' => 'help',
            'title' => __( 'Help Text', 'circular-arts-network' ),
        ),
        array(
            'type' => 'select',
            'name' => 'tab',
            'options' => $tabOptions,
            'title' => __( 'Section or Tab', 'circular-arts-network' ),
        ),
        array(
            'type' => 'select',
            'name' => 'accessibility',
            'options' => array(
            	'public' => __( 'Public', 'circular-arts-network' ),
            	'seller' => __( 'Seller', 'circular-arts-network' ),
            	'admin' => __( 'Administrator', 'circular-arts-network' ),
            	'disable' => __( 'Disable', 'circular-arts-network' ),
            ),
            'title' => __( 'Accessibility', 'circular-arts-network' ),
        ),
        array(
            'type' => 'checkbox',
            'name' => 'required',
            'title' => __( 'Required', 'circular-arts-network' ),
        ),
        array(
            'type' => 'number',
            'name' => 'min_value',
            'title' => __( 'Minimum Value', 'circular-arts-network' ),
            'show_if' => array('number'),
        ),
        array(
            'type' => 'number',
            'name' => 'max_value',
            'title' => __( 'Maximum Value', 'circular-arts-network' ),
            'show_if' => array('number'),
        ),
        array(
            'type' => 'icon',
            'name' => 'icon',
            'title' => __( 'Icon', 'circular-arts-network' ),
        ),
	);
?>