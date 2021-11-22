import UIkit from "uikit";
import jQuery from "jquery";

(function ($) {
    "use strict";

    $(document).on('click', '.product-add-to-cart', function (e) {
        let productID = $(this).attr('data-product-id') ?? false;
        let quantity = $(this).attr('data-quantity') ?? 1;
        let variableID = $(this).attr('data-variable-id') ?? false;

        if(isNaN(productID) || productID === false || isNaN(quantity)){
            return;
        }

        let requestArgs = {
            productID: productID,
            quantity: quantity
        };
        if(!isNaN(variableID) && variableID !== false && variableID !== ''){
            requestArgs.variationID = variableID;
        }
        $(this).addClass('item-processing');
        API.send('Cart', 'addItem', requestArgs).then((response) => {
            if (typeof response.data.status !== 'undefined' && response.data.status) {
                UIkit.notification({
                    message: response.data.data.message,
                    status: 'success',
                    pos: 'bottom-left'
                });

                if ( typeof response.data.data.redirect === 'string') {
                    window.location = response.data.data.redirect;
                }
                if ( typeof response.data.data.fragments === 'object') {
                    $.each( response.data.data.fragments, function( key ) {
                        $( key )
                            .addClass( 'updating' )
                            .fadeTo( '400', '0.6' );
                    });

                    $.each( response.data.data.fragments, function( key, value ) {
                        $( key ).replaceWith( value );
                        $( key ).stop( true ).css( 'opacity', '1' );
                    });
                }


            }
        }).finally(() => {
            $(this).removeClass('item-processing');
        });

        e.preventDefault();
    });


})(jQuery);
