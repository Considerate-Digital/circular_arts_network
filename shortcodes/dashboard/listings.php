<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$nonce_success = false;
if (isset($_REQUEST['_wpnonce'])) {
    $nonce_success = wp_verify_nonce( sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'dashboard-link'); 
}
if (!$nonce_success) {
        wp_nonce_ays('log-out');
}
?>
<div class="can-screen-wrapper">
	<div class="can-screen-header">
		<?php esc_html_e( 'My Listings', 'circular-arts-network' ) ?>
		<a href="<?php echo esc_url( add_query_arg( 'circartsnet_page', 'add') ); ?>" class="btn btn-sm btn-success float-end text-decoration-none"><i class="bi bi-plus-circle"></i> <?php esc_html_e( 'Add New', 'circular-arts-network' ) ?></a>
	</div>
	<div class="can-screen-content mb-4">
		<div class="row mb-4">

			<div class="col">
				<form action="#" method="GET">
					<input type="hidden" name="circartsnet_page" value="listings">
				    <div class="input-group">
							<?php 
								$circartsnet_search_query = isset($_GET['circartsnet_search_query']) ? sanitize_text_field(wp_unslash($_GET['circartsnet_search_query'])) : '';
					     echo '<input type="text" value="' . esc_attr($circartsnet_search_query) . '" name="circartsnet_search_query" class="form-control" placeholder="' . esc_html_e( 'Search for...', 'circular-arts-network' ) . '">';
							?>
						<select name="circartsnet_status" class="form-select">
							<option value="any"><?php esc_html_e( 'All Status', 'circular-arts-network' ); ?></option>
						<?php 
							$circartsnet_status = isset($_GET['circartsnet_status']) ? sanitize_text_field(wp_unslash(($_GET['circartsnet_status']))) : '';
							echo esc_html('<option value="publish"'. $circartsnet_status == 'publish' ? 'selected' : '' . esc_html_e( 'Only Published', 'circular-arts-network' ) . '</option>');

							echo '<option value="pending"' . $circartsnet_status == 'pending' ? 'selected' : '' . esc_html_e( 'Only Pending', 'circular-arts-network' ) . '</option>';

							echo esc_html('<option value="draft"' . $circartsnet_status  == 'draft' ? 'selected' : '' . esc_html_e( 'Only Draft', 'circular-arts-network' ) . '</option>');

						wp_nonce_field( 'search-query'); 

						?>

						</select>
					    <button class="btn btn-outline-secondary" type="submit"><?php esc_html_e( 'Search', 'circular-arts-network' ); ?></button>
				    </div>
				</form>
			</div>
		</div>


		<table class="table align-middle my-listings">
		  <thead>
			<tr>
				<th><?php esc_html_e( 'Thumbnail', 'circular-arts-network' ); ?></th>
				<th><?php esc_html_e( 'Title', 'circular-arts-network' ); ?></th>
				<th><?php esc_html_e( 'Price', 'circular-arts-network' ); ?></th>
				<th><?php esc_html_e( 'Updated', 'circular-arts-network' ); ?></th>
				<th><?php esc_html_e( 'Status', 'circular-arts-network' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'circular-arts-network' ); ?></th>
			</tr>
		  </thead>
		  <tbody>
			<?php 
				$current_user_data = wp_get_current_user();
				// Quick hack for translating wp statuses
				$statuses_translatable = array(
					__( 'pending', 'circular-arts-network' ),
					__( 'draft', 'circular-arts-network' ),
					__( 'future', 'circular-arts-network' ),
					__( 'publish', 'circular-arts-network' )
				);
				$circartsnet_status = isset($_GET['circartsnet_status']) ? sanitize_text_field(wp_unslash(($_GET['circartsnet_status']))) : '';
				if ($circartsnet_status) {
					$statuses = array($circartsnet_status);
				} else {
					$statuses = array( 'any' );
				}

				$args = array(
					'author'	=> $current_user_data->ID,
					'post_type' => 'circartsnet_listing',
					'posts_per_page' => 10,
					'post_status' => $statuses
				);

				$circartsnet_search_query = isset($_GET['circartsnet_search_query']) ? sanitize_text_field(wp_unslash(($_GET['circartsnet_search_query']))) : '';
				if ($circartsnet_search_query) {
					$args['s'] = $circartsnet_search_query;
				}
		    	if (is_front_page()) {
		    		$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
		    	} else {
					$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		    	}
				$args['paged'] = $paged;

				$my_listings = new WP_Query( $args );
				if( $my_listings->have_posts() ){
					while( $my_listings->have_posts() ){ 
						$my_listings->the_post(); ?>	
							<tr>
								<td class="listing-thumb">
									<?php do_action( 'circartsnet_featured_image', get_the_id(), 'thumbnail' ); ?>
								</td>
								<td>
									<a class="listing-title" href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</td>
								<td>
									<?php echo esc_attr(circartsnet_get_field_value(get_the_id(), array('key' =>'regular_price', 'type' => 'price'))); ?>
								</td>
								<td><?php echo esc_html( human_time_diff( get_the_time('U'), current_time('timestamp') ) ) . ' ago'; ?></td>
								<td><?php echo esc_html(ucfirst(get_post_status(get_the_id()))); ?></td>
								<td>
									<a href="<?php echo esc_url( add_query_arg( array('circartsnet_page' => 'edit', 'listing_id' => get_the_id()), wp_nonce_url('#', 'edit-listing'))); ?>" class="btn btn-info btn-sm">
										<i class="fas fa-pencil-alt"></i>
										<?php esc_html_e( 'Edit', 'circular-arts-network' ); ?>
									</a>
										<a class="btn btn-danger btn-sm delete-listing" data-pid="<?php echo esc_attr(get_the_id()); ?>" href="<?php echo esc_url(wp_nonce_url('#', 'delete-listing'))?>">
										<i class="fa fa-trash"></i>
										<?php esc_html_e( 'Delete', 'circular-arts-network' ); ?>
									</a>
								</td>
							</tr>
						<?php 
					}
					wp_reset_postdata();
				} else { ?>
					<tr><td colspan="6">
						<div class="alert alert-primary text-center"><?php esc_html_e( 'No Listings Found!', 'circular-arts-network' ) ?></div>
					</td></tr>
				<?php }
			?>
		  </tbody>
		</table>
		<?php do_action( 'circartsnet_pagination', $paged, $my_listings->max_num_pages ); ?>
	</div>
</div>
