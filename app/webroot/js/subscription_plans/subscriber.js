"use strict";

;(function (ReactDOM, payload) {

    var settings = [{
        "node": React.createElement(PaymentPayture, { key: "1", payload: payload, selected: false })
    }, {
        "node": React.createElement(PaymentSeparator, { key: "2" })
    }, {
        "node": React.createElement(PaymentPaymaster, { key: "3", payload: payload, selected: false })
    }];

    ReactDOM.render(React.createElement(Receipt, { data: payload.receipt }), document.getElementById('receipt-container'));
    ReactDOM.render(React.createElement(PaymentTypesList, { payload: payload, settings: settings }), document.getElementById('payments-container'));

    PaymentDispatcher.register(function (eventPayload) {
        if (eventPayload.actionType === 'payment-type-selected') {
            if (eventPayload.selectedPaymentType == 'payment-payture') {
                settings = [{
                    "node": React.createElement(PaymentPayture, { key: "1", payload: payload, selected: true })
                }, {
                    "node": React.createElement(PaymentSeparator, { key: "2" })
                }, {
                    "node": React.createElement(PaymentPaymaster, { key: "3", payload: payload, selected: false })
                }];
            }
            if (eventPayload.selectedPaymentType == 'payment-paymaster') {
                settings = [{
                    "node": React.createElement(PaymentPayture, { key: "1", payload: payload, selected: false })
                }, {
                    "node": React.createElement(PaymentSeparator, { key: "2" })
                }, {
                    "node": React.createElement(PaymentPaymaster, { key: "3", payload: payload, selected: true })
                }];
            }
            ReactDOM.render(React.createElement(PaymentTypesList, { payload: payload, settings: settings }), document.getElementById('payments-container'));
        }
    });
})(ReactDOM, payload);