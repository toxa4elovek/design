'use strict';

var BalanceBox = React.createClass({displayName: "BalanceBox",
    mixins: [SetIntervalMixin],
    componentDidMount: function() {
        this.setInterval(this.updateBalance, 10000);
    },
    render: function() {
        var subscriptionStatus = React.createElement("span", null, "не действителен")
        if(this.props.isSubscriptionActive) {
            subscriptionStatus = React.createElement("span", null, "действителен до ", this.props.expirationDate)
        }
        return (React.createElement("div", null, React.createElement("h6", null, "Ваш текущий счет"), 
        React.createElement("span", null, this.props.companyName), React.createElement("br", null), 
        subscriptionStatus, React.createElement("br", null), 
        React.createElement("span", {className: "balance"}, this.props.balance, "р.")
        ))
    },
    updateBalance: function() {
        $.get('/users/subscriber.json').done(function(data) {
            this.setProps(data)
        }.bind(this))
    }
});