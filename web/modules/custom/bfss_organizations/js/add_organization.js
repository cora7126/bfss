 (function($) {

			        	console.log("here2222222");
			  $.fn.myTest = function(data) {
			    alert('My Test Has called');
			    alert(data);
			  };

})(jQuery);


(function ($, Drupal) {
  Drupal.behaviors.bfss_organizations = {
    attach: function (context, settings) {
      // I am doing a find() but you can do a once() or whatever you like :-)
      $('#element', context).find('.sub-element').each(function () {
        // Do your thing here

			        	console.log("here2222222");
			  $.fn.myTest = function(data) {
			    alert('My Test Has called');
			    alert(data);
			  };
      });
    }
  };
})(jQuery, Drupal);