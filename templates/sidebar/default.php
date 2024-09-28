<div class="can-section">
	<?php if(can_get_option('seller_info', 'enable') == 'enable'){ ?>
	<div class="seller-info">
		<div class="seller-image">
			<?php
			    if(get_user_meta( $author_id, 'seller_image', true ) != '') {
			    	echo wp_get_attachment_image(get_user_meta( $author_id, 'seller_image', true ));
			    } else {
			        echo get_avatar( $author_id , 256 );
			    }
			?>
		</div>
		<h4 class="seller-name">
			<?php
				$user_info = get_userdata($author_id);
				echo can_wpml_translate($user_info->display_name, 'Seller', 'display_name_'.$author_id);
			?>
		</h4>
		<ul class="seller-contact">
			<?php if (get_user_meta( $author_id, 'seller_phone', true ) != '') { ?>
				<li>
					<i class="bi bi-telephone"></i>
					<?php echo esc_attr( get_user_meta( $author_id, 'seller_phone', true ) ); ?>
				</li>
			<?php } ?>
			<li>
				<i class="bi bi-envelope"></i>
				<?php echo esc_attr( $user_info->user_email ); ?>
			</li>
		</ul>
	</div>
	<?php } ?>
	<form class="form-contact contact-seller-form" method="post" role="form" data-toggle="validator" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">
	    <input type="hidden" name="seller_id" value="<?php echo esc_attr( $author_id ); ?>">
	    <input type="hidden" name="action" value="can_contact_seller">
	    <?php if(isset($_GET['listing_id'])){ ?>
	    	<input type="hidden" name="listing_id" value="<?php echo intval($_GET['listing_id']); ?>">
	    <?php } else {
	        global $post;
	        if(isset($post->ID)){ ?>
	            <input type="hidden" name="listing_id" value="<?php echo intval($post->ID); ?>">
	        <?php }
	    } ?>
	    <div class="row">
	    	<div class="col-sm-12">
	    		<h2 class="title-contact-seller">Contact</h2>
	    	</div>
	        <div class="col-sm-12 can-input-wrapper">
	            <label for="client_name"><?php _e( 'Name', 'circular-arts-network' ); ?> *</label>
	            <input name="client_name" id="client_name" type="text" class="can-text-input" required>
	        </div>
	        <div class="col-sm-12 can-input-wrapper">
	        	<label for="client_email"><?php _e( 'Email', 'circular-arts-network' ); ?> *</label>
	            <input type="email" class="can-text-input" name="client_email" id="client_email" required>
	        </div>
	        <div class="col-sm-12 can-input-wrapper">
	        	<label for="client_phone"><?php _e( 'Phone', 'circular-arts-network' ); ?> *</label>
	        	<input type="text" class="can-text-input" name="client_phone" id="client_phone" required>
	        </div>
	        <div class="col-sm-12 can-input-wrapper">
	        	<label for="client_msg"><?php _e( 'Your Message', 'circular-arts-network' ); ?> *</label>
	            <textarea name="client_msg" id="client_msg" class="can-text-input text-form" required></textarea>
	        </div>
			<?php
		        if (can_get_option('gdpr_message') != '') {
		            echo '<div class="col-sm-12 can-input-wrapper"><label><input type="checkbox" required> '.stripcslashes(can_get_option('gdpr_message')).'</label></div>';
		        }
			    if (can_get_option('captcha_on_contact') == 'on') { ?>
			        <script src='https://www.google.com/recaptcha/api.js'></script>
			        <div class="g-recaptcha" style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;" data-sitekey="<?php echo can_get_option('captcha_site_key', '6LcDhUQUAAAAAFAsfyTUPCwDIyXIUqvJiVjim2E9'); ?>"></div>
			        <br>
			    <?php }
			?>
			<div class="col-sm-12 can-sending-email">
				<div role="alert">
				    <i class="bi bi-hourglass-split"></i>
				    <span class="msg"><?php _e( 'Sending Email, Please Wait...', 'circular-arts-network' ); ?></span>
				</div>
			</div>
	        <div class="col-sm-12 can-input-wrapper">
	            <button type="submit" class="can-btn"><span class=""></span> <?php _e( 'SEND MESSAGE', 'circular-arts-network' ); ?></button>
	        </div>
	    </div><!-- /.row -->
	</form><!-- /.form -->
</div>
