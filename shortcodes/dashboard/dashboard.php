<div class="welcome-screen ucl-screen-wrapper mb-3">
	<div class="ucl-screen-header">
		<?php _e( 'Welcome', 'ultimate-classified-listings' ) ?>
		<?php
			$curren_user = wp_get_current_user();
			echo esc_attr( $curren_user->display_name );
		?>
		<a href="<?php echo wp_logout_url( get_permalink() ); ?>" class="btn btn-sm btn-danger float-end text-decoration-none"><i class="bi bi-power"></i> <?php _e( 'Logout', 'ultimate-classified-listings' ); ?></a>
	</div>
	<div class="ucl-screen-content">
		<div class="row">
			<div class="col">
				<a href="<?php echo esc_url( add_query_arg( array('ucl_page' => 'listings', 'uclwp_status' => 'draft') ) ); ?>" class="d-block text-dark text-decoration-none">
					<div class="p-3 py-4 mb-2 bg-light text-center rounded">
						<i class="bi bi-file-earmark-ruled display-5"></i>
						<p class="m-0">
							<?php _e( 'Drafts', 'ultimate-classified-listings' ); ?>
							(<?php echo uclwp_count_user_listings($curren_user->ID, 'draft'); ?>)
						</p>
					</div>
				</a>
			</div>
			<div class="col">
				<a href="<?php echo esc_url( add_query_arg( array('ucl_page' => 'listings', 'uclwp_status' => 'pending') ) ); ?>" class="d-block text-dark text-decoration-none">
					<div class="p-3 py-4 mb-2 bg-light text-center rounded">
						<i class="bi bi-file-earmark-text display-5"></i>
						<p class="m-0">
							<?php _e( 'Pending', 'ultimate-classified-listings' ); ?>
							(<?php echo uclwp_count_user_listings($curren_user->ID, 'pending'); ?>)
						</p>
					</div>
				</a>
			</div>
			<div class="col">
				<a href="<?php echo esc_url( add_query_arg( array('ucl_page' => 'listings', 'uclwp_status' => 'publish') ) ); ?>" class="d-block text-dark text-decoration-none">
					<div class="p-3 py-4 mb-2 bg-light text-center rounded">
						<i class="bi bi-file-richtext display-5"></i>
						<p class="m-0">
							<?php _e( 'Published', 'ultimate-classified-listings' ); ?>
							(<?php echo uclwp_count_user_listings($curren_user->ID, 'publish'); ?>)
						</p>
					</div>
				</a>
			</div>
		</div>
	</div>
</div>