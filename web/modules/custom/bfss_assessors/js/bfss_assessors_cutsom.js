jQuery(document).ready(function(){
	jQuery('#dtBasicExample').DataTable();
	jQuery('#assessemntlistingpro').DataTable();
	jQuery('#SearchAthlete_pxl').DataTable();
    jQuery('#bfss_payment_pending_pxl').DataTable();
    jQuery('#bfss_payment_paid_pxl').DataTable();
	jQuery('.eventlisting_main .form-item-par-page-item select').change(function() {
		var currentpageurl =  window.location.href;
		var currentselect_val = jQuery(this).val();
		var fullurl = currentpageurl+'&par_page_item='+currentselect_val; 
		window.location.href = fullurl;
	});
});