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
    }
};