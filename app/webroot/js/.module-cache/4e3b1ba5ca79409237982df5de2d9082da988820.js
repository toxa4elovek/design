var BalanceBox = React.createClass({displayName: "BalanceBox",
    render: function() {
        console.log('test')
        return (React.createElement("div", null, React.createElement("h6", null, "Ваш текущий счет"), 
        React.createElement("span", null, this.props.companyName), React.createElement("br", null), 
        React.createElement("span", null, "действителен до ", this.props.expirationDate), React.createElement("br", null), 
        React.createElement("span", {className: "balance"}, this.props.balance, "р.")
        ))
    },
});