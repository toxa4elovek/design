class ProjectRewardInput extends React.Component {
    constructor(props) {
        super(props);
        this.walletKey = 'Награда Дизайнеру';
        this.minimalPrice = 500;
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
        const style = {
            "float": "left",
            "display": "block",
            "width": "250px",
            "height": "74px",
            "marginTop": "4px",
            "fontFamily": "Arial, serif",
            "fontSize": "37px",
            "lineHeight": "37px",
            "color": "#000000"};
        return(
            <div>
                <input ref="input" type="text" onBlur={this.onBlurHandler.bind(this)} onChange={this.onChangeHandle.bind(this)} onKeyDown={this.onKeydownHandle} defaultValue={initialValue} placeholder="500" style={style} />
            </div>
        )
    }
}