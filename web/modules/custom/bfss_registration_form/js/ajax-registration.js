(function ($, Drupal) {
  Drupal.behaviors.bfss_registration_form = {
    attach: function (context, settings) {
      // trigger submit button
      $('select[name=user_type],select[name=field_program_term]').on('change', function (e) {
        e.preventDefault();
        /* setTimeout(function(){
                jQuery('.js-text-full').removeAttr('value');
        
               var ht = jQuery( ".step-2 .panel-title" ).html();
//            ht.slice(0,15);
//            console.log(ht);     

    jQuery('.form-item-fields-list-title-2').html('Parent Guardian Information');
    jQuery('.step-2 .js-form-item-fields-list-title-2').html('Parent / Guardian Information');
           var abc = ht.split('<img');
   //        var abc = ht.split(')');
//           console.log(abc[0].length);
           if(abc[0].length <= 341){
//               console.log('herere');
//                jQuery('.step-2 .panel-title').html('Step 2 - Athletes Information');
           }else if(abc[0].length <= 371){
//               console.log('dgfggdg');
                jQuery('.step-2 .panel-title').html('<div> Step 2 - Parent / Guardian <div class ="register">(Registering Athlete)</div></div> ');
           }
        },2000); */

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
        //  check if user type exist
        if (drupalSettings.bfss_registration_form.user_type !== undefined) {
          setTimeout(function () {
            $('select[name=user_type]').trigger('change');
          }, 150);
          drupalSettings.bfss_registration_form.user_type = undefined;
        }
        //  check if field_program_term exist
        if (drupalSettings.bfss_registration_form.field_program_term !== undefined) {
          setTimeout(function () {
            $('select[name=field_program_term]').trigger('change');
          }, 150);
          drupalSettings.bfss_registration_form.field_program_term = undefined;
        }
        if (drupalSettings.bfss_registration_form.registered !== undefined) {
          $('.user-register-form .button--primary').attr({'disabled':'disabled'});

          setTimeout(function () {
            $('.user-register-form').prev().find('.messages__wrapper').remove();
          }, 150);

          drupalSettings.bfss_registration_form.registered = undefined;
          setTimeout(function(){
            //  redirect page to home
            //location.href = location.origin;
            //  set form to initally value
            // $('.user-register-form select[name=user_type]').val(0);
            // $('.user-register-form select[name=user_type]').trigger('change');
          }, 7000);
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

      changeTheSign(step);

    }
  };

  $(document).ready(function () {

    $(document).ajaxSend(function( event, xhr, settings ) {
      if (settings.url.indexOf('/user/register?') !== -1) {
        // $('.panel-body.in').throbber('show');
        $('.panel-body.in').throbber().throbber('show');
      }
    });

    $(document).ajaxComplete(function( event, xhr, settings ) {
      if (settings.url.indexOf('/user/register?') !== -1) {
        // $('.panel-body').removeClass('processed');
      }
    });

  });

})(jQuery, Drupal);
