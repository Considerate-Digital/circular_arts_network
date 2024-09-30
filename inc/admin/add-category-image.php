<div class="can-bs-wrapper">
	<div class="card p-3 mb-3">
		<div class="row text-center">
			<div class="col-sm-6">
				<h4><?php _e('Icon', 'circular-arts-network'); ?></h4>
			    <select class="can-iconpicker" id="can-iconpicker" name="can_category_icon">
			    	<option value=""><?php _e( 'No icon', 'circular-arts-network' ) ?></option>
			    	<?php
			    		$icons = can_get_icons_list();
			    		foreach ($icons as $iconClass) {
			    			echo "<option>{$iconClass}</option>";
			    		}
			    	?>
			    </select>
			</div>
			<div class="col-sm-6">
				<h4><?php _e('Image', 'circular-arts-network'); ?></h4>
			    <input type="hidden" id="category-image-id" name="can_category_image" class="custom_media_url" value="">
			    <div id="category-image-wrapper"></div>
			    <div class="can-image-upload">
			    	<i class="bi bi-upload"></i>
			    </div>
			</div>
		</div>
	</div>
</div>