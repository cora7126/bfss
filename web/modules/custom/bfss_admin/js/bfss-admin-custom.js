jQuery(document).ready(function() {

	console.log("here1");
	jQuery("button.ui-button.ui-corner-all.ui-widget.ui-button-icon-only.ui-dialog-titlebar-close").click(function(){
		location.reload();
		console.log("here1");
	});
	

	jQuery( "#sortable_faqs" ).sortable({
		 update: function( event, ui ) {
                 var list = new Array();
                  jQuery('#sortable_faqs').find('.ui-state-default').each(function(){
                       var nid= jQuery(this).data('nid');    
                       list.push(nid);
                  });
                 // console.log(list);
                 		
                  var myJSON = JSON.stringify(list);
                  var user_role = jQuery("input[name='faqs_role']").val();
                   var x = list.toString();
                  //	if(confirm('Are you sure you want to delete this?')){
						jQuery.ajax({
							url : 'http://5ppsystem.com/faq-nids/'+myJSON+'/'+user_role,
							dataType: 'json',
							cache: false,
							success: function(data){
								console.log(data);
								// if(data){
								// 	location.reload();	
								// }	
							},
							error :function (data){
								//console.log(data);
							}
						});
						//jQuery(this).parents('.athlete_left').remove();
					//}

            }
	});
});