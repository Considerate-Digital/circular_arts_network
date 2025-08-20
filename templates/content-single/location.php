<?php

  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="can-section">
	<?php echo esc_html(can_get_section_title($section)); ?>
	<div class="wrap-<?php echo esc_attr( $section['key'] ); ?>">
		<div id="map-canvas"></div>
	</div>
</div>
