(function ($, Drupal) {
  Drupal.behaviors.bfss_registration_form_complete = {
    attach: function (context, settings) {
      $('.complete-registration-form .form-type-password-confirm .form-type-password input[type=password]').each(function () {
        var ftp = $(this);

        var field_text = ftp.prev().text();
        ftp.prev().hide();
        ftp.attr({'placeholder':field_text})

      });

      $('.complete-registration-form').on('click', '.btn-trouble,.btn-resend-code', function (e) {
        e.preventDefault();

        return false;
      });

      $('.form-item-confirm-code,.send-code-wrapper').hide();

      var is_sent = 0;
      if (settings.bfss_registration_form !== undefined) {
        if (settings.bfss_registration_form.code_sent !== undefined) {
          $('.send-code-wrapper').remove();
          $('.form-item-confirm-code').show();
          is_sent = 1;
        }
      }

      if (drupalSettings.bfss_registration_form !== undefined) {
        if (drupalSettings.bfss_registration_form.redirect_too !== undefined) {
          location.href = drupalSettings.bfss_registration_form.redirect_too;
        }
      }

      $('input[name=phone],input[name=confirm_phone]').on('keyup', function () {

        if (!is_sent) {
          if ($('input[name=phone]').val() != '' && $('input[name=confirm_phone]').val() != '') {
            $('.send-code-wrapper').show();
          } else {
            $('.send-code-wrapper').hide();
          }
        }

      });

    }
  };
})(jQuery, Drupal);
