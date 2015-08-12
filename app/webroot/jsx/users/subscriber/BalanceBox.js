var BalanceBox = React.createClass({
    render: function() {
        console.log(this.props);
        if(this.props.isSubscriptionActive) {
            var subscriptionStatus = <span>действителен до {this.props.expirationDate}</span>
        }else {
            var subscriptionStatus = <span>не действителен</span>
        }
        return (<div><h6>Ваш текущий счет</h6>
        <span>{this.props.companyName}</span><br/>
        {subscriptionStatus}<br/>
        <span className="balance">{this.props.balance}р.</span>
        </div>)
    },
});