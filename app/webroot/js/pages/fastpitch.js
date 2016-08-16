'use strict';

$(document).ready(function () {
  $('.fastpitch').click(function () {
    var phoneInput = $('input[name="phone"]');
    if (phoneInput.val() !== '' && phoneInput.val() !== '7 911 123 45 67') {
      $.post('/pitches/addfastpitch.json', {
        'phone': phoneInput.val(),
        'date': $('input[name=time]:checked').data('date')
      }, function (response) {
        if (response) {
          window.location.href = $.parseJSON(response);
        }
      });
    } else {
      alert('Оставьте свой телефон, чтобы мы могли с вами связаться');
    }
  });

  $('#more').on('click', function () {
    $('.date-hide').show();
    $('#fastpitch').css('margin-top', '10px');
  });
});