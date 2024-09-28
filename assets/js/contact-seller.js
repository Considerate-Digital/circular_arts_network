jQuery(document).ready(function($) {
    $('.contact-seller-form').submit(function(event) {
        event.preventDefault();
        var c_form = $(this);
        c_form.find('.can-sending-email').show();
        var ajaxurl = c_form.data('ajaxurl');
        var data = c_form.serialize();

        $.post(ajaxurl, data, function(resp) {
            // console.log(resp);
            if (resp.status == 'sent') {
                c_form.find('.msg').html(resp.msg);
                c_form.trigger("reset");
            } else {
                c_form.find('.msg').html(resp.msg);
            }
        }, 'json');
    });
});