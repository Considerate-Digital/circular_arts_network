<div class="can-bs-wrapper">
	<div class="can-login-wrap">
		<h2><?php _e( 'Register', 'circular-arts-network' ); ?></h2>

		<form action="#" method="post" class="can-register-form mt-3">
            <div class="form-group can-input-wrapper">
                <label><?php _e( 'First Name', 'circular-arts-network' ); ?></label>
                <input type="text" name="first_name" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper">
                <label><?php _e( 'Last Name', 'circular-arts-network' ); ?></label>
                <input type="text" name="last_name" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper">
                <label><?php _e( 'Username', 'circular-arts-network' ); ?></label>
                <input type="text" name="username" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper">
                <label><?php _e( 'Email', 'circular-arts-network' ); ?></label>
                <input type="email" name="seller_email" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper">
                <label><?php _e( 'Phone', 'circular-arts-network' ); ?></label>
                <input type="text" name="seller_phone" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper">
                <label><?php _e( 'Password', 'circular-arts-network' ); ?></label>
                <input type="password" name="seller_password" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper">
                <label><?php _e( 'Confirm Password', 'circular-arts-network' ); ?></label>
                <input type="password" name="seller_repassword" class="can-text-input" required>
            </div>
            <div class="form-group can-input-wrapper can-upload-picture">
              <label for="sellerImage"><?php _e( 'Profile Picture', 'circular-arts-network' ); ?></label>
              <input class="form-control can-text-input" type="file" accept="image/*" id="can_seller_image" name="can_seller_image">
              <div class="seller-dp-prev"><img src=""></div>
              <div class="can-status mt-2"></div>
              <div class="clearfix"></div>
            </div>
            <?php if (can_get_option('captcha_on_registration') == 'on') { ?>
            	<script src='https://www.google.com/recaptcha/api.js'></script>
            	<div class="form-group">
            		<div class="g-recaptcha mb-2" data-sitekey="<?php echo can_get_option('captcha_site_key'); ?>"></div>
            	</div>
            <?php } ?>
            <div class="form-group">
                <button type="submit" class="can-btn"><?php _e( 'Register Now', 'circular-arts-network' ); ?></button>
            </div>
        </form>
        <p class="text-center mb-0 mt-2">
        	<?php _e( "Already have an account?", 'circular-arts-network' ); ?>
        	<a class="can-register-link" href="<?php echo esc_url( remove_query_arg( 'can_page' ) ); ?>"><?php _e( 'Sign In', 'circular-arts-network' ); ?></a>
        </p>
	</div>
</div>