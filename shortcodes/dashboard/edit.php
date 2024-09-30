<?php
global $can_admin_settings;
$listing_id = esc_attr( $_GET['listing_id'] );
$field_sections = $can_admin_settings->get_fields_sections();
?>
<div class="can-screen-wrapper">
	<div class="can-screen-header">
		<?php _e( 'Edit Listing', 'circular-arts-network' ) ?>
	</div>
	<div class="edit-listing-wrap can-screen-content">
		<form action="#" class="can-listing-form">
			<input type="hidden" name="action" value="can_create_listing_frontend">
			<?php
				foreach ($field_sections as $section) {
					can_render_listing_section($section, $listing_id);
				}
			?>
			<div class="row">
				<?php if (get_post_status( $listing_id ) != 'pending') { ?>
				<div class="col-sm-6 col-md-4">
					<select name="listing_admin_status" class="form-select">
						<option <?php echo (get_post_status($listing_id) == 'draft') ? 'selected' : '' ; ?> value="draft"><?php _e( 'Draft', 'circular-arts-network' ); ?></option>
						<option <?php echo (get_post_status($listing_id) == 'publish') ? 'selected' : '' ; ?> value="publish"><?php _e( 'Publish', 'circular-arts-network' ); ?></option>
					</select>
				</div>
				<?php } else { ?>
					<div class="col-sm-12 col-md-12">
						<div class="alert alert-info"><?php _e( 'This listing is awaiting approval', 'circular-arts-network' ) ?></div>
					</div>
				<?php } ?>
				<div class="col-sm-6 col-md-4">
					<input type="hidden" name="listing_id" value="<?php echo esc_attr( $listing_id ); ?>">
					<input class="btn btn-success" type="submit" value="<?php _e( 'Save Changes', 'circular-arts-network' ); ?>">
				</div>
			</div>
		</form>
	</div>
</div>