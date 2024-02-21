jQuery(document).ready(function($) {
	$('.uclwp-section').each(function(index, el) {
		if ($(this).find('.row').length) {
			var sectionContent = $(this).find('.row').text();
			if (sectionContent.trim() == "") {
				$(this).hide();
			}
		}
	});
});