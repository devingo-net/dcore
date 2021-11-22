import UIkit from "uikit";
import jQuery from "jquery";

(function ($) {
    "use strict";

    $(document).ready(function () {
        $('[href*="#action-compare-"]').each(function () {
            let productID = $(this).attr('href').replaceAll('#action-compare-', '');
            $(this).addClass('compare').attr('data-product_id',productID);
        });
    });

    $(document).on('click', '[href*="#action-compare-"]', function (e) {
        e.preventDefault();
    });


})(jQuery);
