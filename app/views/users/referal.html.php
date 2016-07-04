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
                <div style="width:200px">
                    <?php echo $this->stream->renderStream(3);?>
                </div>
            </div>
            <section class="mainblock">
                <section class="referal-section">
                    <input type="hidden" id="prop-phone" value="<?=$user->phone;?>">
                    <input type="hidden" id="prop-phone_valid" value="<?=$user->phone_valid;?>">
                    <h1 class="separator-flag">ПРИГЛАШАЙ ДРУЗЕЙ</h1>
                    <span class="referal-title">Вы получите 500 рублей на телефон,<br /> когда ваши друзья создадут проект на GoDesigner</span>
                    <a href="/answers/view/90" target="_blank">Правила и условия</a>
                    <br />
                    <br />
                    <br />
                    <img src="/img/referal-illustration.png" alt="Вы получите 500 рублей на телефон, когда ваши друзья создадут проект на GoDesigner" />
                    <section class="referal-block block-1" style="display: none;">
                        <form id="referal-1" action="/users/checkphone" method="post" style="margin-top: 35px;">
                            <div style="display: none; background: url(/img/tooltip-bg-bootom-stripe.png) no-repeat scroll 0 100% transparent !important; padding: 4px 0 4px 0 !important; height: auto; width: 282px; position: absolute; z-index: 2147483647;" id="tooltip-phone">
                                <div style="background:url(/img/tooltip-top-bg.png) no-repeat scroll 100% 0 transparent; padding: 30px 10px 10px 16px; height: auto;">
                                    <div>
                                        <p class="regular" style="color: #999;">К сожалению, мы не сможем подтвердить ваш телефон. Пожалуйста, укажите другой номер.</p>
                                    </div>
                                </div>
                            </div>
                            <div style="float: left; text-align: left;">
                            <select name="phone-operator" id="phone-operator" style="height: 20px; width: 300px; border: none; border-radius: 2px;">
                                <option value="0">Выберите провайдера</option>
                                <optgroup label="Россия">
                                    <option value="1">МТС</option>
                                    <option value="2">Билайн</option>
                                    <option value="3">Мегафон</option>
                                    <option value="31">Теле-2</option>
                                </optgroup>
                                <optgroup label="Беларусь">
                                    <option value="620">МТС Беларусь</option>
                                    <option value="621">Velcom</option>
                                    <option value="623">Life Беларусь</option>
                                    <option value="622">DIALLOG</option>
                                </optgroup>
                                <optgroup label="Украина">
                                    <option value="41">Киевстар</option>
                                    <option value="42">МТС Украина</option>
                                    <option value="253">Life Украина</option>
                                </optgroup>
                            </select>
                            <br>
                            <span class="" style="font: 24px/50px Arial; color: #666; text-shadow: 0 1px 0 #fff;">+</span>
                            <input type="text" id="userPhone" name="userPhone" placeholder="Введите номер телефона" data-placeholder="Введите номер телефона" style="width: 260px; margin-top: -2px;">
                            </div>
                            <input type="submit" class="button" style="width: 300px; height: 70px; float: right;" value="Получить код">
                            <div class="clr"></div>
                            <div class="clr"></div>
                        </form>
                    </section>
                    <section class="referal-block block-2" style="display: none;">
                        <form id="referal-2" action="/users/validatecode" method="post" style="margin-top: 35px;">
                            <div style="display: none; background: url(/img/tooltip-bg-bootom-stripe.png) no-repeat scroll 0 100% transparent !important; padding: 4px 0 4px 0 !important; height: auto; width: 282px; position: absolute; z-index: 2147483647;" id="tooltip-code">
                                <div style="background:url(/img/tooltip-top-bg.png) no-repeat scroll 100% 0 transparent; padding: 30px 10px 10px 16px; height: auto;">
                                    <div>
                                        <p class="regular" style="color: #999;">Введен неверный код.</p>
                                    </div>
                                </div>
                            </div>
                            <span class="referal-title">Ваш телефон +<span class="phone-number"><?=$user->phone;?></span></span>
                            <a href="/users/deletephone" class="phone-delete" style="padding-left: 14px; background: url('/img/referal-close.png') 0px 6px no-repeat;">Удалить номер</a><br />
                            <a href="/users/checkphone" class="code-resend" data-phone="<?=$user->phone;?>" data-phone-operator="<?=$user->phone_operator;?>" style="padding-left: 20px; background: url('/img/refresh.png') top left no-repeat;">Выслать код повторно</a><br />
                            <p class="regular" style="margin: 20px 0;">Подтвердите свой номер телефона, на счёт которого будет поступать вознаграждение,<br /> для получения личной ссылки.</p>
                            <input type="text" id="verifyCode" name="verifyCode" placeholder="Введите код" data-placeholder="Введите код" style="width: 270px; float: left; margin-top: 12px;">
                            <input type="submit" class="button" style="width: 300px; float: right;" value="Подтвердить код">
                            <div class="clr"></div>
                        </form>
                    </section>
                    <section class="referal-block block-3" style="display: none;">
                        <form id="referal-3" action="" method="post" style="margin: 35px 0 30px 0;">
                            <span class="referal-title">Ваш телефон +<span class="phone-number"><?=$user->phone;?></span></span>
                            <a href="/users/deletephone" class="phone-delete" style="padding-left: 14px; background: url('/img/referal-close.png') 0px 6px no-repeat;">Удалить номер</a>
                            <div class="clr" style="margin: 30px 0;clear: left"></div>
                            <input type="text" name="" value="https://www.godesigner.ru/?ref=<?=$user->referal_token;?>" style="width: 270px; float: left; margin-top: 3px; font-size: 15px; font-weight: bold; color: #999;">
                            <a href="mailto:?subject=Это новый сервис для создания дизайна" class="button" style="width: 240px; float: right;">Отправить по почте</a>
                            <div class="clr" style="clear: left"></div>
                        </form>
                        <?php
                            $shareTextFacebook = 'GoDesigner, пожалуй, самый современный способ создания дизайна. Создайте проект на разработку лого или сайта по этой ссылке и получите скидку в 300 руб.';
                            $shareTitleFacebook = 'GoDesigner.ru';
                            $shareTextTwitter = 'GoDesigner — самый современный способ создания дизайна. Оформите заказ по этой ссылке и получите скидку в 300 руб.';
                        ?>
                        <a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]=https://www.godesigner.ru/?ref=<?=$user->referal_token?>&p[title]=<?php echo urlencode($shareTitleFacebook);?>&p[summary]=<?php echo urlencode($shareTextFacebook);?>" class="button facebook small social-popup" style="width: 176px;">Поделиться в facebook</a>
                        <a href="http://www.vkontakte.ru/share.php?url=https://www.godesigner.ru/?ref=<?=$user->referal_token?>&title=<?php echo urlencode($shareTitleFacebook);?>&description=<?php echo urlencode($shareTextFacebook);?>&noparse=1" class="button vkontakte small social-popup" style="width: 176px;">Поделиться vkontakte</a>
                        <a href="https://twitter.com/share?url=http://www.godesigners.ru/?ref=<?=$user->referal_token?>&text=<?php echo urlencode($shareTextTwitter);?>" class="button twitter small social-popup" style="width: 176px;">Поделиться в twitter</a>
                        <?php if (count($refPitches) > 0):?>
                            <div class="separator-flag-empty">
                                <img src="/img/text-druzya.png" alt="Друзья на GoDesigner" style="position: relative;left: -110px;" />
                            </div>
                            <table class="referal-table">
                                <thead>
                                    <tr>
                                        <th width="260"><img src="/img/text-drug.png" style="margin-right: 90px;" alt="друг" /></th>
                                        <th width="148"><img src="/img/text-status.png" alt="статус" /></th>
                                        <th width="198"><img src="/img/text-balans.png" alt="баланс телефона" /></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($refPitches as $refPitch):
                                    $refStatus = 'присоедин.';
                                    $refClass = '';
                                    $refSum = '—';
                                    if (($refPitch->billed == 1) && ($refPitch->published == 1)) {
                                        if($completePaymentCount > 0) {
                                            $refStatus = 'выплата произведена';
                                        }else {
                                            $refStatus = 'оплатил проект';
                                        }
                                        $completePaymentCount -= 1;
                                        $refClass = ' class="active"';
                                        $refSum = '+500р.-';
                                    }?>
                                    <tr<?php echo $refClass;?>>
                                        <td width="260"><img class="referal-avatar" src="<?=$this->avatar->show($refPitch->user->data(), false, true);?>" /><span style="float: left;"><?=$refPitch->user->email;?></span></td>
                                        <td width="148" class="ref-status"><?=$refStatus;?></td>
                                        <td width="198"><?php echo $refSum;?></td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        <?php endif;?>
                    </section>
                </section>
            </section>
            <div class="clr"></div>
        </div><!-- .main -->
    </div><!-- .middle -->
</div><!-- .wrapper -->

<?=$this->html->script(array(    '/js/users/office/PushNotificationsStatus.js', 'jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'jquery.tooltip.js', 'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array('/main2.css', '/pitches2.css', '/edit','/view', '/messages12', '/pitches12', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css',), array('inline' => false))?>