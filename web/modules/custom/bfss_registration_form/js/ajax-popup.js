
(function($, Drupal) {
  Drupal.behaviors.bfss_registration_form = {
    attach: function (context, settings) {
       // console.log(drupalSettings.payment.status.payment_status);
    	jQuery("#paymentfailModal span.closepopup.close").on("click", function(){
			location.href = '/user/register';
		});

        jQuery("#coachModal span.closepopup.close").on("click", function(){
            location.href = '/user/login';
        }); 

        jQuery("#ForgetPassModal span.closepopup.close").on("click", function(){
            location.href = '/user/login';
        }); 
        
    	if(drupalSettings.payment.status.payment_status == true){
    		loadModal('#requestCallbackModal');
    	}
    	if(drupalSettings.payment.status.payment_status == false){
    		loadModal('#paymentfailModal');
    	}	
        if(drupalSettings.payment.status.payment_status == 'NOTNEED'){
            loadModal('#coachModal');
        }   
         if(drupalSettings.payment.status.payment_status == 'FORGETPASSWORD'){
            loadModal('#ForgetPassModal');
        } 
    }
  }
})(jQuery, Drupal);