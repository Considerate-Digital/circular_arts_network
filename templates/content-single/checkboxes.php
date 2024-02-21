<div class="col-sm-12">
	<ul class="ucl-features clearfix">
	    <?php
	    	foreach ($value as $cb => $val) {
	            $feature = stripcslashes($cb);
	            $translated_feature = uclwp_wpml_translate($feature, 'ultimate-classified-listings-features');
	            echo "<li>$translated_feature</li>";
	    	}
	    ?>
	</ul>
</div>