<?php 

  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="can-bs-wrapper">
	<div class="can-login-wrap">
		<h2><?php esc_html_e( 'Register', 'circular-arts-network' ); ?></h2>

		<form action="#" method="post" class="can-register-form mt-3">
            <div class="form-group can-input-wrapper">
                <label><?php esc_html_e( 'First Name', 'circular-arts-network' ); ?></label>
                <input type="text" name="first_name" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper">
                <label><?php esc_html_e( 'Last Name', 'circular-arts-network' ); ?></label>
                <input type="text" name="last_name" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper">
                <label><?php esc_html_e( 'Username', 'circular-arts-network' ); ?></label>
                <input type="text" name="username" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper">
                <label><?php esc_html_e( 'Email', 'circular-arts-network' ); ?></label>
                <input type="email" name="seller_email" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper">
                <label><?php esc_html_e( 'Phone', 'circular-arts-network' ); ?></label>
                <input type="text" name="seller_phone" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper">
                <label><?php esc_html_e( 'Password', 'circular-arts-network' ); ?></label>
                <input type="password" name="seller_password" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper">
                <label><?php esc_html_e( 'Confirm Password', 'circular-arts-network' ); ?></label>
                <input type="password" name="seller_repassword" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper can-upload-picture">
              <label for="sellerImage"><?php esc_html_e( 'Profile Picture', 'circular-arts-network' ); ?></label>
              <input class="form-control can-text-input" type="file" accept="image/*" id="circartsnet_seller_image" name="circartsnet_seller_image">
              <div class="seller-dp-prev"><img src=""></div>
              <div class="can-status mt-2"></div>
              <div class="clearfix"></div>
            </div>
            <?php if (circartsnet_get_option('captcha_on_registration') == 'on') { ?>
            <!--recaptcha script moved -->
            	<div class="form-group">
            		<div class="g-recaptcha mb-2" data-sitekey="<?php echo esc_html(circartsnet_get_option('captcha_site_key')); ?>"></div>
            	</div>
            <?php } ?>
            <div class="form-group">
                <button type="submit" class="can-btn"><?php esc_html_e( 'Register Now', 'circular-arts-network' ); ?></button>
            </div>
          <?php wp_nonce_field( 'register'); ?>
        </form>
        <p class="text-center mb-0 mt-2">
        	<?php esc_html_e( "Already have an account?", 'circular-arts-network' ); ?>
        	<a class="can-register-link" href="<?php echo esc_url( remove_query_arg( 'circartsnet_page' ) ); ?>"><?php esc_html_e( 'Sign In', 'circular-arts-network' ); ?></a>
        </p>
	</div>
</div>
