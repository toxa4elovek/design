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
                        <img src="/img/experts/percia_218.png" alt="Валентин Перция">
                        <div class="about regular">
                            <h2>Валентин Перция</h2>
                            <p>Генеральный директор компании <a href="http://blogbrandaid.com/" target="_blank">BrandAid</a> (Москва, Киев). Красный диплом Киевского Высшего Военного Авиационно-Инженерного Училища по специальности матобеспечение АСУ, 1991 год. В рекламе и маркетинге с 1993 года.</p>

                            <p>С 1995 года работа в международных РА (Euro RSCG World Wide, Ark/JWT, Bates Ukraine) на позициях от стратегического планировщика до заместителя директора. В 2000 году создал компанию BrandAid. C 2004 года работает офис в Москве. Специализация – создание и развитие брендов. Компания разработала более 100 новых брендов (Мягков, Сильпо, Винодел и так далее) и продолжает развиваться.</p>

                            <p>Автор книги «Брендинг: Курс молодого бойца» (издательство «Питер», Россия, 2005г.). Соавтор книги «Анатомия бренда» (издательство «Вершина», Россия, Москва, 2007 г.), Соавтор книги «Удвоение продаж» (Издательство «Эксмо», Москва, 2010 г.). Разработчик программы обучения по созданию бренда “Анатомия Бренда”, которую прошло более 2000 человек в пяти странах бывшего СССР за 8 лет.</p>

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