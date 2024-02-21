<div class="uclwp-bs-wrapper">
	<div class="card p-3 mb-3">
		<div class="row text-center">
			<div class="col-sm-6">
				<h4><?php _e('Icon', 'ultimate-classified-listings'); ?></h4>
			    <select class="ucl-iconpicker" id="ucl-iconpicker" name="ucl_category_icon">
			    	<option value=""><?php _e( 'No icon', 'ultimate-classified-listings' ) ?></option>
			    	<?php
			    		$icons = uclwp_get_icons_list();
			    		foreach ($icons as $iconClass) {
			    			echo "<option>{$iconClass}</option>";
			    		}
			    	?>
			    </select>
			</div>
			<div class="col-sm-6">
				<h4><?php _e('Image', 'ultimate-classified-listings'); ?></h4>
			    <input type="hidden" id="category-image-id" name="ucl_category_image" class="custom_media_url" value="">
			    <div id="category-image-wrapper"></div>
			    <div class="ucl-image-upload">
			    	<i class="bi bi-upload"></i>
			    </div>
			</div>
		</div>
	</div>
</div>