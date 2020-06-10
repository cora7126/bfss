
(function($, Drupal) {
  Drupal.behaviors.bfss_registration_form = {
    attach: function (context, settings) {

    	jQuery("#paymentfailModal span.closepopup.close").on("click", function(){
			location.href = '/user/register';
		});  
    	if(drupalSettings.payment.status.payment_status == true){
    		loadModal('#requestCallbackModal');
    	}
    	if(drupalSettings.payment.status.payment_status == false){
    		loadModal('#paymentfailModal');
    	}	
    }
  }
})(jQuery, Drupal);