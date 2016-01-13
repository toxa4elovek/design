const PaymentDispatcher = new Flux.Dispatcher();
const PaymentActions = {
    selectPaymentType: function(paymentTypeName) {
        PaymentDispatcher.dispatch({
            actionType: 'payment-type-selected',
            selectedPaymentType: paymentTypeName
        });
    },
    updateFundBalanceInput: function(value) {
        PaymentDispatcher.dispatch({
            actionType: 'fund-balance-input-updated',
            newValue: value
        });
    },
    submitNewReceipt: function(value) {
        PaymentDispatcher.dispatch({
            actionType: 'submit-news-receipt',
            currentValue: value
        });
    }
};