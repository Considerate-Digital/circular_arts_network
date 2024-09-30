<div class="can-screen-wrapper">
	<div class="can-screen-header">
		<?php _e( 'My Listings', 'circular-arts-network' ) ?>
		<a href="<?php echo esc_url( add_query_arg( 'can_page', 'add') ); ?>" class="btn btn-sm btn-success float-end text-decoration-none"><i class="bi bi-plus-circle"></i> <?php _e( 'Add New', 'circular-arts-network' ) ?></a>
	</div>
	<div class="can-screen-content mb-4">
		<div class="row mb-4">
			<div class="col">
				<form action="#" method="GET">
					<input type="hidden" name="can_page" value="listings">
				    <div class="input-group">
					    <input type="text" value="<?php echo (isset($_GET['can_search_query'])) ? $_GET['can_search_query'] : '' ; ?>" name="can_search_query" class="form-control" placeholder="<?php _e( 'Search for...', 'circular-arts-network' ); ?>">
						<select name="can_status" class="form-select">
							<option value="any"><?php _e( 'All Status', 'circular-arts-network' ); ?></option>
							<option value="publish" <?php echo (isset($_GET['can_status']) && $_GET['can_status'] == 'publish') ? 'selected' : '' ; ?>><?php _e( 'Only Published', 'circular-arts-network' ); ?></option>
							<option value="pending" <?php echo (isset($_GET['can_status']) && $_GET['can_status'] == 'pending') ? 'selected' : '' ; ?>><?php _e( 'Only Pending', 'circular-arts-network' ); ?></option>
							<option value="draft" <?php echo (isset($_GET['can_status']) && $_GET['can_status'] == 'draft') ? 'selected' : '' ; ?>><?php _e( 'Only Draft', 'circular-arts-network' ); ?></option>
						</select>
					    <button class="btn btn-outline-secondary" type="submit"><?php _e( 'Search', 'circular-arts-network' ); ?></button>
				    </div>
				</form>
			</div>
		</div>


		<table class="table align-middle my-listings">
		  <thead>
			<tr>
				<th><?php _e( 'Thumbnail', 'circular-arts-network' ); ?></th>
				<th><?php _e( 'Title', 'circular-arts-network' ); ?></th>
				<th><?php _e( 'Price', 'circular-arts-network' ); ?></th>
				<th><?php _e( 'Updated', 'circular-arts-network' ); ?></th>
				<th><?php _e( 'Status', 'circular-arts-network' ); ?></th>
				<th><?php _e( 'Actions', 'circular-arts-network' ); ?></th>
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
				if (isset($_GET['can_status'])) {
					$statuses = array($_GET['can_status']);
				} else {
					$statuses = array( 'any' );
				}

				$args = array(
					'author'	=> $current_user_data->ID,
					'post_type' => 'can_listing',
					'posts_per_page' => 10,
					'post_status' => $statuses
				);
				if (isset($_GET['can_search_query'])) {
					$args['s'] = $_GET['can_search_query'];
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
									<?php do_action( 'can_featured_image', get_the_id(), 'thumbnail' ); ?>
								</td>
								<td>
									<a class="listing-title" href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</td>
								<td>
									<?php echo can_get_field_value(get_the_id(), array('key' =>'regular_price', 'type' => 'price')); ?>
								</td>
								<td><?php echo esc_html( human_time_diff( get_the_time('U'), current_time('timestamp') ) ) . ' ago'; ?></td>
								<td><?php echo ucfirst(get_post_status(get_the_id())); ?></td>
								<td>
									<a href="<?php echo esc_url( add_query_arg( array('can_page' => 'edit', 'listing_id' => get_the_id()) ) ); ?>" class="btn btn-info btn-sm">
										<i class="fas fa-pencil-alt"></i>
										<?php _e( 'Edit', 'circular-arts-network' ); ?>
									</a>
									<a class="btn btn-danger btn-sm delete-listing" data-pid="<?php echo get_the_id(); ?>" href="#">
										<i class="fa fa-trash"></i>
										<?php _e( 'Delete', 'circular-arts-network' ); ?>
									</a>
								</td>
							</tr>
						<?php 
					}
					wp_reset_postdata();
				} else { ?>
					<tr><td colspan="6">
						<div class="alert alert-primary text-center"><?php _e( 'No Listings Found!', 'circular-arts-network' ) ?></div>
					</td></tr>
				<?php }
			?>
		  </tbody>
		</table>
		<?php do_action( 'can_pagination', $paged, $my_listings->max_num_pages ); ?>
	</div>
</div>