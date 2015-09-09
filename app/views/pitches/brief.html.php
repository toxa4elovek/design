<?php
$briefExamples = array(
    '4' => 'brief_visit_card.pdf',
    '5' => 'brief_visit_card.pdf',
    '9' => 'brief_illustration.pdf',
    '12' => 'brief_illustration.pdf',
    '1' => 'brief_logo.pdf',
    '2' => 'brief_logo.pdf',
    '3' => 'brief_logo.pdf',
    '6' => 'brief_logo.pdf',
    '7' => 'brief_logo.pdf',
    '8' => 'brief_logo.pdf',
    '10' => 'brief_logo.pdf',
    '11' => 'brief_logo.pdf',
    '12' => 'brief_logo.pdf',
);
$specifics = array();

switch ($category->id):
    case 1: $word1 = 'Логотип';
//        $word2 = 'Что вы хотите получить на выходе от дизайнера?  Что должно быть прописано в логотипе?  Кто ваши клиенты/потребители/покупатели?  Где будет это размещаться?';
        break;
    case 2: $word1 = 'Веб-баннер';
//        $word2 = 'Что вы хотите получить на выходе от дизайнера?
//Какую идею вы хотите донести?
//Что должно быть указано?
//Кто ваши клиенты/потребители/покупатели?
//Где будет распространяться?';
        break;
    case 3: $word1 = 'Сайт';
//        $word2 = 'Что вы хотите получить на выходе от дизайнера?
//Какую идею, образ, имидж вы хотите донести?
//Что должно быть указано на главной странице?
//Кто ваши посетители?
//Что должна понять или сделать ваша целевая аудитория, когда у вас будет готово то, зачем вы сюда обратились?';
        break;
    case 4: $word1 = 'Визитка';
//        $word2 = 'Что вы хотите получить на выходе от дизайнера?
//Что должно быть указано на лицевой стороне?
//Кому вы предполагаете их раздавать? Подробно опи';
        break;
    case 5: $word1 = 'Фирменный стиль';
//        $word2 = 'Что вы хотите получить на выходе от дизайнера?
//Что должно быть указано на визитке, бланке, конвертe и т.п.
//Кто ваши клиенты/потребители/покупатели? На кого вы должны повлиять?
//Как вы думаете, почему вас не хватает графического сопровождения и чем оно может помочь?
//Что должна подумать ваша целевая аудитория, когда у вас будет готово то, зачем вы сюда обратились?';
        break;
    case 6: $word1 = 'Страница соц сети';
//        $word2 = 'Что вы хотите получить на выходе от дизайнера?
//Кто ваши посетители?
//Какую идею вы хотите донести?
//Какие ассоциации должно это вызывать?';
        break;
    case 7: $word1 = 'Копирайт';
//        $word2 = 'Что вы хотите получить на выходе от копирайтера?
//Где, в основном, будет использоваться название и слоган?
//Что они должны отражать?
//Чего стоит избегать?';
        break;
    case 8: $word1 = 'Презентация';
//        $word2 = 'Что вы хотите получить на выходе от дизайнера?
//Что должно быть на обложке?
//Кто ваши клиенты/потребители/покупатели? 
//Какие ассоциации должна вызывать печатная продукция?
//Где будет это распространяться?
//Что должно быть указано на каждом из разворотов? ';
        break;
    case 9: $word1 = 'Иллюстрация';
//        $word2 = 'Что вы хотите получить на выходе от дизайнера? 
//Какие виды иллюстрации вы предпочитаете?
//Кто ваши клиенты/потребители/покупатели?
//Где это будет распространяться?
//Какие ассоциации должна вызывать иллюстрация?';
        break;
    case 10: $word1 = 'Другое';
//        $word2 = 'Что вы хотите получить на выходе от дизайнера?
//Кто ваши клиенты/потребители/покупатели?
//Где будет это продаваться?
//Что должна понять или сделать ваша целевая аудитория, когда у вас будет готово то, зачем вы сюда обратились?';
        break;
    case 11: $word1 = 'Упаковка';
//        $word2 = 'Что вы хотите получить на выходе от дизайнера?
//Что должно быть указано на лицевой стороне?
//Кто ваши клиенты/потребители/покупатели?
//Что должна понять или сделать ваша целевая аудитория, когда у вас будет готово то, зачем вы сюда обратились?
//Где будет это продаваться?';
        break;
    case 12: $word1 = 'Реклама';
