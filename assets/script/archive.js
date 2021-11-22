import UIkit from "uikit";
import jQuery from "jquery";

(function ($) {
    "use strict";

    function getUrlParamsObject() {
        var urlParams = location.search.substring(1);
        if (urlParams === "") {
            return {};
        }
        return JSON.parse('{"' + decodeURI(urlParams).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"') + '"}');
    }

    let urlParams = getUrlParamsObject();

    let queueSelectors = [];

    function archiveGetPageContent(targetUrl) {
        $('body').addClass('archive-is-loading');
        $.ajax({
            url: targetUrl,
            context: document.body
        }).done(function (res) {
            parseAjaxResponse(res, targetUrl);
        });
    }

    $(document).on('click', '.has-ajax-filter .woocommerce-ordering a,' +
        '.has-ajax-filter .woocommerce-pagination a, ' +
        '.has-ajax-filter .woocommerce-widget-layered-nav a, ' +
        '.has-ajax-filter .widget_rating_filter a', function (e) {
        if ($('body').hasClass('archive-is-loading')) {
            queueSelectors.push(fullPath($(this)[0]));
            $(this).addClass('will-apply');
            return false;
        }

        let targetUrl = $(this).attr('href');
        archiveGetPageContent(targetUrl);
        e.preventDefault();
    });

    function fullPath(el) {
        let names = [];
        while (el.parentNode) {
            if (el.id) {
                names.unshift('#' + el.id);
                break;
            } else {
                if (el == el.ownerDocument.documentElement) names.unshift(el.tagName);
                else {
                    for (var c = 1, e = el; e.previousElementSibling; e = e.previousElementSibling, c++) ;
                    names.unshift(el.tagName + ":nth-child(" + c + ")");
                }
                el = el.parentNode;
            }
        }
        return names.join(" > ");
    }

    function parseAjaxResponse(response, targetUrl) {
        let pageContent = $($.parseHTML(response));
        $('.products-column, .elementor-widget-wc-archive-products').each(function (){
            console.log($(this));
            let productColumn = pageContent.find('.products-column').html();
            if($(this).hasClass('elementor-widget-wc-archive-products')){
                console.log('find');
                productColumn = pageContent.find('.elementor-widget-wc-archive-products').html();
            }
            $(this).html(productColumn).find('form').addClass('ajax-loaded');
        });
        $('.woocommerce-widget-layered-nav, .widget_rating_filter').each(function () {
            if ($(this).find('.woocommerce-widget-layered-nav-dropdown').length > 0) {
                return;
            }
            let widgetItem = pageContent.find('#' + $(this).attr('id'));
            if (widgetItem.length > 0) {
                $(this).html(widgetItem.html());
                $(this).find('form').addClass('ajax-loaded');
            } else {
                $(this).remove();
            }
        });
        $('body').removeClass('archive-is-loading');
        document.title = pageContent.filter('title').text();
        window.history.pushState({"html": response, "pageTitle": document.title}, "", targetUrl);

        let newUrlParams = getUrlParamsObject();
        let urlParamsKeys = Object.keys(urlParams);

        Object.keys(newUrlParams).forEach((item) => {
            if (urlParamsKeys.indexOf(item) === -1) {
                urlParamsKeys.push(item);
            }
        });


        urlParamsKeys.forEach((item) => {
            let inputSelector = $('form:not(.ajax-loaded) input[name="' + item + '"]');
            if (typeof newUrlParams[item] !== "undefined") {
                if (inputSelector.length <= 0) {
                    $.each(Object.keys(urlParams), function (index, childItem) {
                        let childItemElement = $('input[name="' + childItem + '"]');
                        if (childItemElement.length > 0) {
                            childItemElement.after('<input type="hidden" name="' + item + '" value="' + newUrlParams[item] + '" />');
                            return false;
                        }
                    });
                    inputSelector = $('input[name="' + item + '"]');
                }
                inputSelector.val(newUrlParams[item]);
            } else {
                inputSelector.remove();
            }
        });
        urlParams = newUrlParams;
        if (queueSelectors.length > 0) {
            $(queueSelectors[0]).removeClass('will-apply').click();
            queueSelectors.splice(0, 1);
        }
        queueSelectors.forEach((item) => {
            $(item).addClass('will-apply');
        });
    }


    window.onpopstate = function (e) {
        if (e.state) {
            archiveGetPageContent(window.location);
            document.title = e.state.pageTitle;
        }
    };
})(jQuery);