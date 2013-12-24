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
$specifics = unserialize($pitch->specifics);
?>
<?php if(isset($specifics["audience"])):?>
<script type="text/javascript">var slidersValue = <?php echo json_encode($specifics["audience"])?>;</script>
<?php else:?>
<script type="text/javascript">var slidersValue = <?php echo json_encode($specifics["logo-properties"])?>;</script>
<?php endif;?>
<div class="wrapper">

<?=$this->view()->render(array('element' => 'header'), array('header' => 'header2'))?>
<input type="hidden" id="referal" value="<?=$pitch->referal_sum;?>">
<input type="hidden" id="referalId" value="<?=$pitch->referal;?>">
<script>var feeRates = {low: <?php echo FEE_LOW;?>, normal: <?php echo FEE_NORMAL;?>, good: <?php echo FEE_GOOD;?>};</script>
<aside class="summary-price expanded">
    <h3>Итого:</h3>
    <p class="summary"><strong id="total-tag">0.-</strong></p><!-- .summary -->
    <ul id="check-tag">
    </ul>
    <a href="#" class="show" id="show-check"><span>Подробнее</span></a>
    <a href="#" class="hide" id="hide-check"><span>Скрыть</span></a>
</aside><!-- .summary-price -->

<?php
$onlyText = false;
if($pitch->billed == 1):
    $onlyText = true;
