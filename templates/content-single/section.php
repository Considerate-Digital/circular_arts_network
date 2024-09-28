<div class="can-section section-<?php echo esc_attr( $section['key'] ) ?>">
	<?php echo can_get_section_title($section); ?>
	<div class="wrap-<?php echo esc_attr( $section['key'] ); ?>">
        <div class="row">
    		<?php
    			$inputFields = can_get_listing_fields();

                foreach ($inputFields as $field) {
                    
                    if($field['tab'] == $section['key']){
                        $this->render_single_field($listing_id, $field);
                    }
                }
            ?>
        </div>
	</div>
</div>