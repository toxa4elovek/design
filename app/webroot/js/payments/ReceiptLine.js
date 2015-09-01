var ReceiptLine = new React.createClass({
    render: function() {
        var row = this.props.row;
        var amount = row.amount + '.-';
        return (
            React.createElement("li", null, 
                React.createElement("span", null, row.name), 
                React.createElement("small", null, amount)
            )
        )
    }
});