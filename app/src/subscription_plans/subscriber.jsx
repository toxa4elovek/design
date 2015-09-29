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
        },
        {
            "node": <PaymentSeparator key="4" />
        },
        {
            "node": <PaymentWire key="5" payload={payload} selected={false} />
        }/*,
        {
            "node": <PaymentSeparator key="6" />
        },
        {
            "node": <PaymentAdmin key="7" payload={payload} />
        }*/
    ];
    ReactDOM.render(
        <Receipt data={payload.receipt}/>,
        document.getElementById('receipt-container')
    );
    ReactDOM.render(
        <PaymentTypesList payload={payload} settings={settings}/>,
        document.getElementById('payments-container')
    );
    ReactDOM.render(
        <FundBalanceInput payload={payload}/>,
        document.getElementById('fund-balance-container')
    );

    PaymentDispatcher.register(function(eventPayload) {
        if (eventPayload.actionType === 'fund-balance-input-updated') {
            let total = 0;
            payload.receipt.forEach(function (row) {
                if(row.name == "Пополнение счёта") {
                    row.value = eventPayload.newValue;
                }
                total += row.value;
            });
            payload.total = total;
            ReactDOM.render(
                <Receipt data={payload.receipt}/>,
                document.getElementById('receipt-container')
            );
            ReactDOM.render(
                <PaymentTypesList payload={payload} settings={settings}/>,
                document.getElementById('payments-container')
            );
            const data = {
                "projectId": payload.projectId,
                "updatedReceipt": payload.receipt
            };
            $.post('/subscription_plans/updateReceipt.json', data);
        }
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
                    },
                    {
                        "node": <PaymentSeparator key="4" />
                    },
                    {
                        "node": <PaymentWire key="5" payload={payload} selected={false} />
                    },
                    {
                        "node": <PaymentSeparator key="6" />
                    },
                    {
                        "node": <PaymentAdmin key="7" payload={payload} />
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
                    },
                    {
                        "node": <PaymentSeparator key="4" />
                    },
                    {
                        "node": <PaymentWire key="5" payload={payload} selected={false} />
                    },
                    {
                        "node": <PaymentSeparator key="6" />
                    },
                    {
                        "node": <PaymentAdmin key="7" payload={payload} />
                    }
                ];
            }
            if(eventPayload.selectedPaymentType == 'payment-wire') {
                settings = [
                    {
                        "node": <PaymentPayture key="1" payload={payload} selected={false} />
                    },
                    {
                        "node": <PaymentSeparator key="2" />
                    },
                    {
                        "node": <PaymentPaymaster key="3" payload={payload} selected={false} />
                    },
                    {
                        "node": <PaymentSeparator key="4" />
                    },
                    {
                        "node": <PaymentWire key="5" payload={payload} selected={true} />
                    },
                    {
                        "node": <PaymentSeparator key="6" />
                    },
                    {
                        "node": <PaymentAdmin key="7" payload={payload} />
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