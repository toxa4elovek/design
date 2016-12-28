<div class="wrapper pitchpanel login">

    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo', 'header' => 'header2'])?>

    <div class="middle">
        <div class="middle_inner_gallery" style="padding-top:25px">
            <?=$this->view()->render(['element' => 'pitch-info/infotable'], ['pitch' => $pitch])?>

            <h1 class="largeHeading">Загрузить решение</h1>

            <form action="/pitches/uploadcopy/<?=$pitch->id?>.json" method="post" id="solution" class="add-pitch upload-form" enctype="multipart/form-data">
                <div style="float:left; width: 627px;">

                    <div class="group" style="background:none;margin-bottom:0;">
                        <p>
                            <label class="greyboldheader">Название, хедлайн или слоган</label>

                            <textarea id="charzone" class="upload-textarea" style="height:130px; width: 557px;margin-top: 8px;" name="description"></textarea>
                        </p>
                    </div>

                    <div class="uploadblock" style="float:left;width: 600px;margin-top: 10px;position:relative">
                        <input type="hidden" value="copy" name="type" id="uploadtype" />


                        <input type="file" multiple="multiple" name="solution[]" class="fileinput-button" style="display:block; opacity:0; position:relative;z-index:5; width: 190px; height: 50px;" />
                        <input type="button" class="button" style="position:absolute;z-index: 4;top:0;left:0;" value="Загрузить файлы">
                    </div>
                    <div style="clear:both;margin-bottom:10px;"></div>
                    <ul id="filelist" style="margin-top: 10px;margin-bottom: 10px;">
                        <li class="fakeinput" style=" padding-top: 1px; margin-left:10px;">Файлы не выбран</li>
                    </ul>

                </div>
                <div style="float:left; width: 180px;margin-right:20px; margin-bottom: 20px;"><p class="supplement">
                    Для копирайтинга достаточно написать идею в поле, или прикрепить документ в формате DOC, PDF, RTF не больше 5 Мбт. Если ваше решение победит в проекте, вы загрузите запрашиваемые заказчиком рабочие документы.
                </div>
                <div style="height:2px;clear:both;width:807px;background: url('/img/obnovleniya_line.jpg') repeat-x scroll 0 100% transparent; margin-bottom: 15px;"></div>

                <div class="tos-container supplement">
                    <label><input type="checkbox" name="tos" style="margin-right:5px;"/>Я прочитал(а) и выражаю безусловное согласие с условиями настоящего <a href="/docs/dogovor_2016.pdf" style="text-decoration: none;">конкурсного соглашения</a>.</label>
                </div>

                <div class="tos-container supplement">
                    <label><input type="checkbox" name="rights_tos" style="margin-right: 5px; margin-bottom: 2px;"/>Я прочитал и согласен с <a href="http://godesigner.ru/docs/Договор отчуждения исключительных прав.pdf" style="text-decoration: none;">договором передачи исключительных прав на произведение</a></label>
                    <?= $this->view()->render(['element' => 'newbrief/required_star'], ['tooltipClass' => "tooltip3"]) ?>
                </div>


                <div class="group" style="background:none;">
                    <?=$this->html->link('Отмена', ['controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id], ['class' => 'button second', 'style' => 'width:80px;margin-right:40px;']); ?>
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

<div id="loading-overlay" class="popup-final-step" style="display:none">
    <img src="/img/ajax-loader-4.gif" alt="Загрузка">
</div>


<?=$this->html->script(['jquery-ui-1.11.4.min.js', 'jquery.iframe-transport.js', 'jquery.fileupload.js', 'fancybox/jquery.mousewheel-3.0.4.pack.js', 'fancybox/jquery.fancybox-1.3.4.pack.js', 'jquery.simplemodal-1.4.2.js', 'jquery.charcount', 'pitches/upload-copy.js'], ['inline' => false])?>
<?=$this->html->style(['/view', '/messages12', '/pitches12', '/pitch_overview', '/upload', '/jquery.fancybox-1.3.4.css'], ['inline' => false])?>