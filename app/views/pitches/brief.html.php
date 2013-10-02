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
$specifics  = array();

switch($category->id):
    case 1: $word1 = 'Логотип'; $word2 = 'Что вы хотите получить на выходе от дизайнера?  Что должно быть прописано в логотипе?  Кто ваши клиенты/потребители/покупатели?  Где будет это размещаться?'; break;
    case 2: $word1 = 'Веб-баннер'; $word2 = 'Что вы хотите получить на выходе от дизайнера?
Какую идею вы хотите донести?
Что должно быть указано?
Кто ваши клиенты/потребители/покупатели?
Где будет распространяться?'; break;
    case 3: $word1 = 'Сайт'; $word2 = 'Что вы хотите получить на выходе от дизайнера?
Какую идею, образ, имидж вы хотите донести?
Что должно быть указано на главной странице?
Кто ваши посетители?
Что должна понять или сделать ваша целевая аудитория, когда у вас будет готово то, зачем вы сюда обратились?'; break;
    case 4: $word1 = 'Визитка'; $word2 = 'Что вы хотите получить на выходе от дизайнера?
Что должно быть указано на лицевой стороне?
Кому вы предполагаете их раздавать? Подробно опи'; break;
    case 5: $word1 = 'Фирменный стиль'; $word2 = 'Что вы хотите получить на выходе от дизайнера?
Что должно быть указано на визитке, бланке, конвертe и т.п.
Кто ваши клиенты/потребители/покупатели? На кого вы должны повлиять?
Как вы думаете, почему вас не хватает графического сопровождения и чем оно может помочь?
Что должна подумать ваша целевая аудитория, когда у вас будет готово то, зачем вы сюда обратились?'; break;
    case 6: $word1 = 'Страница соц сети'; $word2 = 'Что вы хотите получить на выходе от дизайнера?
Кто ваши посетители?
Какую идею вы хотите донести?
Какие ассоциации должно это вызывать?'; break;
    case 7: $word1 = 'Копирайт'; $word2 = 'Что вы хотите получить на выходе от копирайтера?
Где, в основном, будет использоваться название и слоган?
Что они должны отражать?
Чего стоит избегать?'; break;
    case 8: $word1 = 'Буклет'; $word2 = 'Что вы хотите получить на выходе от дизайнера?
Что должно быть на обложке?
Кто ваши клиенты/потребители/покупатели? 
Какие ассоциации должна вызывать печатная продукция?
Где будет это распространяться?
Что должно быть указано на каждом из разворотов? '; break;
    case 9: $word1 = 'Иллюстрация'; $word2 = 'Что вы хотите получить на выходе от дизайнера? 
Какие виды иллюстрации вы предпочитаете?
Кто ваши клиенты/потребители/покупатели?
Где это будет распространяться?
Какие ассоциации должна вызывать иллюстрация?'; break;
    case 10: $word1 = 'Другое'; $word2 = 'Что вы хотите получить на выходе от дизайнера?
Кто ваши клиенты/потребители/покупатели?
Где будет это продаваться?
Что должна понять или сделать ваша целевая аудитория, когда у вас будет готово то, зачем вы сюда обратились?'; break;
    case 11: $word1 = 'Упаковка'; $word2 = 'Что вы хотите получить на выходе от дизайнера?
Что должно быть указано на лицевой стороне?
Кто ваши клиенты/потребители/покупатели?
Что должна понять или сделать ваша целевая аудитория, когда у вас будет готово то, зачем вы сюда обратились?
Где будет это продаваться?'; break;
    case 12: $word1 = 'Реклама'; $word2 = 'Что вы хотите получить на выходе от дизайнера?
Какую информацию необходимо указать?
Кто ваши клиенты/потребители/покупатели?
Что должна понять или сделать ваша целевая аудитория, когда у вас будет готово то, зачем вы сюда обратились?
Где будет это показано?'; break;
endswitch;

?>

