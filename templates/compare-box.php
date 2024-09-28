<?php 
	$saved_table_label = can_get_option('listing_compare_columns');

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
			__( 'Price', 'circular-arts-network' ),
			__( 'Purpose', 'circular-arts-network' ),
			__( 'Condition', 'circular-arts-network' ),
			__( 'Build Date', 'circular-arts-network' ),
		);
		$default_labels = apply_filters( 'can_compare_table_default_labels', $default_labels );
		$table_columns_labels = $default_labels;
	}

?>
<div class="prop-compare-wrapper can-bs-wrapper">
	<div class="prop-compare">
		<h4 class="title_compare"><?php _e( 'Compare Listings', 'circular-arts-network' ); ?></h4>
		<button class="compare_close" title="<?php _e( 'Close Compare Panel', 'circular-arts-network' ); ?>" style="display: none"><i class="bi bi-chevron-right" aria-hidden="true"></i></button>
		<button class="compare_open" title="<?php _e( 'Open Compare Panel', 'circular-arts-network' ); ?>" style="display: none"><i class="bi bi-chevron-left" aria-hidden="true"></i></button>
		<div class="can-compare-table">
			<table class="property-box">
				
			</table>
		</div>
		<button id="submit_compare" class="can-btn compare_prop_button" data-izimodal-open="#can-compare-modal"> <?php _e( "Compare", "circular-arts-network" ) ?></button>
	</div>
</div>
<div id="can-compare-modal" class="can-bs-wrapper iziModal">
	<button data-izimodal-close="" class="icon-close"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
	<div class="table-responsive">
	  <table class="table can-compare-table table-bordered m-0">
        <thead>
          <tr>
            <th class='fixed-row'><?php _e( "Title", "circular-arts-network" ); ?></th>
            <?php foreach ($table_columns_labels as $label) { ?>
            	<th><?php _e( $label, "circular-arts-network" ); ?></th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
	</div>
</div>