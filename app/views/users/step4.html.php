<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('header' => 'header2'))?>


    <div class="conteiner">
        <section>
            <div class="menu">

                <?=$this->view()->render(array('element' => 'step-menu'))?>

            </div>
        </section>
        <section>
            <div style="margin-left: 50px;">
                <?=$this->view()->render(array('element' => 'complete-process/filtersmenu'), array('link' => ($solution->step == 4) ? 2 : 3))?>
            </div>
            <?php
            if(($solution->pitch->category_id == 7) && ($type == 'client')):?>
                <?=$this->view()->render(array('element' => '/complete-process/stepmenu-designer'), array('solution' => $solution, 'step' => $step, 'type' => $type))?>
            <?php else:?>
                <?=$this->view()->render(array('element' => '/complete-process/stepmenu-designer'), array('solution' => $solution, 'step' => $step, 'type' => $type))?>
            <?php endif?>
        </section>
        <section>
            <div class="center_block"  style="margin:35px 0 0 63px !important">
                <?php if($grade):?>
                <p style="font: normal 28px/1 RodeoC,sans-serif; text-transform:uppercase;text-align:center;color: #666666;text-shadow:-1px 0 0 #FFFFFF;margin-bottom:10px;margin-top:10px">та-дам!</p>
                <p style="font: normal 28px/1 RodeoC,sans-serif; text-transform:uppercase;text-align:center;color: #FF584D;text-shadow:-1px 0 0 #FFFFFF;margin-bottom:10px">поздравляем, питч завершен!</p>
                <p style="font: normal 28px/1 RodeoC,sans-serif; text-transform:uppercase;text-align:center;color: #666666;text-shadow:-1px 0 0 #FFFFFF;margin-bottom:10px">и мы будем рады видеть вас</p>
                <p style="font: normal 28px/1 RodeoC,sans-serif; text-transform:uppercase;text-align:center;color: #666666;text-shadow:-1px 0 0 #FFFFFF;margin-bottom:10px">снова</p>
                    <?php if($type == 'designer'):?>
                        <p style="margin-top: 40px; padding-top: 0; font: 16px/20px 'Arial', sans-serif;  display: block; margin-left: 0; text-align: center;" class="regular">Деньги поступят вам на счет<br> в течение 5 рабочих дней.</p>
                    <?php endif?>
                <?php else:?>
                <?php if($type == 'designer'):?>
                <span class="regular">Ура! Питч завершен. Мы поздравляем вас с отличной работой! Деньги поступят вам на счет в течение 5 рабочих дней. Пожалуйста, загрузите эскизы в экранном разрешении (RGB, 72 dpi, JPG, GIF, PDF). Если у вас несколько документов, заархивируйте их в один ZIP файл. У заказчика есть право на внесение 3 поправок до запроса исходных файлов. Если для этого вам потребуется более 24 часов, пожалуйста, сообщите об в комментариях. Успехов!</span>
                <?php elseif($type == 'client') :?>
                <span class="regular">Ура! Питч завершен. Мы поздравляем вас с отличной работой! Пожалуйста, оцените работу дизайнера и нашего сервиса. Мы будем рады видеть вас снова!</span>
                <?php endif;?>
                <div class="clr"></div>


                <ul class="logo-properties sliderul" data-name="logo-properties" style="margin-top:40px;">
                    <li style="height:80px; width:600px;float:left;">
                        <?php if($type == 'designer'):?>
                        <p class="steptitle">Общее впечатление от заказчика</p>
                        <?php elseif($type == 'client') :?>
                            <?php if($solution->pitch->category_id != 7):?>
                                <p class="steptitle">Общее впечатление от дизайнера</p>
                            <?php else:?>
                                <p class="steptitle">Общее впечатление от копирайтера</p>
                            <?php endif?>
                        <?php else:?>
                            <p class="steptitle">Вы используете аккаунт с правами админа</p>
                        <?php endif?>
                        <span class="stepspan" style="margin-left: 20px;font:10px/1 'Arial',sans-serif">0</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">1</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">2</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">3</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">4</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">5</span>
                        <div class="clr"></div>
                        <span class="slider-wrapper"><span class="slider" data-field="partner_rating"></span></span>
                        <div class="clr"></div>
                    </li>
                    <li style="height:80px; width:600px;float:left;">
                        <p class="steptitle">Насколько вы удовлетворены работой в целом?</p>
                        <span class="stepspan" style="margin-left: 20px;font:10px/1 'Arial',sans-serif">0</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">1</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">2</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">3</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">4</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">5</span>
                        <div class="clr"></div>
                        <span class="slider-wrapper"><span class="slider" data-field="work_rating"></span></span>
                        <div class="clr"></div>
                    </li>
                    <li style="height:80px; width:600px;float:left;">
                        <p class="steptitle">Как бы вы оценили godesigner.ru</p>

                        <span class="stepspan" style="margin-left: 20px;font:10px/1 'Arial',sans-serif">0</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">1</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">2</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">3</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">4</span>
                        <span class="stepspan" style="font:10px/1 'Arial',sans-serif">5</span>
                        <div class="clr"></div>
                        <span class="slider-wrapper"><span class="slider" data-field="site_rating"></span></span>
                        <div class="clr"></div>
                    </li>
                </ul>
                <h1 class="greyboldheader">Помогите Go Designer стать лучше, оставьте свой отзыв:</h1>
                <br />
                <form method="post" action="/users/step4/<?=$solution->id?>" id="afterPitchCommentForm">
                    <div class="comment" style="margin-left:0px;margin-top:50px">
                        <h4>Откуда вы о нас узнали?</h4>
                            <input type="text" name="referer" style="margin:10px 0 0 0;"/>
                    </div>

                    <div class="comment" style="margin-left:0px;margin-top:50px">
                        <h4>Оставьте комментарий пожалуйста</h4>
                            <textarea name="text" style="margin:10px 0 0 0;"></textarea>
                    </div>

                    <div class="buttons">
                        <?php //if($this->user->isAdmin()):
                        if(1 == 1):
                        ?>
                        <div class="continue spanned" style="margin-bottom:10px;">
                            <a href="">
                                <input id="submitgrade" type="submit" style="opacity: 0; margin: 0pt; width: 160px; right: 400px; position: absolute; height:60px;display:block">
                                <img src="/img/proceed.png" class="fakeclick"/><br />
                                <span class="fakeclick">Завершить</span>
                            </a>
                        </div>
                        <?php endif?>
                    </div>

                    <input type="hidden" value="5" name="partner_rating">
                    <input type="hidden" value="5" name="work_rating">
                    <input type="hidden" value="5" name="site_rating">
                    <input type="hidden" value="<?=$type?>" name="type">
                </form>
                <?php endif;?>
            </div>
            <?=$this->view()->render(array('element' => '/complete-process/rightblock'), array('solution' => $solution, 'type' => $type))?>
            <div class="clr"></div>
        </section>
    </div>
    <div class="conteiner-bottom"></div>
</div><!-- .wrapper -->
<?=$this->html->script(array('jquery-ui-1.8.17.custom.min.js', 'users/step4'), array('inline' => false))?>
<?=$this->html->style(array('/view', '/messages12', '/pitches12', '/pitches2', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css',), array('inline' => false))?>