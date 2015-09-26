class BalanceBox extends React.Component{
    componentDidMount() {
        setInterval(this.updateBalance, 10000);
    }
    updateBalance() {
        fetch('/users/subscriber.json', {
            credentials: 'same-origin'
        }).then(function(response) {
            response.json().then(function(json) {
                data.userInfo = json;
                ReactDOM.render(
                    <SubscriberPage data={data} />,
                    document.getElementById('pageMount')
                );
            });
        });
    }
    render() {
        const data = this.props.data;
        let subscriptionStatus = <span><br/>не действителен</span>;
        if(data.isSubscriptionActive) {
            subscriptionStatus =
                (<span>тариф <a href="/pages/subscribe#plans"
                        target="_blank">«{data.plan.title}»
                            </a>
                    <br/>действителен до {data.expirationDate}
                </span>);
        }
        return (
            <div>
                <h6>Ваш текущий счет</h6>
                <span>{data.fullCompanyName}</span><br/>
                {subscriptionStatus}<br/>
                <span className="balance">{data.balance}р.</span>
            </div>
        );
    }
}