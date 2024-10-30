$(document).ready(function(){

  $('.bc-content-feedback-form-widget form button').on('click', function(e){
    e.preventDefault();
    $('.bc-content-feedback-form-widget form button').prop('disabled', 'disabled');
    var feedback = $(this).val();
    var post_id = $(this).parents('.bc-content-feedback-form-widget').find('#post-id').val();
    var security = $(this).parents('.bc-content-feedback-form-widget').find('#security').val();
    var widget_instance = $(this).parents('.bc-content-feedback-form-widget').find('#widget-instance').val();

    var data = {
      'action':   'bc-submit-content-feedback',
      'security': security,
      'post_id':  post_id,
      'feedback': feedback,
      'widget_instance': widget_instance
    };

    jQuery.post(ajaxurl, data, function(response) {

      if ( response.success ){
        console.log('Success!!');
        $('.bc-content-feedback-form-widget form').remove();
        $('.bc-content-feedback-form-widget .message').removeClass('hidden');
      } else {
        $('.bc-content-feedback-form-widget form button').prop('disabled', false);
        alert('Sorry, an error occurred. Please try again.');
      }
    });
  })
});
