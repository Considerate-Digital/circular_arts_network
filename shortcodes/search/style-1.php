<div class="can-bs-wrapper">
	<div class="can-search-1" style="background-color: <?php echo esc_attr( $attrs['bg_color'] ); ?>;">
		<form action="<?php echo esc_url( $attrs['results_url'] ); ?>" method="get" class="can-search-form">
			<input type="hidden" name="action" value="can_search_listing">
			<div class="row">
				<?php
					if (in_array('search_field', $searchFields)) { ?>
						<div class="<?php echo esc_attr( $columns ); ?>">
							<div class="can-input-wrap">
								<i class="bi bi-search"></i>
								<input type="text" class="can-input-field" name="keywords" placeholder="<?php _e( 'Search Keywords...', 'circular-arts-network' ); ?>">	
							</div>
						</div>
					<?php }

					$inputFields = can_get_listing_fields();
					foreach ($inputFields as $field) {
						if (in_array($field['key'], $searchFields)) {
							echo "<div class='{$columns}'>";
								echo "<div class='can-input-wrap'>";
									echo can_render_search_field($field);
								echo "</div>";
							echo "</div>";
							
						}
					}
				?>
				<div class="<?php echo esc_attr( $columns ); ?> text-end">
					<input type="submit" class="can-btn" value="<?php _e( 'Search', 'circular-arts-network' ) ?>">
				</div>
			</div>
		</form>
	</div>
	<div class="can-loader text-center">
		<img src="<?php echo CAN_URL.'/assets/images/ajax-loader.gif'; ?>" alt="<?php _e( 'Loading...', 'circular-arts-network' ); ?>">
	</div>
	<div class="search-results"></div>
</div>
