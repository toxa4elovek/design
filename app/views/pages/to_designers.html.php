<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner" style="padding-left: 45px; padding-right: 15px;">



            <div class="content group">
                <div class="margins-1">
                    <section class="howitworks">
                        <h1>Дизайнерам</h1>
                    </section>
                    <p class="big-gray">Участвуйте в питче<br />и Зарабатывайте деньги!</p>
                </div>

                <div class="margins-2">
                    <div class="w100">

                        <ul class="score">
                            <li class="fst">
                                <div>1</div>
                                <h2 class="greyboldheader">Выберите питч</h2>
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
                                <h2 class="greyboldheader">Зарабатывайте наравне с известными креативщиками</h2>
                                <p class="regular">Вне зависимости от вашего местоположения вы можете принять участие в мозговом штурме брифов наравне с именитыми креативщиками и заработать также, как опытные рекламщики</p>
                            </li>
                            <li>
                                <h2 class="greyboldheader">Пополняйте портфолио</h2>
                                <p class="regular">Go Designer —  это уникальный шанс участвовать в интересных проектах и работать для известных брендов. Только вы решаете, с кем работать, а с кем —  нет, что дает вам безграничные возможности для отличных решений!</p>
                            </li>
                            <li>
                                <h2 class="greyboldheader">Общайтесь напрямую<br />с заказчиком</h2>
                                <p class="regular">У нас нет менеджеров и модераторов, выкладывайте решения прямо на сайт, комментируйте их и направляйте клиента! Все ваши идеи имеют право на победу.</p>
                            </li>
                            <li class="clear">
                                <h2 class="greyboldheader">У всех одинаковые шансы<br> на победу</h2>
                                <p class="regular">На Go Designer выигрывает лучший дизайн, и тут не имеет значения ваше имя, резюме, опыт работы или портфолио. Мы сотрудничаем с теми, кто понимает, что самые удачные творческие идеи далеко не всегда рождаются на Мэдисон Авеню.</p>
                            </li>

                            <li>
                                <h2 class="greyboldheader">Участвуйте чаще</h2>
                                <p class="regular">Не расстраивайтесь, если вы не выиграли в этот раз: вы можете попытать счастье в другом питче с тем же решением: до момента приобретения идеи заказчиком, все решения являются собственностью автора.</p>
                            </li>

                            <li>
                                <h2 class="greyboldheader">Мы гарантируем оплату</h2>
                                <p class="regular">Недобросовестные заказчики не смогут использовать вашу идею, не заплатив за неё. Мы обеспечим надежное вознаграждение победителю за приложенные усилия.</p>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="clear"></div>
                <div class="margins-1">
                    <div class="flag-gray">
                        <p>
                            <a href="/pitches">Выберите питч</a><br />
                            <i>и предложите идеи заказчику</i>
                        </p>
                    </div>
                    <p class="big-gray">На старт - внимание - марш!</p>
                </div>


                <section class="faq">
                    <div class="margins-1">
                        <h1>Часто задаваемые вопросы</h1>
                    </div>
                    <?=$this->faq->show($questions)?>
                    <div>
                        <?=$this->html->link('Все вопросы', 'Answers::index', array('class' => 'more', 'style' => 'margin-left:28px;'));?>
                    </div>
                </section>


            </div><!-- /content -->

        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/howitworks', '/to_designers'), array('inline' => false))?>