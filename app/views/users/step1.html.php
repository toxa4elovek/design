<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('header' => 'header2'))?>

    <?php
    if(unserialize($solution->user->paymentOptions)) {
        $paydata = unserialize($solution->user->paymentOptions);
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

    <div class="conteiner" style="width:958px !important">
        <section>

            <div class="menu">
                <?=$this->view()->render(array('element' => 'step-menu'))?>
            </div>

        </section>
        <div class="block-toggler" style="margin-left: 65px;">
            <a href="/users/solutions">все решения</a> /
            <?php if($solution->step == 4):?>
            <a href="/users/awarded" class="link">награжденные</a> /
            <?php else:?>
            <a href="/users/awarded">награжденные</a> /
            <?php endif?>
            <?php if($solution->step < 4):?>
            <a href="/users/nominated" class="link">в процессе завершения</a>
            <?php else:?>
            <a href="/users/nominated">в процессе завершения</a>
            <?php endif?>
        </div>
        <section style="min-height: 550px;">

            <?=$this->view()->render(array('element' => '/complete-process/stepmenu-designer'), array('solution' => $solution, 'step' => $step, 'type' => $type))?>

            <form id="worker-payment-data">
            <div class="center_block" style="margin:35px 0 0 63px !important">
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
                                    <tr style="height: 80px;"><td class="" colspan="2"><input type="text" value="<?=$paydata['fio']?>" name="fio" /></td></tr>
                                    <tr><td class="tableheader" colspan="2">Телефон для связи</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="2"><input type="text" value="<?=$paydata['phone']?>" name="phone" /></td></tr>
                                    <tr>
                                        <td width="304" class="tableheader" style="padding-right:10px">Счет</td>
                                        <td width="304" class="tableheader" style="padding-left:10px">Ваш личный ИНН <a href="#" style="display: block; width: 10px; height: 10px; margin-left: 110px; margin-top: -25px; font: 12px Helvetica, sans-serif; color: #658fa5" class="second tooltip" title="Запрашивается банком как обязательное, для формирования платежного поручения">(?)</a></td></tr>
                                    <tr style="height: 80px;">
                                        <td class="" style="padding-right:10px"><input style="width:262px;" type="text" value="<?=$paydata['accountnum']?>" name="accountnum" /></td>
                                        <td class="" style="padding-left:10px"><input style="width:262px;" type="text" value="<?=$paydata['inn']?>" name="inn" /></td>
                                    </tr>
                                    <tr><td colspan="2"  height="60"><h1 style="font:bold 18px/1 'RodeoC',sans-serif;text-transform: uppercase;color:#c6c6c6; text-shadow:-1px 0 0 #FFFFFF">Банк получателя</h1></td></tr>
                                    <tr><td class="tableheader" colspan="2">Наименование</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="2"><input type="text" value="<?=$paydata['bankname']?>" name="bankname" /></td></tr>
                                    <tr>
                                        <td width="304" class="tableheader" style="padding-right:10px">Бик</td>
                                        <td width="304" class="tableheader" style="padding-left:10px">Корсчет</td></tr>
                                    <tr style="height: 80px;">
                                        <td class="" style="padding-right:10px"><input style="width:262px;" type="text" value="<?=$paydata['bik']?>" name="bik" /></td>
                                        <td class="" style="padding-left:10px"><input style="width:262px;" type="text" value="<?=$paydata['coraccount']?>" name="coraccount" /></td>
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
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['wmr-account']?>" name="wmr-account" /></td></tr>
                                    <tr><td class="tableheader" colspan="3">ФИО</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['wmr-fio']?>" name="wmr-fio" /></td></tr>
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
                    <span class="regular">Вы можете вернуться к этому этапу позже!</span>
                </div>
                <div class="proceed">
                    <?php if(($this->session->read('user.isAdmin') == 1) || ($solution->pitch->category_id == 7 && $solution->step != 4)):?>

                    <?php else:?>
                    <?=$this->html->link('<img src="/img/proceed.png" /><br />
                            <span>Продолжить</span>', array('controller' => 'users', 'action' => 'step2', 'id' => $solution->id), array('escape' => false, 'id' => 'step2-link-saveform'))?>
                    <?php endif;?>
                </div>
                <div class="clr"></div>

            </div>
            </form>


            <?=$this->view()->render(array('element' => '/complete-process/rightblock'), array('solution' => $solution, 'type' => $type))?>
            <div class="clr"></div>

        </section>
    </div>
    <div class="conteiner-bottom"></div>
</div><!-- .wrapper -->
<?=$this->html->style(array('/view', '/messages12', '/pitches12', '/pitches2', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css',), array('inline' => false))?>
<?=$this->html->script(array('jquery.tooltip.js', 'users/step1.js'), array('inline' => false))?>