<div class="wrapper">

	<?=$this->view()->render(array('element' => 'header'), array('header' => 'header2'))?>
    <input type="hidden" id="referal" value="<?=$referal;?>">
    <input type="hidden" id="referalId" value="<?=$referalId;?>">
	<aside class="summary-price expanded">
		<h3>Итого:</h3>
		<p class="summary"><strong id="total-tag">0.-</strong></p><!-- .summary -->
		<ul id="check-tag">
		</ul>
		<a href="#" class="show" id="show-check"><span>Подробнее</span></a>
		<a href="#" class="hide" id="hide-check"><span>Скрыть</span></a>
	</aside><!-- .summary-price -->

	<div class="middle add-pitch" id="step1">

		<div class="main">

				<ol class="steps">
					<li class="current"><a href="#" class="steps-link" data-step="1">1. Цена</a></li>
					<li><a href="#" class="steps-link" data-step="2">2. Бриф</a></li>
					<li class="last"><a href="#" class="steps-link" data-step="3">3. оплата</a></li>
				</ol><!-- .steps -->

                <?php
                function renderNumBox($category) {
                    $categoriesWithBox = array(3, 9, 4, 6, 2, 12, 11, 10);
                    if(!in_array($category, $categoriesWithBox)) { return '';}
                    $info = array(
                        3 => array('text' => 'Сколько шаблонов страниц необходимо разработать для вашего сайта? Внимание, только дизайн,  без кода HTML', 'mult' => 2000),
                        9 => array('text' => 'Сколько иллюстранций необходимо создать? Если серия, укажите суммарное число работ'),
                        4 => array('text' => 'Сколько макетов вам нужно создать? Если это серия, то укажите суммарное количество, даже если используется один шаблон.'),
                        6 => array('text' => 'Сколько страниц нужно создать. Если это серия, то укажите суммарное количество, даже если используется одна идея и стиль.'),
                        2 => array('text' => 'Сколько макетов вам нужно создать? Мы рекомендуем учитывать и адаптации под размеры тоже. '),
                        12 => array('text' => 'Сколько макетов вам нужно предоставить?  Если это серия, то укажите суммарное количество.'),
                        11 => array('text' => 'Сколько макетов вам нужно на выходе? Если это серия этикеток (коробок), то укажите суммарное количество.'),
                        10 => array('text' => 'Сколько макетов вам нужно предоставить?  Если это серия, то укажите суммарное количество'),
                    );

                    $chosenCategory = $info[$category];
                    $text = $chosenCategory['text'];
                    $mult = '';
                    if(isset($chosenCategory['mult'])) {
                        $mult = 'data-mult="' . $chosenCategory['mult'] . '"';
                    }

                    $html = '<div class="set-price"><p>
                        <label style="width:260px;">' . $text . '</label>
                        <input type="text" ' . $mult . ' name="site-sub" id="sub-site" placeholder="1" class="initial-price specific-prop" style="width:242px;"/>
                    </p></div>';
                    return $html;
                }?>

                <?php echo renderNumBox($category->id)?>



                <?php if($category->id == 7):?>
                <div class="groupc">
                <p>
                    <label>Выберите вид копирайтинга</label>
                    <input type="hidden" id="copybaseminprice" value="<?php echo COPY_BASE_PRICE;?>"/>
                </p>
                <ul class="radiooptionssite">
                    <li><label><input type="checkbox" name="first-option" value="0" class="sub-check specific-group" checked="checked" style="vertical-align: middle;"><span class="radiospan">Имя / название</span></label></li>
                    <li><label><input type="checkbox" name="second-option" value="1" class="sub-check specific-group"  style="vertical-align: middle;"><span class="radiospan">Адрес сайта</span></label></li>
                    <li><label><input type="checkbox" name="third-option" value="2" class="sub-check specific-group"  style="vertical-align: middle;"><span class="radiospan">Слоган / лозунг</span></label></li>
                </ul>
                </div>
                <?php endif;?>

				<div class="set-price">
					<p>
						<label>Сумма вознаграждения для дизайнера (от <?=$this->moneyFormatter->formatMoney($category->minAward, array('suffix' => 'Р.'))?>) <a href="#" class="second tooltip" title="Здесь вам нужно указать, сколько заработает победитель. Эта сумма не включает сбора Go Designer и стоимость опций.">(?)</a></label>
						<input type="text" name="" id="award" data-low="<?=$category->minAward?>" data-normal="<?=$category->normalAward?>" data-high="<?=$category->goodAward?>" data-low-def="<?=$category->minAward?>" data-normal-def="<?=$category->normalAward?>" data-high-def="<?=$category->goodAward?>" data-option-title="Награда Дизайнеру" data-minimal-award="<?=$category->minAward?>" class="initial-price placeholder" placeholder="<?=$category->minAward?>" value="<?=$category->minAward?>">
					</p>

					<div id="indicator" class="indicator low tooltip" title="С помощью этой шкалы мы информируем вас о средних финансовых запросах современного фрилансера. Чем больше сумма вознаграждения, тем больше дизайнеров откликнется, тем больше вариантов на выбор вы получите.">
						<div class="bar">
							<div class="line"></div>
							<div class="shadow-b"></div>
						</div><!-- .bar -->
						<ul>
							<li>мало</li>
							<li>хорошо</li>
							<li>самое то!</li>
						</ul>
					</div><!-- .indicator -->
				</div><!-- .set-price -->

            <?php if($category->id == 11):?>
            <div class="groupc">
                <p>
                    <label>Вид упаковки</label>

                <ul class="radiooptionssite">
                    <li><label><input type="radio" name="package-type" value="0" class="sub-radio specific-group" data-min-value="7000" checked="checked"><span class="radiospan">Этикетка и контрэтикетка (от 7000) <a href="#" style="color:#658FA5;font-size:11px" class="second tooltip" title="Этикетка — графическое или текстовое описание товара, заполняющее одну плоскость бумажной формы, наклейки и т.п. Н-р, э. пива, э. на консервы, конфетная э. и т.п.">(?)</a></span></label></li>
                    <li><label><input type="radio" name="package-type" value="1" class="sub-radio specific-group" data-min-value="13400"><span class="radiospan">Оформление коробки, развёртки, и прочее (от 13400 Р-.) <a href="#" style="color:#658FA5;font-size:11px" class="second tooltip" title="Упаковка — комплекс полиграфической продукции и материалов, в которые упаковывают товар или сырье; графическое или текстовое описание товара, которое подразумевает более одной плоскости. Например, картонная коробка, бутылка, стрейч-пленка и т.п.">(?)</a></span></label></li>
                </ul>
                </p>
            </div>
            <?php endif;?>

            <div style="margin-top:5px;height:200px;">

                <div style="margin-bottom:20px">
                    <input style="vertical-align: top;margin-top:3px" id="guaranteedTrue" type="radio" name="isGuaranteed" value="1" data-option-title="Гарантированный питч" data-option-value="950">
                    <label for="guaranteedTrue" style="font-size: 30px; color:#6ca475; font-family: 'RodeoC', 'Helvetica Neue';margin-left:10px;">Гарантированный питч!&nbsp;&nbsp;&nbsp;+950.-</label>
                    <p style="font-size:13px; color:#6f6f6f;padding-left:23px;margin-top:5px">Вы гарантируете, что выберете победителя в любом случае, тем самым инициировав до 40% больше решений. Мы выделяем такой питч в списке. Дизайнеры увидят, что питч не останется без победителя, и вы получите больший выбор идей.</p>
                </div>

                <div>
                    <input style="vertical-align: top;margin-top:3px" id="guaranteedFalse" type="radio" name="isGuaranteed" value="0" data-option-title="Гарантированный питч">
                    <label for="guaranteedFalse" style="font-size: 30px; color:#6f6f6f; font-family: 'RodeoC', 'Helvetica Neue';margin-left:10px;">Питч без гарантий&nbsp;&nbsp;&nbsp;0р.-</label>
                    <p style="font-size:13px; color:#6f6f6f;padding-left:23px;margin-top:5px">При активном взаимодействии с дизайнерами вы сможете <a href="/answers/view/71" target="_blank">вернуть деньги, если решения не понравятся</a>. Отсутствие гарантий, однако, спровоцирует меньший интерес к проекту.</p>
                </div>

            </div>


            <h1 style="background: url('/img/images/faq.png') no-repeat scroll 55% 0 transparent;	font-family: 'RodeoC', serif;
            font-size: 12px;
            font-style: normal;
            font-variant: normal;
            font-weight: 400;
            height: 38px;
            line-height: 41px;
            text-align: center;
            text-transform: uppercase;margin-bottom:20px;">Дополнительные опции</h1>
            <script>
            var fillBrief = <?php echo ($this->session->read('fillbrief')) ? 1 : 0; ?>;
            var feeRates = {low: <?php echo FEE_LOW;?>, normal: <?php echo FEE_NORMAL;?>, good: <?php echo FEE_GOOD;?>};
            </script>
                    <div class="ribbon complete-brief">
                        <p class="option"><label><input type="checkbox"  name="" class="single-check" data-option-title="Заполнение брифа" data-option-value="1750" id="phonebrief">Заполнить бриф</label></p>
                        <p class="description">Вы можете ознакомиться с примерами заполнения брифа <a href="/answers/view/68" target="_blank">тут</a>. Оставьте свой № телефона, мы свяжемся с вами для интервью в течении рабочего дня с момента оплаты <a href="#" class="second tooltip" title="Мы работаем пн-пт с 10:00-19:00. Поставив галочку, вы сможете пропустить следующую страницу (или ответить на легкие вопросы) и перейти непосредственно к оплате.">(?)</a></p>
                        <!--p class="description">Опция недоступна до 13.08.2013</p-->
                        <p><input type="text" id="phonenumber" name="phone-brief" placeholder="+7 XXX XXX XX XX" class="phone" value=""></p>
                        <p class="label">1750.-</p>
                    </div>

				<div class="ribbon">
					<p class="option"><label><input type="checkbox" name="" class="single-check" data-option-title="Закрытый питч" data-option-value="<?php if($this->session->read('user.id') == 1431):?>950<?php else:?>950<?php endif?>">Закрытый питч</label></p>
					<p class="description">Питч станет не виден для поисковых систем, а идеи будут доступны для просмотра только вам и их авторам. Подробнее <a target="_blank" href="http://www.godesigner.ru/answers/view/64">тут</a> <a href="#" class="second tooltip" title="Это идеальная возможность, если вы являетесь посредником, рекламным агентством или не хотите разглашать секретов в маркетинговых целях.">(?)</a></p>
					<p class="label">+<?php if($this->session->read('user.id') == 1431):?>950<?php else:?>950<?php endif?>.-</p>
				</div>

				<div class="ribbon">
					<p class="option"><label><input type="checkbox" name="" class="single-check" data-option-title="Рекламный Кейс" data-option-value="15900">Рекламный Кейс</label></p>
					<p class="description">С его помощью ваш питч может стать началом или частью рекламной кампании. Подробнее <a target="_blank" href="http://www.godesigner.ru/answers/view/65">тут</a> <a href="#" class="second tooltip" title="Мы публикуем информацию о результатах питча в интернет СМИ и на страницах социальных сетей. Используя обилие полученных вариантов мы создадим медийное событие.">(?)</a></p>
					<p class="label">15900.-</p>
				</div>

				<div class="ribbon">
					<p class="option"><label><input type="checkbox" name="" class="multi-check" data-option-title="экспертное мнение" data-option-value="1000" id="experts-checkbox">Экспертное мнение</label></p>
					<p class="description"><a href="/experts" id="expert-trigger">Наши эксперты</a> с опытом работы в ведущих рекламных агентствах помогут вам с выбором варианта <a href="#" class="second tooltip" title="Эксперт укажет   и прокомментирует 3 лучших решения, которые максимально отвечают на вашу задачу. Вы можете выбрать несколько экспертов и заручиться надёжной поддержкой.">(?)</a></p>
					<p class="label" id="expert-label">+1000.-</p>
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

                    foreach($experts as $expert):?>
                        <li>
                            <a href="/experts/view/<?=$expert->id?>" target="_blank" class="photo"><img src="<?=$imageArray[$expert->id]?>" alt="<?=$expert->name?>"></a><!-- .photo -->
                            <p class="select"><input type="checkbox" name="" class="expert-check" data-id="<?=$expert->id?>" data-option-title="экспертное мнение" data-option-value="<?=$expert->price?>"></p><!-- .select -->
                            <dl>
                                <dt><strong><a href="/experts/view/<?=$expert->id?>" target="_blank"><?=$expert->name?></a></strong></dt>
                                <dd><a href="/experts/view/<?=$expert->id?>" target="_blank"><?=$expert->spec?></a></dd>
                            </dl>
                        </li>
                    <?php endforeach?>
				</ul><!-- .experts -->

				<!--div class="ribbon">
					<p class="option"><label><input type="checkbox" name="" class="single-check" data-option-title="Email рассылка" data-option-value="1000">Email рассылка</label></p>
					<p class="description">Увеличить число креативщиков, дизайнеров или копирайтеров с помощью рассылки по email <a href="#" class="second">(?)</a></p>
					<p class="label">+1000.-</p>
				</div-->
                <div class="groupc">


                    <div class="ribbon" id="pinned-block">
                        <p class="option"><label><input type="checkbox" name="" class="single-check" data-option-title="“Прокачать” бриф" data-option-value="1000">“Прокачать” бриф</label></p>
                        <p class="description">Увеличить количество решений <a href="#" class="second tooltip" title="Вы сможете увеличить количество предложенных вариантов на 15-40%. Для привлечения дизайнеров мы используем e-mail рассылку, facebook, vkontakte, twitter, выделение синим цветом в списке и на главной странице">(?)</a></p>
                        <p class="label">+1000.-</p>
                    </div>

                    <p class="brief-example"><a href="/docs/<?=$briefExamples[$category->id]?>" target="_blank"></a></p><!-- .brief-example -->

                    <div class="ribbon complete-brief">
                        <p class="option"><label>Промокод</label></p>
                        <p class="description" id="hint">Промокод не введён</p>
                        <p><input style="height:44px; width:125px;padding-left:16px;padding-right:16px; background: none repeat scroll 0 0 #FFFFFF;box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) inset;font-size:12px;" type="text" id="promocode" name="promocode" class="phone" value="<?php echo (isset($promocode)) ? $promocode : ''; ?>"></p>
                    </div>

                </div>
				<p class="submit">
					<input type="submit" value="Продолжить" class="button steps-link" data-step="2">
				</p><!-- .submit -->

		</div><!-- .main -->

	</div><!-- .middle -->

    <!-- second step -->

	<div class="middle add-pitch" style="display:none;" id="step2">

		<div class="main">

				<ol class="steps">
					<li><a href="#" class="steps-link" data-step="1">1. Цена</a></li>
					<li class="current"><a href="#" class="steps-link" data-step="2">2. Бриф</a></li>
					<li class="last"><a href="#" class="steps-link" data-step="3">3. оплата</a></li>
				</ol><!-- .steps -->

				<div class="groupc">
					<p>
						<label class="required">Название питча <a href="#" class="second tooltip" title="Кратко напишите, что вам необходимо создать и для какого бренда. (прим.: обёртка для шоколада “Мишка на севере”) Подробнее о брифе в разделе “Помощь”.">(?)</a></label>
						<?php if($category->id != 7):?>
                        <input type="text" name="title" placeholder="<?=$word1?> для Star Lift" data-placeholder="<?=$word1?> для Star Lift" required>
                        <?php else:?>
                        <input type="text" name="title" placeholder="Название для строительной фирмы" data-placeholder="Название для строительной фирмы" required>
                        <?php endif?>
						<input type="hidden" name="category_id" value="<?=$category->id?>">
					</p>
					<p>
						<label class="required">Вид деятельности <a href="#" class="second tooltip" title="Тут необходимо указать отрасль, для которой вы создаете питч. (прим.: кондитерские изделия, строительная компания)">(?)</a></label>
						<input type="text" name="industry" placeholder="Подъемники для строительства в аренду и продажу" data-placeholder="Подъемники для строительства в аренду и продажу" required>
					</p>

					<div class="ribbon term" style="height: 80px;">
						<p class="option">Установите срок</p>
                        <ul>
                            <li><label><input type="radio" class="short-time-limit" name="short-time-limit" data-option-period="0" data-option-title="Установлен срок" data-option-value="0" checked="checked"><?=$category->default_timelimit?> дней (бесплатно)</label></li>
                            <li><label><input type="radio" class="short-time-limit" name="short-time-limit" data-option-period="1" data-option-title="Установлен срок" data-option-value="950" ><?=$category->shortTimelimit?> дней (950 Р.-)</label></li>
                            <li><label><input type="radio" class="short-time-limit" name="short-time-limit" data-option-period="2" data-option-title="Установлен срок" data-option-value="1450" ><?=$category->shortestTimelimit?> дня (1450 Р.-)</label></li>
                        </ul>
                        <ul>
                            <li style="margin-top:10px"><label><input type="radio" class="short-time-limit" name="short-time-limit" data-option-period="3" data-option-title="Установлен срок" data-option-value="950" ><?=$category->smallIncreseTimelimit?> дней (950 Р.-)</label></li>
                            <li style="margin-top: 10px; margin-left: 64px;"><label><input type="radio" class="short-time-limit" name="short-time-limit" data-option-period="4" data-option-title="Установлен срок" data-option-value="1450" ><?=$category->largeIncreaseTimelimit?> дня (1450 Р.-)</label></li>
                        </ul>
						<p style="margin-top:-34px;" class="label" id="timelimit-label">+500.-</p>
					</div>
				</div><!-- .group -->

				<div class="groupc">
					<p>
						<label>Описание бизнеса/деятельности <a href="#" class="second tooltip" title="Укажите название компании, чем она занимается или что создает. Чем вы отличаетесь от конкурентов. ">(?)</a></label>
                        <?php if($category->id == 7):?>
                        <textarea name="business-description" cols="30" rows="10" placeholder="Опишите в двух словах ваш род деятельности. Чем вы уникальны и чем вы отличаетесь от конкурентов? Кто ваша целевая аудитория и какова ваша бизнес-мечта"></textarea>
                        <?php else:?>
                        <textarea name="business-description" cols="30" rows="10" placeholder="Опишите в двух словах ваш род деятельности. Какие качества отличают ваш бизнес от конкурентов?"></textarea>
                        <?php endif?>
                    </p>
					<p>
                        <?php if($category->id == 7):?>
						<label class="required">Опишите, что вам нужно и для каких целей <a href="#" class="second tooltip" title="Что вы хотите получить от копирайтера? Кто ваши клиенты/потребители, их вкусы и предпочтения. Что они должны понять или сделать? ">(?)</a></label>
						<?php else:?>
                        <label class="required">Опишите, что вам нужно и для каких целей <a href="#" class="second tooltip" title="Что вы хотите получить от дизайнера? Кто ваши клиенты/потребители, их вкусы и предпочтения. Что они должны понять или сделать? ">(?)</a></label>
                        <?php endif?>
                        <?php if($category->id == 7):?>
						<textarea id="full-description" name="description" cols="30" rows="10" required placeholder="Где, в основном, будет использоваться название и слоган? Что они должны отражать? Чего стоит избегать?" data-placeholder="Где, в основном, будет использоваться название и слоган? Что они должны отражать? Чего стоит избегать?" data-low="70" data-normal="140" data-high="280" ></textarea>
						<?php else:?>
						<textarea id="full-description" name="description" cols="30" rows="10" required placeholder="<?=$word2?>" data-placeholder="<?=$word2?>" data-low="70" data-normal="140" data-high="280" ></textarea>
						<?php endif?>
					</p>


					<div id="indicator-desc" class="indicator low tooltip" title="Шкала показывает, насколько подробно вы описали то, зачем пришли. Каждое ваше слово поможет дизайнеру.">
						<div class="bar">
							<div class="line"></div>
							<div class="shadow-b"></div>
						</div><!-- .bar -->
						<ul>
							<li>недостаточно подробно…</li>
							<li>вполне понятно</li>
							<li>самое то!</li>
						</ul>
					</div><!-- .indicator -->
				</div><!-- .group -->

                <?=$this->view()->render(array('element' => 'brief-create/' . $category->id))?>

                <?php if($category->id != 7):?>
                <div class="groupc">
                    <?php if($category->id !=1) :?>
                    <p><label>Можно ли дополнительно использовать материал из банков с изображениями или шрифтами? <a href="#" class="second tooltip" title="Это даст возможность дизайнерам добиться лучшего результата. Профессионалы из мира рекламы часто прибегают к помощи фото-банков для экономии сил, времени или бюджета.">(?)</a></label></p>
                    <?php else: ?>
                    <p><label>Можно ли дополнительно использовать платные шрифты? <a href="#" class="second tooltip" title="Это даст возможность дизайнерам добиться лучшего результата и неповторимого типографического решения.">(?)</a></label></p>
                    <?php endif?>
                    <div style="float:left;width:50px;height:44px;padding-top:10px;">
                        <input style="vertical-align: middle" type="radio" name="materials" value="0" checked="checked"/><span class="radiospan">Нет</span>
                    </div>
                    <div style="float:left;width:50px;height:44px;padding-top:10px;">
                        <input style="vertical-align: middle" type="radio" name="materials" value="1" /><span class="radiospan">Да</span>
                    </div>
                    <div><input type="text" placeholder="допустимая стоимость одного изображения" style="width: 300px;" name="materials-limit" value=""></div>

                </div>
                <?php endif;?>


                <div class="groupc">

					<p><label>Дополнительные материалы <a href="#" class="second tooltip" title="Присоедините все материалы, которые могут помочь креативщику. Это могут быть фотографии, приглянувшиеся аналоги, существующие логотипы, технические требования и т.д.">(?)</a></label></p>
                    <div id="new-download" style="display:none;">
					<p class="add-file">
                        <form action="/pitchfiles/add.json" method="post" id="fileuploadform">
                            <div class="fileinputs">
                                <img style="display:block; height:20px; float:left;" class="fakeinput" src="/img/choosefile.png"/>
                                <span class="fakeinput supplement" id="filename" style="display:block; float: left; height:19px; width: 450px; padding-top: 1px; margin-left:10px;">Файл не выбран</span>
						        <input type="file" name="files" multiple id="fileupload" style="display:block; opacity:0; position:absolute;z-index:5"/>
                            </div>
                            <div clas="clr"></div>
						    <input type="text" id="fileupload-description" name="file-description" style="width:370px;margin-right: 20px;" placeholder="Пояснение"/>
                            <!--input type="button" class="button" value="Загрузить" id="uploadButton"/-->
                            <div class="span5 fileupload-progress fade">
                                <!-- The global progress bar -->
                                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                    <div class="bar" style="width:0%;"></div>
                                </div>
                                <!-- The extended global progress information -->
                                <div class="progress-extended">&nbsp;</div>
                            </div>
                        </form>
					</p>
					</div>

					<iframe id="old-download" src="/pitchfiles/index" seamless style="display:none;width:570px;height:100px;"></iframe>

					<!--p class="add-another-file"><a href="#">+ добавить файл</a></p--><!-- .add-another-file -->
                    <ul id="filezone">
                    </ul>
                    <div style="clear:both"></div>
				</div><!-- .group -->

				<div class="groupc">
                    <p class="brief-example"><a href="/docs/<?=$briefExamples[$category->id]?>" target="_blank"></a></p><!-- .brief-example -->
					<p>
						<label class="required">Формат файла <a href="#" class="second tooltip" title="Необходимо указать формат, который на выходе предоставит вам дизайнер. Мы советуем обратиться в типографию или веб-мастеру и уточнить технические требования.">(?)</a></label>
					</p>

                    <?php if($category->id != 7):?>
					<ul class="extensions">
						<li class="wide graysupplement"><label><input type="checkbox" name="" data-value="EPS">.EPS</label></li>
						<li class="wide graysupplement"><label><input type="checkbox" name="" data-value="AI">.AI (Illustrator)</label></li>
						<li class="graysupplement"><label><input type="checkbox" name="" data-value="JPG">.JPG</label></li>
						<li class="graysupplement"><label><input type="checkbox" name="" data-value="PNG">.PNG</label></li>
						<li class="graysupplement"><label><input type="checkbox" name="" data-value="PDF">.PDF</label></li>
						<li class="wide graysupplement"><label><input type="checkbox" name="" data-value="PSD">.PSD (Photoshop)</label></li>
						<li class="wide graysupplement"><label><input type="checkbox" name="" data-value="Innd">.Innd (In Design)</label></li>
						<li class="graysupplement"><label><input type="checkbox" name="" data-value="GIF">.GIF</label></li>
						<li class="graysupplement"><label><input type="checkbox" name="" data-value="TIFF">.TIFF</label></li>
						<li class="graysupplement"><label><input type="checkbox" name="" data-value="другие">другие</label></li>
					</ul><!-- .extensions -->
                    <?php else: ?>
                    <ul class="extensions">
                        <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="DOC">.DOC</label></li>
                        <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="PDF">.PDF</label></li>
                        <li class="graysupplement"><label><input type="checkbox" name="" data-value="другие">другие</label></li>
                    </ul><!-- .extensions -->
					<?php endif;?>
					<?php if($category->id != 7):?>
					<textarea name="format-description" cols="30" rows="10" placeholder="Дополнительная информация о файлах: размер, разрешение"></textarea>
					<?php else: ?>
					<textarea name="format-description" cols="30" rows="10" placeholder="Дополнительная информация о файлах: объём текстов в разделах и прочее"></textarea>
					<?php endif;?>
				</div><!-- .group -->

                <div class="groupc">
                    <p>
                        <label class="">Ваш контактный телефон <a href="#" class="second tooltip" title="Мы убедительно просим вас оставить ваш номер телефона для экстренных случаев и если возникнут вопросы с оплатой.">(?)</a></label>
                    </p>
                    <div><input type="text" placeholder="" style="width: 400px;" name="phone-brief" value=""></div>
                </div>

                <div class="tos-container supplement" style="margin-bottom: 20px;">
                    <label><input type="checkbox" name="tos" style="vertical-align: middle; margin-right: 5px;"/>Я прочитал(а) и выражаю безусловное согласие с условиями настоящего <a href="/docs/dogovor.pdf" style="text-decoration: none;">конкурсного соглашения</a>.</label>
                </div>
				<p class="submit submit-brief">
					<input type="button" value="Вернуться к шагу 1" class="button steps-link" data-step="1">
					<input type="button" id="save" value="Сохранить и продолжить" class="button" data-step="3">
				</p><!-- .submit -->

		</div><!-- .main -->

	</div><!-- .middle -->
	<div class="middle add-pitch" style="display:none;" id="step3">

		<div class="main">
		    <form action="https://pay.masterbank.ru/acquiring" method="post">
				<input type="hidden" id="pitch-id" name="id" value=""/>
				<ol class="steps">
					<li><a href="#" class="steps-link" data-step="1">1. Цена</a></li>
					<li class=""><a href="#" class="steps-link" data-step="2">2. Бриф</a></li>
					<li class="last current"><a href="#" class="steps-link" data-step="3">3. оплата</a></li>
				</ol><!-- .steps -->
            </form>
			<div style="padding-bottom: 50px;">
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
                                Перевод на расчетный счёт<br/>(Безналичный платеж через банк)
                            </td>
                            <td></td>
                        </tr>
                    </table>
                    <div id="s3_kv">
                        <label><input type="radio" name="radio-face" class="rb-face" data-pay="offline-fiz"> ФИЗИЧЕСКОЕ ЛИЦО</label>
                        <label><input type="radio" name="radio-face" class="rb-face" data-pay="offline-yur"> ЮРИДИЧЕСКОЕ ЛИЦО</label>
                        <div class="pay-fiz">
                            <p>Заполните поля, скачайте счёт на оплату и оплатите его. С помощью него вы можете сделать безналичный перевод через банк.</p>
                            <form action="/bills/save" method="post" id="bill-fiz">
                                <input type="hidden" name="fiz-id" id="fiz-id" value="<?=$pitch->id?>">
                                <input type="hidden" name="fiz-individual" id="fiz-individual" value="1">
                                <input type="text" name="fiz-name" id="fiz-name" placeholder="Иванов Иван Иванович" data-placeholder="Федченко Максим Юрьевич" required="" data-content="symbolic">
                                <img src="/img/arrow-bill-download.png" class="arrow-bill-download" />
                                <input type="submit" id="button-fiz" value="Скачать счёт" class="button third" style="width:420px;">
                                <div class="clr"></div>
                            </form>
                            <p>Мы активируем ваш питч на сайте в течение рабочего дня после поступления денег, и тогда он появится в <a href="/pitches">общем списке</a>.
                               Пока вы можете просмотреть ваш питч в <a href="/users/mypitches">личном кабинете</a>.</p>
                        </div>
                        <div class="pay-yur">
                            <p>Заполните поля, скачайте счёт на оплату и оплатите его. С помощью него вы можете сделать безналичный перевод через банк.</p>
                            <form action="/bills/save" method="post" id="bill-yur">
                                <input type="hidden" name="yur-id" id="yur-id" value="<?=$pitch->id?>">
                                <input type="hidden" name="yur-individual" id="yur-individual" value="0">

                                <label class="required">Наименование организации</label>
                                <input type="text" name="yur-name" id="yur-name" placeholder="OOO «КРАУД МЕДИА»" data-placeholder="OOO «КРАУД МЕДИА»" required="" data-content="mixed">

                                <label class="required">ИНН</label>
                                <input type="text" name="yur-inn" id="yur-inn" placeholder="123456789012" data-placeholder="123456789012" required="" data-content="numeric" data-length="[10,12]">

                                <label class="required">КПП</label>
                                <input type="text" name="yur-kpp" id="yur-kpp" placeholder="123456789" data-placeholder="123456789" required="" data-content="numeric" data-length="[9]">

                                <label class="required">Юридический адрес</label>
                                <input type="text" name="yur-address" id="yur-address" placeholder="199397, Санкт-Петербург, ул. Беринга, д. 27" data-placeholder="199397, Санкт-Петербург, ул. Беринга, д. 27" required="" data-content="mixed">

                                <p>Мы активируем ваш питч на сайте в течение рабочего дня после поступления денег, и тогда он появится в <a href="/pitches">общем списке</a>.
                                Пока вы можете просмотреть ваш питч в <a href="/users/mypitches">личном кабинете</a>.</p>
                                <p>Закрывающие документы вы получите на e-mail сразу после того, как завершите питч. Распечатайте их, подпишите и поставьте печать.
                                Отправьте их нам в двух экземплярах по почте (199397, Россия, Санкт-Петербург, ул. Беринга, д. 27).
                                В ответном письме вы получите оригиналы документов с нашей печатью.</p>
                                <input type="submit" id="button-yur" value="Скачать счёт" class="button third" style="width:420px;">
                                <div class="clr"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="g_line"></div>
                <input type="button" id="backbutton" value="Вернуться к шагу 2" class="button steps-link" data-step="2" style="width:260px;float:left;">
                <a href="/pitches" class="button" style="width:192px;float:right;">На страницу всех питчей</a>
            </div>

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

<?=$this->html->script(array('jquery-ui-1.8.17.custom.min.js', 'jquery.scrollto.min.js', 'jquery-deparam.js', 'pitches/brief.js?' . mt_rand(100, 999), 'jquery.numeric','jquery.iframe-transport.js', 'jquery.fileupload.js', 'jquery.simplemodal-1.4.2.js', 'jquery.tooltip.js', 'popup.js', 'jquery.damnUploader.js'), array('inline' => false))?>
<?=$this->html->style(array('/brief', '/step3'), array('inline' => false))?>