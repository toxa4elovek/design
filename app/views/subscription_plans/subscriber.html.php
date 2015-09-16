<div class="wrapper">
    <?= $this->view()->render(array('element' => 'header'), array('header' => 'header2')) ?>
    <?php
    $total = 0;
    foreach($receipt as $row) {
        $total += $row['amount'];
    }
    ?>
    <script>
        var payload = {
            "total": <?= (int) $total?>,
            "projectId": <?= $planRecordId ?>,
            "receipt": <?php echo json_encode($receipt) ?>,
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
            <?php if(isset($plan)): ?>
                <h4>Тариф «<?=$plan['title']?>»</h4>
                <p>Действителен с <?= date('d.m.Y')?>–<?= date('d.m.Y', time() + YEAR)?><br/>
                    Стоимость тарифного плана не включает гонорары дизайнеру.<br/>
                    При оплате тарифного плана вам будет доступен кошелёк (текущий счет), пополнить который можно по мере необходимости в любое время.
                </p>
            <?php else: ?>
                <h4>Пополнение счёта</h4>
                <p>Пополнить кошелёк можно по мере необходимости в любое время</p>
            <?php endif ?>

            <span class="label-fund-balance">Пополнить личный счет, руб.</span>
            <section id="fund-balance-container"></section>

            <div>
                <h4>Выберите способ оплаты</h4>
                <div id="payments-container" class="payments-container"></div>
            </div>
        </div><!-- .main -->
    </div><!-- .middle -->
<?= $this->html->script(array(
    'flux/flux.min.js',
    'jquery-plugins/jquery.numeric.min.js',
    'jquery-plugins/jquery.scrollto.min.js',
    'subscription_plans/actions/PaymentActions.js',
    'common/receipt/ReceiptLine.js',
    'common/receipt/ReceiptTotal.js',
    'common/receipt/Receipt.js',
    'subscription_plans/FundBalanceInput.js',
    'subscription_plans/paymentSystems/BasePaymentSystem.js',
    'subscription_plans/paymentSystems/PaymentSeparator.js',
    'subscription_plans/paymentSystems/PaymentPayture.js',
    'subscription_plans/paymentSystems/PaymentPaymaster.js',
    'subscription_plans/paymentSystems/PaymentPaymasterPaySystem.js',
    'subscription_plans/paymentSystems/PaymentWire.js',
    'subscription_plans/paymentSystems/PaymentTypesList.js',
    'subscription_plans/paymentSystems/PaymentAdmin.js',
    'subscription_plans/subscriber.js'
), array('inline' => false)) ?>
<?= $this->html->style(array(
    '/css/common/page-title-with-flag.css',
    '/css/common/receipt.css',
    '/css/common/payment-options.css',
    '/css/common/paymaster-widget.css',
    '/brief',
    '/step3',
    '/css/subscription_plans/subscribe.css'
), array('inline' => false))?>