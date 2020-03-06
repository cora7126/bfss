(function($) {
    $(document).ready(function() {
        /*jQuery.ajax({
url : 'http://5ppsystem.com/delete/parent/'+$id+'/'+$delta,
dataType: 'json',
cache: false,
success: function(data){
},
error :function (data){

}

});*/
         
		//Delete Second Social/Club/Uni
		jQuery('#athlete_uni').on('click',function(){
			$(this).parents('.athlete_left').addClass('delete_athlete');
			$('body').append('<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">'+
			  '<div class="modal-dialog" role="document">'+
			    '<div class="modal-content">'+
			      '<div class="modal-header p-4" style="background: #000;">'+
			        '<h3 class="modal-title text-uppercase text-white" id="exampleModalLabel">DELETE Information</h3>'+
			        '<button type="button" class="close confirmation-close-button" data-dismiss="modal" aria-label="Close">'+
			          '<span aria-hidden="true">&times;</span>'+
			        '</button>'+
			     ' </div>'+
			      '<div class="modal-body px-4"><p>Are you sure you want to delete this information permanently?</p></div>'+
			      '<div class="modal-footer px-4">'+
			        '<button type="button" class="btn btn-lg text-white btn-default text-uppercase p-3" id="confirm-delete" style="background: #f76907; font-size: 17px;">YES, Delete</button>'+
			      '</div>'+
			    '</div>'+
			  '</div>'+
			'</div>');
			$('#confirmModal').modal('show');
			// if(confirm('Are you sure you want to delete this?')){
			// 	jQuery.ajax({
			// 		url : 'http://5ppsystem.com/delete/athlete/'+'abc'+'/'+'athlete_uni',
			// 		dataType: 'json',
			// 		cache: false,
			// 		success: function(data){
			// 		},
			// 		error :function (data){

			// 		}
			// 	});
			// jQuery(this).parents('.athlete_left').remove();
			// }
		});
		jQuery(document).on('click', '#confirm-delete', function(){

			jQuery.ajax({
				url : 'http://5ppsystem.com/delete/athlete/'+'abc'+'/'+'athlete_uni',
				dataType: 'json',
				cache: false,
				success: function(data){
					jQuery('.delete_athlete').remove();
					$('#confirmModal').modal('hide');
				},
				error :function (data){

				}
			});
		});
		jQuery(document).on('click', '#not-confirm', function(){
			jQuery('.delete_athlete').removeClass('delete_athlete');
		});
		//Delete Third Social/Club/Uni
		jQuery('#athlete_club').on('click',function(){
			if(confirm('Are you sure you want to delete this?')){
				jQuery.ajax({
					url : 'http://5ppsystem.com/delete/athlete/'+'abc'+'/'+'athlete_club',
					dataType: 'json',
					cache: false,
					success: function(data){
					},
					error :function (data){

					}
				});
				jQuery(this).parents('.athlete_left').remove();
			}
		});
		//Delete newly added org
		jQuery('.previous_delete').on('click',function(){
			jQuery('.previous_athlete').hide();
			jQuery('.popup_add_org').show();
			counter_click--;
		});
		jQuery('.last_delete').on('click',function(){
			jQuery('.last_athlete').hide();
			jQuery('.popup_add_org').show();
			counter_click--;
		});
		//Remove Add org button
		if(jQuery('.previous_athlete').css('display') != 'none' && jQuery('.last_athlete').css('display') != 'none' && jQuery('.previous_athlete').length && jQuery('.last_athlete').length){
			console.log("there");
			jQuery('.popup_add_org').hide();
		}
		//To CHANGE USER PASS
		jQuery('#save_pass').on('click',function(e){
					e.stopPropagation();
					e.preventDefault();
					var oldpass = jQuery('#edit-current-pass').val();
					var newpass = jQuery('#edit-newpass').val();
					var newpassconfirm = jQuery('#edit-newpassconfirm').val();
					jQuery.ajax({
						url : 'http://5ppsystem.com/changepass/'+oldpass+'/'+newpass+'/'+newpassconfirm,
						dataType: 'json',
						cache: false,
						success: function(data){
						
						},
						error :function (data){

						}
					});
					jQuery('span.changepassdiv-modal-close.spb_close').click();
		});
        //$('#cssmenu').prepend('<div id="menu-button">Menu</div>');
        $('#cssmenu #menu-button').on('click', function() {

            if ($(window).width() < 420) {
                $('body').toggleClass('open_left_side_mobile');
                $('body').removeClass('open_left_side');
            }
            else{
                $('body').toggleClass('open_left_side');
                $('body').removeClass('open_left_side_mobile');
            }

        });
        $( window ).resize(function() {
          if ($(window).width() < 420) {
            $('body').removeClass('open_left_side');
          }
          else{
            $('body').removeClass('open_left_side_mobile');
          }
        });
        /* 21-02-2020 */
//        jQuery('.remove_click').click(function(){
//            console.log('clickkk');
//            jQuery(document).find('.image-widget').find('button').submit();
//        })
        
        // jQuery(document).on('click','.add_pos_div .remove_pos',function(){
            // jQuery(this).parents('.add_pos_div ').find('.form-item').last().remove();
            // if(jQuery(this).parents('.add_pos_div ').find('.form-item').length < 2 ){
                // jQuery(this).parents('.add_pos_div ').find('.remove_pos').hide();
            // }else{
                // jQuery(this).parents('.add_pos_div').find('.remove_pos').show();
                
            // }
            
        // })
       
         /* 20-02-2020 */
        jQuery(document).on('click','.left_section .athlete_left h3 , .right_section .athlete_right h3',function(){
            jQuery(this).parent().find('.items_div').slideToggle();
            jQuery(this).find('.toggle_icon').find('i').toggleClass('hide');
        });
        
         // jQuery(document).on('click','.delete_icon',function(e){
            //alert('jj')
           // var delete_icon_popup = jQuery("<div class='modal' id='myModal'><div class='modal-dialog'><div class='modal-content'><div class='modal-header'><h4 class='modal-title'>Modal Heading</h4><button type='button' class='close' data-dismiss='modal'>&times;</button></div><div class='modal-body'>Modal body.</div><div class='modal-footer'><button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button></div></div></div></div>");
            
			//jQuery(this).append(delete_icon_popup);
            //jQuery('.region-content').append('delete_icon_popup');
        // });
        
            var athleteprofile_header = "<div class='main_header'><h1 style='margin-top: 10px;font-size:15px;margin-left: 20px;'><i class='fas fa-home' style='color: #f76907;margin-right: 5px;'></i><i class='fas fa-angle-right' style='font-weight:400;margin-right:5px;'></i><a href='/dashboard' class='edit_dash' style='margin-right:5px;font-weight: bold; color:#000'>Dashboard</a><i class='fas fa-angle-right' style='font-weight:400;margin-right:5px;'></i><a class='edit_dash' style='font-weight: bold; color:#000'>Atheltic Profile</a></h1><div class='edit_header' style='display:flex; padding:15px;background: #fffcd7;border: 1px solid grey;'><i class='far fa-chart-network edit_image'></i></i><h2 style='margin-top:0px;margin-bottom:0px;'><span style='font-size:13px;font-weight:600;'>Atheltic</span><br>Profile</h2></div></div>";
     
 
    jQuery(athleteprofile_header).insertBefore( ".bfssAthleteProfile .edit-form" );
        

        
      /*  jQuery(document).on('click','.add_org', function(){
            var skl_data = '<div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class="items_div"><div class="form-item js-form-item form-type-select js-form-type-select form-item-schoolname js-form-item-schoolname form-no-label form-group"><div class="select-wrapper"><select data-drupal-selector="edit-education" class="form-select form-control" id="edit-education" name="education"><option value="0">--- Highschol ---</option><option value="1">10"</option><option value="2">12"</option><option value="3">16"</option></select></div></div><div class="form-item js-form-item form-type-select js-form-type-select form-item-schoolname js-form-item-schoolname form-no-label form-group"><div class="select-wrapper"><select data-drupal-selector="edit-schoolname" class="form-select form-control" id="edit-schoolname" name="schoolname"><option value="0">--- Williams Highschol ---</option><option value="1">10"</option><option value="2">12"</option><option value="3">16"</option></select></div></div><div class="form-item js-form-item form-type-textfield js-form-type-textfield form-item-coach js-form-item-coach form-no-label form-group"><input data-drupal-selector="edit-coach" class="form-text form-control" type="text" id="edit-coach" name="coach" value="" size="60" maxlength="128" placeholder="Coaches Last Name (Optional)"></div><div class="form-item js-form-item form-type-textfield js-form-type-textfield form-item-sport js-form-item-sport form-no-label form-group"><input data-drupal-selector="edit-sport" class="form-text form-control" type="text" id="edit-sport" name="sport" value="" size="60" maxlength="128" placeholder="Sport"></div><div class="add_pos_div"><div class="form-item js-form-item form-type-textfield js-form-type-textfield form-item-position js-form-item-position form-no-label form-group"><input data-drupal-selector="edit-position" class="form-text form-control" type="text" id="edit-position" name="position" value="" size="60" maxlength="128" placeholder="Position"></div><a class="add_pos"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos" style="display: none;"><i class="fa fa-trash"></i>Remove Position</a></div><div class="form-item js-form-item form-type-textarea js-form-type-textarea form-item-stats js-form-item-stats form-no-label form-group"><div class="form-textarea-wrapper"><textarea data-drupal-selector="edit-stats" class="form-textarea form-control resize-vertical" id="edit-stats" name="stats" rows="5" cols="60"></textarea></div></div></div></div></div></div>';
            jQuery(this).parents('.athlete_school').append(skl_data);
        });*/
        var counter_click_addpos = 0;
		//FIRST POSITION
		if(jQuery('#edit-position2').css('display') != 'none'){
			if(jQuery('#edit-position3').css('display') != 'none'){
				jQuery('.add_pos_first').hide();
			}
			jQuery('.remove_pos_first').show();
		}
        jQuery(document).on('click','.add_pos_first', function(){ 
			if(jQuery('#edit-position2').css('display') == 'none'){
				jQuery('#edit-position2').show();
				jQuery('.remove_pos_first').show();
			}else if(jQuery('#edit-position3').css('display') == 'none'){
				jQuery('#edit-position3').show();
				jQuery(this).hide();
			}
		});
		jQuery(document).on('click','.remove_pos_first', function(){
			if(jQuery('#edit-position3').css('display') != 'none'){
				jQuery('#edit-position3').val("");
				jQuery('#edit-position3').hide();
				jQuery('.add_pos_first').show();
			}else if(jQuery('#edit-position2').css('display') != 'none'){
				jQuery('#edit-position2').val("");
				jQuery('#edit-position2').hide();
				jQuery(this).hide();
			}
		});
		//SECOND POSITION
		if(jQuery('#edit-position-12').css('display') != 'none'){
			if(jQuery('#edit-position-13').css('display') != 'none'){
				jQuery('.add_pos_second').hide();
			}
			jQuery('.remove_pos_second').show();
		}
		jQuery(document).on('click','.add_pos_second', function(){ 
			if(jQuery('#edit-position-12').css('display') == 'none'){
				jQuery('#edit-position-12').show();
				jQuery('.remove_pos_second').show();
			}else if(jQuery('#edit-position-13').css('display') == 'none'){
				jQuery('#edit-position-13').show();
				jQuery(this).hide();
			}
		});
		jQuery(document).on('click','.remove_pos_second', function(){
			if(jQuery('#edit-position-13').css('display') != 'none'){
				jQuery('#edit-position-13').val("");
				jQuery('#edit-position-13').hide();
				jQuery('.add_pos_second').show();
			}else if(jQuery('#edit-position-12').css('display') != 'none'){
				jQuery('#edit-position-12').val("");
				jQuery('#edit-position-12').hide();
				jQuery(this).hide();
			}
		});
		//THIRD POSITION
		if(jQuery('#edit-position-22').css('display') != 'none'){
			if(jQuery('#edit-position-23').css('display') != 'none'){
				jQuery('.add_pos_third').hide();
			}
			jQuery('.remove_pos_third').show();
		}
		jQuery(document).on('click','.add_pos_third', function(){ 
			if(jQuery('#edit-position-22').css('display') == 'none'){
				jQuery('#edit-position-22').show();
				jQuery('.remove_pos_third').show();
			}else if(jQuery('#edit-position-23').css('display') == 'none'){
				jQuery('#edit-position-23').show();
				jQuery(this).hide();
			}
		});
		jQuery(document).on('click','.remove_pos_third', function(){
			if(jQuery('#edit-position-23').css('display') != 'none'){
				jQuery('#edit-position-23').val("");
				jQuery('#edit-position-23').hide();
				jQuery('.add_pos_third').show();
			}else if(jQuery('#edit-position-22').css('display') != 'none'){
				jQuery('#edit-position-22').val("");
				jQuery('#edit-position-22').hide();
				jQuery(this).hide();
			}
		});
		//TO REMOVE ADD ANOTHER ORG LINK IF MAX LIMIT REACHED
		// console.log("display: " , jQuery('.bfssAthleteProfile .athlete_school.popup-athlete-school-hide.last_athlete').css('display'));
		if(jQuery('.bfssAthleteProfile .athlete_school.popup-athlete-school-hide.last_athlete').css('display') != 'none' && jQuery('.bfssAthleteProfile .athlete_school.popup-athlete-school-hide.previous_athlete').css('display') != 'none' && jQuery('.bfssAthleteProfile .athlete_school.popup-athlete-school-hide.last_athlete').length && jQuery('.bfssAthleteProfile .athlete_school.popup-athlete-school-hide.previous_athlete').length){
			console.log("here");
			jQuery('.popup_add_org').hide();
		}
		
		
            /*console.log("here: ", jQuery(this).parents('.add_pos_div_first ').find('.hidpos').length);
            if(jQuery(this).parents('.add_pos_div_first ').find('.hidpos').length == 2 ){
                console.log("if");
                if(counter_click_addpos == 0)
                {     jQuery('.remove_pos_first').css('display', 'inline-block');  
                    jQuery(this).parents('.add_pos_div_first ').children('.pos_hidden_first_1').addClass('showpos').find('input').css('display' , 'block');
                    counter_click_addpos = 1;
                    
                }
                else if(counter_click_addpos == 1){
                    jQuery(this).parents('.add_pos_div_first ').children('.pos_hidden_first_2').addClass('showpos').find('input').css('display' , 'block');
                    counter_click_addpos = 2;
                    jQuery(this).hide();
                }
            }else if(jQuery(this).parents('.add_pos_div_first ').find('.hidpos').length == 1){
                console.log("else");
                if(counter_click_addpos == 0){
                    jQuery('.remove_pos_first').css('display', 'inline-block');
                    jQuery(this).parents('.add_pos_div_first ').children('.pos_hidden_first_2').addClass('showpos').find('input').css('display' , 'block');
                    jQuery(this).hide();
                    counter_click_addpos = 1;
                }
            }
            
//            var add_pos = '<div class="form-item js-form-item form-type-textfield js-form-type-textfield form-item-position js-form-item-position form-no-label form-group"><input data-drupal-selector="edit-position" class="form-text form-control" type="text" id="edit-position" name="position" value="" size="60" maxlength="128" placeholder="Position"></div>';
//            jQuery(this).parents('.add_pos_div').append(add_pos);
            */
               
       /*  jQuery(document).on('click','.remove_pos_first', function(){
            if(jQuery(this).parents('.add_pos_div_first ').find('.hidpos').length == 2 ){
                if(counter_click_addpos == 2){
                    jQuery(this).parents('.add_pos_div_first ').children('.pos_hidden_first_2').removeClass('showpos').find('input').css('display' , 'none');
                        counter_click_addpos = 1;
                }else if(counter_click_addpos == 1){
                    jQuery(this).parents('.add_pos_div_first ').children('.pos_hidden_first_1').removeClass('showpos').find('input').css('display' , 'none');
                    counter_click_addpos = 0;
                    jQuery(this).css('display', 'none');
                }
             }else if(jQuery(this).parents('.add_pos_div_first ').find('.hidpos').length == 1){
                if(counter_click_addpos == 1){
                    jQuery(this).parents('.add_pos_div_first ').children('.pos_hidden_first_2').removeClass('showpos').find('input').css('display' , 'none');
                    counter_click_addpos = 0;
                    jQuery(this).css('display', 'none');
                }
            }
        }); */
        jQuery('#spb-imagepopup .imagepopup-modal').find('.spb-controls').children('.spb_close').html('submit').addClass('submit_btn');
        jQuery('#spb-changepassdiv .changepassdiv-modal').find('.spb-controls').children('.spb_close').hide();
        $('#changepassdiv').find('#save_pass').addClass('change_password_button');
    });
    var athelet = $(".dashboard .athelet_form_content").html();
    // console.log("athelet", athelet);
    // $('.dashboard #athelets-modal .modal-body').html('');
    // $('.dashboard #athelets-modal .modal-body').html(athelet);
	 // $(window).on('load',function(){
        // $('.dashboard #athelets-modal').modal({
            // backdrop: 'static',
            // keyboard: false
        // })
    // });
    $(document).on("click", "#athelets-modal .fa-trash-alt", function(){
        $(this).parents(".card").hide();
    });
    var position_html = $(".position_div").html();
    $(document).on("click", "#athelets-modal button.bg-transparent", function(){
        $(this).parents(".card").find(".actions").before(position_html);
    });
   
})(jQuery);
    var footer_content = jQuery("footer").html();
    jQuery('.tab-main-sec').after(footer_content);
    
     jQuery(".athlete_form_submit .form-submit").click(function(){
     $("#accordion").find('form').submit();
});
     var header_html = jQuery("<div class='main_header'><h1 style='margin-top: 10px;font-size:15px;margin-left: 20px;'><i class='fas fa-home' style='color: #f76907;margin-right: 5px;'></i><i class='fas fa-angle-right' style='font-weight:400;margin-right:5px;'></i><a href='/dashboard' class='edit_dash' style='margin-right:5px;font-weight: bold; color:#000'>Dashboard</a><i class='fas fa-angle-right' style='font-weight:400;margin-right:5px;'></i><a class='edit_dash' style='font-weight: bold; color:#000''>Edit Profile</a></h1><div class='edit_header' style='display:flex; padding:15px;background: #fffcd7;border: 1px solid grey;'><i class='fa fa-laptop edit_image' aria-hidden='true'></i><h2 style='margin-top:0px;margin-bottom:0px;'><span style='font-size:13px;font-weight:600;'>EDIT</span><br>Profile</h2></div></div>");
     jQuery('.edit-user .edit-form').before(header_html);

     var image_action = jQuery("<div class='edit_dropdown'><a class='drop'>Action<span class='down-arrow fa fa-angle-down'></span></a><ul class='dropdown-menu' style='padding:0'></ul></div>");
     jQuery('#edit-profile-class .field-group-format-toggler').after(image_action);
     
   jQuery('#edit-profile-class .edit_dropdown , .right_section .edit_dropdown .drop').click(function(){
//        var button_html =  jQuery(document).find('.image-widget').find('button[value="Remove"]').wrap('<div class="remove_bttn"></div>');
//        var full_html = jQuery('.remove_bttn').html();
//        console.log(full_html);
//        jQuery('#edit-profile-class .edit_dropdown ul.dropdown-menu,.right_section .edit_dropdown ul.dropdown-menu ').html('');
//        if(full_html != undefined){
           //jQuery('#edit-profile-class .edit_dropdown ul.dropdown-menu,.right_section .edit_dropdown ul.dropdown-menu ').append('<li>'+full_html+'</li>');
            
            //jQuery('#edit-profile-class .edit_dropdown ul.dropdown-menu ,.right_section .edit_dropdown ul.dropdown-menu').toggle();
            jQuery('.bfssAthleteProfile .right_section .image-widget .data ,  #edit-user-picture-wrapper .image-widget .data').toggle();
            jQuery('#edit-profile-class .edit_dropdown a span , .right_section .edit_dropdown a span').toggleClass('edit_open');
        //}
        

     });
     
