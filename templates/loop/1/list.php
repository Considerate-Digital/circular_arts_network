<?php

  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="can-list-box-wrap clearfix">
	<div class="can-box-inner can-box-inner-<?php echo esc_html($this->get_category_name($listing_id)); ?>"">
		<div class="can-image-wrap">
			<?php $this->render_ribbon($listing_id); ?>
			<a href="<?php echo esc_html(get_the_permalink( $listing_id )); ?>" target="<?php echo esc_attr( $target ); ?>" class="can-link">
				<picture class="can-image">
					<?php do_action( 'circartsnet_featured_image', $listing_id ) ?>
				</picture>
			</a>
		</div>
		<div class="can-content-wrap can-content-wrap-<?php echo esc_html($this->get_category_name($listing_id)); ?>">
			<div class="can-title-area">
				<h2><?php echo esc_html(get_the_title($listing_id)); ?></h2>
				<?php $this->render_categories($listing_id); ?>
				<div class="can-excerpt d-none d-lg-block">
					<h3 class="can-excerpt-title">Description</h3>
					<p><?php echo esc_html(wp_trim_words(get_the_excerpt($listing_id), 50, '...')); ?></p>
				</div>
			</div>
			<?php if  ($this->get_category_name($listing_id) != "stories"):  ?>
			<div class="can-meta-area">
				<?php $this->render_listing_meta($listing_id); ?>
			</div>
			<div class="can-footer-area">
				<p class="can-price-wrap float-start">
					<?php echo wp_kses_post(circartsnet_get_field_value($listing_id, array('key' =>'regular_price', 'type' => 'price'))); ?>
				</p>
				<div class="can-actions float-end">
					<?php $this->render_action_buttons($listing_id); ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<div class="can-btn-wrap can-btn-wrap-<?php echo esc_html($this->get_category_name($listing_id)); ?>">
			<a target="<?php echo esc_attr( $target ); ?>" href="<?php echo esc_html(get_the_permalink( $listing_id )); ?>" class="can-btn">
				<?php if  ($this->get_category_name($listing_id) != "stories"): 
					esc_html_e( 'Details', 'circular-arts-network' );
					else: 

					esc_html_e( 'Read', 'circular-arts-network' );
				endif; ?>

</a>
		</div>
	</div>
</div>
