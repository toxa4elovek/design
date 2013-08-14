<div class="wrapper">

<?=$this->view()->render(array('element' => 'header'), array('header' => 'header2'))?>

<input type="hidden" value="<?=$pitch->id?>" id="pitch_id"/>
<input type="hidden" value="" id="addon_id"/>
<input type="hidden" value="<?=$pitch->billed?>" id="billed"/>
<input type="hidden" value="<?=$pitch->published?>" id="published"/>

<aside class="summary-price expanded">
    <h3>Итого:</h3>
    <p class="summary"><strong id="total-tag">0.-</strong></p><!-- .summary -->
    <ul id="check-tag">
    </ul>
    <a href="#" class="show" id="show-check"><span>Подробнее</span></a>
    <a href="#" class="hide" id="hide-check"><span>Скрыть</span></a>
</aside><!-- .summary-price -->

<div class="middle add-pitch" id="step2">

    <div class="main">

        <h1 style="background: url('/img/images/faq.png') no-repeat scroll 55% 0 transparent;	font-family: 'RodeoC', serif;
            font-size: 12px;
            font-style: normal;
            font-variant: normal;
            font-weight: 400;
            height: 38px;
            line-height: 41px;
            text-align: center;
            text-transform: uppercase;margin-bottom:20px;">Дополнительные опции</h1>

        <div class="ribbon">
            <p class="option"><label><input type="checkbox" name="" class="single-check" <?php if(isset($this->_request->query['click']) && $this->_request->query['click'] == 'prolong'): echo 'checked'; endif?> data-option-title="продлить срок" data-option-value="1950" id="prolong-checkbox">Продлить срок</label></p>
            <p class="description">Укажите количество дней, на которое вы хотите продлить питч. Каждый день стоит 1 950 Р.-, из которых<br> 1 000Р.- добавляется в счет гонорара для дизайнера.
                <a href="#" class="second tooltip" title="">(?)</a></p>
            <p class="label <?php if(isset($this->_request->query['click']) && $this->_request->query['click'] == 'prolong'): echo 'unfold'; endif?>" id="prolong-label" style="font:16px/68px "RodeoC",sans-serif">+1950.-</p>
        </div>

        <div>
            <input type="prolong-num" data-option-title="продлить срок" id="sub-prolong" placeholder="1" class="placeholder initial-price" style="display:none;<?php if(isset($this->_request->query['click']) && $this->_request->query['click'] == 'prolong'): echo 'display:block;'; endif?>height: 78px; width: 268px; font-size: 48px; margin-bottom: 15px; margin-top: 2px; margin-left: 24px; border: medium none; box-shadow: 0px 3px 2px rgba(0, 0, 0, 0.2) inset; padding-left: 15px;"/>
        </div>
        <?php if($pitch->brief == '0'): ?>
        <div class="ribbon complete-brief">
            <p class="option"><label><input disabled="disabled" type="checkbox" name="" <?php if(isset($this->_request->query['click']) && $this->_request->query['click'] == 'phonebrief'): echo 'checked'; endif?> class="single-check" data-option-title="Заполнение брифа" data-option-value="750" id="phonebrief">Заполнить бриф</label></p>
            <!--p class="description">Вы можете ознакомиться с примерами заполнения брифа <a href="/docs/<?=$briefExamples[$category->id]?>" target="_blank">тут</a>, или мы заполним его за вас. Оставьте свой № телефона, мы свяжемся с вами для интервью в течении рабочего дня с момента оплаты <a href="#" class="second tooltip" title="Мы работаем пн-пт с 10:00-19:00. Поставив галочку, вы сможете пропустить следующую страницу (или ответить на легкие вопросы) и перейти непосредственно к оплате.">(?)</a></p-->
            <p class="description">Опция недоступна до 13.08.2013</p>
            <p><input type="text" disabled="disabled" id="phonenumber" name="phone-brief" placeholder="+7 XXX XXX XX XX" class="phone" value=""></p>
            <p class="label <?php if(isset($this->_request->query['click']) && $this->_request->query['click'] == 'phonebrief'): echo 'unfold'; endif?>">750.-</p>
        </div>
        <?php endif ?>
        <div class="ribbon">
            <p class="option"><label><input type="checkbox" name="" class="multi-check" data-option-title="экспертное мнение" data-option-value="1000" <?php if(isset($this->_request->query['click']) && $this->_request->query['click'] == 'experts-checkbox'): echo 'checked'; endif?> id="experts-checkbox">Экспертное мнение</label></p>
            <p class="description"><a href="/experts" id="expert-trigger">Наши эксперты</a> с опытом работы в ведущих рекламных агентствах помогут вам с выбором варианта <a href="#" class="second tooltip" title="Эксперт укажет   и прокомментирует 3 лучших решения, которые максимально отвечают на вашу задачу. Вы можете выбрать несколько экспертов и заручиться надёжной поддержкой.">(?)</a></p>
            <p class="label <?php if(isset($this->_request->query['click']) && $this->_request->query['click'] == 'experts-checkbox'): echo 'unfold'; endif?>" id="expert-label">+1000.-</p>
        </div>

        <ul class="experts" <?php if(isset($this->_request->query['click']) && $this->_request->query['click'] == 'experts-checkbox'): echo 'style="display:block"'; endif?>>
            <?php
            $imageArray = array(
                1 => '/img/temp/expert-1.jpg',
                2 => '/img/temp/expert-2.jpg',
                3 => '/img/jara_174.png',
                4 => '/img/temp/expert-4.jpg',
                5 => '/img/experts/nesterenko174.jpg',
                6 => '/img/experts/efremov174.jpg',
                7 => '/img/experts/percia_174.png',
                8 => '/img/experts/makarov_dmitry_174.png',
            );
            $i = 0;
            foreach($experts as $expert):
                if(in_array($expert->id, unserialize($pitch->{'expert-ids'}))) {
                    continue;
                }

                ?>
                <li>
                    <a href="/experts/view/<?=$expert->id?>" target="_blank" class="photo"><img src="<?=$imageArray[$expert->id]?>" alt="<?=$expert->name?>"></a><!-- .photo -->
                    <p class="select"><input type="checkbox" name="" <?php if($i == 0 && isset($this->_request->query['click']) && $this->_request->query['click'] == 'experts-checkbox'): echo 'checked'; endif?> class="expert-check" data-id="<?=$expert->id?>" data-option-title="экспертное мнение" data-option-value="<?=$expert->price?>"></p><!-- .select -->
                    <dl>
                        <dt><strong><a href="/experts/view/<?=$expert->id?>" target="_blank"><?=$expert->name?></a></strong></dt>
                        <dd><a href="/experts/view/<?=$expert->id?>" target="_blank"><?=$expert->spec?></a></dd>
                    </dl>
                </li>
                <?php
            $i ++;
            endforeach?>
        </ul><!-- .experts -->

        <!--div class="ribbon">
            <p class="option"><label><input type="checkbox" name="" class="single-check" data-option-title="Email рассылка" data-option-value="1000">Email рассылка</label></p>
            <p class="description">Увеличить число креативщиков, дизайнеров или копирайтеров с помощью рассылки по email <a href="#" class="second">(?)</a></p>
            <p class="label">+1000.-</p>
        </div-->

        <p class="submit">
            <input type="submit" value="Продолжить" id="next" class="button steps-link" data-step="3">
        </p><!-- .submit -->

    </div><!-- .main -->

