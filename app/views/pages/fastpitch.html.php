<?= $this->html->style(['/fastpitch'], ['inline' => false]) ?>

<div class="wrapper">

    <?= $this->view()->render(['element' => 'header'], ['logo' => 'logo', 'header' => 'header']) ?>
    <div id="top-logo-oneclick"></div>
    <div class="lp-fast-pitch-wrapper">
        <div class="lp-autocenter">
            <div class="content group">
                <div id="ap_content_top">
                    <div id="ap_content_top_l">
                        <h1 class="fastpitch-main-header">Как это работает</h1>
                        <div class="fastpitch-step1">
                            <h2 class="fastpitch-secondary-header">Оставьте номер телефона <span>*</span></h2>
                            <span class="plus">+</span> <input name="phone" value="" class="input-phone" placeholder="7 911 123 45 67" />
                            <div class="clear" style="clear:both;"></div>
                            <p>Мы свяжемся с вами для интервью, на основе которого сами создадим тех. задание для дизайнеров.</p>
                        </div>

                        <div class="fastpitch-step2">
                            <h2 class="fastpitch-secondary-header">Выберите удобное время</h2>
                            <ul class="date">
                                <?php
                                $x = 0;
                                foreach ($allowTime as $i => $v):
                                    $x++;
                                    if ($x < 6):
                                        ?>
                                        <li>
                                            <label><input <?php if ($x==1): echo 'checked="checked"'; endif;?> id="time" name="time" data-date="<?= $i ?>" type="radio"><?= $v ?></label>
                                        </li>
                                        <?php unset($allowTime[$i]);
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
                                if (isset($allowTime)):
                                foreach ($allowTime as $i => $v): ?>
                                    <li data-num="<?php echo $j; ?>">
                                        <label><input id="time" name="time" data-date="<?= $i ?>" type="radio"><?= $v ?></label>
                                    </li>
                                    <?php
                                    $j += 1;
                                    if ($j == 5):
                                        $j = 1;
                                    endif;
                                endforeach;
                                    endif;?>
                            </ul>
                            <div class="clear" style="clear:both;"></div>
                            <p>Мы наберем вас в указанный час. Интервью займет примерно 40 минут.</p>
                        </div>

                        <div class="fastpitch-step3">
                            <h2 class="fastpitch-secondary-header">Оплатите 19 800 рублей,<br> остальное мы сделаем за вас!</h2>
                            <p>Мы подобрали для вас самый популярный набор опций, чтобы обеспечить пул хороших дизайнеров и&nbsp;подарить незабываемый опыт работы :</p>
                            <a class="fastpitch button third" style="margin-top: 30px;color:#fff;cursor:pointer;">Оплатить 19 800 р.  и запустить проект</a>
                            <table>
                                <tr height="22"><td width="160">гонорар дизайнеру</td><td>14000</td></tr>
                                <tr height="22"><td><a class="tablelink" href="https://godesigner.ru/answers/view/79" target="_blank">гарантировать проект</a></td><td>950</td></tr>
                                <tr height="22"><td><a class="tablelink" href="https://godesigner.ru/answers/view/68" target="_blank">заполнить бриф</a></td><td>2750</td></tr>
                                <tr height="22"><td><a class="tablelink" href="https://godesigner.ru/answers/view/67" target="_blank">прокачать проект</a></td><td>1000</td></tr>
                                <tr height="22"><td><a class="tablelink" href="https://godesigner.ru/answers/view/66" target="_blank">экспертное мнение</a></td><td>1500</td></tr>
                                <tr height="22"><td>сбор сервиса</td><td>29,5%</td></tr>
                                <tr height="22"><td>скидка</td><td><span class="table-left-price">-4530</span></td></tr>
                                <tr height="31"><td colspan="2"></td></tr>
                                <tr><td class="result">ИТОГО:</td><td class="result"><span class="table-left">19800р</span></td></tr>
                            </table>
                        </div>

                        <div class="fastpitch-line"></div>

                        <div class="fastpitch-bottom">
                            <h1 class="fastpitch-main-header second-part" style="margin-left: 0; margin-top: 20px; margin-bottom: 35px;">Что включено в 19800 р.-</h1>
                            <p style="width: 640px; color: #666;">С нашим предложением вы экономите не только 2 часа на заполнение брифа,<br/>но и деньги.  Стоимость кейса «Логотип в один клик» — 19800 рублей, что на 4530  дешевле, как если бы вы создавали аналогичный проект обычным способом. </p>
                            <a class="fastpitch button third" style="margin-left: -2px;margin-top: 30px;color:#fff;cursor:pointer;">запустить проект на логотип в один клик</a>
                            <ul>
                                <li>гонорар дизайнеру 14 000 р.</li>
                                <li><a class="" href="https://godesigner.ru/answers/view/68" target="_blank">«Заполнить бриф»</a> 2750 р.</li>
                                <li><a class="" href="https://godesigner.ru/answers/view/79" target="_blank">«Гарантировать проект»</a> 950 р.</li>
                                <li><a class="" href="https://godesigner.ru/answers/view/67" target="_blank">«Прокачать проект»</a> 1000 р.</li>
                                <li><a class="" href="https://godesigner.ru/answers/view/66" target="_blank">«Экспертное мнение»</a> 1500 р.</li>
                                <li>сбор GoDesigner 29,5%</li>
                                <li>скидка 4530 р. !</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- /content -->		
        </div><!-- /middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?= $this->html->style(['/css/pages/fastpitch.css'], ['inline' => false]) ?>
<?= $this->html->script([
    'jquery-plugins/jquery.scrollto.min.js',
    'pages/fastpitch.js',
], ['inline' => false]) ?>