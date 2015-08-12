<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner">
            <div class="content group">
                <div id="content_help" style="width:620px;">
                    <section class="howitworks">
                        <h1 style="margin-bottom: 30px;" class="js-blog-index-title"><?php echo (empty($search)) ? 'Наш блог' : 'Результат поиска'; ?></h1>
                        <img id="search-ajax-loader" src="/img/blog-ajax-loader.gif">
                        <?php if (count($posts) == 0):?>
                            <div style="text-align: center;">
                                <h2 class="largest-header" style="line-height: 2em;">УПС, НИЧЕГО НЕ НАШЛИ!</h2>
                                <p class="large-regular">Попробуйте еще раз, изменив запрос.</p>
                            </div>
                        <?php endif; ?>
                        <?php
                        $currentIndex = 1;
                        $count = count($posts);
                        foreach($posts as $post):?>
                            <div>
                                <div style="float:left;width:249px;height:185px;background-image: url(/img/frame.png);margin-top:15px;">
                                    <img style="margin-top:4px;margin-left:4px;" width="240" height="175" src="<?=$post->imageurl?>" alt=""/>
                                </div>
                                <div style="float:left; width:330px; margin-left: 40px;">

                                    <h2 class="largest-header-blog">
                                        <?php if(($post->published == 1) && (strtotime($post->created) < (time() + HOUR))):?>
                                        <a style="line-height: 29px !important; display: block;" href="/posts/view/<?=$post->id?>"><?=$post->title?></a>
                                        <?php else:?>
                                        <a style="line-height: 29px !important; display: block; text-transform:uppercase;color:#ccc;" href="/posts/view/<?=$post->id?>"><?=$post->title?></a>
                                        <?php endif?>
                                    </h2>
                                    <?php
                                    $tags = explode('|', $post->tags);
                                    $tagstring = '';
                                    foreach($tags as $tag):
                                        $tagstring[] = '<a class="blogtaglink" href="/posts?tag=' . urlencode($tag) . '">' . $tag . '</a>';
                                    endforeach;
                                    ?>
                                    <p style="text-transform:uppercase;font-size:11px;color:#666666;margin-top: 10px;"><?=date('d.m.Y', strtotime($post->created))?> &bull; <?=date('H:i', strtotime($post->created))?> &bull; <?php echo implode(' &bull; ', $tagstring)?>
                                    </p>
                                    <div class="regular" style="margin-top: -5px;">
                                        <?php echo $post->short?>
                                    </div>
                                    <div style="height:1px;width:200px;margin-bottom: -5px;"></div>

                                    <?php if($this->user->isEditor()):?>
                                    <a target="_blank" class="more-editor" href="/posts/edit/<?=$post->id?>" >редактировать</a>
                                    <a target="_blank" class="more-editor delete-post" href="/posts/delete/<?=$post->id?>" >удалить</a>
                                    <?php elseif($this->user->isPostAuthor($post->user_id)):?>
                                        <a target="_blank" class="more-editor" href="/posts/edit/<?=$post->id?>" >редактировать</a>
                                    <?php endif?>
                                    <a style="" style="font-transorm: " href="/posts/view/<?=$post->id?>">Подробнее</a>
                                </div>
                                <div style="float:left;width:500px;margin-bottom: 12px; height:1px;"></div>
                            </div>
                            <?php if($currentIndex != $count):
                                $currentIndex += 1;
                                ?>
                            <div style="clear:both;height:3px; background: url(/img/sep.png) repeat-x scroll 0 0 transparent;width:588px;margin-bottom:20px;"></div>
                            <?php endif?>
                        <?php endforeach?>
                    </section>
                </div>
                <div id="right_sidebar_help" style="width:200px;">
                    <form id="post-search">
                        <input type="text" id="blog-search" name="search" value="<?=$search?>" class="text">
                        <input type="submit" class="blog-submit" value="">
                    </form>
                    <?=$this->view()->render(array('element' => 'posts/categories_menu'))?>
                    <div id="current_pitch">
                    <?php echo $this->stream->renderStream(10, false);?>
                    </div>
                </div>
            </div><!-- /content -->
            <div id="blog-ajax-wrapper">
                <div id="blog-ajax-loader">&nbsp;</div>
            </div>
            <div class="onTopMiddle">&nbsp;</div>
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<div class="onTop">&nbsp;</div>
<?=$this->html->script(array('jquery.timeago', 'posts/index'), array('inline' => false))?>
<?=$this->html->style(array('/help', '/blog'), array('inline' => false))?>