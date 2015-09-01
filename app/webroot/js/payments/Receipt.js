var Receipt = new React.createClass({
    hideLinkOnClick: function(e) {
        var aside = React.findDOMNode(this.refs["receiptContainer"]);
        $(aside).removeClass('expanded');
        e.preventDefault();
    },
    showLinkOnClick: function(e) {
        var aside = React.findDOMNode(this.refs["receiptContainer"]);
        $(aside).addClass('expanded');
        e.preventDefault();
    },
    render: function() {
        var total = 0;
        for (i = 0; i < this.props.data.length; i++) {
            total += this.props.data[i].amount;
        }
        return (
            React.createElement("aside", {ref: "receiptContainer", className: "summary-price expanded"}, 
                React.createElement("h3", null, "Итого:"), 
                React.createElement("p", {className: "summary"}, 
                    React.createElement("strong", null, React.createElement(ReceiptTotal, {total: total}))), 
                React.createElement("ul", null, 
                    this.props.data.map(function(object, index){
                        return (React.createElement(ReceiptLine, {key: index, row: object}))
                    })
                ), 
                React.createElement("a", {href: "#", className: "show", onClick: this.showLinkOnClick}, React.createElement("span", null, "Подробнее")), 
                React.createElement("a", {href: "#", className: "hide", onClick: this.hideLinkOnClick}, React.createElement("span", null, "Скрыть"))
            )
        )
    }
});