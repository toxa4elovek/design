class PaymentPayture extends BasePaymentSystem {
    paymentSystemName = 'payment-payture';
    render() {
        const checked = this.props.selected;
        const projectId = this.props.payload.projectId;
        const paymentLink = '/payments/startpayment/' + projectId;
        return (
            <div className="payment" onClick={this.onClickHandler}>
                <input type="radio" name="payment-options" onChange={this.onChange} data-pay="payture" checked={checked}/>
                <span className="description">Оплата дебетовыми или кредитными картами</span>
                <a href={paymentLink} className="button">Оплатить</a>
                <img className="imageblock" src="/img/s3_master.png" alt="Дебетовые и кредитные карты" />
            </div>
        )
    }
}