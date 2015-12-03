<div class="wrapper">
    <?= $this->view()->render(array('element' => 'header'), array('header' => 'header2')) ?>
    <?php
    $total = 0;
    foreach($receipt as $row) {

        $total += $row['value'];
    }
    ?>
    <script>
        var payload = {
            "total": <?= (int) $total?>,
            "projectId": <?= $penaltyId ?>,
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
            <h3 style="color: #666;
font-family: RodeoC;
font-size: 25px;
font-weight: 700;
line-height: 27px;margin-top: 20px;">Оплата штрафа <?= (int) $total?> р.</h3>
                <p style="margin-top: 13px;">Вы не сможете выбрать победителя, пока не оплатите штраф.<br/><br/>

                    Вы не успели выбрать победителя в положенный срок 4 дня, и за каждый<br/>
                    просроченный час мы взимаем 25 руб. Пожалуйста, оплатите штраф любым<br/>
                    удобным способом. Как только средства поступят на наш счёт, решение<br/>
                    <a target="_blank" href="/pitches/viewsolution/<?= $solution->id ?>">#<?= $solution->num ?></a> в проекте «<a target="_blank" href="/pitches/view/<?= $pitch->id ?>"><?= $pitch->title ?></a>» будет назначено победителем, и&nbsp;вы сможете внести правки на&nbsp;<a href="" target="_blank">завершающий этапе</a>.</p>

            <div class="multiclass">
                <!-- multisolution branch -->
                <div class="photo_block">
                    <!-- solo branch -->
                    <?php if($this->solution->getImageCount($solution->images['solution_galleryLargeSize']) > 1):?>
                    <div style="z-index: 2; position: absolute; color: rgb(102, 102, 102); font-weight: bold; font-size: 14px; padding-top: 7px; height: 16px; top: -34px; text-align: right; width: 18px; padding-right: 21px; background: url(/img/multi-icon.png) no-repeat scroll 22px 5px transparent; left: 169px;"><?=$this->solution->getImageCount($solution->images['solution_solutionView'])?></div>
                    <?php endif?>

                    <a style="display:block;" class="imagecontainer" href="/pitches/viewsolution/<?= $solution->id?>?sorting=rating">
                        <img style="position: absolute; left: 10px; top: 9px;" width="180" height="135" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>" alt="">
                    </a>

                    <div class="photo_opt" style="padding-top:0; margin-top:144px;">
                        <div class="" style="display: block; float:left;">
                            <span class="rating_block">
                                <div class="ratingcont" data-default="<?= $solution->rating ?>" style="float: left; height: 9px; background: url(/img/<?= $solution->rating ?>-rating.png) repeat scroll 0 0 transparent; width: 56px; margin-top: 5px;">
                                    <a data-rating="1" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                                    <a data-rating="2" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                                    <a data-rating="3" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                                    <a data-rating="4" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                                    <a data-rating="5" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                                </div>
                            </span>
                            <span class="like_view" style="margin-top:2px;margin-right: 5px;">
                                <img src="/img/looked.png" alt="" class="icon_looked">
                                <span><?= $solution->views ?></span>
                            </span>
                        </div>
                        <ul style="margin-left: 78px;" class="right">
                            <li class="like-hoverbox" style="float: left; margin-top: 0; padding-top: 0; height: 15px; padding-right: 0; margin-right: 0; width: 38px;">
                                <a href="#" data-status="1" style="float:left" class="like-small-icon" data-id="<?= $solution->id ?>"><img src="/img/like.png" alt="количество лайков"></a>
                                <span class="underlying-likes" style="color: rgb(205, 204, 204); font-size: 10px; vertical-align: middle; display: block; float: left; height: 16px; padding-top: 1px; margin-left: 2px;" data-id="<?= $solution->id ?>" rel="http://www.godesigner.ru/pitches/viewsolution/<?= $solution->id ?>"><?= $solution->likes ?></span>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="selecting_numb"><a href="/users/view/<?= $solution->user_id ?>" class="portfolio_gallery_username"><?= $this->user->getFormattedName($solution->user->first_name, $solution->user->last_name)?></a><a href="#" class="number_img_gallery" data-comment-to="#<?= $solution->num ?>">#<?= $solution->num ?></a></div>
            </div>


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
        'subscription_plans/paymentSystems/BasePaymentSystem.js',
        'subscription_plans/paymentSystems/PaymentSeparator.js',
        'subscription_plans/paymentSystems/PaymentPayture.js',
        'subscription_plans/paymentSystems/PaymentPaymaster.js',
        'subscription_plans/paymentSystems/PaymentPaymasterPaySystem.js',
        'subscription_plans/paymentSystems/PaymentWire.js',
        'subscription_plans/paymentSystems/PaymentTypesList.js',
        'subscription_plans/paymentSystems/PaymentAdmin.js',
        'pitches/penalty.js'
    ), array('inline' => false)) ?>
<?= $this->html->style(array(
    '/css/common/page-title-with-flag.css',
    '/css/common/receipt.css',
    '/css/common/payment-options.css',
    '/css/common/paymaster-widget.css',
    '/brief',
    '/step3',
    '/css/subscription_plans/subscribe.css',
    '/css/pitches/penalty.css'
), array('inline' => false))?>