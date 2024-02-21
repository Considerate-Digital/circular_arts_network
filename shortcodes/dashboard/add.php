<?php
global $uclwp_admin_settings;
$field_sections = $uclwp_admin_settings->get_fields_sections();
?>
<div class="ucl-screen-wrapper">
	<div class="ucl-screen-header">
		<?php _e( 'Create Listing', 'ultimate-classified-listings' ) ?>
	</div>
	<div class="edit-listing-wrap ucl-screen-content">
		<form action="#" class="ucl-listing-form">
			<input type="hidden" name="action" value="uclwp_create_listing_frontend">
			<?php
				foreach ($field_sections as $section) {
					uclwp_render_listing_section($section);
				}
			?>
			<input class="btn btn-success" type="submit" value="<?php _e( 'Create Listing', 'ultimate-classified-listings' ); ?>">
		</form>
	</div>
</div>