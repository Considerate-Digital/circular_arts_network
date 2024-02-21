<?php 
/**
* UCLWP_Register_CPT: registers cpt uclwp_listing & taxonomy uclwp_listing_category
*/
class UCLWP_Register_CPT
{
	
	function __construct(){
		add_action( 'init', array($this, 'register' ) );
		add_filter( 'post_updated_messages', array($this, 'listing_messages' ) );
        // Permalink settings
        add_filter( 'load-options-permalink.php', array($this, 'permalink_settings') ); 
        // Change author in listings page
        add_filter( 'wp_dropdown_users', array($this, 'author_override') );
	}

    function author_override($output){
        global $post, $user_ID;
        if (isset($post->post_type) && 'uclwp_listing' === $post->post_type) {

            // return if this isn't the theme author override dropdown
            if (!preg_match('/post_author_override/', $output)) return $output;

            // return if we've already replaced the list (end recursion)
            if (preg_match ('/post_author_override_replaced/', $output)) return $output;

            // replacement call to wp_dropdown_users
            $output = wp_dropdown_users(array(
                'echo' => 0,
                'name' => 'post_author_override_replaced',
                'selected' => empty($post->ID) ? $user_ID : $post->post_author,
                'include_selected' => true
            ));

            // put the original name back
            $output = preg_replace('/post_author_override_replaced/', 'post_author_override', $output);

        }

        return $output;

    }

    function permalink_settings(){
        if( isset( $_POST['uclwp_listing_permalink'] ) ){
            update_option( 'uclwp_listing_permalink', sanitize_title_with_dashes( $_POST['uclwp_listing_permalink'] ) );
        }
        if( isset( $_POST['uclwp_category_permalink'] ) ){
            update_option( 'uclwp_category_permalink', sanitize_title_with_dashes( $_POST['uclwp_category_permalink'] ) );
        }
        if( isset( $_POST['uclwp_tag_permalink'] ) ){
            update_option( 'uclwp_tag_permalink', sanitize_title_with_dashes( $_POST['uclwp_tag_permalink'] ) );
        }
        
        // Add setting fields to the permalink page
        add_settings_section( 'uclwp_permalink_settings', 'UCL - Permalinks', array($this, 'render_permalink_settings'), 'permalink' );
    }

    function render_permalink_settings(){
        $listing_base = get_option( 'uclwp_listing_permalink' );
        $listing_slug = ($listing_base != '') ? $listing_base : 'listing' ;

        $category_base = get_option( 'uclwp_category_permalink' );
        $category_slug = ($category_base != '') ? $category_base : 'listing_category' ;

        $tag_base = get_option( 'uclwp_tag_permalink' );
        $tag_slug = ($tag_base != '') ? $tag_base : 'listing_tag' ;
        ?>
        <table class="form-table">
            <tr>
                <th><label for="uclwp_listing_permalink"><?php _e( 'Listing Page Base' , 'ultimate-classified-listings' ); ?></label></th>
                <td><input type="text" value="<?php echo esc_attr( $listing_slug ); ?>" name="uclwp_listing_permalink" id="uclwp_listing_permalink" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="uclwp_category_permalink"><?php _e( 'Listing Category Base' , 'ultimate-classified-listings' ); ?></label></th>
                <td><input type="text" value="<?php echo esc_attr( $category_slug ); ?>" name="uclwp_category_permalink" id="uclwp_category_permalink" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="uclwp_tag_permalink"><?php _e( 'Listing Tag Base' , 'ultimate-classified-listings' ); ?></label></th>
                <td><input type="text" value="<?php echo esc_attr( $tag_slug ); ?>" name="uclwp_tag_permalink" id="uclwp_tag_permalink" class="regular-text" /></td>
            </tr>
        </table>
        <?php
    }

