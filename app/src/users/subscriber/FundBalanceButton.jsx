class FundBalanceButton extends React.Component {
  constructor (props) {
    super()
    this.disabled = props.isSubscriptionActive
  }
  render () {
    const style = {'fontSize': '11px'}
    let url = '/subscription_plans/subscriber'
    if (this.disabled) {
      url = '/pages/subscribe'
    }
    return (
    <a
      className='button fund-balance clean-style-button'
      href={url}
      style={style}
      target='_blank'>пополнить счёт</a>
    )
  }
}
