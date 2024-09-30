<?php if(isset($_GET['action']) && $_GET['action'] == 'can_search_listing'){
    
    $args = can_get_search_query($_REQUEST);
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1; 
    $args['paged'] = $paged;
    $listings_query = new WP_Query( $args );
    $links_target = can_get_option('searched_listings_target');

    echo '<div class="can-bs-wrapper">';

    if ( $listings_query->have_posts() ) {

        $columns = 'col-sm-4';

        if (isset($_GET['layout']) && $_GET['layout'] == 'list') {
            $columns = 'col-sm-12';
        }

        echo '<div class="row can-display-listings">';

        while ( $listings_query->have_posts() ) {
            $listings_query->the_post();
            echo '<div id="listing-'.get_the_id().'" class="'.esc_attr( $columns ).'">';
                do_action('can_listing_box', get_the_id(), '1', 'grid', $links_target);
            echo '</div>';
        }
        
        echo '</div>';
        /* Restore original Post Data */
        wp_reset_postdata();

        do_action( 'can_pagination', $paged, $listings_query->max_num_pages ); 

    } else {
        $msg = can_get_option('no_results_message', 'No Results Found.');
        echo '<div class="alert alert-info">'.stripcslashes($msg).'</div>';
    }
    echo '</div>';
} ?>
