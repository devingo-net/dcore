(function ($) {
    "use strict";

    $(document).on('click','.comment-attachments .remove-item',function () {
        let confirmAlert = confirm(themeScriptParams.confirmAlert);
        if(confirmAlert) {
            $(this).addClass('disabled');
            let commentID = $(this).data('comment-id') ?? '0';
            let attachID = $(this).data('attach-id') ?? '0';

            $.post(themeScriptParams.ajaxURL, {
                'action': 'CommentRemoveAttachment',
                'attach': attachID,
                'comment': commentID
            }, (response) => {
                if (typeof response.success !== 'undefined') {
                    if (response.success) {
                        $(this).parents('li').remove();
                    } else {
                        alert(response.message);
                    }

                }
            });
        }
    });

})(jQuery);