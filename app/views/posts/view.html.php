<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner">
            <div class="content group">
                <div id="content_help" style="width: 605px">
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
                            <p style="margin-bottom: 15px;margin-top:5px;text-transform:uppercase;font-size:11px;color:#666666">опубликовано: <a style="text-decoration:none;" href="/users/view/<?=$post->user->id?>"><?=$this->user->getFormattedName($post->user->first_name, $post->user->last_name)?></a> &bull; <?=date('d.m.Y', strtotime($post->created))?> &bull; <?=date('H:i', strtotime($post->created))?> &bull; <?php echo implode(' &bull; ', $tagstring)?>
                            &bull; Просмотров: <?=$post->views?>
                            </p>
                            <div class="regular viewpost">
                                <?php echo $post->full?>
                            </div>

                            <div style="margin-top:50px; clear:both;height:3px; background: url(/img/sep.png) repeat-x scroll 0 0 transparent;width:640px;margin-bottom:15px;"></div>
                            <?php if($post->published == 1):?>
                            <div style="">
                                    <div style="float:left;height:20px;margin-right:15px;">
                                        <div class="fb-like" data-href="http://www.godesigner.ru/posts/view/<?=$post->id?>" data-send="false" data-layout="button_count" data-width="50" data-show-faces="false" data-font="arial"></div>
                                    </div>
                                    <div style="float:left;height:20px;width:95px;">
                                        <div id="vk_like"></div>
                                    </div>
                                    <div style="float:left;height:20px;width:90px;">
                                        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.godesigner.ru/posts/view/<?=$post->id?>" data-text="<?=$post->title?>" data-lang="en" data-hashtags="Go_Deer">Tweet</a>
                                        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                                    </div>
                                    <div style="float:left;height:20px;width:70px;">
                                        <div class="g-plusone" data-size="medium"></div>
                                    </div>
                                    <div style="float:left;height:20px;width:70px;">
                                        <a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fposts%2Fview%2F<?=$post->id?>&media=<?=$post->imageurl?>&description=<?=$post->title?>" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
                                    </div>
                                    <div style="float:left;height:20px;width:80px;">
                                        <a target="_blank" class="surfinbird__like_button" data-surf-config="{'layout': 'common', 'width': '120', 'height': '20'}" href="http://surfingbird.ru/share">Серф</a>
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
                        <div id="livefyre-comments" style="margin-top: 20px"></div>
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
                        </script>
                        <!-- END: Livefyre Embed -->
                    </section>
                </div>
                <div id="right_sidebar_help" style="width:200px;">
                    <form id="post-search">
                        <input type="text" id="blog-search" name="search" value="" class="text">
                        <input type="submit" class="blog-submit" value="">
                    </form>
                    <table style="width: 200px;margin-bottom: 30px;">
                        <tr height="25"><td width="110"><a class="blogtaglink" href="/posts?tag=<?=urlencode('заказчикам')?>">заказчикам</a></td><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('дизайнерам')?>">дизайнерам</a></td></tr>
                        <tr height="25"><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('фриланс')?>">фриланс</a></td><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('интервью')?>">интервью</a></td></tr>
                        <tr height="25"><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('команда go')?>">команда go</a></td><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('герой месяца')?>">герой месяца</a></td></tr>
                        <tr height="25"><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('cовет в обед')?>">cовет в обед</a></td><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('топ 10')?>">топ 10</a></td></tr>
                        <tr height="25"><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('фриланс под пальмами')?>">фриланс под пальмами</a></td><td></td></tr>
                        <!--tr height="25"><td></td><td></td></tr-->
                    </table>
                    <div id="current_pitch">
                        <?php echo $this->stream->renderStream(10, false);?>
                    </div>
                </div>
            </div><!-- /content -->
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), 'http://surfingbird.ru/share/share.min.js', 'http://assets.pinterest.com/js/pinit.js', 'jquery.timeago', 'posts/view'), array('inline' => false))?>
<?=$this->html->style(array('/help', '/blog', 'disqus'), array('inline' => false))?>