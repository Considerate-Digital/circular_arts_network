<div class="can-section">
    <div class="wrap-slider">
        <div class="<?php echo esc_attr( $gallery_type ); ?>-custom" <?php echo call_user_func(array($this, $gallery_type.'_data_attrs')); ?>>
            <?php if($featured_image){
                echo get_the_post_thumbnail( $listing_id, $image_size, array('class' => 'skip-lazy') );
            } ?>
            <?php
                foreach ($galleryimages as $id) {
                    $id = function_exists('icl_object_id') ? icl_object_id($id, 'attachment', true) : $id;
                    $image_url = wp_get_attachment_image_url($id, $image_size);
                    $image_title = wp_strip_all_tags(get_the_title($id));
                    $image_alt = wp_strip_all_tags(get_post_meta($id, '_wp_attachment_image_alt', TRUE));
                    
                    if (wp_attachment_is( 'video', $id )) {
                        echo '<a href="$image_url" data-video="true"></a>';
                    } else {
                        echo '<img class="skip-lazy rem-slider-image" data-alt="'.$image_alt.'" data-title="'.$image_title.'" src="'.$image_url.'">';
                    }
                }
            ?>
        </div>
    </div>
</div>