$(function () {
  if (window.isDesigner === 0) {
    setTimeout(function () {
      Chatra('show')
      Chatra('openChat')
    }, 20000)
  }

  const searchTermSelector = '#searchTerm'

  checkPlaceholder(searchTermSelector)

  function goSearch () {
    Table.options.searchTerm = searchTerm
    if (!isCurrentTypeFilterExists()) {
      Table.setFilter('type', 'all')
    }
    Table.fetchTable(Table.options)
    const image = '/img/filter-arrow-down.png'
    $('#filterToggle').data('dir', 'up')
    $('img', '#filterToggle').attr('src', image)
    $('#filtertab').hide()
  }

  /* Filters */

  $('body').click(function () {
    $('#cat-menu, #price-menu, #timelimit-menu').hide().parent().removeClass('active-list')
  })

  $(document).on('click', '#goSearch', function () {
    goSearch()
    return false
  })

  $(document).on('keyup', searchTermSelector, function (e) {
    checkFilterClear()
    if (e.keyCode == 13) {
      goSearch()
    }
  })

  $(searchTermSelector).keyboard('backspace', function () {
    if (($('li[data-group]').length > 0) && ($(searchTermSelector).val() == '')) {
      $('.removeTag', 'li[data-group]:last').click()
    }
  })

  const filterContainter = $('#filterContainer')

  $(searchTermSelector).on('focus', function () {
    if ($(this).val() == placeholder) {
      $(this).val('').removeClass('placeholder')
    }
    filterContainter.css('border', '4px solid rgb(231, 231, 231)')
    filterContainter.css('box-shadow', '')
    toggleFilterTab()
  })

  $(document).on('blur', searchTermSelector, function () {
    checkPlaceholder(searchTermSelector)
    filterContainter.css('box-shadow', '0 1px 2px rgba(0, 0, 0, 0.2) inset')
    filterContainter.css('border', '4px solid #F3F3F3')
  })

  $(document).on('click', function (e) {
    if ($(e.target).is(searchTermSelector)) {
      return false
    }
    const image = '/img/filter-arrow-down.png'
    $('#filterToggle').data('dir', 'up')
    $('img', '#filterToggle').attr('src', image)
    $('#filtertab').hide()
  })

  $('#filtertab').click(function (event) {
    event.stopPropagation()
  })

  $(document).on('click', '#filterToggle', function () {
    toggleFilterTab()
    return false
  })

  function toggleFilterTab () {
    var $el = $('#filterToggle')
    var dir = $el.data('dir')
    let image = ''
    if (dir == 'up') {
      image = '/img/filter-arrow-up.png'
      $el.data('dir', 'down')
    } else {
      image = '/img/filter-arrow-down.png'
      $el.data('dir', 'up')
    }
    $('img', $el).attr('src', image)
    $('#filtertab').toggle()
  }

  $(document).on('click', '.removeTag', function () {
    removeTag($(this))
    checkPlaceholder(searchTermSelector)
    recalculateBox(searchTermSelector)
    goSearch()
    return false
  })

  function removeTag (el) {
    el.parent().remove()
    Table.setFilter(el.data('group'), 'all')
    if ($('li[data-group]').length == 0) {
      Table.setFilter('type', 'current')
    } else {
      if ((el.data('group') == 'timeframe') && ($('li[data-group=type]').length == 0)) {
        Table.setFilter('type', 'all')
      }
    }
    checkFilterClear()
  }

  $(document).on('click', '#filterClear', function () {
    $.each($('.removeTag', '#filterbox'), function () {
      removeTag($(this))
    })
    $(searchTermSelector).val('')
    recalculateBox(searchTermSelector)
    checkPlaceholder(searchTermSelector)
    goSearch()
    return false
  })

  const filterListElement = $('.filterlist li')

  filterListElement.on('mouseover', function () {
    if (!$(this).hasClass('first')) {
      $(this).css('background-color', '#ebf8fc')
    }
  })

  filterListElement.on('mouseout', function () {
    $(this).css('background-color', '')
  })

  // categories
  $('#cat-menu-toggle').click(function () {
    let menuItem = $(this).parent()
    $('#cat-menu, #price-menu, #timelimit-menu').hide().parent().removeClass('active-list')
    $('#cat-menu').toggle()
    if (menuItem.hasClass('active-list')) {
      menuItem.removeClass('active-list')
    } else {
      menuItem.addClass('active-list')
    }
    return false
  })

  $(document).on('click', '.category-filter', function () {
    const catMenu = $('#cat-menu')
    const catMenuLabel = $('#cat-menu-label')
    Table.setFilter('category', $(this).attr('rel'), catMenu)
    if ($(this).attr('rel') != 'all') {
      catMenuLabel.html($('span', this).html())
    } else {
      catMenuLabel.html(catMenuLabel.data('default'))
    }
    Table.fetchTable(Table.options)
    catMenu.hide().parent().removeClass('active-list')
    return false
  })

  // price

  $('#price-menu-toggle').click(function () {
    const menuItem = $(this).parent()
    $('#cat-menu, #price-menu, #timelimit-menu').hide().parent().removeClass('active-list')
    $('#price-menu').toggle()
    // active-list
    if (menuItem.hasClass('active-list')) {
      menuItem.removeClass('active-list')
    } else {
      menuItem.addClass('active-list')
    }
    return false
  })

  $(document).on('click', '.price-filter', function () {
    Table.setFilter('priceFilter', $(this).attr('rel'), $('#cpriceat-menu'))
    const priceMenuLabel = $('#price-menu-label')
    if ($(this).attr('rel') != 'all') {
      priceMenuLabel.html($('span', this).html())
    } else {
      priceMenuLabel.html(priceMenuLabel.data('default'))
    }
    Table.fetchTable(Table.options)
    $('#price-menu').hide().parent().removeClass('active-list')
    return false
  })

  // timelimit

  $('#timelimit-menu-toggle').click(function () {
    var menuItem = $(this).parent()
    $('#cat-menu, #price-menu, #timelimit-menu').hide().parent().removeClass('active-list')
    $('#timelimit-menu').toggle()
    // active-list
    if (menuItem.hasClass('active-list')) {
      menuItem.removeClass('active-list')
    } else {
      menuItem.addClass('active-list')
    }
    return false
  })

  $(document).on('click', '.timelimit-filter', function () {
    Table.setFilter('timelimitFilter', $(this).attr('rel'), $('#cpriceat-menu'))
    const timelimitMenuLabel = $('#timelimit-menu-label')
    if ($(this).attr('rel') != 'all') {
      timelimitMenuLabel.html($('span', this).html())
    } else {
      timelimitMenuLabel.html(timelimitMenuLabel.data('default'))
    }
    Table.fetchTable(Table.options)
    $('#price-menu').hide().parent().removeClass('active-list')
    return false
  })

  /* Sorting*/

  // price
  $(document).on('click', '#sort-price', function () {
    var sortDirection = $(this).attr('data-dir')
    if (sortDirection == 'asc') {
      $(this).attr('data-dir', 'desc')
    } else {
      $(this).attr('data-dir', 'asc')
    }
    Table.options.order = {'price': sortDirection}
    Table.fetchTable(Table.options)
    return false
  })

  // ideas_count
  $(document).on('click', '#sort-ideas_count', function () {
    var sortDirection = $(this).attr('data-dir')
    if (sortDirection == 'asc') {
      $(this).attr('data-dir', 'desc')
    } else {
      $(this).attr('data-dir', 'asc')
    }
    Table.options.order = {'ideas_count': sortDirection}
    Table.fetchTable(Table.options)
    return false
  })

  // diff
  $(document).on('click', '#sort-finishDate', function () {
    var sortDirection = $(this).attr('data-dir')
    if (sortDirection == 'asc') {
      $(this).attr('data-dir', 'desc')
    } else {
      $(this).attr('data-dir', 'asc')
    }
    Table.options.order = {'finishDate': sortDirection}
    Table.fetchTable(Table.options)
    return false
  })

  $('.filterlist a').on('click', function () {
    $('li[data-group=' + $(this).data('group') + ']', '#filterbox').remove()
    var box = '<li style="margin-left:6px;" data-group="' + $(this).data('group') + '">' + $(this).text() + '<a class="removeTag" href="#" data-group="' + $(this).data('group') + '" data-value="' + $(this).data('value') + '"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>'
    $(box).appendTo('#filterbox')
    checkPlaceholder(searchTermSelector)
    recalculateBox(searchTermSelector)
    Table.setFilter($(this).data('group'), $(this).data('value'))
    if ($(this).data('group') != 'type') {
      if (!isCurrentTypeFilterExists()) {
        Table.setFilter('type', 'all')
      }
      if ($(this).data('group') == 'timeframe') {
        Table.setFilter('type', 'current')
      }
    }
    Table.fetchTable(Table.options)
    return false
  })

  var isCurrentTypeFilterExists = function () {
    return ($('li[data-group=type]').length)
  }

  recalculateBox(searchTermSelector)

  // started
  $(document).on('click', '#sort-started', function () {
    var sortDirection = $(this).attr('data-dir')
    /*if(sortDirection == 'asc') {
    	$(this).attr('rel', 'desc')
    }else {
    	$(this).attr('rel', 'asc')
    }*/
    $('#timelimit-menu-label').html($('span', this).html())

    Table.options.order = {'started': sortDirection}
    Table.fetchTable(Table.options)
    return false
  })

  // title
  $(document).on('click', '#sort-title', function () {
    var sortDirection = $(this).attr('data-dir')
    if (sortDirection == 'asc') {
      $(this).attr('data-dir', 'desc')
    } else {
      $(this).attr('data-dir', 'asc')
    }
    Table.options.order = {'title': sortDirection}
    Table.fetchTable(Table.options)
    return false
  })

  // category
  $(document).on('click', '#sort-category', function () {
    var sortDirection = $(this).attr('data-dir')
    if (sortDirection == 'asc') {
      $(this).attr('data-dir', 'desc')
    } else {
      $(this).attr('data-dir', 'asc')
    }
    Table.options.order = {'category': sortDirection}
    Table.fetchTable(Table.options)
    return false
  })

  /* Status filter */
  /*$(document).on('click', '.status-switch', function(){
  	$('.pitches-type').children().removeClass('active-pitches')
  	$(this).parent().addClass('active-pitches')
  	Table.options.type = $(this).attr('rel')
  	Table.fetchTable(Table.options)
  	return false
  })*/

  /* Zebra */

  $(document).on('mouseenter', '.highlighted, .even, .odd', function () {
    if ($('.pitches-name-td-img', this).hasClass('expand-link')) {
      $('.pitches-name-td-img', this).attr('src', '/img/arrow_grey.png')
      const row = $(this)
      let classToSave = ''
      if ($(this).hasClass('highlighted')) {
        classToSave = 'highlighted'
      }else if ($(this).hasClass('newpitch')) {
        classToSave = 'newpitch'
      }else if ($(this).hasClass('even')) {
        classToSave = 'even'
      }else if ($(this).hasClass('odd')) {
        classToSave = 'odd'
      }
      row.attr('rel', classToSave)
      row.removeClass(row.attr('rel'))
      row.addClass('pitch-mouseover')
    }
  })

  $(document).on('mouseleave', '.pitch-mouseover', function () {
    const row = $(this)

    if (!row.hasClass('pitch-open')) {
      $('.pitches-name-td-img', this).attr('src', '/img/arrow.png')
      row.removeClass('pitch-mouseover')
      row.addClass(row.attr('rel'))
      row.removeAttr('rel')
    }
  })

  /* Show/Hide cells */

  $(document).on('click', '.expand-link', function () {
    var parentRow = $(this).parent().parent().parent()
    var descRow = parentRow.next()
    if (parentRow.hasClass('pitch-open')) {
      descRow.hide().removeClass('pitch-open').addClass('pitch-collapsed')
      parentRow.removeClass('pitch-open')
      $('.pitches-name-td-img', $(this).parent().parent()).attr('src', '/img/arrow_grey.png')
    } else {
      descRow.show().removeClass('pitch-collapsed').addClass('pitch-open')
      parentRow.addClass('pitch-open')
      $('.pitches-name-td-img', $(this).parent().parent()).attr('src', '/img/arrow_grey_down.png')
    }
    return false
  })

  $(document).on('click', '.mypitch_delete_link', function () {
    $('#confirmDelete').data('id', $(this).attr('rel'))
    $('#popup-final-step').modal({
      containerId: 'final-step',
      opacity: 80,
      closeClass: 'popup-close'
    })
    return false
  })

  $(document).on('click', '#confirmDelete', function () {
    var id = $('#confirmDelete').data('id')
    $.post('/pitches/delete/' + id + '.json', function () {
      $('tr[data-id="' + id + '"]').hide()
      $('.popup-close').click()
    })
  })

  // Apply filters from query string
  $(window).on('popstate', function () {
    var options = window.location.search.substr(1)
    queryToSearchField(options, searchTermSelector)
    let fetchOptions = $.deparam(options)
    fetchOptions.fromQuery = true
    Table.fetchTable(fetchOptions)
  })

  var Table = new TableLoader
  Table.init()
})

