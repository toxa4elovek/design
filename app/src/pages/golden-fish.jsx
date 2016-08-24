$(function () {
  const formatMoney = function (value) {
    value = value.replace(/(.*)\.00/g, '$1')
    let counter = 1
    while (value.match(/\w\w\w\w/)) {
      value = value.replace(/^(\w*)(\w\w\w)(\W.*)?$/, '$1 $2$3')
      counter++
      if (counter > 6) break
    }
    return value
  }

  const updateSum = function () {
    const margin = parseInt($('#margin').data('margin'))
    const award = parseInt($('#award').data('award'))
    const clients = parseInt($('#clients').data('clients'))
    const result = parseInt(((margin / 100) * award * clients) * 12)
    console.log(result)
    $('#result').html(formatMoney(result.toString()) + ' Р')
  }

  const clients = function (event, ui) {
    $('#clients').html(ui.value)
    $('#clients').data('clients', ui.value)
    updateSum()
  }

  $('.clients').slider({range: 'min', min: 1, max: 30, value: 10, slide: clients, change: clients})

  const award = function (event, ui) {
    $('#award').html(formatMoney(ui.value.toString()) + ' Р')
    $('#award').data('award', ui.value)
    updateSum()
  }

  $('.award').slider({range: 'min', max: 100000, value: 50000, step: 1000, min: 1000, slide: award, change: award})

  const margin = function (event, ui) {
    $('#margin').html(ui.value)
    $('#margin').data('margin', ui.value)
    updateSum()
  }

  $('.margin').slider({range: 'min', max: 200, min: 1, value: 25, slide: margin, change: margin})

  $(document).on('click', '#send-message', function () {
    const button = $(this)
    const data = button.parent().serialize()
    $.post('/users/requesthelp.json', data, function () {
      button.addClass('with-icon').text('')
      setTimeout(function () {
        button.removeClass('with-icon').text('отправить вопрос')
      }, 5000)
    })
    return false
  })

  let gifs = $('img', '.advantage-list')
  $(window).on('scroll', function () {
    let scroll = $(window).scrollTop()
    if (scroll >= 420) {
      $.each(gifs, function (index, object) {
        let gif = $(object)
        gif.attr('src', gif.attr('src'))
      })
      $(window).off('scroll')
    }
  })

  $('.lp-projects-slider').slick({
    infinite: true,
    slidesToShow: 6,
    slidesToScroll: 6,
    arrows: false,
    variableWidth: true
  })

  $('.img-container').hover(
    function () {
      $('a', $(this), $(this)).fadeIn(300)
    },
    function () {
      $('a', $(this), $(this)).fadeOut(300, function () {
        $(this).hide()
      })
    }
  )
})
