;(function () {
  console.log('Hire Designer page loaded')
  $('.lp-projects-slider').slick({
    infinite: true,
    slidesToShow: 6,
    slidesToScroll: 6,
    arrows: true,
    variableWidth: true,
    appendArrows: $('.arrows')
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
  const style = {'position': 'absolute', 'top': '120px'}
  const minimalPrice = 2000
  const getUpdatedReceiptData = (value) => {
    value = parseInt(value)
    let fee = 0
    if(value < 5000) {
      fee = 1000
    }else if(value < 10000) {
      fee = 1750
    }else if (value < 20000) {
      fee = 3250
    }else if (value < 50000) {
      fee = 7000
    }else if (value < 100000) {
      fee = 12500
    }else {
      fee = value / 10
    }
    const percentage = ((fee / value) * 100).toFixed(1)
    return [
      {'name': 'Награда дизайнеру', 'value': value},
      {'name': `Сбор GoDesigner ${percentage}`, 'value': fee}
    ]
  }
  const renderReceipt = (data, style) => {
    ReactDOM.render(
      <Receipt data={data} style={style} showControl={false}/>,
      document.getElementById('receipt-container')
    )
  }
  renderReceipt(payload.receipt, style)
  $(document).on('change', 'input[name=price]', (element) => {
    const value = parseInt(element.target.value)
    const dataObject = getUpdatedReceiptData(value)
    renderReceipt(dataObject, style)
  })

  $(document).on('blur', 'input[name=price]', (element) => {
    if (element.target.value < minimalPrice) {
      element.target.value = minimalPrice
    }
    const dataObject = getUpdatedReceiptData(element.target.value)
    renderReceipt(dataObject, style)
  })

}) ()
