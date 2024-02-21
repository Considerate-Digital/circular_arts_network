<div class="<?php echo esc_attr( $cols ); ?>">
	<div class="ucl-single-field">
		<span class="ucl-field-title"><?php echo uclwp_wpml_translate($field['title'], 'ultimate-classified-listings-fields'); ?></span>
		<span class="ucl-field-value"><?php echo uclwp_get_field_value($listing_id, $field, $value); ?></span>
	</div>
</div>