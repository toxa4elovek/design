class FundBalanceInput extends React.Component {
    constructor() {
        super();
        this.walletKey = 'Пополнение счёта';
    }
    componentDidMount() {
        const input = $(this.refs.input);
        input.numeric(
            {
                "negative": false,
                "decimal": false
            }, function () {
        });
    }
    onChangeHandle(e) {
        let newValue = e.target.value;
        if(!e.target.value) {
            newValue = 0;
        }
        PaymentActions.updateFundBalanceInput(parseInt(newValue));
    }
    onBlur(e) {
        const currentValue = e.target.value;
        PaymentActions.submitNewReceipt(parseInt(currentValue));
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
        return(
            <div>
                <input ref="input" type="text" onChange={this.onChangeHandle} onBlur={this.onBlur} onKeyDown={this.onKeydownHandle} defaultValue={initialValue} className="fund-balance-input"/>
            </div>
        );
    }
}