<div class="wrapper">

    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo', 'header' => 'header2'])?>

    <main class="lp-subscribe">
        <section class="lp-header">
            <div class="lp-autocenter">
                <img src="/img/pages/subscribe/header_text.png" alt="Часто нужен дизайнер?"/>
                <div class="red-discount-star">
                    <span class="red-discount-star-text"><?= $discount?></span>
                </div>
                <p>Станьте нашим абонентом, и вы сможете создавать проекты от 500р.
                    Только в течение <?php
                    echo ceil((strtotime($discountEndTime) - time()) / DAY);
                    ?> дней мы дарим вам возможность<br/>
                    стать абонентом со скидкой <?= $discount?> %!</p>
                <div class="button-container">
                    <a href="/answers/view/102" target="_blank" class="blue-button clean-style-button ">Как это работает</a>
                    <a href="#" id="scroll-button" class="clean-style-button silver-button">Стать абонентом</a>
                </div>
            </div>
        </section>
        <section class="lp-advantages paper-background">
            <div class="lp-autocenter">
                <h1>Преимущества абонентов</h1>
                <ul>
                    <li class="adv-500">
                        <span>1</span>
                        <h6>Задачи от 500 р.</h6>
                        <p>Купив годовое абонентское обслуживание, вы сможете запускать даже самые маленькие задачи от 500 рублей!</p>
                    </li>
                    <li class="adv-timer ">
                        <span>2</span>
                        <h6>Срочные проекты</h6>
                        <p>Устанавливайте, как абонент, свои сроки: от часа<br/>
                            на внесение правок до месяцев для поиска идей!</p>
                    </li>
                    <li class="adv-rouble">
                        <span>3</span>
                        <h6>Вернём деньги</h6>
                        <p>Мы вернем средства , если решения не понравятся.<br/>
                            Теперь без каких-либо условий и требований.</p>
                    </li>
                </ul>
            </div>
        </section>
        <section class="lp-headline">
            Вам больше не нужен дизайнер в штате.<br />
            Решайте творческие и технические задачи через сервис,<br />  и выбирайте лучшие идеи!
        </section>
        <section class="lp-badges" style="height: 357px;">
            <div class="badges-block" style="bottom: -36px; background: url(/img/pages/subscribe/badge_subscribed.png) no-repeat center top;">
                <div class="badge" style="position: relative;">
                    <div class="green-discount-star" style="top: 75px; right: 5px; -webkit-transform: rotate(12deg) scale(0.4) !important; transform: rotate(12deg) scale(0.4)">
                        <span class="green-discount-star-text">- <?= $discount?></span>
                    </div>
                    <p class="old-price">49 000</p>
                    <span class="price" style="margin-top: 17px;"><?= $this->moneyFormatter->formatMoney($this->moneyFormatter->applyDiscount(49000, $discount), ['suffix' => ''])?></span>
                    <span class="price-description" style="margin-bottom: 32px;">руб./год*</span>
                    <a href="/subscription_plans/subscriber/1" class="small-rounded-button almost-white">Оплатить</a>
                </div>
                <div class="center-badge" style="position: relative;">
                    <div class="green-discount-star" style="top: 35px; right: -15px; -webkit-transform: rotate(12deg) scale(0.4) !important; transform: rotate(12deg) scale(0.4)">
                        <span class="green-discount-star-text">- <?= $discount?></span>
                    </div>
                    <p class="old-price" style="padding-left: 0 !important">69 000</p>
                    <span class="price" style="margin-top: 17px;"><?= $this->moneyFormatter->formatMoney($this->moneyFormatter->applyDiscount(69000, $discount), ['suffix' => ''])?></span>
                    <span class="price-description" style="margin-bottom: 32px;">руб./год*</span>
                    <a href="/subscription_plans/subscriber/2" class="small-rounded-button red-button">Оплатить</a>
                </div>
                <div class="right-badge" style="position: relative;">
                    <div class="green-discount-star" style="top: 80px; right: 5px; -webkit-transform: rotate(12deg) scale(0.4) !important; transform: rotate(12deg) scale(0.4)">
                        <span class="green-discount-star-text">- <?= $discount?></span>
                    </div>
                    <p class="old-price" style="margin-bottom: 0;">89 000</p>
                    <span class="price" style="margin-top: 17px;"><?= $this->moneyFormatter->formatMoney($this->moneyFormatter->applyDiscount(89000, $discount), ['suffix' => ''])?></span>
                    <span class="price-description" style="margin-bottom: 32px;">руб./год*</span>
                    <a href="/subscription_plans/subscriber/3" class="small-rounded-button almost-white">Оплатить</a>
                </div>
            </div>
        </section>
        <section class="lp-table paper-background" style="height: 930px">
            <div class="lp-autocenter">
                <a name="plans"></a>
                <h1>Таблица тарифных планов</h1>
                <table class="tariff-table">
                    <thead>
                    <tr>
                        <td class="title-cell" width="398">Услуга</td>
                        <td width="175">Предпринимательский</td>
                        <td width="115">Фирменный</td>
                        <td width="140">Корпоративный</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="title-cell">Создание проектов без сервисного сбора</td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                    </tr>
                    <tr>
                        <td class="title-cell">Создание проектов от 500 руб.</td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                    </tr>
                    <tr>
                        <td class="title-cell" >Вы сами определяете срок приёма работ</td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                    </tr>
                    <tr>
                        <td class="title-cell" >Закрывающие документы</td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                    </tr>

                    <tr>
                        <td class="title-cell" colspan="2">Опция <a href="/answers/view/64" target="_blank">«Скрыть проект»</a> бесплатно</td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                    </tr>
                    <tr>
                        <td class="title-cell" colspan="2">Вы сами определяете срок определения победителя</td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                    </tr>
                    <tr>
                        <td class="title-cell" colspan="2">Все идеи доступны в 100% разрешении</td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                    </tr>
                    <tr>
                        <td class="title-cell" colspan="3"><a href="/answers/view/68" target="_blank">Заполнение брифа</a> по телефону бесплатно</td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                    </tr>
                    <tr>
                        <td class="title-cell" colspan="3">Опция <a href="/answers/view/67" target="_blank">«Прокачать проект»</a> бесплатно</td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                    </tr>
                    <tr>
                        <td class="title-cell" colspan="3">Подписание индивидуального договора</td>
                        <td class="tick-cell"><img src="/img/pages/subscribe/tick.png" alt="Включено"></td>
                    </tr>

                    </tbody>
                </table>
                <table class="footer-table">
                    <tr>
                        <td class="description">* Стоимость тарифного плана не включает гонорары дизайнеру. Абоненты каждого получат возможность<br/> создать счет в рамках сервиса и пополнять его<br/> в зависимости от задач.</td>
                        <td class="try">
                            <h5><?= $this->moneyFormatter->formatMoney($this->moneyFormatter->applyDiscount(49000, $discount), ['suffix' => ''])?></h5>р./год <a href="/subscription_plans/subscriber/1" class="small-rounded-button almost-white">оплатить</a></td>
                        <td class="try">
                            <h5><?= $this->moneyFormatter->formatMoney($this->moneyFormatter->applyDiscount(69000, $discount), ['suffix' => ''])?></h5>р./год <a href="/subscription_plans/subscriber/2" class="small-rounded-button almost-white">оплатить</a></td>
                        <td class="try">
                            <h5><?= $this->moneyFormatter->formatMoney($this->moneyFormatter->applyDiscount(89000, $discount), ['suffix' => ''])?></h5>р./год <a href="/subscription_plans/subscriber/3" class="small-rounded-button almost-white">оплатить</a></td>
                    </tr>
                    <tr style="height: 15px;"><td colspan="4"></td></tr>
                    <tr>
                        <td></td>
                        <td class="try"><h5><?= $this->moneyFormatter->formatMoney($this->moneyFormatter->applyDiscount(27000, $discount), ['suffix' => ''])?></h5>р. за 6 месяцев <a href="/subscription_plans/subscriber/6" class="small-rounded-button almost-white">оплатить</a></td>
                        <td class="try"><h5><?= $this->moneyFormatter->formatMoney($this->moneyFormatter->applyDiscount(39000, $discount), ['suffix' => ''])?></h5>р. за 6 месяцев <a href="/subscription_plans/subscriber/7" class="small-rounded-button almost-white">оплатить</a></td>
                        <td></td>
                    </tr>
                    <tr style="height: 15px;"><td colspan="4"></td></tr>
                    <tr>
                        <td></td>
                        <td class="try"><h5><?= $this->moneyFormatter->formatMoney($this->moneyFormatter->applyDiscount(15000, $discount), ['suffix' => ''])?></h5>р. за 3 месяца <a href="/subscription_plans/subscriber/5" class="small-rounded-button almost-white">оплатить</a></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </section>
        <section class="lp-timer paper-background" >
            <div class="lp-autocenter" id="timerMount">
                <input type="hidden" name="discount_end_date"  value="<?= $discountEndTime?>"/>
            </div>
        </section>

        <!--section class="lp-getinfo">
            Остались вопросы?<br />
            Мы ответим в течение рабочего дня: <a href="mailto:team@godesigner.ru">team@godesigner.ru</a><br />
            Или просто звоните по телефону <a href="tel:+78126482412">+7 812 648-24-12</a> с 10-17 по Москве<br />
        </section-->
    </main>
</div><!-- .wrapper -->
<?=$this->html->style([
    '/css/common/buttons.css',
    '/css/common/backgrounds.css',
    '/css/pages/subscribe.css'
], ['inline' => false])?>
<?=$this->html->script([
    'jquery-plugins/jquery.scrollto.min.js',
    '/js/moment.min.js',
    '/js/pages/components/CountdownTimer.js',
    'pages/subscribe.js'
], ['inline' => false])?>
