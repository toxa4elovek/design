<div class="wrapper pitchpanel login">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>

    <div class="middle">
        <div class="middle_inner_gallery" style="padding-top:25px">

            <div style="margin-left:280px;width: 560px; height:70px;margin-bottom:40px;">
                <?php if($pitch->user_id != $this->session->read('user.id') || $pitch->status > 0): ?>
                    <?=$this->view()->render(array('element' => 'pitch-info/designers_infotable'), array('pitch' => $pitch))?>
                <?php else: ?>
                    <?=$this->view()->render(array('element' => 'pitch-info/clients_infotable'), array('pitch' => $pitch))?>
                <?php endif ?>
            </div>

            <div id="pitch-title" style="height:36px;margin-bottom:5px;">
                <div class="breadcrumbs-view" style="width:770px;float:left;">
                    <a href="/pitches">Все питчи /</a> <a href="/pitches/view/<?=$pitch->id?>"><?=$pitch->title?></a>

                </div>
                <?php if($pitch->status == 0):?>
                <?php if(!in_array($pitch->id, $this->session->read('user.faves'))):?>
                    <div style="width:36px;height:36px;float:right;margin-right: 20px;">
                        <a data-pitchid="<?=$pitch->id?>" id="fav" data-type="add" href="#" title="Добавить в избранное"><img class="fav-plus" alt="добавить в избранное" src="/img/plus 2.png"></a>
                    </div>
                    <?php else:?>
                    <div style="width:36px;height:36px;float:right;margin-right: 20px;">
                        <a data-pitchid="<?=$pitch->id?>" id="fav" data-type="remove" href="#" title="Удалить из избранного"><img class="fav-minus" alt="Удалить из избранного" src="/img/minus.png"></a>
                    </div>
                    <?php endif?><?php endif?>
            </div>

            <h1 class="largeHeading">Загрузить решение</h1>

            <form action="/pitches/upload/<?=$pitch->id?>.json" method="post" id="solution" class="add-pitch upload-form" enctype="multipart/form-data">
                <div style="float:left; width: 627px;">
                    <div class="uploadblock" style="float:left;width: 190px;position:relative">


                        <input type="file" id="truebutton" multiple="multiple" name="solution[]" class="fileinput-button" style="display:block; opacity:0; position:relative;z-index:5; width: 190px; height: 50px;" />
                        <input id="fakebutton" type="button" class="button" style="position:absolute;z-index: 4;top:0;left:0;" value="Загрузить файлы">
                    </div>
                    <div style="clear:both;margin-bottom:10;"></div>
                    <ul id="filelist" class="supplement" style="margin-top: 10px;">
                        <li class="fakeinput" style=" padding-top: 1px; margin-left:0;">Файлы не выбраны</li>
                    </ul>
                    <div class="group" style="margin-top: 10px;background:none;margin-bottom:0;">
                        <p>
                            <label class="greyboldheader">Описание, концепция или примечание</label>
                            <textarea id="charzone" class="upload-textarea" style="height:130px; width: 557px;margin-top: 8px;" name="description"></textarea>
                        </p>
                    </div>
                </div>
                <div style="float:left; width: 180px;margin-right:20px; margin-bottom: 20px;"><p class="supplement">
                Пожалуйста, ознакомьтесь с правилами участия в <a href="http://www.godesigner.ru/answers/view/37">питче</a>. Прикрепляемые за раз файлы должны соответствовать одной концепции, быть в формате JPEG/RGB, не больше 5 Мбт, 600*400px, или уменьшенный по ширине до 800px эскиз сайта, подробнее <a href="http://www.godesigner.ru/answers/view/40">тут</a>. Если ваше решение победит в питче, вы загрузите 100% макеты и запрашиваемые в брифе файлы.
                </div>
                <div style="height:2px;clear:both;width:807px;background: url('/img/obnovleniya_line.jpg') repeat-x scroll 0 100% transparent; margin-bottom: 15px;"></div>

                <div style="float:left; width: 627px;">
                    <div class="group" style="background: none;margin-bottom:0;">
                            <div style="margin-bottom:20px;"  class="greyboldheader">Использование изображений</div>
                            <label style="margin-bottom: 15px;" class="checkboxtext label-switch"><input type="radio" name="licensed_work" value="0">ВСЯ РАБОТА - РЕЗУЛЬТАТ МОЕГО ТРУДА</label>
                            <div id="fine" style="display:none; margin-bottom:10px;margin-left:12px;">
                                <img src="/img/bravo.png" alt="Браво!">
                            </div>
                            <label style="margin-bottom: 30px;" class="checkboxtext label-switch"><input type="radio" name="licensed_work" value="1">РАБОТА СОДЕРЖИТ НЕ ТОЛЬКО МОИ ИЗОБРАЖЕНИЯ</label>
                        <div id="works" style="display:none;padding:15px;width:627px;height:44px;">
                            <div>
                                <input class="notransform" placeholder="Название изображения" value="" type="text" style="width:138px;margin-left:0px;margin-right:14px;float:left;text-transform:none;" name="filename[1]">
                                <input class="notransform" placeholder="http://" value="" type="text" style="width:200px;margin-left:0px;margin-right:22px;float:left;text-transform:none;" name="source[1]" >
                                <label style="color:#666666;width:65px;display:block;float:left;margin-right:40px;">
                                <input type="checkbox" name="needtobuy[1]" style="width:14px;display:block;float:left;margin-top:10px;"><span style="width:46px;display:block;float:left;margin-top:5px;">Нужно<br>покупать</span></label>
                                <input type="button" class="button" value="+" id="plusbutton" style="float:left;display:block;padding-left:20px;padding-right:20px;font-size:20px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div style="float:left; width: 180px;margin-right:20px;">
                <p class="supplement">Если вы использовали чужие изображения или фотографии из банков, пожалуйста, заявите об этом.</p>
                </div>

                <div style="height:1px;clear:both;width:807px;background: url('/img/obnovleniya_line.jpg') repeat-x scroll 0 100% transparent; margin-bottom: 15px;"></div>

                <div class="tos-container supplement">
                    <label><input type="checkbox" name="tos" style="margin-right:5px;"/>Я прочитал(а) и выражаю безусловное согласие с условиями настоящего <a href="/docs/dogovor.pdf" style="text-decoration: none;">конкурсного соглашения</a>.</label>
                </div>



                <div class="group" style="background:none;">
                    <?=$this->html->link('Отмена', array('controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id), array('class' => 'button second', 'style' => 'width:80px;margin-right:40px;')); ?>
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


<?=$this->html->script(array('jquery-ui-1.8.17.custom.min.js', 'jquery.iframe-transport.js', 'jquery.fileupload.js', 'fancybox/jquery.mousewheel-3.0.4.pack.js', 'fancybox/jquery.fancybox-1.3.4.pack.js', 'jquery.simplemodal-1.4.2.js', 'jquery.damnUploader.js', 'pitches/upload.js?' . mt_rand(100, 999)), array('inline' => false))?>
<?=$this->html->style(array('/view', '/messages12', '/pitches12', '/pitch_overview', '/upload','/jquery.fancybox-1.3.4.css'), array('inline' => false))?>