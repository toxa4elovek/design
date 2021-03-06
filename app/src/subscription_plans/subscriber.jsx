;(function (ReactDOM, payload) {
  if($('.copy-link').length) {
    const clipboardLink = new Clipboard('.copy-link');
  }
  let settings = [
    {
      'node': <PaymentPayture key='1' payload={payload} selected={false} />
    },
    {
      'node': <PaymentSeparator key='2' />
    },
    /*{
      'node': <PaymentPaymaster key='3' payload={payload} selected={false} />
    },
    {
      'node': <PaymentSeparator key='4' />
    },*/
    {
      'node': <PaymentWire key='5' payload={payload} selected={false} />
    }
  ]

  ReactDOM.render(
    <Receipt data={payload.receipt} />,
    document.getElementById('receipt-container')
  )
  ReactDOM.render(
    <PaymentTypesList payload={payload} settings={settings} />,
    document.getElementById('payments-container')
  )
  ReactDOM.render(
    <FundBalanceInput payload={payload} />,
    document.getElementById('fund-balance-container')
  )
  if (document.getElementById('phone-number-container')) {
    ReactDOM.render(
      <PhoneNumberInput phoneNumber='7 911 123 45 67' />,
      document.getElementById('phone-number-container')
    )

    $(document).on('click', '#payments-container .button, #payments-container .pm-item', function () {
      const phoneInput = $('.phone-number-input')
      const value = phoneInput.val()
      if ((value.trim() === '') || (value.trim() === '7 911 123 45 67')) {
        alert('Пожалуйста, укажите свой телефон для связи!')
        return false
      }
      return true
    })
  }

  PaymentDispatcher.register(function (eventPayload) {
    if (eventPayload.actionType === 'submit-new-phone') {
      const data = {
        'newPhone': eventPayload.value,
        'projectId': payload.projectId
      }
      $.post('/subscription_plans/updatePhone.json', data)
    }
    if (eventPayload.actionType === 'fund-balance-input-updated') {
      let total = 0
      payload.receipt.forEach(function (row) {
        if (row.name == 'Пополнение счёта') {
          row.value = eventPayload.newValue
        }
        total += row.value
      })
      payload.total = total
      ReactDOM.render(
        <FundBalanceInput payload={payload} />,
        document.getElementById('fund-balance-container')
      )
      ReactDOM.render(
        <Receipt data={payload.receipt} />,
        document.getElementById('receipt-container')
      )
      ReactDOM.render(
        <PaymentTypesList payload={payload} settings={settings} />,
        document.getElementById('payments-container')
      )
    }
    if (eventPayload.actionType === 'phone-number-input-updated') {
      ReactDOM.render(
        <PhoneNumberInput phoneNumber={eventPayload.value} />,
        document.getElementById('phone-number-container')
      )
    }
    if (eventPayload.actionType === 'submit-news-receipt') {
      let total = 0
      payload.receipt.forEach(function (row) {
        if (row.name == 'Пополнение счёта') {
          row.value = eventPayload.currentValue
        }
        total += row.value
      })
      payload.total = total
      const data = {
        'newFundValue': eventPayload.currentValue,
        'projectId': payload.projectId,
        'updatedReceipt': payload.receipt
      }
      $.post('/subscription_plans/updateReceipt.json', data, function (response) {
        if (response.fundBalance != eventPayload.currentValue) {
          PaymentActions.updateFundBalanceInput(parseInt(response.fundBalance))
          ReactDOM.render(
            <FundBalanceInput payload={payload} />,
            document.getElementById('fund-balance-container')
          )
        } else {
          ReactDOM.render(
            <FundBalanceInput payload={payload} />,
            document.getElementById('fund-balance-container')
          )
        }
      })
    }
    if (eventPayload.actionType === 'payment-type-selected') {
      if (eventPayload.selectedPaymentType == 'payment-payture') {
        settings = [
          {
            'node': <PaymentPayture key="1" payload={payload} selected={true} />
          },
          {
            'node': <PaymentSeparator key="2" />
          },
          /*{
            'node': <PaymentPaymaster key="3" payload={payload} selected={false} />
          },
          {
            'node': <PaymentSeparator key="4" />
          },*/
          {
            'node': <PaymentWire key="5" payload={payload} selected={false} />
          } /*,
                    {
                        "node": <PaymentSeparator key="6" />
                    },
                    {
                        "node": <PaymentAdmin key="7" payload={payload} />
                    }*/
        ]
      }
      if (eventPayload.selectedPaymentType == 'payment-paymaster') {
        settings = [
          {
            'node': <PaymentPayture key="1" payload={payload} selected={false} />
          },
          {
            'node': <PaymentSeparator key="2" />
          },
          /*{
            'node': <PaymentPaymaster key="3" payload={payload} selected={true} />
          },
          {
            'node': <PaymentSeparator key="4" />
          },*/
          {
            'node': <PaymentWire key="5" payload={payload} selected={false} />
          } /*,
                    {
                        "node": <PaymentSeparator key="6" />
                    },
                    {
                        "node": <PaymentAdmin key="7" payload={payload} />
                    }*/
        ]
      }
      if (eventPayload.selectedPaymentType == 'payment-wire') {
        settings = [
          {
            'node': <PaymentPayture key="1" payload={payload} selected={false} />
          },
          {
            'node': <PaymentSeparator key="2" />
          },
          /*{
            'node': <PaymentPaymaster key="3" payload={payload} selected={false} />
          },
          {
            'node': <PaymentSeparator key="4" />
          },*/
          {
            'node': <PaymentWire key="5" payload={payload} selected={true} />
          } /*,
                    {
                        "node": <PaymentSeparator key="6" />
                    },
                    {
                        "node": <PaymentAdmin key="7" payload={payload} />
                    }*/
        ]
      }
      ReactDOM.render(
        <PaymentTypesList payload={payload} settings={settings} />,
        document.getElementById('payments-container')
      )
    }
  })
})(ReactDOM, payload)
