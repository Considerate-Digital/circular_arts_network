<?php
  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $circartsnet_admin_settings;
$field_sections = $circartsnet_admin_settings->get_fields_sections();
?>
<div class="can-screen-wrapper">
	<div class="can-screen-header">
		<?php esc_html_e( 'Create Listing', 'circular-arts-network' ) ?>
	</div>
	<div class="edit-listing-wrap can-screen-content">
		<form action="#" class="can-listing-form">
			<input type="hidden" name="action" value="circartsnet_create_listing_frontend">
			<?php
				foreach ($field_sections as $section) {
					circartsnet_render_listing_section($section);
				}
			?>
			<input class="btn btn-success" type="submit" value="<?php esc_html_e( 'Create Listing', 'circular-arts-network' ); ?>">
			<?php wp_nonce_field( 'listing-added'); ?>
		</form>
	</div>
</div>
