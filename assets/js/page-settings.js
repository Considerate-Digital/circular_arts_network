jQuery(document).ready(function($) {
    
    $('.wcp-progress').hide();
    var icons = {
        header: "dashicons dashicons-plus",
        activeHeader: "dashicons dashicons-minus"
    }

    // tabs relates
    $("#ucl-settings-form .panel-settings").hide().first().show();
    $(".wcp-tabs-menu a:first").addClass("active");
    $(".wcp-tabs-menu a").on('click', function (e) {
        e.preventDefault();
        $(this).addClass("active").siblings().removeClass("active");
        $($(this).attr('href')).show().siblings('.panel-settings').hide();
    });
    var hash = $.trim( window.location.hash );
    if (hash) $('.wcp-tabs-menu a[href$="'+hash+'"]').trigger('click');


	$('#ucl-settings-form').submit(function(event) {
		event.preventDefault();
        $('.wcp-progress').show();
        var data = $(this).serialize();

        $.post(ajaxurl, data, function(resp) {
            $('.wcp-progress').hide();
            Swal.fire(resp.title, resp.message, resp.status);
		}, 'json');
	});

    $('.colorpicker').wpColorPicker();

    // Media Uploader
    var ucl_icon_uploader;
     
    jQuery('.uclwp-bs-wrapper').on('click', '.upload_image_button', function( event ){
     
        event.preventDefault();

        var this_widget = jQuery(this).closest('.input-group');
     
        // Create the media frame.
        ucl_icon_uploader = wp.media.frames.ucl_icon_uploader = wp.media({
          title: jQuery( this ).data( 'title' ),
          button: {
            text: jQuery( this ).data( 'btntext' ),
          },
          multiple: false,
        });
     
        // When an image is selected, run a callback.
        ucl_icon_uploader.on( 'select', function() {
          // We set multiple to false so only get one image from the uploader
          attachment = ucl_icon_uploader.state().get('selection').first().toJSON();
            jQuery(this_widget).find('.image-url').val(attachment.url);
        });
     
        // Finally, open the modal
        ucl_icon_uploader.open();
    });

    $('[data-cond-option]').conditionize();
});