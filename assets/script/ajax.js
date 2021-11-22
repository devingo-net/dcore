import jquery from 'jquery';

let API = {};

API.send = async function (module, method, params) {
    jquery.ajaxSetup({
        url: themeScriptParams.ajaxURL,
        global: false,
        type: "POST",
        error: function (x, status, error) {
            let message = '[' + error + '] ' + themeScriptParams.ajaxError;
            if (typeof x?.responseJSON?.data?.data !== 'undefined') {
                message = x.responseJSON.data.data ?? themeScriptParams.ajaxError;
            }
            UIkit.notification({
                message: message,
                status: 'danger',
                pos: 'bottom-left'
            });
        }
    });

    return jquery.ajax({
        data: {
            action: 'themeApiAjax',
            module: module,
            method: method,
            nonce: themeScriptParams.ajaxNonce,
            ...params
        }
    });
}

export default API;