// (function ($, Drupal) {
//   Drupal.behaviors.myModuleBehavior = {
//     attach: function (context, settings) {
//       // $('input.myCustomBehavior', context).once('myCustomBehavior').each(function () {
//       //   // Apply the myCustomBehaviour effect to the elements only once.
//       // });
//       console.log("sfsdf");
//         jQuery(".form-item-venue-loaction input").val("");
// 		jQuery(".form-item-organizationtype option[value='']").attr('selected', true);
//     }
//   };
// })(jQuery, Drupal);

// (function($, Drupal) {
//   Drupal.behaviors.edit_form = {
//     attach: function (context, settings) {
//       //do your stuff here
//       //do use once for better results
//       	jQuery(document).once().on('change','#edit-venue-state',function(){
//          		 console.log("sfsdf");
// 						  jQuery(".form-item-venue-loaction input").val("");
// 						  jQuery(".form-item-organizationtype option[value='']").attr('selected', true);
//         });
      
// jQuery("#edit-venue-state").change(function(){
//   alert("The text has been changed.");
//    jQuery(".form-item-venue-loaction input").val("");
// 						  jQuery(".form-item-organizationtype option[value='']").attr('selected', true);
// });
//        console.log("sfsd1f");
//     }
//   }
// })(jQuery, Drupal);

// jQuery(document).ready(function(){
//  jQuery("#edit-venue-state").change(function(){
//   //alert("The text has been changed.");
//     jQuery(".form-item-venue-loaction input").val("");
// 	jQuery(".form-item-organizationtype option[value='']").attr('selected', true);
// 	});
// });
