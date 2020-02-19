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
    });
    var athelet = $(".dashboard .athelet_form_content").html();
    console.log("athelet", athelet);
    $('.dashboard #athelets-modal .modal-body').html('');
    $('.dashboard #athelets-modal .modal-body').html(athelet);
	 $(window).on('load',function(){
        $('.dashboard #athelets-modal').modal('show');
    });
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