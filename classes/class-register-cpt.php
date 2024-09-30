<?php 
/**
* CAN_Register_CPT: registers cpt can_listing & taxonomy can_listing_category
*/
class CAN_Register_CPT
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
        if (isset($post->post_type) && 'can_listing' === $post->post_type) {

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
        if( isset( $_POST['can_listing_permalink'] ) ){
            update_option( 'can_listing_permalink', sanitize_title_with_dashes( $_POST['can_listing_permalink'] ) );
        }
        if( isset( $_POST['can_category_permalink'] ) ){
            update_option( 'can_category_permalink', sanitize_title_with_dashes( $_POST['can_category_permalink'] ) );
        }
        if( isset( $_POST['can_tag_permalink'] ) ){
            update_option( 'can_tag_permalink', sanitize_title_with_dashes( $_POST['can_tag_permalink'] ) );
        }
        
        // Add setting fields to the permalink page
        add_settings_section( 'can_permalink_settings', 'CAN - Permalinks', array($this, 'render_permalink_settings'), 'permalink' );
    }

    function render_permalink_settings(){
        $listing_base = get_option( 'can_listing_permalink' );
        $listing_slug = ($listing_base != '') ? $listing_base : 'listing' ;

        $category_base = get_option( 'can_category_permalink' );
        $category_slug = ($category_base != '') ? $category_base : 'listing_category' ;

        $tag_base = get_option( 'can_tag_permalink' );
        $tag_slug = ($tag_base != '') ? $tag_base : 'listing_tag' ;
        ?>
        <table class="form-table">
            <tr>
                <th><label for="can_listing_permalink"><?php _e( 'Listing Page Base' , 'circular-arts-network' ); ?></label></th>
                <td><input type="text" value="<?php echo esc_attr( $listing_slug ); ?>" name="can_listing_permalink" id="can_listing_permalink" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="can_category_permalink"><?php _e( 'Listing Category Base' , 'circular-arts-network' ); ?></label></th>
                <td><input type="text" value="<?php echo esc_attr( $category_slug ); ?>" name="can_category_permalink" id="can_category_permalink" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label for="can_tag_permalink"><?php _e( 'Listing Tag Base' , 'circular-arts-network' ); ?></label></th>
                <td><input type="text" value="<?php echo esc_attr( $tag_slug ); ?>" name="can_tag_permalink" id="can_tag_permalink" class="regular-text" /></td>
            </tr>
        </table>
        <?php
    }

    function listing_messages( $messages ) {
        $post             = get_post();
        $post_type        = get_post_type( $post );
        $post_type_object = get_post_type_object( $post_type );

        $messages['can_listing'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => __( 'Listing updated.', 'circular-arts-network' ),
            2  => __( 'Custom field updated.', 'circular-arts-network' ),
            3  => __( 'Custom field deleted.', 'circular-arts-network' ),
            4  => __( 'Listing updated.', 'circular-arts-network' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( __( 'Listing restored to revision', 'circular-arts-network' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => __( 'Listing published.', 'circular-arts-network' ),
            7  => __( 'Listing saved.', 'circular-arts-network' ),
            8  => __( 'Listing submitted.', 'circular-arts-network' ),
            9  => sprintf(
                __( 'Listing scheduled.', 'circular-arts-network' ),
                // translators: Publish box date format, see http://php.net/date
                date_i18n( __( 'M j, Y @ G:i', 'circular-arts-network' ), strtotime( $post->post_date ) )
            ),
            10 => __( 'Listing draft updated.', 'circular-arts-network' )
        );

        if ( $post_type_object->publicly_queryable && 'can_listing' === $post_type ) {
            $permalink = get_permalink( $post->ID );

            $view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View Listing', 'circular-arts-network' ) );
            $messages[ $post_type ][1] .= $view_link;
            $messages[ $post_type ][6] .= $view_link;
            $messages[ $post_type ][9] .= $view_link;

            $preview_permalink = add_query_arg( 'preview', 'true', $permalink );
            $preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview Listing', 'circular-arts-network' ) );
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
		$menu_name = __( 'Circular Arts Network', 'circular-arts-network' );

	    if (current_user_can('edit_can_listing') && !current_user_can('edit_others_can_listings')) {
	        $menu_name = __( 'Listings', 'circular-arts-network' );
	    }

	    $custom_labels = array(
	        'name'                => __( 'Listings', 'circular-arts-network' ),
	        'singular_name'       => __( 'Listing', 'circular-arts-network' ),
	        'add_new'             => _x( 'Add New Listing', 'circular-arts-network', 'circular-arts-network' ),
	        'add_new_item'        => __( 'Add New Listing', 'circular-arts-network' ),
	        'edit_item'           => __( 'Edit Listing', 'circular-arts-network' ),
	        'new_item'            => __( 'New Listing', 'circular-arts-network' ),
	        'view_item'           => __( 'View Listing', 'circular-arts-network' ),
	        'search_items'        => __( 'Search Listing', 'circular-arts-network' ),
	        'not_found'           => __( 'No Listing found', 'circular-arts-network' ),
	        'not_found_in_trash'  => __( 'No Listing found in Trash', 'circular-arts-network' ),
	        'parent_item_colon'   => __( 'Parent Listing:', 'circular-arts-network' ),
	        'menu_name'           => $menu_name,
	        'all_items'           => __( 'Listings', 'circular-arts-network' ),
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
	        'capability_type'     => array('can_listing', 'can_listings'),
	        'map_meta_cap'        => true,
	        'supports'            => array(
            	'title', 'editor', 'author', 'thumbnail', 'excerpt'
            )
	    );

	    register_post_type( 'can_listing', $prop_args );
	}

	function register_category(){
	    $cat_labels = array(
	        'name'                    => _x( 'Categories', 'Categories', 'circular-arts-network' ),
	        'singular_name'            => _x( 'Category', 'Categories', 'circular-arts-network' ),
	        'search_items'            => __( 'Search Categories', 'circular-arts-network' ),
	        'popular_items'            => __( 'Popular Categories', 'circular-arts-network' ),
	        'all_items'                => __( 'All Categories', 'circular-arts-network' ),
	        'parent_item'            => __( 'Parent Category', 'circular-arts-network' ),
	        'parent_item_colon'        => __( 'Parent Category', 'circular-arts-network' ),
	        'edit_item'                => __( 'Edit Category', 'circular-arts-network' ),
	        'update_item'            => __( 'Update Category', 'circular-arts-network' ),
	        'add_new_item'            => __( 'Add New Category', 'circular-arts-network' ),
	        'new_item_name'            => __( 'New Category Name', 'circular-arts-network' ),
	        'add_or_remove_items'    => __( 'Add or remove Categories', 'circular-arts-network' ),
	        'choose_from_most_used'    => __( 'Choose from most used categories', 'circular-arts-network' ),
	        'menu_name'                => __( 'Categories', 'circular-arts-network' ),
	    );

	    $category_permalink = get_option( 'can_category_permalink' );
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
	    register_taxonomy( 'can_listing_category', array( 'can_listing' ), $cat_args );
	}

	function register_tag(){
	    $tag_labels = array(
	        'name'                    => _x( 'Tags', 'Tags', 'circular-arts-network' ),
	        'singular_name'            => _x( 'Tag', 'Tags', 'circular-arts-network' ),
	        'search_items'            => __( 'Search Tags', 'circular-arts-network' ),
	        'popular_items'            => __( 'Popular Tags', 'circular-arts-network' ),
	        'all_items'                => __( 'All Tags', 'circular-arts-network' ),
	        'parent_item'            => __( 'Parent Tag', 'circular-arts-network' ),
	        'parent_item_colon'        => __( 'Parent Tag', 'circular-arts-network' ),
	        'edit_item'                => __( 'Edit Tag', 'circular-arts-network' ),
	        'update_item'            => __( 'Update Tag', 'circular-arts-network' ),
	        'add_new_item'            => __( 'Add New Tag', 'circular-arts-network' ),
	        'new_item_name'            => __( 'New Tag Name', 'circular-arts-network' ),
	        'add_or_remove_items'    => __( 'Add or remove Tags', 'circular-arts-network' ),
	        'choose_from_most_used'    => __( 'Choose from most used tags', 'circular-arts-network' ),
	        'menu_name'                => __( 'Tags', 'circular-arts-network' ),
	    );

	    $tag_permalink = get_option( 'can_tag_permalink' );
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

	    register_taxonomy( 'can_listing_tag', array( 'can_listing' ), $tag_args );
	}


}

new CAN_Register_CPT();
?>
