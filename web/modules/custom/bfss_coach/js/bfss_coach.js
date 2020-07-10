(function($, Drupal) {
  Drupal.behaviors.bfss_coach = {
    attach: function (context, settings) {
       //coachfirsttime popup submit button color chnage start//
       var toValidate = jQuery('input[name="numberone"], input[name="city"]');
		toValidate.each(function () {
				if (jQuery(this).val().length > 0) {
		 			valid = false;
				} else {
					valid = true;
				}
			});

		if(valid == false){
			jQuery('.js-form-submit').addClass("green");
			jQuery('.js-form-submit').removeClass("gray");
			jQuery("#edit-submit").prop('disabled', false);
		}else{
			jQuery('.js-form-submit').addClass("gray");
			jQuery('.js-form-submit').removeClass("gray");
			jQuery("#edit-submit").prop('disabled', true);
		}

		   jQuery('#edit-sextype, #edit-az').on('change',function () {
			   jQuery('#edit-sextype, #edit-az').each(function () {
				if (jQuery(this).val().length > 0) {
		 			valid = false;
				} else {
					valid = true;
				}
			   });

			    if (valid == false) {
				jQuery('.js-form-submit').addClass("green");
				jQuery('.js-form-submit').removeClass("gray");
				jQuery("#edit-submit").prop('disabled', false);
			    } else {
				jQuery('.js-form-submit').addClass("gray");
				jQuery('.js-form-submit').removeClass("green");
				jQuery("#edit-submit").prop('disabled', true);
			    }
		  });


		   jQuery('input[name="city"]').on('keyup',function () {
			   jQuery('input[name="city"]').each(function () {
				if (jQuery(this).val().length > 0) {
		 			valid = false;
				} else {
					valid = true;
				}
			   });

			    if (valid == false) {
				jQuery('.js-form-submit').addClass("green");
				jQuery('.js-form-submit').removeClass("gray");
			    } else {
				jQuery('.js-form-submit').addClass("gray");
				jQuery('.js-form-submit').removeClass("green");
			    }
		  });

		    jQuery('input[name="numberone"]').on('keyup',function () {
			   jQuery('input[name="numberone"]').each(function () {
				if (jQuery(this).val().length > 0) {
		 			valid = false;
				} else {
					valid = true;
				}
			   });

			    if (valid == false) {
				jQuery('.js-form-submit').addClass("green");
				jQuery('.js-form-submit').removeClass("gray");
				jQuery("#edit-submit").prop('disabled', false);
			    } else {
				jQuery('.js-form-submit').addClass("gray");
				jQuery('.js-form-submit').removeClass("green");
				jQuery("#edit-submit").prop('disabled', true);
			    }
		  });

    //coachfirsttime popup submit button color chnage end//
}
  }
})(jQuery, Drupal);