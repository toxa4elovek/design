<div class="wrapper">


    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>
    <div class="middle">
        <div class="middle_inner">
            <div class="content group">

                <div id="right_sidebar_help">
                    <div id="r_h_v">

                        <h2>Возникли вопросы?</h2>
                        Если вы&nbsp;не&nbsp;можете найти ответ на&nbsp;свой вопрос&nbsp;&mdash; напишите нам. Мы&nbsp;постараемся ответить вам в&nbsp;течении 24&nbsp;часов по&nbsp;рабочим дням.
                        <?=$this->html->link('<img src="/img/otp_em.jpg">', 'Pages::contacts', array('escape' => false))?>
                    </div>

                </div><!-- /right_sidebar_help -->

                <div id="content_help" style="min-height: 600px;">
                    <section class="howitworks">
                        <h1 class="h2link"><a href="/answers">Помощь</a></h1>
                    </section>
                    <!--div id="content_help_cont">
                        <div id="content_help_seach">
                            <input type="text" class="text"><input type="button" class="button" value="Поиск">

                        </div>
                    </div><!-- /content_help_cont -->

                    <div class="answer regular">

                        <h2><?=$answer->title?></h2>
                        <?php echo $answer->text;?>
                        <!--a href="" class="back">помощь дизайнерам</a-->
                        <?=$this->html->link('все вопросы', 'Answers::index', array('class' => 'back', 'style' => 'margin-left:5px;margin-top:20px;display: block;'))?>

                    </div>

                    <div style="margin-top:20px; margin-bottom:15px; width: 611px; background: url(/img/sep.png) repeat-x scroll 0 0 transparent" class="separator"></div>

                    <section class="faq">
                        <div class="margins-2">
                            <ul>
                                <?php foreach($similar as $answer):?>
                                <li style="text-shadow: -1px 0 0 #FFFFFF; padding-left: 5px;"><a href="/answers/view/<?=$answer->id?>"><?=$answer->title?></a></li>
                                <?php endforeach?>
                            </ul>
                        </div>
                    </section>

                    <!--ul class="menu-quest">

                        <li class="same">
                            <h2>Похожие вопросы</h2>
                            <p><a href="#">Что такое питч?</a></p>
                            <p><a href="#">Как это работает?</a></p>
                            <p><a href="#">Зачем нужно использовать Go Designer для создания дизайна и копирайтинга?</a></p>
                            <p><a href="#">Сколько стоит создать питч?</a></p>

                            <p><a href="#">Кто участвует в питчах и кто выкладывает решения?</a></p>
                        </li>
                        <li class="general">
                            <a href="" class="more"><span>все вопросы</span></a>
                            <h2><a href="">Общие вопросы</a></h2>
                        </li>
                        <li class="client">

                            <a href="" class="more"><span>все вопросы</span></a>
                            <h2><a href="">Помощь заказчикам</a></h2>
                        </li>
                        <li class="designer">
                            <a href="" class="more"><span>все вопросы</span></a>
                            <h2><a href="">Помощь дизайнерам</a></h2>
                        </li>

                        <li class="payment">
                            <a href="" class="more"><span>все вопросы</span></a>
                            <h2><a href="">Оплата и денежные вопросы</a></h2>
                        </li>
                    </ul-->


                </div><!-- /content_help -->


            </div><!-- /content -->
        </div><!-- /middle_inner -->

        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/help', '/howitworks', '/answer'), array('inline' => false))?>