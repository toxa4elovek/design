'use strict';

;(function () {
  console.log('Hire Designer page loaded');
  $('.lp-projects-slider').slick({
    infinite: true,
    slidesToShow: 6,
    slidesToScroll: 6,
    arrows: true,
    variableWidth: true,
    appendArrows: $('.arrows')
  });
  $('.img-container').hover(function () {
    $('a', $(this), $(this)).fadeIn(300);
  }, function () {
    $('a', $(this), $(this)).fadeOut(300, function () {
      $(this).hide();
    });
  });
  var style = { 'position': 'absolute', 'top': '120px' };
  var minimalPrice = 2000;
  var getUpdatedReceiptData = function getUpdatedReceiptData(value) {
    value = parseInt(value);
    var fee = 0;
    if (value < 5000) {
      fee = 1000;
    } else if (value < 10000) {
      fee = 1750;
    } else if (value < 20000) {
      fee = 3250;
    } else if (value < 50000) {
      fee = 7000;
    } else if (value < 100000) {
      fee = 12500;
    } else {
      fee = value / 10;
    }
    var percentage = (fee / value * 100).toFixed(1);
    return [{ 'name': 'Награда дизайнеру', 'value': value }, { 'name': '\u0421\u0431\u043E\u0440 GoDesigner ' + percentage, 'value': fee }];
  };
  var renderReceipt = function renderReceipt(data, style) {
    ReactDOM.render(React.createElement(Receipt, { data: data, style: style, showControl: false }), document.getElementById('receipt-container'));
  };
  renderReceipt(payload.receipt, style);
  $(document).on('change', 'input[name=price]', function (element) {
    var value = parseInt(element.target.value);
    var dataObject = getUpdatedReceiptData(value);
    renderReceipt(dataObject, style);
  });

  $(document).on('blur', 'input[name=price]', function (element) {
    if (element.target.value < minimalPrice) {
      element.target.value = minimalPrice;
    }
    var dataObject = getUpdatedReceiptData(element.target.value);
    renderReceipt(dataObject, style);
  });
})();