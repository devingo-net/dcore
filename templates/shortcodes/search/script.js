(function ($) {
    "use strict";
    $(document).ready(function () {
        const template = wp.template('theme-widget-ajax-response');
        $(document).on('ajaxAction', '[class*="-search-widget"].search-ajax-method form', function () {
            let searchText = $(this).find('.search-field').val();
            let searchType = $(this).find('.search-post-type')?.val() ?? '';
            let searchCat = $(this).find('.search-filter')?.val() ?? 'all';
            let searchCount = $(this).find('.search-count')?.val() ?? 10;
            let searchResult = $(this).find('.search-result-type')?.val() ?? 'both';


            let parent = $(this).parents('.search-ajax-method');

            parent.addClass('user-is-searching');

            API.send('Search', 'getResult', {
                search: searchText,
                type: searchType,
                category: searchCat,
                count: searchCount,
                result: searchResult
            }).then((response) => {
                if (typeof response.data.status !== 'undefined' && response.data.status) {
                    if (parent.find('.search-result').length === 0) {
                        parent.append('<ul class="search-result uk-card uk-card-default"></ul>');
                    } else {
                        parent.find('.search-result').html('');
                    }
                    response.data.data.items.forEach((item) => {
                        parent.find('.search-result').append(template(item));
                    });
                }
            }).finally(() => {
                $(this).parents('.search-ajax-method').removeClass('user-is-searching');
            });
        });

        let searchWidgetTimer;
        $(document).on('keyup', '[class*="-search-widget"].search-ajax-method form .search-field', function () {
            $(this).parents('.search-ajax-method').addClass('user-is-typing');
            clearTimeout(searchWidgetTimer);
            searchWidgetTimer = setTimeout(() => {
                $(this).parents('.search-ajax-method').removeClass('user-is-typing');
                if ($(this).val().length > 2) {
                    $(this).parents('form').trigger('ajaxAction');
                }
            }, 1000);
        });

        $(document).on('change', '[class*="-search-widget"].search-ajax-method form .search-filter', function () {
            $(this).parents('form').trigger('ajaxAction');
        });
    });

})(jQuery);