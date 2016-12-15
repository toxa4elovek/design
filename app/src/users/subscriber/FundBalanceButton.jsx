class FundBalanceButton extends React.Component {
  constructor (props) {
    super()
    this.disabled = props.data.isSubscriptionActive
  }
  render () {
    const style = {'fontSize': '11px'}
    let url = '/pages/subscribe'
    let text = 'Выбрать абонентский план'
    if (this.disabled !== 0) {
      url = '/subscription_plans/subscriber'
      text = 'пополнить счёт'
    }
    return (
    <a
      className='button fund-balance clean-style-button'
      href={url}
      style={style}
      target='_blank'>{text}</a>
    )
  }
}