    function listing_messages( $messages ) {
        $post             = get_post();
        $post_type        = get_post_type( $post );
        $post_type_object = get_post_type_object( $post_type );

        $messages['uclwp_listing'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => __( 'Listing updated.', 'ultimate-classified-listings' ),
            2  => __( 'Custom field updated.', 'ultimate-classified-listings' ),
            3  => __( 'Custom field deleted.', 'ultimate-classified-listings' ),
            4  => __( 'Listing updated.', 'ultimate-classified-listings' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( __( 'Listing restored to revision', 'ultimate-classified-listings' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => __( 'Listing published.', 'ultimate-classified-listings' ),
            7  => __( 'Listing saved.', 'ultimate-classified-listings' ),
            8  => __( 'Listing submitted.', 'ultimate-classified-listings' ),
            9  => sprintf(
                __( 'Listing scheduled.', 'ultimate-classified-listings' ),
                // translators: Publish box date format, see http://php.net/date
                date_i18n( __( 'M j, Y @ G:i', 'ultimate-classified-listings' ), strtotime( $post->post_date ) )
            ),
            10 => __( 'Listing draft updated.', 'ultimate-classified-listings' )
        );

        if ( $post_type_object->publicly_queryable && 'uclwp_listing' === $post_type ) {
            $permalink = get_permalink( $post->ID );

            $view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View Listing', 'ultimate-classified-listings' ) );
            $messages[ $post_type ][1] .= $view_link;
            $messages[ $post_type ][6] .= $view_link;
            $messages[ $post_type ][9] .= $view_link;

            $preview_permalink = add_query_arg( 'preview', 'true', $permalink );
            $preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview Listing', 'ultimate-classified-listings' ) );
            $messages[ $post_type ][8]  .= $preview_link;
            $messages[ $post_type ][10] .= $preview_link;
        }

        return $messages;
    }

	function register(){
		$this->register_cpt();
		$this->register_category();
		$this->register_tag();
	}

	function register_cpt(){
		$menu_name = __( 'Ultimate Classified Listings', 'ultimate-classified-listings' );

	    if (current_user_can('edit_uclwp_listing') && !current_user_can('edit_others_uclwp_listings')) {
	        $menu_name = __( 'Listings', 'ultimate-classified-listings' );
	    }

	    $custom_labels = array(
	        'name'                => __( 'Listings', 'ultimate-classified-listings' ),
	        'singular_name'       => __( 'Listing', 'ultimate-classified-listings' ),
	        'add_new'             => _x( 'Add New Listing', 'ultimate-classified-listings', 'ultimate-classified-listings' ),
	        'add_new_item'        => __( 'Add New Listing', 'ultimate-classified-listings' ),
	        'edit_item'           => __( 'Edit Listing', 'ultimate-classified-listings' ),
	        'new_item'            => __( 'New Listing', 'ultimate-classified-listings' ),
	        'view_item'           => __( 'View Listing', 'ultimate-classified-listings' ),
	        'search_items'        => __( 'Search Listing', 'ultimate-classified-listings' ),
	        'not_found'           => __( 'No Listing found', 'ultimate-classified-listings' ),
	        'not_found_in_trash'  => __( 'No Listing found in Trash', 'ultimate-classified-listings' ),
	        'parent_item_colon'   => __( 'Parent Listing:', 'ultimate-classified-listings' ),
	        'menu_name'           => $menu_name,
	        'all_items'           => __( 'Listings', 'ultimate-classified-listings' ),
	    );

	    $prop_args = array(
	        'labels'              => $custom_labels,
	        'hierarchical'        => false,
	        'description'         => 'Listings',
	        'public'              => true,
	        'show_ui'             => true,
	        'show_in_menu'        => true,
	        'show_in_admin_bar'   => true,
	        'menu_position'       => null,
	        'show_in_rest'        => true,
	        'rest_base'           => 'properties',
	        'menu_icon'           => 'dashicons-admin-home',
	        'show_in_nav_menus'   => true,
	        'publicly_queryable'  => true,
	        'exclude_from_search' => false,
	        'has_archive'         => (0) ? true : false,
	        'query_var'           => true,
	        'can_export'          => true,
	        'rewrite'             => array(
	            'slug'          => (0) ? 'customlisting' : 'listing',
	            'with_front'    => false
	        ),
	        'capability_type'     => array('uclwp_listing', 'uclwp_listings'),
	        'map_meta_cap'        => true,
	        'supports'            => array(
            	'title', 'editor', 'author', 'thumbnail', 'excerpt'
            )
	    );

	    register_post_type( 'uclwp_listing', $prop_args );
	}

	function register_category(){
	    $cat_labels = array(
	        'name'                    => _x( 'Categories', 'Categories', 'ultimate-classified-listings' ),
	        'singular_name'            => _x( 'Category', 'Categories', 'ultimate-classified-listings' ),
	        'search_items'            => __( 'Search Categories', 'ultimate-classified-listings' ),
	        'popular_items'            => __( 'Popular Categories', 'ultimate-classified-listings' ),
	        'all_items'                => __( 'All Categories', 'ultimate-classified-listings' ),
	        'parent_item'            => __( 'Parent Category', 'ultimate-classified-listings' ),
	        'parent_item_colon'        => __( 'Parent Category', 'ultimate-classified-listings' ),
	        'edit_item'                => __( 'Edit Category', 'ultimate-classified-listings' ),
	        'update_item'            => __( 'Update Category', 'ultimate-classified-listings' ),
	        'add_new_item'            => __( 'Add New Category', 'ultimate-classified-listings' ),
	        'new_item_name'            => __( 'New Category Name', 'ultimate-classified-listings' ),
	        'add_or_remove_items'    => __( 'Add or remove Categories', 'ultimate-classified-listings' ),
	        'choose_from_most_used'    => __( 'Choose from most used categories', 'ultimate-classified-listings' ),
	        'menu_name'                => __( 'Categories', 'ultimate-classified-listings' ),
	    );

	    $category_permalink = get_option( 'uclwp_category_permalink' );
	    $category_slug = ($category_permalink != '') ? $category_permalink : 'listing_category' ;

	    $cat_args = array(
	        'labels'            => $cat_labels,
	        'public'            => true,
	        'show_in_nav_menus' => true,
	        'show_admin_column' => true,
	        'hierarchical'      => true,
	        'show_tagcloud'     => true,
	        'show_ui'           => true,
	        'query_var'         => true,
	        'show_in_rest' => true,
	        'rewrite'             => array(
	            'slug'          => $category_slug,
	            'with_front'    => true
	        ),            
	        'query_var'         => true,
	    );
	    register_taxonomy( 'uclwp_listing_category', array( 'uclwp_listing' ), $cat_args );
	}

	function register_tag(){
	    $tag_labels = array(
	        'name'                    => _x( 'Tags', 'Tags', 'ultimate-classified-listings' ),
	        'singular_name'            => _x( 'Tag', 'Tags', 'ultimate-classified-listings' ),
	        'search_items'            => __( 'Search Tags', 'ultimate-classified-listings' ),
	        'popular_items'            => __( 'Popular Tags', 'ultimate-classified-listings' ),
	        'all_items'                => __( 'All Tags', 'ultimate-classified-listings' ),
	        'parent_item'            => __( 'Parent Tag', 'ultimate-classified-listings' ),
	        'parent_item_colon'        => __( 'Parent Tag', 'ultimate-classified-listings' ),
	        'edit_item'                => __( 'Edit Tag', 'ultimate-classified-listings' ),
	        'update_item'            => __( 'Update Tag', 'ultimate-classified-listings' ),
	        'add_new_item'            => __( 'Add New Tag', 'ultimate-classified-listings' ),
	        'new_item_name'            => __( 'New Tag Name', 'ultimate-classified-listings' ),
	        'add_or_remove_items'    => __( 'Add or remove Tags', 'ultimate-classified-listings' ),
	        'choose_from_most_used'    => __( 'Choose from most used tags', 'ultimate-classified-listings' ),
	        'menu_name'                => __( 'Tags', 'ultimate-classified-listings' ),
	    );

	    $tag_permalink = get_option( 'uclwp_tag_permalink' );
	    $tag_slug = ($tag_permalink != '') ? $tag_permalink : 'listing_tag' ;

	    $tag_args = array(
	        'labels'            => $tag_labels,
	        'public'            => true,
	        'show_in_nav_menus' => true,
	        'show_admin_column' => true,
	        'hierarchical'      => false,
	        'show_tagcloud'     => true,
	        'show_ui'           => true,
	        'query_var'         => true,
	        'rewrite'             => array(
	            'slug'          => $tag_slug,
	            'with_front'    => false
	        ),            
	        'query_var'         => true,
	    );

	    register_taxonomy( 'uclwp_listing_tag', array( 'uclwp_listing' ), $tag_args );
	}


}

new UCLWP_Register_CPT();
?>