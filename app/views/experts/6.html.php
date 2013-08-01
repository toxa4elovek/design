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
                        <img src="/img/experts/efremov218.jpg" alt="Станислав Ефремов">
                        <div class="about regular">
                            <h2>Станислав Ефремов</h2>
                            <p>Основатель, управляющий партнер и арт-директор креативного агентства nOne.</p>

                            <p>Более 10 лет возглавляет творческое подразделение агентства nOne. Решает креативные задачи в области товарного и территориального брендинга, презентационного дизайна, интерактивных и рекламных коммуникаций  компаний  Вимм-Билль-Данн, Газпромбанк, Intel, Motorola, Северсталь, Росатом, FSC, Трансаэро и многих других. Руководил созданием брендов Смоленска и Воронежа.</p>

                            <p>Придерживается инженерного подхода к решению творческих вопросов без лишней декоративности, но с остроумием. «Стараюсь найти идеальный баланс между функциональностью и красотой, интересами клиента и агентства, — комментирует Станислав. — Каждый, кто входит в нашу команду — это трендсеттер. Я предлагаю клиенту то, к чему придут конкуренты через пару лет».</p>

                            <p>Вдохновляется архитектурой, электронной музыкой, современным дизайном, классическим кино и пинг-понгом — «ни дня без новых впечатлений»!</p>

                            <a class="back" href="/experts">все эксперты</a>
                        </div>
                    </div>
                    <div class="clear" style="height:20px;"></div>

                    <?=$this->view()->render(array('element' => 'experts/expert-faq'), array('questions' => $questions))?>


                </div>
            </div><!-- /content -->

        </div><!-- /middle_inner -->

        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/text', '/howitworks', '/expert-personal'), array('inline' => false))?>