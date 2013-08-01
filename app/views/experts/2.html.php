<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner">

            <div class="content group">

                <div class="margins-1 expert-personal">
                    <section>
                        <h1 class="on-flag">наши эксперты</h1>
                    </section>
                    <div class="margins-2">
                        <img src="/img/experts/kojara.jpg" alt="Кожара Сергей">
                        <div class="about regular">
                            <h2>Кожара Сергей</h2>
                            <p>Закончил художественно-графический факультет СПбГПУ. С 2000 по 2006 год работал арт-директором и и.о. креативного директора в рекламном агентстве «БизнесЛинк». С 2006 по 2011 год работал арт-директором в рекламном агентстве «Небо» и вошел в рейтинг  наиболее награждаемых арт-директоров  России. В 2011 году основал Special One Visual Production, который специализируется на фото-пост-продакшене. В рамках Special One  создал неповторимый стиль для матчевых афиш для ФК «Зенит». Сергей работал с брендами: Chupa-Chups, Fazer, Jameson, Балтимор, Балтика, Мегафон, банк "ТРАСТ" и  др. Является финалистом и призером многочисленных фестивалей рекламы.</p>
                            <!--p> Будучи арт-директором креативного агентства <a href="http://www.nebo-ra.net" target="_blank">«Небо»</a>, Сергей попал в рейтинг 15 наиболее творческих пар в России. Основатель <a href="http://jara.specialone.ru" target="_blank">Jara Special One</a>, специализирующегося на ретуши фотографий, он создал неповторимый стиль для матчевых афиш для «Зенит». Сергей также работал с Chupa-Chups, Fazer, Jameson, Балтимор, Балтика, Мегафон, банк "ТРАСТ", является лауреатом самых престижных наград в сфере рекламы.</p-->

                            <a class="back" href="/experts">все эксперты</a>
                        </div>
                    </div>
                    <div class="clear"></div>

                    <?=$this->view()->render(array('element' => 'experts/expert-faq'), array('questions' => $questions))?>

                </div>
            </div><!-- /content -->

        </div><!-- /middle_inner -->

        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/text', '/howitworks', '/expert-personal'), array('inline' => false))?>