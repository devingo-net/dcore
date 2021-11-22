import UIkit from "uikit";
import jQuery from "jquery";

(function ($) {
    "use strict";

    $(document).ready(function () {
        $('[href*="#action-favorite-exists-"]').each(function () {
            $(this).addClass('post-in-favorites');
        });
    });

    $(document).on('click', '.post-toggle-favorite,[href*="#action-favorite-"]', function (e) {
        let postID = $(this).attr('data-post-id') ?? false;
        let urlData;
        if (!$(this).hasClass('post-toggle-favorite')) {
            urlData = $(this).attr('href').replaceAll('#action-favorite-exists-', '').replaceAll('#action-favorite-notex-', '');
            postID = parseInt(urlData);
        }
        let isRemove = $(this).hasClass('post-in-favorites');

        if (isNaN(postID) || postID === false) {
            return;
        }

        $(this).addClass('item-processing');

        API.send('Favorite', isRemove ? 'removeItem' : 'addItem', {
            itemID: postID
        }).then((response) => {
            if (typeof response.data.status !== 'undefined' && response.data.status) {
                if (isRemove) {
                    $(this).removeClass('post-in-favorites');
                    UIkit.notification({
                        message: response.data.data,
                        status: 'warning',
                        pos: 'bottom-left'
                    });
                    if ($(this).parents('.user-favorites-list').length > 0) {
                        $(this).parents('tr').remove();
                    }
                } else {
                    $(this).addClass('post-in-favorites');
                    UIkit.notification({
                        message: response.data.data,
                        status: 'success',
                        pos: 'bottom-left'
                    });
                }

            }
        }).finally(() => {
            $(this).removeClass('item-processing');
        });

        e.preventDefault();
    });


})(jQuery);
