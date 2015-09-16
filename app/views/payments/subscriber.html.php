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

                    <div id="wire-payment">

                    </div>

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
                            getInitialState: function() {
                                return {active: 0};
                            },
                            componentDidMount: function() {
                                $(".pmwidget").pmwidget();
                            },
                            activate: function() {
                                this.setState({active: 1});
                                console.log('active');
                            },
                            unmakeActiveOnBlur: function(e) {
                                console.log(e.target.checked)
                                if(e.target.checked == true) {
                                    this.setState({active: 1});
                                }else {
                                    this.setState({active: 0});
                                }
                            },
                            render: function() {
                                console.log('render');
                                console.log(this.state);
                                var paySystems = this.props.payload.paySystems;
                                var total = this.props.payload.total + '.00';
                                var projectId = this.props.payload.projectId;
                                var widgetStyle = {"display": "none"};
                                var imageStyle = {"display": "block"};
                                var checked = false;
                                if(this.state.active == 1) {
                                    checked = 'checked';
                                    widgetStyle = {"display": "block"};
                                    imageStyle = {"display": "none"};
                                }
                                return (
                                    <div className="payment paymaster">
                                        <input ref="radio" checked={checked} onChange={this.unmakeActiveOnBlur} onClick={this.activateOnClick} type="radio" name="payment-options" data-pay="paymaster" />
                                        <span onClick={this.makeActiveOnClick} className="description">Оплата электронными деньгами через PayMaster</span>
                                        <img style={imageStyle} onClick={this.makeActiveOnClick} className="imageblock" src="/img/s3_paymaster.png" alt="Дебетовые и кредитные карты" />
                                        <div style={widgetStyle}>
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
                        var PaymentWire = new React.createClass({
                            getInitialState: function() {
                                return {active: 0};
                            },
                            unmakeActiveOnBlur: function(e) {
                                console.log(e.target.checked)
                                if(e.target.checked == true) {
                                    this.setState({active: 1});
                                }else {
                                    this.setState({active: 0});
                                }
                            },
                            activate: function() {
                                this.setState({active: 1});
                                console.log('active');
                            },
                            render: function() {
                                var paySystems = this.props.payload.paySystems;
                                var total = this.props.payload.total + '.00';
                                var projectId = this.props.payload.projectId;
                                var widgetStyle = {"display": "none"};
                                var imageStyle = {"display": "inline-block"};
                                var checked = false;
                                if (this.state.active == 1) {
                                    checked = 'checked';
                                    widgetStyle = {"display": "block"};
                                    imageStyle = {"display": "none"};
                                }
                                return (
                                    <div className="wire">
                                        <div className="payment">
                                            <input ref="radio" checked={checked} onChange={this.unmakeActiveOnBlur}
                                                   onClick={this.activateOnClick} type="radio" name="payment-options"
                                                   data-pay="wire"/>
                                            <img style={imageStyle} onClick={this.makeActiveOnClick} className="imageblock"
                                                 src="/img/s3_rsh.png" alt="Безналичный платеж через банк"/>
                                            <span onClick={this.makeActiveOnClick} className="description">Перевод на
                                                расчетный счёт<br />(Безналичный платеж через банк)
                                            </span>
                                        </div>

                                        <div className="wire-data-inputs" style={{"display": "block"}}>
                                            <label><input type="radio" name="radio-face" className="rb-face" data-pay="offline-fiz" /> ФИЗИЧЕСКОЕ ЛИЦО</label>
                                            <label><input type="radio" name="radio-face" className="rb-face" data-pay="offline-yur" /> ЮРИДИЧЕСКОЕ ЛИЦО</label>
                                            <div className="pay-fiz" style={{"display": "block"}}>
                                                <p>Заполните поля, скачайте счёт на оплату и оплатите его. С помощью него вы можете сделать безналичный перевод через банк.</p>
                                                <form action="/bills/save" method="post" id="bill-fiz">
                                                    <input type="hidden" name="fiz-id" id="fiz-id" value="106221" />
                                                    <input type="hidden" name="fiz-individual" id="fiz-individual" value="1" />
                                                    <input type="text" name="fiz-name" id="fiz-name" placeholder="Иванов Иван Иванович" data-placeholder="Иванов Иван Иванович" required="" data-content="symbolic" className="placeholder" />
                                                    <img src="/img/arrow-bill-download.png" className="arrow-bill-download" />
                                                    <input type="submit" id="button-fiz" value="Скачать счёт" className="button third" style={{"width": "420px"}} />
                                                    <div className="clr"></div>
                                                </form>
                                                <p>Мы активируем ваш проект на сайте в течение рабочего дня после поступления денег, и тогда он появится в <a href="/pitches">общем списке</a>.
                                                    Пока вы можете просмотреть ваш проект в <a href="/users/mypitches">личном кабинете</a>.</p>
                                            </div>
                                            <div className="pay-yur" style={{"display": "block"}}>
                                                <p>Заполните поля, скачайте счёт на оплату и оплатите его. С помощью него вы можете сделать безналичный перевод через банк.</p>
                                                <form action="/bills/save" method="post" id="bill-yur">
                                                    <input type="hidden" name="yur-id" id="yur-id" value="106221" />
                                                    <input type="hidden" name="yur-individual" id="yur-individual" value="0" />

                                                    <label className="required">Наименование организации</label>
                                                    <input type="text" name="yur-name" id="yur-name" placeholder="OOO «КРАУД МЕДИА»" data-placeholder="OOO «КРАУД МЕДИА»" required="" data-content="mixed" className="placeholder" />

                                                    <label className="required">ИНН</label>
                                                    <input type="text" name="yur-inn" id="yur-inn" placeholder="123456789012" data-placeholder="123456789012" required="" data-content="numeric" data-length="[10,12]" className="placeholder" />

                                                    <label>КПП</label>
                                                    <input type="text" name="yur-kpp" id="yur-kpp" placeholder="123456789" data-placeholder="123456789" required="" data-content="numeric" data-length="[9]" className="placeholder" />

                                                    <label className="required">Юридический адрес</label>
                                                    <input type="text" name="yur-address" id="yur-address" placeholder="199397, Санкт-Петербург, ул. Беринга, д. 27" data-placeholder="199397, Санкт-Петербург, ул. Беринга, д. 27" required="" data-content="mixed" className="placeholder" />

                                                    <p>Мы активируем ваш проект на сайте в течение рабочего дня после поступления денег, и тогда он появится в <a href="/pitches">общем списке</a>.
                                                        Пока вы можете просмотреть ваш проект в <a href="/users/mypitches">личном кабинете</a>.</p>
                                                    <p>Закрывающие документы вы получите на e-mail сразу после того, как завершите проект. Распечатайте их, подпишите и поставьте печать.
                                                        Отправьте их нам в двух экземплярах по почте (199397, Россия, Санкт-Петербург, ул. Беринга, д. 27).
                                                        В ответном письме вы получите оригиналы документов с нашей печатью.</p>
                                                    <input type="submit" id="button-yur" value="Скачать счёт" className="button third" style={{"width": "420px"}} />
                                                    <div className="clr"></div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                );
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
                        React.render(
                            <PaymentWire payload={payload}/>,
                            document.getElementById('wire-payment')
                        );
                    </script>

                    <script>
                        var payload = {
                            "total": 61000,
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