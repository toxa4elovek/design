;(function () {
  $(function () {
    let tags = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: '/pitches/getags/?name=%QUERY'
    })

    tags.initialize()
    console.log(tags)

    $('#searchTerm').typeahead(null, {
      name: 'tags',
      displayKey: 'name',
      source: tags.ttAdapter()
    }).on('typeahead:selected', function (obj, val) {
      var box = '<li style="margin-left:6px;">' + val.name + '<a class="removeTag" href="#"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>'
      $(box).appendTo('#filterbox')
      $('#searchTerm').val('')
      if ($('#filterbox').children().length == 5) {
        $('#filterContainer').removeClass('error-searhTerm')
      }
      recalculateBox()
    })

    let paramsSearch = {}

    $('#searchTerm').keyup(function (event) {
      if (event.keyCode == 13) {
        const search = $('#searchTerm')
        isBusy = false
        fetchSearch(search)
        const image = '/img/filter-arrow-down.png'
        $('#filterToggle').data('dir', 'up')
        $('img', '#filterToggle').attr('src', image)
        $('#filtertab').hide()
        return false
      }else if (event.keyCode == 8) {
        if (($('#filterbox li').length > 0) && ($('#searchTerm').val() == '')) {
          $('.removeTag', '#filterbox li:last').click()
        }
      }
    })

    $('#searchTerm').on('focus', function () {
      $('#filterContainer').css('border', '4px solid rgb(231, 231, 231)')
      $('#filterContainer').css('box-shadow', 'none')
    })

    $(document).on('blur', '#searchTerm', function () {
      $('#filterContainer').css('box-shadow', '0 1px 2px rgba(0, 0, 0, 0.2) inset')
      $('#filterContainer').css('border', '4px solid #F3F3F3')
    })

    $('.slider').each(function (index, object) {
      var value = 5
      if (typeof (slidersValue) != 'undefined') {
        value = slidersValue[index]
      }
      $(object).slider({
        disabled: false,
        value: value,
        min: 1,
        max: 9,
        step: 1,
        slide: function (event, ui) {
          var rightOpacity = (((ui.value - 1) * 0.08) + 0.36).toFixed(2)
          var leftOpacity = (1 - ((ui.value - 1) * 0.08)).toFixed(2)
          $(ui.handle).parent().parent().next().css('opacity', rightOpacity)
          $(ui.handle).parent().parent().prev().css('opacity', leftOpacity)
        },
        stop: function (event, ui) {
          var search = $('#searchTerm')
          isBusy = false
          fetchSearch(search)
          var image = '/img/filter-arrow-down.png'
          $('#filterToggle').data('dir', 'up')
          $('img', '#filterToggle').attr('src', image)
          $('#filtertab').hide()
          return false
        }
      })
    })

    $('#adv_search').on('click', function () {
      var button = $(this)
      $('#groups').toggle('fast', function () {
        if (button.hasClass('active')) {
          button.removeClass('active')
        } else {
          $('ul.marsh').hide()
          button.addClass('active')
        }
      })
      return false
    })
    var isBusy = false,
      page = 1,
      gallery = $('.portfolio_gallery')

    var receiptOffsetTop = 0
    var popupReady = false

    $(window).on('scroll', function () {
      if (($('.solution-sale:visible').length == 1) && (popupReady)) {
        var receipt = $('.summary-price')
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

    $(window).on('scroll', function () {
      if (((gallery.height() - 200) - $(window).scrollTop() < 400) && !isBusy) {
        isBusy = true
        $('#officeAjaxLoader').show()
        page += 1
        var queryDict = {}
        location.search.substr(1).split('&').forEach(function (item) {queryDict[item.split('=')[0]] = item.split('=')[1]})
        if (typeof (queryDict['search']) != 'undefined') {
          var search_list = []
          search_list.push(decodeURIComponent(queryDict['search']))
          var search = $('#searchTerm')
          paramsSearch.search = (search.hasClass('placeholder')) ? '' : search.val()
          paramsSearch.search_list = search_list
        }
        var url = ($.isEmptyObject(paramsSearch)) ? '/solutions/logosale/' : '/solutions/search_logo/'
        if (url != '/solutions/logosale/') {
          $('ul.marsh').hide()
          $('.portfolio_gallery').css('margin-top', '40px')
        }
        $.post(url + page + '.json', paramsSearch, function (response) {
          var html = ''
          keys = []
          $.each(response.solutions, function (index, solution) {
            keys.push(solution)
          })
          keys = keys.sort(function (obj1, obj2) {
            if (obj2.sort > obj1.sort) { return -1;}
            if (obj2.sort < obj1.sort) { return 1;}
            if (obj2.rating > obj1.rating) { return 1;}
            if (obj2.rating < obj1.rating) { return -1;}
            if (obj2.likes > obj1.likes) { return 1;}
            if (obj2.likes < obj1.likes) { return -1;}
            if (obj2.views > obj1.views) { return 1;}
            if (obj2.views < obj1.views) { return -1;}
            return 0
          })
          $.each(keys, function (index, solution) {
            html += addSolution(solution)
          })
          var $prependEl = $(html)
          $prependEl.hide()
          $prependEl.appendTo('.list_portfolio').slideDown('slow')
          $('#officeAjaxLoader').hide()
          if ((typeof (response.solutions) == 'object') && (Object.keys(response.solutions).length > 0)) {
            isBusy = false
          } else {
            isBusy = true
          }
        })
      }
    })

    function getImageCount (images) {
      if (images && typeof (images[0]) != 'undefined') {
        return images.length
      } else {
        return 1
      }
    }

    function addSolution (solution) {
      var picCounter2 = 0
      var html = ''
      if ($.isEmptyObject(solution.images)) {
        return html
      }
      if (solution.images.solution_galleryLargeSize && typeof solution.images.solution_galleryLargeSize[0] != 'undefined') {
        picCounter2 = solution.images.solution_galleryLargeSize.length
      } else if (typeof solution.images.solution_galleryLargeSize == 'undefined') {
        solution.images.solution_galleryLargeSize = solution.images.solution
        if ($.isArray(solution.images.solution_galleryLargeSize)) {
          picCounter2 = solution.images.solution_galleryLargeSize.length
        }
      }
      if (solution.images.solution_galleryLargeSize || solution.images.solution_galleryLargeSize.length > 0) {
        var multiclass = (picCounter2 > 1) ? ' class=multiclass' : ''
        html += '<li id="li_' + solution.id + '"' + multiclass + '>\
                            <div class="photo_block">'
        if (getImageCount(solution.images.solution_galleryLargeSize) > 1) {
          html += '<div class="image-count">' + getImageCount(solution.images.solution_solutionView) + '</div>'
        }
        html += '<a data-solutionid="' + solution.id + '" class="imagecontainer" href="/pitches/viewsolution/' + solution.id + '">'

        if (solution.images.solution_galleryLargeSize && typeof solution.images.solution_galleryLargeSize[0] == 'undefined') {
          html += '<img rel="#' + solution.num + '"  width="180" height="135" src="' + solution.images.solution_galleryLargeSize.weburl + '">'
        } else {
          var picCounter = 0
          $.each(solution.images.solution_galleryLargeSize, function (index, img) {
            var display = (picCounter > 0) ? 'display:none;' : 'opacity:1;'
            html += '<img class="multi"  width="180" height="135" style="position: absolute;left:10px;top:9px;z-index:1;' + display + '" rel="#' + solution.num + '" src="' + img.weburl + '">'
            picCounter++
          })
        }
        var tweetLike = 'Этот логотип можно приобрести у автора за 8500 рублей на распродаже; адаптация названия и 2 правки включены»'
        var media = 'https://godesigner.ru'
        if ($.isArray(solution.images.solution_solutionView)) {
          media += solution.images.solution_solutionView[0].weburl
        } else {
          media += solution.images.solution_solutionView.weburl
        }

        var shareTitle = tweetLike
        var url = 'https://godesigner.ru/pitches/viewsolution/' + solution.id

        var sharebar = '<div class="sharebar"><div class="tooltip-block"> \
                    <div class="social-likes" data-counters="no" data-url="' + url + '" data-title="' + shareTitle + '"> \
                    <div class="facebook" style="display: inline-block;" title="Поделиться ссылкой на Фейсбуке" data-url="' + url + '">SHARE</div> \
                    <div class="twitter" style="display: inline-block;">TWITT</div> \
                    <div class="vkontakte" style="display: inline-block;" title="Поделиться ссылкой во Вконтакте" data-image="' + media + '" data-url="' + url + '">SHARE</div> \
                    <div class="pinterest" style="display: inline-block;" title="Поделиться картинкой на Пинтересте" data-url="' + url + '" data-media="' + media + '">PIN</div></div></div></div>'

        html += '</a>\
                    <div class="photo_opt">\
                        <div class="" style="display: block; float:left;">\
                            <span class="rating_block">\
                                <div class="ratingcont" data-default="<?= $solution->rating ?>" data-solutionid="' + solution.id + '" style="float: left; height: 9px; background: url(/img/' + solution.rating + '-rating.png) repeat scroll 0% 0% transparent; width: 56px;"></div>\
                            </span>\
                            <span class="like_view" style="margin-top:2px;">\
                                <img src="/img/looked.png" alt="" class="icon_looked"><span>' + solution.views + '</span>\
                            </span>\
                        </div>\
                        <ul style="margin-left: 78px;" class="right">\
                            <li class="like-hoverbox" style="float: left; margin-top: 0px; padding-top: 0px; height: 15px; padding-right: 0px; margin-right: 0px; width: 38px;">\
                                <a href="#" style="float:left" class="like-small-icon" data-id="' + solution.id + '"><img src="/img/like.png" alt="количество лайков"></a>\
                                <span class="underlying-likes" style="color: rgb(205, 204, 204); font-size: 10px; vertical-align: middle; display: block; float: left; height: 16px; padding-top: 5px; margin-left: 2px;" data-id="' + solution.id + '" rel="https://godesigner.ru/pitches/viewsolution/' + solution.id + '">' + solution.likes + '</span>' + sharebar + '</li>\
                            <li style="padding-left:0;margin-left:0;float: left; padding-top: 1px; height: 16px; margin-top: 0;width:30px">\
                                <span class="bottom_arrow">\
                                    <a href="#" class="solution-menu-toggle"><img src="/img/marker5_2.png" alt=""></a>\
                                </span>\
                            </li>\
                        </ul>\
                    </div>\
                </div>\
                <div class="selecting_numb"><span class="price">' + solution.pitch.total.replace(/(\.00)/, '') + ' р.</span><span class="new-price">8500р.-</span></div>\
                    <div class="solution_menu" style="display: none;">\
                        <ul class="solution_menu_list">\
                            <li class="sol_hov"><a data-solutionid="' + solution.id + '" class="imagecontainer" href="/pitches/viewsolution/' + solution.id + '">Купить</a></li>\
                            <li class="sol_hov"><a href="/solutions/warn/' + solution.id + '.json" class="warning" data-solution-id="' + solution.id + '">Пожаловаться</a></li>\
                        </ul>\
                    </div>\
            </li>'
      }
      return html
    }

    $(document).on('mouseover', '.solution-menu-toggle', function () {
      $('img', $(this)).attr('src', '/img/marker-green.png')
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
      menu.offset({top: offset.top + 178, left: offset.left + 47})
      menu.fadeIn(200)
      $(menu).on('mouseleave', function () {
        $(this).fadeOut(200, function () {
          $(this).remove()
        })
      })
      $('.solution-info').on('mouseenter', function () {
        $('.solution_menu.temp').fadeOut(200, function () {
          $(this).remove()
        })
      })
    })

    $(document).keyup(function (e) {
      if (e.which == 27 && $('.solution-sale').is(':visible')) {
        window.history.back()
      }
    })

    var prev = null
    var next = null

    $(document).on('click', '.imagecontainer', function (e) {
      var parent = $(this).parent().parent()
      var prevLi = parent.prev()
      var nextLi = parent.next()
      if (prevLi.length == 0) {
        prevLi = $('.main_portfolio').children().last()
      }
      if (nextLi.length == 0) {
        nextLi = $('.main_portfolio').children().first()
      }
      prev = $('.imagecontainer', prevLi).data('solutionid')
      next = $('.imagecontainer', nextLi).data('solutionid')

      if (/designers/.test(window.location.pathname)) {
        return true
      }
      if (window.history.pushState) {
        window.history.pushState('object or string', 'Title', this.href); // @todo Check params
      } else {
        window.location = $(this).attr('href')
        return false
      }
      $('.solution-overlay-dummy').appendTo('body').addClass('solution-overlay')
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
      var queryParam = '?exp=1'
      if (document.URL.indexOf('?') != -1) {
        queryParam = document.URL.slice(document.URL.indexOf('?'))
      }
      var urlJSON = window.location.pathname + '.json' + queryParam
      fetchSolution(urlJSON)
      return false
    })

    $('#goSearch, #goSearchAplly').on('click', function () {
      const search = $('#searchTerm')
      const image = '/img/filter-arrow-down.png'
      const advSearch = $('#adv_search')
      $('ul.marsh').hide()
      if (advSearch.hasClass('active')) {
        advSearch.click()
      }
      isBusy = false
      const newAddress = '?search=' + encodeURIComponent(search.val().trim())
      fetchSearch(search)
      $('#filterToggle').data('dir', 'up')
      $('img', '#filterToggle').attr('src', image)
      $('#filtertab').hide()
      return false
    })

    $('.look-variants').on('change', function () {
      var search = $('#searchTerm')
      isBusy = false
      fetchSearch(search, false)
      var image = '/img/filter-arrow-down.png'
      $('#filterToggle').data('dir', 'up')
      $('img', '#filterToggle').attr('src', image)
      $('#filtertab').hide()
      return false
    })

    function fetchSearch (search, close, isClicked) {
      close || (close = false)
      var filterbox = $('#filterbox')
      if (search.val().trim().length > 0 && !search.hasClass('placeholder')) {
        if (typeof (isClicked) == 'undefined') {
          var box = '<li style="margin-left:6px;">' + search.val().replace(/[^A-Za-zА-Яа-яЁё0-9-]/g, '').trim() + '<a class="removeTag" href="#"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>'
        }else if (isClicked) {
          var box = '<li style="margin-left:6px;">' + search.val().replace(/[^A-Za-zА-Яа-яЁё0-9-\/\s]/g, '').trim() + '<a class="removeTag" href="#"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>'
        }
        search.val('')
        var filter = $('#filterbox')
        $(box).appendTo(filter)
        recalculateBox()
      }
      var params = {},
        adv_search = $('#adv_search'),
        search_list = []
      if (filterbox.children().length > 0 || adv_search.hasClass('active')) {
        $('.marsh').hide()
        $('#logosaleAjaxLoader').show()
        $('ul.marsh').hide()
        if (adv_search.hasClass('active')) {
          if ($('.look-variants').length) {
            var variants = [ ]
            var checked = $('input:checked', '.look-variants')
            $.each(checked, function (index, object) {
              variants.push($(object).data('id'))
            })
            params.variants = variants
          }
          var slider = $('.slider')
          if (slider.length > 0) {
            var prop = [ ]
            $.each(slider, function (i, object) {
              prop.push($(object).slider('value'))
            })
            params.prop = prop
          }
        }
        $.each(filterbox.children(), function (i, v) {
          if (i == 0) {
            History.pushState(null, null, '?search=' + $(v).text())
          }
          search_list.push($(v).text())
        })
        if (close && adv_search.hasClass('active')) {
          $('#groups').toggle('fast')
          adv_search.removeClass('active')
        }
        $('ul.marsh').hide()
        params.search_list = search_list
        params.search = (search.hasClass('placeholder')) ? '' : search.val()
        $.post('/solutions/search_logo.json', params, function (response) {
          var html = '', sol_count = 0, hash = ''
          paramsSearch = params
          page = 1
          keys = []
          $.each(response.solutions, function (index, solution) {
            keys.push(solution)
          })
          keys = keys.sort(function (obj1, obj2) {
            // Ascending: first age less than the previous
            if (obj2.sort > obj1.sort) { return -1;}
            if (obj2.sort < obj1.sort) { return 1;}
            if (obj2.rating > obj1.rating) { return 1;}
            if (obj2.rating < obj1.rating) { return -1;}
            if (obj2.likes > obj1.likes) { return 1;}
            if (obj2.likes < obj1.likes) { return -1;}
            if (obj2.views > obj1.views) { return 1;}
            if (obj2.views < obj1.views) { return -1;}
            return 0
          })
          $.each(keys, function (index, solution) {
            hash = html
            html = html + addSolution(solution)
            if (hash != html) {
              sol_count++
            }
          })
          $('.list_portfolio').empty()
          var $prependEl = $(html)
          $prependEl.hide()
          $('#logosaleAjaxLoader').hide()
          $('#logo_found').text('')
          if (response.total_solutions == 0) {
            $('#search_result').css('padding-bottom', '0')
          }
          $('ul.marsh').hide()
          $('#search_result').show()
          if (sol_count < 1) {
            $('#not-found-container').show()
          } else {
            $('#not-found-container').hide()
          }
          $('ul.marsh').hide()
          $prependEl.appendTo('.list_portfolio').slideDown('slow', function () {
            $('.social-likes').socialLikes()
          })
          $('.social-likes').socialLikes()
        })
      }
    }

    /*
    $('#searchTerm').keyboard('space', function () {
        if ($(this).val().trim() != '') {
            var box = '<li style="margin-left:6px;">' + $(this).val().replace(/[^A-Za-zА-Яа-яЁё0-9-]/g, "").trim() + '<a class="removeTag" href="#"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>'
            $(this).val('')
            var filter = $('#filterbox')
            $(box).appendTo(filter)
            recalculateBox()
        }
    }).keyboard('enter', function () {
        if ($(this).val().trim() != '') {
            var box = '<li style="margin-left:6px;">' + $(this).val().replace(/[^A-Za-zА-Яа-яЁё0-9-]/g, "").trim() + '<a class="removeTag" href="#"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>'
            $(this).val('')
            var filter = $('#filterbox')
            $(box).appendTo(filter)
            recalculateBox()
        }
        return false
    })
    */
    $('#searchTerm').keyboard('enter', function () {
      if ($(this).val().trim() != '') {
        var box = '<li style="margin-left:6px;">' + $(this).val().replace(/[^\sA-Za-zА-Яа-яЁё0-9-]/g, '').trim() + '<a class="removeTag" href="#"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>'
        $(this).val('')
        var filter = $('#filterbox')
        $(box).appendTo(filter)
        recalculateBox()
      }
      return false
    })

    $(document).on('click', '.removeTag', function (e) {
      e.preventDefault()
      $(this).parent().remove()
    })

    var recalculateBox = function () {
      var baseWidth = 524,
        filterbox = $('#filterbox')
      $.each(filterbox.children(), function (index, object) {
        baseWidth -= $(object).width() + 100
      })
      $('#searchTerm').width(baseWidth)
      $('#filterContainer .tt-hint').width(baseWidth)
    }

    $('#searchTerm').keyboard('backspace', function () {
      if (($('#filterbox li').length > 0) && ($('#searchTerm').val() == '')) {
        $('.removeTag', '#filterbox li:last').click()
      }
    })

    $(document).on('click', function (e) {
      if ($(e.target).is('#searchTerm')) {
        var $el = $('#filterToggle')
        var dir = $el.data('dir')
        if (dir == 'up') {
          var image = '/img/filter-arrow-up.png'
          $el.data('dir', 'down')
        } else {
          var image = '/img/filter-arrow-down.png'
          $el.data('dir', 'up')
        }
        $('img', $el).attr('src', image)
        // $('#filtertab').toggle()
        return false
      }
      var image = '/img/filter-arrow-down.png'
      $('#filterToggle').data('dir', 'up')
      $('img', '#filterToggle').attr('src', image)
      $('#filtertab').hide()
    })

    $('.prepTag').on('click', function () {
      var input = $('#searchTerm')
      if (input.hasClass('placeholder')) {
        input.removeClass('placeholder')
      }
      input.val($(this).text())
      fetchSearch(input, false, true)
      var image = '/img/filter-arrow-down.png'
      $('#filterToggle').data('dir', 'up')
      $('img', '#filterToggle').attr('src', image)
      $('#filtertab').hide()
      return false
    })

    $(document).on('mouseleave', '.solution-menu-toggle', function () {
      $('img', $(this)).attr('src', '/img/marker5_2.png')
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
      }
    }

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

    function fetchSolution (urlJSON, scroll) {
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
        var container = $('.imagecontainer[data-solutionid="' + result.solution.id + '"]')
        var parent = container.parent().parent()
        var prevLi = parent.prev()
        var nextLi = parent.next()
        if (prevLi.length == 0) {
          prevLi = $('.main_portfolio').children().last()
        }
        if (nextLi.length == 0) {
          nextLi = $('.main_portfolio').children().first()
        }
        prev = $('.imagecontainer', prevLi).data('solutionid')
        next = $('.imagecontainer', nextLi).data('solutionid')

        $('.solution-prev-area').attr('href', '/pitches/viewsolution/' + prev + '.json?exp=1')
        $('.solution-next-area').attr('href', '/pitches/viewsolution/' + next + '.json?exp=1')

        if (result.solution.tags) {
          var html = ''
          $.each(result.solution.tags, function (i, v) {
            html += '<li><a href="/logosale?search=' + encodeURIComponent(v) + '">' + v + '</a></li>'
          })
          solution_tags.append(html)
        }

        // Left Panel
        $('.solution-images').html('')
        // $('.solution-left-panel .solution-title').addClass('nodecoration').data('href', '/pitches/view/' + result.pitch.id)
        $('.solution-left-panel .solution-title').children('h1').html('<a href="/pitches/view/' + result.solution.pitch_id + '">' + result.pitch.title + '</a>' + '<br> Новая цена: <span class="price"> ' + result.pitch.total.replace(/\.00/, '') + ' р. с учетом сборов</span> <span class="new-price scrolldown">8500 р.-</span>')
        if ((result.solution.images.solution) && (result.pitch.category_id != 7)) {
          // Main Images
          if (typeof (result.solution.images.solution_gallerySiteSize) != 'undefined') {
            viewsize = result.solution.images.solution_gallerySiteSize
            work = result.solution.images.solution_solutionView
          } else {
            // case when we don't have gallerySiteSize image size
            viewsize = result.solution.images.solution
            work = result.solution.images.solution
          }
          if ($.isArray(result.solution.images.solution)) {
            $.each(work, function (idx, field) {
              $('.solution-images').append('<a href="' + viewsize[idx].weburl + '" target="_blank"><img src="' + field.weburl + '" class="solution-image" /></a>')
            })
          } else {
            $('.solution-images').append('<a href="' + viewsize.weburl + '" target="_blank"><img src="' + work.weburl + '" class="solution-image" /></a>')
          }
          // Thumbnail Image
          if (typeof (result.solution.images.solution_galleryLargeSize) != 'undefined') {
            viewsize = result.solution.images.solution_galleryLargeSize
          } else {
            // case when we don't have gallerySiteSize image size
            viewsize = result.solution.images.solution
          }
          if ($.isArray(viewsize)) {
            solutionThumbnail = viewsize[0].weburl
          } else {
            solutionThumbnail = viewsize.weburl
          }
        } else {
          $('.solution-images').append('<div class="preview"> \
                        <span>' + result.solution.description + '</span> \
                    </div>')
        }

        var firstImage = $('.solution-image', '.solution-overlay').first().parent()
        if (currentUserId == result.pitch.user_id) { // isClient
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

          $('.solution-overlay #star-widget').raty({
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

        var desc = result.solution.description
        var viewLength = 100 // Description string cut length parameter
        if (desc.length > viewLength) {
          var descBefore = desc.slice(0, viewLength - 1)
          descBefore = descBefore.substr(0, Math.min(descBefore.length, descBefore.lastIndexOf(' ')))
          var descAfter = desc.slice(descBefore.length)
          $('.solution-description').html(descBefore)
          $('.description-more').show(500)
          $('.description-more').on('click', function () {
            $('.solution-description').append(descAfter)
            descAfter = ''
            $('.description-more').hide()
          })
        } else {
          $('.solution-description').html(result.solution.description)
        }
        if (result.solution.description != '') {
          $('span#date').after('<br />')
        }
        if (scroll) {
          // $('.scrolldown').click()
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
        if ((result.pitch.awarded !== result.solution.id) && ((currentUserId == result.solution.user_id) || isCurrentAdmin)) {
          $('.solution-abuse').html('<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a> \
                        <a class="delete-solution" href="/solutions/delete/' + result.solution.id + '" data-solution="' + result.solution.id + '">Удалить</a>')
        } else {
          $('.solution-abuse').html('<a class="abuse warning" href="/solutions/warn/' + result.solution.id + '.json" data-solution-id="' + result.solution.id + '">Пожаловаться</a>')
        }

        if (result.pitch.category_id != 7) {
          var media = 'https://godesigner.ru'
          if ($.isArray(result.solution.images.solution_solutionView)) {
            media += result.solution.images.solution_solutionView[0].weburl
          } else {
            media += result.solution.images.solution_solutionView.weburl
          }
          // Twitter like solution message
          var tweetLike = 'Мне нравится этот дизайн! А вам? Этот логотип можно приобрести у автора за 8500 рублей на распродаже!'
          if (Math.floor((Math.random() * 100) + 1) <= 50) {
            tweetLike = 'Из всех ' + result.pitch.ideas_count + ' мне нравится этот дизайн! Этот логотип можно приобрести у автора за 8500 рублей на распродаже!'
          }

          var shareTitle = tweetLike
          var url = 'https://godesigner.ru/pitches/viewsolution/' + result.solution.id
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
        var receipt = $('.summary-price')
        var offset = receipt.offset()
        popupReady = true
        receiptOffsetTop = offset.top
      })
    }

    $(document).on('click', '#filterToggle', function () {
      var $el = $('#filterToggle')
      var dir = $el.data('dir')
      if (dir == 'up') {
        var image = '/img/filter-arrow-up.png'
        $el.data('dir', 'down')
      } else {
        var image = '/img/filter-arrow-down.png'
        $el.data('dir', 'up')
      }
      $('img', $el).attr('src', image)
      $('#filtertab').toggle()
      return false
    })

    $('body, .solution-overlay').on('click', '.solution-popup-close', function (e) {
      window.history.back()
      hideSolutionPopup()
      return false
    })

    function fetchCommentsNew (result) {
      var fetchedComments = ''
      $.each(result.comments, function (idx, comment) {
        var commentData = prepareCommentData(comment, result)
        fetchedComments += populateComment(commentData)
        if (comment.child) {
          var commentChildData = prepareCommentData(comment.child, result)
          fetchedComments += populateComment(commentChildData)
        }
      })
      return fetchedComments
    }

    function hideSolutionPopup () {
      if ($('.solution-overlay').is(':visible')) {
        $(window).scrollTop(beforeScrollTop)
        $('#pitch-panel').show()
        $('.wrapper', 'body').first().removeClass('wrapper-frozen')
        $('.solution-overlay').hide()
      }
    }

    // Delete Solution
    $(document).on('click', '.delete-solution', function () {
      // Delete without Moderation
      if (!isCurrentAdmin) {
        return true
      }
      var link = $(this)
      if (link.attr('data-pressed') == 'on') {
        window.location = link.attr('href')
      }

      // Show Delete Moderation Overlay
      $('#popup-delete-solution').modal({
        containerId: 'final-step-clean',
        opacity: 80,
        closeClass: 'popup-close',
        onShow: function () {
          $('#model_id', '#popup-delete-solution').val(link.data('solution'))
          link.attr('data-pressed', 'on')
          $(document).on('click', '.popup-close', function () {
            link.attr('data-pressed', 'off')
          })
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
          link.click()
        })
        return false
      })
      return false
    })

    $(document).on('click', '.like-small-icon', function () {
      var likesNum = $(this).next()
      var likeLink = $(this)
      likesNum.html(parseInt(likesNum.html()))
      var sharebar = likesNum.next()
      var solutionId = $(this).data('id')
      $('body').one('click', function () {
        $('.sharebar').fadeOut(300)
      })
      $('.social-likes').socialLikes()
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
      return false
    })

    $(document).on('mouseenter', '.like-hoverbox', function () {
      $('img:first', $(this)).attr('src', '/img/like_hover.png')
    })

    $(document).on('mouseleave', '.like-hoverbox', function () {
      $('img:first', $(this)).attr('src', '/img/like.png')
    })

    $('.s3_text, .s3_h img').on('click', function () {
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
          $('#paybutton-payture').hide()
          break
        case 'payture':
          $('#paybutton-payture').fadeIn(100)
          $('#paybutton-payture')
          $('#paymaster-images').show()
          $('#paymaster-select').hide()
          $('#s3_kv').hide()
          break
        case 'paymaster':
          $('.solution-overlay #paybutton-paymaster').removeAttr('style')
          $('.solution-overlay #paybutton-payanyway').fadeOut(100)
          $('.solution-overlay #paymaster-images').hide()
          $('.solution-overlay #paymaster-select').show()
          $('.solution-overlay #s3_kv').hide()
          $('#paybutton-payture').hide()
          break
        case 'offline':
          $('.solution-overlay #paybutton-payanyway').fadeOut(100)
          $('.solution-overlay #paybutton-paymaster').css('background', '#a2b2bb')
          $('.solution-overlay #paymaster-images').show()
          $('.solution-overlay #paymaster-select').hide()
          $('#paybutton-payture').hide()
          $('.solution-overlay #s3_kv').show()
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

    $('#clear-options').on('click', function () {
      var checked = $('input:checked', '.look-variants')
      checked.prop('checked', false)
      var slider = $('.slider')
      if (slider.length > 0) {
        slider.slider('value', 5)
      }
      return false
    })

    $(document).on('click', '#to-pay, .scrolldown', function () {
      $('html, body').animate({
        scrollTop: $('.solution-overlay #step3').offset().top
      }, 500)
      return false
    })

    $('.new-price', '.selecting_numb').on('click', function () {
      var li = $(this).parent().parent()
      var imagecontainer = $('.imagecontainer', li)
      var parent = imagecontainer.parent().parent()
      var prevLi = parent.prev()
      var nextLi = parent.next()
      if (prevLi.length == 0) {
        prevLi = $('.main_portfolio').children().last()
      }
      if (nextLi.length == 0) {
        nextLi = $('.main_portfolio').children().first()
      }
      prev = $('.imagecontainer', prevLi).data('solutionid')
      next = $('.imagecontainer', nextLi).data('solutionid')

      if (/designers/.test(window.location.pathname)) {
        return true
      }
      if (window.history.pushState) {
        window.history.pushState('object or string', 'Title', imagecontainer.attr('href')); // @todo Check params
      } else {
        window.location = imagecontainer.attr('href')
        return false
      }
      $('.solution-overlay-dummy').appendTo('body').addClass('solution-overlay')
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
      var queryParam = '?exp=1'
      if (document.URL.indexOf('?') != -1) {
        queryParam = document.URL.slice(document.URL.indexOf('?'))
      }
      var urlJSON = window.location.pathname + '.json' + queryParam
      fetchSolution(urlJSON, true)
      return false
    })

    $('.needassist').on('click', function () {
      $('#loading-overlay2').modal({
        containerId: 'spinner',
        opacity: 80,
        close: false
      })
      $('.simplemodal-wrap').css('overflow', 'visible')
      $('#reqname').focus()
      $('#reqtarget').val(1)
      $('#reqto').val('дизайн консультация (Оксана Девочкина)')
      return false
    })

    function getParameterByName (name) {
      name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]')
      var regex = new RegExp('[\\?&]' + name + '=([^&#]*)'),
        results = regex.exec(location.search)
      return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '))
    }

    var search = getParameterByName('search')

    if (search) {
      var box = '<li style="margin-left:6px;">' + search.replace(/[^A-Za-zА-Яа-яЁё0-9-\/\s]/g, '').trim() + '<a class="removeTag" href="#"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>'
      var filter = $('#filterbox')
      $(box).appendTo(filter)
      recalculateBox()
    }

    $('#bill-fiz').submit(function (e) {
      e.preventDefault()
      if (checkRequired($(this))) {
        $.scrollTo($('.wrong-input', $(this)).parent(), {duration: 600})
      } else {
        $.post($(this).attr('action') + '.json', {
          'id': $('#fiz-id').val(),
          'name': $('#fiz-name').val(),
          'individual': $('#fiz-individual').val(),
          'inn': 0,
          'kpp': 0,
          'address': 0
        }, function (result) {
          if (result.error == false) {
            window.location = '/pitches/getpdf/godesigner-pitch-' + $('#fiz-id').val() + '.pdf'
          }
        })
      }
    })

    $('#bill-yur').submit(function (e) {
      e.preventDefault()
      if (checkRequired($(this))) {
        $.scrollTo($('.wrong-input', $(this)).parent(), {duration: 600})
      } else {
        $.post($(this).attr('action') + '.json', {
          'id': $('#yur-id').val(),
          'name': $('#yur-name').val(),
          'individual': $('#yur-individual').val(),
          'inn': $('#yur-inn').val(),
          'kpp': $('#yur-kpp').val(),
          'address': $('#yur-address').val()
        }, function (result) {
          if (result.error == false) {
            window.location = '/pitches/getpdf/godesigner-pitch-' + $('#yur-id').val() + '.pdf'
          }
        })
      }
    })

    function checkRequired (form) {
      var required = false
      $.each($('[required]', form), function (index, object) {
        if (($(this).attr('id') == 'yur-kpp') && ($('#yur-inn').val().length == 10)) {
          $(this).val('')
          return true
        }
        if (($(this).val() == $(this).data('placeholder')) || ($(this).val().length == 0)) {
          $(this).addClass('wrong-input')
          required = true
          return true // Continue next element
        }
        if (($(this).data('length')) && ($(this).data('length').length > 0)) {
          var arrayLength = $(this).data('length')
          if (-1 == $.inArray($(this).val().length, arrayLength)) {
            $(this).addClass('wrong-input')
            required = true
            return true
          }
        }
        if (($(this).data('content')) && ($(this).data('content').length > 0)) {
          if ($(this).data('content') == 'numeric') {
            // Numbers only
            if (/\D+/.test($(this).val())) {
              $(this).addClass('wrong-input')
              required = true
              return true
            }
          }
          if ($(this).data('content') == 'symbolic') {
            // Symbols only
            if (/[^a-zа-я\s]/i.test($(this).val())) {
              $(this).addClass('wrong-input')
              required = true
              return true
            }
          }
          if ($(this).data('content') == 'mixed') {
            // Symbols and Numbers
            if (!(/[a-zа-я0-9]/i.test($(this).val()))) {
              $(this).addClass('wrong-input')
              required = true
              return true
            }
          }
        }
      })
      return required
    }
  })
})()
