<?php
/**
* CAN - Admin Settings Page and Fields Builder
*/
class CAN_Admin_Settings
{
	
	function __construct(){
		add_action( 'admin_menu', array($this, 'menu_pages') );
		add_action( 'admin_enqueue_scripts', array($this, 'load_admin_scripts') );
        add_action( 'add_meta_boxes', array($this, 'listing_metaboxes' ) );
        add_action( 'save_post', array($this, 'save_listing' ) );

        /**
         * AJAX Callbacks
         */
        add_action( 'wp_ajax_can_save_field_sections', array($this, 'save_field_sections' ) );
        add_action( 'wp_ajax_can_save_custom_fields', array($this, 'save_custom_fields' ) );
        add_action( 'wp_ajax_can_reset_custom_fields', array($this, 'reset_custom_fields' ) );
        add_action( 'wp_ajax_wcp_rem_save_settings', array($this, 'save_admin_settings' ) );

        // Seller Approve/ Deny
        add_action( 'wp_ajax_can_deny_seller', array($this, 'deny_seller' ) );
        add_action( 'wp_ajax_can_approve_seller', array($this, 'approve_seller' ) );

        // Image in Category    
        add_action( 'can_listing_category_add_form_fields', array( $this, 'add_category_image' ), 10, 1 );
        add_action( 'created_can_listing_category', array( $this, 'save_category_image' ), 10, 2 );
        add_action( 'can_listing_category_edit_form_fields', array( $this, 'edit_category_image' ), 10, 2 );
        add_action( 'edited_can_listing_category', array( $this, 'updated_category_image' ), 10, 2 );
        add_action( 'pre_get_posts', array($this, 'archive_page_listings_count'), 99 );

        add_action( 'transition_post_status', array($this, 'listing_submission_email'), 10, 3 );
        add_filter( 'wp_kses_allowed_html', array($this, 'custom_wpkses_post_tags'), 10, 2 );
	}

    function menu_pages(){

        /**
         * Listing Fields Sections
         */
        add_submenu_page(
            'edit.php?post_type=can_listing',
            __( 'Fields Sections', 'circular-arts-network' ),
            __( 'Fields Sections', 'circular-arts-network' ),
            'manage_options',
            'can_listing_fields_sections',
            array($this, 'can_fields_sections_callback')
        );
    	
        /**
         * Listing Fields Builder
         */
        add_submenu_page(
            'edit.php?post_type=can_listing',
            __( 'Fields Builder', 'circular-arts-network' ),
            __( 'Fields Builder', 'circular-arts-network' ),
            'manage_options',
            'can_listing_fields_builder',
            array($this, 'can_fields_builder_callback')
        );
        
        /**
         * Listing Fields Builder
         */
        add_submenu_page(
            'edit.php?post_type=can_listing',
            __( 'Sellers', 'circular-arts-network' ),
            __( 'Sellers', 'circular-arts-network' ),
            'manage_options',
            'can_listing_sellers',
            array($this, 'can_lising_sellers')
        );
        
    	/**
    	 * Settings Page
    	 */
        add_submenu_page(
            'edit.php?post_type=can_listing',
            __( 'Settings', 'circular-arts-network' ),
            __( 'Settings', 'circular-arts-network' ),
            'manage_options',
            'can_settings',
            array($this, 'can_settings_page_callback')
        );
    }

    function listing_metaboxes(){
        add_meta_box( 'listing_info_meta_box', __( 'Listing Information', 'circular-arts-network' ), array($this, 'render_listing_settings' ), array('can_listing'));
    }

