<div class="can-top-bar clearfix">
	
	<?php
		if (is_archive()) {
			echo '<div class="float-start">';
				the_archive_title( '<h2 class="can-archive-title">', '</h1>' );
			echo '</div>';
		}
	?>
	
	<div class="<?php echo (is_archive()) ? 'float-end' : ''; ?> clearfix">
		<form method="GET" action="#">
	        <div class="can-select-box float-start">
		<?php /*
				<select class="can-select-menu" name="sort_by" onchange="this.form.submit()">
					<?php
						$sorting_options = $this->lists_sorting_options();
						foreach ($sorting_options as $option) {
							$selected = (isset($_GET['sort_by']) && $_GET['sort_by'] == $option['value']) ? 'selected' : '' ; ?>
							<option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $option['value'] ); ?>"><?php echo esc_attr( $option['title'] ); ?></option>
						<?php }
					?>
				</select>

		 */?>
				<input type="hidden" name="layout" value="<?php echo (isset($_GET['layout'])) ? $_GET['layout'] : 'grid' ;  ?>">
	        </div>
			<div class="can-menu-box <?php echo (is_archive()) ? 'float-start' : 'float-end'; ?>">
			    <a href="<?php echo esc_url( add_query_arg( 'layout', 'list' ) ); ?>" class="can-list-view <?php echo (isset($_GET['layout']) && $_GET['layout'] == 'list') ? 'active' : '' ; ?>">
			    	<i class="bi bi-list-task"></i>
			    </a>
			    <a href="<?php echo esc_url( add_query_arg( 'layout', 'grid' ) ); ?>" class="can-grid-view <?php echo ((isset($_GET['layout']) && $_GET['layout'] == 'grid') || !isset($_GET['layout'])) ? 'active' : '' ; ?>">
			    	<i class="bi bi-grid"></i>
			    </a>
			</div>
		</form>
	</div>
</div>
