

jQuery(document).ready(function(){
    jQuery('#bfss_payment_letest_pxl').DataTable(
		{
	 "order": [[ 0, "desc" ]], //or asc 
    "columnDefs" : [{"targets":0, "type":"date"}],
		}
    	);
});