endif?>
<input type="hidden" value="<?=$pitch->id?>" id="pitch_id"/>
<input type="hidden" value="<?=$pitch->billed?>" id="billed"/>
<input type="hidden" value="<?=$pitch->published?>" id="published"/>
<?php if(!$onlyText):?>
<div class="middle add-pitch" id="step1">

    <div class="main">

        <h2><?=$category->title;?></h2>

        <ol class="steps">
            <li class="current"><a href="#" class="steps-link" data-step="1">1. Цена</a></li>
            <li><a href="#" class="steps-link" data-step="2">2. Бриф</a></li>
            <li class="last"><a href="#" class="steps-link" data-step="3">3. оплата</a></li>
        </ol><!-- .steps -->

        <?php if($category->id == 11):?>
        <div class="groupc">
            <p>
                <label>Вид упаковки</label>
                <ul class="radiooptionssite">
                    <li><label><input type="radio" name="package-type" <?php if($specifics['package-type'] == 0): echo 'checked'; endif;?> value="0" class="sub-radio specific-group" data-min-value="7000" checked="checked"><span class="radiospan">Этикетка и контрэтикетка (от 7000) <a href="#" style="color:#658FA5;font-size:11px" class="second tooltip" title="Этикетка — графическое или текстовое описание товара, заполняющее одну плоскость бумажной формы, наклейки и т.п. Н-р, э. пива, э. на консервы, конфетная э. и т.п.">(?)</a></span></label></li>
                    <li><label><input type="radio" name="package-type" <?php if($specifics['package-type'] == 1): echo 'checked'; endif;?> value="1" class="sub-radio specific-group" data-min-value="13400"><span class="radiospan">Оформление коробки, развёртки, и прочее (от 13400 Р-.) <a href="#" style="color:#658FA5;font-size:11px" class="second tooltip" title="Упаковка — комплекс полиграфической продукции и материалов, в которые упаковывают товар или сырье; графическое или текстовое описание товара, которое подразумевает более одной плоскости. Например, картонная коробка, бутылка, стрейч-пленка и т.п.">(?)</a></span></label></li>
                </ul>
            </p>
        </div>
        <?php endif;?>

        <?php
        function renderNumBox($category, $details) {
            $categoriesWithBox = array(3, 9, 4, 6, 2, 12, 11, 10);
            if (($category == 11) && empty($details['site-sub'])) { return '';}
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
            $site_sub = (empty($details['site-sub'])) ? 1 : $details['site-sub'];
            $html = '<div class="set-price"><p>
                        <label style="width:260px;">' . $text . '</label>
                        <input type="text" ' . $mult . ' name="site-sub" id="sub-site" value="' . $site_sub . '" class="specific-prop" style="width:242px;"/>
                    </p></div>';
            return $html;
        }?>

        <?php echo renderNumBox($category->id, unserialize($pitch->specifics))?>

        <?php if($category->id == 7):?>
        <div class="groupc">
            <p>
                <label>Выберите вид копирайтинга</label>
                <input type="hidden" id="copybaseminprice" value="<?php echo COPY_BASE_PRICE;?>"/>
            </p>
            <ul class="radiooptionssite">
                <li><label><input type="checkbox" name="first-option" value="0" class="sub-check specific-group" <?php if (isset($specifics['first-option'])): echo 'checked'; endif;?> style="vertical-align: middle;"><span class="radiospan">Имя / название</span></label></li>
                <li><label><input type="checkbox" name="second-option" value="1" class="sub-check specific-group" <?php if (isset($specifics['second-option'])): echo 'checked'; endif;?> style="vertical-align: middle;"><span class="radiospan">Адрес сайта</span></label></li>
                <li><label><input type="checkbox" name="third-option" value="2" class="sub-check specific-group" <?php if (isset($specifics['third-option'])): echo 'checked'; endif;?> style="vertical-align: middle;"><span class="radiospan">Слоган / лозунг</span></label></li>
            </ul>
        </div>
        <?php endif;?>

        <div class="set-price">

            <p>
                <label>Сумма вознаграждения для дизайнера (от <?=$this->moneyFormatter->formatMoney($category->minAward, array('suffix' => 'Р.'))?>) <a href="#" class="second tooltip" title="Здесь вам нужно указать, сколько заработает победитель. Эта сумма не включает сбора Go Designer и стоимость опций.">(?)</a></label>
                <input type="text" name="" id="award" data-low="<?=$category->minAward?>" data-normal="<?=$category->normalAward?>" data-high="<?=$category->goodAward?>" data-low-def="<?=$category->minAward?>" data-normal-def="<?=$category->normalAward?>" data-high-def="<?=$category->goodAward?>" data-option-title="Награда Дизайнеру" data-minimal-award="<?=$category->minAward?>" value="<?=(int)$pitch->price?>">
            </p>
            <div class="clr"></div>
            <!-- <div id="indicator" class="indicator low tooltip" data-normal="183" data-high="366" title="С помощью этой шкалы мы информируем вас о средних финансовых запросах современного фрилансера. Чем больше сумма вознаграждения, тем больше дизайнеров откликнется, тем больше вариантов на выбор вы получите."> -->
            <div id="indicator" class="indicator low" data-normal="183" data-high="366">
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
            <img src="/img/comissions.png" style="margin-bottom: 30px;">
        </div><!-- .set-price -->

        <div style="margin-top:5px;height:200px;">

            <div style="margin-bottom:20px">
                <input <?php if($pitch->guaranteed == 1): echo "checked"; endif;?> style="vertical-align: top;margin-top:3px" id="guaranteedTrue" type="radio" name="isGuaranteed" value="1" data-option-title="Гарантированный питч" data-option-value="950">
                <label for="guaranteedTrue" style="font-size: 30px; color:#6ca475; font-family: 'RodeoC', 'Helvetica Neue';margin-left:10px;">Гарантированный питч!&nbsp;&nbsp;&nbsp;+950р.-</label>
                <p style="font-size:13px; color:#6f6f6f;padding-left:23px;margin-top:5px">Вы гарантируете, что выберете победителя в любом случае, тем самым инициировав до 40% больше решений. Мы выделяем такой питч в списке. Дизайнеры увидят, что питч не останется без победителя, и вы получите больший выбор идей.</p>
            </div>

            <div>
                <input <?php if($pitch->guaranteed == 0): echo "checked"; endif;?> style="vertical-align: top;margin-top:3px" id="guaranteedFalse" type="radio" name="isGuaranteed" value="0" data-option-title="Гарантированный питч">
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

            <script>var fillBrief = 0;</script>
            <div class="ribbon complete-brief">
                <p class="option"><label><input type="checkbox" name="" <?php if($pitch->brief): echo "checked"; endif;?> class="single-check" data-option-title="Заполнение брифа" data-option-value="1750" id="phonebrief">Заполнить бриф</label></p>
                <p class="description">Вы можете ознакомиться с примерами заполнения брифа <a href="/answers/view/68" target="_blank">тут</a>. Оставьте свой № телефона, мы свяжемся с вами для интервью в течении рабочего дня с момента оплаты <a href="#" class="second tooltip" title="Мы работаем пн-пт с 10:00-19:00. Поставив галочку, вы сможете пропустить следующую страницу (или ответить на легкие вопросы) и перейти непосредственно к оплате.">(?)</a></p>
                <p><input type="text" id="phonenumber" name="phone-brief" placeholder="+7 XXX XXX XX XX" value="<?=$pitch->{'phone-brief'}?>" class="phone"></p>
                <p class="label <?php if($pitch->brief): echo "unfold"; endif;?>">1750.-</p>
            </div>

        <!--div class="ribbon">
                      <p class="option"><label><input type="checkbox" name="" <?php if($pitch->private): echo "checked"; endif;?> class="single-check" data-option-title="Закрытый питч" data-option-value="<?php if($this->session->read('user.id') == 1431):?>950<?php else:?>950<?php endif?>">Закрытый питч</label></p>
            <p class="description">Питч станет не виден для поисковых систем, а идеи будут доступны для просмотра только вам и их авторам. Подробнее <a target="_blank" href="http://www.godesigner.ru/answers/view/64">тут</a> <a href="#" class="second tooltip" title="Это идеальная возможность, если вы являетесь посредником, рекламным агентством или не хотите разглашать секретов в маркетинговых целях.">(?)</a></p>
                      <p class="label <?php if($pitch->private): echo "unfold"; endif;?>">+<?php if($this->session->read('user.id') == 1431):?>950<?php else:?>950<?php endif?>.-</p>
                  </div-->

        <div class="ribbon">
            <p class="option"><label><input type="checkbox" name="" <?php if($pitch->social): echo "checked"; endif;?> class="single-check" data-option-title="Рекламный Кейс" data-option-value="15900">Рекламный Кейс</label></p>
            <p class="description">С его помощью ваш питч может стать началом или частью рекламной кампании. Подробнее <a target="_blank" href="http://www.godesigner.ru/answers/view/65">тут</a> <a href="#" class="second tooltip" title="Мы публикуем информацию о результатах питча в интернет СМИ и на страницах социальных сетей. Используя обилие полученных вариантов мы создадим медийное событие.">(?)</a></p>
            <p class="label <?php if($pitch->social): echo "unfold"; endif;?>">15900.-</p>
        </div>

        <div class="ribbon">
            <p class="option"><label><input type="checkbox" name="" <?php if($pitch->expert): echo "checked"; endif;?> class="multi-check" data-option-title="экспертное мнение" data-option-value="1000" id="experts-checkbox">Экспертное мнение</label></p>
            <p class="description"><a href="#" id="expert-trigger">Наши эксперты</a> с опытом работы в ведущих рекламных агентствах помогут вам с выбором варианта <a href="#" class="second tooltip" title="Эксперт укажет   и прокомментирует 3 лучших решения, которые максимально отвечают на вашу задачу. Вы можете выбрать несколько экспертов и заручиться надёжной поддержкой.">(?)</a></p>
            <p class="label <?php if($pitch->expert): echo "unfold"; endif;?>" id="expert-label"></p>
        </div>

        <ul class="experts" <?php if(count($pitch->{'expert-ids'}) > 0): echo 'style="display:block;"'; endif;?>>
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
                    <p class="select"><input type="checkbox" name="" <?php if(in_array($expert->id, unserialize($pitch->{'expert-ids'}))): echo "checked"; endif;?> class="expert-check" data-id="<?=$expert->id?>" data-option-title="Экспертное мнение" data-option-value="<?=$expert->price?>"></p><!-- .select -->
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

        <div class="ribbon">
            <?php
            $value = 1000;
            if(($code) && ($code->type == 'pinned')):
                $value = 0;
            endif;
            ?>
            <p class="option"><label><input type="checkbox" name="" <?php if($pitch->pinned): echo "checked"; endif;?>  class="single-check" data-option-title="“Прокачать” бриф" data-option-value="<?=$value?>">“Прокачать” бриф</label></p>
            <p class="description">Увеличить количество решений <a href="#" class="second tooltip" title="Вы сможете увеличить количество предложенных вариантов на 15-40%. Для привлечения дизайнеров мы используем e-mail рассылку, facebook, vkontakte, twitter, выделение синим цветом в списке и на главной странице">(?)</a></p>
            <p class="label <?php if($pitch->pinned): echo "unfold"; endif;?>">+<?=$value?>.-</p>
        </div>
            <p class="brief-example" style="margin-top:0;"><a href="/docs/<?=$briefExamples[$category->id]?>" target="_blank"></a></p><!-- .brief-example -->

            <?php
            $codeValue = '';
            $text = 'Промокод не введён';
            $disabled = '';
            if($code) {
                $codeValue = $code->code;
                $text = 'Промкод активирован!';
                $disabled = 'disabled="disabled"';
            }
            if($pitch->promocode) {
                $codeValue = $pitch->promocode;
                $text = 'Промкод активирован!';
                $disabled = 'disabled="disabled"';
                $fieldHidden = '<input type="hidden" value="700" name="discount" id="discount">';
            }
            ?>

            <div class="ribbon complete-brief">
                <p class="option"><label>Промокод</label></p>
                <p class="description" id="hint"><?=$text?></p>
                <p>
                    <?php if(isset($fieldHidden)) echo $fieldHidden; ?>
                    <input type="text" <?=$disabled?>  id="promocode" name="promocode" class="phone" value="<?=$codeValue?>"></p>
            </div>


        </div><!-- .group -->

        <p class="submit">
            <input type="submit" value="Продолжить" class="button steps-link" data-step="2">
        </p><!-- .submit -->

    </div><!-- .main -->

