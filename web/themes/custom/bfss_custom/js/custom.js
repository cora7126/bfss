(function($) {
    $(document).ready(function() {
        //$('#cssmenu').prepend('<div id="menu-button">Menu</div>');
        $('#cssmenu #menu-button').on('click', function() {
            var menu = $(this).next('ul');
            if (menu.hasClass('open')) {
                menu.removeClass('open');
            } else {
                menu.addClass('open');
            }
        });
        
        /* 21-02-2020 */
//        jQuery('.remove_click').click(function(){
//            console.log('clickkk');
//            jQuery(document).find('.image-widget').find('button').submit();
//        })
        
        jQuery(document).on('click','.add_pos_div .remove_pos',function(){
            jQuery(this).parents('.add_pos_div ').find('.form-item').last().remove();
            if(jQuery(this).parents('.add_pos_div ').find('.form-item').length < 2 ){
                jQuery(this).parents('.add_pos_div ').find('.remove_pos').hide();
            }else{
                jQuery(this).parents('.add_pos_div').find('.remove_pos').show();
                
            }
            
        })
        
        
         /* 20-02-2020 */
        jQuery(document).on('click','.left_section .athlete_left h3 , .right_section .athlete_right h3',function(){
            jQuery(this).parent().find('.items_div').slideToggle();
            jQuery(this).find('.toggle_icon').find('i').toggleClass('hide');
        });
        
         jQuery(document).on('click','.delete_icon',function(e){
             //if(e.target !== e.currentTarget) ;
            jQuery(this).parents('.athlete_left').remove();
        });
        
            var athleteprofile_header = "<div class='main_header'><h1 style='margin-top: 10px;font-size:15px;margin-left: 20px;'><i class='fas fa-home' style='color: #f76907;margin-right: 5px;'></i><i class='fas fa-angle-right' style='font-weight:400;margin-right:5px;'></i><a href='/dashboard' class='edit_dash' style='margin-right:5px;font-weight: bold;'>Dashboard</a><i class='fas fa-angle-right' style='font-weight:400;margin-right:5px;'></i><a class='edit_dash' style='font-weight: bold;'>Atheltic Profile</a></h1><div class='edit_header' style='display:flex; padding:15px;background: #fffcd7;border: 1px solid grey;'><i class='far fa-chart-network edit_image'></i></i><h2 style='margin-top:0px;margin-bottom:0px;'><span style='font-size:13px;font-weight:600;'>Atheltic</span><br>Profile</h2></div></div>";
     
 
    jQuery(athleteprofile_header).insertBefore( ".bfssAthleteProfile .edit-form" );
        

        
        jQuery(document).on('click','.add_org', function(){
            var skl_data = '<div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class="items_div"><div class="form-item js-form-item form-type-select js-form-type-select form-item-schoolname js-form-item-schoolname form-no-label form-group"><div class="select-wrapper"><select data-drupal-selector="edit-education" class="form-select form-control" id="edit-education" name="education"><option value="0">--- Highschol ---</option><option value="1">10"</option><option value="2">12"</option><option value="3">16"</option></select></div></div><div class="form-item js-form-item form-type-select js-form-type-select form-item-schoolname js-form-item-schoolname form-no-label form-group"><div class="select-wrapper"><select data-drupal-selector="edit-schoolname" class="form-select form-control" id="edit-schoolname" name="schoolname"><option value="0">--- Williams Highschol ---</option><option value="1">10"</option><option value="2">12"</option><option value="3">16"</option></select></div></div><div class="form-item js-form-item form-type-textfield js-form-type-textfield form-item-coach js-form-item-coach form-no-label form-group"><input data-drupal-selector="edit-coach" class="form-text form-control" type="text" id="edit-coach" name="coach" value="" size="60" maxlength="128" placeholder="Coaches Last Name (Optional)"></div><div class="form-item js-form-item form-type-textfield js-form-type-textfield form-item-sport js-form-item-sport form-no-label form-group"><input data-drupal-selector="edit-sport" class="form-text form-control" type="text" id="edit-sport" name="sport" value="" size="60" maxlength="128" placeholder="Sport"></div><div class="add_pos_div"><div class="form-item js-form-item form-type-textfield js-form-type-textfield form-item-position js-form-item-position form-no-label form-group"><input data-drupal-selector="edit-position" class="form-text form-control" type="text" id="edit-position" name="position" value="" size="60" maxlength="128" placeholder="Position"></div><a class="add_pos"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos" style="display: none;"><i class="fa fa-trash"></i>Remove Position</a></div><div class="form-item js-form-item form-type-textarea js-form-type-textarea form-item-stats js-form-item-stats form-no-label form-group"><div class="form-textarea-wrapper"><textarea data-drupal-selector="edit-stats" class="form-textarea form-control resize-vertical" id="edit-stats" name="stats" rows="5" cols="60"></textarea></div></div></div></div></div></div>';
            jQuery(this).parents('.athlete_school').append(skl_data);
        });
        
        jQuery(document).on('click','.add_pos', function(){
            var add_pos = '<div class="form-item js-form-item form-type-textfield js-form-type-textfield form-item-position js-form-item-position form-no-label form-group"><input data-drupal-selector="edit-position" class="form-text form-control" type="text" id="edit-position" name="position" value="" size="60" maxlength="128" placeholder="Position"></div>';
            jQuery(this).parents('.add_pos_div').append(add_pos);
            jQuery(this).parents('.add_pos_div').find('.remove_pos').show();
            
        });
        
        
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
     var header_html = jQuery("<div class='main_header'><h1 style='margin-top: 10px;font-size:15px;margin-left: 20px;'><i class='fas fa-home' style='color: #f76907;margin-right: 5px;'></i><i class='fas fa-angle-right' style='font-weight:400;margin-right:5px;'></i><a href='/dashboard' class='edit_dash' style='margin-right:5px;font-weight: bold;'>Dashboard</a><i class='fas fa-angle-right' style='font-weight:400;margin-right:5px;'></i><a class='edit_dash' style='font-weight: bold;'>Edit Profile</a></h1><div class='edit_header' style='display:flex; padding:15px;background: #fffcd7;border: 1px solid grey;'><i class='fa fa-laptop edit_image' aria-hidden='true'></i><h2 style='margin-top:0px;margin-bottom:0px;'><span style='font-size:13px;font-weight:600;'>EDIT</span><br>Profile</h2></div></div>");
     jQuery('#edit-profile-class .main-container').before(header_html);

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
            jQuery('.right_section .image-widget .data ,  #edit-user-picture-wrapper .image-widget .data').toggle();
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
 jQuery(document).on('click', '.popup_add_org', function(){
     if(counter_click == 0){
        jQuery(this).parents('#popup_form_id').find('.previous_athlete').css('display', 'block');
        counter_click++
    }else if(counter_click == 1){
        jQuery(this).parents('#popup_form_id').find('.last_athlete').css('display', 'block');
        jQuery(this).hide();
        counter_click++
    }
 });