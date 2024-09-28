jQuery(document).ready(function($) {
	$('.deny-user').click(function(event) {
		event.preventDefault();
		$(this).attr('disabled', 'disabled');
		var data = {
			userindex: $(this).data('userindex'),
			action: 'can_deny_seller',
		}

		$.post(ajaxurl, data, function(resp) {
			Swal.fire("Denied!", "Seller is denied.", "success");
			window.location.reload();
		});
	});
	$('.approve-user').click(function(event) {
		event.preventDefault();
		$(this).attr('disabled', 'disabled');
		var data = {
			userindex: $(this).data('userindex'),
			action: 'can_approve_seller',
		}

		$.post(ajaxurl, data, function(resp) {
			Swal.fire("Approved!", "Seller is approved.", "success");
			window.location.reload();
		});
	});
});