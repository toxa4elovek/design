<div class="wrapper">

    <?=$this->view()->render(['element' => 'header'], ['header' => 'header2'])?>

    <input type="hidden" value="<?=$pitch->id?>" id="pitch_id"/>
    <input type="hidden" value="" id="addon_id"/>
    <input type="hidden" value="<?=$pitch->billed?>" id="billed"/>
    <input type="hidden" value="<?=$pitch->published?>" id="published"/>

    <!--aside class="summary-price expanded">
        <h3>Итого:</h3>
        <p class="summary"><strong id="total-tag">0.-</strong></p><!-- .summary -->
        <!--ul id="check-tag">
        </ul>
        <a href="#" class="show" id="show-check"><span>Подробнее</span></a>
        <a href="#" class="hide" id="hide-check"><span>Скрыть</span></a-->
    </aside--><!-- .summary-price -->

    <div class="middle add-pitch" style="display:block;" id="step3">

        <div class="main">
            <div style="height:800px"><p>

                <div class="g_line"></div>
                <div id="P_card">
                    <table>
                        <tr class="paymaster-section">
                            <td>
                                <!--input type="radio" name="1" class="rb1" data-pay="paymaster" style="background: #a2b2bb;"-->
                            </td>
                            <td colspan="2" class="s3_text" style="padding-left: 20px;">
                                Оплата пластиковыми картами и эл. деньгами <br>через PayMaster<br><br>
                                <p style="font-size:12px; text-transform: none;" class="regular">Всвязи с временным ограничением платежной системы PayMaster,<br> максимально возможная сумма платежа может составлять от 15000-30000 р. <br>Подробнее <a href="/answers/view/91" target="_blank">тут</a>. В случае, если ваш платеж превышает лимит, пожалуйста, воспользуйтесь переводом на рассчетный счет (ниже). Спасибо за понимание!</p>
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
                        <tr id="paymaster-select" class="paymaster-section" style="">
                            <td colspan="4">
                                <?php echo $this->html->script(['jquery-1.8.3.min.js']);?>
                                <script type='text/javascript' src='https://paymaster.ru/widget/BasicFP/1?LMI_MERCHANT_ID=d5d2e177-6ed1-4e5f-aac6-dd7ea1c16f60&LMI_PAYMENT_AMOUNT=10120&LMI_PAYMENT_NO=200026&LMI_PAYMENT_DESC=<?php echo urlencode('Оплата увеличения награды на 9000 для проекта 101534')?>&LMI_CURRENCY=RUB'></script>
                            </td>
                        </tr>
                        </table>
                    </div>
                </div>
                <div class="g_line"></div>
                <p class="submit">
                    <input type="submit" value="Назад" id="prev" class="button steps-link" data-step="2">
                </p><!-- .submit -->

            </div>
            </form>
        </div><!-- .main -->

    </div><!-- .middle -->

    <?=$this->html->script(['jquery-ui-1.11.4.min.js', 'pitches/addon.js?' . mt_rand(100, 999), 'jquery.numeric', 'jquery.iframe-transport.js', 'jquery.fileupload.js', 'jquery.simplemodal-1.4.2.js', 'jquery.tooltip.js', 'popup.js', 'jquery.damnUploader.js'], ['inline' => false])?>
<?= $this->html->style([], ['inline' => false])?>