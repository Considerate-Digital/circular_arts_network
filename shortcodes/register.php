<div class="uclwp-bs-wrapper">
	<div class="ucl-login-wrap">
		<h2><?php _e( 'Register', 'ultimate-classified-listings' ); ?></h2>

		<form action="#" method="post" class="ucl-register-form mt-3">
            <div class="form-group ucl-input-wrapper">
                <label><?php _e( 'First Name', 'ultimate-classified-listings' ); ?></label>
                <input type="text" name="first_name" class="ucl-text-input" required>
            </div>
            <div class="form-group ucl-input-wrapper">
                <label><?php _e( 'Last Name', 'ultimate-classified-listings' ); ?></label>
                <input type="text" name="last_name" class="ucl-text-input" required>
            </div>
            <div class="form-group ucl-input-wrapper">
                <label><?php _e( 'Username', 'ultimate-classified-listings' ); ?></label>
                <input type="text" name="username" class="ucl-text-input" required>
            </div>
            <div class="form-group ucl-input-wrapper">
                <label><?php _e( 'Email', 'ultimate-classified-listings' ); ?></label>
                <input type="email" name="seller_email" class="ucl-text-input" required>
            </div>
            <div class="form-group ucl-input-wrapper">
                <label><?php _e( 'Phone', 'ultimate-classified-listings' ); ?></label>
                <input type="text" name="seller_phone" class="ucl-text-input" required>
            </div>
            <div class="form-group ucl-input-wrapper">
                <label><?php _e( 'Password', 'ultimate-classified-listings' ); ?></label>
                <input type="password" name="seller_password" class="ucl-text-input" required>
            </div>
            <div class="form-group ucl-input-wrapper">
                <label><?php _e( 'Confirm Password', 'ultimate-classified-listings' ); ?></label>
                <input type="password" name="seller_repassword" class="ucl-text-input" required>
            </div>
            <div class="form-group ucl-input-wrapper ucl-upload-picture">
              <label for="sellerImage"><?php _e( 'Profile Picture', 'ultimate-classified-listings' ); ?></label>
              <input class="form-control ucl-text-input" type="file" accept="image/*" id="uclwp_seller_image" name="uclwp_seller_image">
              <div class="seller-dp-prev"><img src=""></div>
              <div class="ucl-status mt-2"></div>
              <div class="clearfix"></div>
            </div>
            <?php if (uclwp_get_option('captcha_on_registration') == 'on') { ?>
            	<script src='https://www.google.com/recaptcha/api.js'></script>
            	<div class="form-group">
            		<div class="g-recaptcha mb-2" data-sitekey="<?php echo uclwp_get_option('captcha_site_key'); ?>"></div>
            	</div>
            <?php } ?>
            <div class="form-group">
                <button type="submit" class="ucl-btn"><?php _e( 'Register Now', 'ultimate-classified-listings' ); ?></button>
            </div>
        </form>
        <p class="text-center mb-0 mt-2">
        	<?php _e( "Already have an account?", 'ultimate-classified-listings' ); ?>
        	<a class="ucl-register-link" href="<?php echo esc_url( remove_query_arg( 'ucl_page' ) ); ?>"><?php _e( 'Sign In', 'ultimate-classified-listings' ); ?></a>
        </p>
	</div>
</div>