'use strict';

(function (ReactDOM, payload) {
    ReactDOM.render(React.createElement(Receipt, { data: payload.receipt }), document.getElementById('receipt-container'));
    ReactDOM.render(React.createElement(PaymentPayture, { payload: payload }), document.getElementById('payture-payment'));
    ReactDOM.render(React.createElement(PaymentPaymaster, { payload: payload }), document.getElementById('paymaster-payment'));
})(ReactDOM, payload);