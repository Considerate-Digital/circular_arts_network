jQuery(document).ready(function($) {
	$('.ucl-input-wrap select').niceSelect();

	$('.ucl-search-form').submit(function(event) {
		if(uclwp_search_vars.results_url != ''){
			return;
		}
		event.preventDefault();
		var s_wrap = $(this).closest('.uclwp-bs-wrapper');
		var results_cont = '';
		if (uclwp_search_vars.results_selector != '') {
			selectorTest = uclwp_search_vars.results_selector;
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
		s_wrap.find('.uclwp-loader').show();

	    var formData = $(this).serializeArray();

	    $.post(uclwp_search_vars.ajaxurl, formData, function(resp) {
			s_wrap.find('.uclwp-loader').hide();
	    	results_cont.html(resp);
	    });
	}); 
});