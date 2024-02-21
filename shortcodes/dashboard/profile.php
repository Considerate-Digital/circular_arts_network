<?php
	$current_user = wp_get_current_user();
?>
<div class="ucl-screen-wrapper">
	<div class="ucl-screen-header">
		<?php _e( 'Edit Profile', 'ultimate-classified-listings' ) ?>
	</div>
	<div class="edit-profile-wrap ucl-screen-content">
		<form action="#" class="ucl-update-profile">
			<input type="hidden" name="action" value="uclwp_update_profile">
			<input type="hidden" name="seller_id" value="<?php echo esc_attr( $current_user->ID ); ?>">
			<div class="row">
				<div class="col">
					<div class="ucl-pic-wrap">
						<div class="ucl-pp">
							<?php echo wp_get_attachment_image( get_user_meta( $current_user->ID, 'seller_image', true ), 'full'); ?>
						</div>
						<a href="#" class="ucl-upload-btn"
							data-title="<?php _e( 'Choose Image for Profile Picture', 'ultimate-classified-listings' ); ?>"
							data-btntext="<?php _e( 'Set Profile', 'ultimate-classified-listings' ); ?>"
							>
							<i class="bi bi-card-image"></i>
							<?php _e( 'Edit', 'ultimate-classified-listings' ) ?>
						</a>
						<input type="hidden" name="seller_image" class="seller_image" value="<?php echo esc_attr( get_user_meta( $current_user->ID, 'seller_image', true ) ); ?>">
					</div>
				</div>
				<div class="col">
					<table class="table table-borderless">
						<tr>
							<th><?php _e( 'First Name', 'ultimate-classified-listings' ); ?></th>
							<td><input type="text" class="form-control" name="first_name" value="<?php echo esc_attr( $current_user->first_name ); ?>" required></td>
						</tr>
						<tr>
							<th><?php _e( 'Last Name', 'ultimate-classified-listings' ); ?></th>
							<td><input type="text" class="form-control" name="last_name" value="<?php echo esc_attr( $current_user->last_name ); ?>" required></td>
						</tr>
						<tr>
							<th><?php _e( 'Username', 'ultimate-classified-listings' ); ?></th>
							<td><input type="text" class="form-control" disabled value="<?php echo esc_attr( $current_user->user_login ); ?>"></td>
						</tr>
						<tr>
							<th><?php _e( 'Email', 'ultimate-classified-listings' ); ?></th>
							<td><input type="email" class="form-control" name="seller_email" value="<?php echo esc_attr( $current_user->user_email ); ?>" required></td>
						</tr>
						<tr>
							<th><?php _e( 'Phone', 'ultimate-classified-listings' ); ?></th>
							<td><input type="text" class="form-control" name="seller_phone" value="<?php echo esc_attr( get_user_meta( $current_user->ID, 'seller_phone', true ) ); ?>" required></td>
						</tr>
					</table>
					<div class="text-end px-2">
						<input class="btn btn-success" type="submit" value="<?php _e( 'Update Profile', 'ultimate-classified-listings' ); ?>">
					</div>
				</div>
			</div>
				
		</form>
	</div>
</div>