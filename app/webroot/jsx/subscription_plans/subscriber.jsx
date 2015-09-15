;(function (ReactDOM, payload) {

    let settings = [
        {
            "node": <PaymentPayture key="1" payload={payload} selected={false} />
        },
        {
            "node": <PaymentSeparator key="2" />
        },
        {
            "node": <PaymentPaymaster key="3" payload={payload} selected={false} />
        }
    ];

    ReactDOM.render(
        <Receipt data={payload.receipt}/>,
        document.getElementById('receipt-container')
    );
    ReactDOM.render(
        <PaymentTypesList payload={payload} settings={settings}/>,
        document.getElementById('payments-container')
    );

    PaymentDispatcher.register(function(eventPayload) {
        if (eventPayload.actionType === 'payment-type-selected') {
            if(eventPayload.selectedPaymentType == 'payment-payture') {
                settings = [
                    {
                        "node": <PaymentPayture key="1" payload={payload} selected={true} />
                    },
                    {
                        "node": <PaymentSeparator key="2" />
                    },
                    {
                        "node": <PaymentPaymaster key="3" payload={payload} selected={false} />
                    }
                ];
            }
            if(eventPayload.selectedPaymentType == 'payment-paymaster') {
                settings = [
                    {
                        "node": <PaymentPayture key="1" payload={payload} selected={false} />
                    },
                    {
                        "node": <PaymentSeparator key="2" />
                    },
                    {
                        "node": <PaymentPaymaster key="3" payload={payload} selected={true} />
                    }
                ];
            }
            ReactDOM.render(
                <PaymentTypesList payload={payload} settings={settings}/>,
                document.getElementById('payments-container')
            );
        }
    });

}) (ReactDOM, payload);