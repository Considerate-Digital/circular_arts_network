<div class="<?php echo esc_attr( $cols ); ?>">
	<div class="can-single-field">
		<span class="can-field-title"><?php echo can_wpml_translate($field['title'], 'circular-arts-network-fields'); ?>:</span>
		<span class="can-field-value"><?php echo can_get_field_value($listing_id, $field, $value); ?></span>
	</div>
</div>
