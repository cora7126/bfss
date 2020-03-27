Drupal.behaviors.customDatepicker = {
  attach: function (context, settings) {
    jQuery(function () {
      jQuery("#datepicker").datepicker({
        dateFormat: "yy-mm-dd",
      });
    });
  }
};