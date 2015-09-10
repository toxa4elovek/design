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
                                    <textarea name="short" id="short" /></textarea>
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>Полный текст</label>
                                    <textarea name="full" id="fulltext"  /></textarea>
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
                                    <label>Теги</label>
                                    <input type="text" id="typeahead" name="tags" />
                                </p>
                            </div>

                            <?php if($this->user->isAuthor()): ?>
                                <input type="hidden" value="0" name="published">
                            <?php else: ?>
                                <div class="groupc">
                                    <p>
                                        <label>Опубликован?</label>
                                        <input type="checkbox" id="published" name="published" />
                                    </p>
                                </div>
                            <?php endif ?>
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
<script>
var commonTags = [ <?php echo implode(',' , app\models\Post::getCommonTags()); ?> ];
var existingTags = [];
</script>
<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
<?=$this->html->style(array('/help', '/brief', '/css/ui-lightness/jquery-ui-1.8.23.custom.css', '/css/datetimepicker.css'), array('inline' => false))?>
<?php $this->html->script(array('/js/tiny_mce/jquery.tinymce.js', 'jquery-ui-1.11.4.min.js', '/js/datetimepicker.js', '/js/posts/textext.min.js', '/js/posts/save.js'), array('inline' => false));?>