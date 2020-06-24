jQuery(document).ready(function() {

// jQuery(document).on('change', '.form-item-bi-state select', function(){
// 	var Orgname = jQuery(this).data("org");

// 	jQuery.ajax({
// 		url : 'http://5ppsystem.com/get-state-autocomplete/'+'AK'+'/'+'10',
// 		dataType: 'json',
// 		cache: false,
// 		success: function(data){
// 			// jQuery('.delete_athlete').remove();
// 			// jQuery('#confirmModal').modal('hide');
// 			console.log(data);
// 		},
// 		error :function (data){

// 		}
// 	});
// });

//SAVE BUTTON TEXT CHANGE ON MOBILE [START HERE] 
function checkWidth() {
   if (jQuery(window).width() < 514) {
   jQuery('.bfss_save_all.save_all_changes').addClass('save-mobile');
   jQuery(".bfss_save_all.save_all_changes button").text("SAVE");
	} else {
	    jQuery('.bfss_save_all.save_all_changes').removeClass('save-mobile');
	    jQuery(".bfss_save_all.save_all_changes button").text("SAVE ALL CHANGES");
	}
}
jQuery(window).resize(checkWidth);
//SAVE BUTTON TEXT CHANGE ON MOBILE [END HERE] 

jQuery('.athlete_left i.fa.fa-info.right-icon,.athlete_right i.fa.fa-info.right-icon').click(function (evt){
	return false;
});
	jQuery('#requestCallbackModal span.closepopup').click(function(){
	window.location = "/user/login";
});
	jQuery(".user_pro_block .table-responsive-wrap table").wrap("<div class='table-responsive'></div>");

	jQuery(".search_icon_field i.fal.fa-caret-down,.search_icon_field i.fal.fa-calendar-alt").click(function(){
	 	jQuery(".month-view-form .nice-select.form-select.form-control").toggleClass('open');
    	return false;
	});
jQuery('[data-toggle="tooltip"]').tooltip();

jQuery('.edit_dropdown .drop').click(function(){
console.log("image");
jQuery('.data.col-sm-10 ').toggle();

});
jQuery("[name=image_athlete_remove_button]").wrap('<div class="remove_btn_wrap"></div>');
	jQuery(".share a.share_assessment").click(function(){
  		jQuery(".social_share_links").toggle('slow');
  		
	});
	jQuery('#calendar .fc-view-container td.fc-event-container a').addClass('col use-ajax assessment_inner');
jQuery('#calendar .fc-view-container td.fc-event-container a').attr('data-dialog-type','modal');
jQuery('#calendar .fc-view-container td.fc-event-container a').attr('data-dialog-options','{&quot;width&quot;:800, &quot;dialogClass&quot;: &quot;assessments-popup-md&quot;}');
    //jQuery( "#sortable_faqs" ).disableSelection();
    jQuery(document).on('click', 'a[href^="#assessment-event-section"]', function (e) {
    e.preventDefault();
    jQuery('html, body').stop().animate({
        scrollTop: jQuery(jQuery(this).attr('href')).offset().top - 2
    }, 1000, 'linear');
	});
	 jQuery(document).on('click', 'a[href^="#assessment-private-section"]', function (e) {
    e.preventDefault();
    jQuery('html, body').stop().animate({
        scrollTop: jQuery(jQuery(this).attr('href')).offset().top - 2
    }, 1000, 'linear');
	});

	jQuery(document).on('click', 'a[href^="#Pending_section"]', function (e) {
    e.preventDefault();
    jQuery('html, body').stop().animate({
        scrollTop: jQuery(jQuery(this).attr('href')).offset().top - 2
    }, 1000, 'linear');
    return false;
	});

	jQuery(document).on('click', 'a[href^="#Paid_section_ppp"]', function (e) {
    e.preventDefault();
    jQuery('html, body').stop().animate({
        scrollTop: jQuery(jQuery(this).attr('href')).offset().top 
    }, 1000, 'linear');
    
    return false;
	});

jQuery( ".user-login-form input[name=name]" ).attr('tabindex','1');
jQuery( ".user-login-form input[name=pass]" ).attr('tabindex','2');
 	jQuery('#select_faqs_by_user').niceSelect();
	
	jQuery('.dashboard-menu span').on('click',function(){
		 jQuery(".top_right").toggleClass("toggle2");
		 jQuery('.dashboard-menu span').toggleClass("tgl-cls");
		 console.log("hr1");
	});
	 	jQuery('a.pr-3.assessment-search-icon').click(function(){
	 		jQuery(".search-assessements-input").toggle('slow');	
	 	});
		i = 0;
		jQuery('#edit-organizations-plx').on('click',function(){
			var arr = [];
			jQuery('.edit-ckeckbox-plx:checked').each(function () {
				arr[i++] = $(this).val();
			});
			console.log(jQuery.isEmptyObject(arr));
			if (arr.length != 0) {
				var arrStr = encodeURIComponent(JSON.stringify(arr));
				window.location.href = "http://5ppsystem.com/edit-organizations?nids="+arrStr;
			}else{
				alert("Plaese Select Checkboxes.");
			}

		});

		// jQuery('.edit-ckeckbox-plx').click(function(){
  //           if(jQuery(this).is(":checked")){
  //               console.log(jQuery(this).val());
  //               var nid = jQuery(this).val();
  //               window.location.href = "http://5ppsystem.com/edit-organizations?nids="+nid;
  //           }
  //           else if(jQuery(this).is(":not(:checked)")){
  //               console.log("Checkbox is unchecked.");
  //           }
		// });

		//autosuggest
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


		jQuery('span.removeorg').on('click',function(){
			var $nid = jQuery(this).data("nid");
			console.log(jQuery(this).data("nid"));
			if(confirm('Are you sure you want to delete this?')){
				jQuery.ajax({
					url : 'http://5ppsystem.com/delete-org-from-coach/'+$nid,
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