<div class="wrapper" xmlns="http://www.w3.org/1999/html">

    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo'])?>

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
                                    <label>Автореклама (после первого параграфа)</label>
                                    <select name="blog_ad_id">
                                        <option value="0">Не использовать</option>
                                        <?php foreach ($snippets as $snippet):?>
                                            <option value="<?= $snippet->id?>"><?= $snippet->title?></option>
                                        <?php endforeach;?>
                                    </select>
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>Теги</label>
                                    <input type="text" id="typeahead" name="tags" />
                                </p>
                            </div>

                            <?php if ($this->user->isAuthor()): ?>
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
var commonTags = [ <?php echo implode(',', app\models\Post::getCommonTags()); ?> ];
var existingTags = [];
</script>
<?=$this->html->style(['/help', '/brief', '/css/ui-lightness/jquery-ui.css', '/css/datetimepicker.css'], ['inline' => false])?>
<?php $this->html->script(['/js/tinymce/tinymce.min.js', 'jquery-ui-1.11.4.min.js', '/js/datetimepicker.js', '/js/posts/textext.min.js', '/js/posts/save.js'], ['inline' => false]);?>