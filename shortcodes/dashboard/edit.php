<?php
global $uclwp_admin_settings;
$listing_id = esc_attr( $_GET['listing_id'] );
$field_sections = $uclwp_admin_settings->get_fields_sections();
?>
<div class="ucl-screen-wrapper">
	<div class="ucl-screen-header">
		<?php _e( 'Edit Listing', 'ultimate-classified-listings' ) ?>
	</div>
	<div class="edit-listing-wrap ucl-screen-content">
		<form action="#" class="ucl-listing-form">
			<input type="hidden" name="action" value="uclwp_create_listing_frontend">
			<?php
				foreach ($field_sections as $section) {
					uclwp_render_listing_section($section, $listing_id);
				}
			?>
			<div class="row">
				<?php if (get_post_status( $listing_id ) != 'pending') { ?>
				<div class="col-sm-6 col-md-4">
					<select name="listing_admin_status" class="form-select">
						<option <?php echo (get_post_status($listing_id) == 'draft') ? 'selected' : '' ; ?> value="draft"><?php _e( 'Draft', 'ultimate-classified-listings' ); ?></option>
						<option <?php echo (get_post_status($listing_id) == 'publish') ? 'selected' : '' ; ?> value="publish"><?php _e( 'Publish', 'ultimate-classified-listings' ); ?></option>
					</select>
				</div>
				<?php } else { ?>
					<div class="col-sm-12 col-md-12">
						<div class="alert alert-info"><?php _e( 'This listing is awaiting approval', 'ultimate-classified-listings' ) ?></div>
					</div>
				<?php } ?>
				<div class="col-sm-6 col-md-4">
					<input type="hidden" name="listing_id" value="<?php echo esc_attr( $listing_id ); ?>">
					<input class="btn btn-success" type="submit" value="<?php _e( 'Save Changes', 'ultimate-classified-listings' ); ?>">
				</div>
			</div>
		</form>
	</div>
</div>