<?php

  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="can-section section-<?php echo esc_attr( $section['key'] ) ?>">
	<?php echo circartsnet_get_section_title($section); ?>
	<div class="wrap-<?php echo esc_attr( $section['key'] ); ?>">
        <div class="row">
    		<?php
    			$inputFields = circartsnet_get_listing_fields();

                foreach ($inputFields as $field) {
                    
                    if($field['tab'] == $section['key']){
                        $this->render_single_field($listing_id, $field);
                    }
                }
            ?>
        </div>
	</div>
</div>
