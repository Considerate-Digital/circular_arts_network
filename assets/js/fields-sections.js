jQuery(document).ready(function($) {

	$(document).on('click', '.can-create-field-section', function(event) {
		event.preventDefault();
		/* Act on the event */
		var panel = $('#field-sections-wrap .card').last().clone(true);
		
		$(panel).find('input,select').val('');
		$(panel).find('.card-header b').html('New Section');
		$(panel).find('.card-header span.key').html('');
		$(panel).find('.inside-contents').show();
		(panel).appendTo('#field-sections-wrap');
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
        $('#field-sections-wrap .card:last-child').effect("highlight", {color: '#0FF700'}, 2000);
	});
	
    $('#field-sections-wrap').on('keyup', 'input.section_title', function() {
        var input_val = $(this).val();
        var parent = $(this).closest('.card');
        parent.find('.card-header b').text(input_val+' - ');
    });
   
    $(document).on('click', '.can-save-field-section', function(event) {
    	event.preventDefault();
    	/* Act on the event */
    	Swal.fire('Please Wait', 'Sections Saving...', 'info');
        
    	var sections_data = [];
    	$('#field-sections-wrap .card').each(function(index, penal) {
    		/* iterate through array or object */

			var title = $(penal).find('.section_title').val();
			var key = $(penal).find('.section_key').val();
			var icon = $(penal).find('.section_icon').val();
			var accessibility = $(penal).find('.section_accessibility').val();

			var section = {'title': title, 'key':key , 'icon':icon , 'accessibility':accessibility }
			// console.log(section);
			sections_data.push(section);
    	});
    	var data = {
    		'action': 'can_save_field_sections',
    		'sections' : sections_data
    	}
    	$.post(ajaxurl, data, function(resp) {
    		Swal.fire(resp.title, resp.message, resp.status);
    	});
    });

	$('#field-sections-wrap').on('click', '.remove-field', function(event) {
        event.preventDefault();
        var field_title = $(this).closest('.card-header').find('b').text().replace(' - ', '');
        Swal.fire({
          title: "Delete "+field_title+" field?",
          text: "Once deleted, you will not be able to recover this section!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $(this).closest('.card').remove();
          }
        });
    });

	$("#field-sections-wrap")
     .sortable({
        axis: "y",
        revert : true,
        handle: ".trigger-sort",
        placeholder: "ui-state-highlight",
        start: function( event, ui ){
            
        },
        stop: function( event, ui ) {
            
        }
    });

    $('#field-sections-wrap').on('click', '.trigger-toggle', function(event) {
        event.preventDefault();
        var toggle_btn = $(this);
        if (toggle_btn.find('i').hasClass('bi-arrows-expand')) {
            toggle_btn.find('i').removeClass('bi-arrows-expand');
            toggle_btn.find('i').addClass('bi-arrows-collapse');
            $(this).closest('.card').find('.inside-contents').show();
        } else {
            toggle_btn.find('i').removeClass('bi-arrows-collapse');
            toggle_btn.find('i').addClass('bi-arrows-expand');
            $(this).closest('.card').find('.inside-contents').hide();
        }
    });

    $('#field-sections-wrap .card').find('.inside-contents').hide();

    $('#field-sections-wrap').on('blur', 'input.section_title', function() {
        if ($(this).closest('.inside-contents').find('.section_key').val() == '') {
            var data_name = $(this).val().replace(/[^a-z0-9\s]/gi, '').replace(/[-\s]/g, '_');
            $(this).closest('.inside-contents').find('.section_key').val(data_name.toLowerCase());
        }
    });

    $(document).on('click', '.can-reset-field-section', function(event) {
        event.preventDefault();
        Swal.fire({
          title: "Are you sure?",
          text: "Once reset, you will not be able to recover newly created sections!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, reset!'
        }).then((result) => {
          if (result.isConfirmed) {
            var data = {
                action: 'can_save_field_sections',
                reset: 'yes'
            }
            $.post(ajaxurl, data, function(resp) {
                Swal.fire( resp.title, resp.message, resp.status );
                setTimeout(function() {
                    window.location.reload();
                }, 500);
            });
          }
        });

    });
});