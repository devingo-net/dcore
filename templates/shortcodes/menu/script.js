(function ($) {
    "use strict";
    $(document).on('mouseenter','.menu-elementor-width-boxed',function (e) {
        let child = $(this).children('.sub-menu');
        if(child.offset().left < 0){
            child.css({'left': '20px' });
        }
        setTimeout(()=>{
            let rightOffset = $(window).innerWidth() - (child.offset().left + child.innerWidth());
            if(rightOffset < 0){
                child.css({'right': '20px', 'width': 'auto' });
            }
        },500)
    });
    $('.menu-elementor-width-boxed').trigger('mouseenter');

})(jQuery);