//        $word2 = 'Что вы хотите получить на выходе от дизайнера?
//Какую информацию необходимо указать?
//Кто ваши клиенты/потребители/покупатели?
//Что должна понять или сделать ваша целевая аудитория, когда у вас будет готово то, зачем вы сюда обратились?
//Где будет это показано?';
        break;
    case 13: $word1 = 'Фирменный стиль и логотип'; break;
endswitch;
$str = ($category->id == 1) ? ' в логотипе' : '';
$word2 = 'Опишите вид деятельности. Что отличает вас от конкурентов?<br>
Кто ваши клиенты/потребители/покупатели?<br>
<br>
Что вы хотите получить на выходе от дизайнера?<br>
Что должно быть прописано' . $str . '?<br>
Где будет это размещаться?';
if($category->id == 7):
    $word2 = 'Опишите вид деятельности. Что отличает вас от конкурентов?<br>
Кто ваши клиенты/потребители/покупатели?<br>
<br>
Где, в основном, будет использоваться название и слоган? <br>Что они должны отражать?
 Чего стоит избегать?<br> Кириллица или латиница? <br>
 Хотите ли вы описательное название («Северо-западный GSM» )<br> или яркое («Мегафон» )? <br>
 Укажите количество букв и слов.
';
endif;
?>

