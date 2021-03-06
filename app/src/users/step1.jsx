$(document).ready(function () {
  $('#step2-link-saveform').click(function () {
    let data = ''
    if ($('#cards').is(':visible')) {
      $('input[data-validate]', '#cards').blur()
      data = $('#cards :input, input[name=cashintype]').serialize()
    }
    if ($('#wmr').is(':visible')) {
      $('input[data-validate]', '#wmr').blur()
      data = $('#wmr :input, input[name=cashintype]').serialize()
    }
    if ($('input[name=documentsfor]').length > 0) {
      data = $('input[name=documentsfor]').serialize()
      if ($('#company').is(':visible')) {
        $('input[data-validate]', '#company').blur()
        data = $('#company :input, input[name=documentsfor], #solution_id').serialize()
      }
      if ($('#individual').is(':visible')) {
        $('input[data-validate]', '#individual').blur()
        data = $('#individual :input, input[name=documentsfor], #solution_id').serialize()
      }
      if ($('#simpleclient').is(':visible')) {
        $('input[data-validate]', '#simpleclient').blur()
        data = $('#simpleclient :input, input[name=documentsfor], #solution_id').serialize()
      }
    }
    if ($('.wrong-input').length > 0) {
      return false
    }
    if ($('input[name="cashintype"]:checked').data('pay') === 'cards') {
      accountCheck()
      if ($('.account-check').length > 0) {
        return false
      }
    }
    const href = $(this).attr('href')
    $.post('/users/savePaymentData.json', data, function (response) {
      window.location = href
    })
    return false
  })

  $('.rb1').change(function () {
    if ($(this).data('pay') === 'cards') {
      $('#cards').show()
      $('#wmr').hide()
      $('#yandex').hide()
    }else if ($(this).data('pay') === 'wmr') {
      $('#cards').hide()
      $('#wmr').show()
      $('#yandex').hide()
    }if ($(this).data('pay') === 'yandex') {
      $('#cards').hide()
      $('#wmr').hide()
      $('#yandex').show()
    }
    const selectedItem = $(this).data('pay')
    if (selectedItem === 'not_needed') {
      $('#company').hide()
    } else if (selectedItem === 'company') {
      $('#company').show()
      $('#individual').hide()
      $('#simpleclient').hide()
    }else if (selectedItem === 'individual') {
      $('#company').hide()
      $('#individual').show()
      $('#simpleclient').hide()
    }else if (selectedItem === 'simpleclient') {
      $('#company').hide()
      $('#individual').hide()
      $('#simpleclient').show()
    }
  })

  $('.tooltip').tooltip({
    tooltipID: 'tooltip3',
    width: '205px',
    correctPosX: 45,
    positionTop: -180,
    borderSize: '0px',
    tooltipPadding: 0,
    tooltipBGColor: 'transparent'
  })

  $('#step2-link-saveform').mouseover(function () {
    $('span', this).css('color', '#50525a')
  })

  $('#step2-link-saveform').mouseout(function () {
    $('span', this).css('color', '#BABABA')
  })

  $(document).on('focus', 'input[data-validate]', function () {
    $(this).removeClass('wrong-input')
  })

  $(document).on('blur', 'input[data-validate=fio]', function () {
    if (!/^[А-ЯЁ]{1}[а-яё]+\s[А-ЯЁ]{1}[а-яё]+\s[А-ЯЁ]{1}[а-яё]+$/.test($(this).val())) {
      $(this).addClass('wrong-input')
      required = true
      return true
    }
  })

  $(document).on('blur', 'input[data-validate=numeric]', function () {
    if (/[\D\s]/i.test($(this).val())) {
      $(this).addClass('wrong-input')
      required = true
      return true
    }
  })

  $(document).on('blur', 'input[data-validate=wmr]', function () {
    if (! /^R\d{12}$/.test($(this).val())) {
      $(this).addClass('wrong-input')
      required = true
      return true
    }
  })

  $(document).on('blur', 'input[data-validate=notempty]', function () {
    if (($(this).val() === '') || ($(this).val().trim() === '')) {
      $(this).addClass('wrong-input')
      required = true
      return true
    }
  })

  $(document).on('blur', 'input[data-validate=yandex]', function () {
    if (! /^(41001\d{7,10})$/.test($(this).val())) {
      $(this).addClass('wrong-input')
      required = true
      return true
    }
  })

  $('input[name=bik], input[name=coraccount], input[name=accountnum], input[name="inn"]').on('blur', function () {
    accountCheck()
  })

  $(document).on('click', function () {
    if ($('.account-check').hasClass('active')) {
      $('.account-check').remove()
    }
  })

  $(document).on('blur keyup', 'input[name=phone]', function () {
    if ($(this).val() == '') {
      var paragraph = $('.confirm-message', '.user-mobile-section')
      paragraph.hide()
    }
  })

  $(document).on('click', '#save-mobile', function () {
    var form = $('#mobile-form')
    var phonenumber = $('input[name=phone]', form).val()
    var data = form.serialize()
    $.post('/users/update.json', data, function (response) {
      var paragraph = $('.confirm-message', '.user-mobile-section')
      var text = ''
      if (response == false) {
        text = 'К сожалению, мы не сможем подтвердить ваш телефон. Пожалуйста, укажите другой номер.'
      }else if (response == 'limit') {
        text = 'К сожалению, Вы превысили лимит отправки сообщений. Попробуйте снова через час.'
      } else {
        if (response.indexOf('error') != -1) {
          text = 'Произошел сбой доставки SMS-сообщения. Попробуйте позже.'
          paragraph.text(text).show()
        } else {
          text = 'Для подтверждения номера +' + phonenumber + ' введите код, который пришел по смс.'
          $('.phone-input-container').hide()
          $('#save-mobile').hide()
          $('#save-mobile').next().hide()
          $('ul', '#mobile-form').show()
          $('#confirm-mobile').prev().show()
          $('#confirm-mobile').show()
          paragraph.text(text).show()
          $('.number').text('+ ' + phonenumber)
        }
      }
      paragraph.text(text).show()
    })
    return false
  })

  $(document).on('click', '.remove-number-link', function () {
    var data = {'removephone': true}
    $.post('/users/update.json', data)
    $('.confirm-message').hide()
    $('ul', '#mobile-form').hide()
    $('#confirm-mobile').prev().hide()
    $('#confirm-mobile').hide()
    $('.phone-input-container').show()
    $('#save-mobile').next().show()
    $('#save-mobile').show()
    $('.resend-code').show()
    $('.remove-number-link').text('Удалить номер')
    $('.remove-number').css('margin-right', '75px')
    return false
  })

  $(document).on('click', '.resend-code', function () {
    var data = {'resendcode': true}
    $.post('/users/update.json', data, function (response) {
      var paragraph = $('.confirm-message', '.user-mobile-section')
      var text = ''
      if (response == 'limit') {
        text = 'К сожалению, Вы превысили лимит отправки сообщений. Попробуйте снова через час.'
      } else {
        if (response.indexOf('error') != -1) {
          text = 'Произошел сбой доставки SMS-сообщения. Попробуйте позже.'
        } else {
          text = 'Код подтверждения отправлен повторно на ваш номер.'
        }
      }
      paragraph.text(text).show()
    })
    return false
  })

  $(document).on('click', '#confirm-mobile', function () {
    var data = {'code': $('input[name=phone_code]', '.user-mobile-section').val()}
    $.post('/users/update.json', data, function (response) {
      var paragraph = $('.confirm-message', '.user-mobile-section')
      if (response == 'false') {
        text = 'Вы ввели неверный код.'
        paragraph.text(text)
      } else {
        paragraph.hide()
        $('#confirm-mobile').prev().hide()
        $('#confirm-mobile').hide()
        $('.remove-number-link').text('Удалить/поменять номер')
        $('.remove-number').css('margin-right', '0')
        $('.remove-number').css('margin-right', '0 !important')
        $('.resend-code').hide()
        $('.number').show()
      }
    })
    return false
  })
})

