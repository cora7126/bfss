jQuery(document).ready(function(){
  jQuery("button.close.ui-dialog-titlebar-close").click(function(){
   location.reload();
  });

  	jQuery(document).on('click', '.pending-del-org', function(){
		var org_id = jQuery(this).attr("id");
		jQuery.ajax({
			url : 'http://5ppsystem.com/delete-organization/'+org_id,
			dataType: 'json',
			cache: false,
			success: function(data){
				console.log(data);
			},
			error :function (data){
			}
		});
	});

});