</div><!-- .middle -->
<?php endif?>
<!-- second step -->
<?php if(!$onlyText):?>
<div class="middle add-pitch" style="display:none;" id="step2">
<?php else:?>
<div class="middle add-pitch" style="display:block;" id="step2">
<?php endif?>
    <div class="main">

        <h2><?=$category->title;?></h2>

        <?php if(!$onlyText):?>
        <ol class="steps">
            <li><a href="#" class="steps-link" data-step="1">1. Цена</a></li>
            <li class="current"><a href="#" class="steps-link" data-step="2">2. Бриф</a></li>
            <li class="last"><a href="#" class="steps-link" data-step="3">3. оплата</a></li>
        </ol><!-- .steps -->
        <?php endif?>
        <div class="groupc">
            <p>
                <label class="required">Название питча <a href="#" class="second tooltip" title="Кратко напишите, что вам необходимо создать и для какого бренда. (прим.: обёртка для шоколада “Мишка на севере”) Подробнее о брифе в разделе “Помощь”.">(?)</a></label>
                <input type="text" name="title" placeholder="Логотип для Star Lift" data-placeholder="Логотип для Star Lift" value="<?=$pitch->title?>" required>
                <input type="hidden" name="category_id" value="<?=$category->id?>">
            </p>
            <?php if (!empty($pitch->industry)):?>
            <p>
                <label class="required">Вид деятельности <a href="#" class="second tooltip" title="Тут необходимо указать отрасль, для которой вы создаете питч. (прим.: кондитерские изделия, строительная компания)">(?)</a></label>
                    <input type="text" name="industry" value="<?=$pitch->industry?>" placeholder="Подъемники для строительства в аренду и продажу" data-placeholder="Подъемники для строительства в аренду и продажу" required>
            </p>
            <?php endif; ?>

            <?php if(!$onlyText):?>
            <div class="ribbon term" style="height: 80px;">
                <p class="option">Установите срок</p>
                    <ul>
                        <li><label><input type="radio" <?php if($pitch->timelimit == 0): echo 'checked'; endif;?> class="short-time-limit" name="short-time-limit" data-option-period="0" data-option-title="Поджимают сроки" data-option-value="0" checked="checked"><?=$category->default_timelimit?> дней (бесплатно)</label></li>
                        <li><label><input type="radio" <?php if($pitch->timelimit == 1): echo 'checked'; endif;?> class="short-time-limit" name="short-time-limit" data-option-period="1" data-option-title="Поджимают сроки" data-option-value="950" ><?=$category->shortTimelimit?> дней (950 Р.-)</label></li>
                        <li><label><input type="radio" <?php if($pitch->timelimit == 2): echo 'checked'; endif;?> class="short-time-limit" name="short-time-limit" data-option-period="2" data-option-title="Поджимают сроки" data-option-value="1450" ><?=$category->shortestTimelimit?> дня (1450 Р.-)</label></li>
                    </ul>
                    <ul>
                        <li style="margin-top:10px"><label><input type="radio" <?php if($pitch->timelimit == 3): echo 'checked'; endif;?> class="short-time-limit" name="short-time-limit" data-option-period="3" data-option-title="Установлен срок" data-option-value="950" ><?=$category->smallIncreseTimelimit?> дней (950 Р.-)</label></li>
                        <li style="margin-top: 10px; margin-left: 64px;"><label><input type="radio" <?php if($pitch->timelimit == 4): echo 'checked'; endif;?> class="short-time-limit" name="short-time-limit" data-option-period="4" data-option-title="Установлен срок" data-option-value="1450" ><?=$category->largeIncreaseTimelimit?> дня (1450 Р.-)</label></li>
                    </ul>
                <p style="margin-top:-34px;" class="label <?php if($pitch->timelimit > 0): echo "unfold"; endif;?>" id="timelimit-label">
                    <?php if($pitch->timelimit > 0 ):
                        if(($pitch->timelimit == 1) || ($pitch->timelimit == 3)):
                            echo '950';
                        else:
                            echo '1450';
                        endif;
                    endif ?>.-</p>
            </div>
            <?php endif?>
        </div><!-- .group -->

        <div class="groupc">
            <p>
                <label>Описание бизнеса/деятельности <a href="#" class="second tooltip" title="Укажите название компании, чем она занимается или что создает. Чем вы отличаетесь от конкурентов. ">(?)</a></label>
                <?php if($category->id == 7):?>
                <textarea name="business-description" cols="30" rows="10" placeholder="Опишите в двух словах ваш род деятельности. Чем вы уникальны и чем вы отличаетесь от конкурентов? Кто ваша целевая аудитория и какова ваша бизнес-мечта"><?=$pitch->{'business-description'}?></textarea>
                <?php else:?>
                <textarea name="business-description" cols="30" rows="10" placeholder="Опишите в двух словах ваш род деятельности. Какие качества отличают ваш бизнес от конкурентов?"><?=$pitch->{'business-description'}?></textarea>
                <?php endif?>
            </p>
            <p>
                <label class="required">Опишите, что вам нужно и для каких целей <a href="#" class="second tooltip" title="Что вы хотите получить от дизайнера? Кто ваши клиенты/потребители, их вкусы и предпочтения. Что они должны понять или сделать? ">(?)</a></label>
                <textarea id="full-description" name="description" cols="30" rows="10" required placeholder="Что вы хотите получить на выходе от дизайнера?
