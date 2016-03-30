class ProjectRewardInput extends React.Component {
    constructor(props) {
        super(props);
        this.walletKey = 'Награда Дизайнеру';
        this.minimalPrice = 500;
        this.placeholderValue = props.payload.startValue;
    }
    componentDidMount() {
        const input = $(this.refs.input);
        input.numeric(
            {
                "negative": false,
                "decimal": false
            }
        );
        this.updateTextInput();
    }
    onBlurHandler(e) {
        if(e.target.value < this.minimalPrice) {
            e.target.value = this.minimalPrice;
            SubscribedBriefActions.updateWinnerReward(parseInt(e.target.value));
        }
    }
    onFocus(e) {
        if(e.target.value == this.placeholderValue) {
            e.target.value = '';
            SubscribedBriefActions.updateWinnerReward(0);
        }
    }
    updateTextInput() {
        const currentBalanceLable = $('#current-balance-label');
        if(balance < this.refs.input.value) {
            currentBalanceLable.css('color', '#FF585D');
        }else {
            currentBalanceLable.css('color', '#888888');
        }
    }
    onChangeHandle(e) {
        e.target.value = e.target.value || 0;
        SubscribedBriefActions.updateWinnerReward(parseInt(e.target.value));
        this.updateTextInput();
    }
    __getReceiptValue(receipt) {
        let value = 0;
        receipt.forEach(function (row) {
            if(row.name == this.walletKey) {
                value = row.value;
            }
        }.bind(this));
        return value;
    }
    render() {
        const initialValue = this.__getReceiptValue(this.props.payload.receipt);
        let color = '#4f5159';
        if((initialValue == this.placeholderValue) || (initialValue == 500)) {
            color = '#ccc';
        }
        const style = {
            "float": "left",
            "display": "block",
            "width": "250px",
            "height": "74px",
            "marginTop": "4px",
            "fontFamily": "Arial, serif",
            "fontSize": "37px",
            "lineHeight": "37px",
            "color": color};
        return(
            <div>
                <input className="placeholder" ref="input" type="text" onFocus={this.onFocus.bind(this)} onBlur={this.onBlurHandler.bind(this)} onChange={this.onChangeHandle.bind(this)} onKeyDown={this.onKeydownHandle} defaultValue={initialValue} placeholder="500" style={style} />
            </div>
        )
    }
}