class PaymentPaymaster extends React.Component{
    paymentSystemClick(id) {
        const amInput = $("#pmOpenAmount");
        if((0 < amInput.length) && (!amInput.val().match(/^\s*\d+.?\d*\s*$/))) {
            alert('Укажите, пожалуйста, корректную сумму платежа');
            return false;
        }
        $("#pmwidgetPS").val(id);
        $("#pmwidgetForm").submit();
    }
    render() {
        const paySystems = this.props.payload.paySystems;
        const total = this.props.payload.total + '.00';
        const projectId = this.props.payload.projectId;
        return (
            <div className="payment paymaster">
                <input type="radio" name="payment-options" data-pay="paymaster" />
                <span className="description">Оплата электронными деньгами через PayMaster</span>
                <img className="imageblock" src="/img/s3_paymaster.png" alt="Дебетовые и кредитные карты" />
                <div className="widget">
                    <div className="pmwidget pmwidgetDone" style={{"width": "471px"}}>
                        <a onClick={this.paymentSystemClick.bind(this)} href="http://paymaster.ru/" className="pmlogo"></a>
                        <h1 className="pmheader"> Выберите способ оплаты</h1>
                        <p className="pmdesc"><strong>Описание:</strong> Оплата проекта</p>
                        <p className="pmamount"><strong>Сумма:&nbsp;</strong>{total}&nbsp;RUB</p>
                        <label className="pmpaymenttype"> Способ оплаты:</label>
                        <div className="payList" style={{"height": "auto", "overflow": "hidden"}}>
                            {paySystems.map(function(system) {
                                return (
                                    <PaymentPaymasterPaySystem
                                        onMouseEnter={this.paymentSystemClick.bind(this)}
                                        key={system.id}
                                        total={total}
                                        projectId={projectId}
                                        paySystem={system}
                                        clickCallback={this.paymentSystemClick}
                                    />
                                );
                            }, this)}
                            <div className="clearfix"></div>
                        </div>
                        <form id="pmwidgetForm" method="POST" action="https://paymaster.ru/Payment/Init">
                            <div>
                                <input type="hidden" name="LMI_MERCHANT_ID" value="d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60" />
                                <input type="hidden" name="LMI_CURRENCY" value="RUB" />
                                <input type="hidden" name="LMI_PAYMENT_AMOUNT" id="pmOpenAmount" value={total} />
                                <input type="hidden" name="LMI_PAYMENT_NO" value={projectId} />
                                <input type="hidden" name="LMI_PAYMENT_DESC" value="Оплата проекта" />
                                <input type="hidden" name="LMI_PAYMENT_SYSTEM" id="pmwidgetPS" ref="inputPaymentSystem" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        )
    }
}