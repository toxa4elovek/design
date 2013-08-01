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
                        <img src="/img/experts/chern.jpg" alt="Чернышев Михаил">
                        <div class="about regular">
                            <h2>Чернышев Михаил</h2>
                            <p>Михаил окончил курс Executive MBA по стратегическому маркетингу в Стокгольмской Школе Экономики. Работал с такими клиентами, как Richemont, Puma, Pernod Ricard, Alcatel, Binatone, Nestle. С апреля 2006 по июнь 2010 года занимал позицию медиа-менеджера, а затем — маркетинг-директора в TELE2 - Россия, а с 2010 перешел на работу в хорватский офис TELE2.</p>

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