Что должно быть прописано в логотипе?
Кто ваши клиенты/потребители/покупатели?
Где будет это размещаться?" data-placeholder="Что вы хотите получить на выходе от дизайнера?
Что должно быть прописано в логотипе?
Кто ваши клиенты/потребители/покупатели?
Где будет это размещаться?" data-low="70" data-normal="140" data-high="280" ><?=$pitch->description?></textarea>
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

        <?=$this->view()->render(array('element' => 'brief-edit/' . $category->id), array('specifics' => $specifics, 'pitch' => $pitch))?>

        <?php if($category->id != 7):?>
        <div class="groupc">
            <?php if($category->id !=1) :?>
            <p><label>Можно ли дополнительно использовать материал из банков с изображениями или шрифтами? <a href="#" class="second tooltip" title="Это даст возможность дизайнерам добиться лучшего результата. Профессионалы из мира рекламы часто прибегают к помощи фото-банков для экономии сил, времени или бюджета.">(?)</a></label></p>
            <?php else: ?>
            <p><label>Можно ли дополнительно использовать платные шрифты? <a href="#" class="second tooltip" title="Это даст возможность дизайнерам добиться лучшего результата и неповторимого типографического решения.">(?)</a></label></p>
            <?php endif?>

            <div style="float:left;width:50px;height:44px;padding-top:10px;">
                <input style="vertical-align: middle" type="radio" name="materials" value="0" <?php if(!$pitch->materials): echo 'checked';endif;?>/><span class="radiospan">Нет</span>
            </div>
            <div style="float:left;width:50px;height:44px;padding-top:10px;">
                <input style="vertical-align: middle" type="radio" name="materials" value="1" <?php if($pitch->materials): echo 'checked';endif;?>/><span class="radiospan">Да</span>
            </div>
            <div><input type="text" placeholder="допустимая стоимость одного изображения" style="width: 300px;" name="materials-limit" value="<?=$pitch->{'materials-limit'}?>"></div>

        </div>
        <?php endif;?>


        <div class="groupc">

            <p><label>Дополнительные материалы <a href="#" class="second tooltip" title="Присоедините все материалы, которые могут помочь креативщику. Это могут быть фотографии, приглянувшиеся аналоги, существующие логотипы, технические требования и т.д.">(?)</a></label></p>
            <div id="new-download" style="display:none;">
            <p class="add-file">
            <form action="/pitchfiles/add.json" method="post" id="fileuploadform">
                <div class="fileinputs">
                    <img style="display:block; height:20px; float:left;" class="fakeinput" src="/img/choosefile.png"/>
                    <span class="fakeinput" id="filename" style="display:block; float: left; height:19px; width: 450px; padding-top: 1px; margin-left:10px;">Файл не выбран</span>
                    <input type="file" name="files" id="fileupload" style="display:block; opacity:0; position:absolute;z-index:5"/>
                </div>
            </form>
            </p>
            </div>

            <iframe id="old-download" src="/pitchfiles/index" seamless style="display:none;width:570px;height:100px;"></iframe>

            <!--p class="add-another-file">
            <a href="#">+ добавить файл</a></p--><!-- .add-another-file -->
            <ul id="filezone">
                <?php foreach($files as $file):?>
                    <?php if (empty($file->originalbasename)):?>
                        <li data-id="<?=$file->id?>"><a style="float:left;width:300px" class="filezone-filename" href="<?=$file->weburl?>"><?=$file->basename?></a><a class="filezone-delete-link" style="float:right;width:100px;margin-left:0" href="#">удалить</a><div style="clear:both;"></div><p><?=$file->{'file-description'}?></p></li>
                    <?php else:?>
                        <li data-id="<?=$file->id?>"><a style="float:left;width:300px" class="filezone-filename" href="<?=str_replace('pitchfiles/', 'pitchfiles/1', $file->weburl);?>"><?=$file->basename?></a><a class="filezone-delete-link" style="float:right;width:100px;margin-left:0" href="#">удалить</a><div style="clear:both;"></div><p><?=$file->{'file-description'}?></p></li>
                    <?php endif;?>
                <?php endforeach;?>
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
                <li class="wide graysupplement"><label><input type="checkbox" name="" <?php if(in_array('EPS', unserialize($pitch->fileFormats))): echo 'checked'; endif; ?> data-value="EPS">.EPS</label></li>
                <li class="wide graysupplement"><label><input type="checkbox" name="" <?php if(in_array('AI', unserialize($pitch->fileFormats))): echo 'checked'; endif; ?> data-value="AI">.AI (Illustrator)</label></li>
                <li class="graysupplement"><label><input type="checkbox" name="" data-value="JPG" <?php if(in_array('JPG', unserialize($pitch->fileFormats))): echo 'checked'; endif; ?> >.JPG</label></li>
                <li class="graysupplement"><label><input type="checkbox" name="" data-value="PNG" <?php if(in_array('PNG', unserialize($pitch->fileFormats))): echo 'checked'; endif; ?> >.PNG</label></li>
                <li class="graysupplement"><label><input type="checkbox" name="" data-value="PDF" <?php if(in_array('PDF', unserialize($pitch->fileFormats))): echo 'checked'; endif; ?> >.PDF</label></li>
                <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="PSD" <?php if(in_array('PSD', unserialize($pitch->fileFormats))): echo 'checked'; endif; ?> >.PSD (Photoshop)</label></li>
                <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="Innd" <?php if(in_array('Innd', unserialize($pitch->fileFormats))): echo 'checked'; endif; ?> >.Innd (In Design)</label></li>
                <li class="graysupplement"><label><input type="checkbox" name="" data-value="GIF" <?php if(in_array('GIF', unserialize($pitch->fileFormats))): echo 'checked'; endif; ?> >.GIF</label></li>
                <li class="graysupplement"><label><input type="checkbox" name="" data-value="TIFF" <?php if(in_array('TIFF', unserialize($pitch->fileFormats))): echo 'checked'; endif; ?> >.TIFF</label></li>
                <li class="graysupplement"><label><input type="checkbox" name="" data-value="другие" <?php if(in_array('другие', unserialize($pitch->fileFormats))): echo 'checked'; endif; ?> >другие</label></li>
            </ul><!-- .extensions -->
            <?php else: ?>
            <ul class="extensions">
                <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="DOC" <?php if(in_array('DOC', unserialize($pitch->fileFormats))): echo 'checked'; endif; ?> >.DOC</label></li>
                <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="PDF" <?php if(in_array('PDF', unserialize($pitch->fileFormats))): echo 'checked'; endif; ?> >.PDF</label></li>
                <li class="graysupplement"><label><input type="checkbox" name="" data-value="другие" <?php if(in_array('другие', unserialize($pitch->fileFormats))): echo 'checked'; endif; ?> >другие</label></li>
            </ul><!-- .extensions -->
            <?php endif;?>
            <textarea name="format-description" cols="30" rows="10" placeholder="Дополнительная информация о файлах: размер, разрешение"><?=$pitch->fileFormatDesc?></textarea>

        </div><!-- .group -->

        <p class="submit submit-brief">
            <?php if(!$onlyText):?>
            <input type="button" value="Вернуться к шагу 1" class="button steps-link" data-step="1">
            <input type="button" id="save" value="Сохранить и продолжить" class="button" data-step="3">
            <?php else:?>
            <input type="button" id="save" value="Сохранить и просмотреть бриф" class="button">
            <?php endif?>
        </p><!-- .submit -->

    </div><!-- .main -->

