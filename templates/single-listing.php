<?php 
/**
** Single Listing Template
*/

/*
**========== Direct Access Restriction =========== 
*/
if( ! defined('ABSPATH' ) ){ exit; }

get_header(); ?>

<div class="can-bs-wrapper">
	<div class="container pt-5 pb-5">
		<div class="row">
			<div class="col-sm-9">
				<?php do_action( 'can_listing_content' ); ?>
			</div>
			<div class="col-sm-3">
				<?php do_action( 'can_listing_sidebar' ); ?>
			</div>
		</div>
	</div>
</div>
  
<?php get_footer(); ?>