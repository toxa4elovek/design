'use strict';

$(function () {
  console.log('loaded');

  var formatMoney = function formatMoney(value) {
    value = value.replace(/(.*)\.00/g, '$1');
    var counter = 1;
    while (value.match(/\w\w\w\w/)) {
      value = value.replace(/^(\w*)(\w\w\w)(\W.*)?$/, '$1 $2$3');
      counter++;
      if (counter > 6) break;
    }
    return value;
  };

  var updateSum = function updateSum() {
    var margin = parseInt($('#margin').data('margin'));
    var award = parseInt($('#award').data('award'));
    var clients = parseInt($('#clients').data('clients'));
    var result = parseInt(margin / 100 * award * clients * 12);
    console.log(result);
    $('#result').html(formatMoney(result.toString()) + ' Р');
  };

  var clients = function clients(event, ui) {
    $('#clients').html(ui.value);
    $('#clients').data('clients', ui.value);
    updateSum();
  };

  $('.clients').slider({ range: 'min', min: 1, max: 30, value: 10, slide: clients, change: clients });

  var award = function award(event, ui) {
    $('#award').html(formatMoney(ui.value.toString()) + ' Р');
    $('#award').data('award', ui.value);
    updateSum();
  };

  $('.award').slider({ range: 'min', max: 100000, value: 50000, step: 1000, min: 1000, slide: award, change: award });

  var margin = function margin(event, ui) {
    $('#margin').html(ui.value);
    $('#margin').data('margin', ui.value);
    updateSum();
  };

  $('.margin').slider({ range: 'min', max: 200, min: 1, value: 25, slide: margin, change: margin });
});