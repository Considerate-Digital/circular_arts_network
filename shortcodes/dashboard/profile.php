<?php
	$current_user = wp_get_current_user();
?>
<div class="can-screen-wrapper">
	<div class="can-screen-header">
		<?php _e( 'Edit Profile', 'circular-arts-network' ) ?>
	</div>
	<div class="edit-profile-wrap can-screen-content">
		<form action="#" class="can-update-profile">
			<input type="hidden" name="action" value="can_update_profile">
			<input type="hidden" name="seller_id" value="<?php echo esc_attr( $current_user->ID ); ?>">
			<div class="row">
				<div class="col">
					<div class="can-pic-wrap">
						<div class="can-pp">
							<?php echo wp_get_attachment_image( get_user_meta( $current_user->ID, 'seller_image', true ), 'full'); ?>
						</div>
						<a href="#" class="can-upload-btn"
							data-title="<?php _e( 'Choose Image for Profile Picture', 'circular-arts-network' ); ?>"
							data-btntext="<?php _e( 'Set Profile', 'circular-arts-network' ); ?>"
							>
							<i class="bi bi-card-image"></i>
							<?php _e( 'Edit', 'circular-arts-network' ) ?>
						</a>
						<input type="hidden" name="seller_image" class="seller_image" value="<?php echo esc_attr( get_user_meta( $current_user->ID, 'seller_image', true ) ); ?>">
					</div>
				</div>
				<div class="col">
					<table class="table table-borderless">
						<tr>
							<th><?php _e( 'First Name', 'circular-arts-network' ); ?></th>
							<td><input type="text" class="form-control" name="first_name" value="<?php echo esc_attr( $current_user->first_name ); ?>" required></td>
						</tr>
						<tr>
							<th><?php _e( 'Last Name', 'circular-arts-network' ); ?></th>
							<td><input type="text" class="form-control" name="last_name" value="<?php echo esc_attr( $current_user->last_name ); ?>" required></td>
						</tr>
						<tr>
							<th><?php _e( 'Username', 'circular-arts-network' ); ?></th>
							<td><input type="text" class="form-control" disabled value="<?php echo esc_attr( $current_user->user_login ); ?>"></td>
						</tr>
						<tr>
							<th><?php _e( 'Email', 'circular-arts-network' ); ?></th>
							<td><input type="email" class="form-control" name="seller_email" value="<?php echo esc_attr( $current_user->user_email ); ?>" required></td>
						</tr>
						<tr>
							<th><?php _e( 'Phone', 'circular-arts-network' ); ?></th>
							<td><input type="text" class="form-control" name="seller_phone" value="<?php echo esc_attr( get_user_meta( $current_user->ID, 'seller_phone', true ) ); ?>" required></td>
						</tr>
					</table>
					<div class="text-end px-2">
						<input class="btn btn-success" type="submit" value="<?php _e( 'Update Profile', 'circular-arts-network' ); ?>">
					</div>
				</div>
			</div>
				
		</form>
	</div>
</div>