<?php 
  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php echo esc_html(apply_filters( 'the_content', $value )); ?>
