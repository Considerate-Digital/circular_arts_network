<div class="col-sm-12">
	<ul class="can-features clearfix">
	    <?php
	    	foreach ($value as $cb => $val) {
	            $feature = stripcslashes($cb);
	            $translated_feature = can_wpml_translate($feature, 'circular-arts-network-features');
	            echo "<li>$translated_feature</li>";
	    	}
	    ?>
	</ul>
</div>