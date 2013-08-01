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
                        <img src="/img/experts/nesterenko218.jpg" alt="Максим Нестеренко">
                        <div class="about regular">
                            <h2>Максим Нестеренко</h2>
                            <p>Куратор курса Графического дизайна в Высшей Британской Школе Дизайна.</p>

                            <p>Максим закончил Московский Университет дизайна и технологии и Университет прикладных искусств и дизайна в Вене. Работает в дизайне с 1990 года: в 1998 был старшим арт-директором в рекламном агентстве Leo Burnett,  с 2002 выступал в роли креативного директора в 5 агентствах, в том числе и РА "Приор". Работал с такими брендами как Panasonic, Salamander, Carrier, Aiko, Toshiba, OKI, AIWA.</p>

                            <p>Куратор курса Графического Дизайна на отделении дополнительного профессионального образования и куратор Культурных исследований (Critical & Cultural Studies) в Британской Высшей Школе Дизайна.</p>

                            <p>Участник Международных и Всероссийских художественных выставок с 1988 г. Творческие работы находятся в галереях и частных коллекциях в России, Японии, Австрии, США, Германии и Словакии. Победитель Adobe Europe design сompetition в категории Create (1997 г.). Призёр и номинант столичных рекламных фестивалей. Член жюри ММФР. </p>

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