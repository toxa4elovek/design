class PushNotificationsStatus extends React.Component {
  onChange (event) {
    if (event.target.checked) {
      OneSignal.push(['registerForPushNotifications'])
    } else {
      OneSignal.push(['setSubscription', false])
    }
    return true
  }
  render () {
    return (
            <label className='regular' style={{'fontWeight': 'normal'}}>
                <input defaultChecked={this.props.checked} onChange={this.onChange.bind(this)} style={{'marginTop': 0, 'marginBottom': '2px'}} type='checkbox' />получать push-уведомления
            </label>
        )
  }
}
