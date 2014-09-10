<?=$this->html->style(array('/firstpitch'), array('inline' => false)) ?>

<div class="wrapper">

    <?= $this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header')) ?>
    <div id="top-logo-oneclick"></div>
    <div class="middle">
        <div class="middle_inner_oneclick">
            <div class="content group">
                <div id="ap_content_top">
                    <div id="ap_content_top_l">
                        <h1 class="head">Создай питч на логотип в один клик, остальное мы сделаем за вас.</h1>
                        <label id="phone">Номер телефона</label>
                        <input name="phone" value="" class="input-phone" /> <span class="and_phone">и</span> <a id="fastpitch" class="button third" style="color:#fff;cursor:pointer;">СОЗДАТЬ &laquo;ЛОГОТИП В ОДИН КЛИК&raquo;</a>

                        <h1>Что включено?</h1>

                        <ul class="oneclick">
                            <li>гонорар дизайнеру 14 000р.-</li>
                            <li>опция <a href="">&laquo;Заполнить бриф&raquo;</a></li>
                            <li>опция <a href="">&laquo;Экспертное мнение&raquo;</a></li>
                            <li>опция <a href="">&laquo;Прокачать бриф&raquo;</a> в подарок!</li>
                            <li>стоимость кейса &laquo;Логотип в один клик&raquo; 19600 рублей, что на 2530 рублей дешевле, как если бы вы создавали аналогичный проект обычным способом.</li>
                        </ul>
                        <br /><br />
                        <span>Запустить питч на GoDesigner стало предельно просто: оставьте свой номер телефона и оплатите его, остальное мы сделаем за вас.
                            В течении дня мы свяжемся с вами, на основе интервью сами составим тех. задание, и опубликуем проект на сайте.</span>

                        <h1>Легко и выгдно!</h1>
                        <span>С нашим предложением вы экономите не только 2 часа на заполнение брифа, но и 2530 рублей. Мы подобрали для вас оптимальное решение и самый популярный набор опций, чтобы обеспечить пул специалистов и подарить незабываемый опыт работы с GoDesigner.</span>
                    </div>
                </div>
            </div><!-- /content -->		
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->			
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?= $this->html->script(array('pages/fastpitch.js'), array('inline' => false)) ?>