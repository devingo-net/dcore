(function ($) {
    $(document).ready(function () {
        $('.input-has-map').each(function () {
            if ($(this).parents('.woocommerce-billing-fields__field-wrapper').length > 0) {
                $(this).after('<div id="' + $(this).data('map') + '"></div>');

                $(document).on('change', '#billing_city', function () {
                    if ($(this).val() !== null) {
                        let searchUrl = 'https://nominatim.openstreetmap.org/search?format=json&state=' + encodeURI($('#billing_state option:selected').text()) + '&city=' + encodeURI($('#billing_city option:selected').text());
                        $.get(searchUrl, (response) => {
                            if (response.length > 0) {
                                marker.setLatLng(new L.LatLng(response[0].lat, response[0].lon), {draggable: 'true'});
                                map.setView(marker.getLatLng(), map.getZoom());
                            }
                        });
                    }
                });
            }

            if ($(this).parents('.woocommerce-shipping-fields__field-wrapper').length > 0) {
                $(this).after('<div id="' + $(this).data('map') + '"></div>');

                $(document).on('change', '#shipping_city', function () {
                    if ($(this).val() !== null) {
                        let searchUrl = 'https://nominatim.openstreetmap.org/search?format=json&state=' + encodeURI($('#shipping_state option:selected').text()) + '&city=' + encodeURI($('#shipping_city option:selected').text());
                        $.get(searchUrl, (response) => {
                            if (response.length > 0) {
                                marker.setLatLng(new L.LatLng(response[0].lat, response[0].lon), {draggable: 'true'});
                                map.setView(marker.getLatLng(), map.getZoom());
                            }
                        });
                    }
                });
            }

            let mapValue = $(this).val();

            if (mapValue.replaceAll(' ', '') !== '') {
                mapValue = mapValue.split(',');
            }


            if (mapValue.length !== 2) {
                mapValue = [35.688, -668.608];
                $('#billing_city').trigger('change');
                $('#shipping_city').trigger('change');
            }

            let map = L.map($(this).data('map'), {
                center: mapValue,
                zoom: 14
            });
            L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            let marker = new L.marker(mapValue, {
                draggable: true,
                autoPan: true
            }).addTo(map);

            map.on('click', (e) => {
                marker.setLatLng(e.latlng, {draggable: 'true'});
            });
            marker.on('move', () => {
                $(this).val(marker.getLatLng().lat + ',' + marker.getLatLng().lng).trigger('keydown');
            });
        });
    });
})(jQuery);