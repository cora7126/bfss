jQuery(document).ready(function(){
	
 //jQuery("#assessor_popup_form").modal("show");

 jQuery(".form-modal-fn").on('click',function(){
 	var $nid = jQuery(this).data('nid');
 	var $formtype = jQuery(this).data('formtype');
 	var $Assess_type = jQuery(this).data('assesstype');
 	var $booked_id = jQuery(this).data('booked_id');
	 
 	if($formtype == 'Starter' ){
     	jQuery('h2.el').css('display','none');
     	jQuery('h2.st').css('display','block');
    }
    else if($formtype == 'Elite' ){
     jQuery('h2.st').css('display','none');
     jQuery('h2.el').css('display','block');
    }
    else{
     	jQuery('h2.st').css('display','none');
     	 jQuery('h2.el').css('display','none');
     }
	
	if($formtype == 'Starter'){
		 jQuery('div#for_elete').css('display','none');
		 jQuery('div#form_type_hd input').val('Starter');
		 jQuery("div#for_elete input").prop('required',false);
	}else if($formtype == 'Elite'){
		 jQuery('div#for_elete').css('display','block');
		jQuery("div#for_elete input").prop('required',true);
		 jQuery('div#form_type_hd input').val('Elite');
	}

	if($Assess_type == 'individual'){	
		 jQuery('div#assessment_type_hd input').val('individual');
		 
	}else if($Assess_type == 'private'){
		 jQuery('div#assessment_type_hd input').val('private');
	}

	jQuery('div#athelete_nid_hd input').val($nid);
	jQuery('div#booked_id_hd input').val($booked_id);
	jQuery("#assessor_popup_form").modal("show");
 
 });
});