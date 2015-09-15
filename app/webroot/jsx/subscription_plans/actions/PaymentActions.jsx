const PaymentDispatcher = new Flux.Dispatcher();
const PaymentActions = {
    selectPaymentType: function(paymentTypeName) {
        PaymentDispatcher.dispatch({
            actionType: 'payment-type-selected',
            selectedPaymentType: paymentTypeName
        });
    }
};