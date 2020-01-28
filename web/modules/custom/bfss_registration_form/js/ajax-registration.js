(function ($, Drupal) {
  Drupal.behaviors.bfss_registration_form = {
    attach: function (context, settings) {
      $('.step-1 .panel-title').click(function() {
        $('.step-2 .panel-body').removeClass('in');
      });

      $('.step-2 .panel-title').click(function() {
        $('.step-1 .panel-body').removeClass('in');
      });
    }
  };
})(jQuery, Drupal);
