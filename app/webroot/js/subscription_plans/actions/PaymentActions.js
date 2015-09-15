'use strict';

var PaymentDispatcher = new Flux.Dispatcher();
var PaymentActions = {
    selectPaymentType: function selectPaymentType(paymentTypeName) {
        PaymentDispatcher.dispatch({
            actionType: 'payment-type-selected',
            selectedPaymentType: paymentTypeName
        });
    }
};