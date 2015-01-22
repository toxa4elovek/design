<!-- start: Solution overlay -->
<div class="solution-overlay-dummy solution-sale" style="display: none;">
    <!-- start: Solution Container -->
    <div class="solution-container">
        <div class="solution-nav-wrapper">
            <div class="solution-prev"></div>
            <a class="solution-prev-area" href="#"></a>
            <div class="solution-next"></div>
            <a class="solution-next-area" href="#"></a>
        </div>
        <!-- start: Solution Right Panel -->
        <div class="solution-right-panel">
            <div class="solution-popup-close"></div>
            <div class="solution-info solution-summary">
                <div class="solution-number">#<span class="number isField"><!--  --></span></div>
                <div class="solution-rating"><div class="rating-image star0"></div> рейтинг заказчика</div>
            </div>
            <div class="separator"></div>
            <div class="solution-info solution-author chapter">
                <h2>АВТОР</h2>
                <img class="author-avatar" src="/img/default_small_avatar.png" alt="Портрет автора" />
                <a class="author-name isField" href="#"><!--  --></a>
                <div class="author-from isField"><!--  --></div>
                <div class="clr"></div>
            </div>
            <div class="separator"></div>
            <div class="solution-info solution-about chapter">
                <h2>О РЕШЕНИИ</h2>
                <span id="date" style="color:#878787;">Опубликовано </span><br/>
                <span class="solution-description isField"><!--  --></span><a class="description-more">… Подробнее</a>
            </div>
            <div class="separator"></div>
            <div class="solution-info solution-abuse isField"><!--  --></div>
            <?php if (isset($data['receipt'])) : ?>
                <div>
                    <aside class="summary-price expanded">
                        <h3>Итого:</h3>
                        <p class="summary"><strong id="total-tag"><?= $data['total'] ?>р.-</strong></p><!-- .summary -->
                        <ul id="check-tag">
                        </ul>
                        <div class="hide">
                            <span id="to-pay">Перейти к оплате</span>
                        </div>
                    </aside><!-- .summary-price -->
                    <!-- end: Solution Left Panel -->
                </div>
            <?php endif; ?>
            <div class="clr"></div>
            <!-- end: Solution Right Panel -->
        </div>
        <!-- start: Solution Left Panel -->
        <div class="solution-left-panel">
            <a class="solution-title" href="#">
                <h1>

                </h1>
            </a>
            <!-- start: Soluton Images -->
            <section class="solution-images isField">
                <div style="text-align:center;height:220px;padding-top:180px"><img alt="" src="/img/blog-ajax-loader.gif"></div>
                <!-- end: Solution Images -->
            </section>
            <section class="allow-comments">
                <div class="all_messages">
                    <div class="clr"></div>
                </div>
                <div class="separator full"></div>

            </section>
            <!-- start: Comments -->
            <section class="solution-comments isField">

                <!-- end: Comments -->
            </section>
            <!-- end: Solution Container -->
        </div>
        <?php if (isset($data['receipt'])) : ?>
            <div id="step3">
                <div class="g_line first"></div>
                <h1>выберите способ оплаты</h1>
                <div id="P_card">
                    <table>
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
                                    <input type="hidden" name="MNT_TRANSACTION_ID" value="<?= isset($data['pitch_id']) ? $data['pitch_id'] : 0 ?>">
                                    <input type="hidden" name="MNT_CURRENCY_CODE" value="RUB">
                                    <input type="hidden" name="MNT_AMOUNT" value="<?= $data['total'] ?>">
                                    <input type="hidden" name="MNT_TEST_MODE" value="0">
                                    <input type="hidden" name="paymentSystem.unitId" value="499669">
                                    <input type="hidden" name="followup" value="true">
                                    <input type="submit" id="paybutton-payanyway" value="продолжить оплату" class="button">
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
                                            <input type="hidden" name="fiz-id" id="fiz-id" value="<?= isset($data['pitch_id']) ? $data['pitch_id'] : 0 ?>">
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
                                <?php echo $this->html->script(array('jquery-1.7.1.min.js')); ?>
                                <script type='text/javascript' src='https://paymaster.ru/widget/BasicFP/1?LMI_MERCHANT_ID=d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60&LMI_PAYMENT_AMOUNT=<?= $data['total'] ?>&LMI_PAYMENT_DESC=<?php echo urlencode('Оплата питча') ?>&LMI_CURRENCY=RUB&LMI_PAYMENT_NO=<?= isset($data['pitch_id']) ? $data['pitch_id'] : 0 ?>'></script>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="g_line"></div>
            </div>
        <?php endif; ?>
    </div><!-- .main -->  
    <!-- end: Solution overlay -->
</div>
