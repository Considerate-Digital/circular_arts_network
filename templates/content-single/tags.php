<div class="uclwp-section">
	<h2><?php _e( 'Tags', 'ultimate-classified-listings' ); ?></h2>
	<?php
            echo '<ul class="uclwp-tags">';
                 
                foreach ( $terms as $term ) {
                 
                    $term_link = get_term_link( $term );
                    
                    if ( is_wp_error( $term_link ) ) {
                        continue;
                    }

                    echo '<li><a class="filter" href="' . esc_url( $term_link ) . '">' . $term->name . ' </a></li>';
                }
                 
            echo '</ul>';
	?>
</div>