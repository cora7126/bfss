jQuery(document).ready(function() {


jQuery('.dashboard-menu span').on('click',function(){
	 jQuery(".top_right").toggleClass("toggle2");
	 jQuery('.dashboard-menu span').toggleClass("tgl-cls");
	 console.log("hr1");
});
 

	i = 0;
	jQuery('#edit-organizations-plx').on('click',function(){
		var arr = [];
		jQuery('.edit-ckeckbox-plx:checked').each(function () {
			arr[i++] = $(this).val();
		});
		console.log(jQuery.isEmptyObject(arr));
		//if(jQuery.isEmptyObject(arr) == false){
			var arrStr = encodeURIComponent(JSON.stringify(arr));
			window.location.href = "http://5ppsystem.com/edit-organizations?nids="+arrStr;
		//}

	});

	// jQuery('.drupal-approve-org').on('click',' button.ui-dialog-titlebar-close',function(){
	// 	location.reload();
	// });
	// jQuery('.drupal-edit-org ').on('click','button.ui-dialog-titlebar-close',function(){
	// 	location.reload();
	// });
});