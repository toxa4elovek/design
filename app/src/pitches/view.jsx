;$(document).ready(function () {
  $('.social-likes').socialLikes()

  // Preload Images
  ;(function (arrayOfImages) {
    $(arrayOfImages).each(function () {
      $('<img/>')[0].src = this
    })
  })([
    '/img/0-rating.png',
    '/img/1-rating.png',
    '/img/2-rating.png',
    '/img/3-rating.png',
    '/img/4-rating.png',
    '/img/5-rating.png',
    '/img/like.png',
    '/img/like_hover.png'
  ])

  var receiptOffsetTop = 0
  var popupReady = false

  if ($('.summary-price').length > 0) {
    $(window).on('scroll', function () {
      if (popupReady) {
        var receipt = $('.summary-price').last()
        if (($(window).scrollTop() + 20) > receiptOffsetTop) {
          var parent = receipt.parent()
          var parentOffset = parent.offset()
          receipt.css('position', 'fixed')
          receipt.css('top', '20px')
          receipt.css('left', parentOffset.left + 'px')
        } else {
          receipt.css('position', 'relative').css('left', '0').css('top', '0')
        }
      }
    })
  }

  $(document).on('click', '#to-pay, .scrolldown', function () {
    $('html, body').animate({
      scrollTop: $('.solution-overlay #step3').offset().top
    }, 500)
    return false
  })

  $(document).on('click', '.s3_text, .s3_h img', function () {
    var paymentType = $(this).data('radio')
    $('.rb1[data-pay=' + paymentType + ']').prop('checked', true).change()
  })

  $(document).on('change', '.rb1', function () {
    $('.solution-prev').hide()
    $('.solution-next').hide()
    switch ($(this).data('pay')) {
      case 'payanyway':
        $('.solution-overlay #paybutton-payanyway').fadeIn(100)
        $('.solution-overlay #paybutton-paymaster').css('background', '#a2b2bb')
        $('.solution-overlay #paymaster-images').show()
        $('.solution-overlay #paymaster-select').hide()
        $('.solution-overlay #s3_kv').hide()
        $('.solution-overlay #paybutton-payture').hide()
        break
      case 'payture':
        $('.solution-overlay #paybutton-payture').fadeIn(100)
        $('.solution-overlay #paymaster-images').show()
        $('.solution-overlay #paymaster-select').hide()
        $('.solution-overlay #s3_kv').hide()
        break
      case 'paymaster':
        $('.solution-overlay #paybutton-paymaster').removeAttr('style')
        $('.solution-overlay #paybutton-payanyway').fadeOut(100)
        $('.solution-overlay #paymaster-images').hide()
        $('.solution-overlay #paymaster-select').show()
        $('.solution-overlay #s3_kv').hide()
        $('.solution-overlay #paybutton-payture').hide()
        break
      case 'offline':
        $('.solution-overlay #paybutton-payanyway').fadeOut(100)
        $('.solution-overlay #paybutton-paymaster').css('background', '#a2b2bb')
        $('.solution-overlay #paymaster-images').show()
        $('.solution-overlay #paymaster-select').hide()
        $('.solution-overlay #s3_kv').show()
        $('.solution-overlay #paybutton-payture').hide()
        break
    }
  })

  $(document).on('change', '.solution-overlay .rb-face', '.solution-overlay #s3_kv', function () {
    if ($(this).data('pay') == 'offline-fiz') {
      $('.solution-overlay .pay-fiz').show()
      $('.solution-overlay .pay-yur').hide()
    } else {
      $('.solution-overlay .pay-fiz').hide()
      $('.solution-overlay .pay-yur').show()
    }
  })

  $(document).on('mouseenter', '.ratingchange', function () {
    $(this).parent().css('background', 'url(/img/' + $(this).data('rating') + '-rating.png) repeat scroll 0% 0% transparent')
  })

  $(document).on('mouseleave', '.ratingcont', function () {
    $(this).css('background', 'url(/img/' + $(this).data('default') + '-rating.png) repeat scroll 0% 0% transparent')
  })

  $(document).on('click', '.ratingchange', function () {
    var id = $(this).parent().data('solutionid')
    var rating = $(this).data('rating')
    var self = $(this)
    if (rating < 5) {
      var number = $(this).closest('li').find('.number_img_gallery').data('comment-to')
      var $el = $(this).closest('li').find('.photo_block')
      var positionClass = ($(this).parent().offset().left > 300) ? '' : ' right-pos'
      var offset = $(this).parent().offset()
      var $newEl = $('<div class="ratingcomment' + positionClass + '"><span>Как улучшить?</span><form><textarea></textarea><a href="#" id="rating_comment_send" data-solution_id="' + number + '">отправить</a></form><div id="rating-close"></div></div>')
      $('body').append($newEl)
      if ($newEl.hasClass('right-pos')) {
        $newEl.offset({top: offset.top - 78, left: offset.left + 20})
      } else {
        $newEl.offset({top: offset.top - 78, left: offset.left - 283})
      }
      $('textarea', $newEl).focus()
    }
    $.post('/solutions/rating/' + id + '.json',
      {'id': id, 'rating': rating}, function (response) {
        self.parent().data('default', rating)
        self.parent().css('background', 'url(/img/' + rating + '-rating.png) repeat scroll 0% 0% transparent')
      })
    return false
  })

  $(document).on('click', '#rating-close', function () {
    const ratingComment = $('.ratingcomment')
    $(this).closest(ratingComment).fadeOut(200, function () {
      $(this).remove()
    })
  })

  $(document).on('click', '#rating_comment_send', function (e) {
    const ratingComment = $('.ratingcomment')
    e.preventDefault()
    $(this).closest(ratingComment).fadeOut(200, function () {
      $(this).remove()
    })
  })

  // Gallery buttons
  $(document).on('click', '.next_part', function (e) {
    e.preventDefault()
    $('.button_more').css('opacity', 0)
    $('.gallery_postload_loader').show()
    var data = {
      count: $('.photo_block', '.main_portfolio').length
    }
    var gallerySorting = getParameterByName('sorting')
    if (gallerySorting.length > 0) {
      data.sorting = gallerySorting
    }
    $.get('/pitches/view/' + $('input[name=pitch_id]').val(), data, function (response) {
      var solutionsCount = $($(response)[0]).val()
      const obj = $('<div/>').html(response).contents(); // http://stackoverflow.com/a/11047751
      obj.each(function () {
        if ($(this).is('li')) {
          $(this).css('opacity', '0')
        }
      })
      obj.appendTo('.list_portfolio.main_portfolio')
      obj.each(function () {
        if ($(this).is('li')) {
          $(this).animate({opacity: 1}, 500)
        }
      })
      $('.gallery_postload_loader').hide()
      if ($('.photo_block').length < solutionsCount) {
        $('.button_more').css('opacity', 1)
      } else {
        $('.gallery_postload').hide()
        $('.pre-comment-separator').fadeIn()
        checkSeparator()
      }
    })
  })
  $(document).on('click', '.rest_part', function (e) {
    e.preventDefault()
    $('.button_more').css('opacity', 0)
    $('.gallery_postload_loader').show()
    var data = {
      count: $('.photo_block', '.main_portfolio').length,
      rest: 1
    }
    var gallerySorting = getParameterByName('sorting')
    if (gallerySorting.length > 0) {
      data.sorting = gallerySorting
    }
    $.get('/pitches/view/' + $('input[name=pitch_id]').val(), data, function (response) {
      obj = $('<div/>').html(response).contents(); // http://stackoverflow.com/a/11047751
      obj.each(function (index) {
        if ($(this).is('li')) {
          $(this).css('opacity', '0')
        }
      })
      obj.appendTo('.list_portfolio.main_portfolio')
      obj.each(function (index) {
        if ($(this).is('li')) {
          $(this).animate({opacity: 1}, 500)
        }
      })
      $('.gallery_postload').hide()
      $('.pre-comment-separator').fadeIn()
      checkSeparator()
    })
  })

  $(document).on('click', '.select-winner', function () {
    var num = $(this).data('num')
    var item = $('.photo_block', '#li_' + num).clone()
    $('#winner-num').text('#' + num)
    $('#winner-num').attr('href', '/pitches/viewsolution/' + $(this).data('solutionid'))
    $('#winner-user-link').text($(this).data('user'))
    $('#winner-user-link').attr('href', '/users/view/' + $(this).data('userid'))
    $('#confirmWinner').data('url', $(this).attr('href'))
    $('#replacingblock').replaceWith(item)
    $('#popup-final-step').modal({
      containerId: 'final-step-multi',
      opacity: 80,
      closeClass: 'popup-close'
    })

    /*
     $.get($(this).attr('href'), function(response) {
     if(response.result != false) {
     if(response.result.nominated) {
     window.location = '/users/nominated'
     $('.select-winner-li').remove()
     }
     }
     });*/
    return false
  })

  $(document).on('click', '.select-multiwinner', function () {
    var num = $(this).data('num')
    var item = $('.photo_block', '#li_' + num).clone()
    $('#winner-num-multi').text('#' + num)
    $('#winner-num-multi').attr('href', '/pitches/viewsolution/' + $(this).data('solutionid'))
    $('#winner-user-link-multi').text($(this).data('user'))
    $('#winner-user-link-multi').attr('href', '/users/view/' + $(this).data('userid'))
    $('#confirmWinner-multi').data('url', $(this).attr('href'))
    $('#multi-replacingblock').replaceWith(item)
    $('#popup-final-step-multi').modal({
      containerId: 'final-step-multi',
      opacity: 80,
      closeClass: 'popup-close'
    })
    return false
  })

  $(document).on('click', '#confirmWinner', function () {
    var url = $(this).data('url')
    $.get(url, function (response) {
      if (response.result != false) {
        if (response.result.nominated) {
          window.location = '/users/nominated'
          $('.select-winner-li').remove()
        }
      } else {
        if (typeof response.redirect != 'undefined') {
          window.location = response.redirect
        }
      }
    })
  })

  $(document).on('click', '#confirmWinner-multi', function () {
    var url = $(this).data('url')
    if ($(this).data('url')) {
      window.location = url
    }
  })

  // Delete Solution from Solution Popup
  $(document).on('click', '.delete-solution-popup', function (e) {
    e.preventDefault()
    var item = $('.delete-solution[data-solution="' + $(this).data('solution') + '"]')
    if (item.length > 0) {
      hideSolutionPopup()
      item.click()
    } else {
      window.location = '/solutions/delete/' + $(this).data('solution')
    }
  })

  $('.social-likes__button').on('click', function () {
    $(this).closest('.sharebar').fadeOut(300)
  })

  // Delete Solution
  $(document).on('click', '.delete-solution', function () {
    var num = $(this).data('solution_num')
    var link = $('a.delete-solution', '#li_' + num)

    // Delete without Moderation
    if (!isCurrentAdmin) {
      solutionDelete(link)
      return false
    }
    // Delete for Moderators
    $('#model_id', '#popup-delete-solution').val($(this).data('solution'))

    if ($(`#li_${num}`).has('.medal').length == 1) {
      $('#winner-warning').show()
    } else {
      $('#winner-warning').hide()
    }

    // Show Delete Moderation Overlay
    $('#popup-delete-solution').modal({
      containerId: 'final-step-clean',
      opacity: 80,
      closeClass: 'popup-close',
      onShow: function () {
        $(document).off('click', '#sendDeleteSolution')
      }
    })

    // Delete Solution Popup Form
    $(document).on('click', '#sendDeleteSolution', function () {
      var form = $(this).parent().parent()
      if (!$('input[name=reason]:checked', form).length || !$('input[name=penalty]:checked', form).length) {
        $('#popup-delete-solution').addClass('wrong-input')
        return false
      }
      var $spinner = $(this).next()
      $spinner.addClass('active')
      $(document).off('click', '#sendDeleteSolution')
      var data = form.serialize()
      $.post(form.attr('action') + '.json', data).done(function (result) {
        if ($('input[name=penalty]:checked', '#delete-solution-form').val() == '1') {
          softSolutionDelete(link)
        }
        $spinner.removeClass('active')
        $('.popup-close').click()
      })
      return false
    })
    return false
  })

  $(document).on('click', '.update-description-button', function () {
    var textArea = $('.edit-description-textarea')
    var updatedText = textArea.val()
    $.post('/solutions/update_description.json', {'updatedText': updatedText, 'id': $(this).data('solutionid')}, function () {})
    textArea.hide()
    $(this).hide()
    var viewLength = 100
    if (updatedText.length > viewLength) {
      var descBefore = updatedText.slice(0, viewLength - 1)
      descBefore = descBefore.substr(0, Math.min(descBefore.length, descBefore.lastIndexOf(' ')))
      var descAfter = updatedText.slice(descBefore.length)
      $('.solution-description').html(descBefore).show()
      showMoreLink = $('.description-more')
      showMoreLink.show(500).on('click', function () {
        $('.solution-description').append(descAfter)
        descAfter = ''
        showMoreLink.hide()
      })
    } else {
      $('.solution-description').show().text(updatedText)
    }
    $('.edit-description-link').show()
    return false
  })

  $(document).on('click', '.edit-description-link', function () {
    var descriptionElement = $('.solution-description:visible')
    $(this).after('<a data-solutionid="' + $(this).data('solutionid') + '" class="button update-description-button" href="#">сохранить</a>').hide()
    $('.description-more').click()
    var descriptionText = descriptionElement.text()
    descriptionElement
      .after('<textarea class="edit-description-textarea">' + descriptionText + ' </textarea>')
      .hide()
    $('.description-more').hide()
    return false
  })

  function softSolutionDelete (link) {
    var newSolutionCount = parseInt($('#hidden-solutions-count').val()) - 1
    var word = formatString(newSolutionCount, {'string': 'решен', 'first': 'ие', 'second': 'ия', 'third': 'ий'})
    var newString = newSolutionCount + ' ' + word
    link.parent().parent().parent().parent().remove()
    $('#solutions', 'ul').html(newString)
    $('#hidden-solutions-count').val(newSolutionCount)
  }

  // Instant Delete Solution
  function solutionDelete (link) {
    var newSolutionCount = parseInt($('#hidden-solutions-count').val()) - 1
    var word = formatString(newSolutionCount, {'string': 'решен', 'first': 'ие', 'second': 'ия', 'third': 'ий'})
    var newString = newSolutionCount + ' ' + word
    $.get(link.attr('href'), function (response) {
      if (response.result != false) {
        link.parent().parent().parent().parent().remove()
        $('#solutions', 'ul').html(newString)
        $('#hidden-solutions-count').val(newSolutionCount)
      }
    })
    return true
  }

  function formatString (value, strings) {
    root = strings['string']
    if ((value == 1) || (value.toString().match(/[^1]1$/))) {
      var index = 'first'
    } else if ((value >= 2) && (value <= 4)) {
      var index = 'second'
    } else {
      var index = 'third'
    }
    var string = root + strings[index]
    return string
  }

  // Keys navigation
  $(document).keydown(function (e) {
    if ($('.solution-overlay').is(':hidden')) {
      return true
    }
    if (e.target instanceof HTMLInputElement || e.target instanceof HTMLTextAreaElement) {
      return true
    }
    if (e.keyCode == 37) { // left
      $('.solution-prev-area').click()
    }
    if (e.keyCode == 39) { // right
      $('.solution-next-area').click()
    }
  })

  $(document).on('click', '.like-small-icon', function () {
    var likesNum = $(this).next()
    var likeLink = $(this)
    likesNum.html(parseInt(likesNum.html()))
    var sharebar = likesNum.next()
    $('body').one('click', function () {
      $('.sharebar').fadeOut(300)
    })
    $('.social-likes').socialLikes()
    if ($(this).data('status') > 0) {
      likeLink.off('click')
      sharebar.fadeIn(300)
      likeLink.off('mouseover')
      likeLink.on('click', function () {
        $('body').one('click', function () {
          sharebar.fadeOut(300)
        })
        sharebar.fadeIn(300)
        return false
      })
    } else {
      $.get('/solutions/like/' + $(this).data('id') + '.json', function (response) {
        likesNum.html(response.likes)
        likeLink.off('click')
        sharebar.fadeIn(300)
        likeLink.off('mouseover')
        likeLink.on('click', function () {
          $('body').one('click', function () {
            sharebar.fadeOut(300)
          })
          sharebar.fadeIn(300)
          return false
        })
      })
    }
    return false
  })

  $(document).on('mouseenter', '.like-hoverbox', function () {
    $('img:first', $(this)).attr('src', '/img/like_hover.png')
  })

  $(document).on('mouseleave', '.like-hoverbox', function () {
    $('img:first', $(this)).attr('src', '/img/like.png')
  })

  $(document).on('mouseover', '.solution-menu-toggle', function () {
    $('img', $(this)).attr('src', '/img/marker5_2_hover.png')
    $('body').one('click', function () {
      $('.solution_menu.temp').fadeOut(200, function () {
        $(this).remove()
      })
    })
    var container = $(this).closest('.photo_block')
    var menu = container.siblings('.solution_menu')
    var offset = container.offset()
    menu = menu.clone()
    menu.addClass('temp')
    $('body').append(menu)
    menu.offset({top: offset.top + 155, left: offset.left + 47})
    menu.fadeIn(200)
    $(menu).on('mouseleave', function () {
      $(this).fadeOut(200, function () {
        $(this).remove()
      })
    })
    $('.photo_block').on('mouseenter', function () {
      $('.solution_menu.temp').fadeOut(200, function () {
        $(this).remove()
      })
    })
  })

  $(document).on('mouseleave', '.solution-menu-toggle', function () {
    $('img', $(this)).attr('src', '/img/marker5_2.png')
  })

  $(document).on('click', '.solution-menu-toggle', function () {
    return false
  })

  // Comment Bubble
  $(document).on('click', '.solution-link-menu, .solution-link, .number_img_gallery', function (e) {
    e.preventDefault()
    const newComment = $('#newComment')
    if (newComment.length > 0) { // View Tab
      if ((newComment.val().match(/^#\d/ig) == null) && (newComment.val().match(/@\W*\s\W\.,/) == null)) {
        var prepend = $(this).data('commentTo') + ', '
        var newText = prepend + newComment.val()
        newComment.val(newText)
        $('.solution_menu.temp').hide().remove()
        $.scrollTo($('.all_messages'), {duration: 500})
      }
    } else { // Designers Tab
      if (!isClient && !isCurrentAdmin) {
        return false
      }
      var number = $(this).data('comment-to')
      var num = number.slice(1)
      var $el = $('#li_' + num).find('.photo_block')
      var offset = $el.offset()
      var $newEl = $('<div class="ratingcomment"><span>Комментировать</span><form><textarea></textarea><a href="#" id="rating_comment_send" data-solution_id="' + number + '">отправить</a></form><div id="rating-close"></div></div>')
      $('body').append($newEl)
      $newEl.offset({top: offset.top + 78, left: offset.left - 139})
      $('textarea', $newEl).focus()
    }
  })

  $(document).on('mouseover', '.hidedummy', function () {
    $(this).css('background-image', '')
    var $imagecontainer = $(this).find('a')
    $imagecontainer.css('opacity', '1')
    if ($('.imagecontainer', this).children().length > 1) {
      $(this).parent().css('background-image', '')
    }
  })

  $(document).on('mouseout', '.hidedummy', function () {
    $(this).css('background-image', 'url(/img/copy-inv.png)')
    $(this).find('a').css('opacity', '.1')
    if ($('.imagecontainer', this).children().length > 1) {
      $(this).parent().css('background-image', 'url(/img/copy-inv.png)')
    }
  })

  $(document).on('click', '.hide-item', function () {
    var link = $(this)
    var num = link.data('to')
    var block = $('#li_' + num)
    var listofitems = $('.list_portfolio')
    $.get($(this).attr('href'), function (response) {
      if ($('.imagecontainer', block).children().length == 1) {
        $('.imagecontainer', block).wrap('<div class="hidedummy" style="background-image: url(/img/copy-inv.png)"/>')
      } else {
        $('.photo_block', block).css('background', 'url(/img/copy-inv.png) 10px 10px no-repeat white')
      }
      $('.imagecontainer', block).css('opacity', 0.1)
      $('.hide-item', block).replaceWith('<a data-to="' + num + '" class="unhide-item" href="/solutions/unhide/' + num + '.json">Сделать видимой</a>')
    // listofitems.append(block)
    })
    return false
  })

  $(document).on('click', '.unhide-item', function () {
    var link = $(this)
    var num = link.data('to')
    var block = $('#li_' + num)
    $.get($(this).attr('href'), function (response) {
      if ($('.imagecontainer', block).children().length == 1) {
        var dummy = $('.hidedummy', block)
        var child = dummy.children(':first')
        child.css('opacity', 1)
        dummy.replaceWith(child)
      } else {
        $('.photo_block', block).css('background', '')
        $('.imagecontainer', block).css('opacity', 1)
      }
      $('.solution_menu.temp').hide().remove()
      $('.unhide-item', block).replaceWith('<a data-to="' + num + '" class="hide-item" href="/solutions/hide/' + num + '.json">С глаз долой</a>')
    })
    return false
  })

  var cycle = {}
  $(document).on('mouseenter', '.photo_block', function () {
    if ($(this).children('.imagecontainer').children().length > 1) {
      cycle[$(this).children('.imagecontainer').data('solutionid')] = true
      var image = $(this).children('.imagecontainer').children(':visible')
      cycleImage(image, $(this).children('.imagecontainer'))
    }
  })

  $(document).on('mouseleave', '.photo_block', function () {
    cycle[$(this).children('.imagecontainer').data('solutionid')] = false
  })

  function cycleImage (image, parent, prev) {
    if (cycle[parent.data('solutionid')] == true) {
      image.fadeOut(300)
      var nextImage = image.next()
      if (nextImage.length == 0) {
        nextImage = parent.children().first()
      }
      nextImage.fadeIn(300)
      setTimeout(function () {
        cycleImage(nextImage, parent, image)
      }, 1500)
    //    cycleImage(nextImage, parent, image)
    //    5000
    }
  }

  $(document).on('change', '#client-only-toggle', function () {
    if ($(this).attr('checked')) {
      $.each($('section[data-type="designer"]', '.messages_gallery'), function (index, object) {
        var comment = $(object)
        comment.hide()
        var separator = comment.next('.separator')
        if ((separator) && (separator.length == 1)) {
          separator.hide()
        }
      })
    } else {
      $.each($('section[data-type="designer"]', '.messages_gallery'), function (index, object) {
        var comment = $(object)
        comment.show()
        var separator = comment.next('.separator')
        if ((separator) && (separator.length == 1)) {
          separator.show()
        }
      })
    }
  })

  /*
   * View Solution Overlay
   */
  var solutionId = ''
  $(document).on('click', '.imagecontainer', function (e) {
    if (/designers/.test(window.location.pathname)) {
      return true
    }
    e.preventDefault()
    e.stopPropagation()
    if (window.history.pushState) {
      window.history.pushState('object or string', 'Title', this.href); // @todo Check params
    } else {
      window.location = $(this).attr('href')
      return false
    }
    $('.solution-overlay-dummy').clone().appendTo('body').addClass('solution-overlay')
    $('#pitch-panel').hide()
    beforeScrollTop = $(window).scrollTop()
    $('.wrapper', 'body').first().addClass('wrapper-frozen')
    $.browser.chrome = /chrome/.test(navigator.userAgent.toLowerCase())
    if (!$.browser.chrome) {
      $('.solution-overlay').css('z-index', '50')
    }
    $('.solution-overlay').show()
    if (allowComments) {
      $('.allow-comments', '.solution-left-panel').show()
    }
    var queryParam = ''
    if (document.URL.indexOf('?') != -1) {
      queryParam = document.URL.slice(document.URL.indexOf('?'))
    }
    var urlJSON = window.location.pathname + '.json' + queryParam
    fetchSolution(urlJSON)
    return false
  })
  $(document).on('click', '.solution-overlay', function (e) {
    e.stopPropagation()
    if (!$(e.target).is('.solution-overlay'))
      return
    hideSolutionPopup()
    return false
  })
  $('body, .solution-overlay').on('click', '.solution-title, .solution-popup-close', function (e) {
    e.preventDefault()
    hideSolutionPopup()
    return false
  })
  $('body, .solution-overlay').on('mouseover', '.solution-prev-area, .solution-next-area', function (e) {
    $(this).prev().addClass('active')
  })
  $('body, .solution-overlay').on('mouseout', '.solution-prev-area, .solution-next-area', function (e) {
    $(this).prev().removeClass('active')
  })
  $('body, .solution-overlay').on('click', '.solution-prev-area, .solution-next-area', function (e) {
    e.preventDefault()
    e.stopPropagation()
    window.history.pushState('object or string', 'Title', this.href); // @todo Check params
    var urlJSON = this.href + '.json'
    fetchSolution(urlJSON)
  })

  /*
   * Fetch solution via JSON and populate layout
   */
  function fetchSolution (urlJSON) {
    // Reset layout
    $(window).scrollTop(0)
    $('.solution-images').html('<div style="text-align:center;height:220px;padding-top:180px"><img alt="" src="/img/blog-ajax-loader.gif"></div>')
    $('.author-avatar').attr('src', '/img/default_small_avatar.png')
    $('.rating-image', '.solution-rating').removeClass('star0 star1 star2 star3 star4 star5')
    $('.description-more').hide()
    $('#newComment', '.solution-left-panel').val('')
    $('.solution-images').html('<div style="text-align:center;height:220px;padding-top:180px"><img alt="" src="/img/blog-ajax-loader.gif"></div>')
    solutionThumbnail = ''
    var solution_tags = $('.solution-tags .tags')
    solution_tags.empty()
    $.getJSON(urlJSON, function (result) {
      $('span#date').text('Опубликовано ' + result.date)
      // Navigation
      $('.solution-prev-area').attr('href', '/pitches/viewsolution/' + result.prev); // @todo Next|Prev unclearly
      $('.solution-next-area').attr('href', '/pitches/viewsolution/' + result.next); // @todo ¿Sorting?
      var paymentContainer = $('.solution-container #step3')
      // hide receipt and buy buttons
      if (result.isSolutionReady == false) {
        $('.summary-price').hide()
        paymentContainer.hide()
      } else {
        if (result.pitch.status == 2) {
          $('.allow-comments', '.solution-left-panel').hide()
        }
        $('.summary-price').show()
        paymentContainer.show()
      }

      // Left Panel
      $('.solution-images').html('')

      if (result.solution.tags) {
        var html = ''
        $.each(result.solution.tags, function (i, v) {
          html += '<li><a href="/logosale?search=' + encodeURIComponent(v) + '">' + v + '</a></li>'
        })
        solution_tags.append(html)
      }
      if (($('input[name="isReadyForLogoale"]').val() == 1) && (result.isSolutionReady == true)) {
        $('.solution-left-panel .solution-title').css('background', 'linear-gradient(#F0EFED, #DFDFDC) repeat scroll 0 0 rgba(0, 0, 0, 0)')
        $('.solution-left-panel .solution-title').children('h1').html('<a style="color: #606060;" href="/pitches/view/' + result.solution.pitch_id + '">' + result.pitch.title + '</a>' + '<br> Новая цена: <span class="price"> ' + result.pitch.total.replace(/\.00/, '') + ' р. с учетом сборов</span> <span class="new-price scrolldown">9500 р.-</span>')
      }
      if (result.pitch.isCopywriting === false) {
        // Main Images
        if (typeof (result.solution.images.solution) == 'undefined') {
          var storage = result.solution.images.solution_solutionView
        } else {
          var storage = result.solution.images.solution
        }

        if (typeof (result.solution.images.solution_gallerySiteSize) != 'undefined') {
          viewsize = result.solution.images.solution_gallerySiteSize
          work = result.solution.images.solution_solutionView
        }else if (typeof (result.solution.images.solution_solutionView != 'undefined')) {
          viewsize = result.solution.images.solution_solutionView
          work = result.solution.images.solution_solutionView
        } else {
          // case when we don't have gallerySiteSize image size
          viewsize = result.solution.images.solution
          work = result.solution.images.solution
        }
        if(result.canViewFullImage === true) {
          viewsize = result.solution.images.solution
        }
        if ((typeof (work) !== 'undefined') && ($.isArray(work))) {
          $.each(work, function (idx, field) {
            if (field.weburl.match(/.mp4$/)) {
              var webmsource = field.weburl.replace(/.mp4/, '.webm')
              var ogvsource = field.weburl.replace(/.mp4/, '.ogv')
              var videoHtml = '<video autoplay loop style="max-width: 600px" poster="' + storage[idx].weburl + '">' +
                '<source src="' + field.weburl + '" type="video/mp4">' +
                '<source src="' + ogvsource + '" type="video/ogg">' +
                '<source src="' + webmsource + '" type="video/webm">' +
                '<img src="' + storage[idx].weburl + '" alt="">' +
                '</video>'
              $('.solution-images').append('<a href="' + field.weburl + '" target="_blank">' + videoHtml + '</a>')
            } else {
              $('.solution-images').append('<a href="' + viewsize[idx].weburl + '" target="_blank"><img src="' + field.weburl + '" class="solution-image" /></a>')
            }
          })
        } else if (typeof (work) !== 'undefined') {
          if (work.weburl.match(/.mp4$/)) {
            var webmsource = work.weburl.replace(/.mp4/, '.webm')
            var ogvsource = work.weburl.replace(/.mp4/, '.ogv')
            var videoHtml = '<video autoplay loop style="max-width: 600px" poster="' + storage.weburl + '">' +
              '<source src="' + work.weburl + '" type="video/mp4">' +
              '<source src="' + ogvsource + '" type="video/ogg">' +
              '<source src="' + webmsource + '" type="video/webm">' +
              '<img src="' + storage.weburl + '" alt="">' +
              '</video>'
            $('.solution-images').append('<a href="' + work.weburl + '" target="_blank">' + videoHtml + '</a>')
          } else {
            $('.solution-images').append('<a href="' + viewsize.weburl + '" target="_blank"><img src="' + work.weburl + '" class="solution-image" /></a>')
          }
        }
        if (typeof (work) !== 'undefined') {
          // Thumbnail Image
          if (typeof (result.solution.images.solution_galleryLargeSize) != 'undefined') {
            viewsize = result.solution.images.solution_galleryLargeSize
          } else {
            // case when we don't have gallerySiteSize image size
            viewsize = result.solution.images.solution
          }
          if ($.isArray(viewsize)) {
            solutionThumbnail = viewsize[ 0 ].weburl
          } else {
            solutionThumbnail = viewsize.weburl
          }
        }
      } else {
        var filesHTML = ''
        var downloadUrl = ''
        if ((typeof (result.solution.images) != 'undefined') && (result.solution.images.length != 0)) {
          if (typeof (result.solution.images.solution) != 'undefined') {
            var file = result.solution.images.solution
          } else {
            var file = result.solution.images.solution_solutionView
          }
          if ($.isArray(file)) {
            if (file[0].weburl.match(/solutions/)) {
              downloadUrl = file[0].weburl
            } else {
              downloadUrl = '/solutionfiles' + file[0].weburl
            }
            filename = file[0].originalbasename
          } else {
            if (file.weburl != false) {
              if (file.weburl.match(/solutions/)) {
                downloadUrl = file.weburl
              } else {
                downloadUrl = '/solutionfiles' + file.weburl
              }
              filename = file.originalbasename
            }
          }
          if (downloadUrl != '') {
            filesHTML += '<div class="clip-document-container"><a href="' + downloadUrl + '" class="clip-document">' + filename + '</a></div>'
          }
        }
        $('.solution-images').append(filesHTML + '<div class="preview"> \
                    <span>' + result.solution.description + '</span> \
                </div>')
      }

      var firstImage = $('.solution-image', '.solution-overlay').first().parent()
      if ((currentUserId == result.pitch.user_id) || isCurrentAdmin) { // isClient
        var ratingWidget = $('<div class="separator-rating"> \
                    <div class="separator-left"></div> \
                    <div class="rating-widget"><span class="left">выставьте</span> \
                            <span id="star-widget"></span> \
                    <span class="right">рейтинг</span></div> \
                    <div class="separator-right"></div> \
                    </div>')
        if (firstImage.length > 0) {
          ratingWidget.insertAfter(firstImage)
        } else {
          ratingWidget.insertAfter('.preview:visible')
          $('.separator-rating').css({'margin-top': '20px', 'margin-bottom': '20px'})
        }

        $('#star-widget').raty({
          path: '/img',
          hintList: ['не то!', 'так себе', 'возможно', 'хорошо', 'отлично'],
          starOff: 'solution-star-off.png',
          starOn: 'solution-star-on.png',
          start: result.solution.rating,
          click: function (score, evt) {
            $.post('/solutions/rating/' + $('input[name=solution_id]').val() + '.json',
              {'id': result.solution.id, 'rating': score}, function (response) {
                $('.rating-image', '.solution-rating').removeClass('star0 star1 star2 star3 star4 star5')
                $('.rating-image', '.solution-rating').addClass('star' + score)
                var $underlyingRating = $('.ratingcont', '#li_' + $('.isField.number', '.solution-overlay').text())
                if ($underlyingRating.length > 0) {
                  $underlyingRating.css('background-image', 'url(/img/' + score + '-rating.png)')
                  $underlyingRating.data('default', score)
                }
              })
          }
        })
      } else if (currentUserId == result.solution.user_id) {
        // Solution Author views nothing
      } else { // Any User
        if ((result.pitch.status != 1) && (result.pitch.status != 2)) {
          var already = ''
          if (result.likes == true) {
            already = ' already'
          }
          $('<div class="like-wrapper"><div class="left">поддержи</div> \
                       <a class="like-widget' + already + '" data-id="' + result.solution.id + '"></a> \
                       <div class="right">автора</div></div>').insertAfter($('.solution-image').last().parent())

          $('.like-widget[data-id=' + result.solution.id + ']').click(function () {
            $(this).toggleClass('already')
            var counter = $('.value-likes')
            var solutionId = $(this).data('id')
            if ($(this).hasClass('already')) {
              $.post('/solutions/like/' + solutionId + '.json', {'uid': currentUserId}, function (response) {
                counter.text(parseInt(response.likes))
                $('.underlying-likes[data-id=' + result.solution.id + ']').text(parseInt(response.likes))
              })
            } else {
              $.post('/solutions/unlike/' + solutionId + '.json', {'uid': currentUserId}, function (response) {
                counter.text(parseInt(response.likes))
                $('.underlying-likes[data-id=' + result.solution.id + ']').text(parseInt(response.likes))
              })
            }
            return false
          })
        }
      }

      $('#newComment', '.solution-left-panel').val('#' + result.solution.num + ', ')
      solutionId = result.solution.id

      if (result.comments) {
        $('.solution-comments').html(fetchCommentsNew(result))
        solutionTooltip()
      }

      // Right Panel
      $('.number', '.solution-number').text(result.solution.num || '')
      $('.rating-image', '.solution-rating').addClass('star' + result.solution.rating).attr('data-rating', result.solution.rating)
      if (result.userAvatar) {
        $('.author-avatar').attr('src', result.userAvatar)
      } else {
        $('.author-avatar').attr('src', '/img/default_small_avatar.png')
      }
      $('.author-name').attr('href', '/users/view/' + result.solution.user_id).text(result.solution.user.first_name + ' ' + result.solution.user.last_name.substring(0, 1) + '.')
      if (result.userData && result.userData.city) {
        $('.author-from').text(result.userData.city); // @todo Try to unserialize userdata on Clientside
      } else {
        $('.author-from').text('')
      }

      if (result.pitch.isCopywriting === false) {
        var desc = result.solution.description
        var viewLength = 100 // Description string cut length parameter
        const solutionDescription = $('.solution-description')
        $('.edit-description-textarea').remove()
        $('.update-description-button').hide()
        solutionDescription.show()
        if (desc.length > viewLength) {
          var descBefore = desc.slice(0, viewLength - 1)
          descBefore = descBefore.substr(0, Math.min(descBefore.length, descBefore.lastIndexOf(' ')))
          var descAfter = desc.slice(descBefore.length)
          solutionDescription.html(descBefore)
          var showMoreLink = $('.description-more')
          showMoreLink.show(500).on('click', function () {
            solutionDescription.append(descAfter)
            descAfter = ''
            showMoreLink.hide()
          })
          if (result.solution.user_id == currentUserId) {
            $('.edit-description-link').remove()
            showMoreLink.after('<a href="#" data-solutionid="' + result.solution.id + '" class="edit-description-link">Редактировать</a>')
          }
        } else {
          solutionDescription.html(result.solution.description)
          if (result.solution.user_id == currentUserId) {
            $('.edit-description-link').remove()
            solutionDescription.after('<a href="#" data-solutionid="' + result.solution.id + '" class="edit-description-link">Редактировать</a>')
          } else {
            $('.edit-description-link').remove()
          }
        }
        if (result.solution.description != '') {
          const dateSpan = $('#date', '.solution-info:visible')
          const breakAfterDateSpan = dateSpan.nextUntil('.solution-description', 'br')
          if (breakAfterDateSpan.length < 2) {
            dateSpan.after('<br />')
          }
        }
      } else {
        $('.solution-description').html('')
        $('.solution-about').next().hide()
        $('.solution-about').hide()
        $('.solution-share').next().hide()
        $('.solution-share').hide()
        var html = '<div class="attach-wrapper">'
        if (result.solution.images.solution) {
          if ($.isArray(result.solution.images.solution)) {
            $.each(result.solution.images.solution, function (index, object) {
              if (object.weburl.match(/solutions/)) {
                var downloadUrl = object.weburl
              } else {
                var downloadUrl = '/solutionfiles' + object.weburl
              }
              html += '<a target="_blank" href="' + downloadUrl + '" class="attach">' + object.originalbasename + '</a><br>'
            })
          } else {
            let downloadUrl = ''
            if (result.solution.images.solution.weburl.match(/solutions/)) {
              downloadUrl = result.solution.images.solution.weburl
            } else {
              downloadUrl = '/solutionfiles' + result.solution.images.solution.weburl
            }
            html += '<a href="' + downloadUrl + '" class="attach">' + result.solution.images.solution.originalbasename + '</a>'
          }
          html += '</div>'
          $('.solution-description').prev().html('ФАЙЛЫ')
          $('.solution-description').html(html)
        }
      }

      // Copyrighted Materials
      var copyrightedHtml = '<div class="solution-copyrighted"><!--  --></div>'
      if ((result.solution.copyrightedMaterial == 1) && ((currentUserId == result.pitch.user_id) || (currentUserId == result.solution.user_id) || (isCurrentAdmin))) {
        copyrightedHtml = copyrightedInfo(result.copyrightedInfo)
      }
      $('.solution-copyrighted').replaceWith(copyrightedHtml)

      $('.value-views', '.solution-stat').text(result.solution.views || 0)
      $('.value-likes', '.solution-stat').text(result.solution.likes || 0)
      $('.value-comments', '.solution-stat').text(result.comments.length || 0)

      if (result.pitch.isCopywriting === false) {
        var media = 'https://www.godesigner.ru'
        if ($.isArray(result.solution.images.solution_solutionView)) {
          media += result.solution.images.solution_solutionView[0].weburl
        } else {
          media += result.solution.images.solution_solutionView.weburl
        }
        var readyForLogosale = false
        var parsed = result.pitch.totalFinishDate.split(/[- :]/)
        var pitchFinishedDate = new Date(parsed[0], parsed[1] - 1, parsed[2], parsed[3], parsed[4], parsed[5])
        var now = new Date()
        var timeDiff = Math.abs(now.getTime() - pitchFinishedDate.getTime())
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24))
        if ((result.solution.id != result.pitch.awarded) && (result.pitch.category_id == 1) && (result.pitch.private != 1) && (result.pitch.status == 2) && (diffDays > 30)) {
          readyForLogosale = true
        }
        // Twitter like solution message
        var tweetLike = 'Мне нравится этот дизайн! А вам?'
        if (readyForLogosale) {
          tweetLike += ' Этот логотип можно приобрести у автора за 9500 рублей на распродаже!'
        }
        if (Math.floor((Math.random() * 100) + 1) <= 50) {
          tweetLike = 'Из всех ' + result.pitch.ideas_count + ' мне нравится этот дизайн'
          if (readyForLogosale) {
            tweetLike = 'Этот логотип можно приобрести у автора за 9500 рублей на распродаже; адаптация названия и 2 правки включены»'
          }
        }

        var shareTitle = tweetLike
        var url = 'https://www.godesigner.ru/pitches/viewsolution/' + result.solution.id
        var sharebar = '<div style="display: block; height: 75px"> \
                <div class="social-likes" data-counters="no" data-url="' + url + '" data-title="' + shareTitle + '"> \
                <div class="facebook" style="display: inline-block;" title="Поделиться ссылкой на Фейсбуке" data-url="' + url + '">SHARE</div> \
                <div class="twitter" style="display: inline-block;">TWITT</div> \
                <div class="vkontakte" style="display: inline-block;" title="Поделиться ссылкой во Вконтакте" data-image="' + media + '" data-url="' + url + '">SHARE</div> \
                <div class="pinterest" style="display: inline-block;" title="Поделиться картинкой на Пинтересте" data-url="' + url + '" data-media="' + media + '">PIN</div></div></div>'
        var fullshareblock = '<h2>ПОДЕЛИТЬСЯ</h2><div class="body" style="display: block;">' + sharebar + '</div>'
        $('.solution-share').html(fullshareblock)
        $('.social-likes').socialLikes()
      }

      if (currentUserId == result.pitch.user_id) {
        var html = ''
        if (result.solution.hidden == 1) {
          html += '<a class="client-show" href="#" data-id="' + result.solution.id + '">Сделать видимой</a>'
        } else {
          html += '<a class="client-hide" href="#" data-id="' + result.solution.id + '">С глаз долой</a>'
        }
        html += '<a class="client-comment" href="#">Комментировать</a>'
        html += '<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a>'
        if ((result.selectedsolution != true) && (result.pitch.status != 0)) {
          html += '<a class="select-winner-popup" href="/solutions/select/' + result.solution.id + '.json" data-solutionid="' + result.solution.id + '" data-user="' + result.solution.user.first_name + ' ' + result.solution.user.last_name.substring(0, 1) + '." data-num="' + result.solution.num + '" data-userid="' + result.solution.user_id + '">Назначить победителем</a>'
        }
        $('.solution-abuse').html(html)
      } else if ((currentUserId == result.solution.user_id) || isCurrentAdmin) {
        $('.solution-abuse').html('<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a> \
                    <a class="delete-solution-popup hide" data-solution="' + result.solution.id + '" href="/solutions/delete/' + result.solution.id + '.json">Удалить</a>')
      } else {
        $('.solution-abuse').html('<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a>')
      }
      FB.XFBML.parse()
      $.getScript('/js/pitches/gallery.twitter.js', function () {
        if (typeof (twttr) != 'undefined') {
          twttr.widgets.load()
        }
      })
      var receipt = $('.summary-price').last()
      if (receipt.length > 0) {
        var offset = receipt.offset()
        popupReady = true
        receiptOffsetTop = offset.top
      }
    })
  }
})