<div class="wrapper">

    <?= $this->view()->render(array('element' => 'header'), array('header' => 'header2')) ?>
    <input type="hidden" id="referal" value="<?= $referal; ?>">
    <input type="hidden" id="referalId" value="<?= $referalId; ?>">
    <aside class="summary-price expanded">
        <h3>Итого:</h3>
        <p class="summary"><strong id="total-tag">0.-</strong></p><!-- .summary -->
        <ul id="check-tag">
        </ul>
        <a href="#" class="show" id="show-check"><span>Подробнее</span></a>
        <a href="#" class="hide" id="hide-check"><span>Скрыть</span></a>
    </aside><!-- .summary-price -->

    <div class="middle add-pitch" id="step1">

        <div class="main" style="padding-top: 35px;">

            <h2><?php if($category->title != 'Фирменный стиль и логотип'): echo $category->title;else: echo 'Фир. стиль и логотип'; endif; ?></h2>

            <ol class="steps">
                <li class="current"><a href="#" class="steps-link" data-step="1">1. Цена</a></li>
                <li><a href="#" class="steps-link" data-step="2">2. Бриф</a></li>
                <li class="last"><a href="#" class="steps-link" data-step="3">3. оплата</a></li>
            </ol><!-- .steps -->

            <?php if ($category->id == 11): ?>
                <div class="groupc">
                    <p>
                        <label>Вид упаковки</label>
                    <ul class="radiooptionssite" style="margin-bottom: 11px;">
                        <li><label><input type="radio" name="package-type" value="0" class="sub-radio specific-group" data-min-value="11800" checked="checked"><span class="radiospan">Этикетка и контрэтикетка (от 11800) <a href="#" style="color:#658FA5;font-size:11px" class="second tooltip" title="Этикетка — графическое или текстовое описание товара, заполняющее одну плоскость бумажной формы, наклейки и т.п. Н-р, э. пива, э. на консервы, конфетная э. и т.п.">(?)</a></span></label></li>
                        <li><label><input type="radio" name="package-type" value="1" class="sub-radio specific-group" data-min-value="22500"><span class="radiospan">Оформление коробки, развёртки, и прочее (от 22500 Р-.) <a href="#" style="color:#658FA5;font-size:11px" class="second tooltip" title="Упаковка — комплекс полиграфической продукции и материалов, в которые упаковывают товар или сырье; графическое или текстовое описание товара, которое подразумевает более одной плоскости. Например, картонная коробка, бутылка, стрейч-пленка и т.п.">(?)</a></span></label></li>
                    </ul>
                    </p>
                </div>
            <?php endif; ?>

            <?php

            function renderNumBox($category) {
                $categoriesWithBox = array(2, 3, 4, 6, 8, 9, 10, 11, 12);
                if (!in_array($category, $categoriesWithBox)) {
                    return '';
                }
                $info = array(
                    2 => array('text' => 'Сколько макетов вам нужно создать? Мы рекомендуем учитывать и адаптации под размеры тоже. '),
                    3 => array('text' => 'Сколько шаблонов страниц необходимо разработать для вашего сайта? Внимание, только дизайн,  без кода HTML', 'mult' => 2000),
                    4 => array('text' => 'Сколько разворотов должно быть в буклете (не учитывать, если проект на флаер или листовку).'),
                    6 => array('text' => 'Сколько страниц нужно создать. Если это серия, то укажите суммарное количество, даже если используется одна идея и стиль.'),
                    8 => array('text' => 'Сколько шаблонов страниц необходимо разработать для вашей презентации?', 'mult' => 700),
                    9 => array('text' => 'Сколько иллюстранций необходимо создать? Если серия, укажите суммарное число работ'),
                    10 => array('text' => 'Сколько макетов вам нужно предоставить?  Если это серия, то укажите суммарное количество'),
                    11 => array('text' => 'Сколько макетов вам нужно предоставить?  Если это серия, то укажите суммарное количество'),
                    12 => array('text' => 'Сколько макетов вам нужно предоставить?  Если это серия, то укажите суммарное количество.'),
                );

                $chosenCategory = $info[$category];
                $text = $chosenCategory['text'];
                $mult = '';
                if (isset($chosenCategory['mult'])) {
                    $mult = 'data-mult="' . $chosenCategory['mult'] . '"';
                }

                $html = '<div class="set-price"><p>
                        <label style="width:260px;">' . $text . '</label>
                        <input placeholder="1" type="text" ' . $mult . ' name="site-sub" id="sub-site" value="1" class="initial-price specific-prop placeholder" style="width:242px;"/>
                    </p></div>';
                return $html;
            }
            ?>

            <?php echo renderNumBox($category->id) ?>

            <?php if ($category->id == 7): ?>
                <div class="groupc">
                    <p>
                        <label>Выберите вид копирайтинга</label>
                        <input type="hidden" id="copybaseminprice" value="<?php echo COPY_BASE_PRICE; ?>"/>
                    </p>
                    <ul class="radiooptionssite" style="margin-bottom: 11px;">
                        <li><label><input type="checkbox" name="first-option" value="0" class="sub-check specific-group" checked="checked" style="vertical-align: top;"><span class="radiospan">Имя / название</span></label></li>
                        <li style="margin-top: 4px;"><label><input type="checkbox" name="second-option" value="1" class="sub-check specific-group"  style="vertical-align: top;"><span class="radiospan">Адрес сайта</span></label></li>
                        <li style="margin-top: 4px;"><label><input type="checkbox" name="third-option" value="2" class="sub-check specific-group"  style="vertical-align: top;"><span class="radiospan">Слоган / лозунг</span></label></li>
                    </ul>
                </div>
            <?php endif; ?>

            <?= $this->view()->render(array('element' => 'newbrief/setprice_block'), array('pitch' => $pitch, 'category' => $category)); ?>

            <div style="margin-top:5px;height:200px;">

                <div style="margin-bottom:40px">
                    <input style="vertical-align: top;margin-top:3px" id="guaranteedTrue" type="radio" name="isGuaranteed" value="1" data-option-title="Гарантированный проект" data-option-value="950">
                    <label for="guaranteedTrue" style="text-shadow: 0 1px 1px #eee;font-size: 29px; color:#658fa5; font-family: 'RodeoC', 'Helvetica Neue';margin-left:10px;">Гарантированный проект&nbsp;&nbsp;&nbsp;+950р.</label>
                    <p class="guaranteeExplanation" id="guaranteedTooltip">Вы гарантируете, что выберете победителя в любом случае, тем самым инициировав до 40% больше решений. Мы выделяем такой проект в списке. <?php if($category->id == 7): echo 'Копирайтеры'; else: 'Дизайнеры'; endif;?> увидят, что проект не останется без победителя, и вы получите больший выбор идей.</p>
                </div>

                <div>
                    <input style="vertical-align: top;margin-top:3px" id="guaranteedFalse" type="radio" name="isGuaranteed" value="0" data-option-title="Гарантированный проект">
                    <label for="guaranteedFalse" style="text-shadow: 0 1px 1px #eee;font-size: 29px; color:#6f6f6f; font-family: 'RodeoC', 'Helvetica Neue';margin-left:10px;">Проект без гарантий&nbsp;&nbsp;&nbsp;0р.</label>
                    <p class="guaranteeExplanation" id="nonguaranteedTooltip" style=" display:none;">При активном взаимодействии с <?php if($category->id == 7): echo 'копирайтерами'; else: 'дизайнерами'; endif;?> вы сможете <a href="/answers/view/71" target="_blank">вернуть деньги, если решения не понравятся</a>. Отсутствие гарантий, однако, спровоцирует меньший интерес к проекту.</p>
                </div>

            </div>

            <?= $this->view()->render(array('element' => 'newbrief/time_block'), compact('category', 'pitch')); ?>

            <h1 style="background: url('/img/images/faq.png') no-repeat scroll 55% 0 transparent;	font-family: 'RodeoC', serif;
                font-size: 12px;
                font-style: normal;
                font-variant: normal;
                font-weight: 400;
                height: 38px;
                line-height: 41px;
                text-align: center;
                text-transform: uppercase;margin-bottom:30px;">Дополнительные опции</h1>
            <script>
                var fillBrief = <?php echo ($this->session->read('fillbrief')) ? 1 : 0; ?>;
                var feeRatesOrig = {low: <?php echo FEE_LOW; ?>, normal: <?php echo FEE_NORMAL; ?>, good: <?php echo FEE_GOOD; ?>};
                var feeRates = {low: <?php echo FEE_LOW; ?>, normal: <?php echo FEE_NORMAL; ?>, good: <?php echo FEE_GOOD; ?>};
            </script>

            <div class="ribbon complete-brief" style="padding-top: 35px; height: 56px; padding-bottom: 0;">
                <p class="option"><label><input type="checkbox"  name="" class="single-check" data-option-title="Заполнение брифа" data-option-value="2750" id="phonebrief">Заполнить бриф</label></p>
                <!--p class="description">Вы можете ознакомиться с примерами заполнения брифа <a href="/answers/view/68" target="_blank">тут</a>. Оставьте свой № телефона, мы свяжемся с вами для интервью в течении рабочего дня с момента оплаты <a href="#" class="second tooltip" title="Мы работаем пн-пт с 10:00-19:00. Поставив галочку, вы сможете пропустить следующую страницу (или ответить на легкие вопросы) и перейти непосредственно к оплате.">(?)</a></p>
                <p><input type="text" id="phonenumber" name="phone-brief" placeholder="+7 XXX XXX XX XX" class="phone" value=""></p-->
                <p class="label" style="text-transform: none;">2750р.</p>
            </div>

            <div class="explanation brief" style="display:none;" id="explanation_brief">
                <p>Оставьте свой номер телефона, и мы свяжемся с вами для интервью
                    в течение рабочего дня с момента оплаты:
                </p>
                <p><input type="text" id="phonenumber" name="phone-brief" placeholder="+7 XXX XXX XX XX" class="phone" value=""></p>
                <p>Наши специалисты знают, как правильно сформулировать ваши ожидания и поставить задачу перед дизайнерами (копирайтерами). Мы убеждены, что хороший бриф — залог эффективной работы. С примерами заполненных брифов можно <a href="/answers/view/68">ознакомиться тут</a>.
                </p>
                <img src="/img/brief/brief.png" alt="Заполнить бриф"/>
            </div>

            <div class="ribbon" style="padding-top: 35px; height: 56px; padding-bottom: 0;">
                <p class="option"><label><input type="checkbox" name="" class="single-check" data-option-title="Скрыть проект" data-option-value="3500" id="hideproject">Скрыть проект</label></p>
                <!--p class="description">Питч станет не виден для поисковых систем, а идеи будут доступны для просмотра только вам и их авторам. Подробнее <a target="_blank" href="http://www.godesigner.ru/answers/view/64">тут</a> <a href="#" class="second tooltip" title="Это идеальная возможность, если вы являетесь посредником, рекламным агентством или не хотите разглашать секретов в маркетинговых целях.">(?)</a></p-->
                <p class="label" style="text-transform: none;">3500р.</p>
            </div>

            <div class="explanation closed" style="margin-top: 0px; display: none; padding-bottom: 50px;" id="explanation_closed">
                <img class="explanation_closed" src="/img/brief/closed.png" alt="" style="">
                <ul class="" style="">
                    <li>Идеально для посредников, рекламных агентств или<br>    для сохранения маркетинговых секретов</li>
                    <li>Проект станет недоступен поисковым системам</li>
                    <li>Участники подпишут «Cоглашение о неразглашении»</li>
                    <li>Идеи будут не доступны для просмотра посторонними</li>
                    <li style="list-style: outside none none; margin-top: 12px; margin-left: 0px;"><a href="http://www.godesigner.ru/answers/view/64" target="_blank">Подробнее тут</a></li>
                </ul>
                <div style="clear:both; font-size: 18px; font-family: OfficinaSansC Book, serif;"></div>
            </div>

            <div class="ribbon" style="padding-top: 35px; height: 56px; padding-bottom: 0;">
                <p class="option"><label><input type="checkbox" name="" class="multi-check" data-option-title="экспертное мнение" data-option-value="1000" id="experts-checkbox">Экспертное мнение</label></p>
                <!--p class="description"><a href="/experts" id="expert-trigger">Наши эксперты</a> с опытом работы в ведущих рекламных агентствах помогут вам с выбором варианта <a href="#" class="second tooltip" title="Эксперт укажет   и прокомментирует 3 лучших решения, которые максимально отвечают на вашу задачу. Вы можете выбрать несколько экспертов и заручиться надёжной поддержкой.">(?)</a></p-->
                <p class="label" style="text-transform: none;" id="expert-label">1000р.</p>
            </div>

            <ul class="experts">
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

                foreach ($experts as $expert): if ($expert->enabled == 0)
                    continue;
                    ?>
                    <li>
                        <a href="/experts/view/<?= $expert->id ?>" target="_blank" class="photo"><img src="<?= $imageArray[$expert->id] ?>" alt="<?= $expert->name ?>"></a><!-- .photo -->
                        <p class="select"><input type="checkbox" name="" class="expert-check" data-id="<?= $expert->id ?>" data-option-title="экспертное мнение" data-option-value="<?= $expert->price ?>"></p><!-- .select -->
                        <dl>
                            <dt><strong><a style="font-family:OfficinaSansC Bold,serif;"  href="/experts/view/<?= $expert->id ?>" target="_blank"><?= $expert->name ?></a></strong></dt>
                            <dd><a style="font-family:OfficinaSansC Book,serif; color:#666666;font-size: 14px" href="/experts/view/<?= $expert->id ?>" target="_blank"><?= $expert->spec ?></a></dd>
                        </dl>
                    </li>
                <?php endforeach ?>
            </ul><!-- .experts -->

            <div class="ribbon" style="padding-top: 35px; height: 56px; padding-bottom: 0;" id="pinned-block">
                <p class="option"><label><input type="checkbox" name="" class="single-check" data-option-title="«Прокачать» проект" data-option-value="1000" id="pinproject">«Прокачать» проект</label></p>
                <!--p class="description">Увеличить количество решений <a href="#" class="second tooltip" title="Вы сможете увеличить количество предложенных вариантов на 15-40%. Для привлечения <?php if($category->id == 7): echo 'копирайтеров'; else: 'дизайнеров'; endif;?> мы используем e-mail рассылку, facebook, vkontakte, twitter, выделение синим цветом в списке и на главной странице">(?)</a></p-->
                <p class="label" style="text-transform: none;">1000р.</p>
            </div>

            <div class="explanation pinned" style="margin-top: 0; padding-bottom: 40px; display: none;" id="explanation_pinned">
                <img class="" src="/img/brief/pinned.png" alt="" style="margin-left: -47px; margin-top: -4px;">
                <p style="margin-top: 40px; margin-left: 0px; width: 480px;">С помощью неё вы сможете увеличить количество решений до 40%.<br>
                Для привлечения дизайнеров мы используем:</p>
                <ul class="ul_pinned" style="padding-top: 0px; margin-top: 22px; margin-left: 15px;">
                    <li style="width: 480px;">социальные сети GoDesigner: <a href="https://www.facebook.com/godesigner.ru" target="_blank">fb,</a> <a href="http://vk.com/godesigner" target="_blank">vk,</a> <a href="https://twitter.com/go_deer" target="_blank">twitter</a> (> 10 000 подписчиков)</li>
                    <li style="width: 480px;">выделение цветом в списке проектов</li>
                    <li style="width: 480px;">отображение на главной странице сайта</li>
                    <li style="list-style: outside none none; margin-left:0;"><a href="http://www.godesigner.ru/answers/view/67" target="_blank">Подробнее тут</a></li>
                </ul>
            </div>

            <p class="brief-example"><a href="/docs/<?= $briefExamples[$category->id] ?>" target="_blank"></a></p><!-- .brief-example -->

            <?= $this->view()->render(array('element' => 'newbrief/ad_block'), compact('pitch')) ?>

            <!--div class="ribbon">
                    <p class="option"><label><input type="checkbox" name="" class="single-check" data-option-title="Email рассылка" data-option-value="1000">Email рассылка</label></p>
                    <p class="description">Увеличить число креативщиков, дизайнеров или копирайтеров с помощью рассылки по email <a href="#" class="second">(?)</a></p>
                    <p class="label">+1000.-</p>
            </div-->

            <div class="ribbon complete-brief"  style="padding-top: 35px; height: 56px; padding-bottom: 0;">
                <p class="option"><label><input type="checkbox" name=""  id="promocodecheck">Промокод</label></p>
                <p class="label"></p>
            </div>

            <div class="explanation promo" style="margin-left: 24px; margin-top: 0; padding-bottom: 0; display: none;" id="explanation_promo">
                <p><input style="height:44px; width:125px;padding-left:16px;padding-right:16px; background: none repeat scroll 0 0 #FFFFFF;box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) inset;font-size:29px;margin-top: 12px;color: #cccccc;" type="text" id="promocode" name="promocode" class="phone placeholder" placeholder="8888" value="<?php echo (isset($promocode)) ? $promocode : ''; ?>"></p>
                <p style="margin-top: 20px;">Промо-код высылается постоянным клиентам, которые успешно завершили проект, а также во время праздников или акций. С его помощью можно прокачать бриф, получить бонус или значительно снизить цену на проект! Об акциях можно узнать из наших <a href="https://www.facebook.com/godesigner.ru" target="_blank">fb,</a> <a href="http://vk.com/godesigner" target="_blank">vk,</a> <a href="https://twitter.com/go_deer" target="_blank">twitter</a>.
                </p>
            </div>

            <p class="submit">
                <input type="submit" value="Далее к заполнению брифа" class="button steps-link" data-step="2">
            </p><!-- .submit -->

        </div><!-- .main -->

    </div><!-- .middle -->

    <!-- second step -->

    <div class="middle add-pitch" style="display:none;" id="step2">

        <div class="main" style="padding-top: 35px;">

            <h2><?php if($category->title != 'Фирменный стиль и логотип'): echo $category->title;else: echo 'Фир. стиль и логотип'; endif; ?></h2>

            <ol class="steps">
                <li><a href="#" class="steps-link" data-step="1">1. Цена</a></li>
                <li class="current"><a href="#" class="steps-link" data-step="2">2. Бриф</a></li>
                <li class="last"><a href="#" class="steps-link" data-step="3">3. оплата</a></li>
            </ol><!-- .steps -->

            <?= $this->view()->render(array('element' => 'newbrief/pitchtitle_block'), compact('pitch', 'category', 'word1')) ?>

            <?= $this->view()->render(array('element' => 'newbrief/description_block'), compact('pitch', 'category', 'word2'))?>

            <?= $this->view()->render(array('element' => 'brief-create/' . $category->id)) ?>

            <div class="groupc" style="margin-top: 34px; margin-bottom: 25px;">
                <label id ="show-types" class="greyboldheader">Выберите вид деятельности</label> 
                <ul id="list-job-type" style="margin-bottom: 20px;"> 
                    <li><label><input type="checkbox" name="job-type[]" value="realty"> Недвижимость / Строительство</label></li> 
                    <li> <label><input type="checkbox" name="job-type[]" value="auto"> Автомобили / Транспорт</label>     </li> 
                    <li> <label><input type="checkbox" name="job-type[]" value="finances"> Финансы / Бизнес</label>     </li>
                    <li> <label><input type="checkbox" name="job-type[]" value="food"> Еда / Напитки</label>     </li> 
                    <li> <label><input type="checkbox" name="job-type[]" value="adv"> Реклама / Коммуникации</label></li> 
                    <li> <label><input type="checkbox" name="job-type[]" value="tourism"> Туризм / Путешествие</label>     </li>
                    <li> <label><input type="checkbox" name="job-type[]" value="sport"> Спорт</label>     </li>
                    <li> <label><input type="checkbox" name="job-type[]" value="sci"> Образование / Наука</label>     </li>
                    <li> <label><input type="checkbox" name="job-type[]" value="fashion"> Красота / Мода</label>     </li>
                    <li> <label><input type="checkbox" name="job-type[]" value="music"> Развлечение / Музыка</label>     </li>
                    <li> <label><input type="checkbox" name="job-type[]" value="culture"> Искусство / Культура</label>     </li>
                    <li> <label><input type="checkbox" name="job-type[]" value="animals"> Животные</label>     </li>
                    <li> <label><input type="checkbox" name="job-type[]" value="children"> Дети</label>     </li>
                    <li> <label><input type="checkbox" name="job-type[]" value="security"> Охрана / Безопасность</label>     </li>
                    <li> <label><input type="checkbox" name="job-type[]" value="health"> Медицина / Здоровье</label>     </li>
                    <li> <label><input type="checkbox" name="job-type[]" value="it"> Компьютеры / IT</label>     </li>
                 </ul>
            </div>
                    <?php if ($category->id != 7): ?><div class="groupc">

                    <?php if ($category->id != 1) : ?>
                        <p><label>Можно ли дополнительно использовать материал из банков с изображениями или шрифтами? <a href="#" class="second tooltip" title="Это даст возможность дизайнерам добиться лучшего результата. Профессионалы из мира рекламы часто прибегают к помощи фото-банков для экономии сил, времени или бюджета.">(?)</a></label></p>
                    <?php else: ?>
                        <p><label>Можно ли дополнительно использовать платные шрифты? <a href="#" class="second tooltip" title="Это даст возможность дизайнерам добиться лучшего результата и неповторимого типографического решения.">(?)</a></label></p>
                    <?php endif ?>
                    <div style="float:left;width:50px;height:44px;padding-top:10px;">
                        <input style="vertical-align: middle" type="radio" name="materials" value="0" checked="checked"/><span class="radiospan">Нет</span>
                    </div>
                    <div style="float:left;width:50px;height:44px;padding-top:10px;">
                        <input style="vertical-align: middle" type="radio" name="materials" value="1" /><span class="radiospan">Да</span>
                    </div>
                    <div style="margin-bottom: 15px;"><input type="text" placeholder="допустимая стоимость одного изображения" style="width: 300px;" name="materials-limit" value=""></div>

            </div>            <?php endif; ?>

            <div class="groupc" style="margin-bottom: 30px; padding-bottom: 0px;">
                <p><label>Дополнительные материалы <a href="#" class="second tooltip" title="Присоедините все материалы, которые могут помочь креативщику. Это могут быть фотографии, приглянувшиеся аналоги, существующие логотипы, технические требования и т.д.">(?)</a></label></p>
                <div id="new-download" style="display:none;">
                    <form class="add-file" action="/pitchfiles/add.json" method="post" id="fileuploadform">
                        <div class="fileinputs">
                            <img style="display:block; height:20px; float:left;" class="fakeinput" src="/img/choosefile.png"/>
                            <span class="fakeinput supplement" id="filename" style="display:block; float: left; height:19px; width: 450px; padding-top: 1px; margin-left:10px;">Файл не выбран</span>
                            <input type="file" name="files" multiple id="fileupload" style="display:block; opacity:0; position:absolute;z-index:5"/>
                        </div>
                        <div class="span5 fileupload-progress fade">
                            <!-- The global progress bar -->
                            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                <div class="bar" style="width:0%;"></div>
                            </div>
                            <!-- The extended global progress information -->
                            <div class="progress-extended">&nbsp;</div>
                        </div>
                    </form>
                </div>

                <iframe id="old-download" src="/pitchfiles/index" seamless style="display:none;width:570px;height:100px;"></iframe>

                <!--p class="add-another-file"><a href="#">+ добавить файл</a></p--><!-- .add-another-file -->
                <ul id="filezone">
                </ul>
                <div style="clear:both"></div>

                <p class="brief-example"><a href="/docs/<?= $briefExamples[$category->id] ?>" target="_blank"></a></p><!-- .brief-example -->
           </div>
           <div class="groupc" style="margin-bottom: 19px; padding-bottom: 13px;">
                <?= $this->view()->render(array('element' => 'newbrief/fileformat'), array('pitch' => $pitch, 'category' => $category)); ?>
            <!--div class="groupc">
                <?php if ($category->id != 7): ?>
                    <textarea name="format-description" cols="30" rows="10" placeholder="Дополнительная информация о файлах: размер, разрешение"></textarea>
                <?php else: ?>
                    <textarea name="format-description" cols="30" rows="10" placeholder="Дополнительная информация о файлах: объём текстов в разделах и прочее"></textarea>
                <?php endif; ?>
            </div-->
            </div>
            <div class="groupc" style="background: none repeat scroll 0% 0% transparent; margin-bottom: 7px;">
                <p>
                    <label class="">Ваш контактный телефон <a href="#" class="second tooltip" title="Мы убедительно просим вас оставить ваш номер телефона для экстренных случаев, и если возникнут вопросы с оплатой.">(?)</a></label>
                </p>
                <div><input type="text" placeholder="" style="width: 400px;" name="phone-brief" value=""></div></div>
            </div></div>
            <div class="tos-container supplement" style="margin-bottom: 20px; position: relative;">
                <label><input type="checkbox" name="tos" style="vertical-align: middle; margin-right: 5px;"/>Я прочитал(а) и выражаю безусловное согласие с условиями настоящего <a target="_blank" href="/docs/dogovor.pdf" style="text-decoration: none;">конкурсного соглашения</a>.</label>
                <?= $this->view()->render(array('element' => 'newbrief/required_star'), array('style' => "position: absolute; top:0;right:0")) ?>
            </div>
            <p class="submit submit-brief">
                <?= $this->view()->render(array('element' => 'newbrief/step2fullbuttons')); ?>
            </p><!-- .submit -->

        </div><!-- .main -->

    </div><!-- .middle -->
    <div class="middle add-pitch" style="display:none;" id="step3">

        <div class="main" style="padding-top: 35px;">

            <h2><?php if($category->title != 'Фирменный стиль и логотип'): echo $category->title;else: echo 'Фир. стиль и логотип'; endif; ?></h2>

            <form action="" method="post">
                <input type="hidden" id="pitch-id" name="id" value=""/>
                <ol class="steps">
                    <li><a href="#" class="steps-link" data-step="1">1. Цена</a></li>
                    <li class=""><a href="#" class="steps-link" data-step="2">2. Бриф</a></li>
                    <li class="last current"><a href="#" class="steps-link" data-step="3">3. оплата</a></li>
                </ol><!-- .steps -->
            </form>
            <?= $this->view()->render(array('element' => 'pitchpay'), array('pitch' => $pitch)); ?>
        </div><!-- .main -->

    </div><!-- .middle -->

