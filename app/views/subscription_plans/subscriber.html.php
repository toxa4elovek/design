<div class="wrapper">
    <?= $this->view()->render(['element' => 'header'], ['header' => 'header2']) ?>
    <?php
    $total = 0;
    $startValue = 9000;
    foreach ($receipt as $row) {
        $total += $row['value'];
        if ($row['name'] === 'Пополнение счёта') {
            $startValue = $row['value'];
        }
    }
    if ($plan['id'] === 4) {
        $heading = 'Бизнес-план';
        $description = 'бизнес-плана';
    } else {
        $heading = 'Тариф';
        $description = 'тарифного плана';
    }
    ?>
    <script>
        var payload = {
            "total": <?= (int) $total?>,
            "projectId": <?= $planRecordId ?>,
            "startValue": <?= $startValue ?>,
            "receipt": <?php echo json_encode($receipt) ?>,
            "userData": <?php echo json_encode($this->user->getCompanyData())?>,
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
            <h3 class="page-title-with-flag" style="margin-top: 10px;">Оплата</h3>
            <?php if (isset($plan)):
                if ($discount == 0) {
                    $value = $plan['price'];
                } else {
                    $value = $this->MoneyFormatter->applyDiscount($plan['price'], $discount);
                }
                ?>
                <h4 style="line-height: 30px;"><?=$heading?> «<?=$plan['title']?>» <?=$this->MoneyFormatter->formatMoney($value, ['suffix' => ' р.-'])?>
                    <?php if ($discount):?><br/>(со скидкой <?=$discount?>%)<?php endif;?>

                </h4>
                <p>Действителен с <?= date('d.m.Y')?>–<?= date('d.m.Y', time() + YEAR)?><br/>
                    Стоимость <?=$description?> не включает гонорары дизайнеру.<br/>
                    При оплате <?=$description?> вам будет доступен кошелёк (личный счет), пополнить который можно по мере необходимости в любое время.
                </p>
            <?php else: ?>
                <h4>Пополнение счёта</h4>
                <?php if (isset($predefined)): ?>
                    <p>Для выбранного действия необходимо пополнить личный счёт на указанную сумму.</p>
                <?php else:?>
                    <p>Пополнить кошелёк можно по мере необходимости в любое время</p>
                <?php endif;?>
            <?php endif ?>

            <span class="label-fund-balance">Пополнить личный счет, руб.</span>
            <section id="fund-balance-container"></section>

            <?php if (!$this->user->isLoggedIn()):?>
                <span class="label-fund-balance">Оставьте номер телефона</span>
                <section id="phone-number-container"></section>
            <?php endif?>

            <div>
                <h4>Выберите способ оплаты</h4>
                <div id="payments-container" class="payments-container"></div>
            </div>
        </div><!-- .main -->
    </div><!-- .middle -->
<?= $this->html->script([
    'flux/flux.min.js',
    'jquery-plugins/jquery.numeric.min.js',
    'jquery-plugins/jquery.scrollto.min.js',
    'subscription_plans/actions/PaymentActions.js',
    'common/receipt/ReceiptLine.js',
    'common/receipt/ReceiptTotal.js',
    'common/receipt/Receipt.js',
    'subscription_plans/FundBalanceInput.js',
    'subscription_plans/PhoneNumberInput.js',
    'subscription_plans/paymentSystems/BasePaymentSystem.js',
    'subscription_plans/paymentSystems/PaymentSeparator.js',
    'subscription_plans/paymentSystems/PaymentPayture.js',
    'subscription_plans/paymentSystems/PaymentPaymaster.js',
    'subscription_plans/paymentSystems/PaymentPaymasterPaySystem.js',
    'subscription_plans/paymentSystems/PaymentWire.js',
    'subscription_plans/paymentSystems/PaymentTypesList.js',
    'subscription_plans/paymentSystems/PaymentAdmin.js',
    'subscription_plans/subscriber.js'
], ['inline' => false]) ?>
<?= $this->html->style([
    '/css/common/page-title-with-flag.css',
    '/css/common/receipt.css',
    '/css/common/payment-options.css',
    '/css/common/paymaster-widget.css',
    '/brief',
    '/step3',
    '/css/subscription_plans/subscribe.css'
], ['inline' => false])?>