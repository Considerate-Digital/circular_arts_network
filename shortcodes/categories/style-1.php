<div class="uclwp-bs-wrapper">
		<div class="row">
			<?php 
            if (!empty($categories)) {

                foreach ($categories as $cat) { ?>
    				<div class="<?php echo esc_attr( $col_classes ); ?>">
                            <div class="ucl-single-cat text-center">
                                <div class="ucl-cat-icon">
                                    <?php $this->render_category_image($cat->term_id, $attrs['image_size']); ?>
                                </div>
                                <div class="ucl-cat-title">
                                    <h3>
                                        <?php echo esc_attr( $cat->name ); ?>
                                    </h3>
                                </div>
                                <div class="ucl-cat-count">
                                    <span><?php echo esc_attr( $cat->count ); ?></span>
                                </div>
                                <a href="<?php echo get_term_link($cat->term_id); ?>" class="ucl-absolute-link"></a>
                            </div>
    				</div>
    				<?php 
    			} 
            } else { ?>
                <div class="col"><?php _e( 'Categories Not found', 'ultimate-classified-listings' ); ?></div>
            <?php } ?>
	</div>
</div>