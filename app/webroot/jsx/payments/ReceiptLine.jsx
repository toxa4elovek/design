var ReceiptLine = new React.createClass({
    render: function() {
        var row = this.props.row;
        var amount = row.amount + '.-';
        return (
            <li>
                <span>{row.name}</span>
                <small>{amount}</small>
            </li>
        )
    }
});