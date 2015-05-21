<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>
    <?php
    $userdata = unserialize($user->userdata);
    ?>
    <div class="middle">
        <div class="main">
            <nav class="main_nav clear" style="width:832px;margin-left:2px;">
                <?=$this->view()->render(array('element' => 'office/nav'));?>
            </nav>

            <div class="sideblock">
                <div class="other_nav_gallery"><!--a href="/users/preview/<?=$user->id?>">ПРОСМОТРЕТЬ ПРОФИЛЬ</a-->
                    <a class="other-nav-right active" style="margin-right: 75px; margin-top: 70px; margin-bottom: 35px;" href="/users/preview/<?=$user->id?>">
                        <img width="184" height="34" alt="" src="/img/1.gif"><br>
                        <span>просмотреть профиль</span>
                    </a>
                </div>
                <div style="width:200px">
                    <?php echo $this->stream->renderStream(3);?>
                </div>
            </div>
            <section class="mainblock">
                <form action="/users/profile" method="post">
                    <section>
                        <h1 class="separator-flag">ОТКРЫТАЯ ИНФОРМАЦИЯ</h1>
                        <input type="hidden" name="userpic" value="">
                        <div class="photoselectbox qq-uploader" style="height:196px;width:196px;">

                            <?=$this->avatar->show($user->data(), 'true')?>

                        </div>
                        <span style="display: none;position:absolute;top:396px;left:79px; width:118px;" id="file-uploader-demo1"></span>
                        <div id="fieldblock1">
                            <div class="userwelcometext" style="padding-top:5px;margin-bottom: 9px">Привет, <?=$user->first_name?> <?=$user->last_name?>!</div>
                            <div style="margin-bottom: 7px"><input type="text" name="birthdate" placeholder="Дата рождения" value="<?=$userdata['birthdate']?>" ></div>
                            <div style="margin-bottom: 7px"><input type="text" name="city" placeholder="Город" value="<?=$userdata['city']?>" ></div>
                            <div style="margin-bottom: 5px"><input type="text" name="profession" placeholder="Профессия" value="<?=$userdata['profession']?>" ></div>
                            <div style="margin-top:12px;">
                                <textarea class="textareaabout" name="about" placeholder="О себе"><?=$userdata['about']?></textarea>
                            </div>
                        </div>
                        <div class="profselectbox">
                            <input type="hidden" name="isClient" value="<?=$user->isClient?>" id="iscustomer"/>
                            <input type="hidden" name="isDesigner" value="<?=$user->isDesigner?>" id="isdesigner" />
                            <input type="hidden" name="isCopy" value="<?=$user->isCopy?>" id="iscopyrighter"/>
                            <button id="profselect1" class="changeStatus <?php if($user->isClient): echo 'profselectbtnpressed'; endif; ?>" name="iscustomer" value="false" >Я - ЗАКАЗЧИК</button>
                            <button id="profselect2" class="changeStatus <?php if($user->isDesigner): echo 'profselectbtnpressed'; endif; ?>" name="isdesigner" value="true">Я - ДИЗАЙНЕР</button>
                            <button id="profselect3" class="changeStatus <?php if($user->isCopy): echo 'profselectbtnpressed'; endif; ?>" name="iscopyrighter" value="false" >Я - КОПИРАЙТЕР</button>
                        </div>
                    </section>
                    <section style="height: 230px;">
                        <h1 class="separator-flag">НАСТРОЙКИ</h1>
                        <div id="fieldblock3">
                            <?php if($passwordInfo != false):?>
                            <p class="regular"><?=$passwordInfo?></p>
                            <?php endif;?>
                            <?php if($emailInfo != false):?>
                            <p class="regular"><?=$emailInfo?></p>
                            <?php endif;?>
                            <div class="fieldleft"><input type="password" placeholder="Старый пароль" name="currentpassword"></div>
                            <div><input type="email" placeholder="Email" name="email" value="<?=$user->email?>"></div>
                            <div class="fieldleft"><input type="password" placeholder="Новый пароль" name="newpassword"></div>
                            <!--div><input placeholder="Новый никнейм"></div-->
                            <div><a href="/users/deleteaccount" style="padding-left: 10px; width: 282px; display: block; float: left; height: 40px; margin-top: 13px;" id="deleteaccount">Удалить аккаунт</a></div>
                            <div class="fieldleft">
                                <input type="password" placeholder="Повторите новый пароль" name="confirmpassword">
                            </div>
                        </div>
                        <div style="clear:both;height:1px"></div>
                    </section>
                    <section>
                        <h1 class="separator-flag">УВЕДОМЛЕНИЯ ПО E-MAIL</h1>
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
                                        <input style="margin-top:0; margin-bottom: 2px;" type="checkbox" name="email_newpitch" <?php if($user->email_newpitch): echo 'checked'; endif;?>>о новых проектах сразу, как они<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;публикуются на сайте
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
                                        <input style="margin-top:0; margin-bottom: 2px;" type="checkbox" name="email_newsolonce" <?php if($user->email_newsolonce): echo 'checked'; endif;?>>о новых решениях к моим проектам<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;раз в день
                                    </label>
                                </li>
                                <li>
                                    <label class="regular" style="font-weight: normal">
                                        <input style="margin-top:0; margin-bottom: 2px;" type="checkbox" name="email_newsol" <?php if($user->email_newsol): echo 'checked'; endif;?>>о новых решениях к моим проектам<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;сразу, как только их выкладывают на<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;сайт
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
                    </section>

                    <div id="sendbuttonbox"><input type="submit" class="button" style="width: 140px" value="Сохранить"></div>

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
                </form>

                <div class="g_line"></div>
                <section class="user-details-section">
                    <form id="worker-payment-data" action="/users/details" method="post">
                        <div>
                            <div style="text-align: left;">
                                <h1 class="user-details-header">Реквизиты: выберите способ получения денег</h1>
                            </div>

                            <table class="user-details-table">
                                <tbody><tr>
                                    <td width="28">
                                        <input type="radio" data-pay="cards" class="rb1" name="cashintype" <?php if($paydata['cashintype'] == 'card') echo 'checked' ?> value="card">
                                    </td>
                                    <td width="186">
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

                        </div>
                    </form>


                    <?php //$this->stream->renderStream();?>
                    <div class="clr"></div>

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