    function save_listing($post_id){
        // verify if this is an auto save routine. 
        // If it is our form has not been submitted, so we dont want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return;

        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times
        if ( !isset( $_POST['can_listing_info_nonce'] ) )
            return;


        if ( !wp_verify_nonce( $_POST['can_listing_info_nonce'], plugin_basename( __FILE__ ) ) )
            return;
        
        // OK, we're authenticated: we need to find and save the data
        if (isset($_POST['can_data']) && !empty($_POST['can_data'])) {
            foreach ($_POST['can_data'] as $key => $value) {
                if (is_array($value)) {
                    $value = array_map( 'sanitize_text_field', $value );
                    update_post_meta($post_id, 'can_'.$key, $value);
                } else {
                    update_post_meta($post_id, 'can_'.$key, wp_kses_post( $value ));
                }
            }
        }

        // Saving Gallery Images
        if (isset($_POST['gallery_images']) && $_POST['gallery_images'] != '') {
            $images = array_map( 'sanitize_text_field', $_POST['gallery_images'] );
            update_post_meta( $post_id, 'can_gallery_images', $images );
        } else {
            update_post_meta( $post_id, 'can_gallery_images', '' );
        }

        // Saving Location
        if (isset($_POST['can_listing_latitude']) && $_POST['can_listing_latitude'] != '') {
            update_post_meta( $post_id, 'can_listing_latitude', sanitize_text_field($_POST['can_listing_latitude']) );
        }
        if (isset($_POST['can_listing_longitude']) && $_POST['can_listing_longitude'] != '') {
            update_post_meta( $post_id, 'can_listing_longitude', sanitize_text_field($_POST['can_listing_longitude']) );
        }


    }

    function render_listing_settings(){
        $field_sections = $this->get_fields_sections();
        $inputFields = can_get_listing_fields();
        wp_nonce_field( plugin_basename( __FILE__ ), 'can_listing_info_nonce' );
        include_once CAN_PATH.'/inc/admin/metabox-info.php';
    }

    function can_fields_builder_callback(){
        $fields_data = $this->get_builder_settings_fields();
        $field_types = $this->get_listing_field_types();
        $saved_fields = get_option( 'can_listing_fields' );
        include_once CAN_PATH. '/inc/admin/fields-builder.php';
    }

    function can_fields_sections_callback(){
        $field_sections = $this->get_fields_sections();
        $accessibilities = $this->get_section_accessibilities();
    	include_once CAN_PATH. '/inc/admin/fields-sections.php';
    }

    function can_settings_page_callback(){
        include_once CAN_PATH. '/inc/admin/page-settings.php';
    }

    function admin_settings_fields(){
        include CAN_PATH.'/inc/arrays/admin-settings.php';
        return $fieldsData;
    }

    function can_lising_sellers(){
        include_once CAN_PATH. '/inc/admin/page-sellers.php';
    }

    function render_setting_field($field){
        ob_start();
        include CAN_PATH.'/inc/admin/render-admin-settings.php';
        $field_html = ob_get_clean();
        return apply_filters( 'can_admin_settings_field_raw_html', $field_html, $field );
    }

