class Receipt extends React.Component {
    constructor() {
        super();
        this.hideLinkOnClick = this.hideLinkOnClick.bind(this);
        this.showLinkOnClick = this.showLinkOnClick.bind(this);
    }
    hideLinkOnClick(e) {
        const aside = this.refs.receiptContainer;
        $(aside).removeClass('expanded');
        e.preventDefault();
    }
    showLinkOnClick(e) {
        const aside = this.refs.receiptContainer;
        $(aside).addClass('expanded');
        e.preventDefault();
    }
    componentDidMount() {
        if(typeof(this.props.style) === 'undefined') {
            if($(window).scrollTop() > $('header').offset().top) {
                $('.summary-price').css('position', 'fixed').css('top', '150px');
            }else {
                $('.summary-price').css('position', 'absolute').css('top', ($('header').offset().top + 155) + 'px');
            }
        }
    }
    render() {
        let total = 0;
        for (let i = 0; i < this.props.data.length; i++) {
            total += this.props.data[i].value;
        }
        let style = {}
        if(this.props.style) {
          style = this.props.style
        }
        let showControl = true
        let controlElement = ''
        if((typeof(this.props.showControl) !== 'undefined') && (this.props.showControl === false)) {
          showControl = false
        }
        if(showControl) {
          controlElement = <div>
              <a href="#" className="show" onClick={this.showLinkOnClick}><span>Подробнее</span></a>
              <a href="#" className="hide" onClick={this.hideLinkOnClick}><span>Скрыть</span></a>
          </div>
        }
        return (
            <aside ref="receiptContainer" className="summary-price expanded" style={style}>
                <h3>Итого:</h3>
                <p className="summary">
                    <strong><ReceiptTotal total={total} /></strong></p>
                <ul>
                    {this.props.data.map(function(object, index){
                        return (<ReceiptLine key={index} row={object}/>)
                    })}
                </ul>
                {controlElement}
            </aside>
        )
    }
}