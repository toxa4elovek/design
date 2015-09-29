class ProjectRewardInput extends React.Component {
    walletKey = 'Награда Дизайнеру';
    minimalPrice = 500;
    componentDidMount() {
        const input = $(this.refs.input);
        input.numeric(
            {
                "negative": false,
                "decimal": false
            }
        );
    }
    onBlurHandler(e) {
        if(e.target.value < this.minimalPrice) {
            e.target.value = this.minimalPrice;
            SubscribedBriefActions.updateWinnerReward(parseInt(e.target.value));
        }
    }
    onChangeHandle(e) {
        e.target.value = e.target.value || 0;
        SubscribedBriefActions.updateWinnerReward(parseInt(e.target.value));
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
            <input ref="input" type="text" onBlur={this.onBlurHandler.bind(this)} onChange={this.onChangeHandle} onKeyDown={this.onKeydownHandle} defaultValue={initialValue} placeholder="8000" style={style} />
            </div>
        )
    }
}