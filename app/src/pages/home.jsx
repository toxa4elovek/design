$(document).ready(function () {
  if (window.isDesigner === 0) {
    setTimeout(function () {
      Chatra('show')
      Chatra('openChat')
    }, 20000)
  }
  const expertsZone = $('#experts-zone')

  expertsZone.on('hover', 'a', function () {
    const id = $(this).data('id')
    $('a[data-id="' + id + '"]', '#experts-zone').css('color', '#ff585d')
  })

  expertsZone.on('mouseout', 'a', function () {
    const id = $(this).data('id')
    $('a[data-id="' + id + '"]', '#experts-zone').css('color', '#666666')
  })

  $(function () {
    $('.take, .fill').click(function () {
      const link = $(this).children('a').attr('href')
      if (link === '/pitches/create') {
        _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь перешел на выбор категории', 'Ссылка "Заказчику" на главной'])
      }
      window.location = link
    })

    $('#to_use_text').hover(function () {
      $('#to_use_link').addClass('hover')
    }, function () {
      $('#to_use_link').removeClass('hover')
    })

    $('#to_use_link').hover(function () {
      $('#to_use_text').addClass('hover')
    }, function () {
      $('#to_use_text').removeClass('hover')
    })

    $('#experts_text').hover(function () {
      $('#experts_link').addClass('hover')
      $('#experts_text').css('color', '#ff585d')
    }, function () {
      $('#experts_link').removeClass('hover')
      $('#experts_text').css('color', '#666666')
    })

    $('#experts_link').hover(function () {
      $('#experts_text').addClass('hover').css('color', '#ff585d')
    }, function () {
      $('#experts_text').removeClass('hover').css('color', '#666666')
    })

    $('#finished, #video').hover(function () {
      $('img', this).hide()
    }, function () {
      $('img', this).show()
    })

    $('#slides').slides({
      preload: false,
      play: 5000,
      pause: 2500,
      hoverPause: true,
      animationStart: function () {
        $('.caption').animate({
          bottom: -35
        }, 100)
      },
      animationComplete: function () {
        $('.caption').animate({
          bottom: 0
        }, 200)
      },
      slidesLoaded: function () {
        $('.caption').animate({
          bottom: 0
        }, 300)
      }
    })

    $('#pitch-table').height($('.wap_table').height())

    function changeBanner () {
      const $el = $('div:visible', '#bannerblock')
      let $elNext = $el.next()
      if ($elNext.length === 0) {
        $elNext = $el.prevAll().last()
      }
      $el.fadeOut(300)
      $elNext.fadeIn(300)
    }
    setInterval(changeBanner, 7500)
    $('.talkhoverzone').on('mouseover', function () {
      $('a', $(this)).css('color', '#ff585d')
    }).on('mouseout', function () {
      $('a', $(this)).css('color', '#666666')
    })

    $('.front_catalog li').hover(function () {
      $('.more_info', $(this)).fadeIn(300)
    },
      function () {
        $('.more_info', $(this)).fadeOut(300, function () {
          $(this).hide()
        })
      })

    $('#video').click(function () {
      $('#popup-final-step').modal({
        containerId: 'video-container',
        opacity: 80,
        closeClass: 'popup-close',
        onShow: function () {
          const player = new YT.Player('ytplayer', {
            height: '390',
            width: '640',
            videoId: '3bhLkorXLI8',
            playerVars: { 'autoplay': '1' }
          })
          mixpanel.track('Проигрывание видео')
          return true
        }
      })
      return false
    })

    expertsRandom()

    $('a', '.logosale_search-block').click(function () {
      $(this).html('Поиск <img src="/img/transparent-loader.gif" alt="" style="position: relative;top: 6px;left: 25px;">')
      $(this).css({'text-align': 'left', 'padding-left': '25px', 'width': '119px'})
      $('#logosale_form').submit()
      return false
    })
  })

  function expertsRandom () {
    const limit = 3
    const expertsArray = []
    $('li.expert_enabled').each(function (idx, obj) {
      expertsArray.push($(obj).data('expert_id'))
    })
    expertsArray.sort(function () { return 0.5 - Math.random() })

    for (let i = 0; i < limit; i++) {
      $('li.expert-' + expertsArray[i], '#experts-zone').show()
    }
  }
})