</div><!-- .middle -->

<?php if(!$onlyText):?>
<div class="middle add-pitch" style="display:none;" id="step3">

    <div class="main">

        <h2><?=$category->title;?></h2>

        <form action="https://pay.masterbank.ru/acquiring" method="post">
            <input type="hidden" id="pitch-id" name="id" value=""/>
            <ol class="steps">
                <li><a href="#" class="steps-link" data-step="1">1. Цена</a></li>
                <li class=""><a href="#" class="steps-link" data-step="2">2. Бриф</a></li>
                <li class="last current"><a href="#" class="steps-link" data-step="3">3. оплата</a></li>
            </ol><!-- .steps -->
        </form>
        <?=$this->view()->render(array('element' => 'pitchpay'), array('pitch' => $pitch));?>
    </div><!-- .main -->

</div><!-- .middle -->
<?php endif?>

</div><!-- .wrapper -->

<div id="loading-overlay" class="popup-final-step" style="display:none;width:353px;text-align:center;text-shadow:none;">
    <div style="margin-top: 15px; margin-bottom:20px; color:#afafaf;font-size:14px"><span id="progressbar">0%</span></div>
    <div id="progressbarimage" style="text-align: left; padding-left: 6px; padding-top: 1px; padding-right: 6px; height: 23px; background: url('/img/indicator_empty.png') repeat scroll 0px 0px transparent; width: 341px;">
        <img id="filler" src="/img/progressfilled.png" style="width:1px" height="22">
    </div>
    <div style="color: rgb(202, 202, 202); font-size: 14px; margin-top: 20px;">Пожалуйста, используйте эту паузу<br> с пользой для здоровья!</div>
</div>



<?php if(!$onlyText):?>
<?=$this->html->script(array('jquery-ui-1.8.17.custom.min.js', 'jquery.ui.touch-punch.min.js', 'jquery.scrollto.min.js', 'pitches/brief.js', 'jquery.numeric','jquery.iframe-transport.js', 'jquery.fileupload.js', 'jquery.simplemodal-1.4.2.js', 'jquery.tooltip.js', 'jquery.damnUploader.js'), array('inline' => false))?>
<?php else:?>
    <?=$this->html->script(array('jquery-ui-1.8.17.custom.min.js', 'jquery.ui.touch-punch.min.js', 'jquery.scrollto.min.js', 'pitches/edit.js', 'jquery.numeric','jquery.iframe-transport.js', 'jquery.fileupload.js', 'jquery.simplemodal-1.4.2.js', 'jquery.tooltip.js', 'jquery.damnUploader.js'), array('inline' => false))?>
<?php endif?>
<?=$this->html->style(array('/brief', '/step3'), array('inline' => false))?>