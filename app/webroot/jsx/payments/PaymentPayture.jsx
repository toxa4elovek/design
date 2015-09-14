class PaymentPayture extends React.Component{
    render() {
        return (
            <div className="payment">
                <input type="radio" name="payment-options" data-pay="payture" />
                <span className="description">Оплата дебетовыми или кредитными картами</span>
                <a href="/payments/startpayment/0" className="button">Оплатить</a>
                <img className="imageblock" src="/img/s3_master.png" alt="Дебетовые и кредитные карты" />
            </div>
        )
    }
}