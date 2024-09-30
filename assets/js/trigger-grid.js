jQuery(document).ready(function($) {
    if ($('.grid-custom').length) {
        var images = $('.grid-custom').children('img').map(function(){
            return $(this).attr('src')
        }).get();
        var grid_options = $('.grid-custom').data('grid');
        grid_options.images = images;
        grid_options.getViewAllText = function(imagesCount) {
            var txt = can_grid_vars.grid_view_txt;
            txt = txt.replace("%count%", imagesCount);
            return txt;
        }
        $('.grid-custom').html('');
        $('.grid-custom').imagesGrid(grid_options);
    }
});