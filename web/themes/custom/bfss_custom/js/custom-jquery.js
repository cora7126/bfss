jQuery(document).ready(function() {

	jQuery( "#sortable_faqs" ).sortable();
    jQuery( "#sortable_faqs" ).disableSelection();

 	jQuery('#select_faqs_by_user').niceSelect();
	
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
		// var names = 'Harry,John,Clark,Peter,Rohn,Alice';
		// var nameArr = names.split(',');
	 //  	var companies = [
  //                   {"companyName":"Aperture Science"},
  //                   {"companyName":"MomCorp"},
  //                   {"companyName":"Wayne Enterprises"},
  //                   {"companyName":"Umbrella Corp"},
  //                   {"companyName":"Gringotts"},
  //                   {"companyName":"Globex"}
  //                 ];

  //       jQuery("#companyPicker").fuzzyComplete(companies);

  		//daynamic data
		//  var orgNames = jQuery('textarea:input[name=search_org]').val();
		//  var orgNamesArr = orgNames.split(',');
		//  console.log(orgNamesArr);
		//  //static data
		//  var names = 'Harry,John,Clark,Peter,Rohn,Alice';
		//  var nameArr = names.split(',');


		// jQuery("#country").autosuggest({
		// 			sugggestionsArray: nameArr
		// 		});
		// console.log(orgNamesArr);
		 //jQuery('textarea:input[name=search_org]').hide();
		// jQuery( ".orgNames_searchs" ).keydown(function() {
  // 						 var orgNames = jQuery('textarea:input[name=search_org]').val();
		// 				 var orgNamesArr = orgNames.split(',');
		// 				 var lg = jQuery('.orgNames_searchs').val().length;
		// 					if (lg != 0) {
  //     							 jQuery(".orgNames_searchs").autosuggest({
		// 								sugggestionsArray: orgNamesArr
		// 						});
		// 					}else{
		// 						 //jQuery('.jquery-autosuggest-suggestions').hide();
		// 					}		
		// 	});

		jQuery("input#edit-orgnames-search").click(function(){
			   var orgNames = jQuery('textarea:input[name=search_org]').val();
			var orgNamesArr = orgNames.split(',');
			var lg = jQuery('.orgNames_searchs').val().length;
										
			jQuery(".orgNames_searchs").autosuggest({
					sugggestionsArray: orgNamesArr
			});
		});


		jQuery('a.delete-assess').on('click',function(){
			var $nid = jQuery(this).data("nid");
			console.log(jQuery(this).data("nid"));
			if(confirm('Are you sure you want to delete this?')){
				jQuery.ajax({
					url : 'http://5ppsystem.com/delete-assessments-data/'+$nid,
					dataType: 'json',
					cache: false,
					success: function(data){
						//console.log(data);
						if(data){
							location.reload();	
						}	
					},
					error :function (data){
						//console.log(data);
					}
				});
				//jQuery(this).parents('.athlete_left').remove();
			}
		});
		
       
});