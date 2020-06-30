jQuery(document).ready(function(){
		
		jQuery("button.close.ui-dialog-titlebar-close").click(function(){
		   location.reload();
		});
		jQuery('body').on('click', 'span.delete-org-span', function(){
			 location.reload();
		});
  		jQuery('.pending-del-org').on('click',function(){
			var org_id = jQuery(this).attr("id");
			jQuery(this).parents('.athlete_left').addClass('delete_athlete');
			jQuery('body').append('<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">'+
			  '<div class="modal-dialog" role="document">'+
			    '<div class="modal-content">'+
			      '<div class="modal-header p-4" style="background: #000;">'+
			        '<h3 class="modal-title text-uppercase text-white" id="exampleModalLabel">DELETE Information</h3>'+
			        '<button type="button" class="close confirmation-close-button" data-dismiss="modal" aria-label="Close">'+
			          '<span class="delete-org-span" aria-hidden="true">&times;</span>'+
			        '</button>'+
			     ' </div>'+
			      '<div class="modal-body px-4"><p>Are you sure you want to delete this information permanently?</p></div>'+
			      '<div class="modal-footer px-4">'+
			        '<button type="button" class="modal-org-del btn btn-lg text-white btn-default text-uppercase p-3" id="'+org_id+'" style="background: #f76907; font-size: 17px;">YES, Delete</button>'+
			      '</div>'+
			    '</div>'+
			  '</div>'+
			'</div>');
			jQuery('#confirmModal').modal('show');		
		});


	  	jQuery(document).on('click', '.modal-org-del', function(){
			var org_id1 = jQuery(this).attr("id");
			console.log(org_id1);
			jQuery.ajax({
				url : 'http://5ppsystem.com/delete-organization/'+org_id1,
				dataType: 'json',
				cache: false,	
				success: function(data){
					//console.log(data);
					console.log('delete');
				},
				error :function (data){
				}
			});
		});

});