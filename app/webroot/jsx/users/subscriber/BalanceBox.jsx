class BalanceBox extends React.Component{
    componentDidMount() {
        //this.setInterval(this.updateBalance, 10000);
    }
    render() {
        let subscriptionStatus = <span>не действителен</span>;
        if(this.props.isSubscriptionActive) {
            console.log(this.props.plan);
            subscriptionStatus = <span>Тариф «{this.props.plan.title}»<br/>действителен до {this.props.expirationDate}</span>;
        }
        return (
            <div>
                <h6>Ваш текущий счет</h6>
                <span>{this.props.companyName}</span>
                <br/>
                {subscriptionStatus}<br/>
                <span className="balance">{this.props.balance}р.</span>
            </div>
        )
    }
    updateBalance() {
        $.get('/users/subscriber.json').done(function(data) {
            this.setProps(data)
        }.bind(this))
    }
}