function accountCheck () {
  $('.account-check').remove()
  let accountNum = $('input[name=accountnum]').val()
  var resultCor = 1; // var resultCor = (fn_checkKS($('input[name=coraccount]').val())) ? 1 : 0
  var resultAcc = (fn_checkRS(accountNum, $('input[name=bik]').val())) ? 2 : 0
  var result = resultCor + resultAcc
  var message = ''
  if (accountNum.length != 20) {
    result = 4
  }
  switch (result) {
    case 0:
      message = 'Неверно указан Счёт.<br>Неверно указан Корсчёт.<br>'
      $('input[name=accountnum]').addClass('wrong-input')
      $('input[name=coraccount]').addClass('wrong-input')
      $('input[name=bik]').removeClass('wrong-input')
      break
    case 1:
      message = 'Неверно указан Счёт или БИК.<br>'
      $('input[name=accountnum]').addClass('wrong-input')
      $('input[name=bik]').addClass('wrong-input')
      $('input[name=coraccount]').removeClass('wrong-input')
      break
    case 2:
      message = 'Неверно указан Корсчёт.<br>'
      $('input[name=coraccount]').addClass('wrong-input')
      $('input[name=accountnum]').removeClass('wrong-input')
      $('input[name=bik]').removeClass('wrong-input')
      break
    case 4:
      message = 'Номер счёта должен состоять из 20 цифр.<br>'
      $('input[name=accountnum]').addClass('wrong-input')
      $('input[name=bik]').removeClass('wrong-input')
      $('input[name=coraccount]').removeClass('wrong-input')
      break
    default:
      break
  }
  var messageBik = (/^\d{9}$/.test($('input[name=bik]').val())) ? '' : 'Неверно указан БИК.<br>'
  var messageInn = (/^\d{12}$/.test($('input[name="inn"]').val())) ? '' : 'Неверно указан ИНН.<br>'
  message = messageBik + messageInn + message
  if ((message == '') && (accountNum.match(/^302/))) {
    const extraData = $('input[name=extradata]')
    if (extraData.val().match(/^\d{12,19}$/)) {
      extraData.removeClass('wrong-input')
    } else {
      message = 'Введите номер карты получателя без пробелов в поле "Примечание"'
      extraData.addClass('wrong-input')
      extraData.one('blur', function () {
        accountCheck()
      })
    }
  }
  if (message) {
    var el = $('<tr class="account-check"><td colspan="2">' + message + '</td></tr>')
    el.appendTo($('#step1table')).animate({'opacity': 1}, 200, function () { $(this).addClass('active'); })
  } else {
    $('input[name=accountnum]').removeClass('wrong-input')
    $('input[name=bik]').removeClass('wrong-input')
    $('input[name=coraccount]').removeClass('wrong-input')
  }
}

/*
 * From http://javascript.ru/forum/misc/37373-funkciya-klyuchevaniya-scheta.html
 */
function fn_bank_account (Str) {
  var result = false
  var Sum = 0
  if (Str == 0) {
    return result
  }

  // весовые коэффициенты
  var v = [7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1]

  for (var i = 0; i <= 22; i++) {
    // вычисляем контрольную сумму
    Sum = Sum + (Number(Str.charAt(i)) * v[i]) % 10
  }

  // сравниваем остаток от деления контрольной суммы на 10 с нулём
  if (Sum % 10 == 0) {
    result = true
  }

  return result
}

function fn_checkKS (Account) {
  return (/^\d{20}$/.test(Account))
}

/*
 Проверка правильности указания расчётного счёта:
 1. Для проверки контрольной суммы перед расчётным счётом добавляются три последние цифры БИКа банка.
 */
function fn_checkRS (Account, BIK) {
  return fn_bank_account(BIK.substr(-3, 3) + Account)
}
