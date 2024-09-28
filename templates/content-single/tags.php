<div class="can-section">
	<h2><?php _e( 'Tags', 'circular-arts-network' ); ?></h2>
	<?php
            echo '<ul class="can-tags">';
                 
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