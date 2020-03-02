(function($, Drupal) {
  Drupal.behaviors.bfss_assessment = {
    attach: function (context, settings) {
      //do your stuff here
      //do use once for better results
      	jQuery(document).once().on('click','.accordion_tabs_block .toggler-wrap h3',function(){
            jQuery(this).parent().find('.toggle-content').slideToggle();
            jQuery(this).find('.toggle_icon').find('i').toggleClass('hide');
        });
    }
  }
})(jQuery, Drupal);