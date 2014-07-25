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

                                <img src="/img/map.png" alt="" style="margin-left:auto;
                                margin-right:auto; display:block; margin-top: 20px;">
                                <a class="regular" target="_blank" style="text-align: center;" href="http://maps.google.ru/maps?client=safari&amp;rls=en&amp;oe=UTF-8&amp;redir_esc=&amp;q=инженерная+7&amp;um=1&amp;ie=UTF-8&amp;hq=&amp;hnear=0x469630e01f3c5137:0x32c7e2d0479f34ce,Инженерная+ул.,+7,+Санкт-Петербург&amp;gl=ru&amp;ei=iQljT_LCF5L44QStgf34Bw&amp;sa=X&amp;oi=geocode_result&amp;ct=image&amp;resnum=1&amp;ved=0CCIQ8gEwAA">карта проезда</a>

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
                        <div style="margin-top:85px; position:relative; float:left;" class="regular">
                            Наш офис:<br/>Россия, Санкт-Петербург,<br/>Инженерная 7, 3 этаж
                            <br/><br/>
                            Юридический адрес<br/>
                            и адрес для документов:<br/>
                            199397, Россия, Санкт-Петербург,<br/>ул. Беринга, д. 27
                        </div>
                        <!--div id="ap_content_r_3">
                            <h2>Популярные разделы</h2>
                            <ul>
                                <li><a href="#">Помощь дизайнерам</a></li>
                                <li><a href="#">Помощь заказщчикам</a></li>

                                <li><a href="#">Помощь при оплате ( для заказчиков)</a></li>
                                <li><a href="#">Перевод средств дизайнерам</a></li>
                            </ul>
                        </div-->
                    </div>
                </div>
            </div><!-- /content -->
        </div><!-- /middle_inner -->

        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/contact2', '/howitworks'), array('inline' => false))?>
<?=$this->html->script(array('pages/howitworks.js'), array('inline' => false))?>