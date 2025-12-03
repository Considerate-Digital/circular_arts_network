<?php
/**
 * Renders the shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CIRCARTSNET_Shortcodes
{

	function __construct(){
		add_shortcode( 'circartsnet_dashboard', array($this, 'render_dashboard') );
		add_shortcode( 'circartsnet_categories', array($this, 'render_categories') );
		add_shortcode( 'circartsnet_listings', array($this, 'render_listings') );
		add_shortcode( 'circartsnet_search_form', array($this, 'render_search_form') );
		add_shortcode( 'circartsnet_search_results', array($this, 'render_search_results') );

		add_action( 'wp_ajax_circartsnet_search_listing', array($this, 'search_results' ) );
		add_action( 'wp_ajax_nopriv_circartsnet_search_listing', array($this, 'search_results' ) );

		add_action( 'wp_ajax_nopriv_circartsnet_seller_login', array($this, 'login' ) );
		add_action( 'wp_ajax_nopriv_circartsnet_seller_register', array($this, 'register' ) );

		add_action( 'wp_ajax_circartsnet_create_listing_frontend', array($this, 'create_listing_frontend' ) );
		add_action( 'wp_ajax_circartsnet_update_profile', array($this, 'update_profile' ) );
		add_action( 'wp_ajax_circartsnet_delete_listing', array($this, 'delete_listing' ) );
	}

	function render_dashboard($attrs, $content = ''){
		extract( shortcode_atts( array(
			'layout' => 'left-sidebar',
		), $attrs ) );

		circartsnet_load_basic_styles();
		wp_enqueue_style('can-dashboard', CIRCARTSNET_URL."/assets/css/dashboard.css");
		wp_enqueue_style('can-archive', CIRCARTSNET_URL."/assets/css/archive.css");
		wp_enqueue_script( 'can-sweetalert', CIRCARTSNET_URL . '/assets/libs/sweetalert/sweetalert2.all.min.js', array( 'jquery' ));
		/* testing queing recaptcha script here */
		wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js');

		ob_start();

		if (is_user_logged_in()) {

			wp_enqueue_media();
			wp_enqueue_script( 'can-dashboard', CIRCARTSNET_URL . '/assets/js/dashboard.js' , array('jquery' ));
			wp_localize_script( 'can-dashboard', 'circartsnet_dash_vars', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'wait_text' => __( 'Please wait...', 'circular-arts-network' ),
			) );

			$in_theme = get_stylesheet_directory().'/can/shortcodes/dashboard-'.$layout.'.php';
			if (file_exists($in_theme)) {
				include $in_theme;
			} else {
				include CIRCARTSNET_PATH. '/shortcodes/dashboard-'.$layout.'.php';
			}
		} else {

			wp_enqueue_script( 'can-auth', CIRCARTSNET_URL . '/assets/js/auth.js' , array('jquery' ));
			wp_localize_script( 'can-auth', 'circartsnet_auth_vars', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'wait_text' => __( 'Please wait...', 'circular-arts-network' ),
				'mismatch_text' => __( 'Passwords did not match!', 'circular-arts-network' ),
				'file_size_error' => __( 'Maximum file size allowed is:', 'circular-arts-network' ),
				'file_format_error' => __( 'Allowed formats are:', 'circular-arts-network' ),
			) );

			if (isset($_GET['circartsnet_page']) && $_GET['circartsnet_page'] == 'register') {
				$in_theme = get_stylesheet_directory().'/can/shortcodes/register.php';
				if (file_exists($in_theme)) {
					include $in_theme;
				} else {
					include CIRCARTSNET_PATH. '/shortcodes/register.php';
				}
			} else {
				$in_theme = get_stylesheet_directory().'/can/shortcodes/login.php';
				if (file_exists($in_theme)) {
					include $in_theme;
				} else {
					include CIRCARTSNET_PATH. '/shortcodes/login.php';
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
			'taxonomy' => 'circartsnet_listing_category',
		);

		if (is_array($attrs)) {
			foreach ($attrs as $key => $value) {
				if ($key != 'columns' && $key != 'style' && $key != 'image_size') {
					$args[$key] = $value;
				}
			}
		}

		$categories = get_terms( $args );
		$col_classes = circartsnet_get_column_classes($attrs['columns']);

		circartsnet_load_basic_styles();

		wp_enqueue_style('can-category', CIRCARTSNET_URL."/assets/css/category.css");

		ob_start();
		$in_theme = get_stylesheet_directory().'/can/shortcodes/categories/style-1.php';
		if (file_exists($in_theme)) {
			include $in_theme;
		} else {
			include CIRCARTSNET_PATH. '/shortcodes/categories/style-1.php';
		}
		return ob_get_clean();
	}

	function render_category_image($term_id, $image_size){
		$image_id = get_term_meta( $term_id, 'circartsnet_category_image', true );
		$icon_class = get_term_meta( $term_id, 'circartsnet_category_icon', true );

		if ($image_id != '') {
			echo wp_get_attachment_image( $image_id, $image_size );
		} elseif ($icon_class != '') {
			echo esc_html("<i class='bi bi-{$icon_class}'></i>");
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
		$columns = circartsnet_get_column_classes($attributes['columns']);

		if ($attributes['pagination'] == 'enable') {
			if (is_front_page()) {
				$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
			} else {
				$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
			}

			$args['paged'] = $paged;
		}

		$args = apply_filters( 'circartsnet_shortcode_listings_args', $args );

		circartsnet_load_basic_styles();
		wp_enqueue_style('can-archive', CIRCARTSNET_URL."/assets/css/archive.css");

		if ($attributes['masonry'] == 'enable') {
			wp_enqueue_script('can-masonry', CIRCARTSNET_URL."/assets/js/trigger-masonry.js", array('jquery','jquery-masonry'));
		}

		$the_query = new WP_Query( $args );
		ob_start();
		$in_theme = get_stylesheet_directory().'/can/shortcodes/listings.php';
		if (file_exists($in_theme)) {
			include $in_theme;
		} else {
			include CIRCARTSNET_PATH. '/shortcodes/listings.php';
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
		$columns = circartsnet_get_column_classes($attrs['columns']);

		circartsnet_load_basic_styles();
		wp_enqueue_style('can-search', CIRCARTSNET_URL."/assets/css/search-form.css");
		wp_enqueue_style('can-archive', CIRCARTSNET_URL."/assets/css/archive.css");
		wp_enqueue_style('nice-select', CIRCARTSNET_URL."/assets/libs/css/nice-select.css");
		wp_enqueue_script('nice-select', CIRCARTSNET_URL."/assets/libs/js/jquery.nice-select.min.js", array('jquery'));
		wp_enqueue_script( 'can-search', CIRCARTSNET_URL . '/assets/js/search.js' , array('jquery' ));

		$searchvars = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'results_selector' => $attrs['results_selector'],
			'results_url' => $attrs['results_url'],
		);

		wp_localize_script( 'can-search', 'circartsnet_search_vars', $searchvars );

		ob_start();

		$in_theme = get_stylesheet_directory().'/can/shortcodes/search/style-1.php';
		if (file_exists($in_theme)) {
			include $in_theme;
		} else {
			include CIRCARTSNET_PATH. '/shortcodes/search/style-1.php';
		}

		return ob_get_clean();
	}

	function render_search_results($attrs, $content = ''){
		extract( shortcode_atts( array(
			'order' 	=> 'ASC',
			'orderby' 	=> 'date',
			'masonry' 	=> 'enable',
		), $attrs ) );

		circartsnet_load_basic_styles();
		wp_enqueue_style('can-archive', CIRCARTSNET_URL."/assets/css/archive.css");

		if ($masonry == 'enable') {
			wp_enqueue_script('can-masonry', CIRCARTSNET_URL."/assets/js/trigger-masonry.js", array('jquery','jquery-masonry'));
		}

		ob_start();
		$in_theme = get_stylesheet_directory().'/can/shortcodes/search/results.php';
		if (file_exists($in_theme)) {
			include $in_theme;
		} else {
			include CIRCARTSNET_PATH. '/shortcodes/search/results.php';
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
			'post_type'   => 'circartsnet_listing',
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
			$args['meta_key'] = 'circartsnet_regular_price';
		}

		if ($attrs['orderby_custom'] != '') {
			$args['orderby'] = 'meta_value';
			$args['meta_key'] = 'circartsnet_'.$attrs['orderby_custom'];
		}

		if (isset($_GET['sort_by']) && $_GET['sort_by'] != '') {
			$sort_op = explode("-", sanitize_html(wp_unslash($_GET['sort_by'])));
			$args['order'] = strtoupper($sort_op[1]);
			$args['orderby'] = $sort_op[0];
			if ($sort_op[0] == 'price') {
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = 'circartsnet_regular_price';
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
					'taxonomy' => 'circartsnet_listing_tag',
					'field'    => 'name',
					'terms'    => $p_tags,
				),
			);
		}

		if ($attrs['categories'] != '') {
			$p_cats = array_map('trim', explode(',', $attrs['categories']));
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'circartsnet_listing_category',
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
								'key'     => 'circartsnet_'.trim($m_k_v[0]),
								'value'   => ltrim($m_k_v[1],"!"),
								'compare' => 'NOT LIKE',
							),
						);
					} elseif (strpos($m_k_v[1], '#') !== false) {
						$args['meta_query'][] = array(
							array(
								'key'     => 'circartsnet_'.trim($m_k_v[0]),
								'value'   => ltrim($m_k_v[1],"#"),
								'compare' => '=',
							),
						);			        	
					} else {
						$args['meta_query'][] = array(
							array(
								'key'     => 'circartsnet_'.trim($m_k_v[0]),
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
							'key'     => 'circartsnet_'.trim($m_k_v[0]),
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
		$nonce_success = check_ajax_referer( 'search' ); 
		if($nonce_success && isset($_REQUEST) && !empty($_REQUEST)){
			$args = circartsnet_get_search_query($_REQUEST);

			$the_query = new WP_Query( $args );
			$target = circartsnet_get_option('searched_listings_target', '_blank');

			if ( $the_query->have_posts() ) :

				if (!isset($args['offset'])) { ?>
			<div class="filter-title">
			    <h2>
<?php
					$heading = circartsnet_get_option('search_results_title', 'Search Results (%count%)');
			$heading = str_replace('%count%', '<span class="can-results-count">'.$the_query->post_count.'</span>', $heading);
			echo wp_kses( $heading, array('span' => array('class' => array())));
?>
			    </h2>
			</div>
		<?php } ?>
		<div class="row">
		    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<div id="listing-<?php echo esc_html(get_the_id()); ?>" class="col-sm-12 can-results-box">
			    <?php do_action('circartsnet_listing_box', get_the_id(), '1', 'list', $target); ?>
			</div>
		    <?php endwhile; ?>
		</div>
		<?php wp_reset_postdata(); ?>
	    <?php else : ?>
		<div class="can-no-results alert alert-info mt-2" role="alert">
		    <i class="bi bi-info"></i>
		    <span><?php $msg = circartsnet_get_option('no_results_message', esc_html_e( 'Sorry! No Listings Found. Try Searching Again.', 'circular-arts-network' )); echo esc_html(apply_filters( 'no_results_message',  stripcslashes($msg))); ?></span>
		</div>
<?php endif;
		}

		die(0);
	}

	function login(){
		$nonce_success = check_ajax_referer( 'login' ); 
		if ($nonce_success && isset($_REQUEST)) {

			$captcha = isset($_REQUEST['g-recaptcha-response']) ? sanitize_text_field( wp_unslash($_REQUEST['g-recaptcha-response'] )) : false;

			if (circartsnet_get_option('captcha_on_login') == 'on') {
				if (!$captcha) {
						$resp = array('status' => 'error', 'message' => __( 'Please check the captcha form.', 'circular-arts-network' ));
						echo wp_json_encode($resp); exit;
					} else {
						$secretKey = circartsnet_get_option('captcha_secret_key');
						$ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field( wp_unslash($_SERVER['REMOTE_ADDR'] )): '';
						$response = wp_remote_post("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
						$responseKeys = json_decode($response['body'], true);
						if(intval($responseKeys["success"]) !== 1) {
							$resp = array('status' => 'error', 'message' => __( 'There was an error. Please try again after reloading page', 'circular-arts-network' ));
							echo wp_json_encode($resp); exit;
						}
					}
				}
				global $user;
				$creds = array();
				$creds['user_login'] = isset($_REQUEST['seller_email']) ? 
					sanitize_email( wp_unslash($_REQUEST['seller_email'])) : "";

				$creds['user_password'] = isset($_REQUEST['seller_password']) ?  
					sanitize_text_field( wp_unslash($_REQUEST['seller_password'])) : "";
				$creds['remember'] = (isset($_REQUEST['rememberme'])) ? true : false;
				$user = wp_signon( $creds, true );

				if ( is_wp_error($user) ) {

					$resp = array(
						'status'    => 'error',
						'message'   => $user->get_error_message(),
					);

					echo wp_json_encode($resp);
				}
				if ( !is_wp_error($user) ) {
					$resp = array(
						'status'    => 'success',
						'message'   => __( 'Successful!', 'circular-arts-network' ),
					);

					wp_set_auth_cookie( $user->ID, true, false );
					wp_set_current_user( $user->ID );
					echo wp_json_encode($resp);
				}

			}        	
			die(0);
		}

	function register(){

		$nonce_success = check_ajax_referer( 'register' ); 
			$username 	= isset($_REQUEST['username']) ?	sanitize_text_field( 
				wp_unslash($_REQUEST['username'] )): "";
			$useremail 	= 	isset($_REQUEST['seller_email']) ? sanitize_email( 
				wp_unslash($_REQUEST['seller_email']) ) : "";
			$password 	= isset($_REQUEST['seller_password']) ? sanitize_text_field(
				wp_unslash($_REQUEST['seller_password'])): "";

		$captcha = isset($_REQUEST['g-recaptcha-response']) ? sanitize_text_field(
		 	wp_unslash($_REQUEST['g-recaptcha-response']) ): "";

		if ($nonce_success && $username && $useremail && $password) {
			
			$resp = array();

			// Checking for Spams
				if (circartsnet_get_option('captcha_on_login') == 'on') {
					if (!$captcha) {
						$resp = array('status' => 'info', 'message' => __( 'Please check the captcha form.', 'circular-arts-network' ));
						echo wp_json_encode($resp); exit;
					} else {
						$secretKey = circartsnet_get_option('captcha_secret_key');
						$ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field( 
							wp_unslash($_SERVER['REMOTE_ADDR']) ): "";
						$response = wp_remote_post("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
						$responseKeys = json_decode($response['body'], true);
						if(intval($responseKeys["success"]) !== 1) {
							$resp = array('status' => 'error', 'message' => __( 'There was an error. Please try again after reloading page.', 'circular-arts-network' ));
							echo wp_json_encode($resp); exit;
						}
					}
				}


			// Lets Check if username already exists
			if (username_exists( $username ) || email_exists( $useremail )) {
				$resp = array('status' => 'info', 'message' => __( 'Username or Email already exists', 'circular-arts-network' ));
			} else {
				$firstname = isset($_REQUEST['first_name']) ? sanitize_text_field(wp_unslash($_REQUEST['first_name'])) : '';
				$lastname = isset($_REQUEST['last_name']) ? sanitize_text_field(wp_unslash($_REQUEST['last_name'])) : '';
				$username = isset($_REQUEST['username']) ? sanitize_text_field(wp_unslash($_REQUEST['username'])) : '';
				$useremail = isset($_REQUEST['seller_email']) ? sanitize_email(wp_unslash($_REQUEST['seller_email'])) : '';
				$seller_phone = isset($_REQUEST['seller_phone']) ? sanitize_text_field(wp_unslash($_REQUEST['seller_phone'])) : '';
				$seller_password = isset($_REQUEST['seller_password']) ? sanitize_text_field(wp_unslash($_REQUEST['seller_password'])) : ''; 

				// Create the seller data array using the sanitized variables
				$sellerData = array(
						'first_name'     => $firstname,
						'last_name'      => $lastname,
						'username'       => $username,
						'useremail'      => $useremail,
						'seller_phone'   => $seller_phone,
						'seller_password'=> $seller_password, 
						'time'           => current_time('mysql'),
				);

				if (circartsnet_get_option('seller_approval', 'manual') == 'auto') {
					$seller_id = wp_create_user( $username, $password, $useremail );

					if ($seller_id) {

						wp_update_user( array( 
							'ID' => $seller_id,
							'role' => 'circartsnet_listing_seller',
							'first_name' => $firstname,
							'last_name' => $lastname,
						) );

						if(isset($_REQUEST['seller_phone'])){
							update_user_meta( $seller_id, 'seller_phone', $seller_phone);
						}

						// if image uploaded
						if ( isset($_FILES["circartsnet_seller_image"]) ) { 
							require_once( ABSPATH . 'wp-admin/includes/image.php' );
							require_once( ABSPATH . 'wp-admin/includes/file.php' );
							require_once( ABSPATH . 'wp-admin/includes/media.php' );
							$attachment_id = media_handle_upload( 'circartsnet_seller_image', 0 );
							if (!is_wp_error($attachment_id)) {
								update_user_meta( $seller_id, 'seller_image', $attachment_id);
							}
						}

						if (circartsnet_get_option('auto_login') == 'enable') {
							wp_set_current_user($seller_id);
							wp_set_auth_cookie($seller_id);
						}

						// WPML Language
						if (isset($_REQUEST['wpml_user_email_language'])) {
							$wpml_user_email_language = isset($_REQUEST['wpml_user_email_language']) ? sanitize_text_field(wp_unslash($_REQUEST['wpml_user_email_language'])) : '';

							update_user_meta( $seller_id, 'icl_admin_language', $wpml_user_email_language);
						}

						do_action( 'circartsnet_new_seller_registered', $sellerData );
						do_action( 'circartsnet_new_seller_approved', $sellerData );

						$resp = array('status' => 'success', 'message' => __( 'Registered Successfully, now please login', 'circular-arts-network' ));
					} else {
						$resp = array('status' => 'error', 'message' => __( 'Error, please try later', 'circular-arts-network' ));
					}

				} else {


					$previous_users = get_option( 'circartsnet_pending_users' );

					// if image uploaded
					if ( isset($_FILES["circartsnet_seller_image"]) ) { 
						require_once( ABSPATH . 'wp-admin/includes/image.php' );
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
						require_once( ABSPATH . 'wp-admin/includes/media.php' );
						$attachment_id = media_handle_upload( 'circartsnet_seller_image', 0 );
						if (!is_wp_error($attachment_id)) {
							update_user_meta( $seller_id, 'seller_image', $attachment_id);
							$sellerData['seller_image'] = esc_attr( $attachment_id );
						}
					}

					if ( $previous_users != '' && is_array($previous_users)) {
						foreach ($previous_users as $single_user) {
							if ($single_user['username'] == $sellerData['username'] || $single_user['seller_email'] == $sellerData['seller_email']) {
								$resp = array('status' => 'info', 'message' => __( 'User is already in pending state.', 'circular-arts-network' ));
								echo wp_json_encode($resp);
								exit;
							}
						}
						$previous_users[] = $sellerData;
					} else {
						$previous_users = array($sellerData);
					}

					if (update_option( 'circartsnet_pending_users', $previous_users )) {
						do_action( 'circartsnet_new_seller_registered', $sellerData );
						$resp = array('status' => 'success', 'message' => __( 'Registered Successfully, please wait until admin approves.', 'circular-arts-network' ));
					} else {
						$resp = array('status' => 'error', 'message' => __( 'Error, please try later', 'circular-arts-network' ));
					}
				}
			}

			echo wp_json_encode($resp);
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

		$menu_items = apply_filters( 'circartsnet_dashboard_menu_items', $menu_items );

		echo '<div class="list-group">';
		foreach ($menu_items as $key => $item) {
			$active = (isset($_GET['circartsnet_page']) && $_GET['circartsnet_page'] == $item['url']) ? 'active' : '' ;
			$active = (!isset($_GET['circartsnet_page']) && $key == 'dashboard') ? 'active' : $active ;
			$url = explode( '?', esc_url_raw( add_query_arg( array() ) ) );
			$no_query_args = $url[0];
			$nonce_url = wp_nonce_url($url, 'open-page'.$active);
			echo "<a href='". esc_url( add_query_arg( 'circartsnet_page', $item['url'], $no_query_args) )."' class='list-group-item list-group-item-action" . esc_html($active) . "can-menu-".esc_attr( $key )."'><i class='".esc_attr( $item['icon'] )."'></i> ".esc_attr( $item['title'] )."</a>";
		}
		echo '</div>';
	}

	function render_dashboard_page(){
		$circartsnet_page = isset($_GET['circartsnet_page']) ? sanitize_text_field(
			wp_unslash($_GET['circartsnet_page'])) : "";
		if ($circartsnet_page && file_exists(CIRCARTSNET_PATH. '/shortcodes/dashboard/'.$circartsnet_page.'.php')) {
			include CIRCARTSNET_PATH. '/shortcodes/dashboard/'.$circartsnet_page.'.php';
		} else {
			include CIRCARTSNET_PATH. '/shortcodes/dashboard/dashboard.php';
		}
	}

	function create_listing_frontend(){


		if (isset($_REQUEST) && $_REQUEST != '') {
			$resp = array(
				'status'    => 'error',
				'message'   => __( 'There is some error', 'circular-arts-network' ),
			);


			$current_user_data = wp_get_current_user();

			$listing_id = isset($_REQUEST['listing_id']) ? sanitize_text_field(
				wp_unslash($_REQUEST['listing_id'])) : "";

			// If needs update
			if ($listing_id && get_post_field( 'post_author', $listing_id ) == $current_user_data->ID) {
				$status = (isset($_REQUEST['listing_admin_status']) && $_REQUEST['listing_admin_status'] != '') ? sanitize_text_field(
					wp_unslash($_REQUEST['listing_admin_status'])) : get_post_status( $listing_id ) ;
				if ($status == 'publish') {

					$nonce_success = check_ajax_referer( 'listing-updated' ); 

					if($nonce_success && $this->listing_circartsnet_be_published($listing_id)){

						$listing_id = $this->insert_listing_in_db($listing_id, $_REQUEST, $current_user_data, 'publish');
					} else {
						$listing_id = $this->insert_listing_in_db($listing_id, $_REQUEST, $current_user_data, 'pending');
					}
				} else {
					$listing_id = $this->insert_listing_in_db($listing_id, $_REQUEST, $current_user_data, $status);
				}

				$resp = array(
					'status'    => 'success',
					'message'   => __( 'Listing Updated!', 'circular-arts-network' ),
				);

				echo wp_json_encode($resp);

				// Create a new    
			} else {
				$nonce_success = check_ajax_referer( 'listing-added' ); 
				if($nonce_success && circartsnet_get_option('listing_submission_mode') == 'approve'){
					$listing_id = $this->insert_listing_in_db('', $_REQUEST, $current_user_data, 'pending');
					$resp['status'] = 'success';
					$resp['message'] = __( 'Listing Submitted!', 'circular-arts-network' );
				} else {
					$listing_id = $this->insert_listing_in_db($listing_id, $_REQUEST, $current_user_data, 'publish');
					$resp['status'] = 'success';
					$resp['message'] = __( 'Listing Published!', 'circular-arts-network' );
				}

				echo wp_json_encode($resp);
			}

		}

		die();
	}

	function update_profile(){
		$nonce_success = false;
		if (isset($_REQUEST['_wpnonce'])) {
			$nonce_success = wp_verify_nonce( sanitize_text_field((wp_unslash($_REQUEST['_wpnonce'])), 'update-profile' )); 
		}
		if (!$nonce_success) {
				wp_nonce_ays('log-out');
		}
		if (!empty($_REQUEST)) {
			$current_user_data = wp_get_current_user();
			$seller_id = isset($_REQUEST['seller_id']) ? sanitize_text_field(
				wp_unslash($_REQUEST['seller_id'])) : "";
			$firstname = isset($_REQUEST['first_name']) ? sanitize_text_field(wp_unslash($_REQUEST['first_name'])) : '';
			$lastname = isset($_REQUEST['last_name']) ? sanitize_text_field(wp_unslash($_REQUEST['last_name'])) : '';
			$username = isset($_REQUEST['username']) ? sanitize_text_field(wp_unslash($_REQUEST['username'])) : '';
			$useremail = isset($_REQUEST['seller_email']) ? sanitize_email(wp_unslash($_REQUEST['seller_email'])) : '';
			if ($firstname && $lastname &&
				$username &&
				$useremail &&
			 	$seller_id && $current_user_data->ID == $seller_id) {
				wp_update_user( array( 
					'ID' => $current_user_data->ID,
					'first_name' => $firstname,
					'last_name'  => $lastname,
					'user_email' => $useremail,
				) );

				$seller_image = isset($_REQUEST['seller_image']) ? sanitize_email(wp_unslash($_REQUEST['seller_image'])) : '';
				if ($seller_image) {
					update_user_meta( $current_user_data->ID, 'seller_image', $seller_image);
				}

				$seller_phone = isset($_REQUEST['seller_phone']) ? sanitize_email(wp_unslash($_REQUEST['seller_phone'])) : '';
				if ($seller_phone) {
					update_user_meta( $current_user_data->ID, 'seller_phone', $seller_phone);
				}

				$resp = array(
					'status'    => 'success',
					'message'   => __( 'Profile Updated!', 'circular-arts-network' ),
				);
				echo wp_json_encode($resp);

			} else {
				$resp = array(
					'status'    => 'error',
					'message'   => __( 'You are not allowed to update', 'circular-arts-network' ),
				);
				echo wp_json_encode($resp);
			}
		}
		die(0);
	}

	function listing_circartsnet_be_published($listing_id){
		if (circartsnet_get_option('listing_submission_mode') == 'approve' && get_post_status($listing_id) !== 'publish') {
			return false;
		}
		return true;
	}

	function delete_listing(){
		$nonce_success = false;
		if (isset($_REQUEST['_wpnonce'])) {
			$nonce_success = wp_verify_nonce( sanitize_text_field((wp_unslash($_REQUEST['_wpnonce']))), 'delete-listing' ); 
		} else if ($nonce_success == false) {
			$nonce_success = check_ajax_referer('delete-listing');
		}
		if (!$nonce_success) {
				wp_nonce_ays('log-out');
		}
		$listing_id = isset($_REQUEST['listing_id']) ? sanitize_text_field(
			wp_unslash($_REQUEST['listing_id'])) : "";
		if ($listing_id) {
			$current_user_data = wp_get_current_user();
			if (get_post_field( 'post_author', $listing_id) == $current_user_data->ID || current_user_can( 'manage_options' )) {
				if (circartsnet_get_option('attachment_deletion', 'remain') == 'delete') {
					$gallery_images = get_post_meta( $listing_id, 'circartsnet_gallery_images', true );
					foreach ($gallery_images as $key => $id) {
						wp_delete_attachment( $id, false );
					}
				}
				if (circartsnet_get_option('property_deletion', 'delete') == 'trash') {
					wp_trash_post( $listing_id );
				} else {
					wp_delete_post( $listing_id, true );
				}
				$resp = array(
					'status'    => 'success',
					'message'   => __( 'Deleted!', 'circular-arts-network' ),
				);
				echo wp_json_encode($resp);
			} else {
				$resp = array(
					'status'    => 'error',
					'message'   => __( 'There is some error, please try again later', 'circular-arts-network' ),
				);
				echo wp_json_encode($resp);
			}
		}
		die(0);
	}
function sanitize_request_data($data) {
		return $data;
    if (!is_array($data) || empty($data)) {
        return array();
    }
    
    $sanitized = array();
    
    foreach ($data as $key => $value) {
        // Skip if key or value is not set
        if (!isset($key) || !isset($value)) {
            continue;
        }
        
        // Sanitize the key itself
        $clean_key = sanitize_key($key);
        
        // Handle arrays recursively
        if (is_array($value)) {
            $sanitized[$clean_key] = sanitize_request_data($value);
            continue;
        }
        
        // Convert to string and unslash
        $value = wp_unslash($value);
        // Apply specific sanitization based on field name patterns
        if (preg_match('/(email|user_email|seller_email)$/i', $key)) {
            // Email fields
            $sanitized[$clean_key] = sanitize_email($value);
            
        } elseif (preg_match('/(url|website|link)$/i', $key)) {
            // URL fields
            $sanitized[$clean_key] = esc_url_raw($value);
            
        } elseif (preg_match('/(phone|tel|mobile)$/i', $key)) {
            // Phone numbers - keep numbers, spaces, dashes, parentheses, plus
            $sanitized[$clean_key] = preg_replace('/[^0-9\s\-\(\)\+]/', '', $value);
            
        } elseif (preg_match('/(password|pass|pwd)$/i', $key)) {
            // Passwords - don't sanitize, but ensure it's a string
            $sanitized[$clean_key] = (string) $value;
            
        } elseif (preg_match('/(description|content|message|comment|bio|about)$/i', $key)) {
            // Long text content - allow some HTML but sanitize
            $sanitized[$clean_key] = wp_kses_post($value);
            
        } elseif (preg_match('/(id|ID|_id)$/i', $key) || is_numeric($value)) {
            // IDs and numeric values
            $sanitized[$clean_key] = absint($value);
            
        } elseif (preg_match('/(date|time)$/i', $key)) {
            // Date/time fields
            $sanitized[$clean_key] = sanitize_text_field($value);
            
        } elseif (preg_match('/(slug|username|user_login)$/i', $key)) {
            // Slugs and usernames
            $sanitized[$clean_key] = sanitize_user($value);
            
        } elseif (preg_match('/(key|token|hash|nonce)$/i', $key)) {
            // Keys, tokens, hashes - alphanumeric only
            $sanitized[$clean_key] = preg_replace('/[^a-zA-Z0-9]/', '', $value);
            
        } else {
            // Default: regular text field
            $sanitized[$clean_key] = sanitize_text_field($value);
        }
    }
    
    return $sanitized;
}

	function insert_listing_in_db($listing_id = '', $data, $current_user_data, $status = 'draft'){
		/*
		 * TODO 
		 * Can't error_log here or it breaks the process
		 */
		// TODO not working yet
		//$data = sanitize_request_data($data);
		$listing_data = array(
			'post_title'    	=> wp_strip_all_tags( $data['listing_title'] ),
			'post_content'  	=> $data['content'],
			'post_author'   	=> $current_user_data->ID,
			'post_type'   	=> 'circartsnet_listing',
			'post_status'   	=> $status,
		);

		// if already created
		if ( $listing_id != '') {
			$listing_data['ID'] = $listing_id;
		}

		$listing_id = wp_insert_post( $listing_data );

		if (isset($data['circartsnet_data']) && !empty($data['circartsnet_data'])) {
			foreach ($data['circartsnet_data'] as $key => $value) {
				if (is_array($value)) {
					$value = array_map( 'sanitize_text_field', $value );
					update_post_meta($listing_id, 'circartsnet_'.$key, $value);
				} else {
					update_post_meta($listing_id, 'circartsnet_'.$key, wp_kses_post( $value ));
				}
			}
		}

		// Saving Gallery Images
		if (isset($data['gallery_images']) && $data['gallery_images'] != '') {
			update_post_meta( $listing_id, 'circartsnet_gallery_images', $data['gallery_images'] );
		} else {
			update_post_meta( $listing_id, 'circartsnet_gallery_images', '' );
		}

		// Saving Location
		if (isset($data['circartsnet_listing_latitude']) && $data['circartsnet_listing_latitude'] != '') {
			update_post_meta( $listing_id, 'circartsnet_listing_latitude', $data['circartsnet_listing_latitude'] );
		}
		if (isset($data['circartsnet_listing_longitude']) && $data['circartsnet_listing_longitude'] != '') {
			update_post_meta( $listing_id, 'circartsnet_listing_longitude', $data['circartsnet_listing_longitude'] );
		}

		if (isset($data['circartsnet_listing_category']) && $data['circartsnet_listing_category'] != '') {
			$category_value = $data['circartsnet_listing_category'];
			wp_set_object_terms($listing_id, $category_value, 'circartsnet_listing_category', true);
		}

		return $listing_id;
	}
}

new CIRCARTSNET_Shortcodes();
?>