    function load_admin_scripts($slug){

        // Basic scripts for all ucl admin pages
    	if(strpos($slug, "can") !== -1){
            wp_enqueue_style('can-bs', CAN_URL."/assets/libs/css/bootstrap.css");
            wp_enqueue_style('can-icons', CAN_URL."/assets/libs/icons/bootstrap-icons.css");
			wp_enqueue_script( 'can-sweetalert', CAN_URL . '/assets/libs/sweetalert/sweetalert2.all.min.js', array( 'jquery' ));
    	}

        // Fields Sections
        if($slug == 'can_listing_page_can_listing_fields_sections'){
            wp_enqueue_style( 'can-fields-sections', CAN_URL . '/assets/css/fields-sections.css');
            wp_enqueue_script('can-fields-sections', CAN_URL."/assets/js/fields-sections.js", array( 'jquery', 'jquery-ui-sortable', 'jquery-effects-highlight' ));
        }

        // Fields Buider   
        if ($slug == 'can_listing_page_can_listing_fields_builder') {
            wp_enqueue_style( 'can-iconpicker', CAN_URL . '/assets/libs/iconpicker/jquery.fonticonpicker.min.css');
            wp_enqueue_style( 'can-iconpicker-grey', CAN_URL . '/assets/libs/iconpicker/jquery.fonticonpicker.grey.min.css');
            wp_enqueue_script( 'can-iconpicker', CAN_URL . '/assets/libs/iconpicker/jquery.fonticonpicker.min.js', array('jquery'));
            wp_enqueue_style( 'can-fields-builder', CAN_URL . '/assets/css/fields-builder.css' );
            wp_enqueue_script( 'can-fields-builder', CAN_URL . '/assets/js/fields-builder.js'  , array( 'jquery', 'jquery-ui-accordion', 'jquery-ui-sortable', 'jquery-ui-draggable' ));
        }

        // Settings Page   
        if ($slug == 'can_listing_page_can_settings') {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_media();
            wp_enqueue_script( 'can-conditionize', CAN_URL . '/assets/js/conditionize.js' , array('jquery'));
            wp_enqueue_script( 'can-save-settings-js', CAN_URL . '/assets/js/page-settings.js' , array('jquery', 'wp-color-picker' ));
        }

        if ($slug == 'edit-tags.php' || $slug == 'term.php') {
            if (isset($_GET['post_type']) && 'can_listing' === $_GET['post_type']) {
                wp_enqueue_media();
                wp_enqueue_style('can-bs', CAN_URL."/assets/libs/css/bootstrap.css");
                wp_enqueue_style( 'can-admin', CAN_URL . '/assets/css/admin.css');
                wp_enqueue_style('can-icons', CAN_URL."/assets/libs/icons/bootstrap-icons.css");
                wp_enqueue_style( 'can-iconpicker', CAN_URL . '/assets/libs/iconpicker/jquery.fonticonpicker.min.css');
                wp_enqueue_style( 'can-iconpicker-grey', CAN_URL . '/assets/libs/iconpicker/jquery.fonticonpicker.grey.min.css');
                wp_enqueue_script( 'can-iconpicker', CAN_URL . '/assets/libs/iconpicker/jquery.fonticonpicker.min.js', array('jquery'));
                wp_enqueue_script( 'can-category-admin', CAN_URL . '/assets/js/category.js', array('jquery'));
            }
        }

        if($slug == 'can_listing_page_can_listing_sellers'){
            wp_enqueue_script( 'can-manage-sellers', CAN_URL . '/assets/js/manage-sellers.js'  , array('jquery'));
        }

    }

    function get_builder_settings_fields(){
        $fields = array();
        $sectionTabs = $this->get_fields_sections();
        include CAN_PATH.'/inc/arrays/builder-settings-fields.php';
        return $fields;
    }

    function get_listing_field_types(){
        $field_types = array(
            'text' => __( 'Text Field', 'circular-arts-network' ),
            'price' => __( 'Price Field', 'circular-arts-network' ),
            'number' => __( 'Number Field', 'circular-arts-network' ),
            'select' => __( 'DropDown Field', 'circular-arts-network' ),
            'checkboxes' => __( 'Multi Checkboxes', 'circular-arts-network' ),
            'date' => __( 'Date Field', 'circular-arts-network' ),
            'video' => __( 'Video URL', 'circular-arts-network' ),
            'textarea' => __( 'Text Area', 'circular-arts-network' ),
            'shortcode' => __( 'Shortcode', 'circular-arts-network' ),
        );
        
        return apply_filters( 'can_listing_field_types', $field_types );
    }

    function get_section_accessibilities(){
        $accessibilities = array(
            'public'        => __('Public', 'circular-arts-network' ),
            'seller'         => __('Seller', 'circular-arts-network' ),
            'registered'    => __('Registered Users', 'circular-arts-network' ),
            'admin'         => __('Administrator', 'circular-arts-network' ),
            'disable'       => __('Disable', 'circular-arts-network' ),
        );
        return apply_filters( 'can_section_accessibilities', $accessibilities );
    }

