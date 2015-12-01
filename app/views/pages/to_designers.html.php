<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner" style="padding-left: 45px; padding-right: 15px;">



            <div class="content group">
                <div class="margins-1">
                    <section class="howitworks">
                        <h1>Дизайнерам</h1>
                    </section>
                    <p class="big-gray">Участвуйте в проекте<br />и Зарабатывайте деньги!</p>
                </div>

                <div class="margins-2">
                    <div class="w100">

                        <ul class="score">
                            <li class="fst">
                                <div>1</div>
                                <h2 class="greyboldheader">Выберите проект</h2>
                            </li>
                            <li>
                                <div>2</div>
                                <h2 class="greyboldheader">Предложите идею</h2>
                            </li>
                            <li>
                                <div>3</div>
                                <h2 class="greyboldheader">Победите и заработайте деньги!</h2>
                            </li>
                        </ul>

                        <div class="clear"></div>

                        <ul class="score" id="img">
                            <li class="fst">
                                <div><p><img src="/img/to_designers/1.gif" alt="" /></p></div>
                            </li>
                            <li>
                                <div><p><img  src="/img/to_designers/2.gif" alt="" /></p></div>
                            </li>
                            <li>
                                <div><p><img  src="/img/to_designers/3.gif" alt="" /></p></div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="clear"></div>


                <div class="margins-2">
                    <div class="w100">
                        <ul class="marsh">
                            <li>
                                <h2 class="greyboldheader">Зарабатывайте наравне с&nbsp;арт-директорами</h2>
                                <p class="regular">Принимайте участие в&nbsp;мозговом штурме наравне с&nbsp;именитыми креативщиками и&nbsp;зарабатывайте, как опытные рекламщики.</p>
                            </li>
                            <li>
                                <h2 class="greyboldheader">Пополняйте портфолио</h2>
                                <p class="regular">GoDesigner —  это уникальный шанс участвовать в&nbsp;интересных проектах и&nbsp;работать для известных брендов. Вы решаете, с&nbsp;кем работать, что дает вам безграничные возможности для отличных решений!</p>
                            </li>
                            <li>
                                <h2 class="greyboldheader">Общайтесь напрямую<br />с заказчиком</h2>
                                <p class="regular">У нас нет менеджеров и&nbsp;модераторов, выкладывайте решения прямо на&nbsp;сайт, комментируйте их и&nbsp;направляйте клиента! Все ваши идеи имеют право на&nbsp;победу.</p>
                            </li>
                            <li class="clear">
                                <h2 class="greyboldheader">У всех одинаковые шансы<br> на&nbsp;победу</h2>
                                <p class="regular">На GoDesigner выигрывает лучший дизайн, и&nbsp;тут не&nbsp;имеет значения ваше имя, резюме, опыт работы или портфолио. Самые удачные творческие идеи не&nbsp;всегда рождаются на&nbsp;Мэдисон Авеню.</p>
                            </li>

                            <li>
                                <h2 class="greyboldheader">Участвуйте чаще</h2>
                                <p class="regular">Если вы не&nbsp;выиграли в&nbsp;этот раз, попытайте счастье в&nbsp;другом проекте: до момента приобретения идеи заказчиком, все идеи являются собственностью автора.</p>
                            </li>

                            <li>
                                <h2 class="greyboldheader">Мы гарантируем оплату</h2>
                                <p class="regular">Недобросовестные заказчики не&nbsp;используют вашу идею, не&nbsp;заплатив за неё. Мы обеспечим надежное вознаграждение победителю за приложенные усилия.</p>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="clear"></div>
                <div class="margins-1">
                    <div class="flag-gray">
                        <p>
                            <a href="/pitches">Выберите проект</a><br />
                            <i>и предложите идеи заказчику</i>
                        </p>
                    </div>
                    <p class="big-gray">На старт – внимание – марш!</p>
                </div>

                <div class="margins-1" style="margin-top: 47px; margin-bottom: 33px;">
                    <section class="howitworks">
                        <h1>faq</h1>
                    </section>
                </div>

                <ul class="faq vp_one" style="margin-left: 35px;">
                    <?php foreach($answers as $answer): ?>
                        <li>
                            <p class="regular" style=""><a href="/answers/view/<?=$answer->id ?>" target="_blank"><?= $answer->title ?></a></p>
                            <div style="background:url(/img/sep.png) repeat-x;height:4px;"></div>
                        </li>
                    <?php endforeach?>
                </ul>

            </div><!-- /content -->

        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/howitworks', '/to_designers'), array('inline' => false))?>