jQuery(document).ready(function(){
/**************** View ACTIVE PAGE JS START FROM HERE**************/


	jQuery(document).on('click','a.user-status-edit',function(){
			var uid = jQuery(this).data("uid");
			var editpage = jQuery(this).data("editpage");
			jQuery("button#deactive-yes").attr("data-uid",uid);
			jQuery("button#deactive-yes").attr("data-editpage",editpage);
			jQuery('#ConfirmDeactivateModal').modal('show');
	});
	jQuery('button#deactive-no, button.close.deactivate-close').click(function(){
		jQuery('div.message-deactivate').html("");
	});	
	jQuery('button#deactive-yes').click(function(){
			var uid = jQuery(this).data("uid");
			var editpage = jQuery(this).data("editpage");
			console.log(uid);
			jQuery.ajax({
				url : 'http://5ppsystem.com/user-status-update/'+uid+'/'+editpage,
				dataType: 'json',
				cache: false,
				success: function(data){
					console.log(data);
					if(data.status == "true" && data.editpage == "ViewEditActive"){
						jQuery('div.message-deactivate').html("<p style='color:green;'>User Successfully Deactivate!</p>");
					}else if(data.status == "true" && data.editpage == "ViewEditDeactive"){
						jQuery('div.message-deactivate').html("<p style='color:green;'>User Successfully Reactivate!</p>");
					}
				},
				error :function (data){

				}
			});
	});


	jQuery(document).on('click','a.paginate_button',function(){ // reload jquery
		jQuery('.box.niceselect.roles.viewactive select').change(function(){
				var uid = jQuery(this).data("uid");
				var oldrole = jQuery(this).data("role");
				var newrole = jQuery(this).val();
				var dropdown = jQuery(this).data("dropdown");
				console.log(newrole);
				jQuery.ajax({
					url : 'http://5ppsystem.com/user-role-update/'+uid+'/'+oldrole+'/'+newrole+'/'+dropdown,
					dataType: 'json',
					cache: false,
					success: function(data){
						//console.log(data);
						if(data.status == "true"){ 
							jQuery('#ignismyModal').modal('show');
						}
						
					},
					error :function (data){

					}
				});
		});

	});

	jQuery('.box.niceselect.roles select').change(function(){
			var uid = jQuery(this).data("uid");
			var oldrole = jQuery(this).data("role");
			var newrole = jQuery(this).val();
			var dropdown = jQuery(this).data("dropdown");
			console.log(dropdown);
			jQuery.ajax({
				url : 'http://5ppsystem.com/user-role-update/'+uid+'/'+oldrole+'/'+newrole+'/'+dropdown,
				dataType: 'json',
				cache: false,
				success: function(data){
					if(data.status == "true"){
						jQuery('#ignismyModal').modal('show');
					}
				},
				error :function (data){

				}
			});
	});
/**************** VIEW ACTIVE PAGE JS START FROM HERE**************/

/**************** VIEW DEACTIVE PAGE JS START FROM HERE**************/

/**************** VIEW DEACTIVE PAGE JS END FROM HERE**************/

});