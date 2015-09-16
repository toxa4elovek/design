"use strict";

;(function (ReactDOM, payload) {

    var settings = [{
        "node": React.createElement(PaymentPayture, { key: "1", payload: payload, selected: false })
    }, {
        "node": React.createElement(PaymentSeparator, { key: "2" })
    }, {
        "node": React.createElement(PaymentPaymaster, { key: "3", payload: payload, selected: false })
    }, {
        "node": React.createElement(PaymentSeparator, { key: "4" })
    }, {
        "node": React.createElement(PaymentWire, { key: "5", payload: payload, selected: false })
    }, {
        "node": React.createElement(PaymentSeparator, { key: "6" })
    }, {
        "node": React.createElement(PaymentAdmin, { key: "7", payload: payload })
    }];
    ReactDOM.render(React.createElement(Receipt, { data: payload.receipt }), document.getElementById('receipt-container'));
    ReactDOM.render(React.createElement(PaymentTypesList, { payload: payload, settings: settings }), document.getElementById('payments-container'));
    ReactDOM.render(React.createElement(FundBalanceInput, { payload: payload }), document.getElementById('fund-balance-container'));

    PaymentDispatcher.register(function (eventPayload) {
        if (eventPayload.actionType === 'fund-balance-input-updated') {
            var total = 0;
            payload.receipt.forEach(function (row) {
                if (row.name == "Пополнение счёта") {
                    row.value = eventPayload.newValue;
                }
                total += row.value;
            });
            payload.total = total;
            ReactDOM.render(React.createElement(Receipt, { data: payload.receipt }), document.getElementById('receipt-container'));
            ReactDOM.render(React.createElement(PaymentTypesList, { payload: payload, settings: settings }), document.getElementById('payments-container'));
            var data = {
                "projectId": payload.projectId,
                "updatedReceipt": payload.receipt
            };
            $.post('/subscription_plans/updateReceipt.json', data);
        }
        if (eventPayload.actionType === 'payment-type-selected') {
            if (eventPayload.selectedPaymentType == 'payment-payture') {
                settings = [{
                    "node": React.createElement(PaymentPayture, { key: "1", payload: payload, selected: true })
                }, {
                    "node": React.createElement(PaymentSeparator, { key: "2" })
                }, {
                    "node": React.createElement(PaymentPaymaster, { key: "3", payload: payload, selected: false })
                }, {
                    "node": React.createElement(PaymentSeparator, { key: "4" })
                }, {
                    "node": React.createElement(PaymentWire, { key: "5", payload: payload, selected: false })
                }, {
                    "node": React.createElement(PaymentSeparator, { key: "6" })
                }, {
                    "node": React.createElement(PaymentAdmin, { key: "7", payload: payload })
                }];
            }
            if (eventPayload.selectedPaymentType == 'payment-paymaster') {
                settings = [{
                    "node": React.createElement(PaymentPayture, { key: "1", payload: payload, selected: false })
                }, {
                    "node": React.createElement(PaymentSeparator, { key: "2" })
                }, {
                    "node": React.createElement(PaymentPaymaster, { key: "3", payload: payload, selected: true })
                }, {
                    "node": React.createElement(PaymentSeparator, { key: "4" })
                }, {
                    "node": React.createElement(PaymentWire, { key: "5", payload: payload, selected: false })
                }, {
                    "node": React.createElement(PaymentSeparator, { key: "6" })
                }, {
                    "node": React.createElement(PaymentAdmin, { key: "7", payload: payload })
                }];
            }
            if (eventPayload.selectedPaymentType == 'payment-wire') {
                settings = [{
                    "node": React.createElement(PaymentPayture, { key: "1", payload: payload, selected: false })
                }, {
                    "node": React.createElement(PaymentSeparator, { key: "2" })
                }, {
                    "node": React.createElement(PaymentPaymaster, { key: "3", payload: payload, selected: false })
                }, {
                    "node": React.createElement(PaymentSeparator, { key: "4" })
                }, {
                    "node": React.createElement(PaymentWire, { key: "5", payload: payload, selected: true })
                }, {
                    "node": React.createElement(PaymentSeparator, { key: "6" })
                }, {
                    "node": React.createElement(PaymentAdmin, { key: "7", payload: payload })
                }];
            }
            ReactDOM.render(React.createElement(PaymentTypesList, { payload: payload, settings: settings }), document.getElementById('payments-container'));
        }
    });
})(ReactDOM, payload);