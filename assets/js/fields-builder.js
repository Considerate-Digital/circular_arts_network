jQuery(document).ready(function($) {

    $('.hard-coded-list .trigger-sort').removeClass('btn btn-default');

    var uclIconPicker = $('.form-meta-setting .can-iconpicker').fontIconPicker();

    var fields_panel_width = $('.hard-coded-list .card:first-child').width();
    var settings_panel_width = $('.form-meta-setting .card:first-child').width();


    $(".hard-coded-list .card").draggable({
        connectToSortable : ".form-meta-setting",
        helper : "clone",
        start : function(event, ui) {
                ui.helper.css('max-width', fields_panel_width);
             },
        revert : "invalid",
        stop : function(event, ui) {
            $('.form-meta-setting').find('.card').removeClass('ui-draggable ui-draggable-handle').css({
                width: 'auto',
                height: 'auto'
            });
            ui.helper.find('.trigger-sort').addClass('btn btn-default');
            ui.helper.find('.bi-arrows-expand').addClass('bi-arrows-collapse').removeClass('bi-arrows-expand');
            setTimeout(function() {
                ui.helper.find('.inside-contents').show();
                ui.helper.find('.can-iconpicker').fontIconPicker();
            }, 500);
        }
    });

    $(".form-meta-setting")
    .sortable({
        axis: "y",
        revert : true,
        handle: ".trigger-sort",
        placeholder: "ui-state-highlight",
        start: function( event, ui ){
            ui.helper.css('max-width', settings_panel_width);
        },
        stop: function( event, ui ) {
            ui.item.children( ".card-header" ).triggerHandler( "focusout" );
        }
    });

    $('.form-meta-setting').on('click', '.trigger-toggle', function(event) {
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

    $('.form-meta-setting').on('click', '.remove-field', function(event) {
        event.preventDefault();
        var field_title = $(this).closest('.card-header').find('b').text().replace(' - ', '');
        Swal.fire({
          title: "Delete "+field_title+" field?",
          text: "Once deleted, you will not be able to recover this field!",
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
    
    $('body').on('click', '.can-save-settings',function(e) {
        e.preventDefault();
        Swal.fire('Please Wait', 'Saving settings...', 'info');
        var ListData = [];
        $('.form-meta-setting .card').each(function(index, el) {
            var dataType = $(this).data('type');
            var wrap_panel = $(this);

            if(dataType == 'select' || dataType == 'checkboxes' ){

                var singleField = {
                    key: wrap_panel.find('.field_key').val(),
                    type: dataType,
                    tab: wrap_panel.find('.field_tab').val(),
                    default: wrap_panel.find('.field_default').val(),
                    title: wrap_panel.find('.field_title').val(),
                    options: wrap_panel.find('.field_options').val(),
                    help: wrap_panel.find('.field_help').val(),
                    accessibility: wrap_panel.find('.field_accessibility').val(),
                    required: wrap_panel.find('.field_required').is(':checked') ? true : false,
                    icon: wrap_panel.find('.can-iconpicker').val(),
                };

                ListData.push(singleField);

            } else if(dataType == 'upload') {
                var singleField = {
                    key: wrap_panel.find('.field_key').val(),
                    type: dataType,
                    tab: wrap_panel.find('.field_tab').val(),
                    default: wrap_panel.find('.field_default').val(),
                    title: wrap_panel.find('.field_title').val(),
                    max_files: wrap_panel.find('.field_max_files').val(),
                    max_files_msg: wrap_panel.find('.field_max_files_msg').val(),
                    file_type: wrap_panel.find('.field_file_type').val(),
                    help: wrap_panel.find('.field_help').val(),
                    accessibility: wrap_panel.find('.field_accessibility').val(),
                    required: wrap_panel.find('.field_required').is(':checked') ? true : false,
                    icon: wrap_panel.find('.can-iconpicker').val(),
                };

                ListData.push(singleField);
            } else {
                var singleField = {
                    key: wrap_panel.find('.field_key').val(),
                    type: dataType,
                    tab: wrap_panel.find('.field_tab').val(),
                    default: wrap_panel.find('.field_default').val(),
                    title: wrap_panel.find('.field_title').val(),
                    help: wrap_panel.find('.field_help').val(),
                    editable: wrap_panel.find('.field_editable').val(),
                    max_value: wrap_panel.find('.field_max_value').val(),
                    min_value: wrap_panel.find('.field_min_value').val(),
                    accessibility: wrap_panel.find('.field_accessibility').val(),
                    required: wrap_panel.find('.field_required').is(':checked') ? true : false,
                    icon: wrap_panel.find('.can-iconpicker').val(),
                };

                ListData.push(singleField);
            }

        });
        var data = {
            action: 'can_save_custom_fields',
            fields: ListData
        }
        $.post(ajaxurl, data, function(resp) {
            Swal.fire(resp.title, resp.message, resp.status);
        }, 'json');
    });

    $('body').on('click', '.can-reset-settings',function(e) {
        event.preventDefault();

        Swal.fire({
          title: "Are you sure?",
          text: "Once reset, you will not be able to recover custom fields!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, Reset Fields!'
        }).then((result) => {
          if (result.isConfirmed) {
            var data = {
                action: 'can_reset_custom_fields',
                reset: 'yes'
            }
            $.post(ajaxurl, data, function(resp) {
                Swal.fire("Reset is Done!", '', 'success');
                window.location.reload();
            });
          }
        });

    });

    $('.form-meta-setting').on('keyup', 'input.field_title', function() {
        var input_val = $(this).val();
        var parent = $(this).closest('.card');
        parent.find('.card-header b').text(input_val+' - ');
    });

    $('.form-meta-setting').on('blur', 'input.field_title', function() {
        if ($(this).closest('.inside-contents').find('.field_key').val() == '') {
            var data_name = $(this).val().replace(/[^a-z0-9\s]/gi, '').replace(/[-\s]/g, '_');
            $(this).closest('.inside-contents').find('.field_key').val(data_name.toLowerCase());
        }
    });
});