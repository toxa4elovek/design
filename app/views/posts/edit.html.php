<div class="wrapper" xmlns="http://www.w3.org/1999/html">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <?php
    if(!empty($post->lock)):?>
      <script>
          var myLock = '<?php echo md5($post->id . $this->user->getId()) ?>';
          var postLock = '<?= $post->lock ?>';
          if(myLock != postLock) {
              alert('Статья редактируется другим автором!');
              window.location.href = "/posts";
          }
      </script>
    <?php endif ?>

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
                                    <textarea name="short" id="short" /><?=$post->short?></textarea>
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>Полный текст</label>
                                    <textarea name="full" id="fulltext" /><?=$post->full?></textarea>
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
                                    <label>Автореклама (после первого параграфа)</label>
                                    <select name="blog_ad_id">
                                        <option value="0">Не использовать</option>
                                        <?php foreach($snippets as $snippet):?>
                                        <option <?php if($post->blog_ad_id == $snippet->id) echo ' selected="selected" ';?> value="<?= $snippet->id?>"><?= $snippet->title?></option>
                                        <?php endforeach;?>
                                    </select>
                                </p>
                            </div>

                            <div class="groupc">
                                <p>
                                    <label>Теги</label>
                                    <input type="text" name="tags" id="typeahead" />
                                </p>
                            </div>

                            <?php if($this->user->isAuthor()): ?>
                                <input type="hidden" value="<?= $post->published ?>" name="published">
                            <?php else: ?>
                                <div class="groupc">
                                    <p>
                                        <label>Опубликован?</label>
                                        <input type="checkbox" id="published" name="published" <?php if($post->published == 1): echo 'checked="checked"'; endif;?>/>
                                    </p>
                                </div>
                            <?php endif ?>
                            <input type="button" id="save" value="Сохранить" class="button" >
                            <a href="/posts/view/<?=$post->id?>" type="button" target="_blank" class="button post_preview">Предпросмотр<a/>

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
<?=$this->html->style(array('/help', '/brief', '/css/ui-lightness/jquery-ui.css', '/css/datetimepicker.css'), array('inline' => false))?>
<?php $this->html->script(array('/js/tinymce/tinymce.min.js', 'jquery-ui-1.11.4.min.js', '/js/datetimepicker.js', '/js/posts/textext.min.js', '/js/posts/save.js'), array('inline' => false));?>