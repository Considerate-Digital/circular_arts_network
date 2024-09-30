<?php
/**
 * Renders the shortcodes
 */
class CAN_Shortcodes
{

	function __construct(){
		add_shortcode( 'can_dashboard', array($this, 'render_dashboard') );
		add_shortcode( 'can_categories', array($this, 'render_categories') );
		add_shortcode( 'can_listings', array($this, 'render_listings') );
		add_shortcode( 'can_search_form', array($this, 'render_search_form') );
		add_shortcode( 'can_search_results', array($this, 'render_search_results') );

		add_action( 'wp_ajax_can_search_listing', array($this, 'search_results' ) );
		add_action( 'wp_ajax_nopriv_can_search_listing', array($this, 'search_results' ) );

		add_action( 'wp_ajax_nopriv_can_seller_login', array($this, 'login' ) );
		add_action( 'wp_ajax_nopriv_can_seller_register', array($this, 'register' ) );

		add_action( 'wp_ajax_can_create_listing_frontend', array($this, 'create_listing_frontend' ) );
		add_action( 'wp_ajax_can_update_profile', array($this, 'update_profile' ) );
		add_action( 'wp_ajax_can_delete_listing', array($this, 'delete_listing' ) );
	}

	function render_dashboard($attrs, $content = ''){
		extract( shortcode_atts( array(
			'layout' => 'left-sidebar',
		), $attrs ) );

		can_load_basic_styles();
		wp_enqueue_style('can-dashboard', CAN_URL."/assets/css/dashboard.css");
		wp_enqueue_style('can-archive', CAN_URL."/assets/css/archive.css");
		wp_enqueue_script( 'can-sweetalert', CAN_URL . '/assets/libs/sweetalert/sweetalert2.all.min.js', array( 'jquery' ));

		ob_start();

		if (is_user_logged_in()) {

			wp_enqueue_media();
			wp_enqueue_script( 'can-dashboard', CAN_URL . '/assets/js/dashboard.js' , array('jquery' ));
			wp_localize_script( 'can-dashboard', 'can_dash_vars', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'wait_text' => __( 'Please wait...', 'circular-arts-network' ),
			) );

			$in_theme = get_stylesheet_directory().'/can/shortcodes/dashboard-'.$layout.'.php';
			if (file_exists($in_theme)) {
				include $in_theme;
			} else {
				include CAN_PATH. '/shortcodes/dashboard-'.$layout.'.php';
			}
		} else {

			wp_enqueue_script( 'can-auth', CAN_URL . '/assets/js/auth.js' , array('jquery' ));
			wp_localize_script( 'can-auth', 'can_auth_vars', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'wait_text' => __( 'Please wait...', 'circular-arts-network' ),
				'mismatch_text' => __( 'Passwords did not match!', 'circular-arts-network' ),
				'file_size_error' => __( 'Maximum file size allowed is:', 'circular-arts-network' ),
				'file_format_error' => __( 'Allowed formats are:', 'circular-arts-network' ),
			) );

