<?php 
	$saved_table_label = uclwp_get_option('listing_compare_columns');

	if (!empty($saved_table_label)) {
		$array_value = explode("\n", $saved_table_label);
		foreach ($array_value as $value) {
			$value = trim($value);
			if ($value != '') {
				$column_value = explode( "|", $value);
				$table_columns_labels[] = $column_value['0'];
			}
		}
	} else {
		$default_labels = array(
			__( 'Price', 'ultimate-classified-listings' ),
			__( 'Purpose', 'ultimate-classified-listings' ),
			__( 'Condition', 'ultimate-classified-listings' ),
			__( 'Build Date', 'ultimate-classified-listings' ),
		);
		$default_labels = apply_filters( 'uclwp_compare_table_default_labels', $default_labels );
		$table_columns_labels = $default_labels;
	}

?>
<div class="prop-compare-wrapper uclwp-bs-wrapper">
	<div class="prop-compare">
		<h4 class="title_compare"><?php _e( 'Compare Listings', 'ultimate-classified-listings' ); ?></h4>
		<button class="compare_close" title="<?php _e( 'Close Compare Panel', 'ultimate-classified-listings' ); ?>" style="display: none"><i class="bi bi-chevron-right" aria-hidden="true"></i></button>
		<button class="compare_open" title="<?php _e( 'Open Compare Panel', 'ultimate-classified-listings' ); ?>" style="display: none"><i class="bi bi-chevron-left" aria-hidden="true"></i></button>
		<div class="ucl-compare-table">
			<table class="property-box">
				
			</table>
		</div>
		<button id="submit_compare" class="ucl-btn compare_prop_button" data-izimodal-open="#ucl-compare-modal"> <?php _e( "Compare", "ultimate-classified-listings" ) ?></button>
	</div>
</div>
<div id="ucl-compare-modal" class="uclwp-bs-wrapper iziModal">
	<button data-izimodal-close="" class="icon-close"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
	<div class="table-responsive">
	  <table class="table ucl-compare-table table-bordered m-0">
        <thead>
          <tr>
            <th class='fixed-row'><?php _e( "Title", "ultimate-classified-listings" ); ?></th>
            <?php foreach ($table_columns_labels as $label) { ?>
            	<th><?php _e( $label, "ultimate-classified-listings" ); ?></th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
	</div>
</div>