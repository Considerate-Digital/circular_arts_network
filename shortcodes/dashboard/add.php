<?php
global $can_admin_settings;
$field_sections = $can_admin_settings->get_fields_sections();
?>
<div class="can-screen-wrapper">
	<div class="can-screen-header">
		<?php _e( 'Create Listing', 'circular-arts-network' ) ?>
	</div>
	<div class="edit-listing-wrap can-screen-content">
		<form action="#" class="can-listing-form">
			<input type="hidden" name="action" value="can_create_listing_frontend">
			<?php
				foreach ($field_sections as $section) {
					can_render_listing_section($section);
				}
			?>
			<input class="btn btn-success" type="submit" value="<?php _e( 'Create Listing', 'circular-arts-network' ); ?>">
		</form>
	</div>
</div>