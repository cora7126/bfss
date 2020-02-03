(function ($, Drupal) {
  Drupal.behaviors.bfss_registration_form = {
    attach: function (context, settings) {
      // trigger submit button
      $('select[name=user_type],select[name=field_program_term]').on('change', function (e) {
        e.preventDefault();
        $(this).parents('.details-wrapper,.panel-body').find('.form-submit').trigger('click');
      });

      $('.step-4').on('click', 'input[name="is_over_16_years"],input[name="parent_permission"]', function () {
        $(this).parents('.step-4').find('.button[name="next_step_4"]').trigger('click');
      });

      var img_plus = '/sites/default/files/images/forms/bfss-register-form-icon-plus.png',
          img_minus = '/sites/default/files/images/forms/bfss-register-form-icon-minus.png',
          step = 1;

      if (drupalSettings.bfss_registration_form != undefined) {
        if (drupalSettings.bfss_registration_form.step !== undefined) {
          step = drupalSettings.bfss_registration_form.step;
        }
        if (drupalSettings.bfss_registration_form.registered !== undefined) {
          setTimeout(function(){
            // $('.user-register-form select[name=user_type]').val(0);
            // $('.user-register-form select[name=user_type]').trigger('change');
          }, 5000);
          drupalSettings.bfss_registration_form.registered = undefined;
        }
      }

      $('.reg-form-tabs').on('click', '.panel-title', function () {
        $(this).find('img').attr({'src':img_minus});
        var step = $(this).parents('.reg-form-tabs').data('step'),
            is_expanded = $('.step-' + step + ' .panel-title').attr('aria-expanded') === 'true';

        $('.step-' + step + ' .panel-title img').attr({'src': is_expanded ? img_plus : img_minus});

        $(this).parents('.reg-form-tabs').siblings().find('.panel-title[aria-expanded="true"]').trigger('click');

      });

      function changeTheSign(step = 1) {
        $('.reg-form-tabs').each(function (i) {
          var j = i + 1;
          $('.step-' + j + ' .panel-title img').attr({'src': j == step ? img_minus : img_plus});
        });
      }

      if (!$('body').hasClass('form-loaded')) {
        $('body').addClass('form-loaded');
        changeTheSign();
      }




      if (jQuery().credit) {
        // jQuery('.form-item-cck-number').credit({'placeholder': 'Credit Card Number'});
      }

      // $('.step-1 .panel-title').click(function () {
      //   $('.step-2 .panel-body').removeClass('in');
      //   changeTheSign("one");
      // });
      //
      // $('.step-2 .panel-title').click(function () {
      //   $('.step-1 .panel-body').removeClass('in');
      //   changeTheSign("two");
      // });
      //
      // changeTheSign();
      //
      // function changeTheSign(step) {
      //
      //   /* Step 1 */
      //   if ((step === 'one') || (step === '')) {
      //     if ($('.step-1 .panel-title').attr('aria-expanded') === 'true') {
      //       $('.step-1 .panel-title img').attr('src',
      // '/sites/default/files/images/forms/bfss-register-form-icon-minus.png');
      // $('.step-2 .panel-title img').attr('src',
      // '/sites/default/files/images/forms/bfss-register-form-icon-plus.png')
      // } else { $('.step-1 .panel-title img').attr('src',
      // '/sites/default/files/images/forms/bfss-register-form-icon-plus.png');
      // $('.step-2 .panel-title img').attr('src',
      // '/sites/default/files/images/forms/bfss-register-form-icon-minus.png')
      // } } /* Step 2 */ if ((step === 'two') || (step === '')) { if
      // ($('.step-2 .panel-title').attr('aria-expanded') === 'true') {
      // $('.step-2 .panel-title img').attr('src', '/sites/default/files/images/forms/bfss-register-form-icon-plus.png'); $('.step-1 .panel-title img').attr('src', '/sites/default/files/images/forms/bfss-register-form-icon-plus.png') } else { $('.step-2 .panel-title img').attr('src', '/sites/default/files/images/forms/bfss-register-form-icon-minus.png'); $('.step-1 .panel-title img').attr('src', '/sites/default/files/images/forms/bfss-register-form-icon-plus.png') } } }
    }
  };
})(jQuery, Drupal);
