class BasePaymentSystem extends React.Component {
    constructor() {
        super();
        this.onClickHandler = this.onClickHandler.bind(this);
    }
    onClickHandler() {
        PaymentActions.selectPaymentType(this.paymentSystemName);
    }
    onChange() {
    }
}