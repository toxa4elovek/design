<div class="wrapper">

    <?= $this->view()->render(array('element' => 'header'), array('header' => 'header2')) ?>

    <?php
        $word1 = 'Бланк ';
    ?>

    <script>
        var payload = {
            "receipt": <?php echo json_encode($receipt);?>
        }
    </script>

    <aside id="receipt-container"></aside>

    <div class="middle add-pitch" id="step1">

        <div class="main" style="padding-top: 50px;">

            <ol class="steps">
                <li class="current"><a href="#" class="steps-link" data-step="1">1. Гонорар</a></li>
                <li><a href="#" class="steps-link" data-step="2">2. Бриф</a></li>
                <!--li class="last"><a href="#" class="steps-link" data-step="3">3. Оплата</a></li-->
            </ol>

            <label style="
                font-family: Helvetica, serif; font-size: 11px; text-transform: uppercase; color: #666666;
            ">1. Вознаграждение победителю, руб.<?= $this->view()->render(array('element' => 'newbrief/required_star')) ?></label>
            <div>
                <div id="project-reward"></div>
                <div style="float: left; margin-left: 16px; margin-top: 4px;">
                    <p style="text-shadow: -1px 0 0 #FFFFFF;text-transform: uppercase; font-size: 12px; color: #888888;">ВАШ ТЕКУЩИЙ СЧЁТ: <?= $balance ?> р.</p>
                    <a style="font-weight: bold; color: #6590a2;" target="_blank" href="/subscription_plans/subscriber">пополнить</a>
                    <p style="text-shadow: -1px 0 0 #FFFFFF;margin-top: 6px; font-size: 12px; line-height: 16px; color: #757575">тариф «<?= $plan['title']?>»<br />
                        действителен до <?= $expirationDate ?></p>
                </div>
            </div>

            <div class="clear"></div>

            <div style="margin-top: 48px; margin-bottom: 50px;">
                <span style="
                    font-family: Helvetica, serif; font-size: 11px; text-transform: uppercase; color: #666666;
                ">2. Конец приёма работ</span>

                <span style="margin-left: 75px;
                    font-family: Helvetica, serif; font-size: 11px; text-transform: uppercase; color: #666666;
                ">Выбор победителя до:</span>

                <div style="margin-top: 16px;">
                    <?php
                    $defaultFinishDateTime = strtotime($defaultFinishDate);
                    $defaultChooseWinnerFinishDateTime = strtotime($defaultChooseWinnerFinishDate);
                    $months = array(
                        1 => 'Январь',
                        2 => 'Февраль',
                        3 => 'Март',
                        4 => 'Апрель',
                        5 => 'Май',
                        6 => 'Июнь',
                        7 => 'Июль',
                        8 => 'Август',
                        9 => 'Сентябрь',
                        10 => 'Октябрь',
                        11 => 'Ноябрь',
                        12 => 'Декабрь',
                    );
                    $monthFinishDate = $months[date('n', $defaultFinishDateTime)];
                    $monthChooseWinnerFinishDate = $months[date('n', $defaultChooseWinnerFinishDateTime)];
                    setlocale(LC_TIME, 'ru_RU');
                    ?>

                    <div class="finishDate brief-datetime-select editable_calendar">
                        <input style="width: 112px; height: 144px; cursor: pointer; position: absolute; opacity: 0; z-index:1;" type="text" class="first-datepick"/>
                        <h6 class="month" style="height: 27px; padding-top: 14px;color: #ffffff; text-transform: uppercase;font-size: 14px;text-align:center;"><?= $monthFinishDate?></h6>
                        <h5 class="day" style="text-align: center; padding-top: 22px; font-size: 53px; color: #666666; "><?= date('d', $defaultFinishDateTime) ?></h5>
                        <h6 class="weekday_time" style="padding-top: 22px;text-align: center;text-transform: uppercase; color: #666666; font-size: 14px"><?= strftime('%a', $defaultFinishDateTime)?>, <?= date('h:m', $defaultFinishDateTime) ?></h6>
                        <a href="#"  style="display: block; text-align: center; font-size: 12px;">изменить</a>
                        <input type="hidden" name="finishDate" value="<?= $defaultFinishDate ?>" />
                    </div>

                    <div style="float: left; width: 71px; height: 150px; background: url(/img/brief/arrow_right.png) no-repeat center center;"></div>

                    <div class="chooseWinnerFinishDate brief-datetime-select <?php if(in_array('chooseWinnerFinishDate', $plan['free'])): echo 'editable_calendar';endif;?>">
                        <input style="width: 112px; height: 144px; cursor: pointer; position: absolute; opacity: 0; z-index:1;" type="text" class="second-datepick <?php if(in_array('chooseWinnerFinishDate', $plan['free'])): echo 'editable';endif;?>"/>
                        <h6 class="month" style="height: 27px; padding-top: 14px;color: #ffffff; text-transform: uppercase;font-size: 14px;text-align:center;"><?= $monthChooseWinnerFinishDate?></h6>
                        <h5 class="day" style="text-align: center; padding-top: 22px; font-size: 53px; color: #666666; "><?= date('d', $defaultChooseWinnerFinishDateTime) ?></h5>
                        <h6 class="weekday_time" style="padding-top: 22px;text-align: center;text-transform: uppercase; color: #666666; font-size: 14px"><?= strftime('%a', $defaultChooseWinnerFinishDateTime)?>, <?= date('h:m', $defaultChooseWinnerFinishDateTime) ?></h6>
                        <?php if(in_array('chooseWinnerFinishDate', $plan['free'])):?>
                        <a href="#"  style="display: block; text-align: center; font-size: 12px;">изменить</a>
                        <?php endif?>
                        <input type="hidden" name="chooseWinnerFinishDate" value="<?= $defaultChooseWinnerFinishDate ?>" />
                    </div>
                </div>

                <div class="clear"></div>
            </div>

            <h1 style="background: url('/img/images/faq.png') no-repeat scroll 55% 0 transparent;	font-family: OfficinaSansBookC, serif;
                font-size: 12px;
                font-style: normal;
                font-variant: normal;
                font-weight: 400;
                height: 38px;
                line-height: 41px;
                text-align: center;
                text-transform: uppercase;margin-bottom:30px;">Дополнительные опции</h1>

            <div class="ribbon complete-brief" style="padding-top: 35px; height: 56px; padding-bottom: 0;">
                <p class="option"><label><input type="checkbox"  name="" class="single-check" data-option-title="Заполнение брифа" data-option-value=<?php if(in_array('phonebrief', $plan['free'])):?>"0"<?php else:?>"2750"<?php endif?> id="phonebrief">Заполнить бриф</label></p>
                <?php if(in_array('phonebrief', $plan['free'])):?>
                    <img class="brief-free-label" src="/img/brief/free_option.png" alt="" />
                <?php endif;?>
                <?php if(!in_array('phonebrief', $plan['free'])):?>
                <p class="label" style="text-transform: none;">2750р.</p>
                <?php endif;?>
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
                <p class="option">
                    <label>
                        <input type="checkbox" name="" class="single-check" data-option-title="Скрыть проект" data-option-value=<?php if(in_array('hideproject', $plan['free'])):?>"0"<?php else:?>"3500"<?php endif?> id="hideproject">Скрыть проект
                    </label>
                </p>
                <?php if(in_array('hideproject', $plan['free'])):?>
                <img class="brief-free-label" src="/img/brief/free_option.png" alt="" />
                <?php endif;?>
                <?php if(!in_array('hideproject', $plan['free'])):?>
                <p class="label" style="text-transform: none;">3500р.</p>
                <?php endif;?>
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
                <p class="option"><label><input type="checkbox" name="" class="single-check" data-option-title="«Прокачать» проект" data-option-value=<?php if(in_array('pinproject', $plan['free'])):?>"0"<?php else:?>"1000"<?php endif?> id="pinproject">«Прокачать» проект</label></p>
                <!--p class="description">Увеличить количество решений <a href="#" class="second tooltip" title="Вы сможете увеличить количество предложенных вариантов на 15-40%. Для привлечения <?php if($category->id == 7): echo 'копирайтеров'; else: 'дизайнеров'; endif;?> мы используем e-mail рассылку, facebook, vkontakte, twitter, выделение синим цветом в списке и на главной странице">(?)</a></p-->
                <?php if(in_array('pinproject', $plan['free'])):?>
                    <img class="brief-free-label" src="/img/brief/free_option.png" alt="" />
                <?php endif;?>
                <?php if(!in_array('pinproject', $plan['free'])):?>
                <p class="label" style="text-transform: none;">1000р.</p>
                <?php endif?>
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

            <p class="brief-example"><a href="/docs/brief_logo.pdf" target="_blank"></a></p>

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

    <div class="middle add-pitch" id="step2" style="display:none;">
        <div class="main" style="padding-top: 50px;">

            <ol class="steps">
                <li><a href="#" class="steps-link" data-step="1">1. Гонорар</a></li>
                <li class="current"><a href="#" class="steps-link" data-step="2">2. Бриф</a></li>
                <!--li class="last"><a href="#" class="steps-link" data-step="3">3. Оплата</a></li-->
            </ol>

            <?= $this->view()->render(array('element' => 'newbrief/pitchtitle_block'), compact('pitch', 'category', 'word1', 'defaultTitle')) ?>

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

            <div class="groupc">

                <p>
                    <label>Можно ли дополнительно использовать материал из банков с изображениями или шрифтами? <a href="#" class="second tooltip" title="Это даст возможность дизайнерам добиться лучшего результата. Профессионалы из мира рекламы часто прибегают к помощи фото-банков для экономии сил, времени или бюджета.">(?)</a></label>
                </p>
                <div style="float:left;width:50px;height:44px;padding-top:10px;">
                    <input style="vertical-align: middle" type="radio" name="materials" value="0" checked="checked"/><span class="radiospan">Нет</span>
                </div>
                <div style="float:left;width:50px;height:44px;padding-top:10px;">
                    <input style="vertical-align: middle" type="radio" name="materials" value="1" /><span class="radiospan">Да</span>
                </div>
                <div style="margin-bottom: 15px;"><input type="text" placeholder="допустимая стоимость одного изображения" style="width: 300px;" name="materials-limit" value=""></div>

            </div>

            <div class="groupc" style="margin-bottom: 30px; padding-bottom: 0px;">
                <p><label>Дополнительные материалы <a href="#" class="second tooltip" title="Присоедините все материалы, которые могут помочь креативщику. Это могут быть фотографии, приглянувшиеся аналоги, существующие логотипы, технические требования и т.д.">(?)</a></label></p>
                <div id="new-download" style="display:block;">
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

                <ul id="filezone"></ul>
                <div style="clear:both"></div>

                <p class="brief-example"><a href="/docs/brief_logo.pdf" target="_blank"></a></p><!-- .brief-example -->
            </div>

            <div class="groupc" style="margin-bottom: 19px; padding-bottom: 13px;">
                <?= $this->view()->render(array('element' => 'newbrief/fileformat'), array('pitch' => $pitch, 'category' => $category)); ?>
            </div>

            <div class="groupc" style="background: none repeat scroll 0 0 transparent; margin-bottom: 7px;">
                <p>
                    <label class="">Ваш контактный телефон <a href="#" class="second tooltip" title="Мы убедительно просим вас оставить ваш номер телефона для экстренных случаев, и если возникнут вопросы с оплатой.">(?)</a></label>
                </p>
                <div><input type="text" placeholder="" style="width: 400px;" name="phone-brief" value=""></div>
            </div>
        </div></div>

        <div class="tos-container supplement" style="margin-bottom: 20px; position: relative;">
            <label><input type="checkbox" name="tos" style="vertical-align: middle; margin-right: 5px;"/>Я прочитал(а) и выражаю безусловное согласие с условиями настоящего <a target="_blank" href="/docs/dogovor.pdf" style="text-decoration: none;">конкурсного соглашения</a>.</label>
            <?= $this->view()->render(array('element' => 'newbrief/required_star'), array('style' => "position: absolute; top:0;right:0")) ?>
        </div>
        <p class="submit submit-brief">
            <?= $this->view()->render(array('element' => 'newbrief/subscribe_step2fullbuttons')); ?>
        </p><!-- .submit -->

        </div>
    </div>

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
<?= $this->html->script(array(
    '/js/flux/flux.min.js',
    'jquery-ui-1.11.4.min.js',
    'jquery-plugins/jquery.scrollto.min.js',
    'jquery-deparam.js',
    '/js/jquery-plugins/jquery.numeric.min.js',
    '/js/jquery.damnUploader.js',
    'moment.min.js',
    '/js/ru.js',
    '/js/polyfills/browser-polyfill.min.js',
    '/js/polyfills/fetch.js',
    '/js/bootstrap/bootstrap-datetimepicker.min.js',
    '/js/pitches/classes/ReceiptAccessor.js',
    '/js/pitches/classes/SubscribedCart.js',
    '/js/pitches/actions/SubscribedBriefActions.js',
    'common/receipt/ReceiptLine.js',
    'common/receipt/ReceiptTotal.js',
    'common/receipt/Receipt.js',
    '/js/pitches/ProjectRewardInput.js',
    '/js/pitches/subscribed_brief.js',
    //'pitches/award_calculator.js',
    //'pitches/brief.js?' . mt_rand(100, 999),
    'jquery.iframe-transport.js',
    'jquery.fileupload.js',
    'jquery.simplemodal-1.4.2.js',
    'jquery.tooltip.js',
    'popup.js'
), array('inline' => false)) ?>
<?= $this->html->style(array(
    '/css/common/receipt.css',
    '/css/common/clear.css',
    '/css/common/buttons.css',
    '/css/common/project-search-results.css',
    '/css/common/project-search-widget.css',
    '/css/bootstrap/bootstrap.css',
    '/css/bootstrap/bootstrap-datetimepicker.min.css',
    '/css/bootstrap/bootstrap-datetimepicker-standalone.css',
    '/brief',
    '/step3',
    '/css/brief/subscribed_project.css'
), array('inline' => false))?>
