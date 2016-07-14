'use strict';

var PaymentDispatcher = new Flux.Dispatcher();
var PaymentActions = {
    selectPaymentType: function selectPaymentType(paymentTypeName) {
        PaymentDispatcher.dispatch({
            actionType: 'payment-type-selected',
            selectedPaymentType: paymentTypeName
        });
    },
    updateFundBalanceInput: function updateFundBalanceInput(value) {
        PaymentDispatcher.dispatch({
            actionType: 'fund-balance-input-updated',
            newValue: value
        });
    },
    submitNewReceipt: function submitNewReceipt(value) {
        PaymentDispatcher.dispatch({
            actionType: 'submit-news-receipt',
            currentValue: value
        });
    },
    updatePhoneNumberInput: function updatePhoneNumberInput(value) {
        PaymentDispatcher.dispatch({
            actionType: 'phone-number-input-updated',
            newValue: value
        });
    },
    submitNewPhoneNumber: function submitNewPhoneNumber(value) {
        PaymentDispatcher.dispatch({
            actionType: 'submit-new-phone',
            value: value
        });
    }
};