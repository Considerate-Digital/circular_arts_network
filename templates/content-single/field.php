
<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="<?php echo  esc_html($cols); ?>">
	<div class="can-single-field">
		<span class="can-field-title"><?php echo wp_kses_post(circartsnet_wpml_translate($field['title'], 'circular-arts-network-fields')); ?>:</span>
		<span class="can-field-value"><?php echo wp_kses_post(circartsnet_get_field_value($listing_id, $field, $value)); ?></span>
	</div>
</div>
