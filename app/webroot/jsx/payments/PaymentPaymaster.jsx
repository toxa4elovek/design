class PaymentPaymaster extends React.Component{
    componentDidMount() {
        $(".pmwidget").pmwidget();
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
                <div>
                    <div className="pmwidget pmwidgetDone" style={{"width": "471px"}}>
                        <a href="http://paymaster.ru/" className="pmlogo"></a>
                        <h1 className="pmheader"> Выберите способ оплаты</h1>
                        <p className="pmdesc"><strong>Описание:</strong> Оплата проекта</p>
                        <p className="pmamount"><strong>Сумма:&nbsp;</strong>{total}&nbsp;RUB</p>
                        <label className="pmpaymenttype"> Способ оплаты:</label>
                        <div className="payList" style={{"height": "auto", "overflow": "hidden"}}>
                            {paySystems.map(function(system) {
                                return (
                                    <PaymentPaymasterPaySystem key={system.id} total={total} projectId={projectId} paySystem={system}/>
                                );
                            })}
                            <div className="clearfix"></div>
                        </div>
                        <form id="pmwidgetForm" method="POST" action="https://paymaster.ru/Payment/Init">
                            <div>
                                <input type="hidden" name="LMI_MERCHANT_ID" value="d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60" />
                                <input type="hidden" name="LMI_CURRENCY" value="RUB" />
                                <input type="hidden" name="LMI_PAYMENT_AMOUNT" value={total} />
                                <input type="hidden" name="LMI_PAYMENT_NO" value={projectId} />
                                <input type="hidden" name="LMI_PAYMENT_DESC" value="Оплата проекта" />
                                <input type="hidden" name="LMI_PAYMENT_SYSTEM" id="pmwidgetPS" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        )
    }
}