    function render_fields_builder_field_heading($title, $label){
        ?>
        <b><?php echo ($title != '') ? stripcslashes($title).' - ' : '' ; ?></b>
        <?php echo esc_attr( $label ); ?>
        <span class="float-end btn btn-sm btn-outline-primary trigger-sort">
            <i class="bi bi-arrows-move"></i>
        </span>
        <a href="#" class="btn btn-sm btn-outline-primary float-end trigger-toggle">
            <i class="bi bi-arrows-expand"></i>
        </a>
        <a href="#" class="float-end btn btn-sm btn-outline-danger remove-field">
            <i class="bi bi-trash3"></i>
        </a>
        <div class="clearfix"></div>
        <?php
    }

    function render_fields_builder_field($field, $data){
        $render_it = true;
        if (isset($field['show_if'])) {
            if (!in_array($data['type'], $field['show_if'])) {
                $render_it = false;
            }
        }
        if ($render_it) {
            include CAN_PATH.'/inc/admin/render-builder-field.php';
        }
    }

    function get_fields_sections(){
        $savedSections = get_option('can_field_sections');
        if ($savedSections != '' && is_array($savedSections)) {
            $fieldsSections = $savedSections;
        } else {
            $fieldsSections = array(
                array(
                    'title'     => __( 'Title and Description', 'circular-arts-network' ),
                    'key'       => 'description',
                    'icon'      => '',
                    'accessibility' => 'public',
                ),
		array(
                    'title'     => __( 'Category', 'circular-arts-network' ),
                    'key'       => 'category',
                    'icon'      => '',
                    'accessibility' => 'public',
                ),
                array(
                    'title'     => __( 'Gallery Images', 'circular-arts-network' ),
                    'key'       => 'gallery_images',
                    'icon'      => '',
                    'accessibility' => 'public',
                ),
                array(
                    'title'     => __( 'Details', 'circular-arts-network' ),
                    'key'       => 'details',
                    'icon'      => '',
                    'accessibility' => 'public',
                ),
                array(
                    'title'     => __( 'Features', 'circular-arts-network' ),
                    'key'       => 'features',
                    'icon'      => '',
                    'accessibility' => 'public',
                ),
                array(
                    'title'     => __( 'Location', 'circular-arts-network' ),
                    'key'       => 'location',
                    'icon'      => '',
                    'accessibility' => 'public',
                ),
                array(
                    'title'     => __( 'Video', 'circular-arts-network' ),
                    'key'       => 'video',
                    'icon'      => '',
                    'accessibility' => 'public',
                ),
                array(
                    'title'     => __( 'Tags', 'circular-arts-network' ),
                    'key'       => 'tags',
                    'icon'      => '',
                    'accessibility' => 'public',
                ),
            );
        }

        $fieldsSections = apply_filters('can_fields_sections', $fieldsSections);

        return $fieldsSections;
    }

    function save_field_sections(){
        $resp = array(
            'status' => 'error',
            'title' => __( 'Failed!', 'circular-arts-network' ),
            'message' => __( 'There is some error or you did not make any change.', 'circular-arts-network' ),
        );
        if (isset($_REQUEST['sections']) && !isset($_REQUEST['reset'])) {
            $updated = update_option( 'can_field_sections', $_REQUEST['sections'] );
            if ($updated) {
                $resp['status'] = 'success';
                $resp['title'] = __( 'Settings Saved!', 'circular-arts-network' );
                $resp['message'] = __( 'Settings are saved in the database successfully.', 'circular-arts-network' );
            }
        }
        if (isset($_REQUEST['reset']) && $_REQUEST['reset'] == 'yes') {
            $deleted = delete_option( 'can_field_sections' );
            if ($deleted) {
                $resp['status'] = 'success';
                $resp['title'] = __( 'Reset Done!', 'circular-arts-network' );
                $resp['message'] = __( 'Section are reset successfully.', 'circular-arts-network' );
            }
        }
        
        wp_send_json( $resp );
        die(0);
    }

