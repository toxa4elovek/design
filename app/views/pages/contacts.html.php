<div class="wrapper">


    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>
    <div class="middle">
        <div class="middle_inner">
            <div class="content group">
                <div id="ap_content_top">
                    <div id="ap_content_top_l">

                        <section class="howitworks">
                            <h1 style="color:#bebebe">Напишите нам</h1>
                        </section>
                        <form action="/pages/contacts" method="post" class="contacts-form">
                            <input class="i1" name="name" style="margin-top:30px;" type="text" placeholder="ВАШЕ ИМЯ" />
                            <input class="i1" name="email" type="text" placeholder="ВАШ EMAIL" />
                            <input class="i1" name="subject" type="text" placeholder="ТЕМА СООБЩЕНИЯ" />
                            <textarea name="message" placeholder="ВАШЕ СООБЩЕНИЕ"></textarea>
                            <input type="submit" style="display: block; margin: 0 auto 20px 168px; width: 200px;color:#FFFFFF;font-size: 12px;text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);" value="Отправить" class="button steps-link" data-step="2">
                        </form>
                        <div class="margins-1">

                            <section class="howitworks">
                                <h1 style="color:#bebebe">Адрес</h1>
                            </section>
                        </div>
                        <div class="margins-2" style="margin-top:20px;">
                            <div class="w100"  style="text-align:center;">
                                <div class="bigletters largest-header">
                                    <a href="mailto:team@godesigner.ru">team@godesigner.ru</a><br/>
                                    +7 (812) 648 2412
                                </div>

                                <div class="fontBookC">
                                    <br>
                                    Юридический адрес<br>
                                    и адрес для документов:<br>
                                    199397, Россия, Санкт-Петербург,<br>
                                    ул. Беринга, д. 27<br><br>

                                    Адрес бухгалтерии, <a href="http://www.godesigner.ru/posts/view/62" target="_blank">Вячеслав Афанасьев</a>:<br>
                                    190000, Россия, Санкт-Петербург,<br>ул. Малая Морская, д. 16, оф. 28
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="ap_content_top_r">
                        <div id="ap_content_r_1" class="regular">
                            <h2 class="greyboldheader">Возникли вопросы?</h2>
                            Вы можете найти ответ в разделе <a href="/answers">«Часто задаваемые вопросы»</a>, или напишите нам сообщение. Мы постараемся вам ответить в течении 24 часов по рабочим дням.
                            <!--a href="#"><img src="/img/ap_r_1_1.gif"></a-->

                        </div>
                        <div id="ap_content_r_2" style="margin-top: 20px;">
                            <h2 class="greyboldheader">Часто задаваемые вопросы</h2>
                            <?=$this->faq->show($questions)?>

                            <a href="/answers" class="av">Все вопросы</a>
                        </div>

                    </div>
                </div>
            </div><!-- /content -->
        </div><!-- /middle_inner -->

        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/contact2', '/howitworks'), array('inline' => false))?>
<?=$this->html->script(array('pages/howitworks.js'), array('inline' => false))?>