<?php
$briefExamples = array(
    '4' => 'brief_visit_card.pdf',
    '5' => 'brief_visit_card.pdf',
    '9' => 'brief_illustration.pdf',
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

$job_types = array(
    'realty' => 'Недвижимость / Строительство',
    'auto' => 'Автомобили / Транспорт',
    'finances' => 'Финансы / Бизнес',
    'food' => 'Еда / Напитки',
    'adv' => 'Реклама / Коммуникации',
    'tourism' => 'Туризм / Путешествие',
    'sport' => 'Спорт',
    'sci' => 'Образование / Наука',
    'fashion' => 'Красота / Мода',
    'music' => 'Развлечение / Музыка',
    'culture' => 'Искусство / Культура',
    'animals' => 'Животные',
    'children' => 'Дети',
    'security' => 'Охрана / Безопасность',
    'health' => 'Медицина / Здоровье',
    'it' => 'Компьютеры / IT');

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
<script>
var feeRatesOrig = {low: <?php echo FEE_LOW;?>, normal: <?php echo FEE_NORMAL;?>, good: <?php echo FEE_GOOD;?>};
var feeRates = {low: <?php echo FEE_LOW;?>, normal: <?php echo FEE_NORMAL;?>, good: <?php echo FEE_GOOD;?>};
</script>
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

    <div class="main" style="padding-top: 35px;">

        <h2><?php if($category->title != 'Фирменный стиль и логотип'): echo $category->title;else: echo 'Фир. стиль и логотип'; endif; ?></h2>

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
                    <li><label><input type="radio" name="package-type" <?php if($specifics['package-type'] == 0): echo 'checked'; endif;?> value="0" class="sub-radio specific-group" data-min-value="11800" checked="checked"><span class="radiospan">Этикетка и контрэтикетка (от 11800) <a href="#" style="color:#658FA5;font-size:11px" class="second tooltip" title="Этикетка — графическое или текстовое описание товара, заполняющее одну плоскость бумажной формы, наклейки и т.п. Н-р, э. пива, э. на консервы, конфетная э. и т.п.">(?)</a></span></label></li>
                    <li><label><input type="radio" name="package-type" <?php if($specifics['package-type'] == 1): echo 'checked'; endif;?> value="1" class="sub-radio specific-group" data-min-value="22500"><span class="radiospan">Оформление коробки, развёртки, и прочее (от 22500 Р-.) <a href="#" style="color:#658FA5;font-size:11px" class="second tooltip" title="Упаковка — комплекс полиграфической продукции и материалов, в которые упаковывают товар или сырье; графическое или текстовое описание товара, которое подразумевает более одной плоскости. Например, картонная коробка, бутылка, стрейч-пленка и т.п.">(?)</a></span></label></li>
                </ul>
            </p>
        </div>
        <?php endif;?>

        <?php
        function renderNumBox($category, $details) {
            $categoriesWithBox = array(2, 3, 4, 6, 8, 9, 10, 11, 12);
            if (($category == 11) && empty($details['site-sub'])) { return '';}
            if(!in_array($category, $categoriesWithBox)) { return '';}
            $info = array(
                2 => array('text' => 'Сколько макетов вам нужно создать? Мы рекомендуем учитывать и адаптации под размеры тоже. Внимание, только дизайн, без кода HTML'),
                3 => array('text' => 'Сколько шаблонов страниц необходимо разработать для вашего сайта? Внимание, только дизайн,  без кода HTML', 'mult' => 2000),
                4 => array('text' => 'Сколько разворотов должно быть в буклете (не учитывать, если проект на флаер или листовку).'),
                6 => array('text' => 'Сколько страниц нужно создать. Если это серия, то укажите суммарное количество, даже если используется одна идея и стиль.'),
                8 => array('text' => 'Сколько шаблонов страниц необходимо разработать для вашей презентации?', 'mult' => 700),
                9 => array('text' => 'Сколько иллюстраций необходимо создать? Если серия, укажите суммарное число работ'),
                10 => array('text' => 'Сколько макетов вам нужно предоставить?  Если это серия, то укажите суммарное количество'),
                11 => array('text' => 'Сколько макетов вам нужно на выходе? Если это серия этикеток (коробок), то укажите суммарное количество.'),
                12 => array('text' => 'Сколько макетов вам нужно предоставить?  Если это серия, то укажите суммарное количество.'),
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
            <ul class="radiooptionssite" style="margin-bottom: 11px;">
                <li><label><input type="checkbox" name="first-option" value="0" class="sub-check specific-group" <?php if (isset($specifics['first-option'])): echo 'checked'; endif;?> style="vertical-align: top;"><span class="radiospan">Имя / название</span></label></li>
                <li style="margin-top: 4px;"><label><input type="checkbox" name="second-option" value="1" class="sub-check specific-group" <?php if (isset($specifics['second-option'])): echo 'checked'; endif;?> style="vertical-align: top;"><span class="radiospan">Адрес сайта</span></label></li>
                <li style="margin-top: 4px;"><label><input type="checkbox" name="third-option" value="2" class="sub-check specific-group" <?php if (isset($specifics['third-option'])): echo 'checked'; endif;?> style="vertical-align: top;"><span class="radiospan">Слоган / лозунг</span></label></li>
            </ul>
        </div>
        <?php endif;?>

        <?= $this->view()->render(array('element' => 'newbrief/setprice_block'), array('pitch' => $pitch, 'category' => $category)); ?>

        <div style="margin-top:5px;height:200px;">

            <div style="margin-bottom:40px">
                <input <?php if($pitch->guaranteed == 1): echo "checked"; endif;?> style="vertical-align: top;margin-top:3px" id="guaranteedTrue" type="radio" name="isGuaranteed" value="1" data-option-title="Гарантированный проект" data-option-value="950">
                <label for="guaranteedTrue" style="text-shadow: 0 1px 1px #eee;font-size: 29px; color:#658fa5; font-family: 'RodeoC', 'Helvetica Neue';margin-left:10px;">Гарантированный проект&nbsp;&nbsp;&nbsp;+950р.</label>
                <p class="guaranteeExplanation" id="guaranteedTooltip">Вы гарантируете, что выберете победителя в любом случае, тем самым инициировав до 40% больше решений. Мы выделяем такой проект в списке. <?php if($category->id == 7): echo 'Копирайтеры'; else: 'Дизайнеры'; endif;?> увидят, что проект не останется без победителя, и вы получите больший выбор идей.</p>
            </div>

            <div>
                <input <?php if($pitch->guaranteed == 0): echo "checked"; endif;?> style="vertical-align: top;margin-top:3px" id="guaranteedFalse" type="radio" name="isGuaranteed" value="0" data-option-title="Гарантированный проект">
                <label for="guaranteedFalse" style="text-shadow: 0 1px 1px #eee;font-size: 29px; color:#6f6f6f; font-family: 'RodeoC', 'Helvetica Neue';margin-left:10px;">Проект без гарантий&nbsp;&nbsp;&nbsp;0р.</label>
                <p class="guaranteeExplanation" id="nonguaranteedTooltip" style=" display:none;">При активном взаимодействии с <?php if($category->id == 7): echo 'копирайтерами'; else: echo 'дизайнерами'; endif;?> вы сможете <a href="/answers/view/71" target="_blank">вернуть деньги, если решения не понравятся</a>. Отсутствие гарантий, однако, спровоцирует меньший интерес к проекту.</p>
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

        <script>var fillBrief = 0;</script>

        <div class="ribbon complete-brief" style="padding-top: 35px; height: 56px; padding-bottom: 0;">
            <p class="option"><label><input type="checkbox" name="" <?php if($pitch->brief): echo "checked"; endif;?> class="single-check" data-option-title="Заполнение брифа" data-option-value="2750" id="phonebrief">Заполнить бриф</label></p>
            <!--p class="description">Вы можете ознакомиться с примерами заполнения брифа <a href="/answers/view/68" target="_blank">тут</a>. Оставьте свой № телефона, мы свяжемся с вами для интервью в течении рабочего дня с момента оплаты <a href="#" class="second tooltip" title="Мы работаем пн-пт с 10:00-19:00. Поставив галочку, вы сможете пропустить следующую страницу (или ответить на легкие вопросы) и перейти непосредственно к оплате.">(?)</a></p>
            <p><input type="text" id="phonenumber" name="phone-brief" placeholder="+7 XXX XXX XX XX" value="<?=$pitch->{'phone-brief'}?>" class="phone"></p-->
            <p class="label <?php if($pitch->brief): echo "unfold"; endif;?>" style="text-transform: none;">2750р.</p>
        </div>

        <div class="explanation brief" style="display:none;" id="explanation_brief">
            <p>Оставьте свой номер телефона, и мы свяжемся с вами для интервью
                в течение рабочего дня с момента оплаты:
            </p>
            <p><input type="text" id="phonenumber" name="phone-brief" placeholder="+7 XXX XXX XX XX" class="phone" value="<?=$pitch->{'phone-brief'}?>"></p>
            <p>Наши специалисты знают, как правильно сформулировать ваши ожидания и поставить задачу перед дизайнерами (копирайтерами). Мы убеждены, что хороший бриф - залог эффективной работы. С примерами заполненных брифов можно <a href="/answers/view/68">ознакомиться тут</a>.
            </p>
            <img src="/img/brief/brief.png" alt="Заполнить бриф"/>
        </div>

        <div class="ribbon" style="padding-top: 35px; height: 56px; padding-bottom: 0;">
            <p class="option"><label><input type="checkbox" name="" <?php if($pitch->private): echo "checked"; endif;?> class="single-check" data-option-title="Скрыть проект" data-option-value="3500">Скрыть проект</label></p>
            <!--p class="description">Питч станет не виден для поисковых систем, а идеи будут доступны для просмотра только вам и их авторам. Подробнее <a target="_blank" href="http://www.godesigner.ru/answers/view/64">тут</a> <a href="#" class="second tooltip" title="Это идеальная возможность, если вы являетесь посредником, рекламным агентством или не хотите разглашать секретов в маркетинговых целях.">(?)</a></p-->
            <p class="label <?php if($pitch->private): echo "unfold"; endif;?>" style="text-transform: none;" >3500р.</p>
        </div>

        <div class="explanation closed" style="margin-top: 0px; display: none; padding-bottom: 50px;" id="explanation_closed">
            <img class="explanation_closed" src="/img/brief/closed.png" alt="" style="">
            <ul class="" style="">
                <li>Идеально для посредников, рекламных агентств или<br>    для сохранения маркетинговых секретов</li>
                <li>Проект станет недоступен поисковым системам</li>
                <li>Участники подпишут «Cоглашение о неразглашении»</li>
                <li>Идеи будут не доступны для просмотра посторонними</li>
                <li style="list-style: outside none none; margin-top: 12px; margin-left: 0px;"><a href="https://www.godesigner.ru/answers/view/64" target="_blank">Подробнее тут</a></li>
            </ul>
            <div style="clear:both; font-size: 18px; font-family: OfficinaSansC Book, serif;"></div>
        </div>
        <?php if((isset($experts)) && $experts):?>
        <div class="ribbon" style="padding-top: 35px; height: 56px; padding-bottom: 0;">
            <p class="option"><label><input type="checkbox" name="" <?php if($pitch->expert): echo "checked"; endif;?> class="multi-check" data-option-title="экспертное мнение" data-option-value="1500" id="experts-checkbox">Экспертное мнение</label></p>
            <!--p class="description"><a href="#" id="expert-trigger">Наши эксперты</a> с опытом работы в ведущих рекламных агентствах помогут вам с выбором варианта <a href="#" class="second tooltip" title="Эксперт укажет   и прокомментирует 3 лучших решения, которые максимально отвечают на вашу задачу. Вы можете выбрать несколько экспертов и заручиться надёжной поддержкой.">(?)</a></p-->
            <p class="label <?php if($pitch->expert): echo "unfold"; endif;?>" id="expert-label"></p>
        </div>

        <ul class="experts" <?php if(count(unserialize($pitch->{'expert-ids'})) > 0): echo 'style="display:block;"';else: echo 'style="display: none;"'; endif;?>>
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

            foreach($experts as $expert): if ($expert->enabled == 0) continue;
                if(((int) $category->id === 7) && ((int) $expert->id === 1)) {
                    continue;
                }
            ?>
                <li>
                    <a href="/experts/view/<?=$expert->id?>" target="_blank" class="photo"><img src="<?=$imageArray[$expert->id]?>" alt="<?=$expert->name?>"></a><!-- .photo -->
                    <p class="select"><input type="checkbox" name="" <?php if(in_array($expert->id, unserialize($pitch->{'expert-ids'}))): echo "checked"; endif;?> class="expert-check" data-id="<?=$expert->id?>" data-option-title="Экспертное мнение" data-option-value="<?=$expert->price?>"></p><!-- .select -->
                    <dl>
                        <dt><strong><a style="font-family:OfficinaSansC Bold,serif;" href="/experts/view/<?=$expert->id?>" target="_blank"><?=$expert->name?></a></strong></dt>
                        <dd><a style="font-family:OfficinaSansC Book,serif; color:#666666;font-size: 14px" href="/experts/view/<?=$expert->id?>" target="_blank"><?=$expert->spec?> <?= $expert->price ?>&nbsp;р.-</a></dd>
                    </dl>
                </li>
            <?php endforeach?>
        </ul><!-- .experts -->
        <?php endif?>
        <!--div class="ribbon">
                      <p class="option"><label><input type="checkbox" name="" class="single-check" data-option-title="Email рассылка" data-option-value="1000">Email рассылка</label></p>
                      <p class="description">Увеличить число креативщиков, дизайнеров или копирайтеров с помощью рассылки по email <a href="#" class="second">(?)</a></p>
                      <p class="label">+1000.-</p>
                  </div-->
        <div class="groupc">

        <div class="ribbon" style="padding-top: 35px; height: 56px; padding-bottom: 0;">
            <?php
            $value = 1000;
            if (!empty($codes)) {
                foreach ($codes as $code) {
                    if (($code->type == 'pinned') || ($code->type == 'misha')) {
                        $value = 0;
                    }
                }
            }
            ?>
            <p class="option"><label><input type="checkbox" name="" <?php if($pitch->pinned): echo "checked"; endif;?>  class="single-check" data-option-title="«Прокачать» проект" data-option-value="<?=$value?>">«Прокачать» проект</label></p>
            <!--p class="description">Увеличить количество решений <a href="#" class="second tooltip" title="Вы сможете увеличить количество предложенных вариантов на 15-40%. Для привлечения дизайнеров мы используем e-mail рассылку, facebook, vkontakte, twitter, выделение синим цветом в списке и на главной странице">(?)</a></p-->
            <p style="text-transform: none;" class="label <?php if($pitch->pinned): echo "unfold"; endif;?>"><?=$value?>р.</p>
        </div>

            <div class="explanation pinned" style="margin-top: 0; padding-bottom: 40px; display: none;" id="explanation_pinned">
                <img class="" src="/img/brief/pinned.png" alt="" style="margin-left: -47px; margin-top: -4px;">
                <p style="margin-top: 40px; margin-left: 0px; width: 480px;">С помощью неё вы сможете увеличить количество решений до 40%.<br>
                    Для привлечения дизайнеров мы используем:</p>
                <ul class="ul_pinned" style="padding-top: 0px; margin-top: 22px; margin-left: 15px;">
                    <li style="width: 480px;">социальные сети GoDesigner: <a href="https://www.facebook.com/godesigner.ru" target="_blank">fb,</a> <a href="http://vk.com/godesigner" target="_blank">vk,</a> <a href="https://twitter.com/go_deer" target="_blank">twitter</a> (> 10 000 подписчиков)</li>
                    <li style="width: 480px;">выделение цветом в списке проектов</li>
                    <li style="width: 480px;">отображение на главной странице сайта</li>
                    <li style="list-style: outside none none; margin-left:0;"><a href="https://www.godesigner.ru/answers/view/67" target="_blank">Подробнее тут</a></li>
                </ul>
            </div>

            <p class="brief-example" style="margin-top:0;"><a href="/docs/<?=$briefExamples[$category->id]?>" target="_blank"></a></p><!-- .brief-example -->

            <?= $this->view()->render(array('element' => 'newbrief/ad_block'), compact('pitch')) ?>

            <?php
            $codeValue = '';
            $text = 'Промокод не введён';
            $disabled = '';
            $showHint = false;
            if($code) {
                $codeValue = $code->code;
                $text = 'Промокод активирован!';
                $disabled = 'disabled="disabled"';
                if($code->type == 'discount') {
                    $fieldHidden = '<input type="hidden" value="700" name="discount" id="discount">';
                    $showHint = true;
                }else if($code->type == 'custom_discount') {
                    $fieldHidden = '<input type="hidden" value="' . $code->data . '" name="custom_discount" id="custom_discount">';
                    $showHint = true;
                }
                if($code->type === 'pinned') {
                    $showHint = true;
                    $text = 'Промокод активирован!';
                }
            }
            ?>

            <!--div class="ribbon complete-brief">
                <p class="option"><label>Промокод</label></p>
                <p class="description" id="hint"><?=$text?></p>
                <p>
                    <?php if(isset($fieldHidden)) echo $fieldHidden; ?>
                    <input type="text" <?=$disabled?>  id="promocode" name="promocode" class="phone" value="<?=$codeValue?>"></p>
            </div-->

            <div class="ribbon complete-brief"  style="padding-top: 35px; height: 56px; padding-bottom: 0;">
                <p class="option"><label><input type="checkbox" name=""  id="promocodecheck" <?php if($showHint):?>checked="checked"<?php endif?>>Промокод</label></p>
                <p class="label"></p>
            </div>

            <div class="explanation promo" style="margin-left: 24px; margin-top: 0; padding-bottom: 0; <?php if(!$showHint):?>display: none;<?php endif?>" id="explanation_promo">
                <p><input style="height:44px; width:125px;padding-left:16px;padding-right:16px; background: none repeat scroll 0 0 #FFFFFF;box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) inset;font-size:29px;margin-top: 12px;color: #cccccc;" type="text" id="promocode" name="promocode" class="phone placeholder" placeholder="8888" value="<?php echo (isset($codeValue)) ? $codeValue : ''; ?>">
                <span id="promo-hint" style="<?php if($showHint):?>display: inline-block;<?php else:?>display: none;<?php endif;?> position: relative; top: 7px; left: 10px;"><?=$text?></span>
                </p>
                <p style="margin-top: 20px;">Промо-код высылается постоянным клиентам, которые успешно завершили проект, а также во время праздников или акций. С его помощью можно прокачать бриф, получить бонус или значительно снизить цену на проект! Об акциях можно узнать из наших <a href="https://www.facebook.com/godesigner.ru" target="_blank">fb,</a> <a href="http://vk.com/godesigner" target="_blank">vk,</a> <a href="https://twitter.com/go_deer" target="_blank">twitter</a>.
                </p>
            </div>


        </div><!-- .group -->

        <p class="submit">
            <input type="submit" value="Далее к заполнению брифа" class="button steps-link" data-step="2">
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
        <div class="main" style="padding-top: 35px;">

            <h2><?php if($category->title != 'Фирменный стиль и логотип'): echo $category->title;else: echo 'Фир. стиль и логотип'; endif; ?></h2>

        <?php if(!$onlyText):?>
        <ol class="steps">
            <li><a href="#" class="steps-link" data-step="1">1. Цена</a></li>
            <li class="current"><a href="#" class="steps-link" data-step="2">2. Бриф</a></li>
            <li class="last"><a href="#" class="steps-link" data-step="3">3. оплата</a></li>
        </ol><!-- .steps -->
        <?php endif?>

        <?= $this->view()->render(array('element' => 'newbrief/pitchtitle_block'), compact('pitch', 'category', 'word1')) ?>

        <?php if(($pitch->category_id == 7) && $onlyText): ?>
            <?php if (isset($specifics['first-option'])):?>
                <input type="hidden" data-selected="true" name="first-option" value="0" class="sub-check specific-group">
            <?php endif;?>
            <?php if (isset($specifics['second-option'])):?>
                <input type="hidden" data-selected="true" name="second-option" value="1" class="sub-check specific-group">
            <?php endif;?>
            <?php if (isset($specifics['third-option'])):?>
                <input type="hidden" data-selected="true" name="third-option" value="2" class="sub-check specific-group">
            <?php endif;?>
        <?php endif?>
<?php
$str = ($pitch->category_id == 1) ? ' в логотипе' : '';
$word2 = 'Опишите вид деятельности. Что отличает вас от конкурентов?<br>
Кто ваши клиенты/потребители/покупатели?<br>
<br>
Что вы хотите получить на выходе от дизайнера?<br>
Что должно быть прописано' . $str . '?<br>
Где будет это размещаться?';
?>

        <?= $this->view()->render(array('element' => 'newbrief/description_block'), compact('pitch', 'category', 'word2'))?>

        <?= $this->view()->render(array('element' => 'brief-edit/' . $category->id), array('specifics' => $specifics, 'pitch' => $pitch))?>

        <div class="groupc" style="margin-top: 34px; margin-bottom: 25px;">
            <label id ="show-types" class="greyboldheader required">Выберите вид деятельности</label>
            <ul id="list-job-type">
                <?php
                $industry = (unserialize($pitch->industry));
                $_empty = empty($industry);
                foreach ($job_types as $k => $v):
                    ?>
                    <li>
                        <label><input type="checkbox" name="job-type[]" value="<?= $k ?>" <?php if(!$_empty):  if(in_array($k, $industry)): echo ' checked'; endif; endif;?>><?= $v ?></label>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

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
            <form class="add-file" action="/pitchfiles/add.json" method="post" id="fileuploadform">
                <div class="fileinputs">
                    <img style="display:block; height:20px; float:left;" class="fakeinput" src="/img/choosefile.png"/>
                    <span class="fakeinput" id="filename" style="display:block; float: left; height:19px; width: 450px; padding-top: 1px; margin-left:10px;">Файл не выбран</span>
                    <input type="file" name="files" id="fileupload" style="display:block; opacity:0; position:absolute;z-index:5"/>
                </div>
            </form>
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

        <div class="groupc" style="margin-bottom: 19px; padding-bottom: 13px;">
            <p class="brief-example"><a href="/docs/<?=$briefExamples[$category->id]?>" target="_blank"></a></p><!-- .brief-example -->
            <?= $this->view()->render(array('element' => 'newbrief/fileformat'), array('pitch' => $pitch, 'category' => $category)); ?>

        </div></div><!-- .group -->

        <p class="submit submit-brief">
            <?php if(!$onlyText):?>
            <?= $this->view()->render(array('element' => 'newbrief/step2fullbuttons')); ?>
            <?php else:?>
            <input type="button" id="save" value="Сохранить и просмотреть бриф" class="button">
            <?php endif?>
        </p><!-- .submit --></div>
    </div><!-- .main -->

</div><!-- .middle -->

<?php if(!$onlyText):?>
<div class="middle add-pitch" style="display:none;" id="step3">

    <div class="main" style="padding-top: 35px;">

        <h2><?php if($category->title != 'Фирменный стиль и логотип'): echo $category->title;else: echo 'Фир. стиль и логотип'; endif; ?></h2>

        <form action="https://pay.masterbank.ru/acquiring" method="post">
            <input type="hidden" id="pitch-id" name="id" value=""/>
            <ol class="steps">
                <li><a href="#" class="steps-link" data-step="1">1. Цена</a></li>
                <li class=""><a href="#" class="steps-link" data-step="2">2. Бриф</a></li>
                <li class="last current"><a href="#" class="steps-link" data-step="3">3. оплата</a></li>
            </ol><!-- .steps -->
        </form>
        <?=$this->view()->render(array('element' => 'pitchpay'), array('pitch' => $pitch, 'category' => $category));?>
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

<?= $this->view()->render(array('element' => 'popups/brief_saved')); ?>
<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
<?php if(!$onlyText):?>
    <?=$this->html->script(array(
        'jquery-ui-1.11.4.min.js',
        'jquery.ui.touch-punch.min.js',
        'jquery-plugins/jquery.scrollto.min.js',
        'pitches/award_calculator.js',
        'pitches/brief.js',
        'jquery.numeric',
        'jquery.iframe-transport.js',
        'jquery.fileupload.js',
        'jquery.simplemodal-1.4.2.js',
        'jquery.tooltip.js',
        'jquery.damnUploader.js'
    ), array('inline' => false))?>
<?php else:?>
    <?=$this->html->script(array(
        'jquery-ui-1.11.4.min.js',
        'jquery.ui.touch-punch.min.js',
        'jquery-plugins/jquery.scrollto.min.js',
        'pitches/edit.js',
        'jquery.numeric',
        'jquery.iframe-transport.js',
        'jquery.fileupload.js',
        'jquery.simplemodal-1.4.2.js',
        'jquery.tooltip.js',
        'jquery.damnUploader.js'
    ), array('inline' => false))?>
<?php endif?>
<?=$this->html->style(array(
    '/css/common/receipt.css',
    '/brief',
    '/step3'), array('inline' => false))?>