    function save_custom_fields(){
        if (isset($_REQUEST['fields'])) {
            $resp = array('status' => '', 'title' => '', 'message' => '');
            $fields_arr = array();
            foreach ($_REQUEST['fields'] as $field) {
                $field['editable'] = (isset($field['editable']) && $field['editable'] == 'false') ? false : true;
                $field['options'] = (isset($field['options']) && $field['options'] != '') ? explode("\n", trim($field['options'])) : array();
                $field['title'] = (isset($field['title']) && $field['title'] != '') ? stripcslashes($field['title']) : '';
                $field['help'] = (isset($field['help']) && $field['help'] != '') ? stripcslashes($field['help']) : '';
                $fields_arr[] = $field;
                if (isset($field['title']) && $field['title'] != '') {
                    can_wpml_register($field['title'], 'circular-arts-network-fields');
                }
                if (isset($field['help']) && $field['help'] != '') {
                    can_wpml_register($field['help'], 'circular-arts-network-fields');
                }
                if (isset($field['default']) && $field['default'] != '') {
                    can_wpml_register($field['default'], 'circular-arts-network-fields');
                }
                if (!empty($field['options'])) {
                    foreach ($field['options'] as $option_name) {
                        can_wpml_register(trim($option_name), 'circular-arts-network-fields');
                    }
                }
            }
            if (update_option( 'can_listing_fields', $fields_arr )) {
                $resp['status'] = 'success';
                $resp['title'] = __( 'Settings Saved!', 'circular-arts-network' );
                $resp['message'] = __( 'Settings are saved in the database successfully.', 'circular-arts-network' );
            } else {
                $resp['status'] = 'error';
                $resp['title'] = __( 'Failed!', 'circular-arts-network' );
                $resp['message'] = __( 'There is some error or you did not make any change.', 'circular-arts-network' );
            }

            echo json_encode($resp);
        }
        die(0);
    }

    function reset_custom_fields(){
        if (isset($_REQUEST['reset']) && $_REQUEST['reset'] == 'yes') {
            delete_option( 'can_listing_fields' );
        }
        die(0);
    }

    function save_admin_settings(){
        if (isset($_REQUEST)) {
            $resp = array('status' => '', 'title' => '', 'message' => '');
            
            $can_settings = $_REQUEST;
            if (update_option( 'can_all_settings', $can_settings )) {
                $resp['status'] = 'success';
                $resp['title'] = __( 'Settings Saved!', 'circular-arts-network' );
                $resp['message'] = __( 'Settings are saved in the database successfully.', 'circular-arts-network' );
                if (isset($_REQUEST['listing_submission_mode'])) {
                    $role = get_role( 'can_listing_seller' );
                    if ($_REQUEST['listing_submission_mode'] == 'publish') {
                        $role->add_cap( 'publish_can_listings' );
                    } elseif ($_REQUEST['listing_submission_mode'] == 'approve') {
                        $role->remove_cap( 'publish_can_listings' );
                    }
                }
            } else {
                $resp['status'] = 'error';
                $resp['title'] = __( 'Failed!', 'circular-arts-network' );
                $resp['message'] = __( 'There is some error or you did not make any change.', 'circular-arts-network' );
            }
            echo json_encode($resp);
        }
        die(0);
    }

    function add_category_image( $taxonomy ){
        include CAN_PATH.'/inc/admin/add-category-image.php';
    }

    function save_category_image( $term_id, $tt_id ) {
        if( isset( $_POST['can_category_image'] ) && '' !== $_POST['can_category_image'] ){
            add_term_meta( $term_id, 'can_category_image', sanitize_text_field( $_POST['can_category_image'] ), true );
        }
        if( isset( $_POST['can_category_icon'] ) && '' !== $_POST['can_category_icon'] ){
            add_term_meta( $term_id, 'can_category_icon', sanitize_text_field( $_POST['can_category_icon'] ), true );
        }
    }
    function edit_category_image( $term, $taxonomy ) {
        include CAN_PATH.'/inc/admin/edit-category-image.php';
    }
    function updated_category_image( $term_id, $tt_id ) {
        if( isset( $_POST['can_category_image'] ) && '' !== $_POST['can_category_image'] ){
            update_term_meta ( $term_id, 'can_category_image', sanitize_text_field( $_POST['can_category_image'] ) );
        } else {
            update_term_meta ( $term_id, 'can_category_image', '' );
        }
        if( isset( $_POST['can_category_icon'] ) && '' !== $_POST['can_category_icon'] ){
            update_term_meta ( $term_id, 'can_category_icon', sanitize_text_field( $_POST['can_category_icon'] ) );
        } else {
            update_term_meta ( $term_id, 'can_category_icon', '' );
        }
    }

