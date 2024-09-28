jQuery(document).ready(function($) {
	$('.can-input-wrap select').niceSelect();

	$('.can-search-form').submit(function(event) {
		if(can_search_vars.results_url != ''){
			return;
		}
		event.preventDefault();
		var s_wrap = $(this).closest('.can-bs-wrapper');
		var results_cont = '';
		if (can_search_vars.results_selector != '') {
			selectorTest = can_search_vars.results_selector;
			if ( selectorTest.indexOf('.') != 0 && selectorTest.indexOf('#') != 0 ){
				if ( $("." + selectorTest).length )
				{
					results_cont = $("." + selectorTest);
				} else if ( $("#" + selectorTest).length ) {
					results_cont = $("#" + selectorTest);
				}
			} else {
				if ( $(selectorTest).length ){
					results_cont = $(selectorTest);
				}
			}
		}

		if ( results_cont == '' || typeof results_cont === "undefined" ){
			results_cont = s_wrap.find('.search-results');
		}
		s_wrap.find('.search-results').html('');
		s_wrap.find('.can-loader').show();

	    var formData = $(this).serializeArray();

	    $.post(can_search_vars.ajaxurl, formData, function(resp) {
			s_wrap.find('.can-loader').hide();
	    	results_cont.html(resp);
	    });
	}); 
});