<div style="padding-bottom: 50px;">
    <h1>выберите способ оплаты</h1>
    <!--h1 class="greyboldheader" style="text-transform:none; line-height: 20px; font: bold 17px/24px 'RodeoC',sans-serif">Друзья, в связи со внезапным <a href="http://lenta.ru/news/2013/11/20/master/" style="" target="_blank">приостановлением работы</a> «Мастер-банка», оплата питчей пока стала возможна только <br/>с помощью переводов на альтернативный рассчетный счет. Благодарим за понимание.</h1-->
    <!--p>В связи с временным ограничением платежной системы Paymaster, максимально возможная сумма платежа составляет <b>15000</b> рублей.</p-->
    <!-- <?php echo var_dump($pitch->category_id);  ?> -->
    <div class="g_line"></div>
    <div id="P_card">
        <table>
            <?php if ($pitch->category_id != 10):?>
                <tr>
                    <td>
                        <input type="radio" name="1" class="rb1" data-pay="payanyway">
                    </td>
                    <td colspan="2" class="s3_text" style="padding-left: 20px;">
                        Оплата пластиковыми картами <br>через Payanyway<br><br>
                    </td>
                    <td>
                        <form id="payanyway_form" method="post" action="https://www.moneta.ru/assistant.htm">
                            <input type="hidden" name="MNT_ID" value="36102238">
                            <input type="hidden" name="MNT_TRANSACTION_ID" value="">
                            <input type="hidden" name="MNT_CURRENCY_CODE" value="RUB">
                            <input type="hidden" name="MNT_AMOUNT" value="">
                            <input type="hidden" name="MNT_TEST_MODE" value="1">
                            <input type="hidden" name="paymentSystem.unitId" value="499669">
                            <input type="hidden" name="followup" value="true">
                            <input type="submit" id="paybutton-payanyway" value="продолжить оплату" class="button" style="background: #a2b2bb;">
                        </form>
                    </td>
                </tr>
                <tr id="online-images">
                    <td colspan="4" style="padding: 20px 0 0 40px;">
                        <img src="/img/s3_master.png" alt="">
                    </td>
                </tr>


                <tr>
                    <td colspan="4"><div class="g_line"><i>или</i></div></td>
                </tr>



            <tr class="paymaster-section">
                <td>
                    <input type="radio" name="1" class="rb1" data-pay="paymaster" style="background: #a2b2bb;">
                </td>
                <td colspan="2" class="s3_text" style="padding-left: 20px;">
                    Оплата пластиковыми картами и эл. деньгами <br>через PayMaster<br><br>
                    <p style="font-size:11px; text-transform: ">Всвязи с временным ограничением платежной системы PayMaster,<br> максимально возможная сумма платежа может составлять от 15000-35000. <br>Подробнее <a href="/answers/view/91">тут</a>. В случае, если ваш платеж превышает лимит, пожалуйста, воспользуйтесь переводом на рассчетный счет (ниже).<br> Спасибо за понимание!</p>
                </td>
                <td>
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
                    <?php echo $this->html->script(array('jquery-1.7.1.min.js'));?>
                    <script type='text/javascript' src='https://paymaster.ru/widget/BasicFP/1?LMI_MERCHANT_ID=d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60&LMI_PAYMENT_AMOUNT=1&LMI_PAYMENT_DESC=<?php echo urlencode('Оплата питча')?>&LMI_CURRENCY=RUB'></script>
                </td>
            </tr>



            <tr class="paymaster-section">
                <td colspan="4"><div class="g_line"><i>или</i></div></td>
            </tr>
            <?php endif;?>
            <!--tr>
                <td>
                    <input type="radio" name="1" class="rb1" data-pay="online">
                </td>
                <td colspan="2" class="s3_text" style="padding-left: 20px;">
                    Оплата пластиковыми картами <br>через Мастер-Банк
                </td>
                <td>
                    <form action="https://pay.masterbank.ru/acquiring" method="post">
                        <input type="HIDDEN" value="" name="ORDER" id="order-id">
                        <input type="HIDDEN" value="" name="AMOUNT" id="order-total">
                        <input type="HIDDEN" value="" name="TIMESTAMP" id="order-timestamp">
                        <input type="HIDDEN" value="" NAME="SIGN" id="order-sign">
                        <input type="HIDDEN" value="http://godesigner.ru/users/mypitches" name="MERCH_URL">
                        <input type="HIDDEN" value="71846655" name="TERMINAL">
                        <input type="submit" id="paybutton-online" value="продолжить оплату" class="button" style="background: #a2b2bb;">
                    </form>
                </td>
            </tr>
            <tr id="online-images">
                <td colspan="4" style="padding: 20px 0 0 40px;">
                    <img src="/img/s3_master.png" alt="">
                </td>
            </tr>
            <tr>
                <td colspan="4"><div class="g_line"><i>или</i></div></td>
            </tr-->
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
        </table>
        <div id="s3_kv">
            <label><input type="radio" name="radio-face" class="rb-face" data-pay="offline-fiz"> ФИЗИЧЕСКОЕ ЛИЦО</label>
            <label><input type="radio" name="radio-face" class="rb-face" data-pay="offline-yur"> ЮРИДИЧЕСКОЕ ЛИЦО</label>
            <div class="pay-fiz">
                <p>Заполните поля, скачайте счёт на оплату и оплатите его. С помощью него вы можете сделать безналичный перевод через банк.</p>
                <form action="/bills/save" method="post" id="bill-fiz">
                    <input type="hidden" name="fiz-id" id="fiz-id" value="<?=$pitch->id?>">
                    <input type="hidden" name="fiz-individual" id="fiz-individual" value="1">
                    <input type="text" name="fiz-name" id="fiz-name" placeholder="Иванов Иван Иванович" data-placeholder="Иванов Иван Иванович" required="" data-content="symbolic">
                    <img src="/img/arrow-bill-download.png" class="arrow-bill-download" />
                    <input type="submit" id="button-fiz" value="Скачать счёт" class="button third" style="width:420px;">
                    <div class="clr"></div>
                </form>
                <p>Мы активируем ваш питч на сайте в течение рабочего дня после поступления денег, и тогда он появится в <a href="/pitches">общем списке</a>.
                   Пока вы можете просмотреть ваш питч в <a href="/users/mypitches">личном кабинете</a>.</p>
            </div>
            <div class="pay-yur">
                <p>Заполните поля, скачайте счёт на оплату и оплатите его. С помощью него вы можете сделать безналичный перевод через банк.</p>
                <form action="/bills/save" method="post" id="bill-yur">
                    <input type="hidden" name="yur-id" id="yur-id" value="<?=$pitch->id?>">
                    <input type="hidden" name="yur-individual" id="yur-individual" value="0">

                    <label class="required">Наименование организации</label>
                    <input type="text" name="yur-name" id="yur-name" placeholder="OOO «КРАУД МЕДИА»" data-placeholder="OOO «КРАУД МЕДИА»" required="" data-content="mixed">

                    <label class="required">ИНН</label>
                    <input type="text" name="yur-inn" id="yur-inn" placeholder="123456789012" data-placeholder="123456789012" required="" data-content="numeric" data-length="[10,12]">

                    <label class="required">КПП</label>
                    <input type="text" name="yur-kpp" id="yur-kpp" placeholder="123456789" data-placeholder="123456789" required="" data-content="numeric" data-length="[9]">

                    <label class="required">Юридический адрес</label>
                    <input type="text" name="yur-address" id="yur-address" placeholder="199397, Санкт-Петербург, ул. Беринга, д. 27" data-placeholder="199397, Санкт-Петербург, ул. Беринга, д. 27" required="" data-content="mixed">

                    <p>Мы активируем ваш питч на сайте в течение рабочего дня после поступления денег, и тогда он появится в <a href="/pitches">общем списке</a>.
                    Пока вы можете просмотреть ваш питч в <a href="/users/mypitches">личном кабинете</a>.</p>
                    <p>Закрывающие документы вы получите на e-mail сразу после того, как завершите питч. Распечатайте их, подпишите и поставьте печать.
                    Отправьте их нам в двух экземплярах по почте (199397, Россия, Санкт-Петербург, ул. Беринга, д. 27).
                    В ответном письме вы получите оригиналы документов с нашей печатью.</p>
                    <input type="submit" id="button-yur" value="Скачать счёт" class="button third" style="width:420px;">
                    <div class="clr"></div>
                </form>
            </div>
        </div>
    </div>
    <div class="g_line"></div>
    <input type="button" id="backbutton" value="Вернуться к шагу 2" class="button steps-link" data-step="2" style="width:260px;float:left;">
    <a href="/pitches" class="button" style="width:192px;float:right;">На страницу всех питчей</a>
</div>