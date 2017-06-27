<?php
$job_types = [
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
    'it' => 'Компьютеры / IT'];
?>
<div class="wrapper pitchpanel login">

    <?= $this->view()->render(['element' => 'header'], ['logo' => 'logo', 'header' => 'header2']) ?>

    <div class="middle">
        <div class="middle_inner_gallery" style="padding-top:25px; padding-left: 40px;">
            <?= $this->view()->render(['element' => 'pitch-info/infotable'], ['pitch' => $pitch]) ?>

            <form action="/pitches/uploadfile/<?= $pitch->id ?>.json" method="post" id="solutionfiles" class="add-pitch upload-form" enctype="multipart/form-data">
                <input type="hidden" name="uploadnonce" id="uploadnonce" value="<?php echo $uploadnonce; ?>">
                <input type="hidden" name="fileposition" id="fileposition" value="">
                <div class="upload-dropzone-wrapper" <?php if ($fullResolution):?>style="background-image: url('/img/upload-dropzone-sub.png');"<?php endif;?>>
                    <div id="scrollerarea">
                        <div id="scroller" class="ui-draggable"></div>
                    </div>
                    <div class="upload-dropzone">
                        <input type="file" id="truebutton" multiple="multiple" name="solution[]" class="fileinput-button">
                        <input id="fakebutton" type="button" class="button" value="Выберите файлы">
                    </div>
                </div>
            </form>
            <form action="/pitches/uploaddata/<?= $pitch->id ?>.json" method="post" id="solution" class="add-pitch upload-form">
                <input type="hidden" name="uploadnonce" id="uploadnonce" value="<?php echo $uploadnonce; ?>">
                <div style="float:left; width: 627px;">
                    <ul id="filelist" class="supplement" style="margin-top: 10px;">
                        <li class="fakeinput" style=" padding-top: 1px; margin-left:0;"></li>
                    </ul>
                    <p class="output-p">
                        <label class="greyboldheader">Укажите 5 тегов, которые описывают <?php if ($pitch->category_id == 1):?>логотип<?php else:?>решение<?php endif?> <?= $this->view()->render(['element' => 'newbrief/required_star'], ['tooltipClass' => "tooltip3"]) ?></label>
                    </p>
                    <div id="filterContainer">
                        <ul class="tags" id="filterbox" style="margin-left: 9px"></ul>
                        <?php
                        $placeholders = [
                            'Мясо, комбинат, красный, вкусный, колбаса',
                            'Лаванда, фиолетовый, травы, успокаивающий, сон',
                            'Инвестиции, банк, деньги, синий, it'
                        ];
                        $key = array_rand($placeholders);
                        ?>
                        <input type="text" placeholder="<?= $placeholders[$key] ?>" id="searchTerm" style="padding-bottom:10px; width:545px; box-shadow:none;line-height:12px; height:13px; padding-top: 9px;margin-left:0; padding-left: 10px">
                    </div>

                    <p class="output-p" style="margin-top: 23px;">
                        <label id ="show-types" class="greyboldheader"><span id="job-type" class="greyboldheader">+</span>Выберите вид деятельности</label>
                    </p>
                    <ul id="list-job-type" style="margin-top: -12px;">
                        <?php
                        $industry = (unserialize($pitch->industry));
                        $_empty = empty($industry);
                        foreach ($job_types as $k => $v):
                            ?>
                            <li>
                                <label><input type="checkbox" name="job-type[]" value="<?= $k ?>"<?= ($_empty) ? : (in_array($k, $industry) ? ' checked' : '') ?>><?= $v ?></label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div style="height:2px;clear:both;width:807px;background: url('/img/obnovleniya_line.jpg') repeat-x scroll 0 100% transparent; margin-bottom: 15px;"></div>
                    <div class="group">
                        <p>
                            <label class="greyboldheader">Опишите идею</label>
                            <textarea id="charzone" class="upload-textarea" name="description"></textarea>
                        </p>
                    </div>
                </div>
                <div style="float:left; width: 230px; margin-bottom: 20px;"><p class="supplement"><?php if ($pitch->category_id == 1):?>
                        Это поможет найти ваш логотип тем, кто захочет его купить. Т.о мы дарим вам возможность продать работу, если та не станет победителем с первого раза.
                    <?php endif ?>
                </div>
                <div style="float:left; width: 230px; margin-bottom: 20px;<?php if ($pitch->category_id != 1):?>margin-top:188px;<?php else:?>margin-top:63px;<?php endif?>">
                    <p class="supplement">
                        Для копирайтинга достаточно написать идею в поле, или прикрепить документ в формате TXT, PDF или JPEG/RGB, не больше 5 Мб, <?php if ($fullResolution):?>100% разрешение<?php else:?>800*800px<?php endif?>. Если ваше решение победит в проекте, вы загрузите запрашиваемые заказчиком рабочие документы.
                    </p>
                </div>
                <div style="height:2px;clear:both;width:807px;background: url('/img/obnovleniya_line.jpg') repeat-x scroll 0 100% transparent; margin-bottom: 15px;"></div>

                <div style="float:left; width: 627px;">
                    <div class="group" style="background: none;margin-bottom:0;">
                        <div style="margin-bottom:20px;"  class="greyboldheader">Укажите источники изображений и шрифтов</div>
                        <label style="margin-bottom: 15px;" class="checkboxtext label-switch"><input type="radio" name="licensed_work" value="0">ВСЯ РАБОТА — РЕЗУЛЬТАТ МОЕГО ТРУДА</label>
                        <div id="fine" style="display:none; margin-bottom:10px;margin-left:12px;">
                            <img src="/img/bravo.png" alt="Браво!">
                        </div>
                        <label style="margin-bottom: 30px;" class="checkboxtext label-switch"><input type="radio" name="licensed_work" value="1">РАБОТА СОДЕРЖИТ НЕ ТОЛЬКО МОИ ИЗОБРАЖЕНИЯ</label>
                        <div id="works" style="display:none;padding:15px;width:627px;height:44px;">
                            <div>
                                <input class="notransform" placeholder="Название изображения" value="" type="text" name="filename[1]">
                                <input class="notransform" placeholder="http://" value="" type="text" name="source[1]" >
                                <label style="color:#666666;width:65px;display:block;float:left;margin-right:40px;">
                                    <input type="checkbox" name="needtobuy[1]" style="width:14px;display:block;float:left;margin-top:10px;"><span style="width:46px;display:block;float:left;margin-top:5px;">Нужно<br>покупать</span></label>
                                <input type="button" class="button" value="+" id="plusbutton" style="float:left;display:block;padding-left:20px;padding-right:20px;font-size:20px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div style="float:left; width: 230px;">
                    <p class="supplement">Если вы использовали чужие изображения, платные шрифты или фотографии из банков, пожалуйста, заявите об этом.</p>
                </div>

                <div style="height:1px;clear:both;width:807px;background: url('/img/obnovleniya_line.jpg') repeat-x scroll 0 100% transparent; margin-bottom: 15px;"></div>

                <div class="tos-container supplement">
                    <label><input type="checkbox" name="tos" style="margin-right: 5px; margin-bottom: 2px;"/>Я прочитал и согласен с <a href="/docs/dogovor_oferta_06_2017.pdf" style="text-decoration: none;">правилами и условиями</a> Go Designer</label>
                    <?= $this->view()->render(['element' => 'newbrief/required_star'], ['tooltipClass' => "tooltip3"]) ?>
                </div>

                <div class="tos-container supplement">
                    <label><input type="checkbox" name="rights_tos" style="margin-right: 5px; margin-bottom: 2px;"/>Я прочитал и согласен с <a href="/docs/dogovor_otchuzhdenia_2017.pdf" style="text-decoration: none;">договором передачи исключительных прав на произведение</a></label>
                    <?= $this->view()->render(['element' => 'newbrief/required_star'], ['tooltipClass' => "tooltip3"]) ?>
                </div>


                <div class="group" style="background:none;">
                    <?= $this->html->link('Отмена', ['controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id], ['class' => 'button second', 'style' => 'width:80px;margin-right:40px;']); ?>
                    <input type="hidden" id="reSortable" name="reSortable" value="" />
                    <input id="uploadSolution" type="submit" class="button" value="Отправить"/>
                </div>



                <a href="#uploading" style="display:none;">.</a>
                <div style="display: none;">
                    <div id="uploading" style="width:200px;height:100px;overflow:auto;">
                        Файл загружается
                    </div>
                </div>
                <a href="#invalid" style="display:none;">.</a>
                <div style="display: none;">
                    <div id="invalid" style="width:200px;height:100px;overflow:auto;">
                        Вы не заполнили все обязательные поля.
                    </div>
                </div>
            </form>
            <input type="hidden" id="redirect-value" value="/pitches/view/<?= $pitch->id ?>">
        </div><!-- /solution -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div>

</div><!-- /middle_inner -->


</div><!-- /middle -->

</div><!-- .wrapper -->

<div id="loading-overlay" class="popup-final-step" style="display:none;width:353px;text-align:center;text-shadow:none;">
    <div style="margin-top: 15px; margin-bottom:20px; color:#afafaf;font-size:14px"><span id="progressbar">0%</span></div>
    <div id="progressbarimage" style="text-align: left; padding-left: 6px; padding-top: 1px; padding-right: 6px; height: 23px; background: url('/img/indicator_empty.png') repeat scroll 0px 0px transparent; width: 341px;">
        <img id="filler" src="/img/progressfilled.png" style="width:1px" height="22">
    </div>
    <div style="color: rgb(202, 202, 202); font-size: 14px; margin-top: 20px;">Пожалуйста, используйте эту паузу<br> с пользой для здоровья!</div>
</div>

<?= $this->view()->render(['element' => 'popups/brief_tos']); ?>

<?= $this->html->script([
    'jquery-ui-1.11.4.min.js',
    'typeahead.jquery.min.js',
    'bloodhound.min.js',
    'jquery.keyboard.js',
    'jquery.iframe-transport.js',
    'jquery.fileupload.js',
    'fancybox/jquery.mousewheel-3.0.4.pack.js',
    'jquery-plugins/jquery.scrollto.min.js',
    'fancybox/jquery.fancybox-1.3.4.pack.js',
    'jquery.simplemodal-1.4.2.js',
    'jquery.tooltip.js',
    'pitches/upload.js?' . mt_rand(100, 999)], ['inline' => false]) ?>
<?=
$this->html->style(['/view', '/messages12', '/pitches12', '/pitch_overview', '/upload', '/jquery.fancybox-1.3.4.css'], ['inline' => false])?>