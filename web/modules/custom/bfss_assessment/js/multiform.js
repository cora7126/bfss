(function($, Drupal) {
  Drupal.behaviors.bfss_assessment = {
    attach: function (context, settings) {
      //do your stuff here
      //do use once for better results
        // jQuery('.timeslots').find('input[type="radio"]').prop('checked', false);
        jQuery('input[type="radio"]').on('click',function(){
          var nid = jQuery(this).parent().find('span').data('nid')
          jQuery('.timeslots-nid input').val(nid);
      
        });
      var anyRadio = jQuery('.timeslots').find('input[type="radio"]');
      anyRadio.once().on('change', function(){
      	jQuery('.timeslots').find('input[type="radio"]').prop('checked', false);
        jQuery('.timeslots').find('input[type="radio"]').removeAttr('checked');
      	jQuery(this).prop('checked', true);
      });
    }
  }
})(jQuery, Drupal);