jQuery(window).on('elementor/frontend/init', () => {
    elementorFrontend.hooks.addAction('frontend/element_ready/Categories_Widget.default', function ($scope, $) {
        carouselInit($scope.find('.swiper-container'));
    });
});
