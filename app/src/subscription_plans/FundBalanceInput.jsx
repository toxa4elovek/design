class FundBalanceInput extends React.Component {
    constructor(props) {
        super();
        this.walletKey = 'Пополнение счёта';
        this.placeholderValue = props.payload.startValue;
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
        if(e.target.value === '') {
            e.target.value = this.placeholderValue;
        }
        const currentValue = e.target.value;
        PaymentActions.submitNewReceipt(parseInt(currentValue));
    }
    onFocus(e) {
        if(e.target.value == this.placeholderValue) {
            e.target.value = '';
            PaymentActions.updateFundBalanceInput(0);
        }
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
        let styles = {'color': '#4f5159'};
        if(initialValue == this.placeholderValue) {
           styles = {'color': '#ccc'};
        }
        return(
            <div>
                <input style={styles} ref="input" type="text" onChange={this.onChangeHandle} onBlur={this.onBlur.bind(this)} onFocus={this.onFocus.bind(this)} onKeyDown={this.onKeydownHandle} defaultValue={initialValue} className="fund-balance-input"/>
            </div>
        );
    }
}