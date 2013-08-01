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
                        <img src="/img/experts/fedchenko.jpg" alt="Альберт Федченко">
                        <div class="about regular">
                            <h2>Альберт Федченко</h2>

                            <p>Закончил художественно-графический факультет Кубанского Государственного Университета.</p>

                            <p>Более 10 лет преподает в Кубанском государственном университете на факультете Архитектуры и дизайна. Интерес к творческой части компьютерных технологий проявил еще в эру черно-белых мониторов. Студентом, на заре компьютерной графики России, стал занимать первые места международных конкурсов и фестивалей. На данный момент совмещает преподавательскую деятельность с работой в ведущем дизайн-агентстве Краснодара <a href="http://lafedja.ru" target="_blank">LaFedja</a>.</p>

                            <p>Каждый день просматривает и анализирует cотни новых работ лучших мировых агентств и дизайнеров в области графического дизайна, упаковки и веб-технологий. С появлением Интернета считает возможность самообучения безграничными.</p>

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