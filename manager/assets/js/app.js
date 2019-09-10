// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

require('bootstrap');
require('@coreui/coreui');

const Centrifuge = require('centrifuge');
const toastr = require('toastr');

document.addEventListener('DOMContentLoaded', function () {
    const url = document.querySelector('meta[name=centrifugo-url]').getAttribute('content');
    const user = document.querySelector('meta[name=centrifugo-user]').getAttribute('content');
    const token = document.querySelector('meta[name=centrifugo-token]').getAttribute('content');
    const centrifuge = new Centrifuge(url);
    centrifuge.setToken(token);
    centrifuge.subscribe('alerts#' + user, function (message) {
        toastr.info(message.data.message);
    });
    centrifuge.connect();
});
