<?php

  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="can-section">
	<h2><?php echo esc_html(get_the_title( $listing_id )) ?></h2>
	<div class="listing-content">
        <?php
            $content_property = get_post($listing_id);
            $content = $content_property->post_content;
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
            echo wp_kses_post($content);
        ?>
	</div>
</div>
