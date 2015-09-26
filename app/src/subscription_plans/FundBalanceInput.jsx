class FundBalanceInput extends React.Component {
    walletKey = 'Пополнение счёта';
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
        e.target.value = e.target.value || 0;
        PaymentActions.updateFundBalanceInput(parseInt(e.target.value));
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
                <input ref="input" type="text" onChange={this.onChangeHandle} onKeyDown={this.onKeydownHandle} defaultValue={initialValue} className="fund-balance-input" placeholder="8000"/>
            </div>
        )
    }
}