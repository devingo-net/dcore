import API from "./ajax";

(function ($) {
    $(document).on('click', '.product-quick-view-button', function (e) {
        let productID = $(this).attr('data-product-id') ?? '';
        $(this).addClass('item-processing');
        API.send('Quick_View', 'show', {
            product: productID
        }).then((response) => {
            if (response.success) {
                let quickViewModal = $('#product-quick-view-modal');
                if (quickViewModal.length > 0) {
                    quickViewModal.find('.modal-content').html(response?.data?.data);
                    UIkit.modal(quickViewModal).show();
                }
            }
        }).finally(() => {
            $(this).removeClass('item-processing');
        })
        e.preventDefault();
    });
})(jQuery);