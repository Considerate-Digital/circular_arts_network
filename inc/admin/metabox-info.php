
<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="can-listing-info-box can-bs-wrapper">
	<?php
		foreach ($field_sections as $section) {
			circartsnet_render_listing_section($section);
		}
	?>
</div>
