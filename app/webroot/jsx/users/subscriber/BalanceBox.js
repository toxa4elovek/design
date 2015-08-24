'use strict';

var BalanceBox = React.createClass({
    mixins: [SetIntervalMixin],
    componentDidMount: function() {
        this.setInterval(this.updateBalance, 10000);
    },
    render: function() {
        var subscriptionStatus = <span>не действителен</span>
        if(this.props.isSubscriptionActive) {
            subscriptionStatus = <span>действителен до {this.props.expirationDate}</span>
        }
        return (<div><h6>Ваш текущий счет</h6>
        <span>{this.props.companyName}</span><br/>
        {subscriptionStatus}<br/>
        <span className="balance">{this.props.balance}р.</span>
        </div>)
    },
    updateBalance: function() {
        $.get('/users/subscriber.json').done(function(data) {
            this.setProps(data)
        }.bind(this))
    }
});