/* Init placeholder */
const placeholder = 'НАЙТИ ПРОЕКТ ПО КЛЮЧЕВОМУ СЛОВУ ИЛИ ТИПУ'
function checkPlaceholder (searchTermSelector) {
  const el = $(searchTermSelector)
  let value = el.val()
  if ($('#filterbox li').length == 0) {
    if ((el.val() == '') || (el.val() == placeholder)) {
      el.addClass('placeholder')
      value = placeholder
    }
  } else {
    if (el.val() == placeholder) {
      value = ''
    }
    el.removeClass('placeholder')
  }
  el.val(value)
  checkFilterClear()
}

function checkFilterClear (searchTermSelector) {
  if ((($(searchTermSelector).val() == '') || ($(searchTermSelector).val() == placeholder)) && ($('#filterbox li').length == 0)) {
    $('#filterClear').fadeOut(50)
  } else {
    $('#filterClear').fadeIn(100)
  }
}

var recalculateBox = function (searchTermSelector) {
  var baseWidth = 524
  $.each($('#filterbox').children(), function (index, object) {
    baseWidth -= $(object).width() + 50
  })
  $(searchTermSelector).width(baseWidth)
}

function queryToSearchField (options, searchTermSelector) {
  if ($('#filterClear').is(':visible')) {
    $.each($('.removeTag', '#filterbox'), function () {
      $(this).parent().remove()
    })
  }
  $(searchTermSelector).val('')
  checkPlaceholder(searchTermSelector)
  recalculateBox(searchTermSelector)
  let box = ''
  if (options.type && options.type != 'all') {
    box = '<li style="margin-left:6px;" data-group="type">' + $('a[data-group=type][data-value=' + options.type + ']').text() + '<a class="removeTag" href="#" data-group="type" data-value="' + options.type + '"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>'
    $(box).appendTo('#filterbox')
  }
  if (options.category && options.category != 'all') {
    box = '<li style="margin-left:6px;" data-group="category">' + $('a[data-group=category][data-value=' + options.category + ']').text() + '<a class="removeTag" href="#" data-group="category" data-value="' + options.category + '"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>'
    $(box).appendTo('#filterbox')
  }
  if (options.priceFilter && options.priceFilter != 'all') {
    box = '<li style="margin-left:6px;" data-group="priceFilter">' + $('a[data-group=priceFilter][data-value=' + options.priceFilter + ']').text() + '<a class="removeTag" href="#" data-group="priceFilter" data-value="' + options.priceFilter + '"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>'
    $(box).appendTo('#filterbox')
  }
  if (options.searchTerm) {
    $(searchTermSelector).val(options.searchTerm)
  }
  if (options.order) {
    $('#sort-' + Object.keys(options.order)[0]).attr('rel', (options.order[Object.keys(options.order)[0]] == 'asc') ? 'desc' : 'asc')
  }
  checkPlaceholder(searchTermSelector)
  recalculateBox(searchTermSelector)
}
