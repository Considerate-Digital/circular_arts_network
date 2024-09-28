<?php
	$pending_sellers = get_option( 'can_pending_users' );
	$args = array(
		'role'         => 'can_listing_seller',
	); 
	$registered_sellers = get_users( $args );
?>
<div class="wrap can-bs-wrapper">
	<div class="card mb-3">
		<div class="card-header">
			<b>
				<?php _e( 'Pending Sellers', 'circular-arts-network' ); ?>
				-
				<?php echo (!empty($pending_sellers)) ? count($pending_sellers) : '0' ; ?>
				
			</b>
		</div>
		<div class="card-body">
			<?php if (is_array($pending_sellers) && !empty($pending_sellers)) { ?>
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th><?php _e( 'Profile Picture', 'circular-arts-network' ); ?></th>
							<th><?php _e( 'First Name', 'circular-arts-network' ) ?></th>
							<th><?php _e( 'Last Name', 'circular-arts-network' ); ?></th>
							<th><?php _e( 'Username', 'circular-arts-network' ); ?></th>
							<th><?php _e( 'Email', 'circular-arts-network' ); ?></th>
							<th><?php _e( 'Phone', 'circular-arts-network' ); ?></th>
							<th><?php _e( 'Registered', 'circular-arts-network' ); ?></th>
							<th><?php _e( 'Action', 'circular-arts-network' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($pending_sellers as $index => $seller) { ?>
							<tr>
								<td><?php echo (isset($seller['seller_image'])) ? wp_get_attachment_image($seller['seller_image'], 'thumbnail') : ''; ?></td>
								<td><?php echo esc_attr( $seller['first_name'] ) ?></td>
								<td><?php echo esc_attr( $seller['last_name'] ) ?></td>
								<td><?php echo esc_attr( $seller['username'] ) ?></td>
								<td><?php echo esc_attr( $seller['useremail'] ) ?></td>
								<td><?php echo esc_attr( $seller['seller_phone'] ) ?></td>
								<td><?php echo isset($seller['time']) ? human_time_diff(strtotime($seller['time'])).' ago' : ''; ?></td>
								<td>
									<button class="btn btn-sm btn-danger deny-user" data-userindex="<?php echo esc_attr( $index ); ?>"><?php _e( 'Deny', 'circular-arts-network' ); ?></button>
									<button class="btn btn-sm btn-success approve-user" data-userindex="<?php echo esc_attr( $index ); ?>"><?php _e( 'Approve', 'circular-arts-network' ); ?></button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else { ?>
				<div class="alert alert-info">
					<?php _e( 'You dont have any pending sellers.', 'circular-arts-network' ); ?> 
				</div>
			<?php } ?>
		</div>
	</div>

	<div class="card mb-2">
		<div class="card-header">
			<b><?php _e( 'Registered Sellers', 'circular-arts-network' ); ?> - <?php echo count($registered_sellers); ?></b>
		</div>
		<div class="card-body">
			<?php if (is_array($registered_sellers) && !empty($registered_sellers)) { ?>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><?php _e( 'Profile Picture', 'circular-arts-network' ); ?></th>
							<th><?php _e( 'First Name', 'circular-arts-network' ) ?></th>
							<th><?php _e( 'Last Name', 'circular-arts-network' ); ?></th>
							<th><?php _e( 'Username', 'circular-arts-network' ); ?></th>
							<th><?php _e( 'Email', 'circular-arts-network' ); ?></th>
							<th><?php _e( 'Phone', 'circular-arts-network' ); ?></th>
							<th><?php _e( 'Listings', 'circular-arts-network' ); ?></th>
							<th><?php _e( 'Profile', 'circular-arts-network' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($registered_sellers as $seller) {
							$seller_info = get_userdata($seller->ID);
							$image_id = get_user_meta( $seller->ID, 'seller_image', true );
							if (!is_wp_error($image_id)) {
								$image_id = esc_attr($image_id);

							}


?>
							<tr>
								<td><?php echo ($image_id) ? wp_get_attachment_image($image_id) : '' ?></td>
								<td><?php echo esc_attr( $seller_info->first_name ); ?></td>
								<td><?php echo esc_attr( $seller_info->last_name ); ?></td>
								<td><?php echo esc_attr( $seller_info->user_login ); ?></td>
								<td><?php echo esc_attr( $seller_info->user_email ); ?></td>
								<td><?php echo esc_attr( get_user_meta( $seller->ID, 'seller_phone', true ) ); ?></td>
								<td><?php echo count_user_posts( $seller->ID, 'can_listing' ); ?></td>
								<td>
									<a class="btn btn-sm btn-primary" target="_blank" href="<?php echo get_author_posts_url( $seller->ID ); ?>"><?php _e( 'View Profile', 'circular-arts-network' ); ?></a>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else { ?>
				<div class="alert alert-info">
					<?php _e( 'You dont have any registered sellers.', 'circular-arts-network' ); ?> 
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="text-right">
		<a href="<?php echo admin_url( 'user-new.php' ); ?>" class="btn btn-primary"><?php _e( 'Register New Seller', 'circular-arts-network' ); ?></a>
	</div>
</div>
