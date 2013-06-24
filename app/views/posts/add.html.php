<div class="wrapper" xmlns="http://www.w3.org/1999/html">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner">
            <div class="content group">
                <div id="content_help">
                    <form action="/posts/save.json" method>
                        <section class="howitworks add-pitch">
                            <h1>Новый пост</h1>
                            <input type="hidden" id="id" value="" name="id" />
                            <div class="groupc">
                                <p>
                                    <label>Заголовок</label>
                                    <input type="text" name="title" />
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>Короткая новость</label>
                                    <textarea name="short" /></textarea>
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>Полный текст</label>
                                    <textarea name="full" /></textarea>
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>Дата</label>
                                    <input type="text" value="<?=date('Y-m-d H:i:s')?>" class="datepicker" name="created" />
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>URL заглавной картинкой (размер картинки 240*175)</label>
                                    <input type="text" name="imageurl" " />
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>Теги (через запятую)</label>
                                    <input type="text" name="tags" />
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>Опубликован?</label>
                                    <input type="checkbox" name="published" />
                                </p>
                            </div>

                            <input type="button" id="save" value="Сохранить" class="button" >
                            <a style="display:none;" id="preview" href="/posts/view/" type="button" target="_blank" class="button" >Предпросмотр<a/>

                        </section>
                    </form>
                </div>
            </div><!-- /content -->
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/help', '/brief', '/css/ui-lightness/jquery-ui-1.8.23.custom.css', '/css/datetimepicker.css'), array('inline' => false))?>
<?php $this->html->script(array('/js/tiny_mce/jquery.tinymce.js', '/js/jquery-ui-1.8.23.custom.min.js', '/js/datetimepicker.js','/js/posts/save.js'), array('inline' => false));?>