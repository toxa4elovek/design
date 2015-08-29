<div class="wrapper">
    <?= $this->view()->render(array('element' => 'header'), array('header' => 'header2')) ?>
    <input type="hidden" value="<?= $pitch->id ?>" id="pitch_id"/>
    <input type="hidden" value="" id="addon_id"/>
    <input type="hidden" value="<?= $pitch->billed ?>" id="billed"/>
    <input type="hidden" value="<?= $pitch->published ?>" id="published"/>
    <?php
    $sum = 0;
    foreach ($receipt as $rec) {
        $sum += $rec->value;
    }
    ?>
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
            <p id="top-pay">Мы свяжемся с вами для интервью, на основе которого сами создадим тех. задание для дизайнеров.</p><br />
            <div>
                <h1>выберите способ оплаты</h1>
                <div class="g_line"></div>
                <div id="P_card">
                    <table>
                        <!--tr>
                            <td>
                                <input type="radio" name="1" class="rb1" data-pay="payanyway">
                            </td>
                            <td colspan="2" class="s3_text" style="padding-left: 20px;">
                                Оплата пластиковыми картами <br>через Payanyway<br><br>
                            </td>
                            <td>
                                <form id="payanyway_form" method="post" action="https://www.moneta.ru/assistant.htm">
                                    <input type="hidden" name="MNT_ID" value="36102238">
                                    <input type="hidden" name="MNT_TRANSACTION_ID" value="<?= $pitch->id ?>">
                                    <input type="hidden" name="MNT_CURRENCY_CODE" value="RUB">
                                    <input type="hidden" name="MNT_AMOUNT" value="<?= $sum ?>">
                                    <input type="hidden" name="MNT_TEST_MODE" value="0">
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
                        </tr-->
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
                        <tr class="paymaster-section">
                            <td>
                                <input type="radio" name="1" class="rb1" data-pay="paymaster" style="background: #a2b2bb;">
                            </td>
                            <td colspan="1" class="s3_text" style="padding-left: 20px;">
                                Оплата пластиковыми картами и эл. деньгами <!--br-->через PayMaster<br><br>
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
                                <?php echo $this->html->script(array('jquery-1.8.3.min.js')); ?>
                                <script type='text/javascript' src='https://paymaster.ru/widget/BasicFP/1?LMI_MERCHANT_ID=d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60&LMI_PAYMENT_AMOUNT=<?= $sum ?>&LMI_PAYMENT_DESC=<?php echo urlencode('Оплата проекта') ?>&LMI_CURRENCY=RUB&LMI_PAYMENT_NO=<?= $pitch->id ?>'></script>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="g_line"></div>
                <p class="submit">
                    <a href="/pages/fastpitch"  class="button steps-link">Назад</a>
                </p><!-- .submit -->
            </div>
        </div><!-- .main -->
    </div><!-- .middle -->
    <?= $this->html->script(array('jquery-ui-1.8.17.custom.min.js', 'pitches/newwinner.js?' . mt_rand(100, 999), 'jquery.numeric', 'jquery.iframe-transport.js', 'jquery.fileupload.js', 'jquery.simplemodal-1.4.2.js', 'jquery.tooltip.js', 'popup.js', 'jquery.damnUploader.js'), array('inline' => false)) ?>
    <?= $this->html->style(array('/brief', '/step3'), array('inline' => false))?>