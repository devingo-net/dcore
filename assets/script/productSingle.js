import * as FilePond from 'filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import UIkit from "uikit";

(function ($) {
    "use strict";

    $(document).on('productTabsInit', '.wc-tabs-wrapper, .woocommerce-tabs', function () {
        let hash = window.location.hash;
        let url = window.location.href;
        let $tabs = $(this).find('.wc-tabs, ul.tabs').first();

        $tabs.find('li').removeClass('uk-active');

        if (hash.toLowerCase().indexOf('comment-') >= 0 || hash === '#reviews' || hash === '#tab-reviews') {
            $tabs.find('li.reviews_tab').addClass('uk-active');
        } else if (url.indexOf('comment-page-') > 0 || url.indexOf('cpage=') > 0) {
            $tabs.find('li.reviews_tab').addClass('uk-active');
        } else if (hash === '#tab-additional_information') {
            $tabs.find('li.additional_information_tab').addClass('uk-active');
        } else {
            $tabs.find('li:first').addClass('uk-active');
        }
    });

    $(document).ready(function () {
        $('.wc-tabs-wrapper, .woocommerce-tabs').trigger('productTabsInit');

        let productGalleryThumbsElement = $('body:not([class*="elementor-page-"]) .product-gallery-thumbnails-swiper');
        let productGalleryThumbs = null;
        if (productGalleryThumbsElement.length > 0) {
            productGalleryThumbs = carouselInit(productGalleryThumbsElement, {
                watchSlidesVisibility: true,
                watchSlidesProgress: true
            });
        }

        let productGalleryElement = $('body:not([class*="elementor-page-"]) .product-gallery-swiper');

        if (productGalleryElement.length > 0) {
            let productGallery = carouselInit(productGalleryElement, {thumbs: {swiper: productGalleryThumbs}});
            if ($('body').hasClass('product-gallery-zoom')) {
                productGallery.on('slideChangeTransitionStart', function () {
                    let currentSlideImage = $(productGallery.$el[0]).find('.swiper-slide-active .woocommerce-product-gallery__image');
                    if (currentSlideImage.find('.zoomImg').length <= 0) {
                        currentSlideImage.zoom({
                            url: currentSlideImage.find('img').attr('data-large_image')
                        });
                    }
                });
            }
        }

        $.fn.wc_variations_image_update = function (variation) {
            var $form = this,
                $product = $form.closest('.product'),
                $product_gallery = $product.find('.product-gallery-swiper'),
                $gallery_nav = $product.find('.product-gallery-thumbnails-swiper'),
                $gallery_img = $gallery_nav.find('.swiper-slide:first-of-type img'),
                $product_img_wrap = $product_gallery
                    .find('.woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder')
                    .eq(0),
                $product_img = $product_img_wrap.find('.wp-post-image'),
                $product_link = $product_img_wrap.find('a').eq(0);

            if (variation && variation.image && variation.image.src && variation.image.src.length > 1) {
                // See if the gallery has an image with the same original src as the image we want to switch to.
                var galleryHasImage = $gallery_nav.find('li img[data-o_src="' + variation.image.gallery_thumbnail_src + '"]').length > 0;

                // If the gallery has the image, reset the images. We'll scroll to the correct one.
                if (galleryHasImage) {
                    $form.wc_variations_image_reset();
                }

                // See if gallery has a matching image we can slide to.
                var slideToImage = $gallery_nav.find('li img[src="' + variation.image.gallery_thumbnail_src + '"]');

                if (slideToImage.length > 0) {
                    slideToImage.trigger('click');
                    $form.attr('current-image', variation.image_id);
                    window.setTimeout(function () {
                        $(window).trigger('resize');
                        $product_gallery.trigger('woocommerce_gallery_init_zoom');
                    }, 20);
                    return;
                }


                $product_gallery[0].swiper.slideTo(0);
                if ($gallery_nav.length > 0) {
                    $gallery_nav[0].swiper.slideTo(0);
                }

                $product_img.wc_set_variation_attr('src', variation.image.src);
                $product_img.wc_set_variation_attr('height', variation.image.src_h);
                $product_img.wc_set_variation_attr('width', variation.image.src_w);
                $product_img.wc_set_variation_attr('srcset', variation.image.srcset);
                $product_img.wc_set_variation_attr('sizes', variation.image.sizes);
                $product_img.wc_set_variation_attr('title', variation.image.title);
                $product_img.wc_set_variation_attr('data-caption', variation.image.caption);
                $product_img.wc_set_variation_attr('alt', variation.image.alt);
                $product_img.wc_set_variation_attr('data-src', variation.image.full_src);
                $product_img.wc_set_variation_attr('data-large_image', variation.image.full_src);
                $product_img.wc_set_variation_attr('data-large_image_width', variation.image.full_src_w);
                $product_img.wc_set_variation_attr('data-large_image_height', variation.image.full_src_h);
                $product_img_wrap.wc_set_variation_attr('data-thumb', variation.image.src);

                $gallery_img.wc_set_variation_attr('src', variation.image.gallery_thumbnail_src);
                $gallery_img.wc_set_variation_attr('srcset', variation.image.srcset);
                $gallery_img.wc_set_variation_attr('sizes', variation.image.sizes);
                $gallery_img.wc_set_variation_attr('title', variation.image.title);
                $gallery_img.wc_set_variation_attr('data-caption', variation.image.caption);
                $gallery_img.wc_set_variation_attr('alt', variation.image.alt);
                $gallery_img.wc_set_variation_attr('data-src', variation.image.full_src);
                $gallery_img.wc_set_variation_attr('data-large_image', variation.image.full_src);
                $gallery_img.wc_set_variation_attr('data-large_image_width', variation.image.full_src_w);
                $gallery_img.wc_set_variation_attr('data-large_image_height', variation.image.full_src_h);

                $product_link.wc_set_variation_attr('href', variation.image.full_src);
            } else {
                $form.wc_variations_image_reset();
            }

            window.setTimeout(function () {
                $(window).trigger('resize');
                $form.wc_maybe_trigger_slide_position_reset(variation);
                $product_gallery.trigger('woocommerce_gallery_init_zoom');
            }, 20);
        };

        $.fn.wc_variations_image_reset = function () {
            var $form = this,
                $product = $form.closest('.product'),
                $product_gallery = $product.find('.product-gallery-swiper'),
                $gallery_nav = $product.find('.product-gallery-thumbnails-swiper'),
                $gallery_img = $gallery_nav.find('.swiper-slide:first-of-type img'),
                $product_img_wrap = $product_gallery
                    .find('.woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder')
                    .eq(0),
                $product_img = $product_img_wrap.find('.wp-post-image'),
                $product_link = $product_img_wrap.find('a').eq(0);

            $product_img.wc_reset_variation_attr('src');
            $product_img.wc_reset_variation_attr('width');
            $product_img.wc_reset_variation_attr('height');
            $product_img.wc_reset_variation_attr('srcset');
            $product_img.wc_reset_variation_attr('sizes');
            $product_img.wc_reset_variation_attr('title');
            $product_img.wc_reset_variation_attr('data-caption');
            $product_img.wc_reset_variation_attr('alt');
            $product_img.wc_reset_variation_attr('data-src');
            $product_img.wc_reset_variation_attr('data-large_image');
            $product_img.wc_reset_variation_attr('data-large_image_width');
            $product_img.wc_reset_variation_attr('data-large_image_height');
            $product_img_wrap.wc_reset_variation_attr('data-thumb');

            $gallery_img.wc_reset_variation_attr('src');
            $gallery_img.wc_reset_variation_attr('srcset');
            $gallery_img.wc_reset_variation_attr('sizes');
            $gallery_img.wc_reset_variation_attr('title');
            $gallery_img.wc_reset_variation_attr('data-caption');
            $gallery_img.wc_reset_variation_attr('alt');
            $gallery_img.wc_reset_variation_attr('data-src');
            $gallery_img.wc_reset_variation_attr('data-large_image');
            $gallery_img.wc_reset_variation_attr('data-large_image_width');
            $gallery_img.wc_reset_variation_attr('data-large_image_height');

            $product_link.wc_reset_variation_attr('href');
        };


        $('input.chips-input').each(function () {
            $(this).after('<div class="chips-box">' +
                '<div class="insert-box">' +
                '<div class="uk-inline"><a class="add-item uk-form-icon uk-form-icon-flip" href="javascript:void(0);"><i class="fas fa-plus"></i></a><input type="text" name="' + $(this).attr('data-name') + '[]" placeholder="' + $(this).attr('data-label') + '" class="uk-input text-field">' +
                '</div>' +
                '</div>' +
                '<ul class="chip-items"></ul>' +
                '</div>');
        });

        let commentAttachment = $('#comment-attachments input[type="file"]');
        if (commentAttachment.length > 0) {
            FilePond.registerPlugin(FilePondPluginFileValidateSize);
            FilePond.registerPlugin(FilePondPluginFileValidateType);
            FilePond.setOptions({
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg'],
                allowMultiple: true,
                server: {
                    process: {
                        url: themeScriptParams.ajaxURL,
                        method: 'POST',
                        ondata: (formData) => {
                            formData.append('action', 'CommentUploadAttachments');
                            formData.append('nonce', themeScriptParams.ajaxNonce);
                            return formData;
                        }
                    },
                    fetch: null,
                    revert: null
                },
                allowFileSizeValidation: true,
                maxFileSize: '2MB',
                credits: false
            });
            FilePond.create(commentAttachment[0]);
        }
    });


    $(document).on("keypress", ".chips-box .text-field", function (e) {
        if (e.which === 13) {
            $(this).parents('.insert-box').find('.add-item').click();
            return false;
        }
    });
    $(document).on("click", ".chips-box .insert-box .add-item", function () {
        let parent = $(this).parents('.chips-box');
        let textInput = parent.find('.text-field').val();
        if (textInput !== "" && parent.find("[value='" + textInput + "']").length <= 0) {
            let div = document.createElement("div");
            div.innerHTML = textInput;
            parent.find('.chip-items').prepend('<li><span>' + div.innerText + '</span><button class="remove-item" type="button"><i class="far fa-times"></i></button><input type="hidden" name="' + parent.prev('.chips-input').attr('data-name') + '[]" value="' + textInput + '"></li>');
            parent.find('.text-field').val("");
        }
    });
    $(document).on("click", ".chips-box .chip-items .remove-item", function () {
        $(this).parents('li').remove();
    });

    function exLikes() {
        $('.comment-likes-outer .like-button').each(function () {
            let id = $(this).attr('data-comment');
            let cookie_name = "comments_like";
            let cookie = getCookie(cookie_name);
            if (cookie === "") {
                cookie = [];
            } else {
                cookie = JSON.parse(cookie);
            }
            let rate_exists = jQuery.inArray(id, cookie);
            if (rate_exists >= 0) {
                $(this).addClass('disabled');
            }
        });
    }

    $(document).on("click", ".comment-likes-outer .like-button", function () {
        let thisItem = $(this);
        let cookie_name = "comments_like";
        let cookie = getCookie(cookie_name);
        if (cookie === "") {
            cookie = [];
        } else {
            cookie = JSON.parse(cookie);
        }
        let rate_exists = jQuery.inArray(thisItem.attr('data-comment'), cookie);
        if (rate_exists === -1) {
            if (cookie.length === 0) {
                cookie[0] = thisItem.attr('data-comment');
                cookie = JSON.stringify(cookie);
                setCookie(cookie_name, cookie, 30, false);
            } else {
                cookie[cookie.length] = thisItem.attr('data-comment');
                cookie = JSON.stringify(cookie);
                setCookie(cookie_name, cookie, 30, false);
            }
            if (!thisItem.hasClass('disabled')) {
                thisItem.addClass('disabled');
                $.post(themeScriptParams.ajaxURL, {
                    'action': 'commentRates',
                    'commentID': thisItem.attr('data-comment'),
                    'rateType': $(this).hasClass('like') ? 'like' : 'dislike'
                }, function (response) {
                    if (response.status) {
                        thisItem.find('.count').text(response.count);
                        exLikes();
                    }
                    $('.like-button[data-comment="' + thisItem.attr('data-comment') + '"]').addClass('disabled');
                });
            }
        } else {
            thisItem.addClass('disabled');
        }
    });
})(jQuery);

