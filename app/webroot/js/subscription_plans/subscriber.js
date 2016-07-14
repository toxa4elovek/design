'use strict';

;(function (ReactDOM, payload) {
  var settings = [{
    'node': React.createElement(PaymentPayture, { key: '1', payload: payload, selected: false })
  }, {
    'node': React.createElement(PaymentSeparator, { key: '2' })
  }, {
    'node': React.createElement(PaymentPaymaster, { key: '3', payload: payload, selected: false })
  }, {
    'node': React.createElement(PaymentSeparator, { key: '4' })
  }, {
    'node': React.createElement(PaymentWire, { key: '5', payload: payload, selected: false })
  }];

  ReactDOM.render(React.createElement(Receipt, { data: payload.receipt }), document.getElementById('receipt-container'));
  ReactDOM.render(React.createElement(PaymentTypesList, { payload: payload, settings: settings }), document.getElementById('payments-container'));
  ReactDOM.render(React.createElement(FundBalanceInput, { payload: payload }), document.getElementById('fund-balance-container'));
  if (document.getElementById('phone-number-container')) {
    ReactDOM.render(React.createElement(PhoneNumberInput, { phoneNumber: '7 911 123 45 67' }), document.getElementById('phone-number-container'));

    $(document).on('click', '#payments-container .button, #payments-container .pm-item', function () {
      var phoneInput = $('.phone-number-input');
      var value = phoneInput.val();
      if (value.trim() === '' || value.trim() === '7 911 123 45 67') {
        alert('Пожалуйста, укажите свой телефон для связи!');
        return false;
      }
      return true;
    });
  }

  PaymentDispatcher.register(function (eventPayload) {
    if (eventPayload.actionType === 'submit-new-phone') {
      var data = {
        'newPhone': eventPayload.value,
        'projectId': payload.projectId
      };
      $.post('/subscription_plans/updatePhone.json', data);
    }
    if (eventPayload.actionType === 'fund-balance-input-updated') {
      var total = 0;
      payload.receipt.forEach(function (row) {
        if (row.name == 'Пополнение счёта') {
          row.value = eventPayload.newValue;
        }
        total += row.value;
      });
      payload.total = total;
      ReactDOM.render(React.createElement(FundBalanceInput, { payload: payload }), document.getElementById('fund-balance-container'));
      ReactDOM.render(React.createElement(Receipt, { data: payload.receipt }), document.getElementById('receipt-container'));
      ReactDOM.render(React.createElement(PaymentTypesList, { payload: payload, settings: settings }), document.getElementById('payments-container'));
    }
    if (eventPayload.actionType === 'phone-number-input-updated') {
      ReactDOM.render(React.createElement(PhoneNumberInput, { phoneNumber: eventPayload.value }), document.getElementById('phone-number-container'));
    }
    if (eventPayload.actionType === 'submit-news-receipt') {
      var _total = 0;
      payload.receipt.forEach(function (row) {
        if (row.name == 'Пополнение счёта') {
          row.value = eventPayload.currentValue;
        }
        _total += row.value;
      });
      payload.total = _total;
      var _data = {
        'newFundValue': eventPayload.currentValue,
        'projectId': payload.projectId,
        'updatedReceipt': payload.receipt
      };
      $.post('/subscription_plans/updateReceipt.json', _data, function (response) {
        if (response.fundBalance != eventPayload.currentValue) {
          PaymentActions.updateFundBalanceInput(parseInt(response.fundBalance));
          ReactDOM.render(React.createElement(FundBalanceInput, { payload: payload }), document.getElementById('fund-balance-container'));
        } else {
          ReactDOM.render(React.createElement(FundBalanceInput, { payload: payload }), document.getElementById('fund-balance-container'));
        }
      });
    }
    if (eventPayload.actionType === 'payment-type-selected') {
      if (eventPayload.selectedPaymentType == 'payment-payture') {
        settings = [{
          'node': React.createElement(PaymentPayture, { key: '1', payload: payload, selected: true })
        }, {
          'node': React.createElement(PaymentSeparator, { key: '2' })
        }, {
          'node': React.createElement(PaymentPaymaster, { key: '3', payload: payload, selected: false })
        }, {
          'node': React.createElement(PaymentSeparator, { key: '4' })
        }, {
          'node': React.createElement(PaymentWire, { key: '5', payload: payload, selected: false })
        } /*,
                  {
                      "node": <PaymentSeparator key="6" />
                  },
                  {
                      "node": <PaymentAdmin key="7" payload={payload} />
                  }*/
        ];
      }
      if (eventPayload.selectedPaymentType == 'payment-paymaster') {
        settings = [{
          'node': React.createElement(PaymentPayture, { key: '1', payload: payload, selected: false })
        }, {
          'node': React.createElement(PaymentSeparator, { key: '2' })
        }, {
          'node': React.createElement(PaymentPaymaster, { key: '3', payload: payload, selected: true })
        }, {
          'node': React.createElement(PaymentSeparator, { key: '4' })
        }, {
          'node': React.createElement(PaymentWire, { key: '5', payload: payload, selected: false })
        } /*,
                  {
                      "node": <PaymentSeparator key="6" />
                  },
                  {
                      "node": <PaymentAdmin key="7" payload={payload} />
                  }*/
        ];
      }
      if (eventPayload.selectedPaymentType == 'payment-wire') {
        settings = [{
          'node': React.createElement(PaymentPayture, { key: '1', payload: payload, selected: false })
        }, {
          'node': React.createElement(PaymentSeparator, { key: '2' })
        }, {
          'node': React.createElement(PaymentPaymaster, { key: '3', payload: payload, selected: false })
        }, {
          'node': React.createElement(PaymentSeparator, { key: '4' })
        }, {
          'node': React.createElement(PaymentWire, { key: '5', payload: payload, selected: true })
        } /*,
                  {
                      "node": <PaymentSeparator key="6" />
                  },
                  {
                      "node": <PaymentAdmin key="7" payload={payload} />
                  }*/
        ];
      }
      ReactDOM.render(React.createElement(PaymentTypesList, { payload: payload, settings: settings }), document.getElementById('payments-container'));
    }
  });
})(ReactDOM, payload);