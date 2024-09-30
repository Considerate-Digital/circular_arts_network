jQuery(document).ready(function($) {
	
	jQuery('.can-listing-form').submit(function(event){
	    event.preventDefault();
	    Swal.fire('', can_dash_vars.wait_text, 'info');
	    
	    if (jQuery("#wp-can-description-wrap").hasClass("tmce-active")){
	        content = tinyMCE.get('can-description').getContent();
	    } else{
	        content = $('#can-description').val();
	    }        

	    var data = $(this).serialize()+"&content="+encodeURIComponent(content);

	    $.post(can_dash_vars.ajaxurl, data , function(resp) {
	        Swal.fire('', resp.message, resp.status);
	        setTimeout(function() {
	        	window.location = jQuery('.can-menu-listings').attr('href');
	        }, 1000);
	    }, "json");
	});

	jQuery('.my-listings').on('click', '.delete-listing', function(event) {
		event.preventDefault();
		var listing_title = $(this).closest('tr').find('.listing-title').text();
		var listing_id = $(this).data('pid');
		Swal.fire({
		  title: "Delete "+listing_title+" ?",
		  text: "Once deleted, you will not be able to recover this listing!",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#d33',
		  cancelButtonColor: '#3085d6',
		  confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		  if (result.isConfirmed) {
		  	var data = {
		  		action: 'can_delete_listing',
		  		listing_id: listing_id,
		  	}
		  	$.post(can_dash_vars.ajaxurl, data, function(resp) {
		  		Swal.fire('', resp.message, resp.status);
		  		window.location.reload();
		  	}, "json");
		  }
		});
	});


	jQuery('.can-update-profile').submit(function(event){
	    event.preventDefault();
	    Swal.fire('', can_dash_vars.wait_text, 'info');

	    var data = $(this).serialize();

	    $.post(can_dash_vars.ajaxurl, data , function(resp) {
	        Swal.fire('', resp.message, resp.status);
	        setTimeout(function() {
	        	window.location.reload();
	        }, 1000);
	    }, "json");
	});

	var can_profile_picture;
	 
	jQuery('.can-pic-wrap').on('click', '.can-upload-btn', function( event ){
	 
	    event.preventDefault();
	 
	    // Create the media frame.
	    can_profile_picture = wp.media.frames.can_profile_picture = wp.media({
	      title: jQuery( this ).data( 'title' ),
	      button: {
	        text: jQuery( this ).data( 'btntext' ),
	      },
	      library: {
	            type: [ 'image' ]
	      },
	      multiple: true
	    });
	 
	    // When an image is selected, run a callback.
	    can_profile_picture.on( 'select', function() {
	        // We set multiple to false so only get one image from the uploader
	        var selection = can_profile_picture.state().get('selection');
	        selection.map( function( attachment ) {
	            attachment = attachment.toJSON();
	            jQuery('.can-pic-wrap').find('.seller_image').val(attachment.id);
	            jQuery('.can-pic-wrap').find('.can-pp').html("<img src='"+attachment.url+"' >");
	        });  
	    });
	 
	    // Finally, open the modal
	    can_profile_picture.open();
	});
});