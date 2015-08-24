var FundBalanceButton = React.createClass({displayName: "FundBalanceButton",
    render: function() {
        return (React.createElement("a", {href: "/payments/fund_balance", target: "_blank", className: "button fund-balance clean-style-button"}, "пополнить счёт"))
    },
});