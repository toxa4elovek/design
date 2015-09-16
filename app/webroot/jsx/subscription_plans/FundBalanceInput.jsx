class FundBalanceInput extends React.Component {
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
        PaymentActions.updateFundBalanceInput(parseInt(e.target.value));
    }
    render() {
        return(
            <div>
                <input ref="input" type="text" onChange={this.onChangeHandle} onKeyDown={this.onKeydownHandle} className="fund-balance-input" placeholder="9000"/>
            </div>
        )
    }
}