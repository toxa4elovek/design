class PaymentPaymasterPaySystem extends React.Component{
    onClickHandler(event) {
        event.preventDefault();
        this.props.clickCallback(this.props.paySystem.id);
    }
    render() {
        const paySystem = this.props.paySystem;
        const link = 'https://paymaster.ru/Payment/Init?LMI_PAYMENT_SYSTEM=' + paySystem.id + '&amp;LMI_MERCHANT_ID=d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60&amp;LMI_CURRENCY=RUB&amp;LMI_PAYMENT_AMOUNT=' + this.props.total + '&amp;LMI_PAYMENT_NO=' + this.props.projectId + '&amp;LMI_PAYMENT_DESC=%d0%9e%d0%bf%d0%bb%d0%b0%d1%82%d0%b0+%d0%bf%d1%80%d0%be%d0%b5%d0%ba%d1%82%d0%b0';
        return (
            <a onClick={this.onClickHandler.bind(this)} href={link} rel={paySystem.id} className="pm-item paySystem" title={paySystem.title}>
                <img src={paySystem.logo} alt="" title={paySystem.title} />
            </a>
        )
    }
}