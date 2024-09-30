<?php

if ( $the_query->have_posts() ) {
	echo '<div class="can-bs-wrapper">';

	if ($attributes['top_bar'] == 'enable') {
		do_action( 'can_archive_topbar' );
	}

	if (isset($_GET['layout']) && $_GET['layout'] == 'list') {
		$columns = 'col-sm-12';
	}

	echo '<div class="row can-display-listings">';

	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		echo '<div id="listing-'.get_the_id().'" class="'.esc_attr( $columns ).'">';
			do_action('can_listing_box', get_the_id(), '1', 'grid');
		echo '</div>';
	}
	
	echo '</div>';
	/* Restore original Post Data */
	wp_reset_postdata();
	if ($attributes['pagination'] == 'enable') {
		do_action( 'can_pagination', $paged, $the_query->max_num_pages );
	}				
	echo '</div>';
} else {
	$msg = can_get_option('no_results_message', 'No Listings Found.');
	echo stripcslashes($msg);
}
?>