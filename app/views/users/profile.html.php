<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>
    <?php
    $userdata = unserialize($user->userdata);
    ?>
    <div class="middle">
        <div class="main">
            <nav class="main_nav clear" style="width:832px;margin-left:2px;">
                <?=$this->html->link('<span>Обновления</span>', array('controller' => 'users', 'action' => 'office'), array('escape' => false, 'class' => 'ajaxoffice')) ?>
                <?=$this->html->link('<span>Мои питчи</span>', array('controller' => 'users', 'action' => 'mypitches'), array('escape' => false, 'class' => 'ajaxoffice')) ?>
                <!--a href="#"><span>Сообщения</span></a-->
                <?=$this->html->link('<span>Профиль</span>', array('controller' => 'users', 'action' => 'profile'), array('escape' => false, 'class' => 'active')) ?>
                <?=$this->html->link('<span>Решения</span>', array('controller' => 'users', 'action' => 'solutions'), array('escape' => false, 'class' => 'ajaxoffice')) ?>
                <?=$this->html->link('<span>Реквизиты</span>', array('controller' => 'users', 'action' => 'details'), array('escape' => false, 'class' => 'ajaxoffice')) ?>
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
                <div class="block">
                    <!--div class="blocktitle">Текущие питчи</div>
                    <div id="block2">
                        <div id="block2content">
                            <div class="block2pitch" onclick="document.location.href = '#'">
                                <div class="block2pitchpic"><img src="/img/pitch-lock.png" alt="Some text"></div>
                                <div class="block2pitchdate">21.11.11</div>
                                <div class="block2pitchprice">21000 Р.-</div>
                                <div class="block2pitchdescr">Лого для ООО "Громпушка", фирменный стиль</div>
                            </div>
                            <div class="block2pitchdark" onclick="document.location.href = '#'">
                                <div class="block2pitchpic"></div>
                                <div class="block2pitchdate">21.11.11</div>
                                <div class="block2pitchprice">21000 Р.-</div>
                                <div class="block2pitchdescr">Лого для ООО "Громпушка", фирменный стиль</div>
                            </div>
                            <div class="block2pitch" onclick="document.location.href = '#'">
                                <div class="block2pitchpic"><img src="/img/pitch-dialog.png" alt="Some text"></div>
                                <div class="block2pitchdate">21.11.11</div>
                                <div class="block2pitchprice">21000 Р.-</div>
                                <div class="block2pitchdescr">Лого для ООО "Громпушка", фирменный стиль</div>
                            </div>
                            <div class="block2pitchdark" onclick="document.location.href = '#'">
                                <div class="block2pitchpic"><img src="/img/pitch-medal.png" alt="Some text"></div>
                                <div class="block2pitchdate">21.11.11</div>
                                <div class="block2pitchprice">21000 Р.-</div>
                                <div class="block2pitchdescr">Лого для ООО "Громпушка", фирменный стиль</div>
                            </div>
                            <div class="block2pitch" onclick="document.location.href = '#'">
                                <div class="block2pitchpic"></div>
                                <div class="block2pitchdate">21.11.11</div>
                                <div class="block2pitchprice">21000 Р.-</div>
                                <div class="block2pitchdescr">Лого для ООО "Громпушка", фирменный стиль</div>
                            </div>
                            <div class="block2pitchdark" onclick="document.location.href = '#'">
                                <div class="block2pitchpic"></div>
                                <div class="block2pitchdate">3 часа 25 мин</div>
                                <div class="block2pitchprice">25000 Р.-</div>
                                <div class="block2pitchdescr">Лого для ООО "Громпушка", фирменный стиль, баннер, сайт, футболка</div>
                            </div>
                            <div class="block2pitch" onclick="document.location.href = '#'">
                                <div class="block2pitchpic"></div>
                                <div class="block2pitchdate">21.11.11</div>
                                <div class="block2pitchprice">21000 Р.-</div>
                                <div class="block2pitchdescr">Лого для ООО "Громпушка", фирменный стиль</div>
                            </div>
                            <div class="block2pitchdark" onclick="document.location.href = '#'">
                                <div class="block2pitchpic"></div>
                                <div class="block2pitchdate">21.11.11</div>
                                <div class="block2pitchprice">21000 Р.-</div>
                                <div class="block2pitchdescr">Лого для ООО "Громпушка", фирменный стиль</div>
                            </div>
                            <div class="block2pitch" onclick="document.location.href = '#'">
                                <div class="block2pitchpic"></div>
                                <div class="block2pitchdate">21.11.11</div>
                                <div class="block2pitchprice">21000 Р.-</div>
                                <div class="block2pitchdescr">Лого для ООО "Громпушка", фирменный стиль</div>
                            </div>
                            <div class="block2pitchdark" onclick="document.location.href = '#'">
                                <div class="block2pitchpic"></div>
                                <div class="block2pitchdate">21.11.11</div>
                                <div class="block2pitchprice">21000 Р.-</div>
                                <div class="block2pitchdescr">Лого для ООО "Громпушка", фирменный стиль</div>
                            </div>
                        </div>
                    </div-->
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
                        <?php if($user->facebook_uid == ''):?>
                        <span style="display: none;position:absolute;top:396px;left:79px; width:118px;" id="file-uploader-demo1"></span>
                        <?php endif;?>
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
                    <!--section>
                        <h1 class="separator">ЗАКРЫТАЯ ИНФОРМАЦИЯ</h1>
                        <div id="fieldblock2">
                            <div class="fieldleft"><input placeholder="Имя"></div>
                            <div><input placeholder="Фамилия"></div>
                            <div class="fieldwide"><input placeholder="Адрес"></div>
                            <div class="fieldleft"><input placeholder="Страна"></div>
                            <div><input placeholder="Индекс"></div>
                            <div class="fieldleft"><input placeholder="Телефон"></div>
                            <div><input placeholder="Вэб-сайт"></div>
                        </div>
                    </section-->
                    <section style="height: 230px;">
                        <h1 class="separator-flag">НАСТРОЙКИ</h1>
                        <div id="fieldblock3">
                            <?php if($passwordInfo != false):?>
                            <p class="regular"><?=$passwordInfo?></p>
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
                            <!--div class="fieldleft"><label><input type="checkbox" name="asas">важные изменения на сайте</label></div-->
                            <div class="fieldleft"><label class="regular" style="font-weight: normal"><input style="margin-top:0" type="checkbox" name="email_newpitchonce" <?php if($user->email_newpitchonce): echo 'checked'; endif;?>>о новых питчах раз в день</label></div>
                            <div class="" style="margin-bottom: 4px;"><label class="regular" style="font-weight: normal"><input style="margin-top:0" type="checkbox" name="email_newsolonce" <?php if($user->email_newsolonce): echo 'checked'; endif;?>>о новых решениях к моему питчу<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;раз в день</label></div>
                            <div class="fieldleft"><label class="regular" style="font-weight: normal"><input style="margin-top:0" type="checkbox" name="email_newpitch" <?php if($user->email_newpitch): echo 'checked'; endif;?>>о новых питчах сразу, как они<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;публикуются на сайте</label></div>
                            <div class="" style="margin-bottom: 14px;"><label class="regular" style="font-weight: normal"><input style="margin-top:0" type="checkbox" name="email_newsol" <?php if($user->email_newsol): echo 'checked'; endif;?>>о новых решениях к моему питчу<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;сразу, как только их выкладывают на сайт</label></div>
                            <div class="fieldleft"><label class="regular" style="font-weight: normal"><input style="margin-top:0" type="checkbox" name="email_newcomments" <?php if($user->email_newcomments): echo 'checked'; endif;?>>комментарии к моим решениям</label></div>
                            <div class=""><label class="regular" style="font-weight: normal"><input style="margin-top:0" type="checkbox" name="email_digest" <?php if($user->email_digest): echo 'checked'; endif;?>>дайджест новостей (1 раз в 3 недели)</label></div>

                        </div>
                    </section>

                    <div id="sendbuttonbox"><input type="submit" class="button" style="width: 140px" value="Сохранить"></div>
                </form>
                <div id="popup-final-step" class="popup-final-step" style="display:none;">
                    <h3 style="text-transform:uppercase;font-family: RodeoC; margin-top: 140px;margin-left: 110px;font-size:28px;text-shadow: -1px 1px 2px white;margin-bottom: 30px;">Хотите удалить аккаунт?</h3>
                    <div style="margin-bottom: 30px;">• Если вам надоела рассылка, пожалуйста, просто отпишитесь<br> от нее в один клик <a href="/users/unsubscribe?token=<?=base64_encode($this->session->read('user.id'))?>">здесь.</a><br></div>
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


<?=$this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array('/main2.css', '/pitches2.css', '/edit','/view', '/messages12', '/pitches12', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css',), array('inline' => false))?>