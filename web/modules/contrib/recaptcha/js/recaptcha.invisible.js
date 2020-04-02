/**
 * @file
 * Invisible reCaptcha behaviors.
 */

/* globals grecaptcha*/
/* eslint-disable no-unused-vars*/
/**
 * The submit object that was clicked.
 *
 * @type {object}
 */
var clickedSubmit;
var clickedSubmitEvent;
var clickedSubmitAjaxEvent;


/**
 * reCaptcha data-callback that submits the form.
 *
 */
function recaptchaOnInvisibleSubmit(token) {
  'use strict';

  jQuery(clickedSubmit).parents('form').find(".g-recaptcha-response").val(token);

  jQuery(clickedSubmit).unbind('.recaptcha');
  if (clickedSubmitAjaxEvent) {
    jQuery(clickedSubmit).trigger(clickedSubmitAjaxEvent);
  }
  else {
    jQuery(clickedSubmit).click();
  }
  clickedSubmitEvent = clickedSubmit = clickedSubmitAjaxEvent = '';
}

(function ($, Drupal) {
  'use strict';

  /**
   * Handles the submission of the form with the invisible reCaptcha.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches the behavior for the invisible reCaptcha.
   */
  Drupal.behaviors.invisibleRecaptcha = {
   attach: function (context) {
      if (Drupal.hasOwnProperty('Ajax')) {
        var originalBeforeSubmit = Drupal.Ajax.prototype.beforeSubmit;
        Drupal.Ajax.prototype.beforeSubmit = function (form_values, element, options) {
          if (this.event === 'mousedown' && $(this.element).data('recaptcha-submit') && grecaptcha.getResponse().length === 0) {
            clickedSubmit = this.element;
            options.needsRevalidate = true;
            this.progress.type = 'none';
            clickedSubmitAjaxEvent = this.event;
          }

          originalBeforeSubmit.apply(this, arguments);
        };

        if (!$(document).data('invisible-recaptcha-ajax-send-processed')) {
          $(document).ajaxSend(function (event, jqxhr, settings) {
            if (settings.needsRevalidate) {
              jqxhr.abort();
              $(clickedSubmit).prop('disabled', false);
            }
          });

          $(document).data('invisible-recaptcha-ajax-send-processed', true);
        }
      }
      $('form', context).each(function () {
        var $form = $(this);
        if ($form.find('.g-recaptcha[data-size="invisible"]').length) {
          $form.find(':submit').data('recaptcha-submit', true).on({
            'mousedown.recaptcha': function (e) {
              preventFormSubmit(this, e);
            },
            'click.recaptcha': function (e) {
              preventFormSubmit(this, e);
            }
          });
        }
      });

      /**
       * Prevent form submit if recaptcha is not valid.
       *
       *   @param {Object} elem -  Triggering element.
       *   @param {Object} event - Triggering event.
       */
      function preventFormSubmit(elem, event) {
        if (grecaptcha.getResponse().length === 0) {
          // We need validate form, to avoid prevention of html5 validation.
          var form = $(elem).closest('form')[0];
          if (form && typeof form.checkValidity === 'function' && !$(form).attr('validate')) {
            if (form.checkValidity()) {
              event.preventDefault();
              event.stopPropagation();
              clickedSubmitEvent = event.type;
              validateInvisibleCaptcha(elem);
            }
            else {
              if (typeof form.reportValidity === 'function') {
                form.reportValidity();
              }
            }
          }
          else {
            validateInvisibleCaptcha(elem);
          }
        }
      }

      /**
       * Triggers the reCaptcha to validate the form.
       *
       * @param {object} button - The submit button object was clicked.
       */
      function validateInvisibleCaptcha(button) {
        clickedSubmit = button;
        grecaptcha.execute();
      }
    }
  };
})(jQuery, Drupal);