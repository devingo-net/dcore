(function ($) {
    "use strict";

    $(document).on('click', '.blog_posts-widget.paginate-type-number.ajax-paginate .page-numbers a', function () {
        let currentPageNumber = parseInt($(this).parents('.page-numbers').find('.current').text());
        let pageNumber = 0;

        let widgetID = $(this).parents('[data-id]').data('id');
        let widgetParent = $(this).parents('.blog_posts-widget');

        if ($(this).hasClass('next')) {
            pageNumber = currentPageNumber + 1;
        } else if ($(this).hasClass('prev')) {
            pageNumber = currentPageNumber - 1;
        } else if (!isNaN($(this).text())) {
            pageNumber = parseInt($(this).text());
        }
        let taxonomyArgs = false;

        if (widgetParent.find('[name="taxonomy_query"]').length > 0) {
            taxonomyArgs = widgetParent.find('[name="taxonomy_query"]').val();
        }

        widgetParent.addClass('widget-loading');
        API.send('Blog_Posts', 'getPagedPosts', {
            pageID: themeScriptParams.pageID,
            widgetID: widgetID,
            page: pageNumber,
            tax: taxonomyArgs
        }).then((response) => {
            if (typeof response.data.status !== 'undefined' && response.data.status) {
                widgetParent.find('.widget-pagination').replaceWith(response.data.data.pagination);
                widgetParent.find('.grid-content-area').html('');
                if (Array.isArray(response.data.data.content)) {
                    response.data.data.content.forEach((item) => {
                        widgetParent.find('.grid-content-area').append('<div class="grid-item">' + item + '</div>');
                    });
                }
            }
        }).finally(() => {
            widgetParent.removeClass('widget-loading');
        });

        return false;
    });

    $(document).on('click', '.blog_posts-widget .widget-load-more .load-more-btn', function () {
        let pageNumber = $(this).attr('data-page') ?? 2;
        let widgetID = $(this).parents('[data-id]').data('id');
        let widgetParent = $(this).parents('.blog_posts-widget');
        if(!isNaN(pageNumber)){
            pageNumber = parseInt(pageNumber);
        }

        widgetParent.addClass('widget-loading');

        let taxonomyArgs = false;

        if (widgetParent.find('[name="taxonomy_query"]').length > 0) {
            taxonomyArgs = widgetParent.find('[name="taxonomy_query"]').val();
        }
        API.send('Blog_Posts', 'getPagedPosts', {
            pageID: themeScriptParams.pageID,
            widgetID: widgetID,
            page: pageNumber,
            tax: taxonomyArgs
        }).then((response) => {
            if (typeof response.data.status !== 'undefined' && response.data.status) {
                if (Array.isArray(response.data.data.content) && response.data.data.content.length > 0) {
                    response.data.data.content.forEach((item) => {
                        widgetParent.find('.grid-content-area').append('<div class="grid-item">' + item + '</div>');
                    });
                } else {
                    $(this).remove();
                }
                pageNumber++;
                $(this).attr('data-page', pageNumber);
            }
        }).finally(() => {
            widgetParent.removeClass('widget-loading');
        });
    });

    $(document).on('load_more', '.blog_posts-widget .widget-auto-load', function () {
        let pageNumber = $(this).attr('data-page') ?? 2;
        let widgetID = $(this).parents('[data-id]').data('id');
        let widgetParent = $(this).parents('.blog_posts-widget');
        if(!isNaN(pageNumber)){
            pageNumber = parseInt(pageNumber);
        }

        widgetParent.addClass('widget-loading');
        let taxonomyArgs = false;

        if (widgetParent.find('[name="taxonomy_query"]').length > 0) {
            taxonomyArgs = widgetParent.find('[name="taxonomy_query"]').val();
        }

        API.send('Blog_Posts', 'getPagedPosts', {
            pageID: themeScriptParams.pageID,
            widgetID: widgetID,
            page: pageNumber,
            tax: taxonomyArgs
        }).then((response) => {
            if (typeof response.data.status !== 'undefined' && response.data.status) {
                if (Array.isArray(response.data.data.content) && response.data.data.content.length > 0) {
                    response.data.data.content.forEach((item) => {
                        widgetParent.find('.grid-content-area').append('<div class="grid-item">' + item + '</div>');
                    });
                } else {
                    $(this).remove();
                }
                pageNumber++;
                $(this).attr('data-page', pageNumber);
            }
        }).finally(() => {
            widgetParent.removeClass('widget-loading');
        });
    });

    $(window).on('scroll',function () {
        $('.blog_posts-widget .widget-auto-load').each(function () {
            let widgetParent = $(this).parents('.blog_posts-widget');
            if($(window).scrollTop() + $(window).height() > $(this).offset().top){
                if(!widgetParent.hasClass('widget-loading')) {
                    $(this).trigger('load_more');
                }
            }
        });
    });

})(jQuery);