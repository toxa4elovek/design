<div class="wrapper">
    <?= $this->view()->render(array('element' => 'header'), array('header' => 'header2')) ?>

    <?php
    $sum = 0;
    foreach ($receipt as $rec) {
        $sum += $rec->value;
    }
    ?>
    <div id="receipt-container"></div>

    <div class="middle">
        <div class="main">
            <h3 class="page-title-with-flag">Оплата</h3>
            <h4>Тариф «Фирменный»</h4>
            <p>Действителен с 28.08.2015–27.08.2016<br/>
                Стоимость тарифного плана не включает гонорары дизайнеру.<br/>
                При оплате тарифного плана вам будет доступен кошелёк (текущий счет), пополнить который можно по мере необходимости в любое время.
            </p>

            <div>
                <h4>Выберите способ оплаты</h4>

                <div class="payments-container">

                    <div id="payture-payment"></div>

                    <div class="separator">
                        <span>или</span>
                    </div>

                    <div id="paymaster-payment"></div>

                    <div class="separator">
                        <span>или</span>
                    </div>
                    <script>
                        var payload = {
                            "total": 20000,
                            "projectId": 300000,
                            "receipt": [
                                {"name": "Оплата тарифа", "amount": 49000},
                                {"name": "Проверка чека", "amount": 12000}
                            ],
                            "paySystems": [
                                {
                                    "id": 30,
                                    "logo": "https://paymaster.ru/Content/img/logos/yandex.gif",
                                    "title": "Яндекс.Деньги"
                                },
                                {
                                    "id": 31,
                                    "logo": "https://paymaster.ru/Content/img/logos/webmoney.gif",
                                    "title": "WebMoney"
                                },
                                {
                                    "id": 46,
                                    "logo": "https://paymaster.ru/Content/img/logos/qiwi_h.png",
                                    "title": "QIWI-кошелек"
                                },
                                {
                                    "id": 8,
                                    "logo": "https://paymaster.ru/Content/img/logos/alfabank.gif",
                                    "title": "Альфа-банк"
                                },
                                {
                                    "id": 24,
                                    "logo": "https://paymaster.ru/Content/img/logos/brs.gif",
                                    "title": "Русский Стандарт Банк"
                                },                                                        {
                                    "id": 62,
                                    "logo": "https://paymaster.ru/Content/img/logos/euroset.png",
                                    "title": "Евросеть"
                                },                            {
                                    "id": 64,
                                    "logo": "https://paymaster.ru/Content/img/logos/psb-paymaster.png",
                                    "title": "Промсвязьбанк"
                                },                            {
                                    "id": 65,
                                    "logo": "https://paymaster.ru/Content/img/logos/svyaznoi.png",
                                    "title": "Связной"
                                },                            {
                                    "id": 93,
                                    "logo": "https://paymaster.ru/Content/img/logos/92_VisaMastercardLogo.gif",
                                    "title": "Банковская карта"
                                }
                            ]
                        };
                    </script>

                    <script type="text/jsx">
                        var PaymentPayture = new React.createClass({
                            render: function() {
                                return (
                                    <div className="payment">
                                        <input type="radio" name="payment-options" data-pay="payture" />
                                        <span className="description">Оплата дебетовыми или кредитными картами</span>
                                        <a href="/payments/startpayment/0" className="button">Оплатить</a>
                                        <img className="imageblock" src="/img/s3_master.png" alt="Дебетовые и кредитные карты" />
                                    </div>
                                )
                            }
                        });

                        var PaymentPaymasterPaySystem = new React.createClass({
                            render: function() {
                                var paySystem = this.props.paySystem;
                                var link = 'https://paymaster.ru/Payment/Init?LMI_PAYMENT_SYSTEM=' + paySystem.id + '&amp;LMI_MERCHANT_ID=d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60&amp;LMI_CURRENCY=RUB&amp;LMI_PAYMENT_AMOUNT=' + this.props.total + '&amp;LMI_PAYMENT_NO=' + this.props.projectId + '&amp;LMI_PAYMENT_DESC=%d0%9e%d0%bf%d0%bb%d0%b0%d1%82%d0%b0+%d0%bf%d1%80%d0%be%d0%b5%d0%ba%d1%82%d0%b0';
                                return (
                                    <a href={link} rel={paySystem.id} className="pm-item paySystem" title={paySystem.title}>
                                        <img src={paySystem.logo} alt="" title={paySystem.title} />
                                    </a>
                                )
                            }
                        });
                        var PaymentPaymaster = new React.createClass({
                            componentDidMount: function() {
                                $(".pmwidget").pmwidget();
                            },
                            render: function() {
                                var paySystems = this.props.payload.paySystems;
                                var total = this.props.payload.total + '.00';
                                var projectId = this.props.payload.projectId;
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
                                                <p className="pmamount"><strong>Сумма:&nbsp;</strong>{{total}}&nbsp;RUB</p>
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
                        });
                        React.render(
                            <PaymentPayture payload={payload} />,
                            document.getElementById('payture-payment')
                        );
                        React.render(
                            <PaymentPaymaster payload={payload}/>,
                            document.getElementById('paymaster-payment')
                        );
                    </script>

                    <table>
                        <tr class="paymaster-section">
                            <td>
                                <input type="radio" name="1" class="rb1" data-pay="paymaster" style="background: #a2b2bb;">
                            </td>
                            <td colspan="1" class="s3_text" style="padding-left: 20px;">
                                Оплата пластиковыми картами и эл. деньгами <!--br-->через PayMaster<br><br>
                                <!--p style="font-size:11px; text-transform: ">Всвязи с временным ограничением платежной системы PayMaster,<br> максимально возможная сумма платежа может составлять от 15000-35000. <br>Подробнее <a href="/answers/view/91">тут</a>. В случае, если ваш платеж превышает лимит, пожалуйста, воспользуйтесь переводом на рассчетный счет (ниже).<br> Спасибо за понимание!</p-->
                            </td>
                        </tr>
                        <tr id="paymaster-images" class="paymaster-section">
                            <td colspan="4" class="s3_text" style="padding: 20px 0 0 40px; text-transform: uppercase;">
                                <img src="/img/s3_paymaster.png" alt="">
                                <span style="margin: 0 0 0 20px; line-height: 3em;">и другие...</span>
                            </td>
                        </tr>
                        <tr id="paymaster-select" class="paymaster-section" style="display: block;">
                            <td colspan="4">
                                <!--script type='text/javascript' src='https://paymaster.ru/widget/BasicFP/1?LMI_MERCHANT_ID=d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60&LMI_PAYMENT_AMOUNT=<?= $sum ?>&LMI_PAYMENT_DESC=<?php echo urlencode('Оплата проекта') ?>&LMI_CURRENCY=RUB&LMI_PAYMENT_NO=<?= $pitch->id ?>'></script-->
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="g_line"></div>
                <p class="submit">
                    <a href="/pages/fastpitch"  class="button steps-link">Назад</a>
                </p><!-- .submit -->
            </div>
        </div><!-- .main -->
    </div><!-- .middle -->
<?= $this->html->script(array(
    'payments/ReceiptLine.js',
    'payments/ReceiptTotal.js',
    'payments/Receipt.js',
    'common/pmwidget.js',
    'payments/subscriber.js'
), array('inline' => false)) ?>
<?= $this->html->style(array(
    '/css/common/page-title-with-flag.css',
    '/css/common/receipt.css',
    '/css/common/payment-options.css',
    '/css/common/paymaster-widget.css',
    '/brief',
    '/step3',
    '/css/payments/subscribe.css'
), array('inline' => false))?>