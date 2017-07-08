$(function () {
  const awardInput = $('#award')

  tinymce.init({
    selector: '.enable-editor',
    content_css: '/css/brief_wysiwyg.css',
    language: 'ru',
    height: '240',
    width: '538',
    relative_urls: false,
    remove_script_host: false,
    menubar: false,
    plugins: ['link,lists,charmap,paste'],
    toolbar: 'styleselect,link,bullist,numlist,charmap',
    default_link_target: '_blank',
    style_formats: [
      {title: 'Основной текст', inline: 'span', classes: 'regular'},
      {title: 'Заголовок', inline: 'span', classes: 'greyboldheader'},
      {title: 'Дополнение', inline: 'span', classes: 'supplement2'}
    ],
    setup: function (ed) {
      ed.on('keyup', function () {
        const chars = ed.getContent().length
        const indicator = $('#indicator-desc')
        indicator.removeClass('low normal good')
        const textarea = $('#full-description')
        if (chars < textarea.data('normal')) {
          indicator.addClass('low')
        } else if (chars < textarea.data('high')) {
          indicator.addClass('normal')
        } else {
          indicator.addClass('good')
        }
      })
    }
  })

  if (window.isDesigner === 0) {
    setTimeout(function () {
      Chatra('show')
      Chatra('openChat')
    }, 30000)
  }

  /* Download Form Select */
  if ((window.File != null) && (window.FileList != null)) {
    $('#new-download').show()
  } else {
    $('#old-download').show()
  }

  /**/

  $('.short-time-limit').change(function () {
    var value = $(this).data('optionValue')
    var key = $(this).data('optionTitle')
    if (value == '0') {
      $('#timelimit-label').removeClass('unfold')
      Cart.removeOption(key)
    } else {
      Cart.addOption(key, value)
      $('#timelimit-label').addClass('unfold').html('+' + Cart.decoratePrice(value))
    }
  })

  const fullDescription = $('#full-description')
  const indicatorDesc = $('#indicator-desc')
  var chars = fullDescription.val().length
  fullDescription.removeClass('low normal good')
  if (chars < 1000) {
    indicatorDesc.addClass('low')
  } else if (chars < 2500) {
    indicatorDesc.addClass('normal')
  } else {
    indicatorDesc.addClass('good')
  }
  fullDescription.keyup(function () {
    var chars = $(this).val().length
    indicatorDesc.removeClass('low normal good')
    if (chars < 1000) {
      indicatorDesc.addClass('low')
    } else if (chars < 2500) {
      indicatorDesc.addClass('normal')
    } else {
      indicatorDesc.addClass('good')
    }
  })

  $('#hide-check').click(function () {
    $(this).parent().removeClass('expanded')
    return false
  })

  $('#show-check').click(function () {
    $(this).parent().addClass('expanded')
    return false
  })
  /*sliders*/

  $('.slider').each(function (index, object) {
    var value = 5
    if ((typeof (slidersValue) != 'undefined') && (slidersValue != null)) {
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
      }
    })
  })

  /**/
  $(document).on('focus', '.wrong-input', function () {
    $(this).removeClass('wrong-input')
  })
  $('input', '.extensions').change(function () {
    $('.extensions').removeClass('wrong-input')
  })

  $('input', '#list-job-type').change(function () {
    $('#list-job-type').removeClass('wrong-input')
  })

  $('.expand_extra').on('click', function () {
    const extraOptions = $('.extra_options')
    extraOptions.toggle()
    if (extraOptions.is(':visible')) {
      $(this).text('– Дополнительная информация')
    } else {
      $(this).text('+ Дополнительная информация')
    }
    return false
  })

  $('#save').click(function () {
    if ((($('input[name=tos]').attr('checked') != 'checked') || ($('input[type=radio]:checked').length == 0)) && ($('input[name=tos]').length == 1)) {
      alert('Вы должны принять правила и условия')
    } else {
      if (Cart.prepareData()) {
        if (uploader.duCount() > 0) {
          $('#loading-overlay').modal({
            containerId: 'spinner',
            opacity: 80,
            close: false
          })
          uploader.duStart()
        } else {
          Cart.isDirty = false
          Cart.saveData()
          _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь перешел на третий шаг брифа'])
        }
      } else {
        $.scrollTo($('.wrong-input').parent(), {duration: 600})
      }
    }
    return false
  })

  var fileIds = []
  var uploader = $('#fileupload').damnUploader({
    url: '/pitchfiles/add.json'
  })

  uploader.on('du.add', function (e) {
    return onSelectHandler.call(this, uploader, e, fileIds, Cart)
  })

  $('input[name="phone-brief"]').change(function () {
    var phone = $(this).val()
    $('input[name="phone-brief"]').val(phone)
  })

  $(document).on('click', '.filezone-delete-link', function () {
    if (Cart.id) {
      const givenId = +$(this).parent().attr('data-id')
      if (givenId) {
        $.post(`/pitchfiles/delete/${givenId}`, function (response) {
          if (response !== 'true') {
            alert('При удалении файла произошла ошибка')
          }
          const position = $.inArray(givenId, Cart.fileIds)
          Cart.fileIds.splice(position, 1)
          Cart.saveFileIds()
        })
      } else {
        uploader.duCancel($(this).attr('data-delete-id'))
      }
      $(this).parent().remove()
      return false
    } else {
      uploader.duCancel($(this).attr('data-delete-id'))
      $(this).parent().remove()
      return false
    }
  })

  $('#uploadButton').click(function () {
    $('#fileuploadform').fileupload('uploadByClick')
    return false
  })

  $('.sub-radio').change(function () {
    var minValue = $(this).data('minValue')
    awardInput.data('minimalAward', minValue)
    awardInput.blur()
  })

  $('#phonebrief').change(function () {
    if ($(this).attr('checked') == 'checked') {
      Cart.validatetype = 2
    } else {
      Cart.validatetype = 1
    }
  })

  $('.tooltip').tooltip({
    tooltipID: 'tooltip',
    width: '282px',
    correctPosX: 45,
    positionTop: -180,
    borderSize: '0px',
    tooltipPadding: 0,
    tooltipBGColor: 'transparent'
  })

  $('.rb1').change(function () {
    if ($(this).data('pay') == 'online') {
      $('#paybutton').removeAttr('style')
      $('#s3_kv').hide()
    } else {
      $('#paybutton').css('background', '#a2b2bb')
      $('#s3_kv').show()
    }
  })

  /**/
  const Cart = new FeatureCart
  Cart.isDirty = true
  Cart.init()

  $(window).bind('beforeunload', function () {
    if (Cart.isDirty) return 'Сохраните черновик проекта'
  })
})
/* Class */

