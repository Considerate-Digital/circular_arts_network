<?php
	$pending_sellers = get_option( 'uclwp_pending_users' );
	$args = array(
		'role'         => 'ucl_listing_seller',
	); 
	$registered_sellers = get_users( $args );
?>
<div class="wrap uclwp-bs-wrapper">
	<div class="card mb-3">
		<div class="card-header">
			<b>
				<?php _e( 'Pending Sellers', 'ultimate-classified-listings' ); ?>
				-
				<?php echo (!empty($pending_sellers)) ? count($pending_sellers) : '0' ; ?>
				
			</b>
		</div>
		<div class="card-body">
			<?php if (is_array($pending_sellers) && !empty($pending_sellers)) { ?>
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th><?php _e( 'Profile Picture', 'ultimate-classified-listings' ); ?></th>
							<th><?php _e( 'First Name', 'ultimate-classified-listings' ) ?></th>
							<th><?php _e( 'Last Name', 'ultimate-classified-listings' ); ?></th>
							<th><?php _e( 'Username', 'ultimate-classified-listings' ); ?></th>
							<th><?php _e( 'Email', 'ultimate-classified-listings' ); ?></th>
							<th><?php _e( 'Phone', 'ultimate-classified-listings' ); ?></th>
							<th><?php _e( 'Registered', 'ultimate-classified-listings' ); ?></th>
							<th><?php _e( 'Action', 'ultimate-classified-listings' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($pending_sellers as $index => $seller) { ?>
							<tr>
								<td><?php echo (isset($seller['seller_image'])) ? wp_get_attachment_image($seller['seller_image'], 'thumbnail') : ''; ?></td>
								<td><?php echo esc_attr( $seller['first_name'] ) ?></td>
								<td><?php echo esc_attr( $seller['last_name'] ) ?></td>
								<td><?php echo esc_attr( $seller['username'] ) ?></td>
								<td><?php echo esc_attr( $seller['seller_email'] ) ?></td>
								<td><?php echo esc_attr( $seller['seller_phone'] ) ?></td>
								<td><?php echo isset($seller['time']) ? human_time_diff(strtotime($seller['time'])).' ago' : ''; ?></td>
								<td>
									<button class="btn btn-sm btn-danger deny-user" data-userindex="<?php echo esc_attr( $index ); ?>"><?php _e( 'Deny', 'ultimate-classified-listings' ); ?></button>
									<button class="btn btn-sm btn-success approve-user" data-userindex="<?php echo esc_attr( $index ); ?>"><?php _e( 'Approve', 'ultimate-classified-listings' ); ?></button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else { ?>
				<div class="alert alert-info">
					<?php _e( 'You dont have any pending sellers.', 'ultimate-classified-listings' ); ?> 
				</div>
			<?php } ?>
		</div>
	</div>

	<div class="card mb-2">
		<div class="card-header">
			<b><?php _e( 'Registered Sellers', 'ultimate-classified-listings' ); ?> - <?php echo count($registered_sellers); ?></b>
		</div>
		<div class="card-body">
			<?php if (is_array($registered_sellers) && !empty($registered_sellers)) { ?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><?php _e( 'Profile Picture', 'ultimate-classified-listings' ); ?></th>
							<th><?php _e( 'First Name', 'ultimate-classified-listings' ) ?></th>
							<th><?php _e( 'Last Name', 'ultimate-classified-listings' ); ?></th>
							<th><?php _e( 'Username', 'ultimate-classified-listings' ); ?></th>
							<th><?php _e( 'Email', 'ultimate-classified-listings' ); ?></th>
							<th><?php _e( 'Phone', 'ultimate-classified-listings' ); ?></th>
							<th><?php _e( 'Listings', 'ultimate-classified-listings' ); ?></th>
							<th><?php _e( 'Profile', 'ultimate-classified-listings' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($registered_sellers as $seller) {
							$seller_info = get_userdata($seller->ID);
							$image_id = esc_attr( get_user_meta( $seller->ID, 'seller_image', true ) ); ?>
							<tr>
								<td><?php echo ($image_id) ? wp_get_attachment_image($image_id) : '' ?></td>
								<td><?php echo esc_attr( $seller_info->first_name ); ?></td>
								<td><?php echo esc_attr( $seller_info->last_name ); ?></td>
								<td><?php echo esc_attr( $seller_info->user_login ); ?></td>
								<td><?php echo esc_attr( $seller_info->user_email ); ?></td>
								<td><?php echo esc_attr( get_user_meta( $seller->ID, 'seller_phone', true ) ); ?></td>
								<td><?php echo count_user_posts( $seller->ID, 'uclwp_listing' ); ?></td>
								<td>
									<a class="btn btn-sm btn-primary" target="_blank" href="<?php echo get_author_posts_url( $seller->ID ); ?>"><?php _e( 'View Profile', 'ultimate-classified-listings' ); ?></a>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else { ?>
				<div class="alert alert-info">
					<?php _e( 'You dont have any registered sellers.', 'ultimate-classified-listings' ); ?> 
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="text-right">
		<a href="<?php echo admin_url( 'user-new.php' ); ?>" class="btn btn-primary"><?php _e( 'Register New Seller', 'ultimate-classified-listings' ); ?></a>
	</div>
</div>