//     var athleteprofile_header = jQuery("<div class='main_header'><h1 style='margin-top: 10px;font-size:15px;margin-left: 20px;'><i class='fas fa-home' style='color: #f76907;margin-right: 5px;'></i><i class='fas fa-angle-right' style='font-weight:400;margin-right:5px;'></i><span class='edit_dash' style='margin-right:5px;font-weight: bold;'>Dashboard</span><i class='fas fa-angle-right' style='font-weight:400;margin-right:5px;'></i><span class='edit_dash' style='font-weight: bold;'>Atheltic Profile</span></h1><div class='edit_header' style='display:flex; padding:15px;background: #fffcd7;border: 1px solid grey;'><i class='far fa-chart-network edit_image'></i></i><h2 style='margin-top:0px;margin-bottom:0px;'><span style='font-size:13px;font-weight:600;'>Atheltic</span><br>Profile</h2></div></div>");
//     
//    //jQuery('.bfssathleteprofile .dialog-off-canvas-main-canvas .edit-form').before(athleteprofile_header);
//    jQuery(athleteprofile_header).insertBefore( ".inner" );

    
    jQuery(document).on('click', '#edit-submit', function(){
        jQuery('.dashboard #athelets-modal').modal('hide');
    })
    
 jQuery('.popup_form_id-modal spb_overlay').css('background','black');
 
 var counter_click = 0;
 jQuery(document).on('click', '.bfssAthleteProfile .popup_add_org', function(){
	 console.log(counter_click);
     if(counter_click == 0){
        jQuery(this).siblings('.previous_athlete').css('display', 'block');
        counter_click++
    }else if(counter_click == 1){
        jQuery(this).siblings('.last_athlete').css('display', 'block');
		jQuery(this).hide();
        counter_click++
    }
 });
  jQuery(document).on('click', '.edit-parent .popup_add_org', function(){
	 console.log(counter_click);
     if(counter_click == 0){
        jQuery(this).siblings('.first-parent-guardian').css('display', 'block');
        counter_click++
    }else if(counter_click == 1){
        jQuery(this).siblings('.second-parent-guardian').css('display', 'block');
        counter_click++
    }else if(counter_click == 2){
    	jQuery(this).siblings('.third-parent-guardian').css('display', 'block');
        counter_click++
		jQuery(this).hide();
    }
 });
	


   



 // var counter_click_parent = 0;
 // jQuery(document).on('click', '.popup_add_parent', function(){
     // if(counter_click_parent == 0){
        // jQuery(this).siblings('.parent_1').css('display', 'block');
        // counter_click_parent++
    // }else if(counter_click_parent == 1){
        // jQuery(this).siblings('.last_athlete').css('display', 'block');
        // jQuery(this).hide();
        // counter_click_parent++
    // }
 // });
 jQuery(document).ready(function(){
	jQuery('.messages__wrapper').css('display','none'); 
 });
	var counter_click_1 = 0;
	jQuery(document).on('click', '.add_pos', function(){
		if(counter_click_1 == 0){
		   jQuery(this).parents('.add_pos_div').find('.form-item-position2').siblings('#edit-position2').css('display', 'block');
		   counter_click++
	   }else if(counter_click_1 == 1){
		   jQuery(this).parents('.add_pos_div').find('.form-item-position3').siblings('#edit-position3').css('display', 'block');
		   jQuery(this).hide();
		   counter_click++
	   }
	});

   $(document).ready(function(){
   $('.dropdown > a').click(function(e){
     e.preventDefault();
     e.stopPropagation();
     $(this).siblings('.dropdown-menu').toggleClass('open');
     $(this).find('.down-arrow').toggleClass('open-caret');

   });

   $(document).click(function(){
       $('.dropdown-menu').removeClass('open');
       $('.down-arrow').removeClass('open-caret');  
     });
   });
   
   function openForm() {
   document.getElementById("myForm").style.display = "block";
   }
   
   function closeForm() {
   document.getElementById("myForm").style.display = "none";
   }
   

      var acc = document.getElementsByClassName("accordion");
      var i;
      
      for (i = 0; i < acc.length; i++) {
       acc[i].addEventListener("click", function() {
         this.classList.toggle("active");
         var panel = this.nextElementSibling;
         if (panel.style.display === "none") {
           panel.style.display = "block";
         } else {
           panel.style.display = "none";
         }
       });
      }
      
//    jQuery('.select-wrapper select[data-drupal-selector="edit-user-type"]').on('change', function(){
//	console.log('here');
//        jQuery('.js-text-full').removeAttr('value')
//        });