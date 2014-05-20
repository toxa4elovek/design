<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('header' => 'header2'))?>

    <?php
    if(unserialize($user->paymentOptions)) {
        $paydata = unserialize($user->paymentOptions);
        $paydata = $paydata[0];
    }else{
        $paydata = array(
            'cashintype' => 'none',
            'phone' => '',
            'fio' => '',
            'accountnum' => '',
            'inn' => '',
            'bankname' => '',
            'bik' => '',
            'coraccount' => '',
            'wmr-phone' => '',
            'wmr-account' => '',
            'wmr-fio' => '',
            'passseries' => '',
            'passnum' => '',
            'issuedby' => ''
        );
    }
    ?>
    <div class="middle">
    <div class="conteiner" style="padding: 0 0 35px !important;">
        <nav class="main_nav clear" style="margin-top:65px;margin-left:62px;width:832px;">
            <?=$this->view()->render(array('element' => 'office/nav'));?>
        </nav>
        <section style="min-height: 550px;">
            <form id="worker-payment-data" action="/users/details" method="post">
                <div class="center_block" style="margin:0 0 0 63px !important; padding-top:15px;">
                    <div style="text-align: center; margin-top: 10px;">
                        <h1 style="font:bold 28px/1 'RodeoC',sans-serif;text-transform: uppercase;color:#c6c6c6; text-shadow:-1px 0 0 #FFFFFF;">способ получения денег</h1>
                    </div>
                    <div class="g_line"></div>
                    <div id="P_card" style="margin: 20px 0;">
                        <table style="width: 608px;">
                            <tbody><tr>
                                <td>
                                    <input type="radio" data-pay="cards" class="rb1" name="cashintype" <?php if($paydata['cashintype'] == 'card') echo 'checked' ?> value="card" style="width:14px;height:14px;margin-top:15;">
                                </td>
                                <td>
                                    <img alt="" src="/img/visa_mastercard.png">
                                </td>
                                <td class="s3_text">
                                    Получить вознагреждение <br>на банковскую карту VISA, MASTERCARD
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr  id="cards" <?php if($paydata['cashintype'] != 'card'):?> style="display:none;" <?php endif;?>>
                                <td colspan="4">
                                    <table id="step1table">
                                        <tr><td class="tableheader" colspan="2">ФИО</td></tr>
                                        <tr style="height: 80px;"><td class="" colspan="2"><input type="text" value="<?=$paydata['fio']?>" name="fio" data-validate="fio" /></td></tr>
                                        <tr><td class="tableheader" colspan="2">Телефон для связи</td></tr>
                                        <tr style="height: 80px;"><td class="" colspan="2"><input type="text" value="<?=$paydata['phone']?>" name="phone" /></td></tr>
                                        <tr>
                                            <td width="304" class="tableheader" style="padding-right:10px">Номер счета получателя</td>
                                            <td width="304" class="tableheader" style="padding-left:10px">Ваш личный ИНН <a href="#" class="tooltip_plugin" style="display: inline-block; margin-top: -1px; font: 12px Helvetica, sans-serif; color: #658fa5" title="12 цифр без пробелов">(?)</a></td></tr>
                                        <tr style="height: 80px;">
                                            <td class="" style="padding-right:10px"><input style="width:262px;" type="text" value="<?=$paydata['accountnum']?>" name="accountnum" data-validate="numeric" /></td>
                                            <td class="" style="padding-left:10px"><input style="width:262px;" type="text" value="<?=$paydata['inn']?>" name="inn" /></td>
                                        </tr>
                                        <tr><td colspan="2"  height="60"><h1 style="font:bold 18px/1 'RodeoC',sans-serif;text-transform: uppercase;color:#c6c6c6; text-shadow:-1px 0 0 #FFFFFF">Банк получателя</h1></td></tr>
                                        <tr><td class="tableheader" colspan="2">Наименование</td></tr>
                                        <tr style="height: 80px;"><td class="" colspan="2"><input type="text" value="<?=$paydata['bankname']?>" name="bankname" /></td></tr>
                                        <tr>
                                            <td colspan="2" class="tableheader" style="padding-right:10px">Бик</td>
                                            <!--td width="304" class="tableheader" style="padding-left:10px">Корсчет</td--></tr>
                                        <tr style="height: 80px;">
                                            <td class="" style="padding-right:10px"><input style="width:262px;" type="text" value="<?=$paydata['bik']?>" name="bik" /></td>
                                            <!--td class="" style="padding-left:10px"><input style="width:262px;" type="text" value="<?=$paydata['coraccount']?>" name="coraccount" /></td-->
                                        </tr>
                                        <tr><td class="tableheader" colspan="2">Примечание</td></tr>
                                        <tr style="height: 80px;"><td class="" colspan="2"><input type="text" value="<?php if(isset($paydata['extradata'])) echo $paydata['extradata']?>" name="extradata" /></td></tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="4"><div class="g_line"></div></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" data-pay="wmr" class="rb1" name="cashintype" <?php if($paydata['cashintype'] == 'wmr') echo 'checked' ?> value="wmr" style="width:14px;height:14px;margin-top:15;">
                                </td>
                                <td class="s3_h">
                                    <img alt="" src="/img/wmr.png">
                                </td>
                                <td class="s3_text" style="margin-top: 14px;">
                                    Получить вознагреждение в wmr (webmoney.ru)
                                </td>
                                <td></td>
                            </tr>
                            <tr id="wmr" <?php if($paydata['cashintype'] != 'wmr'):?> style="display:none;"<?php endif;?> >
                                <td colspan="4">
                                    <table id="step2table">
                                        <tr><td class="tableheader" colspan="3">Кошелек</td></tr>
                                        <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['wmr-account']?>" name="wmr-account" data-validate="wmr" /></td></tr>
                                        <tr><td class="tableheader" colspan="3">ФИО</td></tr>
                                        <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['wmr-fio']?>" name="wmr-fio" data-validate="fio" /></td></tr>
                                        <tr><td class="tableheader" colspan="3">Телефон для связи</td></tr>
                                        <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['wmr-phone']?>" name="wmr-phone" /></td></tr>
                                        <tr>
                                            <td colspan="2" width="304" class="tableheader" style="padding-right:10px">Номер и серия паспорта</td>
                                            <td width="304" class="tableheader" style="padding-left:10px">Кем и когда выдан</td></tr>
                                        <tr style="height: 80px;">
                                            <td class="" style="padding-right:10px;width: 60px" width="60"><input style="width:60px;" type="text" value="<?=$paydata['passseries']?>" name="passseries" /></td>
                                            <td class="" style="padding-right:10px;width: 100px"><input style="width:100px;" type="text" value="<?=$paydata['passnum']?>" name="passnum" /></td>
                                            <td class="" style="padding-left:10px"><input style="width:272px" type="text" value="<?=$paydata['issuedby']?>" name="issuedby" /></td>
                                        </tr>
                                        <tr><td class="tableheader" colspan="3">Примечание</td></tr>
                                        <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?php if(isset($paydata['extradatawmr'])) echo $paydata['extradatawmr']?>" name="extradatawmr" /></td></tr>
                                    </table>
                                </td>
                            </tr>
                            </tbody></table>
                    </div>


                    <div class="step">
                        <input type="submit" style="width:210px;" id="save" class="button" value="Сохранить реквизиты" />
                    </div>
                    <div class="clr"></div>

                </div>
            </form>


            <?php //$this->stream->renderStream();?>
            <div class="clr"></div>

        </section>
    </div>

    <div class="conteiner-bottom"></div></div>
</div><!-- .wrapper -->
<?=$this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'jquery.tooltip.js', 'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array('/main2.css', '/pitches2.css', '/edit','/view', '/messages12', '/pitches12', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css',), array('inline' => false))?>