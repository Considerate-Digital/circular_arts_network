<div class="ucl-screen-wrapper">
	<div class="ucl-screen-header">
		<?php _e( 'My Listings', 'ultimate-classified-listings' ) ?>
		<a href="<?php echo esc_url( add_query_arg( 'ucl_page', 'add') ); ?>" class="btn btn-sm btn-success float-end text-decoration-none"><i class="bi bi-plus-circle"></i> <?php _e( 'Add New', 'ultimate-classified-listings' ) ?></a>
	</div>
	<div class="ucl-screen-content mb-4">
		<div class="row mb-4">
			<div class="col">
				<form action="#" method="GET">
					<input type="hidden" name="ucl_page" value="listings">
				    <div class="input-group">
					    <input type="text" value="<?php echo (isset($_GET['uclwp_search_query'])) ? $_GET['uclwp_search_query'] : '' ; ?>" name="uclwp_search_query" class="form-control" placeholder="<?php _e( 'Search for...', 'ultimate-classified-listings' ); ?>">
						<select name="uclwp_status" class="form-select">
							<option value="any"><?php _e( 'All Status', 'ultimate-classified-listings' ); ?></option>
							<option value="publish" <?php echo (isset($_GET['uclwp_status']) && $_GET['uclwp_status'] == 'publish') ? 'selected' : '' ; ?>><?php _e( 'Only Published', 'ultimate-classified-listings' ); ?></option>
							<option value="pending" <?php echo (isset($_GET['uclwp_status']) && $_GET['uclwp_status'] == 'pending') ? 'selected' : '' ; ?>><?php _e( 'Only Pending', 'ultimate-classified-listings' ); ?></option>
							<option value="draft" <?php echo (isset($_GET['uclwp_status']) && $_GET['uclwp_status'] == 'draft') ? 'selected' : '' ; ?>><?php _e( 'Only Draft', 'ultimate-classified-listings' ); ?></option>
						</select>
					    <button class="btn btn-outline-secondary" type="submit"><?php _e( 'Search', 'ultimate-classified-listings' ); ?></button>
				    </div>
				</form>
			</div>
		</div>


		<table class="table align-middle my-listings">
		  <thead>
			<tr>
				<th><?php _e( 'Thumbnail', 'ultimate-classified-listings' ); ?></th>
				<th><?php _e( 'Title', 'ultimate-classified-listings' ); ?></th>
				<th><?php _e( 'Price', 'ultimate-classified-listings' ); ?></th>
				<th><?php _e( 'Updated', 'ultimate-classified-listings' ); ?></th>
				<th><?php _e( 'Status', 'ultimate-classified-listings' ); ?></th>
				<th><?php _e( 'Actions', 'ultimate-classified-listings' ); ?></th>
			</tr>
		  </thead>
		  <tbody>
			<?php 
				$current_user_data = wp_get_current_user();
				// Quick hack for translating wp statuses
				$statuses_translatable = array(
					__( 'pending', 'ultimate-classified-listings' ),
					__( 'draft', 'ultimate-classified-listings' ),
					__( 'future', 'ultimate-classified-listings' ),
					__( 'publish', 'ultimate-classified-listings' )
				);
				if (isset($_GET['uclwp_status'])) {
					$statuses = array($_GET['uclwp_status']);
				} else {
					$statuses = array( 'any' );
				}

				$args = array(
					'author'	=> $current_user_data->ID,
					'post_type' => 'uclwp_listing',
					'posts_per_page' => 10,
					'post_status' => $statuses
				);
				if (isset($_GET['uclwp_search_query'])) {
					$args['s'] = $_GET['uclwp_search_query'];
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
									<?php do_action( 'uclwp_featured_image', get_the_id(), 'thumbnail' ); ?>
								</td>
								<td>
									<a class="listing-title" href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</td>
								<td>
									<?php echo uclwp_get_field_value(get_the_id(), array('key' =>'regular_price', 'type' => 'price')); ?>
								</td>
								<td><?php echo esc_html( human_time_diff( get_the_time('U'), current_time('timestamp') ) ) . ' ago'; ?></td>
								<td><?php echo ucfirst(get_post_status(get_the_id())); ?></td>
								<td>
									<a href="<?php echo esc_url( add_query_arg( array('ucl_page' => 'edit', 'listing_id' => get_the_id()) ) ); ?>" class="btn btn-info btn-sm">
										<i class="fas fa-pencil-alt"></i>
										<?php _e( 'Edit', 'ultimate-classified-listings' ); ?>
									</a>
									<a class="btn btn-danger btn-sm delete-listing" data-pid="<?php echo get_the_id(); ?>" href="#">
										<i class="fa fa-trash"></i>
										<?php _e( 'Delete', 'ultimate-classified-listings' ); ?>
									</a>
								</td>
							</tr>
						<?php 
					}
					wp_reset_postdata();
				} else { ?>
					<tr><td colspan="6">
						<div class="alert alert-primary text-center"><?php _e( 'No Listings Found!', 'ultimate-classified-listings' ) ?></div>
					</td></tr>
				<?php }
			?>
		  </tbody>
		</table>
		<?php do_action( 'uclwp_pagination', $paged, $my_listings->max_num_pages ); ?>
	</div>
</div>