// Rating Popup
function fireRatingPopup () {
  var el = '<div id="popup-rating"> \
                <a class="modalCloseImg popup-rating-close" title="Close"></a> \
                <h1 class="largest-header">Возврат средств недоступен</h1> \
                <p>Возврат средств по окончанию проекта доступен тогда, когда средний балл вашей активности не меньше трех.</p> \
                <p>На данный момент ваш средний балл активности ниже трех. Это означает, что оставлено недостаточно комментариев или не всем выставлен рейтинг.</p> \
                <p><a href="/answers/view/71">Как это исправить?</a></p> \
              </div>'
  $('body').append(el)
  $('#popup-rating').modal({
    containerId: 'popup-rating-box',
    opacity: 80,
    closeClass: 'popup-rating-close',
    onShow: function () {
      $('#popup-rating-box').animate({opacity: 1}, 800)
    }
  })
}

// Winner Popup for Private Pitches
function fireWinnerPopup (whom) {
  var el = '<div id="popup-rating"> \
                <a class="modalCloseImg popup-rating-close" title="Close"></a> \
                <h1 class="largest-header">Это закрытый проект!</h1> \
                <p>Просмотр решений других участников доступен только заказчику и привилегированному меньшинству — победителям GoDesigner.</p><p>Побеждайте в проектах, и работа на сервисе станет для вас еще интереснее!</p> \
                <p><a href="/answers/view/64">Подробнее</a></p> \
              </div>'
  if (whom == 'win') {
    el = '<div id="popup-rating"> \
                <a class="modalCloseImg popup-rating-close" title="Close"></a> \
                <h1 class="largest-header">Та-даам!</h1> \
                <p>Поздравляем, вы — член элитного общества на GoDesigner: вы не раз принимали участие в проектах и, главное, становились победителем. Именно поэтому мы предоставляем вам эксклюзивную привилегию: вы сможете видеть работы других участников в закрытых проектах.<br> \
                Соблюдение условий соглашения о неразглашении является по-прежнему обязательным. Спасибо за понимание и творческих успехов!</p> \
                <p><a href="/answers/view/64">Подробнее</a></p> \
              </div>'
  }
  $('body').append(el)
  $('#popup-rating').modal({
    containerId: 'popup-rating-box',
    opacity: 80,
    closeClass: 'popup-rating-close',
    onShow: function () {
      $('#popup-rating-box').animate({opacity: 1}, 800)
    }
  })
}

// Show Separator after Gallery Postload
function checkSeparator () {
  if ($('#newComment').length == 0) {
    $('.separator', '.isField.pitch-comments').first().show()
  }
}
