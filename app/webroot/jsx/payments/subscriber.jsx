(function (ReactDOM, payload) {
    ReactDOM.render(
        <Receipt data={payload.receipt}/>,
        document.getElementById('receipt-container')
    );
    ReactDOM.render(
        <PaymentPayture payload={payload} />,
        document.getElementById('payture-payment')
    );
    ReactDOM.render(
        <PaymentPaymaster payload={payload}/>,
        document.getElementById('paymaster-payment')
    );
}) (ReactDOM, payload);