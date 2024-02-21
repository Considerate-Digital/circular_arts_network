<div class="uclwp-bs-wrapper">
	<div class="ucl-login-wrap">
		<h2><?php _e( 'Login', 'ultimate-classified-listings' ); ?></h2>

		<form action="#" method="post" class="ucl-login-form mt-3">
            <input type="hidden" value="uclwp_seller_login" name="action">  
            <div class="form-group ucl-input-wrapper">
                <label><?php _e( 'Email', 'ultimate-classified-listings' ); ?></label>
                <input type="email" name="seller_email" class="ucl-text-input" required>
            </div>
            <div class="form-group ucl-input-wrapper">
                <label><?php _e( 'Password', 'ultimate-classified-listings' ); ?></label>
                <input type="password" name="seller_password" class="ucl-text-input" required>
            </div>
            <div class="form-group">
                <div class="ucl-lost-pass"><a href="<?php echo wp_lostpassword_url(); ?>"><?php _e( 'Forgot Password?', 'ultimate-classified-listings' ); ?></a></div>
            </div>
            <?php if (uclwp_get_option('captcha_on_login') == 'on') { ?>
            	<script src="https://www.google.com/recaptcha/api.js"></script>
            	<div class="form-group">
            		<div class="g-recaptcha mb-2" data-sitekey="<?php echo uclwp_get_option('captcha_site_key'); ?>"></div>
            	</div>
            <?php } ?>
            <div class="form-group message-btn">
                <button type="submit" class="ucl-btn"><?php _e( 'Login Now', 'ultimate-classified-listings' ); ?></button>
            </div>
        </form>
        <p class="text-center mb-0 mt-2">
        	<?php _e( "Don't have an account?", 'ultimate-classified-listings' ); ?>
        	<a class="ucl-register-link" href="<?php echo esc_url( add_query_arg( 'ucl_page', 'register') ); ?>"><?php _e( 'Register Now', 'ultimate-classified-listings' ); ?></a>
        </p>
	</div>
</div>