function FeatureCart () {
  var self = this
  this.id = 0
  this.total = 0
  this.content = {}
  this.container = $('#check-tag')
  this.priceTag = $('#total-tag')
  this.award = $('#award')
  this.awardKey = ($('input[name=category_id]').val() == 7) ? 'Награда копирайтеру' : 'Награда Дизайнеру'
  this.category = $('input[name=category_id]').val()
  this.data = {}
  this.fileIds = []
  this.validatetype = 1
  this.transferFee = feeRates.normal
  this.transferFeeKey = 'Сбор GoDesigner'
  this.mode = 'add'
  this.init = function () {
    if (typeof ($('#pitch_id').val()) != 'undefined') {
      self.id = $('#pitch_id').val()
      this.mode = 'edit'
    }
    var initVal = self.award.val()
    if (self.award.val() == '') {
      initVal = self.award.attr('value')
    }
    if (self.id > 0) {
      var awardName = ($('input[name=category_id]').val() == 7) ? 'Награда копирайтеру' : 'Награда Дизайнеру'
      self.addOption(awardName, initVal)
    } else {
      self.updateOption('Заполнение брифа', 0)
    }
    if (self.mode == 'edit') {
      $.get('/receipts/view/' + self.id + '.json', function (response) {
        self.fillCheck(response)
        self._renderCheck()
      })
    } else {
      self._renderCheck()
    }
    if ($('#phonebrief').attr('checked') == 'checked') {
      self.validatetype = 2
    }
    $.each($('li', '#filezone'), function (index, object) {
      self.fileIds.push($(object).data('id'))
    })

    if (window.location.hash == '#step3') {
      if (self.prepareData()) {
        self.isDirty = false
        self.saveData()
        _gaq.push(['_trackEvent', 'Создание проекта', 'Пользователь перешел на третий шаг брифа'])
      }
    }
  }
  this.fillCheck = function (data) {
    self.content = {}
    $.each(data, function (index, object) {
      const feeOption = object.name.indexOf(self.transferFeeKey)
      if ((feeOption != -1) && (self.category != 20)) {
        const percent = object.name.substr(self.transferFeeKey.length + 1, 4)
        if (percent.length > 0) {
          self.transferFee = (percent.replace(',', '.') / 100).toFixed(3)
        } else { // For older pitches
          self.transferFee = feeRates.normal
        }
        object.name = self.transferFeeKey
      }
      if ( (object.value != 0)) {
        self.updateOption(object.name, parseInt(object.value))
      } else if ( (object.name == 'Заполнение брифа')) {
        self.updateOption(object.name, parseInt(object.value))
      }
    })
  }
  this.addOption = function (key, value) {
    self.content[key] = value
    self.updateFees()
    self._renderCheck()
  }
  this.updateOption = function (key, value) {
    if (typeof (self.content[key]) != 'undefined') {
      self.content[key] = value
    } else {
      self.addOption(key, value)
    }
    self.updateFees()
    self._renderCheck()
  }
  this.updateFees = function () {
    self._calculateOptionsWithoutFee()
    if (self.category != 20) {
      var award = self.getOption(self.awardKey)
      var commision = Math.round(award * this.transferFee) - this.transferFeeDiscount
      if (commision < 0) {
        commision = 0
      }
      if ($('input[name=category_id]').val() === '22') {
        commision = this.transferFee
      }
      self.content[self.transferFeeKey] = commision
    }
  }
  this.getOption = function (key) {
    if (typeof (self.content[key]) != 'undefined') {
      return parseInt(self.content[key])
    } else {
      return 0
    }
  }
  this.getTimelimit = function () {
    return $('.short-time-limit:checked').data('optionPeriod')
  }
  this.removeOption = function (key) {
    delete self.content[key]
    self.updateFees()
    self._renderCheck()
  }
  this.prepareData = function () {
    const features = {
      'award': self.getOption(self.awardKey),
      'private': self.getOption('Скрыть проект'),
      'social': self.getOption('Рекламный Кейс'),
      'experts': self._expertArray(),
      'email': self.getOption('Email рассылка'),
      'pinned': self.getOption('«Прокачать» проект'),
      'timelimit': self.getOption('Установлен срок'),
      'brief': self.getOption('Заполнение брифа'),
      'guaranteed': self.getOption('Гарантированный проект'),
      'timelimitOption': self.getTimelimit()
    }
    const commonPitchData = {
      'id': self.id,
      'title': $('input[name=title]').val(),
      'category_id': $('input[name=category_id]').val(),
      'industry': $('input[name=industry]').val() || '',
      'jobTypes': self._jobArray(),
      'business-description': $('textarea[name=business-description]').val(),
      'description': tinyMCE.activeEditor.getContent(),
      'fileFormats': self._formatArray(),
      'fileFormatDesc': $('textarea[name=format-description]').val(),
      'filesId': self.fileIds,
      'phone-brief': $('input[name=phone-brief]').val(),
      'materials': $('input[name=materials]:checked').val(),
      'materials-limit': $('input[name=materials-limit]').val()
    }
    self.data = {
      'features': features,
      'commonPitchData': commonPitchData,
      'specificPitchData': self.getSpecificData()
    }
    return self.validateData()
  }
  this.validateData = function () {
    var result = true
    if (self.validatetype == 1) {
      if ((self.data.commonPitchData.title == '') || ($('input[name=title]').data('placeholder') == self.data.commonPitchData.title)) {
        $('input[name=title]').addClass('wrong-input')
        result = false
      }
    }
    return result
  }
  this.saveData = function () {
    if (self.data.features.award == 0) {
      alert('Укажите награду для дизайнера!')
    } else {
      $.post('/pitches/add.json', self.data, function (response) {
        if (response === 'redirect') {
          window.location = '/users/registration'
        }
        if (response === 'noaward') {
          alert('Укажите награду для дизайнера!')
          return false
        }
        if (response === 'lowaward') {
          alert('Награда слишком низкая!')
          return false
        }
        if (typeof (response.error) !== 'undefined') {
          if (response.error === 'lowaward') {
            alert('Награда слишком низкая!')
            return false
          }
        }
        self.id = response
        window.location = '/pitches/details/' + self.id
      })
    }
  }

  this.saveFileIds = function () {
    $.post('/pitches/updatefiles.json', {'fileids': self.fileIds, 'id': self.id})
  }

  this.getSpecificData = function () {
    var specificPitchData = {}
    $('input.specific-prop, textarea.specific-prop').each(function (index, object) {
      specificPitchData[$(object).attr('name')] = $(object).val()
    })
    $('input.specific-group:checked').each(function (index, object) {
      specificPitchData[$(object).attr('name')] = $(object).val()
    })
    $('input.specific-group[data-selected~="true"]').each(function (index, object) {
      specificPitchData[$(object).attr('name')] = $(object).val()
    })
    if ($('.slider').length > 0) {
      specificPitchData[$('.sliderul').data('name')] = self._logoProperites()
    }
    if ($('.look-variants').length) {
      specificPitchData['logoType'] = self._logoTypeArray()
    }
    return specificPitchData
  }
  this.decoratePrice = function (price) {
    price += '.-'
    return price
  }
  this._logoProperites = function () {
    var array = []
    $.each($('.slider'), function (i, object) {
      array.push($(object).slider('value'))
    })
    return array
  }
  this._logoTypeArray = function () {
    var array = []
    var checkedExperts = $('input:checked', '.look-variants')
    $.each(checkedExperts, function (index, object) {
      array.push($(object).data('id'))
    })
    return array
  }
  this._formatArray = function () {
    var array = []
    var checkedExperts = $('input:checked', '.extensions')
    $.each(checkedExperts, function (index, object) {
      array.push($(object).data('value'))
    })
    return array
  }
  this._jobArray = function () {
    var array = []
    var checkedJob = $('input:checked', '#list-job-type')
    $.each(checkedJob, function (index, object) {
      array.push($(object).val())
    })
    return array
  }
  this._expertArray = function () {
    var array = []
    var checkedExperts = $('input:checked', '.experts')
    $.each(checkedExperts, function (index, object) {
      array.push($(object).data('id'))
    })
    return array
  }
  this._renderCheck = function () {
    self._renderTotal()
    self._renderOptions()
  }
  this._renderTotal = function () {
    const priceTag = self._calculateTotal() + '.-'
    self.priceTag.html(priceTag)
  }
  this._renderOptions = function () {
    var html = ''
    $.each(self.content, function (key, value) {
      if (self.category == 7 && self.awardKey == key) {
        key = 'Награда копирайтеру'
      }
      if ((key == self.transferFeeKey) && ($('input[name=category_id]').val() !== '22')) {
        html += '<li><span>' + key + ' <div>' + (self.transferFee * 100).toFixed(1) + '%</div></span><small>' + value + '.-</small></li>'
      } else {
        html += '<li><span>' + key + '</span><small>' + value + '.-</small></li>'
      }
    })
    self.container.html(html)
  }
  this._calculateOptions = function () {
    let optionsTotal = 0
    $.each(self.content, function (key, value) {
      if (key != self.transferFeeKey) {
        optionsTotal += parseInt(value)
      }
    })
    if (typeof (self.content[self.transferFeeKey]) != 'undefined') {
      optionsTotal += parseInt(self.content[self.transferFeeKey])
    }
    return optionsTotal
  }
  this._calculateOptionsWithoutFee = function () {
    var optionsTotal = 0
    $.each(self.content, function (key, value) {
      if (key != self.transferFeeKey) {
        optionsTotal += parseInt(value)
      }
    })
    //
    return optionsTotal
  }
  this._calculateTotal = function () {
    self.total = self._calculateOptions()
    return self.total
  }
}