    function archive_page_listings_count($query){
        if ( is_admin() || ! $query->is_main_query() ) {
            return;
        }
        $number_of_listings = can_get_option('listings_per_page', 9);
        if (is_tax('can_listing_tag') || is_tax('can_listing_category')) {
            $query->set( 'posts_per_page', $number_of_listings );
        }
    }

    function listing_submission_email($new_status, $old_status, $listing){
        if (isset($listing->post_type) && $listing->post_type == 'can_listing' && can_get_option('listing_submission_mode') == 'approve') {
            if ( $new_status === "pending" && $old_status !== 'pending' ) {
                do_action( 'can_new_listing_submitted', $listing->ID );
            }
            if ( $new_status === "publish" && $old_status === 'pending' ) {
                do_action( 'can_new_listing_approved', $listing->ID );
            }
        }
    }

    function deny_seller(){
        if (isset($_REQUEST) && current_user_can( 'manage_options' )) {
            $pending_sellers = get_option( 'can_pending_users' );
            do_action( 'can_new_seller_rejected', $pending_sellers[$_REQUEST['userindex']] );
            unset($pending_sellers[$_REQUEST['userindex']]);
            update_option( 'can_pending_users', $pending_sellers );
        }
        die(0);
    }

    function approve_seller(){
        if (isset($_REQUEST) && current_user_can( 'manage_options' )) {
            $pending_sellers = get_option( 'can_pending_users' );

            $new_seller = $pending_sellers[$_REQUEST['userindex']];

            $seller_id = wp_create_user( $new_seller['username'], $new_seller['seller_password'], $new_seller['useremail'] );

            do_action( 'can_new_seller_approved', $new_seller );

            if ($seller_id != '') {
                wp_update_user( array( 
                    'ID' => $seller_id,
                    'role' => 'can_listing_seller',
                    'first_name' => sanitize_text_field( $new_seller['first_name'] ),
                    'last_name' => sanitize_text_field( $new_seller['last_name'] ),
                ) );

                // WPML Language
                if (isset($_REQUEST['wpml_user_email_language'])) {
                    update_user_meta( $seller_id, 'icl_admin_language', sanitize_text_field( $_REQUEST['wpml_user_email_language'] ));
                }

                update_user_meta( $seller_id, 'seller_phone', sanitize_text_field( $new_seller['seller_phone'] ));
                
                if (isset($new_seller['seller_image'])) {
                    update_user_meta( $seller_id, 'seller_image', sanitize_text_field( $new_seller['seller_image'] ));
                }
            }
            
            unset($pending_sellers[$_REQUEST['userindex']]);

            update_option( 'can_pending_users', $pending_sellers );
        }

        die(0);
    }

    /**
     * Add iFrame to allowed wp_kses_post tags
     *
     * @param array  $tags Allowed tags, attributes, and/or entities.
     * @param string $context Context to judge allowed tags by. Allowed values are 'post'.
     *
     * @return array
     */
    function custom_wpkses_post_tags( $tags, $context ) {

        if ( 'post' === $context ) {
            $tags['iframe'] = array(
                'src'             => array(),
                'height'          => array(),
                'width'           => array(),
                'frameborder'     => array(),
                'allowfullscreen' => array(),
            );
        }

        return $tags;
    }
}

$can_admin_settings = new CAN_Admin_Settings;
?>
