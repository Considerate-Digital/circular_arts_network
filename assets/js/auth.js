jQuery(document).ready(function($) {
	$('.can-login-form').submit(function(event) {
		event.preventDefault();
		Swal.fire('', circartsnet_auth_vars.wait_text, 'info');
		var loginData = $(this).serialize();

		$.post(circartsnet_auth_vars.ajaxurl, loginData, function(resp) {
			Swal.fire('', resp.message, resp.status);

			if (resp.status == 'success') {
				window.location.reload();
			};

		}, "json");
	});

	$('.can-register-form').submit(function(event) {
		event.preventDefault();

		if ($('input[name="seller_password"]').val() == $('input[name="seller_repassword"]').val()) {
		    Swal.fire('', circartsnet_auth_vars.wait_text, 'info');
		    var registerData = new FormData(this);
		    registerData.append("action", 'circartsnet_seller_register');

		    $.ajax({
		        url: circartsnet_auth_vars.ajaxurl,
		        data: registerData,
		        processData: false,
		        contentType: false,
		        type: 'POST',
		        success: function(resp){
        			var resp = $.parseJSON(resp);
        			Swal.fire('', resp.message, resp.status);
        	    	if (resp.status == 'success') {
        	    		setTimeout(function() {
        	    			window.location.reload();
        	    		}, 2000);
        	    	};
		        }
		    });

		} else {
		    Swal.fire('', circartsnet_auth_vars.mismatch_text, "error");
		}
	});

	$("#circartsnet_seller_image").change(function(){
	    if (this.files && this.files[0]) {
	        var allowedTypes = ['jpg', 'jpeg', 'png'];
	        var allowedSize = 5;
	        var fileSize = ((this.files[0].size/1024)/1024).toFixed(4); // MB
	        if (fileSize <= allowedSize) {

	            console.log($(this).val().split('.').pop().toLowerCase());
	            if ($.inArray($(this).val().split('.').pop().toLowerCase(), allowedTypes) == -1) {
	                var types = allowedTypes.map(function(type){
	                    return "<code>" + type + "</code>";
	                }).join(",");                    
	                $('.can-status').html(circartsnet_auth_vars.file_format_error+' '+types);
	                $('.can-status').show();
	                $('.seller-dp-prev img').attr('src', '');
	                $(this).val('');
	            } else {
	                
	                $('.can-status').hide();
	                $('.can-status').html('');
	                var reader = new FileReader();
	                reader.onload = function (e) {
	                    $('.seller-dp-prev img').attr('src', e.target.result);
	                }

	                reader.readAsDataURL(this.files[0]);
	            }
	        } else{
	            $('.can-status').html(circartsnet_auth_vars.file_size_error+' '+allowedSize+'MB');
	            $('.seller-dp-prev img').attr('src', '');
	            $(this).val('');
	        }
	    }
	});
});
