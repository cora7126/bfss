(function ($, Drupal) {
  Drupal.behaviors.bfss_registration_form = {
    attach: function (context, settings) {
      $('.step-1 .panel-title').click(function() {
        $('.step-2 .panel-body').removeClass('in');
         changeTheSign("one");
      });

      $('.step-2 .panel-title').click(function() {
        $('.step-1 .panel-body').removeClass('in');
        changeTheSign("two");
      });

      changeTheSign();

      function changeTheSign(step) {

        /* Step 1 */
          if((step === 'one') || (step === '')) {
              if ($('.step-1 .panel-title').attr('aria-expanded') === 'true') {
                  $('.step-1 .panel-title img').attr('src', '/sites/default/files/images/forms/bfss-register-form-icon-minus.png');
                  $('.step-2 .panel-title img').attr('src','/sites/default/files/images/forms/bfss-register-form-icon-plus.png')
              } else {
                  $('.step-1 .panel-title img').attr('src', '/sites/default/files/images/forms/bfss-register-form-icon-plus.png');
                  $('.step-2 .panel-title img').attr('src','/sites/default/files/images/forms/bfss-register-form-icon-minus.png')
              }
          }
          /* Step 2 */
          if((step === 'two') || (step === '')) {
              if ($('.step-2 .panel-title').attr('aria-expanded') === 'true') {
                  $('.step-2 .panel-title img').attr('src', '/sites/default/files/images/forms/bfss-register-form-icon-plus.png');
                  $('.step-1 .panel-title img').attr('src','/sites/default/files/images/forms/bfss-register-form-icon-plus.png')
              } else {
                  $('.step-2 .panel-title img').attr('src', '/sites/default/files/images/forms/bfss-register-form-icon-minus.png');
                  $('.step-1 .panel-title img').attr('src','/sites/default/files/images/forms/bfss-register-form-icon-plus.png')
              }
          }
      }
    }
  };
})(jQuery, Drupal);
