jQuery(window).on('elementor/frontend/init', () => {
    elementorFrontend.hooks.addAction('frontend/element_ready/Custom_Carousel_Widget.default', function ($scope, $) {
        carouselInit($scope.find('.swiper-container'));
    });
});
