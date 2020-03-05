
jQuery(document).ready(function(){
      jQuery(".regular").slick({
        dots: true,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3
      });
      
      var ium = jQuery("#instagram_username").val();
      
    jQuery.instagramFeed({
        'username': ium,
        'container': "#instagram-feed1",
        'display_profile': false,
        'styling': false,
        'items': 6,
        //'display_gallery': false
    });

     setTimeout(function() {
                    jQuery(".instagram_gallery").slick({
                                     	dots: true,
									    infinite: true,
									    slidesToShow: 3,
									    slidesToScroll: 3
                                    });
                  }, 2000);
});
      


           