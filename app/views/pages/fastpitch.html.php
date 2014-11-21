<?= $this->html->style(array('/fastpitch'), array('inline' => false)) ?>

<div class="wrapper">

    <?= $this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header')) ?>
    <div id="top-logo-oneclick"></div>
    <div class="middle">
        <div class="middle_inner_oneclick">
            <div class="content group">
                <div id="ap_content_top">
                    <div id="ap_content_top_l">
                        <h1>Создай питч на логотип в один клик, остальное мы сделаем за вас.</h1>
                        <label id="phone" class="regular">Оставить номер телефона</label>
                        <input name="phone" value="" class="input-phone" /> <span class="and_phone">и</span>
                        <label id="time-label" class="regular">Выберите удобное время для беседы</label>
                        <ul class="date">
                            <?php
                            $x = 0;
                            foreach ($alllow_time as $i => $v):
                                $x++;
                                if ($x < 4):
                                    ?>
                                    <li>
                                        <label><input id="time" name="time" data-date="<?= $i ?>" type="radio"><?= $v ?></label>
                                    </li>
                                    <?php unset($alllow_time[$i]);
                                else: ?>
                                    <li>
                                        <label><input id="more" name="time" type="radio">Другое время</label>
                                    </li>
                                    <?php
                                    break;
                                endif;
                                ?>
                            <?php endforeach; ?>
                        </ul>
                        <ul class="date-hide">
                        <?php
                        $j = 1;
                        foreach ($alllow_time as $i => $v): ?>
                                <li data-num="<?php echo $j; ?>" <?php if(($j % 3) == 0): echo 'style="padding-right: 30px;"'; else: echo 'style="padding-right: 33px;"'; endif;?>>
                                    <label><input id="time" name="time" data-date="<?= $i ?>" type="radio"><?= $v ?></label>
                                </li>
                        <?php
                            $j += 1;
                            if($j == 5):
                                $j = 1;
                            endif;
                        endforeach; ?>
                        </ul>
                        <a id="fastpitch" class="button third" style="color:#fff;cursor:pointer;">СОЗДАТЬ &laquo;ЛОГОТИП В ОДИН КЛИК&raquo;</a>

                        <h1>Что включено?</h1>

                        <ul class="oneclick regular">
                            <li>гонорар дизайнеру 14 000р.-</li>
                            <li>опция <a href="/answers/view/68" target="_blank">&laquo;Заполнить бриф&raquo;</a></li>
                            <li>опция <a href="/answers/view/66" target="_blank">&laquo;Экспертное мнение&raquo;</a></li>
                            <li>опция <a href="/answers/view/67" target="_blank">&laquo;Прокачать бриф&raquo;</a> в подарок!</li>
                            <li>стоимость кейса &laquo;Логотип в один клик&raquo; 19600 рублей, что на 2530 рублей дешевле, как если бы вы создавали аналогичный проект обычным способом.</li>
                        </ul>
                        <br /><br />
                        <span class="regular">Запустить питч на GoDesigner стало предельно просто: оставьте свой номер телефона и оплатите его, остальное мы сделаем за вас.
                            В течении дня мы свяжемся с вами, на основе интервью сами составим тех. задание, и опубликуем проект на сайте.</span>

                        <h1>Легко и выгодно!</h1>
                        <span class="regular">С нашим предложением вы экономите не только 2 часа на заполнение брифа, но и 2530 рублей. Мы подобрали для вас оптимальное решение и самый популярный набор опций, чтобы обеспечить пул специалистов и подарить незабываемый опыт работы с GoDesigner.</span>
                    </div>
                </div>
            </div><!-- /content -->		
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->			
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?= $this->html->script(array('pages/fastpitch.js'), array('inline' => false)) ?>