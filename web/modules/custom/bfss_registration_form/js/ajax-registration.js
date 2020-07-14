(function ($, Drupal) {
  Drupal.behaviors.bfss_registration_form = {
    attach: function (context, settings) {
            /****tooltip for register start**/     
            $('.form-item-field-first-name-0-value input').attr("data-toggle","tooltip").attr("title","Enter your first name.");
            $('.form-item-field-last-name-0-value input').attr("data-toggle","tooltip").attr("title","Enter your last name.");
            $('.form-item-mail input').attr("data-toggle","tooltip").attr("title","Enter your email address.");
            $('.form-item-conf-mail input').attr("data-toggle","tooltip").attr("title","Enter your confirm E-mail.");
            $('.form-item-name input').attr("data-toggle","tooltip").attr("title","Enter your username.");
            $('.field--name-field-state select').attr("data-toggle","tooltip").attr("title","Select your state.");
            $('.form-item-user-type  select').attr("data-toggle","tooltip").attr("title","Select your account.");
            $('.form-item-field-program-term  select').attr("data-toggle","tooltip").attr("title","Select your program.");

            $('.form-item-cck-name  input').attr("data-toggle","tooltip").attr("title","Enter your credit card name.");
            $('.form-item-cck-number  input').attr("data-toggle","tooltip").attr("title","Enter your credit card number.");

            $('.form-item-cck-month select').attr("data-toggle","tooltip").attr("title","Select month.");
            $('.form-item-cck-year select').attr("data-toggle","tooltip").attr("title","Select year.");

            $('.form-item-cck-csv  input').attr("data-toggle","tooltip").attr("title","Enter your csv.");

            $('.form-item-bi-first-name  input').attr("data-toggle","tooltip").attr("title","Enter your billing first name.");
            $('.form-item-bi-last-name  input').attr("data-toggle","tooltip").attr("title","Enter your billing last name.");
            $('.form-item-bi-address  input').attr("data-toggle","tooltip").attr("title","Enter your billing address.");
            $('.form-item-bi-city input').attr("data-toggle","tooltip").attr("title","Enter your billing city.");
            $('.form-item-bi-state select').attr("data-toggle","tooltip").attr("title","Select your state.");
            $('.form-item-bi-postal-code input').attr("data-toggle","tooltip").attr("title","Enter Zip/Post Code");
            $('.form-item-bi-country select').attr("data-toggle","tooltip").attr("title","Select country.");
            $('[data-toggle="tooltip"]').tooltip();
            /****tooltip for register end**/

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

     // $(document).ajaxSend(function( event, xhr, settings ) {
     
     //   // console.log(settings.extraData._triggering_element_name);
     //   // console.log(settings.extraData);
     //   // if(settings.extraData._triggering_element_name == "bi_state"){

     //   // }else if(settings.url.indexOf('/user/register?') == -1){

     //   // }else{
     //   //   //$('.panel-body.in').throbber('show');
     //   //  $('.panel-body.in').throbber().throbber('show');
     //   // }
     
     // });

    $(document).ajaxComplete(function( event, xhr, settings ) {
      if (settings.url.indexOf('/user/register?') !== -1) {
        // $('.panel-body').removeClass('processed');
      }
    });

  });

})(jQuery, Drupal);
