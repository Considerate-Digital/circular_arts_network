<div class="uclwp-list-box-wrap clearfix">
	<div class="uclwp-box-inner">
		<div class="uclwp-image-wrap">
			<?php $this->render_ribbon($listing_id); ?>
			<a href="<?php echo get_the_permalink( $listing_id ); ?>" target="<?php echo esc_attr( $target ); ?>" class="ucl-link">
				<div class="uclwp-image">
					<?php do_action( 'uclwp_featured_image', $listing_id ) ?>
				</div>
			</a>
		</div>
		<div class="uclwp-content-wrap">
			<div class="uclwp-title-area">
				<h2><?php echo get_the_title($listing_id); ?></h2>
				<?php $this->render_categories($listing_id); ?>
				<div class="uclwp-excerpt d-none d-lg-block">
					<p><?php echo wp_trim_words(get_the_excerpt($listing_id), 10, '...'); ?></p>
				</div>
			</div>
			<div class="uclwp-meta-area">
				<?php $this->render_listing_meta($listing_id); ?>
			</div>
			<div class="uclwp-footer-area">
				<p class="uclwp-price-wrap float-start">
					<?php echo uclwp_get_field_value($listing_id, array('key' =>'regular_price', 'type' => 'price')); ?>
				</p>
				<div class="uclwp-actions float-end">
					<?php $this->render_action_buttons($listing_id); ?>
				</div>
			</div>
		</div>
		<div class="uclwp-btn-wrap">
			<a target="<?php echo esc_attr( $target ); ?>" href="<?php echo get_the_permalink( $listing_id ); ?>" class="ucl-btn"><?php _e( 'Details', 'ultimate-classified-listings' ) ?></a>
		</div>
	</div>
</div>