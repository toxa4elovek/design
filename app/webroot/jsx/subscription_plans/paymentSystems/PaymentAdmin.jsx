class PaymentAdmin extends BasePaymentSystem {
    activatePayment(e) {
        const data = {
            'projectId': this.props.payload.projectId
        };
        e.preventDefault();
        console.log('click');
        $.post('/subscription_plans/activate.json', data, function(response) {
            alert(response.data.message);
        });
    }
    render() {
        return (
            <a href="#"
               onClick={this.activatePayment.bind(this)}
               className="button"
            >Активировать текущий платеж</a>
        );
    }
}