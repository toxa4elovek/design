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
                        <img src="/img/experts/pavlov.jpg" alt="Владимир Павлов">
                        <div class="about regular">
                            <h2>Владимир Павлов</h2>
                            <p>Окончил курс окончил курс Executive MBA по стратегическому маркетингу в Британском Открытом Университете. Работает в рекламе с 1996 года. В 1997 году Владимир был назначен руководителем по работе с клиентами в Bates Saatchi & Saatchi Украина. С 1999 по 2004 гг. — был аккаунт директором в Saatchi & Saatchi Estonia. С 2004 по 2011 год возглавлял петербургское рекламное агентство «Небо». Под его руководством, «Небо» стало креативным агентством номер 1 в Петербурге и в 2009 году вошло в TOP 10 российских рекламных агентств (по версии AKAP).
                                В 2011 году возглавил «Родную речь». На сегодняшний день «Родная речь» является одним из самых известных рекламных агентств в России, с 2005 года оно входит в состав Leo Burnett Group.  Владимир Павлов в разное время работал с такими клиентами, как Toyota, Procter & Gamble, Hewlett-Packard, Альфа-банк, банк «ТРАСТ», Vimm-Bill-Dann, Audi, Valio и другие. </p>
                            <!--p>Исполнительный директор «Родная Речь», экс-директор креативного агентства <a href="http://www.nebo-ra.net" target="_blank">«Небо»</a>,. На сегодняшний день «Родная речь» является одним из самых известных рекламных агентств в России, с 2005 года оно входит в состав Leo Burnett Group. Владимир Павлов в разное время работал с такими клиентами, как Toyota, Procter & Gamble, Hewlett-Packard, Valio, банк «ТРАСТ» и другие. Под его руководством, «Небо» стало креативным агентством номер 1 в Петербурге и в 2009 году вошло в TOP 10 российских рекламных агентств (по версии AKAP)</p-->

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