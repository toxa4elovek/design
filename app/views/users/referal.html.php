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
                    <span class="referal-title">Вы получите 500 рублей на телефон,<br /> когда ваши друзья создадут питч на GoDesigner</span>
                    <a href="#">Правила и условия</a>
                    <br />
                    <br />
                    <br />
                    <img src="/img/referal-illustration.png" alt="Вы получите 500 рублей на телефон, когда ваши друзья создадут питч на GoDesigner" />
                    <section class="referal-block block-1" style="display: none;">
                        <form id="referal-1" action="/users/checkphone" method="post" style="margin-top: 35px;">
                            <div style="display: none; background: url(/img/tooltip-bg-bootom-stripe.png) no-repeat scroll 0 100% transparent !important; padding: 4px 0 0 !important; height: auto; width: 282px; position: absolute; z-index: 2147483647; margin-bottom: 4px;" id="tooltip-phone">
                                <div style="background:url(/img/tooltip-top-bg.png) no-repeat scroll 100% 0 transparent; padding: 30px 10px 10px 16px; height: auto;">
                                    <div>
                                        <p class="regular" style="color: #999;">К сожалению, мы не сможем подтвердить ваш телефон. Пожалуйста, укажите другой номер.</p>
                                    </div>
                                </div>
                            </div>
                            <input type="text" id="userPhone" name="userPhone" placeholder="" data-placeholder="" style="width: 270px; float: left; margin-top: 12px;">
                            <input type="submit" class="button" style="width: 300px; float: right;" value="Получить код">
                            <div class="clr"></div>
                            <div class="clr"></div>
                        </form>
                    </section>
                    <section class="referal-block block-2" style="display: none;">
                        <form id="referal-2" action="/users/validatecode" method="post" style="margin-top: 35px;">
                            <div style="display: none; background: url(/img/tooltip-bg-bootom-stripe.png) no-repeat scroll 0 100% transparent !important; padding: 4px 0 0 !important; height: auto; width: 282px; position: absolute; z-index: 2147483647; margin-bottom: 4px;" id="tooltip-code">
                                <div style="background:url(/img/tooltip-top-bg.png) no-repeat scroll 100% 0 transparent; padding: 30px 10px 10px 16px; height: auto;">
                                    <div>
                                        <p class="regular" style="color: #999;">Введен неверный код.</p>
                                    </div>
                                </div>
                            </div>
                            <span class="referal-title">Ваш телефон +<span class="phone-number"><?=$user->phone;?></span></span>
                            <a href="/users/deletephone" class="phone-delete" style="padding-left: 14px; background: url('/img/referal-close.png') 0px 6px no-repeat;">Удалить номер</a><br />
                            <a href="#" style="padding-left: 20px; background: url('/img/refresh.png') top left no-repeat;">Выслать код повторно</a><br />
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
                            <div class="clr" style="margin: 30px 0;"></div>
                            <input type="text" name="" placeholder="https://www.airbnb.ru/referrals" data-placeholder="https://www.airbnb.ru/referrals" style="width: 270px; float: left; margin-top: 12px;">
                            <input type="submit" class="button" style="width: 300px; float: right;" value="Отправить по почте">
                            <div class="clr"></div>
                        </form>
                        <a href="#" class="button facebook small" style="width: 176px;">Поделиться в facebook</a>
                        <a href="#" class="button vkontakte small" style="width: 176px;">Поделиться vkontakte</a>
                        <a href="#" class="button twitter small" style="width: 176px;">Поделиться в twitter</a>
                        <?php if (!empty($user->friends)):?>
                            <div class="separator-flag-empty">
                                <img src="/img/text-druzya.png" alt="Друзья на GoDesigner" />
                            </div>
                            <table class="referal-table">
                                <thead>
                                    <tr>
                                        <th width="308"><img src="/img/text-drug.png" style="margin-right: 90px;" alt="друг" /></th>
                                        <th width="100"><img src="/img/text-status.png" alt="статус" /></th>
                                        <th width="198"><img src="/img/text-balans.png" alt="баланс телефона" /></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="308"><img class="referal-avatar" src="" /><span style="float: left;">irenfrid@gmail.com</span></td>
                                        <td width="100" class="ref-status">приглашена</td>
                                        <td width="198">—</td>
                                    </tr>
                                    <tr>
                                        <td width="308"><img class="referal-avatar" src="" /><span style="float: left;">tulenvarstitagasi@yahoo.com</span></td>
                                        <td width="100" class="ref-status">присоедин.</td>
                                        <td width="198">—</td>
                                    </tr>
                                    <tr class="active">
                                        <td width="308"><img class="referal-avatar" src="" /><span style="float: left;">George Kronberg</span></td>
                                        <td width="100" class="ref-status">создал питч</td>
                                        <td width="198">+500р.-</td>
                                    </tr>
                                    <tr>
                                        <td width="308"><img class="referal-avatar" src="" /><span style="float: left;">tulenvarstitagasi@yahoo.com</span></td>
                                        <td width="100" class="ref-status">присоедин.</td>
                                        <td width="198">—</td>
                                    </tr>
                                    <tr>
                                        <td width="308"><img class="referal-avatar" src="" /><span style="float: left;">tulenvarstitagasi@yahoo.com</span></td>
                                        <td width="100" class="ref-status">присоедин.</td>
                                        <td width="198">—</td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php endif;?>
                    </section>
                </section>
            </section>
        </div><!-- .main -->
    </div><!-- .middle -->
</div><!-- .wrapper -->

<?=$this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array('/main2.css', '/pitches2.css', '/edit','/view', '/messages12', '/pitches12', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css',), array('inline' => false))?>