			if (isset($_GET['can_page']) && $_GET['can_page'] == 'register') {
				$in_theme = get_stylesheet_directory().'/can/shortcodes/register.php';
				if (file_exists($in_theme)) {
					include $in_theme;
				} else {
					include CAN_PATH. '/shortcodes/register.php';
				}
			} else {
				$in_theme = get_stylesheet_directory().'/can/shortcodes/login.php';
				if (file_exists($in_theme)) {
					include $in_theme;
				} else {
					include CAN_PATH. '/shortcodes/login.php';
				}
			}
		}

		return ob_get_clean();
	}

	function render_categories($attrs){

		$attrs = shortcode_atts( array(
			'columns' => 'auto',
			'style' => '1',
			'image_size' => 'thumbnail',
			'hide_empty' => false,
		), $attrs);

		$args = array(
			'taxonomy' => 'can_listing_category',
		);

		if (is_array($attrs)) {
			foreach ($attrs as $key => $value) {
				if ($key != 'columns' && $key != 'style' && $key != 'image_size') {
					$args[$key] = $value;
				}
			}
		}

		$categories = get_terms( $args );
		$col_classes = can_get_column_classes($attrs['columns']);

		can_load_basic_styles();

		wp_enqueue_style('can-category', CAN_URL."/assets/css/category.css");

		ob_start();
		$in_theme = get_stylesheet_directory().'/can/shortcodes/categories/style-1.php';
		if (file_exists($in_theme)) {
			include $in_theme;
		} else {
			include CAN_PATH. '/shortcodes/categories/style-1.php';
		}
		return ob_get_clean();
	}

	function render_category_image($term_id, $image_size){
		$image_id = get_term_meta( $term_id, 'can_category_image', true );
		$icon_class = get_term_meta( $term_id, 'can_category_icon', true );

		if ($image_id != '') {
			echo wp_get_attachment_image( $image_id, $image_size );
		} elseif ($icon_class != '') {
			echo "<i class='bi bi-{$icon_class}'></i>";
		} else {
			echo '';
		}
	}

	function render_listings($attrs){
		//TODO default column setting
		$attributes = shortcode_atts( array(
			'columns' => '4',
			'style' => '1',
			'image_size' => 'large',
			'pagination'  => 'enable',
			'top_bar' => 'enable',
			'masonry' => 'enable',
		), $attrs);

		$args = $this->get_listings_query_args($attrs);
		$columns = can_get_column_classes($attributes['columns']);

		if ($attributes['pagination'] == 'enable') {
			if (is_front_page()) {
				$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
			} else {
				$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
			}

			$args['paged'] = $paged;
		}

		$args = apply_filters( 'can_shortcode_listings_args', $args );

		can_load_basic_styles();
		wp_enqueue_style('can-archive', CAN_URL."/assets/css/archive.css");

		if ($attributes['masonry'] == 'enable') {
			wp_enqueue_script('can-masonry', CAN_URL."/assets/js/trigger-masonry.js", array('jquery','jquery-masonry'));
		}

		$the_query = new WP_Query( $args );
		ob_start();
		$in_theme = get_stylesheet_directory().'/can/shortcodes/listings.php';
		if (file_exists($in_theme)) {
			include $in_theme;
		} else {
			include CAN_PATH. '/shortcodes/listings.php';
		}
		return ob_get_clean();
	}

	function render_search_form($attrs, $content){
		$attrs = shortcode_atts( array(
			'columns' => '',
			'style' => '1',
			'fields' => 'search_field', //,regular_price,purpose,condition',
			'results_selector' => '',
			'results_url' => '',
			'bg_color' => '#f5f5f5',
		), $attrs);

		$searchFields = explode(",", $attrs['fields']);
		$columns = can_get_column_classes($attrs['columns']);

		can_load_basic_styles();
		wp_enqueue_style('can-search', CAN_URL."/assets/css/search-form.css");
		wp_enqueue_style('can-archive', CAN_URL."/assets/css/archive.css");
		wp_enqueue_style('nice-select', CAN_URL."/assets/libs/css/nice-select.css");
		wp_enqueue_script('nice-select', CAN_URL."/assets/libs/js/jquery.nice-select.min.js", array('jquery'));
		wp_enqueue_script( 'can-search', CAN_URL . '/assets/js/search.js' , array('jquery' ));

		$searchvars = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'results_selector' => $attrs['results_selector'],
			'results_url' => $attrs['results_url'],
		);

		wp_localize_script( 'can-search', 'can_search_vars', $searchvars );

		ob_start();

		$in_theme = get_stylesheet_directory().'/can/shortcodes/search/style-1.php';
		if (file_exists($in_theme)) {
			include $in_theme;
		} else {
			include CAN_PATH. '/shortcodes/search/style-1.php';
		}

		return ob_get_clean();
	}

	function render_search_results($attrs, $content = ''){
		extract( shortcode_atts( array(
			'order' 	=> 'ASC',
			'orderby' 	=> 'date',
			'masonry' 	=> 'enable',
		), $attrs ) );

		can_load_basic_styles();
		wp_enqueue_style('can-archive', CAN_URL."/assets/css/archive.css");

		if ($masonry == 'enable') {
			wp_enqueue_script('can-masonry', CAN_URL."/assets/js/trigger-masonry.js", array('jquery','jquery-masonry'));
		}

		ob_start();
		$in_theme = get_stylesheet_directory().'/can/shortcodes/search/results.php';
		if (file_exists($in_theme)) {
			include $in_theme;
		} else {
			include CAN_PATH. '/shortcodes/search/results.php';
		}
		return ob_get_clean();
	}

	function get_listings_query_args($attrs){

		$attrs = shortcode_atts( array(
			'order' 	=> 'ASC',
			'orderby' 	=> 'date',
			'author'  	=> '',
			'tags'  	=> '',
			'categories'  	=> '',
			'filter'  	=> '',
			'lang'  	=> '',
			'orderby_custom'  	=> '',
			'ids'  	=> '',
			'exclude'  	=> '',
			'total'  	=> '9',
			'admin_status'  	=> 'publish',
		), $attrs );

		$args = array(
			'order'       => $attrs['order'],
			'orderby'     => $attrs['orderby'],			
			'post_type'   => 'can_listing',
			'posts_per_page'  => $attrs['total'],
		);

		if ($attrs['ids'] != '') {
			$args['post__in'] = explode(',', $attrs['ids']);
		}

		if ($attrs['lang'] != '') {
			$args['lang'] = $attrs['lang'];
		}

		if ($attrs['admin_status'] != '') {
			$args['post_status'] = explode(",", $attrs['admin_status']);
		}

		if ($attrs['exclude'] != '') {
			$args['post__not_in'] = explode(',', $attrs['exclude']);
		}

		if ($attrs['orderby'] == 'price') {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = 'can_regular_price';
		}

		if ($attrs['orderby_custom'] != '') {
			$args['orderby'] = 'meta_value';
			$args['meta_key'] = 'can_'.$attrs['orderby_custom'];
		}

		if (isset($_GET['sort_by']) && $_GET['sort_by'] != '') {
			$sort_op = explode("-", $_GET['sort_by']);
			$args['order'] = strtoupper($sort_op[1]);
			$args['orderby'] = $sort_op[0];
			if ($sort_op[0] == 'price') {
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = 'can_regular_price';
			}
			if (isset($sort_op[2]) && $sort_op[2] == 'custom') {
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = $sort_op[0];
			}
		}

		if ($attrs['author'] != '') {
			if ($attrs['author'] == 'current' && is_user_logged_in()) {
				$current_user = wp_get_current_user();
				$args['author'] = $current_user->ID;
			} else {
				$args['author'] = $attrs['author'];
			}
		}

		if ($attrs['tags'] != '') {
			$p_tags = array_map('trim', explode(',', $attrs['tags']));
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'can_listing_tag',
					'field'    => 'name',
					'terms'    => $p_tags,
				),
			);
		}

		if ($attrs['categories'] != '') {
			$p_cats = array_map('trim', explode(',', $attrs['categories']));
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'can_listing_category',
					'field'    => 'name',
					'terms'    => $p_cats,
				),
			);
		}

		if ($attrs['filter'] != '') {
			$meta_data = explode(",", $attrs['filter']);
			foreach ($meta_data as $single_meta) {
				$m_k_v = explode("|", $single_meta);
				if (isset($m_k_v[1]) && $m_k_v[1] != '' && strpos($m_k_v[1], '*') == false) {
					if (strpos($m_k_v[1], '!') !== false) {
						$args['meta_query'][] = array(
							array(
								'key'     => 'can_'.trim($m_k_v[0]),
								'value'   => ltrim($m_k_v[1],"!"),
								'compare' => 'NOT LIKE',
							),
						);
					} elseif (strpos($m_k_v[1], '#') !== false) {
						$args['meta_query'][] = array(
							array(
								'key'     => 'can_'.trim($m_k_v[0]),
								'value'   => ltrim($m_k_v[1],"#"),
								'compare' => '=',
							),
						);			        	
					} else {
						$args['meta_query'][] = array(
							array(
								'key'     => 'can_'.trim($m_k_v[0]),
								'value'   => trim($m_k_v[1]),
								'compare' => 'LIKE',
							),
						);
					}
				}
				if (isset($m_k_v[1]) && $m_k_v[1] != '' && strpos($m_k_v[1], '*') != false) {
					$m_k_v_and = explode("*", $m_k_v[1]);

					$meta_query_arr = array();

					foreach ($m_k_v_and as $meta_value) {
						$meta_query_arr[] = array(
							'key'     => 'can_'.trim($m_k_v[0]),
							'value'   => trim($meta_value),
							'compare' => 'LIKE',
						);
					}
					$meta_query_arr['relation'] = 'OR';
					$args['meta_query'][] = $meta_query_arr;
				}

			}
		}

		return $args;
	}

	function search_results(){
		if(isset($_REQUEST) && !empty($_REQUEST)){
			$args = can_get_search_query($_REQUEST);

			$the_query = new WP_Query( $args );
			$target = can_get_option('searched_listings_target', '_blank');

			if ( $the_query->have_posts() ) :

				if (!isset($args['offset'])) { ?>
			<div class="filter-title">
			    <h2>
<?php
					$heading = can_get_option('search_results_title', 'Search Results (%count%)');
			$heading = str_replace('%count%', '<span class="can-results-count">'.$the_query->post_count.'</span>', $heading);
			echo wp_kses( $heading, array('span' => array('class' => array())));
?>
			    </h2>
			</div>
		<?php } ?>
		<div class="row">
		    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<div id="listing-<?php echo get_the_id(); ?>" class="col-sm-12 can-results-box">
			    <?php do_action('can_listing_box', get_the_id(), '1', 'list', $target); ?>
			</div>
		    <?php endwhile; ?>
		</div>
		<?php wp_reset_postdata(); ?>
	    <?php else : ?>
		<div class="can-no-results alert alert-info mt-2" role="alert">
		    <i class="bi bi-info"></i>
		    <span><?php $msg = can_get_option('no_results_message', __( 'Sorry! No Listings Found. Try Searching Again.', 'circular-arts-network' )); echo apply_filters( 'no_results_message',  stripcslashes($msg)); ?></span>
		</div>
<?php endif;
		}

		die(0);
	}

	function login(){
		if (isset($_REQUEST)) {
			if (isset($_REQUEST['g-recaptcha-response'])) {
				if (!$_REQUEST['g-recaptcha-response']) {
					$resp = array('status' => 'error', 'message' => __( 'Please check the captcha form.', 'circular-arts-network' ));
					echo json_encode($resp); exit;
				} else {
					$captcha = sanitize_text_field( $_REQUEST['g-recaptcha-response'] );
					$secretKey = can_get_option('captcha_secret_key');
					$ip = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
					$response = wp_remote_post("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
					$responseKeys = json_decode($response['body'], true);
					if(intval($responseKeys["success"]) !== 1) {
						$resp = array('status' => 'error', 'message' => __( 'There was an error. Please try again after reloading page', 'circular-arts-network' ));
						echo json_encode($resp); exit;
					}
				}
			}        	
			global $user;
			$creds = array();
			$creds['user_login'] = sanitize_email( $_REQUEST['seller_email'] );
			$creds['user_password'] =  $_REQUEST['seller_password'];
			$creds['remember'] = (isset($_REQUEST['rememberme'])) ? true : false;
			$user = wp_signon( $creds, true );

			if ( is_wp_error($user) ) {

				$resp = array(
					'status'    => 'error',
					'message'   => $user->get_error_message(),
				);

				echo json_encode($resp);
			}
			if ( !is_wp_error($user) ) {
				$resp = array(
					'status'    => 'success',
					'message'   => __( 'Successful!', 'circular-arts-network' ),
				);

				wp_set_auth_cookie( $user->ID, true, false );
				wp_set_current_user( $user->ID );
				echo json_encode($resp);
			}

			die(0);
		}
	}

	function register(){

		if (isset($_REQUEST['username'])) {
			$username 	= 	sanitize_text_field( $_REQUEST['username'] );
			$useremail 	= 	sanitize_email( $_REQUEST['seller_email'] );
			$password 	= 	$_REQUEST['seller_password'];


			$resp = array();

			// Checking for Spams
			if (isset($_REQUEST['g-recaptcha-response'])) {
				if (!$_REQUEST['g-recaptcha-response']) {
					$resp = array('status' => 'info', 'message' => __( 'Please check the captcha form.', 'circular-arts-network' ));
					echo json_encode($resp); exit;
				} else {
					$captcha = sanitize_text_field( $_REQUEST['g-recaptcha-response'] );
					$secretKey = can_get_option('captcha_secret_key');
					$ip = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
					$response = wp_remote_post("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
					$responseKeys = json_decode($response['body'], true);
					if(intval($responseKeys["success"]) !== 1) {
						$resp = array('status' => 'error', 'message' => __( 'There was an error. Please try again after reloading page.', 'circular-arts-network' ));
						echo json_encode($resp); exit;
					}
				}
			}


			// Lets Check if username already exists
			if (username_exists( $_REQUEST['username'] ) || email_exists( $_REQUEST['seller_email'] )) {
				$resp = array('status' => 'info', 'message' => __( 'Username or Email already exists', 'circular-arts-network' ));
			} else {
				$sellerData = array(
					'first_name'	=> 		sanitize_text_field( $_REQUEST['first_name'] ),
					'last_name'		=> 		sanitize_text_field( $_REQUEST['last_name'] ),
					'username'		=> 		sanitize_text_field( $_REQUEST['username'] ),
					'useremail'	=> 		sanitize_email( $_REQUEST['seller_email'] ),
					'seller_phone'	=> 		sanitize_text_field( $_REQUEST['seller_phone'] ),
					'seller_password'=> 	$_REQUEST['seller_password'],
					'time'			=> 		current_time( 'mysql' ),
				);

				if (can_get_option('seller_approval', 'manual') == 'auto') {
					$seller_id = wp_create_user( $username, $password, $useremail );

					if ($seller_id) {

						wp_update_user( array( 
							'ID' => $seller_id,
							'role' => 'can_listing_seller',
							'first_name' => sanitize_text_field( $_REQUEST['first_name'] ),
							'last_name' => sanitize_text_field( $_REQUEST['last_name'] ),
						) );

						if(isset($_REQUEST['seller_phone'])){
							update_user_meta( $seller_id, 'seller_phone', sanitize_text_field( $_REQUEST['seller_phone'] ));
						}

						// if image uploaded
						if ( isset($_FILES["can_seller_image"]) ) { 
							require_once( ABSPATH . 'wp-admin/includes/image.php' );
							require_once( ABSPATH . 'wp-admin/includes/file.php' );
							require_once( ABSPATH . 'wp-admin/includes/media.php' );
							$attachment_id = media_handle_upload( 'can_seller_image', 0 );
							if (!is_wp_error($attachment_id)) {
								update_user_meta( $seller_id, 'seller_image', $attachment_id);
							}
						}

						if (can_get_option('auto_login') == 'enable') {
							wp_set_current_user($seller_id);
							wp_set_auth_cookie($seller_id);
						}

						// WPML Language
						if (isset($_REQUEST['wpml_user_email_language'])) {
							update_user_meta( $seller_id, 'icl_admin_language', sanitize_text_field( $_REQUEST['wpml_user_email_language'] ));
						}

						do_action( 'can_new_seller_registered', $sellerData );
						do_action( 'can_new_seller_approved', $sellerData );

						$resp = array('status' => 'success', 'message' => __( 'Registered Successfully, now please login', 'circular-arts-network' ));
					} else {
						$resp = array('status' => 'error', 'message' => __( 'Error, please try later', 'circular-arts-network' ));
					}

				} else {


					$previous_users = get_option( 'can_pending_users' );

					// if image uploaded
					if ( isset($_FILES["can_seller_image"]) ) { 
						require_once( ABSPATH . 'wp-admin/includes/image.php' );
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
						require_once( ABSPATH . 'wp-admin/includes/media.php' );
						$attachment_id = media_handle_upload( 'can_seller_image', 0 );
						if (!is_wp_error($attachment_id)) {
							update_user_meta( $seller_id, 'seller_image', $attachment_id);
							$sellerData['seller_image'] = esc_attr( $attachment_id );
						}
					}

					if ( $previous_users != '' && is_array($previous_users)) {
						foreach ($previous_users as $single_user) {
							if ($single_user['username'] == $sellerData['username'] || $single_user['seller_email'] == $sellerData['seller_email']) {
								$resp = array('status' => 'info', 'message' => __( 'User is already in pending state.', 'circular-arts-network' ));
								echo json_encode($resp);
								exit;
							}
						}
						$previous_users[] = $sellerData;
					} else {
						$previous_users = array($sellerData);
					}

					if (update_option( 'can_pending_users', $previous_users )) {
						do_action( 'can_new_seller_registered', $sellerData );
						$resp = array('status' => 'success', 'message' => __( 'Registered Successfully, please wait until admin approves.', 'circular-arts-network' ));
					} else {
						$resp = array('status' => 'error', 'message' => __( 'Error, please try later', 'circular-arts-network' ));
					}
				}
			}

			echo json_encode($resp);
		}

		die(0);
	}

	function render_dashboard_menu(){
		$menu_items = array(
			'dashboard' => array(
				'title' => __( 'Dashboard', 'circular-arts-network' ),
				'icon' => 'bi bi-pc-display-horizontal',
				'url' => 'dashboard',
			),
			'listings' => array(
				'title' => __( 'My Listings', 'circular-arts-network' ),
				'icon' => 'bi bi-list-task',
				'url' => 'listings',
			),
			'add' => array(
				'title' => __( 'Create Listing', 'circular-arts-network' ),
				'icon' => 'bi bi-plus-circle',
				'url' => 'add',
			),
			'profile' => array(
				'title' => __( 'My Profile', 'circular-arts-network' ),
				'icon' => 'bi bi-person-circle',
				'url' => 'profile',
			),
		);

		$menu_items = apply_filters( 'can_dashboard_menu_items', $menu_items );

		echo '<div class="list-group">';
		foreach ($menu_items as $key => $item) {
			$active = (isset($_GET['can_page']) && $_GET['can_page'] == $item['url']) ? 'active' : '' ;
			$active = (!isset($_GET['can_page']) && $key == 'dashboard') ? 'active' : $active ;
			$url = explode( '?', esc_url_raw( add_query_arg( array() ) ) );
			$no_query_args = $url[0];

			echo "<a href='".esc_url( add_query_arg( 'can_page', $item['url'], $no_query_args) )."' class='list-group-item list-group-item-action {$active} can-menu-".esc_attr( $key )."'><i class='".esc_attr( $item['icon'] )."'></i> ".esc_attr( $item['title'] )."</a>";
		}
		echo '</div>';
	}

	function render_dashboard_page(){
		if (isset($_GET['can_page']) && file_exists(CAN_PATH. '/shortcodes/dashboard/'.$_GET['can_page'].'.php')) {
			include CAN_PATH. '/shortcodes/dashboard/'.$_GET['can_page'].'.php';
		} else {
			include CAN_PATH. '/shortcodes/dashboard/dashboard.php';
		}
	}

	function create_listing_frontend(){


		if (isset($_REQUEST) && $_REQUEST != '') {
			$resp = array(
				'status'    => 'error',
				'message'   => __( 'There is some error', 'circular-arts-network' ),
			);

			$current_user_data = wp_get_current_user();

			// If needs update
			if (isset($_REQUEST['listing_id']) && get_post_field( 'post_author', $_REQUEST['listing_id'] ) == $current_user_data->ID) {
				$status = (isset($_REQUEST['listing_admin_status']) && $_REQUEST['listing_admin_status'] != '') ? $_REQUEST['listing_admin_status'] : get_post_status( $_REQUEST['listing_id'] ) ;
				if (isset($_REQUEST['listing_admin_status']) && $_REQUEST['listing_admin_status'] == 'publish') {
					if($this->listing_can_be_published($_REQUEST['listing_id'])){
						$listing_id = $this->insert_listing_in_db($_REQUEST['listing_id'], $_REQUEST, $current_user_data, 'publish');
					} else {
						$listing_id = $this->insert_listing_in_db($_REQUEST['listing_id'], $_REQUEST, $current_user_data, 'pending');
					}
				} else {
					$listing_id = $this->insert_listing_in_db($_REQUEST['listing_id'], $_REQUEST, $current_user_data, $status);
				}

				$resp = array(
					'status'    => 'success',
					'message'   => __( 'Listing Updated!', 'circular-arts-network' ),
				);

				echo json_encode($resp);

				// Create a new    
			} else {
				if(can_get_option('listing_submission_mode') == 'approve'){
					$listing_id = $this->insert_listing_in_db('', $_REQUEST, $current_user_data, 'pending');
					$resp['status'] = 'success';
					$resp['message'] = __( 'Listing Submitted!', 'circular-arts-network' );
				} else {
					$listing_id = $this->insert_listing_in_db($_REQUEST['listing_id'], $_REQUEST, $current_user_data, 'publish');
					$resp['status'] = 'success';
					$resp['message'] = __( 'Listing Published!', 'circular-arts-network' );
				}

				echo json_encode($resp);
			}

		}

		die();
	}

	function update_profile(){
		if (!empty($_REQUEST)) {
			$current_user_data = wp_get_current_user();
			if ($current_user_data->ID == $_REQUEST['seller_id']) {
				wp_update_user( array( 
					'ID' => $current_user_data->ID,
					'first_name' => sanitize_text_field( $_REQUEST['first_name'] ),
					'last_name' => sanitize_text_field( $_REQUEST['last_name'] ),
					'user_email' => sanitize_email( $_REQUEST['seller_email'] ),
				) );

				if (isset($_REQUEST['seller_image'])) {
					update_user_meta( $current_user_data->ID, 'seller_image', sanitize_text_field( $_REQUEST['seller_image'] ));
				}

				if (isset($_REQUEST['seller_phone'])) {
					update_user_meta( $current_user_data->ID, 'seller_phone', sanitize_text_field( $_REQUEST['seller_phone'] ));
				}

				$resp = array(
					'status'    => 'success',
					'message'   => __( 'Profile Updated!', 'circular-arts-network' ),
				);
				echo json_encode($resp);

			} else {
				$resp = array(
					'status'    => 'error',
					'message'   => __( 'You are not allowed to update', 'circular-arts-network' ),
				);
				echo json_encode($resp);
			}
		}
		die(0);
	}

	function listing_can_be_published($listing_id){
		if (can_get_option('listing_submission_mode') == 'approve' && get_post_status($listing_id) !== 'publish') {
			return false;
		}
		return true;
	}

	function delete_listing(){
		if (isset($_REQUEST['listing_id'])) {
			$current_user_data = wp_get_current_user();
			if (get_post_field( 'post_author', $_REQUEST['listing_id'] ) == $current_user_data->ID || current_user_can( 'manage_options' )) {
				if (can_get_option('attachment_deletion', 'remain') == 'delete') {
					$gallery_images = get_post_meta( $_REQUEST['listing_id'], 'can_gallery_images', true );
					foreach ($gallery_images as $key => $id) {
						wp_delete_attachment( $id, false );
					}
				}
				if (can_get_option('property_deletion', 'delete') == 'trash') {
					wp_trash_post( $_REQUEST['listing_id'] );
				} else {
					wp_delete_post( $_REQUEST['listing_id'], true );
				}
				$resp = array(
					'status'    => 'success',
					'message'   => __( 'Deleted!', 'circular-arts-network' ),
				);
				echo json_encode($resp);
			} else {
				$resp = array(
					'status'    => 'error',
					'message'   => __( 'There is some error, please try again later', 'circular-arts-network' ),
				);
				echo json_encode($resp);
			}
		}
		die(0);
	}

	function insert_listing_in_db($listing_id = '', $data, $current_user_data, $status = 'draft'){
		/*
		 * TODO 
		 * Can't error_log here or it breaks the process
		 */
		$listing_data = array(
			'post_title'    	=> wp_strip_all_tags( $data['listing_title'] ),
			'post_content'  	=> $data['content'],
			'post_author'   	=> $current_user_data->ID,
			'post_type'   	=> 'can_listing',
			'post_status'   	=> $status,
		);

		// if already created
		if ( $listing_id != '') {
			$listing_data['ID'] = $listing_id;
		}

		$listing_id = wp_insert_post( $listing_data );

		if (isset($data['can_data']) && !empty($data['can_data'])) {
			foreach ($data['can_data'] as $key => $value) {
				if (is_array($value)) {
					$value = array_map( 'sanitize_text_field', $value );
					update_post_meta($listing_id, 'can_'.$key, $value);
				} else {
					update_post_meta($listing_id, 'can_'.$key, wp_kses_post( $value ));
				}
			}
		}

		// Saving Gallery Images
		if (isset($data['gallery_images']) && $data['gallery_images'] != '') {
			update_post_meta( $listing_id, 'can_gallery_images', $data['gallery_images'] );
		} else {
			update_post_meta( $listing_id, 'can_gallery_images', '' );
		}

		// Saving Location
		if (isset($data['can_listing_latitude']) && $data['can_listing_latitude'] != '') {
			update_post_meta( $listing_id, 'can_listing_latitude', $data['can_listing_latitude'] );
		}
		if (isset($data['can_listing_longitude']) && $data['can_listing_longitude'] != '') {
			update_post_meta( $listing_id, 'can_listing_longitude', $data['can_listing_longitude'] );
		}

		//TODO save the category -- something is going wroing here
		if (isset($data['can_listing_category']) && $data['can_listing_category'] != '') {
			$category_value = $data['can_listing_category'];
			wp_set_object_terms($listing_id, $category_value, 'can_listing_category', true);
		}

		return $listing_id;
	}
}

new CAN_Shortcodes();
?>
