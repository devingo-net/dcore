(function ($) {
    wp.customize.section('theme-settings-styles-inputs', function (section) {
        section.expanded.bind(function (isExpanded) {
            if (isExpanded) {
                wp.customize.previewer.previewUrl.set(themeCustomizeParams.customize_inputs_style);
            }
        });
    });
    wp.customize.section('theme-settings-styles-buttons', function (section) {
        section.expanded.bind(function (isExpanded) {
            if (isExpanded) {
                wp.customize.previewer.previewUrl.set(themeCustomizeParams.customize_buttons_style);
            }
        });
    });

    wp.customize.bind('ready', function () {
        $('.customize-control-tab ~ li:not(.customize-control-tab)').hide();
        $(document).on('click', '.customize-control-tab .customize-tab-header', function () {
            let parent = $(this).parents('.customize-control-tab');
            let toshow = parent.attr('data-show') ?? 'default';

            if (parent.hasClass('show')) {
                parent.nextUntil('.customize-control-tab').slideUp('fast');
                parent.removeClass('show');
            } else {
                if (toshow === 'default') {
                    parent.nextUntil('.customize-control-tab', 'li:not([data-mod])').slideDown('fast');
                } else {
                    parent.nextUntil('.customize-control-tab', 'li[data-mod="' + toshow + '"]').slideDown('fast');
                }

                parent.addClass('show');
            }
        });

        $('.customize-control[id*="_hasmod_"]').each(function () {
            let mod = $(this).attr('id').split("_hasmod_")[1];
            $(this).attr('data-mod', mod).addClass('has-mode').hide();
        });

        $(document).on('click', '.customize-control-tab .customize-tab-mods .mod-item[data-target]:not(.active)', function () {
            $(this).parents('.customize-tab-mods').find('.mod-item').removeClass('active');
            $(this).addClass('active').parents('.customize-control-tab').attr('data-show', $(this).data('target'));

            $(this).parents('.customize-control-tab').find('.customize-tab-icon').trigger('click');

            if (!$(this).parents('.customize-control-tab').hasClass('show')) {
                $(this).parents('.customize-control-tab').find('.customize-tab-icon').trigger('click');
            }

        });


        $('.wp-color-field').wpColorPicker({
            change: function (event, ui) {
                $(event.target).val(ui.color.toString()).trigger('change');
            },
        });

    });


})(jQuery);