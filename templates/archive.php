<?php 
/**
** Listing Archive Template
*/

/*
**========== Direct Access Restriction =========== 
*/
if( ! defined('ABSPATH' ) ){ exit; }

get_header();
$columns = (isset($_GET['layout']) && $_GET['layout'] == 'list') ? 'col-sm-12' : uclwp_get_option('archive_page_cols', 'col-sm-4') ;
?>

<div class="uclwp-bs-wrapper">
	<div class="container pt-5 pb-5">
		<?php do_action( 'uclwp_archive_topbar' ); ?>
		<div class="row ucl-display-listings">
			<?php if( have_posts() ){ while( have_posts() ){ the_post(); ?>
				<div id="ucl-listing-<?php the_ID(); ?>" class="<?php echo esc_attr( $columns ); ?>">
					<?php do_action('uclwp_listing_box', $post->ID, '1', 'grid'); ?>
				</div>
			<?php } } ?>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<?php do_action( 'uclwp_pagination' ); ?>
			</div>
		</div>
	</div>
</div>
  
<?php get_footer(); ?>