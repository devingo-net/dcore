jQuery( window ).on( 'elementor/frontend/init', () => {
   elementorFrontend.hooks.addAction( 'frontend/element_ready/Image_Slider_Widget.default', function($scope, $){
       carouselInit($scope.find('.swiper-container'));
    });
} );
