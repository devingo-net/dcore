import UIkit from 'uikit';
import Icons from 'uikit/dist/js/uikit-icons';
import API from "./ajax";
import jQuery from 'jquery';

require('./productSingle');
require('./compare');
require('./favorites');
require('./addToCart');
require('./checkout');
require('./quickView');
require('./archive');

import Swiper, {
    Navigation,
    Pagination,
    Scrollbar,
    Autoplay,
    EffectCoverflow,
    EffectCube,
    EffectFade,
    EffectFlip,
    Virtual,
    Thumbs
} from 'swiper';


Swiper.use([Navigation, Pagination, Scrollbar, Autoplay, EffectCoverflow, EffectCube, EffectFade, EffectFlip, Virtual, Thumbs]);

UIkit.use(Icons);
window.UIkit = UIkit;
window.API = API;

(function ($) {
    "use strict";

    $(document).on('mouseenter', '.tab-event-hover .uk-tab>li', function (e) {
        UIkit.tab($(this).parents('.uk-tab')[0]).show($(this).index());
    });
})(jQuery);

window.carouselInit = function (element, replaceOptions = null) {

    if (element.length === 0) {
        return;
    }
    let carouselOptions = element.data('theme-carousel') ?? false;

    function checkArrow(e){
        if (typeof carouselOptions.navigation === 'undefined' || carouselOptions.navigation === false){
            $(e.el).find('.slider-navs').hide().addClass('hide-button');
            return null;
        }
        if(typeof carouselOptions.breakpoints === 'undefined'){
            return null;
        }
        Object.keys(carouselOptions.breakpoints).forEach((item)=>{
            if(e.size >= parseInt(item)){
                if (typeof carouselOptions.breakpoints[item].navigation !== 'undefined' && carouselOptions.breakpoints[item].navigation ===false){
                    $(e.el).find('.slider-navs').hide().addClass('hide-button');
                }else{
                    $(e.el).find('.slider-navs').show().removeClass('hide-button');
                }
            }
        });
    }
    function checkDots(e){
        if (typeof carouselOptions.pagination === 'undefined' || carouselOptions.pagination === false){
            $(e.el).find('.swiper-pagination').hide().addClass('hide-button');
            return null;
        }
        if(typeof carouselOptions.breakpoints === 'undefined'){
            return null;
        }

        Object.keys(carouselOptions.breakpoints).forEach((item)=>{
            if(e.size >= parseInt(item)){
                if (typeof carouselOptions.breakpoints[item].pagination !== 'undefined' && carouselOptions.breakpoints[item].pagination ===false){
                    $(e.el).find('.swiper-pagination').hide().addClass('hide-button');
                }else{
                    $(e.el).find('.swiper-pagination').show().removeClass('hide-button');
                }
            }
        });
    }

    if (typeof carouselOptions === 'object') {
        if ($('body').hasClass('rtl')) {
            carouselOptions.rtl = true;
        }
        if (replaceOptions !== null && typeof replaceOptions === 'object') {
            Object.keys(replaceOptions).forEach((optionKey) => {
                carouselOptions[optionKey] = replaceOptions[optionKey];
            });
        }
        carouselOptions.on = {
            init: function(e) {
                checkArrow(e);
                checkDots(e);
            },
            resize: function (e) {
                checkDots(e);
                checkArrow(e);
            }
        };

        return new Swiper(element[0], carouselOptions);
    }
}

window.setCookie = function(cname, cvalue, exdays, htime) {
    let d = new Date();
    if (!htime) {
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    } else {
        d.setTime(d.getTime() + (exdays * 60 * 60 * 1000));
    }
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

window.getCookie = function(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}