</div><!-- .middle -->


    <div class="middle add-pitch" style="display:none;" id="step3">

        <div class="main">
            <div style="height:800px"><p>
                <h1>выберите способ оплаты</h1>
                <div class="g_line"></div>
                <div id="P_card">
                    <table>
                        <tr>
                            <td>
                                <input type="radio" name="1" class="rb1" data-pay="online" checked>
                            </td>
                            <td>
                                <img src="/img/s3_card.png" alt="">
                            </td>
                            <td class="s3_text">
                                Пластиковая карта ВИЗА, МАСТЕРКАРД<br/>(VISA, MASTERCARD)
                            </td>
                            <td>
                                <form action="https://pay.masterbank.ru/acquiring" method="post">
                                    <input type="HIDDEN" value="" name="ORDER" id="order-id">
                                    <input type="HIDDEN" value="" name="AMOUNT" id="order-total">
                                    <input type="HIDDEN" value="" name="TIMESTAMP" id="order-timestamp">
                                    <input type="HIDDEN" value="" NAME="SIGN" id="order-sign">
                                    <input type="HIDDEN" value="http://godesigner.ru/users/mypitches" name="MERCH_URL">
                                    <input type="HIDDEN" value="71846655" name="TERMINAL">
                                    <input type="submit" id="paybutton" value="продолжить оплату" class="button" >
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"><div class="g_line"><i>или</i></div></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="radio" name="1" class="rb1" data-pay="offline">
                            </td>
                            <td class="s3_h">
                                <img src="/img/s3_rsh.png" alt="">
                            </td>
                            <td class="s3_text">
                                Перевод  на расчетный счёт<br/>(Безналичный платеж через банк)
                            </td>
                            <td></td>
                        </tr>
                    </table>
                    <div id="s3_kv" style="display:none;">
                        <table>
                            <tr>
                                <td width="25px;"><img src="/img/s3_hz.png" alt=""></td>
                                <td colspan="3">
                                    <p><a id="pdf-link" href="#" target="_blank">Скачайте счёт на оплату</a> и оплатите его. С помощью него вы можете сделать безналичный перевод через банк.</p><br/>
                                    <p>Мы активируем ваш питч на сайте в течение рабочего дня после поступления денег, и тогда он появится в <a href="/pitches">общем списке</a>. Пока вы можете просмотреть ваш питч в <a href="/users/mypitches">личном кабинете</a>.</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="g_line"></div>
                <p class="submit">
                    <input type="submit" value="Назад" id="prev" class="button steps-link" data-step="2">
                </p><!-- .submit -->

                </div>
            </form>
        </div><!-- .main -->

    </div><!-- .middle -->

<?=$this->html->script(array('jquery-ui-1.8.17.custom.min.js', 'pitches/addon.js?' . mt_rand(100, 999), 'jquery.numeric','jquery.iframe-transport.js', 'jquery.fileupload.js', 'jquery.simplemodal-1.4.2.js', 'jquery.tooltip.js', 'popup.js', 'jquery.damnUploader.js'), array('inline' => false))?>
<?= $this->html->style(array('/brief', '/step3'), array('inline' => false))?>