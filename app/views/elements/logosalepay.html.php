<div id="step3">
    <div class="g_line first"></div>
    <h1>выберите способ оплаты</h1>
    <div id="P_card">
        <table>
            <tr class="payture-section">
                <td>
                    <input type="radio" name="1" class="rb1" data-pay="payture" style="background: #a2b2bb;">
                </td>
                <td colspan="2" class="s3_text" data-radio="payture" style="padding-left: 20px;">
                    Оплата дебетовыми или кредитными картами<br><br>
                    <!--p style="font-size:11px; text-transform: ">Всвязи с временным ограничением платежной системы PayMaster,<br> максимально возможная сумма платежа может составлять от 15000-35000. <br>Подробнее <a href="/answers/view/91">тут</a>. В случае, если ваш платеж превышает лимит, пожалуйста, воспользуйтесь переводом на рассчетный счет (ниже).<br> Спасибо за понимание!</p-->
                </td>
                <td style="width: 190px;">
                    <a href="/payments/startpayment/<?= isset($data['pitch_id']) ? $data['pitch_id'] : 0 ?>" id="paybutton-payture" class="button" style="display: none;">Оплатить</a>
                </td>
            </tr>
            <tr id="online-images">
                <td colspan="4" style="padding: 20px 0 0 40px;" class="s3_h" >
                    <img src="/img/s3_master.png" alt="" data-radio="payture">
                </td>
            </tr>

            <tr>
                <td colspan="4"><div class="g_line"><i>или</i></div></td>
            </tr>
            <!--tr>
                <td style="width: 36px;">
                    <input type="radio" name="1" class="rb1" data-pay="payanyway">
                </td>
                <td colspan="2" class="s3_text"  data-radio="payanyway" style="padding-left: 20px;">
                    Оплата пластиковыми картами <br>через Payanyway<br><br>
                </td>
                <td style="width: 228px;">
                    <?php if($this->user->isLoggedIn()):?>
                        <form id="payanyway_form" method="post" action="https://www.moneta.ru/assistant.htm">
                            <input type="hidden" name="MNT_ID" value="36102238">
                            <input type="hidden" name="MNT_TRANSACTION_ID" value="<?= isset($data['pitch_id']) ? $data['pitch_id'] : 0 ?>">
                            <input type="hidden" name="MNT_CURRENCY_CODE" value="RUB">
                            <input type="hidden" name="MNT_AMOUNT" value="<?= $data['total'] ?>">
                            <input type="hidden" name="MNT_TEST_MODE" value="0">
                            <input type="hidden" name="paymentSystem.unitId" value="499669">
                            <input type="hidden" name="followup" value="true">
                            <input type="submit" id="paybutton-payanyway" style="display: block;" value="продолжить оплату" class="button">
                        </form>
                    <?php else:?>
                        <a href="/login" class="button" style="width: 145px">продолжить оплату</a>
                    <?php endif?>
                </td>
            </tr>
            <tr id="online-images">
                            <td colspan="4" style="padding: 20px 0 0 40px;" class="s3_h" data-radio="payanyway">
                                <img src="/img/s3_master.png" data-radio="payanyway" alt="">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"><div class="g_line"><i>или</i></div></td>
                        </tr-->
                        <tr>
                            <td>
                                <input type="radio" name="1" class="rb1" data-pay="offline">
                            </td>
                            <td class="s3_h" data-radio="offline">
                                <img src="/img/s3_rsh.png" data-radio="offline" alt="">
                            </td>
                            <td class="s3_text" data-radio="offline">
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
                                            <input type="hidden" name="fiz-id" id="fiz-id" value="<?= isset($data['pitch_id']) ? $data['pitch_id'] : 0 ?>">
                                            <input type="hidden" name="fiz-individual" id="fiz-individual" value="1">
                                            <input type="text" name="fiz-name" id="fiz-name" placeholder="Иванов Иван Иванович" data-placeholder="Иванов Иван Иванович" required="" data-content="symbolic">
                                            <img src="/img/arrow-bill-download.png" class="arrow-bill-download" />
                                            <input type="submit" id="button-fiz" value="Скачать счёт" class="reqbutton third" style="width:420px;">
                                            <div class="clr"></div>
                                        </form>
                                        <p>Мы активируем ваш питч на сайте в течение рабочего дня после поступления денег, и тогда он появится в <a href="/pitches">общем списке</a>.
                                            Пока вы можете просмотреть ваш питч в <a href="/users/mypitches">личном кабинете</a>.</p>
                                    </div>
                                    <div class="pay-yur">
                                        <p>Заполните поля, скачайте счёт на оплату и оплатите его. С помощью него вы можете сделать безналичный перевод через банк.</p>
                                        <form action="/bills/save" method="post" id="bill-yur">
                                            <input type="hidden" name="yur-id" id="yur-id" value="<?= isset($data['pitch_id']) ? $data['pitch_id'] : 0 ?>">
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
                                            <input type="submit" id="button-yur" value="Скачать счёт" class="reqbutton third" style="width:420px;">
                                            <div class="clr"></div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!--tr>
                            <td colspan="4"><div class="g_line"><i>или</i></div></td>
                        </tr>
                        <tr class="paymaster-section">
                            <td>
                                <input type="radio" name="1" class="rb1" data-pay="paymaster" style="background: #a2b2bb;">
                            </td>
                            <td colspan="3" class="s3_text" style="padding-left: 20px;">
                                Оплата пластиковыми картами и эл. деньгами <br>через PayMaster<br><br>
                                <p style="font-size:11px; text-transform: ">Всвязи с временным ограничением платежной системы PayMaster,<br> максимально возможная сумма платежа может составлять от 15000-35000. <br>Подробнее <a href="/answers/view/91">тут</a>. В случае, если ваш платеж превышает лимит, пожалуйста,<br> воспользуйтесь переводом на рассчетный счет (ниже).<br> Спасибо за понимание!</p>
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
                                <?php echo $this->html->script(array('jquery-1.8.3.min.js')); ?>
                                <script type='text/javascript' src='https://paymaster.ru/widget/BasicFP/1?LMI_MERCHANT_ID=d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60&LMI_PAYMENT_AMOUNT=<?= $data['total'] ?>&LMI_PAYMENT_DESC=<?php echo urlencode('Оплата проекта') ?>&LMI_CURRENCY=RUB&LMI_PAYMENT_NO=<?= isset($data['pitch_id']) ? $data['pitch_id'] : 0 ?>'></script>
                            </td>
                        </tr-->
        </table>
    </div>
    <!--div class="g_line"></div-->
</div>