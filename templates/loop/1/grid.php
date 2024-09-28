<div class="can-grid-box-wrap clearfix">
	<div class="can-box-inner can-box-inner-<?php echo $this->get_category_name($listing_id); ?>">
		<div class="can-image-wrap">
			<?php $this->render_ribbon($listing_id); ?>
			<a href="<?php echo get_the_permalink( $listing_id ); ?>" target="<?php echo esc_attr( $target ); ?>" class="can-link">
				<picture class="can-image">
					<?php do_action( 'can_featured_image', $listing_id ) ?>
				</picture>
			</a>
		</div>
		<div class="can-content-wrap can-content-wrap-<?php echo $this->get_category_name($listing_id); ?>">
			<div class="can-title-area">
				<h2><?php echo get_the_title($listing_id); ?></h2>
				<?php $this->render_categories($listing_id); ?>
			</div>
		<?php if  ($this->get_category_name($listing_id) != "stories"):  ?>
			<div class="can-meta-area">
				<?php $this->render_listing_meta($listing_id); ?>
			</div>
			<div class="can-footer-area clearfix">
				<p class="can-price-wrap float-start">
					<?php echo can_get_field_value($listing_id, array('key' =>'regular_price', 'type' => 'price')); ?>
				</p>
				<div class="can-actions float-end">
					<?php $this->render_action_buttons($listing_id); ?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
</div>
