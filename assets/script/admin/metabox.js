(function ($) {
    "use strict";
    let uploadControlStates = {};
    $(document).on('click', '.custom-metabox-menu-icon .remove-item', function () {
        $(this).parent().find('.item-preview').attr('src','').hide();
        $(this).parent().find('.image-upload-input').val('').trigger('change');
    });
    $(document).on('click', '.dc-upload-btn', function () {
        let mediaOptions = {
            frame: 'post',
            multiple: false
        };
        if(typeof uploadControlStates[$(this).attr('id')] !== 'undefined'){
            mediaOptions.state = uploadControlStates[$(this).attr('id')];
        }
        let mediaFrame = wp.media(mediaOptions);

        mediaFrame.on('close', () => {
            let selection = mediaFrame.state().get('selection');
            if (!selection.length) {
                $(this).parent().find('.image-upload-input').val('').trigger('change');
                $(this).next('.item-preview').attr('src', '').hide();
            }else{
                uploadControlStates[$(this).attr('id')] = mediaFrame.state();
            }
            let attachmentsList = [];
            selection.each((attachment) => {
                attachmentsList.push(attachment.id);
                $(this).next('.item-preview').attr('src', attachment.changed.url).show();
            });
            $(this).parent().find('.image-upload-input').val(attachmentsList.join(',')).trigger('change');
        });

        mediaFrame.open();
        return false;
    });

    $(document).on('change','.custom-metabox-item input, .custom-metabox-item select', function () {
        let conditionSelectors = $('[data-condition*="'+$(this).attr('name')+'"]');
        conditionSelectors.hide();
        conditionSelectors.each(function () {
            let jsonData = $(this).data('condition');
            $.each(jsonData, (sel, val) => {
                if ($('[name="'+sel+'"]').val() !== val) {
                    $(this).hide();
                    return false;
                } else {
                    $(this).show();
                }
            });
        });
    });

    $(document).ready(function () {
        $('.custom-metabox-item input, .custom-metabox-item select').trigger('change');
    });

    $(document).ajaxSuccess(function(event, xhr, settings) {
        if (typeof settings.data !== "undefined" && settings.data.indexOf('add-menu-item') !== -1) {
            $('.custom-metabox-item input, .custom-metabox-item select').trigger('change');
        }
    });
})(jQuery);