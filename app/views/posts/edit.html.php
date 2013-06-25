<div class="wrapper" xmlns="http://www.w3.org/1999/html">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner">
            <div class="content group">
                <div id="content_help">
                    <form action="/posts/save.json" method>
                        <section class="howitworks add-pitch">
                            <h1>Новый пост</h1>
                            <input type="hidden" id="id" value="<?=$post->id?>" name="id" />
                            <div class="groupc">
                                <p>
                                    <label>Заголовок</label>
                                    <input type="text" name="title" value="<?=$post->title?>" />
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>Короткая новость</label>
                                    <textarea name="short" /><?=$post->short?></textarea>
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>Полный текст</label>
                                    <textarea name="full" /><?=$post->full?></textarea>
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>Дата</label>
                                    <input type="text" class="datepicker" name="created" value="<?=$post->created?>" />
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>URL заглавной картинкой (размер картинки 240*175)
                                    </label>
                                    <input type="text" name="imageurl" value="<?=$post->imageurl?>" />
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>Теги</label>
                                    <input type="text" name="tags" id="typeahead" />
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>Опубликован?</label>
                                    <input type="checkbox" name="published" <?php if($post->published == 1): echo 'checked="checked"'; endif;?>/>
                                </p>
                            </div>

                            <input type="button" id="save" value="Сохранить" class="button" >
                            <a href="/posts/view/<?=$post->id?>" type="button" target="_blank" class="button" >Предпросмотр<a/>

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
var existingTags = [ <?php echo implode(',', app\models\Post::parseExistingTags($post->tags)); ?> ];
</script>
<?=$this->html->style(array('/help', '/brief', '/css/ui-lightness/jquery-ui-1.8.23.custom.css', '/css/datetimepicker.css'), array('inline' => false))?>
<?php $this->html->script(array('/js/tiny_mce/jquery.tinymce.js', '/js/jquery-ui-1.8.23.custom.min.js', '/js/datetimepicker.js', '/js/posts/textext.min.js', '/js/posts/save.js'), array('inline' => false));?>