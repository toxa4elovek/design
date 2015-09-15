class Receipt extends React.Component{
    hideLinkOnClick(e) {
        var aside = React.findDOMNode(this.refs["receiptContainer"]);
        $(aside).removeClass('expanded');
        e.preventDefault();
    }
    showLinkOnClick(e) {
        var aside = React.findDOMNode(this.refs["receiptContainer"]);
        $(aside).addClass('expanded');
        e.preventDefault();
    }
    render() {
        var total = 0;
        for (i = 0; i < this.props.data.length; i++) {
            total += this.props.data[i].amount;
        }
        return (
            <aside ref="receiptContainer" className="summary-price expanded">
                <h3>Итого:</h3>
                <p className="summary">
                    <strong><ReceiptTotal total={total} /></strong></p>
                <ul>
                    {this.props.data.map(function(object, index){
                        return (<ReceiptLine key={index} row={object}/>)
                    })}
                </ul>
                <a href="#" className="show" onClick={this.showLinkOnClick}><span>Подробнее</span></a>
                <a href="#" className="hide" onClick={this.hideLinkOnClick}><span>Скрыть</span></a>
            </aside>
        )
    }
}