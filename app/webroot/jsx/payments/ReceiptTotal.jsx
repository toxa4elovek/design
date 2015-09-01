var ReceiptTotal = new React.createClass({
    render: function() {
        var total = this.props.total;
        total += '.-';
        return (
            <span>{total}</span>
        )
    }
});