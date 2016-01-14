<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner" style="padding-top: 42px;">
            <div class="content group" style="width: 100%;">
                <div id="content_help" style="width: 605px" onload="clearData();" onblur="clearData();">
                    <section class="howitworks">
                        <h1 class="h2link"><a href="/posts">Наш блог</a></h1>
                            <h2 class="largest-header-blog" style="text-transform:uppercase;margin-top:40px;"><?=$post->title?></h2>
                        <?php
                        $tags = explode('|', $post->tags);
                        $tagstring = '';
                        foreach($tags as $tag):
                            $tagstring[] = '<a class="blogtaglink" href="/posts?tag=' . urlencode($tag) . '">' . $tag . '</a>';
                        endforeach;
                        ?>
                            <p style="margin-bottom: 15px;margin-top:5px;text-transform:uppercase;font-size:11px;color:#666666">опубликовано: <a style="text-decoration:none;" href="/posts?author=<?=$post->user->id?>"><?=$this->user->getFormattedName($post->user->first_name, $post->user->last_name)?></a> &bull; <?=date('d.m.Y', strtotime($post->created))?> &bull; <?=date('H:i', strtotime($post->created))?> &bull; <?php echo implode(' &bull; ', $tagstring)?>
                            &bull; Просмотров: <?=$post->views?>
                            </p>

                            <?php if($post->published == 1):?>
                                <div style="margin-top: 25px;">
                                    <div style="margin-top: -20px;" class="social-likes" data-zeroes="yes" data-counters="yes" data-url="https://www.godesigner.ru/posts/view/<?=$post->id?>">
                                        <div style="margin: 7px 0 0 9px;" class="facebook" title="Поделиться ссылкой на Фейсбуке">SHARE</div>
                                        <div style="margin: 7px 0 0 7px;" class="twitter" data-via="Go_Deer">TWITT</div>
                                        <div style="margin: 7px 0 0 7px;" class="vkontakte" data-image="<?=$post->imageurl?>" title="Поделиться ссылкой во Вконтакте">SHARE</div>
                                        <div style="margin: 7px 0 0 7px;" class="pinterest" data-media="<?=$post->imageurl?>" title="Поделиться картинкой на Пинтересте">PIN</div>
                                    </div>
                                    <div style="clear:both;width:300px;height:1px;"></div>
                                </div>
                            <?php endif?>
                        <div style="margin-top:20px; clear:both;height:3px;width:600px;margin-bottom:40px;"></div>

                        <div class="regular viewpost">
                                <?php echo $post->full?>
                            </div>

                            <div style="margin-top:50px; clear:both;height:3px; background: url(/img/sep.png) repeat-x scroll 0 0 transparent;width:640px;margin-bottom:15px;"></div>
                            <?php if($post->published == 1):?>
                                <div style="">
                                    <div style="margin-top: -20px;" class="social-likes" data-zeroes="yes" data-counters="yes" data-url="https://www.godesigner.ru/posts/view/<?=$post->id?>">
                                        <div style="margin: 7px 0 0 9px;" class="facebook" title="Поделиться ссылкой на Фейсбуке">SHARE</div>
                                        <div style="margin: 7px 0 0 7px;" class="twitter" data-via="Go_Deer">TWITT</div>
                                        <div style="margin: 7px 0 0 7px;" class="vkontakte" data-image="<?=$post->imageurl?>" title="Поделиться ссылкой во Вконтакте">SHARE</div>
                                        <div style="margin: 7px 0 0 7px;" class="pinterest" data-media="<?=$post->imageurl?>" title="Поделиться картинкой на Пинтересте">PIN</div>
                                    </div>
                                    <div style="clear:both;width:300px;height:1px;"></div>
                                </div>
                            <?php endif?>
                        <?php if(count($related) > 0):?>
                            <div style="margin-top:15px;height:261px;width:610px;background-image: url(/img/relatedback.png)">
<?php
                                $i = 0;
                                foreach($related as $relatedpost):?>
<a class="relatedpost" href="/posts/view/<?=$relatedpost->id?>" style="padding-left:1px;height:256px;margin-top:3px;<?php if($i==2): echo 'float:right;'; else: echo 'float:left;'; endif?> <?php if($i==1):?>width: 197px;padding-left:7px;<?php else:?>width: 197px;<?php endif?>margin-right: 2px;">
<?php if($i == 0):?>
    <h2 style="margin-top: 16px;font-weight: bold; color:#999999;font-size:17px;text-shadow: 1px 0 1px #FFFFFF;">Вам будет интересно:</h2>
                                <?php endif?>
    <div style="width:191px;height:140px;background-image: url(/img/frame-small.png);<?php if($i==0):?>margin-top:10px;<?php else:?>margin-top:44px<?php endif?>">
        <img width="184" height="134" alt="" src="<?=$relatedpost->imageurl?>" style="margin-top:3px;margin-left:3px;">
    </div>
    <h2 class="title" style="padding-left:6px;padding-right:6px;margin-top:13px;font-size: 15px;text-shadow: 1px 0 1px #FFFFFF;color:#999999"><?=$relatedpost->title?></h2>
</a>
    <?php $i++;endforeach?>
                            </div><?php endif?>
                        <!-- START: Livefyre Embed -->
                        <!--div id="livefyre-comments" style="margin-top: 20px"></div>
                        <script type="text/javascript" src="http://zor.livefyre.com/wjs/v3.0/javascripts/livefyre.js"></script>
                        <script type="text/javascript">
                            (function () {
                                var articleId = fyre.conv.load.makeArticleId(null, <?= $post->id ?>);
                                fyre.conv.load({}, [{
                                    el: 'livefyre-comments',
                                    network: "livefyre.com",
                                    siteId: 307155,
                                    articleId: articleId,
                                    signed: false,
                                    collectionMeta: {
                                        articleId: articleId,
                                        url: fyre.conv.load.makeCollectionUrl()
                                    }
                                }], function() {});
                            }());
                        </script-->
                        <!-- END: Livefyre Embed -->
                    </section>
                </div>
                <div id="right_sidebar_help" style="width:200px;">
                    <form id="post-search">
                        <input type="text" id="blog-search" name="search" value="" class="text">
                        <input type="submit" class="blog-submit" value="">
                    </form>
                    <?=$this->view()->render(array('element' => 'posts/categories_menu'))?>
                    <div id="current_pitch">
                        <?php echo $this->stream->renderStream(10, false);?>
                    </div>
                </div>
            </div><!-- /content -->
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->script(array('/js/fotorama/jquery-1.8.0.min.js','/js/fotorama/jquery.validate.min.js','/js/fotorama/jquery.countdown.min.js', 'jquery.timeago', '/js/fotorama/fotorama.js', 'social-likes.min.js', 'posts/view'), array('inline' => false))?>
<?=$this->html->style(array('/js/fotorama/fotorama.css', '/help', '/blog', 'disqus', '/css/social-likes_flat'), array('inline' => false))?>