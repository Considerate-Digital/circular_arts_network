jQuery(document).ready( function($) {
  
  $('.ucl-iconpicker').fontIconPicker();

  if ($('#category-image-wrapper').find('.bi').length) {
    $('.ucl-image-upload').hide();
  }

   function rem_cat_media_upload(button_class) {
     var _custom_media = true,
     _orig_send_attachment = wp.media.editor.send.attachment;
     $('body').on('click', button_class, function(e) {
       var button_id = '#'+$(this).attr('id');
       var send_attachment_bkp = wp.media.editor.send.attachment;
       var button = $(button_id);
       _custom_media = true;
       wp.media.editor.send.attachment = function(props, attachment){
         if ( _custom_media ) {
           $('#category-image-id').val(attachment.id);
           $('#category-image-wrapper').html('<img class="ucl-category-thumb" src="" /> <i class="bi bi-x-circle"></i>');
           $('#category-image-wrapper .ucl-category-thumb').attr('src', attachment.url).css('display','block');
           $('.ucl-image-upload').hide();
         } else {
           return _orig_send_attachment.apply( button_id, [props, attachment] );
         }
        }
     wp.media.editor.open(button);
     return false;
   });
 }
 rem_cat_media_upload('.ucl-image-upload');

 $('body').on('click','.bi-x-circle',function(){
   $('#category-image-id').val('');
   $('#category-image-wrapper').html('');
   $('.ucl-image-upload').show();
 });

 $(document).ajaxComplete(function(event, xhr, settings) {
   var queryStringArr = settings.data.split('&');
   if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
     var xml = xhr.responseXML;
     $response = $(xml).find('term_id').text();
     if($response!=""){
       // Clear the thumb image
       $('#category-image-id').val("");
       $('#category-image-wrapper').html('');
       $('.ucl-image-upload').show();
     }
   }
 });
});