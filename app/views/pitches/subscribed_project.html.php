<div class="wrapper">

    <?= $this->view()->render(array('element' => 'header'), array('header' => 'header2')) ?>
    <aside class="summary-price expanded">
        <h3>Итого:</h3>
        <p class="summary"><strong id="total-tag">0.-</strong></p><!-- .summary -->
        <ul id="check-tag">
        </ul>
        <a href="#" class="show" id="show-check"><span>Подробнее</span></a>
        <a href="#" class="hide" id="hide-check"><span>Скрыть</span></a>
    </aside><!-- .summary-price -->

    <div class="middle add-pitch" id="step1">

        <div class="main" style="padding-top: 50px;">

            <ol class="steps">
                <li class="current"><a href="#" class="steps-link" data-step="1">1. Гонорар</a></li>
                <li><a href="#" class="steps-link" data-step="2">2. Бриф</a></li>
                <li class="last"><a href="#" class="steps-link" data-step="3">3. Оплата</a></li>
            </ol><!-- .steps -->

            <label style="
                font-family: Helvetica, serif; font-size: 11px; text-transform: uppercase; color: #666666;
            ">1. Вознаграждение победителю, руб.<?= $this->view()->render(array('element' => 'newbrief/required_star')) ?></label>
            <div>
                <input type="text" value="" placeholder="" style="
                float: left;
                    display: block; width: 250px; height: 74px; margin-top: 4px;    font-family: Arial, serif;
        font-size: 37px;
        line-height: 37px;
        color: #000000;
                " />
                <div style="float: left; margin-left: 16px; margin-top: 4px;">
                    <p style="text-transform: uppercase; font-size: 12px; color: #888888;">ВАШ ТЕКУЩИЙ СЧЁТ: 2000 р.</p>
                    <a style="font-weight: bold; color: #6590a2; " href="/subscription_plans/subscriber">пополнить</a>
                    <p style="margin-top: 6px; font-size: 12px; line-height: 16px; color: #757575">тариф «Фирменный»<br />
                        действителен до 12.12.2016</p>
                </div>
            </div>

            <div class="clear"></div>

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

</div><!-- .wrapper -->

<div id="loading-overlay" class="popup-final-step" style="display:none;width:353px;text-align:center;text-shadow:none;">
    <div style="margin-top: 15px; margin-bottom:20px; color:#afafaf;font-size:14px"><span id="progressbar">0%</span></div>
    <div id="progressbarimage" style="text-align: left; padding-left: 6px; padding-top: 1px; padding-right: 6px; height: 23px; background: url('/img/indicator_empty.png') repeat scroll 0px 0px transparent; width: 341px;">
        <img id="filler" src="/img/progressfilled.png" style="width:1px" height="22">
    </div>
    <div style="color: rgb(202, 202, 202); font-size: 14px; margin-top: 20px;">Пожалуйста, используйте эту паузу<br> с пользой для здоровья!</div>
</div>

<?= $this->view()->render(array('element' => 'popups/brief_tos')); ?>
<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
<?= $this->html->script(array(
    'jquery-ui-1.8.17.custom.min.js',
    'jquery.scrollto.min.js',
    'jquery-deparam.js',
    'pitches/award_calculator.js',
    'pitches/brief.js',
    'jquery.numeric',
    'jquery.iframe-transport.js',
    'jquery.fileupload.js',
    'jquery.simplemodal-1.4.2.js',
    'jquery.tooltip.js',
    'popup.js',
    'jquery.damnUploader.js'), array('inline' => false)) ?>
<?= $this->html->style(array(
    '/css/common/receipt.css',
    '/css/common/clear.css',
    '/brief',
    '/step3',
    '/css/brief/subscribed_project.css'
), array('inline' => false))?>
