var BalanceBox = React.createClass({
    render: function() {
        console.log('test')
        return (<div><h6>Ваш текущий счет</h6>
        <span>{this.props.companyName}</span><br/>
        <span>действителен до {this.props.expirationDate}</span><br/>
        <span className="balance">{this.props.balance}р.</span>
        </div>)
    },
});