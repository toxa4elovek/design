<div class="wrapper pitchpanel login">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>

    <div class="middle">
        <div class="middle_inner_gallery" style="padding-top:25px; padding-left: 40px;">
            <div id="pitch-title" style="height:36px;margin-bottom:5px;">
                <div class="breadcrumbs-view" style="width:840px; margin: 30px 0 20px 0; float:left;">
                    <a href="/pitches">Все питчи /</a> <a href="/pitches/view/<?=$pitch->id?>"><?=$pitch->title?></a>
                </div>
            </div>
            <div class="crl" style="width: 840px; margin-bottom: 30px;">
                <?=$this->view()->render(array('element' => 'pitch-info/infotable'), array('pitch' => $pitch))?>
            </div>



            <form action="/pitches/uploadfile/<?=$pitch->id?>.json" method="post" id="solutionfiles" class="add-pitch upload-form" enctype="multipart/form-data">
                <input type="hidden" name="uploadnonce" id="uploadnonce" value="<?php echo $uploadnonce; ?>">
                <input type="hidden" name="fileposition" id="fileposition" value="">
                <div class="upload-dropzone-wrapper">
                    <div id="scrollerarea">
                        <div id="scroller" class="ui-draggable"></div>
                    </div>
                    <div class="upload-dropzone">
                        <input type="file" id="truebutton" multiple="multiple" name="solution[]" class="fileinput-button">
                        <input id="fakebutton" type="button" class="button" value="Выберите файлы">
                    </div>
                </div>
            </form>
            <form action="/pitches/uploaddata/<?=$pitch->id?>.json" method="post" id="solution" class="add-pitch upload-form">
                <input type="hidden" name="uploadnonce" id="uploadnonce" value="<?php echo $uploadnonce; ?>">
                <div style="float:left; width: 627px;">
                    <ul id="filelist" class="supplement" style="margin-top: 10px;">
                        <li class="fakeinput" style=" padding-top: 1px; margin-left:0;"></li>
                    </ul>
                    <div class="group">
                        <p>
                            <label class="greyboldheader">Опишите идею</label>
                            <textarea id="charzone" class="upload-textarea" style="height:130px; width: 557px;margin-top: 8px;" name="description"></textarea>
                        </p>
                    </div>
                </div>
                <div style="float:left; width: 230px; margin-bottom: 20px; margin-top: 40px;"><p class="supplement">
                Прикрепляемые за раз файлы должны соответствовать одной концепции, быть в формате JPEG/RGB, max 800px по ширине, &lt;&nbsp;5&nbsp;МБ. Если ваше решение победит в питче, вы загрузите запрашиваемые заказчиком рабочие файлы.
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
                    <label><input type="checkbox" name="tos" style="margin-right: 5px; margin-bottom: 2px;"/>Я прочитал и согласен с <a href="/docs/dogovor.pdf" style="text-decoration: none;">правилами и условиями</a> Go Designer</label>
                </div>



                <div class="group" style="background:none;">
                    <?=$this->html->link('Отмена', array('controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id), array('class' => 'button second', 'style' => 'width:80px;margin-right:40px;')); ?>
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
            <input type="hidden" id="redirect-value" value="/pitches/view/<?=$pitch->id?>">
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
    <div style="color: rgb(202, 202, 202); font-size: 14px; margin-top: 20px;">Пожалуйста, используйте эту паузу<br> с пользой для здоровья!</div>
</div>


<?=$this->html->script(array('jquery-ui-1.8.23.custom.min.js', 'jquery.iframe-transport.js', 'jquery.fileupload.js', 'fancybox/jquery.mousewheel-3.0.4.pack.js', 'fancybox/jquery.fancybox-1.3.4.pack.js', 'jquery.simplemodal-1.4.2.js', 'pitches/upload.js?' . mt_rand(100, 999)), array('inline' => false))?>
<?=$this->html->style(array('/view', '/messages12', '/pitches12', '/pitch_overview', '/upload','/jquery.fancybox-1.3.4.css'), array('inline' => false))?>