<div class="wrapper">

    <?=$this->view()->render(['element' => 'header'], ['header' => 'header2'])?>

    <?php
    if (unserialize($solution->user->paymentOptions)) {
        $paydata = unserialize($solution->user->paymentOptions);
        $paydata = $paydata[0];
    } else {
        $paydata = [
            'cashintype' => 'none',
            'phone' => '',
            'birthdate' => '',
            'fio' => '',
            'birthplace' => '',
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
            'issuedby' => '',
            'yandex-phone' => '',
            'yandex-account' => '',
            'yandex-fio' => '',
            'passseriesyandex' => '',
            'passnumyandex' => '',
            'issuedbyyandex' => ''
        ];
    }
    ?>

    <div class="conteiner">
        <section>

            <div class="menu">
                <?=$this->view()->render(['element' => 'step-menu'])?>
            </div>

        </section>
        <div style="margin-left: 50px;">
            <?=$this->view()->render(['element' => 'complete-process/filtersmenu'], ['link' => ($solution->step == 4) ? 2 : 3])?>
        </div>
        <section style="min-height: 550px;">

            <?=$this->view()->render(['element' => '/complete-process/stepmenu-designer'], ['solution' => $solution, 'step' => $step, 'type' => $type])?>

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
                                <input type="radio" data-pay="cards" class="rb1" name="cashintype" <?php if ($paydata['cashintype'] == 'card') {
    echo 'checked';
} ?> value="card" style="width:14px;height:14px;margin-top:15;">
                            </td>
                            <td>
                                <img alt="" src="/img/visa_mastercard.png">
                            </td>
                            <td class="s3_text">
                                Получить вознаграждение <br>на банковскую карту VISA, MASTERCARD
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr  id="cards" <?php if ($paydata['cashintype'] != 'card'):?> style="display:none;" <?php endif;?>>
                            <td colspan="4">
                                <table id="step1table">
                                    <tr><td class="tableheader" colspan="3">ФИО</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['fio']?>" name="fio" data-validate="fio" /></td></tr>
                                    <tr><td class="tableheader" colspan="3">Телефон для связи</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['phone']?>" name="phone" /></td></tr>
                                    <tr>
                                        <td colspan="2" width="304" class="tableheader" style="padding-right:10px">Номер и серия паспорта</td>
                                        <td width="304" class="tableheader" style="padding-left:10px">Кем и когда выдан</td>
                                    </tr>
                                    <tr style="height: 80px;">
                                        <td class="" style="padding-right:10px;width: 60px" width="60"><input style="width:60px;" type="text" value="<?=$paydata['passseries']?>" name="passseries" data-validate="notempty" /></td>
                                        <td class="" style="padding-right:10px;width: 100px"><input style="width:100px;" type="text" value="<?=$paydata['passnum']?>" name="passnum" data-validate="notempty" /></td>
                                        <td class="" style="padding-left:10px"><input style="width:262px" type="text" value="<?=$paydata['issuedby']?>" name="issuedby" data-validate="notempty" /></td>
                                    </tr>
                                    <tr><td class="tableheader" colspan="3">Дата рождения</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['birthdate']?>" name="birthdate" data-validate="notempty" /></td></tr>
                                    <tr><td class="tableheader" colspan="3">Место рождения</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['birthplace']?>" name="birthplace" data-validate="notempty"/></td></tr>
                                    <tr>
                                        <td width="304" class="tableheader" style="padding-right:10px" colspan="2">Номер счета получателя</td>
                                        <td width="304" class="tableheader" style="padding-left:10px">Ваш личный ИНН <a href="#" style="display: block; width: 10px; height: 10px; margin-left: 110px; margin-top: -25px; font: 12px Helvetica, sans-serif; color: #658fa5" class="second tooltip" title="12 цифр без пробелов">(?)</a></td></tr>
                                    <tr style="height: 80px;">
                                        <td class="" style="padding-right:10px" colspan="2"><input style="width:262px;" type="text" value="<?=$paydata['accountnum']?>" name="accountnum" data-validate="numeric" /></td>
                                        <td class="" style="padding-left:10px"><input style="width:262px;" type="text" value="<?=$paydata['inn']?>" name="inn" /></td>
                                    </tr>
                                    <tr><td colspan="3"  height="60"><h1 style="font:bold 18px/1 'RodeoC',sans-serif;text-transform: uppercase;color:#c6c6c6; text-shadow:-1px 0 0 #FFFFFF">Банк получателя</h1></td></tr>
                                    <tr><td class="tableheader" colspan="3">Наименование</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['bankname']?>" name="bankname" /></td></tr>
                                    <tr>
                                        <td colspan="3" class="tableheader" style="padding-right:10px">Бик</td>
                                        <!--td width="304" class="tableheader" style="padding-left:10px">Корсчет</td--></tr>
                                    <tr style="height: 80px;">
                                        <td class="" style="padding-right:10px" colspan="3"><input style="width:262px;" type="text" value="<?=$paydata['bik']?>" name="bik" /></td>
                                        <!--td class="" style="padding-left:10px"><input style="width:262px;" type="text" value="<?=$paydata['coraccount']?>" name="coraccount" /></td-->
                                    </tr>
                                    <tr><td class="tableheader" colspan="3">Примечание</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?php if (isset($paydata['extradata'])) {
    echo $paydata['extradata'];
}?>" name="extradata" /></td></tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4"><div class="g_line"></div></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="radio" data-pay="wmr" class="rb1" name="cashintype" <?php if ($paydata['cashintype'] == 'wmr') {
    echo 'checked';
} ?> value="wmr" style="width:14px;height:14px;margin-top:15;">
                            </td>
                            <td class="s3_h">
                                <img alt="" src="/img/wmr.png">
                            </td>
                            <td class="s3_text" style="margin-top: 14px;">
                                Получить вознаграждение в wmr (webmoney.ru)
                            </td>
                            <td></td>
                        </tr>
                        <tr id="wmr" <?php if ($paydata['cashintype'] != 'wmr'):?> style="display:none;"<?php endif;?> >
                            <td colspan="4">
                                <table id="step2table">
                                    <tr><td class="tableheader" colspan="3">Кошелек</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['wmr-account']?>" name="wmr-account" data-validate="wmr" /></td></tr>
                                    <tr><td class="tableheader" colspan="3">ФИО</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['wmr-fio']?>" name="wmr-fio" /></td></tr>
                                    <tr><td class="tableheader" colspan="3">Телефон для связи</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['wmr-phone']?>" name="wmr-phone" /></td></tr>
                                    <tr>
                                        <td colspan="2" width="304" class="tableheader" style="padding-right:10px">Номер и серия паспорта</td>
                                        <td width="304" class="tableheader" style="padding-left:10px">Кем и когда выдан</td>
                                    </tr>
                                    <tr style="height: 80px;">
                                        <td class="" style="padding-right:10px;width: 60px" width="60"><input style="width:60px;" type="text" value="<?=$paydata['passseries']?>" name="passseries" data-validate="notempty"/></td>
                                        <td class="" style="padding-right:10px;width: 100px"><input style="width:100px;" type="text" value="<?=$paydata['passnum']?>" name="passnum" data-validate="notempty" /></td>
                                        <td class="" style="padding-left:10px"><input style="width:272px" type="text" value="<?=$paydata['issuedby']?>" name="issuedby" data-validate="notempty"/></td>
                                    </tr>
                                    <tr><td class="tableheader" colspan="3">Дата рождения</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['birthdate']?>" name="birthdate" data-validate="notempty"/></td></tr>
                                    <tr><td class="tableheader" colspan="3">Место рождения</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['birthplace']?>" name="birthplace" data-validate="notempty" /></td></tr>
                                    <tr><td class="tableheader" colspan="3">Примечание</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?php if (isset($paydata['extradatawmr'])) {
    echo $paydata['extradatawmr'];
}?>" name="extradatawmr" /></td></tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4"><div class="g_line"></div></td>
                        </tr>
                        <!--tr>
                            <td>
                                <input type="radio" disabled data-pay="yandex" class="rb1" name="cashintype" <?php if ($paydata['cashintype'] == 'yandex') {
    echo 'checked';
} ?> value="yandex" style="width:14px;height:14px;margin-top:15px;">
                            </td>
                            <td class="s3_h">
                                <img alt=""  style="width:120px; margin-left: 10px;" src="/img/yd.png">
                            </td>
                            <td class="s3_text" style="margin-top: 14px;">
                                Получить вознаграждение в Yandex Деньгах  (<a href="https://money.yandex.ru/doc.xml?id=526543" target="_blank">только на идентифицированные кошельки</a>)
                            </td>
                            <td></td>
                        </tr>
                        <tr id="yandex" <?php if ($paydata['cashintype'] != 'yandex'):?> style="display:none;"<?php endif;?> >
                            <td colspan="4">
                                <table id="step2table" style="margin-top: 30px;">
                                    <tr><td class="tableheader" colspan="3">Кошелек</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['yandex-account']?>" name="yandex-account" data-validate="yandex" /></td></tr>
                                    <tr><td class="tableheader" colspan="3">ФИО</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['yandex-fio']?>" name="yandex-fio" /></td></tr>
                                    <tr><td class="tableheader" colspan="3">Телефон для связи</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?=$paydata['yandex-phone']?>" name="yandex-phone" /></td></tr>
                                    <tr>
                                        <td colspan="2" width="304" class="tableheader" style="padding-right:10px">Номер и серия паспорта</td>
                                        <td width="304" class="tableheader" style="padding-left:10px">Кем и когда выдан</td></tr>
                                    <tr style="height: 80px;">
                                        <td class="" style="padding-right:10px;width: 60px" width="60"><input style="width:60px;" type="text" value="<?=$paydata['passseriesyandex']?>" name="passseriesyandex" /></td>
                                        <td class="" style="padding-right:10px;width: 100px"><input style="width:100px;" type="text" value="<?=$paydata['passnumyandex']?>" name="passnumyandex" /></td>
                                        <td class="" style="padding-left:10px"><input style="width:272px" type="text" value="<?=$paydata['issuedbyyandex']?>" name="issuedbyyandex" /></td>
                                    </tr>
                                    <tr><td class="tableheader" colspan="3">Примечание</td></tr>
                                    <tr style="height: 80px;"><td class="" colspan="3"><input type="text" value="<?php if (isset($paydata['extradatayandex'])) {
    echo $paydata['extradatayandex'];
}?>" name="extradatayandex" /></td></tr>
                                </table>
                            </td>
                        </tr-->

                        </tbody></table>
                </div>
            </form>
                <section class="user-mobile-section" style="width: 540px">
                    <div class="g_line"></div>
                    <h1 class="section-header" style="width: 540px">Для перехода на следующий шаг и получения награды необходимо подтвердить ваш номер телефона!</h1>
                    <form method="post" id="mobile-form" action="/users/update">
                        <p class="confirm-message" <?php if (!empty($user->phone) && $user->phone_valid == 1):?>style="display:none;"<?php endif?> <?php if (!empty($user->phone) && $user->phone_valid == 0):?>style="display:block;"<?php endif?>>Для подтверждения номера +<?=$user->phone?> введите код, который пришел по смс.</p>
                        <div class="phone-input-container" <?php if (!empty($user->phone) && $user->phone_valid == 1):?>style="display:none;"<?php endif?> <?php if (!empty($user->phone) && $user->phone_valid == 0):?>style="display:none;"<?php endif?>>
                            <span class="plus">+</span>
                            <input type="text" name="phone" placeholder="79811234567">
                        </div>
                        <div class="clear"></div>
                        <ul <?php if (!empty($user->phone) && $user->phone_valid == 1):?>style="display:block;"<?php endif?> <?php if (!empty($user->phone) && $user->phone_valid == 0):?>style="display:block;"<?php endif?>>
                            <li class="number" <?php if (!empty($user->phone) && $user->phone_valid == 1):?>style="display:block;"<?php endif?>>+ <?= $user->phone?></li>
                            <li class="remove-number" <?php if (!empty($user->phone) && $user->phone_valid == 1):?>style="margin-right: 0 !important;"<?php endif?>><a href="#" class="remove-number-link"><?php if (!empty($user->phone) && $user->phone_valid == 1):?>Удалить/поменять номер<?php else:?>Удалить номер<?php endif?></a></li>
                            <li class="resend-code" <?php if (!empty($user->phone) && $user->phone_valid == 1):?>style="display:none;"<?php endif?>><a href="#" class="resend-code-link">Выслать код повторно</a></li>
                        </ul>
                        <div class="clear"></div>
                        <input type="text" name="phone_code"  <?php if (!empty($user->phone) && $user->phone_valid == 0):?>style="display:block;"<?php endif?>>
                        <input type="submit" id="confirm-mobile" class="button" value="Подтвердить код" <?php if (!empty($user->phone) && $user->phone_valid == 0):?>style="display:block;"<?php endif?>>
                        <input type="submit" id="save-mobile" class="button" value="Подтвердить телефон" <?php if (!empty($user->phone) && $user->phone_valid == 1):?>style="display:none;"<?php endif?> <?php if (!empty($user->phone) && $user->phone_valid == 0):?>style="display:none;"<?php endif?>>
                        <span class="note" <?php if (!empty($user->phone) && $user->phone_valid == 1):?>style="display:none;"<?php endif?> <?php if (!empty($user->phone) && $user->phone_valid == 0):?>style="display:none;"<?php endif?>>для смс оповещения в экстренных случаях</span>
                        <div class="clear"></div>
                    </form>
                    <p class="help" style="margin-top: 40px; font-family: Georgia, serif; font-size: 13px; font-style: italic; line-height: 16px;">Свяжитесь с нами, если  не получается подтвердить номер:<br/>
                        <a href="mailto:team@godesigner.ru">team@godesigner.ru</a> или (812) 648-24-12 по будням с 10–17<br/> по Москве</p>                </section>
                <div class="clear"></div>

                <div class="proceed">
                    <?=$this->html->link('<img src="/img/proceed.png" /><br />
                        <span>Продолжить</span>', ['controller' => 'users', 'action' => 'step2', 'id' => $solution->id], ['escape' => false, 'id' => 'step2-link-saveform'])?>
                </div>
                <div class="clr"></div>

            </div>



            <?=$this->view()->render(['element' => '/complete-process/rightblock'], ['solution' => $solution, 'type' => $type])?>
            <div class="clr"></div>

        </section>
    </div>
    <div class="conteiner-bottom"></div>
</div><!-- .wrapper -->
<?=$this->html->style(['/view', '/messages12', '/pitches12', '/pitches2', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css', ], ['inline' => false])?>
<?=$this->html->script(['jquery.tooltip.js', 'users/step1.js'], ['inline' => false])?>