</div><!-- .wrapper -->

<div id="loading-overlay" class="popup-final-step" style="display:none;width:353px;text-align:center;text-shadow:none;">
    <div style="margin-top: 15px; margin-bottom:20px; color:#afafaf;font-size:14px"><span id="progressbar">0%</span></div>
    <div id="progressbarimage" style="text-align: left; padding-left: 6px; padding-top: 1px; padding-right: 6px; height: 23px; background: url('/img/indicator_empty.png') repeat scroll 0px 0px transparent; width: 341px;">
        <img id="filler" src="/img/progressfilled.png" style="width:1px" height="22">
    </div>
    <div style="color: rgb(202, 202, 202); font-size: 14px; margin-top: 20px;">Пожалуйста, используйте эту паузу<br> с пользой для здоровья!</div>
</div>

<?= $this->view()->render(array('element' => 'popups/brief_tos')); ?>
<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
<?= $this->html->script(array('jquery-ui-1.11.4.min.js', 'jquery.scrollto.min.js', 'jquery-deparam.js', 'pitches/award_calculator.js', 'pitches/brief.js?' . mt_rand(100, 999), 'jquery.numeric', 'jquery.iframe-transport.js', 'jquery.fileupload.js', 'jquery.simplemodal-1.4.2.js', 'jquery.tooltip.js', 'popup.js', 'jquery.damnUploader.js'), array('inline' => false)) ?>
<?= $this->html->style(array(
    '/css/common/receipt.css',
    '/brief',
    '/step3'
    ), array('inline' => false))?>