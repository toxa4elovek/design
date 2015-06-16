<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>
    <?php
    $userdata = unserialize($user->userdata);
    $company = unserialize($user->companydata);
    ?>
    <div class="middle">
        <div class="main">

            <nav class="main_nav clear" style="width:832px;margin-left:2px;">
                <?=$this->view()->render(array('element' => 'office/nav'));?>
            </nav>

            <div class="sideblock">
                <a class="button show-preview" href="/users/preview/<?=$user->id?>">
                    просмотреть профиль
                </a>
            </div>

            <section class="mainblock">
                <form action="/users/profile" method="post">
                    <section class="basic-info-section">
                        <input type="hidden" name="userpic" value="">
                        <div class="photoselectbox qq-uploader" style="height:196px;width:196px;">
                            <?=$this->avatar->show($user->data(), 'true')?>
                        </div>
                        <span style="display: none;position:absolute;top:334px;left:79px; width:118px;" id="file-uploader-demo1"></span>

                        <div id="fieldblock1">
                            <div style="margin-bottom: 2px"><input type="text" name="first_name" placeholder="Имя" value="<?=$user->first_name?>" ></div>
                            <div style="margin-bottom: 7px"><input type="text" name="last_name" placeholder="Фамилия" value="<?=$user->last_name?>" ></div>
                            <div style="margin-top: 20px;">
                                <label><input type="radio" name="gender" value="1" <?php if($user->gender == 1):?>checked<?php endif ?>>Мужчина</label>
                                <label><input type="radio" name="gender" value="2" <?php if($user->gender == 2):?>checked<?php endif ?>>Женщина</label>
                            </div>
                            <div class="short-input-block">
                                <input class="short" type="text" name="city" placeholder="Город, страна" value="<?=$userdata['city']?>" >
                                <input class="short" type="text" name="birthdate" placeholder="ДД.ММ.ГГ рождения" value="<?=$userdata['birthdate']?>" >
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="profselectbox">
                            <input type="hidden" name="isDesigner" value="0">
                            <input type="hidden" name="isClient" value="0">
                            <input type="hidden" name="isCopy" value="0">
                            <input type="hidden" name="is_company" value="0">
                            <label><input type="checkbox" name="isDesigner" value="1" id="isdesigner" <?php if($user->isDesigner): echo 'checked'; endif; ?> />Я — дизайнер</label>
                            <label style="margin-left: 83px;"><input type="checkbox" name="isClient" value="1" id="iscustomer" <?php if($user->isClient): echo 'checked'; endif; ?> />Я — заказчик</label>
                            <label style="margin-left: 76px;"><input type="checkbox" name="isCopy" value="1" id="iscopyrighter" <?php if($user->isCopy): echo 'checked'; endif; ?> />Я — копирайтер</label>
                            <label style="margin-left: 55px;"><input type="checkbox" name="is_company" value="1" <?php if($user->is_company): echo 'checked'; endif; ?> />Я — юр. лицо</label>
                        </div>
                    </section>
                </form>

                <div class="clear"></div>
                <section class="user-mobile-section">
                    <div class="g_line"></div>
                    <h1 class="section-header">Сотовый</h1>
                    <form method="post" id="mobile-form" action="/users/update">
                        <p class="confirm-message" <?php if(!empty($user->phone) && $user->phone_valid == 1):?>style="display:none;"<?php endif?> <?php if(!empty($user->phone) && $user->phone_valid == 0):?>style="display:block;"<?php endif?>>Для подтверждения номера +<?=$user->phone?> введите код, который пришел по смс.</p>
                        <div class="phone-input-container" <?php if(!empty($user->phone) && $user->phone_valid == 1):?>style="display:none;"<?php endif?> <?php if(!empty($user->phone) && $user->phone_valid == 0):?>style="display:none;"<?php endif?>>
                            <span class="plus">+</span>
                            <input type="text" name="phone" placeholder="79811234567">
                        </div>
                        <div class="clear"></div>
                        <ul <?php if(!empty($user->phone) && $user->phone_valid == 1):?>style="display:block;"<?php endif?> <?php if(!empty($user->phone) && $user->phone_valid == 0):?>style="display:block;"<?php endif?>>
                            <li class="number" <?php if(!empty($user->phone) && $user->phone_valid == 1):?>style="display:block;"<?php endif?>>+ <?= $user->phone?></li>
                            <li class="remove-number" <?php if(!empty($user->phone) && $user->phone_valid == 1):?>style="margin-right: 0 !important;"<?php endif?>><a href="#" class="remove-number-link"><?php if(!empty($user->phone) && $user->phone_valid == 1):?>Удалить/поменять номер<?php else:?>Удалить номер<?php endif?></a></li>
                            <li class="resend-code" <?php if(!empty($user->phone) && $user->phone_valid == 1):?>style="display:none;"<?php endif?>><a href="#" class="resend-code-link">Выслать код повторно</a></li>
                        </ul>
                        <div class="clear"></div>
                        <input type="text" name="phone_code"  <?php if(!empty($user->phone) && $user->phone_valid == 0):?>style="display:block;"<?php endif?>>
                        <input type="submit" id="confirm-mobile" class="button" value="Подтвердить код" <?php if(!empty($user->phone) && $user->phone_valid == 0):?>style="display:block;"<?php endif?>>
                        <input type="submit" id="save-mobile" class="button" value="Подтвердить телефон" <?php if(!empty($user->phone) && $user->phone_valid == 1):?>style="display:none;"<?php endif?> <?php if(!empty($user->phone) && $user->phone_valid == 0):?>style="display:none;"<?php endif?>>
                        <span class="note" <?php if(!empty($user->phone) && $user->phone_valid == 1):?>style="display:none;"<?php endif?> <?php if(!empty($user->phone) && $user->phone_valid == 0):?>style="display:none;"<?php endif?>>для смс оповещения в экстренных случаях</span>
                        <div class="clear"></div>
                    </form>

                    <section class="user-email-section">
                        <div class="g_line"></div>
                        <h1 class="section-header">Email</h1>
                        <p>
                            <?php if($emailInfo != false):?>
                                <?=$emailInfo?><br>
                            <?php endif;?>
                            <?= $this->user->getMaskedEmail()?></p>
                        <form method="post" id="email-form" action="/users/update">
                            <input type="email" placeholder="Новый email" name="email" value="">
                            <input type="submit" id="save-email" class="button" value="Сохранить адрес" />
                        </form>
                    </section>
                </section>

                <section class="user-password-section">
                    <div class="g_line"></div>
                    <h1 class="section-header">Пароль</h1>
                        <form method="post" id="password-form" action="/users/update">
                            <p></p>
                            <input type="password" placeholder="Старый пароль" name="currentpassword">
                            <input type="password" placeholder="Новый пароль" name="newpassword">
                            <input type="password" placeholder="Повторите новый пароль" name="confirmpassword">
                        </form>
                    <div class="clear"></div>

                    <input type="submit" id="save-password" class="button" value="Изменить пароль">
                    <a href="/users/deleteaccount" id="deleteaccount">Удалить аккаунт</a>
                </section>




                <div class="clear"></div>

                    <div class="g_line" style="margin-top: 25px;"></div>
                    <section class="user-notifications">
                        <h1 class="section-header">Уведомления</h1>
                        <form id="notifications-form" action="/users/update/" method="post">
                            <div id="fieldblock4">
                                <?php
                                    $labels = array(
                                        'client' => array('email_newcomments' => 'комментарии к моим проектам'),
                                        'other' => array('email_newcomments' => 'комментарии к моим решениям'),
                                    );
                                    if($user->isClient) {
                                        $email_newcomments = $labels['client']['email_newcomments'];
                                    }else {
                                        $email_newcomments = $labels['other']['email_newcomments'];
                                    }
                                ?>
                                <ul>
                                    <li>
                                        <label class="regular" style="font-weight: normal">
                                            <input style="margin-top:0; margin-bottom: 2px;" type="checkbox" name="email_newpitchonce" <?php if($user->email_newpitchonce): echo 'checked'; endif;?>>о новых проектах раз в день
                                        </label>
                                    </li>
                                    <li>
                                        <label class="regular" style="font-weight: normal">
                                            <input style="margin-top:0; margin-bottom: 2px;" type="checkbox" name="email_newpitch" <?php if($user->email_newpitch): echo 'checked'; endif;?>>о новых проектах сразу, как они публикуются на сайте
                                        </label>
                                    </li>
                                    <li>
                                        <label class="regular" style="font-weight: normal">
                                            <input style="margin-top:0; margin-bottom: 2px;" type="checkbox" name="email_onlycopy" <?php if($user->email_onlycopy): echo 'checked'; endif;?>>только о новых проектах на копирайтинг
                                        </label>
                                    </li>
                                    <li>
                                        <label class="regular" style="font-weight: normal">
                                            <input style="margin-top:0; margin-bottom: 2px;" type="checkbox" name="email_newcomments" <?php if($user->email_newcomments): echo 'checked'; endif;?>><?= $email_newcomments?>
                                        </label>
                                    </li>
                                </ul>
                                <ul>
                                    <li>
                                        <label class="regular" style="font-weight: normal">
                                            <input style="margin-top:0; margin-bottom: 2px;" type="checkbox" name="email_newsolonce" <?php if($user->email_newsolonce): echo 'checked'; endif;?>>о новых решениях к моим проектам раз в день
                                        </label>
                                    </li>
                                    <li>
                                        <label class="regular" style="font-weight: normal">
                                            <input style="margin-top:0; margin-bottom: 2px;" type="checkbox" name="email_newsol" <?php if($user->email_newsol): echo 'checked'; endif;?>>о новых решениях к моим проектам сразу, как их <br> выкладывают на сайт
                                        </label>
                                    </li>
                                    <li>
                                        <label class="regular" style="font-weight: normal">
                                            <input style="margin-top:0; margin-bottom: 2px;" type="checkbox" name="email_digest" <?php if($user->email_digest): echo 'checked'; endif;?>>дайджест новостей (1 раз в 3 недели)
                                        </label>
                                    </li>
                                </ul>
                                <div class="clr"></div>
                            </div>
                            <input type="submit" id="save-notifications" class="button" value="Сохранить настройки уведомлений" />
                        </form>
                    </section>

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

                <div class="g_line"></div>
                <section class="user-details-section" <?php if($user->is_company == 1):?>style="display: none;"<?php endif?>>
                    <form id="worker-payment-data" action="/users/details" method="post">
                        <h1 class="section-header">Реквизиты: выберите способ получения денег</h1>

                        <table class="user-details-table">
                            <tbody><tr>
                                <td width="28">
                                    <input type="radio" data-pay="cards" class="rb1" name="cashintype" <?php if($paydata['cashintype'] == 'card') echo 'checked' ?> value="card">
                                </td>
                                <td width="186" class="s3_h">
                                    <img alt="Банковские карты" src="/img/visa_mastercard.png">
                                </td>
                                <td class="s3_text">
                                    Получить вознаграждение <br>на банковскую карту VISA, MASTERCARD
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr id="cards" <?php if($paydata['cashintype'] != 'card'):?> style="display:none;" <?php endif;?>>
                                <td colspan="4">
                                    <table id="step1table">
                                        <tr style="height: 40px;">
                                            <td></td>
                                        </tr>
                                        <tr><td class="tableheader" colspan="2">ФИО</td></tr>
                                        <tr style="height: 80px;"><td class="" colspan="2"><input type="text" value="<?=$paydata['fio']?>" name="fio" data-validate="fio" /></td></tr>
                                        <tr><td class="tableheader" colspan="2">Телефон для связи</td></tr>
                                        <tr style="height: 80px;"><td class="" colspan="2"><input type="text" value="<?=$paydata['phone']?>" name="phone" /></td></tr>
                                        <tr>
                                            <td width="304" class="tableheader" style="padding-right:10px">Номер счета получателя <a href="#" class="tooltip_plugin" title="Номер вашего счёта, не карты!">(?)</a></td>
                                            <td width="304" class="tableheader" style="padding-left:10px">Ваш личный ИНН <a href="#" class="tooltip_plugin" title="12 цифр без пробелов">(?)</a></td></tr>
                                        <tr style="height: 80px;">
                                            <td class="" style="padding-right:10px"><input style="width:262px;" type="text" value="<?=$paydata['accountnum']?>" name="accountnum" data-validate="numeric" /></td>
                                            <td class="" style="padding-left:10px"><input style="width:262px;" type="text" value="<?=$paydata['inn']?>" name="inn" /></td>
                                        </tr>
                                        <tr><td colspan="2"  height="60"><h1 style="font:bold 18px/1 'RodeoC',sans-serif;text-transform: uppercase;color:#c6c6c6; text-shadow:-1px 0 0 #FFFFFF">Банк получателя</h1></td></tr>
                                        <tr><td class="tableheader" colspan="2">Наименование</td></tr>
                                        <tr style="height: 80px;"><td class="" colspan="2"><input type="text" value="<?=$paydata['bankname']?>" name="bankname" /></td></tr>
                                        <tr>
                                            <td colspan="2" class="tableheader" style="padding-right:10px">Бик</td>
                                        <tr style="height: 80px;">
                                            <td class="" style="padding-right:10px"><input style="width:262px;" type="text" value="<?=$paydata['bik']?>" name="bik" /></td>
                                        </tr>
                                        <tr><td class="tableheader" colspan="2">Примечание</td></tr>
                                        <tr style="height: 80px;"><td class="" colspan="2"><input type="text" value="<?php if(isset($paydata['extradata'])) echo $paydata['extradata']?>" name="extradata" /></td></tr>
                                    </table>
                                </td>
                            </tr>

                            <tr style="height: 80px;">
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" data-pay="wmr" class="rb1" name="cashintype" <?php if($paydata['cashintype'] == 'wmr') echo 'checked' ?> value="wmr">
                                </td>
                                <td class="s3_h">
                                    <img alt="Webmoney WMR" src="/img/wmr.png">
                                </td>
                                <td class="s3_text" style="margin-top: 14px;">
                                    Получить вознаграждение в wmr (webmoney.ru)
                                </td>
                                <td></td>
                            </tr>
                            <tr id="wmr" <?php if($paydata['cashintype'] != 'wmr'):?> style="display:none;"<?php endif;?> >
                                <td colspan="4">
                                    <table id="step2table">
                                        <tr style="height: 40px;">
                                            <td></td>
                                        </tr>
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
                            </tbody>
                        </table>

                        <input type="submit" id="save" class="button" value="Сохранить реквизиты" />

                        <div class="clr"></div>

                    </form>

                    <div class="clr"></div>

                </section>

                <section class="user-company-section" <?php if($user->is_company == 0):?>style="display: none;"<?php endif?>>
                    <h1 class="section-header">Реквизиты вашей компании</h1>
                    <form id="company-payment-data" action="/users/update" method="post">
                        <input type="text" required maxlength="10" name="short_company_name" value="<?= $user->short_company_name?>" placeholder="Краткое название компании">
                        <input type="text" required data-content="mixed" name="company_name" value="<?= $company['company_name']?>" placeholder="Полное название компании">
                        <input type="text" required data-content="numeric" id="yur-inn" data-length="[10,12]" name="inn" value="<?= $company['inn']?>" placeholder="ИНН">
                        <input type="text" required data-content="numeric" id="yur-kpp" data-length="[9]" name="kpp" value="<?= $company['kpp']?>" placeholder="КПП">
                        <input type="text" required data-content="mixed" name="address" value="<?= $company['address']?>" placeholder="Полный адрес компании">
                        <input type="submit" id="save-company" class="button" value="Сохранить реквизиты" />
                    </form>
                </section>

                <div id="popup-final-step" class="popup-final-step" style="display:none;">
                    <h3 style="text-transform:uppercase;font-family: RodeoC; margin-top: 140px;margin-left: 110px;font-size:28px;text-shadow: -1px 1px 2px white;margin-bottom: 30px;">Хотите удалить аккаунт?</h3>
                    <div style="margin-bottom: 30px;">• Если вам надоела рассылка, пожалуйста, просто отпишитесь<br> от нее в один клик <a href="/users/unsubscribe?token=<?=base64_encode($this->user->getId())?>">здесь.</a><br></div>
                    <div style="margin-bottom: 50px;">• Если вы желаете навсегда стереть свой аккаунт, вы больше<br> не сможете в него зайти, а ваш профиль нельзя будет просмотреть.</div>
                    <div class="final-step-nav wrapper" style="margin-top:20px;">
                        <input type="submit" style="width: 179px" class="button second popup-close" value="Нет, отменить">
                        <input type="submit" style="width: 179px" class="button" id="confirmWinner" value="Да, подтвердить">
                    </div>
                </div>

                <div id="delete-comfirm" class="popup-final-step" style="display:none;text-align:center">
                    <div style="text-transform: uppercase; font-family: RodeoC; margin-top: 160px; font-size: 28px; text-shadow: -1px 1px 2px white; margin-bottom: 30px; line-height: 35px; margin-left: 0px;">Жаль, что не смогли<br> быть вам полезными.</div>
                    <div style="text-transform: uppercase; font-family: RodeoC; margin-top: 60px; font-size: 28px; text-shadow: -1px 1px 2px white; margin-bottom: 30px; line-height: 35px; margin-left: 0px;">Может,<br>в следующий раз...</div>
                </div>
            </section>
        </div><!-- .main -->
    </div><!-- .middle -->


</div><!-- .wrapper -->


<?=$this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'jquery.tooltip.js', 'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array('/main2.css', '/pitches2.css', '/edit','/view', '/messages12', '/pitches12', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css', '/css/profile.css'), array('inline' => false))?>