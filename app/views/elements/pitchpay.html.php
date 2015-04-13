<div style="padding-bottom: 50px;">
    <h1>выберите способ оплаты</h1>
    <!--h1 class="greyboldheader" style="text-transform:none; line-height: 20px; font: bold 17px/24px 'RodeoC',sans-serif">Друзья, в связи со внезапным <a href="http://lenta.ru/news/2013/11/20/master/" style="" target="_blank">приостановлением работы</a> «Мастер-банка», оплата питчей пока стала возможна только <br/>с помощью переводов на альтернативный рассчетный счет. Благодарим за понимание.</h1-->
    <!--p>В связи с временным ограничением платежной системы Paymaster, максимально возможная сумма платежа составляет <b>15000</b> рублей.</p-->
    <!-- <?php echo var_dump($pitch->category_id);  ?> -->
    <div class="g_line"></div>
    <div id="P_card">
        <table>
            <?php if ($pitch->category_id != 10):?>

                <tr class="paymaster-section">
                    <td>
                        <input type="radio" name="1" class="rb1" data-pay="paymaster" style="background: #a2b2bb;">
                    </td>
                    <td colspan="3" class="s3_text" style="padding-left: 20px;">
                        Оплата пластиковыми картами и эл. деньгами <br>через PayMaster<br><br>
                        <!--p style="font-size:11px; text-transform: ">Всвязи с временным ограничением платежной системы PayMaster,<br> максимально возможная сумма платежа может составлять от 15000-35000. <br>Подробнее <a href="/answers/view/91">тут</a>. В случае, если ваш платеж превышает лимит, пожалуйста, воспользуйтесь переводом на рассчетный счет (ниже).<br> Спасибо за понимание!</p-->
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
            <?php endif;?>

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

                                <label>КПП</label>
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
                </td>
            </tr>

            <!--tr>
                <td colspan="4"><div class="g_line"><i>или</i></div></td>
            </tr-->

            <?php if ($pitch->category_id == 1000):?>
                <tr>
                    <td style="width: 25px;">
                        <input type="radio" name="1" class="rb1" data-pay="payanyway">
                    </td>
                    <td colspan="2" class="s3_text" style="padding-left: 20px;">
                        Оплата пластиковыми картами <br>через Payanyway<br><br>
                    </td>
                    <td style="width: 190px;">
                        <form id="payanyway_form" method="post" action="https://www.moneta.ru/assistant.htm">
                            <input type="hidden" name="MNT_ID" value="36102238">
                            <input type="hidden" name="MNT_TRANSACTION_ID" value="">
                            <input type="hidden" name="MNT_CURRENCY_CODE" value="RUB">
                            <input type="hidden" name="MNT_AMOUNT" value="">
                            <input type="hidden" name="MNT_TEST_MODE" value="0">
                            <input type="hidden" name="paymentSystem.unitId" value="499669">
                            <input type="hidden" name="followup" value="true">
                            <input type="submit" id="paybutton-payanyway" value="продолжить оплату" class="button" style="display: none;">
                        </form>
                    </td>
                </tr>
                <tr id="online-images">
                    <td colspan="4" style="padding: 20px 0 0 40px;">
                        <img src="/img/s3_master.png" alt="">
                    </td>
                </tr>


            <?php endif;?>


        </table>
    </div>
    <div class="g_line"></div>
    <input type="button" id="backbutton" style="width: 175px; margin-right: 12px;" value="<< Вернуться" class="button steps-link" data-step="2">
    <input type="button" style="width: 175px; margin-right: 12px; padding-left: 0; padding-right: 0" value="Сохранить черновик" class="savedraft button">
    <a href="/pitches" class="button" style="width: 167px; padding-left: 0; padding-right: 0; margin-right: 0;">Все питчи >></a>
</div>