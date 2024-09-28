<div class="can-bs-wrapper">
		<div class="row">
			<?php 
            if (!empty($categories)) {

                foreach ($categories as $cat) { 
                 $cat_names = array(
                  'Materials',
                  'Equipment',
                  'Transport',
                  'Furniture',
                  'Packing',
                  'Time',
                  'Skills',
                  'Everything Else',
		  'Wanted',
		  'Stories'
                );
          ?>
    				<div class="<?php echo esc_attr( $col_classes ); ?>">
                            <div class="can-single-cat text-center">
                                <div class="can-cat-icon">
                                    <?php if ( in_array($cat->name, $cat_names ) ) { ?>
                                        <img src="<?php echo plugins_url() ?>/circular_arts_network/assets/images/categories/<?php echo $cat->name ?>.svg" />
                                    <?php } else { 
                                        $this->render_category_image($cat->term_id, $attrs['image_size']); 
                                    } ?>
                                </div>
                                <div class="can-cat-title">
                                    <h3>
                                        <?php echo esc_attr( $cat->name ); ?>
                                        <?php //var_dump( $cat ) ?>
                                    </h3>
                                </div>
                                <div class="can-cat-count">
                                    <span><?php echo esc_attr( $cat->count ); ?></span>
                                </div>
                                <a href="<?php echo get_term_link($cat->term_id); ?>" class="can-absolute-link"></a>
                            </div>
    				</div>
    				<?php 
    			} 
            } else { ?>
                <div class="col"><?php _e( 'Categories Not found', 'circular-arts-network' ); ?></div>
            <?php } ?>
	</div>
</div>
