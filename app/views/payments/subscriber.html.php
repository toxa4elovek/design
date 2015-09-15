<div class="wrapper">
    <?= $this->view()->render(array('element' => 'header'), array('header' => 'header2')) ?>

    <?php
    $sum = 0;
    foreach ($receipt as $rec) {
        $sum += $rec->value;
    }
    ?>
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

                </div>
                <div class="g_line"></div>
                <p class="submit">
                    <a href="/pages/fastpitch"  class="button steps-link">Назад</a>
                </p><!-- .submit -->
            </div>
        </div><!-- .main -->
    </div><!-- .middle -->
<?= $this->html->script(array(
    'common/receipt/ReceiptLine.js',
    'common/receipt/ReceiptTotal.js',
    'common/receipt/Receipt.js',
    'payments/PaymentPayture.js',
    'payments/PaymentPaymaster.js',
    'payments/PaymentPaymasterPaySystem.js',
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