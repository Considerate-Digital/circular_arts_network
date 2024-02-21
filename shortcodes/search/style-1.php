<div class="uclwp-bs-wrapper">
	<div class="ucl-search-1" style="background-color: <?php echo esc_attr( $attrs['bg_color'] ); ?>;">
		<form action="<?php echo esc_url( $attrs['results_url'] ); ?>" method="get" class="ucl-search-form">
			<input type="hidden" name="action" value="uclwp_search_listing">
			<div class="row">
				<?php
					if (in_array('search_field', $searchFields)) { ?>
						<div class="<?php echo esc_attr( $columns ); ?>">
							<div class="ucl-input-wrap">
								<i class="bi bi-search"></i>
								<input type="text" class="ucl-input-field" name="keywords" placeholder="<?php _e( 'Search Keywords...', 'ultimate-classified-listings' ); ?>">	
							</div>
						</div>
					<?php }

					$inputFields = uclwp_get_listing_fields();
					foreach ($inputFields as $field) {
						if (in_array($field['key'], $searchFields)) {
							echo "<div class='{$columns}'>";
								echo "<div class='ucl-input-wrap'>";
									echo uclwp_render_search_field($field);
								echo "</div>";
							echo "</div>";
							
						}
					}
				?>
				<div class="<?php echo esc_attr( $columns ); ?> text-end">
					<input type="submit" class="ucl-btn" value="<?php _e( 'Search', 'ultimate-classified-listings' ) ?>">
				</div>
			</div>
		</form>
	</div>
	<div class="uclwp-loader text-center">
		<img src="<?php echo UCLWP_URL.'/assets/images/ajax-loader.gif'; ?>" alt="<?php _e( 'Loading...', 'ultimate-classified-listings' ); ?>">
	</div>
	<div class="search-results"></div>
</div>