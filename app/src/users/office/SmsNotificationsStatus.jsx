class SmsNotificationsStatus extends React.Component {
  render () {
    let checked = false
    if (this.props.checked === 'true') {
      checked = true
    }
    return (
    <label className='regular' style={{'fontWeight': 'normal'}}>
      <input type='hidden' value='0' name='accept_sms' />
      <input
        defaultChecked={checked}
        style={{'marginTop': 0, 'marginBottom': '2px'}}
        type='checkbox'
        value='1'
        name='accept_sms' />получать sms уведомления
    </label>
    )
  }
}
