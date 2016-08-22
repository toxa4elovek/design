;(function () {
  $(function () {
    if (window.isDesigner === 0) {
      setTimeout(function () {
        Chatra('show')
        Chatra('openChat')
      }, 20000)
    }
  })
  let maxHeight = 0
  let counter = 0
  let list = []
  const advantagesBlockChilder = $('#advantages').children()
  let total = advantagesBlockChilder.length
  $.each(advantagesBlockChilder, function (index, object) {
    if ($('h2', object).height() > maxHeight) {
      maxHeight = $('h2', object).height()
    }
    list.push(object)
    counter += 1
    total -= 1
    if (counter == 3 || total == 0) {
      $.each(list, function (index, object) {
        $('h2', object).height(maxHeight)
      })
      list = []
      maxHeight = 0
      counter = 0
    }
  })
})()
