<div class="wrapper">
    <?= $this->view()->render(array('element' => 'header'), array('header' => 'header2')) ?>
    <input type="hidden" value="<?= $pitch->id ?>" id="pitch_id"/>
    <input type="hidden" value="" id="addon_id"/>
    <input type="hidden" value="<?= $pitch->billed ?>" id="billed"/>
    <input type="hidden" value="<?= $pitch->published ?>" id="published"/>
    <?php $sum = ($receipt[0]->value + $receipt[1]->value)? : 0 ?>
    <input type="hidden" value="<?= $sum ?>" id="amount"/>

    <aside class="summary-price expanded">
        <h3>Итого:</h3>
        <p class="summary"><strong id="total-tag"><?= $sum ?>.-</strong></p><!-- .summary -->
        <ul id="check-tag">
            <?php foreach ($receipt as $v): ?>
                <li><span><?= $v->name ?></span><small><?= $v->value ?>.-</small></li>
            <?php endforeach; ?>
        </ul>
        <a href="#" class="show" id="show-check"><span>Подробнее</span></a>
        <a href="#" class="hide" id="hide-check"><span>Скрыть</span></a>
    </aside><!-- .summary-price -->
    <div class="middle add-pitch" id="step3">
        <div class="main">
            <p id="top-pay">Выкупить решение можно только при первоначальном размере вознаграждения.<br />
                Стоимость дополнительных опций не взимается, если таковы были заказаны,
                однако учитывается сбор сервиса GoDesigner.</p><br />
            <div>
                <h1>выберите способ оплаты</h1>
                <div class="g_line"></div>
                <div id="P_card">
                    <table>

                        <tr class="payture-section">
                            <td>
                                <input type="radio" name="1" class="rb1" data-pay="payture" style="background: #a2b2bb;">
                            </td>
                            <td colspan="2" class="s3_text" style="padding-left: 20px;">
                                Оплата дебетовыми или кредитными картами<br><br>
                                <!--p style="font-size:11px; text-transform: ">Всвязи с временным ограничением платежной системы PayMaster,<br> максимально возможная сумма платежа может составлять от 15000-35000. <br>Подробнее <a href="/answers/view/91">тут</a>. В случае, если ваш платеж превышает лимит, пожалуйста, воспользуйтесь переводом на рассчетный счет (ниже).<br> Спасибо за понимание!</p-->
                            </td>
                            <td style="width: 190px;">
                                <a href="/payments/startpayment/<?= $pitch->id ?>" id="paybutton-payture" class="button" style="display: none;">Оплатить</a>
                            </td>
                        </tr>
                        <tr id="online-images">
                            <td colspan="4" style="padding: 20px 0 0 40px;">
                                <img src="/img/s3_master.png" alt="Дебетовые и кредитные карты">
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4"><div class="g_line"><i>или</i></div></td>
                        </tr>

                        <tr>
                            <td>
                                <input type="radio" name="1" class="rb1" data-pay="offline">
                            </td>
                            <td class="s3_h">
                                <img src="/img/s3_rsh.png" alt="">
                            </td>
                            <td class="s3_text">
                                Перевод  на расчетный счёт<br/>(Безналичный платеж через банк)
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <div id="s3_kv">
                                    <label><input type="radio" name="radio-face" class="rb-face" data-pay="offline-fiz"> ФИЗИЧЕСКОЕ ЛИЦО</label>
                                    <label><input type="radio" name="radio-face" class="rb-face" data-pay="offline-yur"> ЮРИДИЧЕСКОЕ ЛИЦО</label>
                                    <div class="pay-fiz">
                                        <p>Заполните поля, скачайте счёт на оплату и оплатите его. С помощью него вы можете сделать безналичный перевод через банк.</p>
                                        <form action="/bills/save" method="post" id="bill-fiz">
                                            <input type="hidden" name="fiz-id" id="fiz-id" value="<?= $pitch->id ?>">
                                            <input type="hidden" name="fiz-individual" id="fiz-individual" value="1">
                                            <input type="text" name="fiz-name" id="fiz-name" placeholder="Иванов Иван Иванович" data-placeholder="Иванов Иван Иванович" required="" data-content="symbolic">
                                            <img src="/img/arrow-bill-download.png" class="arrow-bill-download" />
                                            <input type="submit" id="button-fiz" value="Скачать счёт" class="button third" style="width:420px;">
                                            <div class="clr"></div>
                                        </form>
                                        <p>Мы активируем ваш проект на сайте в течение рабочего дня после поступления денег, и тогда он появится в <a href="/pitches">общем списке</a>.
                                            Пока вы можете просмотреть ваш проект в <a href="/users/mypitches">личном кабинете</a>.</p>
                                    </div>
                                    <div class="pay-yur">
                                        <p>Заполните поля, скачайте счёт на оплату и оплатите его. С помощью него вы можете сделать безналичный перевод через банк.</p>
                                        <form action="/bills/save" method="post" id="bill-yur">
                                            <input type="hidden" name="yur-id" id="yur-id" value="<?= $pitch->id ?>">
                                            <input type="hidden" name="yur-individual" id="yur-individual" value="0">

                                            <label class="required">Наименование организации</label>
                                            <input type="text" name="yur-name" id="yur-name" placeholder="OOO «КРАУД МЕДИА»" data-placeholder="OOO «КРАУД МЕДИА»" required="" data-content="mixed">

                                            <label class="required">ИНН</label>
                                            <input type="text" name="yur-inn" id="yur-inn" placeholder="123456789012" data-placeholder="123456789012" required="" data-content="numeric" data-length="[10,12]">

                                            <label>КПП</label>
                                            <input type="text" name="yur-kpp" id="yur-kpp" placeholder="123456789" data-placeholder="123456789" required="" data-content="numeric" data-length="[9]">

                                            <label class="required">Юридический адрес</label>
                                            <input type="text" name="yur-address" id="yur-address" placeholder="199397, Санкт-Петербург, ул. Беринга, д. 27" data-placeholder="199397, Санкт-Петербург, ул. Беринга, д. 27" required="" data-content="mixed">

                                            <p>Мы активируем ваш проект на сайте в течение рабочего дня после поступления денег, и тогда он появится в <a href="/pitches">общем списке</a>.
                                                Пока вы можете просмотреть ваш проект в <a href="/users/mypitches">личном кабинете</a>.</p>
                                            <p>Закрывающие документы вы получите на e-mail сразу после того, как завершите проект. Распечатайте их, подпишите и поставьте печать.
                                                Отправьте их нам в двух экземплярах по почте (199397, Россия, Санкт-Петербург, ул. Беринга, д. 27).
                                                В ответном письме вы получите оригиналы документов с нашей печатью.</p>
                                            <input type="submit" id="button-yur" value="Скачать счёт" class="button third" style="width:420px;">
                                            <div class="clr"></div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"><div class="g_line"><i>или</i></div></td>
                        </tr>
                        <tr class="paymaster-section">
                            <td>
                                <input type="radio" name="1" class="rb1" data-pay="paymaster" style="background: #a2b2bb;">
                            </td>
                            <td colspan="3" class="s3_text" style="padding-left: 20px;">
                                Оплата пластиковыми картами и эл. деньгами <br>через PayMaster<br><br>
                                <p style="font-size:11px; text-transform: ">Всвязи с временным ограничением платежной системы PayMaster,<br> максимально возможная сумма платежа может составлять от 15000-35000. <br>Подробнее <a href="/answers/view/91">тут</a>. В случае, если ваш платеж превышает лимит, пожалуйста, воспользуйтесь переводом на рассчетный счет (ниже).<br> Спасибо за понимание!</p>
                            </td>
                        </tr>
                        <tr id="paymaster-images" class="paymaster-section">
                            <td colspan="4" class="s3_text" style="padding: 20px 0 0 40px; text-transform: uppercase;">
                                <img src="/img/s3_paymaster.png" alt="">
                                <span style="margin: 0 0 0 20px; line-height: 3em;">и другие...</span>
                            </td>
                        </tr>
                        <tr id="paymaster-select" class="paymaster-section" style="display: none;">
                            <td colspan="4">
                                <?php echo $this->html->script(array('jquery-1.7.1.min.js')); ?>
                                <script type='text/javascript' src='https://paymaster.ru/widget/BasicFP/1?LMI_MERCHANT_ID=d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60&LMI_PAYMENT_AMOUNT=<?= $sum ?>&LMI_PAYMENT_DESC=<?php echo urlencode('Оплата проекта') ?>&LMI_CURRENCY=RUB&LMI_PAYMENT_NO=<?= $pitch->id ?>'></script>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="g_line"></div>
                <p class="submit">
                    <input type="submit" value="Назад" id="prev" class="button steps-link" data-step="2">
                </p><!-- .submit -->
            </div>
        </div><!-- .main -->
    </div><!-- .middle -->
    <?= $this->html->script(array('jquery-ui-1.8.17.custom.min.js', 'jquery.scrollto.min.js', 'pitches/newwinner.js?' . mt_rand(100, 999), 'jquery.numeric', 'jquery.iframe-transport.js', 'jquery.fileupload.js', 'jquery.simplemodal-1.4.2.js', 'jquery.tooltip.js', 'popup.js', 'jquery.damnUploader.js'), array('inline' => false)) ?>
    <?=
    $this->html->style(array('/brief', '/step3'), array('inline' => false))?>