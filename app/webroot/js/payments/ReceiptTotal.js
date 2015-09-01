var ReceiptTotal = new React.createClass({
    render: function() {
        var total = this.props.total;
        total += '.-';
        return (
            React.createElement("span", null, total)
        )
    }
});