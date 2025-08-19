<?php
  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
global $can_admin_settings;
$nonce_success = false;
if (isset($_REQUEST['_wpnonce'])) {
	$nonce_success = wp_verify_nonce( sanitize_text_field((wp_unslash($_REQUEST['_wpnonce']))), 'edit-listing' ); 
}
if (!$nonce_success) {
		wp_nonce_ays('log-out');
}
$listing_id = isset($_GET['listing_id']) ? sanitize_text_field(wp_unslash($_GET['listing_id'], 'listing_id' )) : '';
$field_sections = $can_admin_settings->get_fields_sections();
?>
<div class="can-screen-wrapper">
	<div class="can-screen-header">
		<?php esc_html_e( 'Edit Listing', 'circular-arts-network' ) ?>
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
						<option <?php echo (get_post_status($listing_id) == 'draft') ? 'selected' : '' ; ?> value="draft"><?php esc_html_e( 'Draft', 'circular-arts-network' ); ?></option>
						<option <?php echo (get_post_status($listing_id) == 'publish') ? 'selected' : '' ; ?> value="publish"><?php esc_html_e( 'Publish', 'circular-arts-network' ); ?></option>
					</select>
				</div>
				<?php } else { ?>
					<div class="col-sm-12 col-md-12">
						<div class="alert alert-info"><?php esc_html_e( 'This listing is awaiting approval', 'circular-arts-network' ) ?></div>
					</div>
				<?php } ?>
				<div class="col-sm-6 col-md-4">
					<input type="hidden" name="listing_id" value="<?php echo esc_attr( $listing_id ); ?>">
					<input class="btn btn-success" type="submit" value="<?php esc_html_e( 'Save Changes', 'circular-arts-network' ); ?>">
				</div>
			</div>
			<?php wp_nonce_field( 'listing-updated'); ?>
		</form>
	</div>
</div>
