<div class="can-bs-wrapper">
	<div class="can-login-wrap">
		<h2><?php _e( 'Login', 'circular-arts-network' ); ?></h2>

		<form action="#" method="post" class="can-login-form mt-3">
            <input type="hidden" value="can_seller_login" name="action">  
            <div class="form-group can-input-wrapper">
                <label><?php _e( 'Email', 'circular-arts-network' ); ?></label>
                <input type="email" name="seller_email" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper">
                <label><?php _e( 'Password', 'circular-arts-network' ); ?></label>
                <input type="password" name="seller_password" class="can-text-input" required>
            </div>
            <div class="form-group">
                <div class="can-lost-pass"><a href="<?php echo wp_lostpassword_url(); ?>"><?php _e( 'Forgot Password?', 'circular-arts-network' ); ?></a></div>
            </div>
            <?php if (can_get_option('captcha_on_login') == 'on') { ?>
            	<script src="https://www.google.com/recaptcha/api.js"></script>
            	<div class="form-group">
            		<div class="g-recaptcha mb-2" data-sitekey="<?php echo can_get_option('captcha_site_key'); ?>"></div>
            	</div>
            <?php } ?>
            <div class="form-group message-btn">
                <button type="submit" class="can-btn"><?php _e( 'Login Now', 'circular-arts-network' ); ?></button>
            </div>
        </form>
        <p class="text-center mb-0 mt-2">
        	<?php _e( "Don't have an account?", 'circular-arts-network' ); ?>
        	<a class="can-register-link" href="<?php echo esc_url( add_query_arg( 'can_page', 'register') ); ?>"><?php _e( 'Register Now', 'circular-arts-network' ); ?></a>
        </p>
	</div>
</div>