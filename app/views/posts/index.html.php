<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner">
            <!-- <div id="post-seach-wrap" style="background: none repeat scroll 0 0 #F3F3F3;box-shadow: 3px 3px #D2D2D2;margin:20px 0;padding:20px 30px;">
                <form id="post-search" action="/posts/search">
                    <input type="text" id="search" name="search" value="" class="text">
                    <input type="submit" class="button second" style="margin-left: 30px" value="Поиск">
                </form>
            </div> -->
            <div class="content group">
                <div id="content_help" style="width:620px;">
                    <section class="howitworks">
                        <h1 style="margin-bottom: 30px;">Наш блог</h1>
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
                                        <?php if(($post->published == 1) && (strtotime($post->created) < time())):?>
                                        <a style="text-transform:uppercase;" href="/posts/view/<?=$post->id?>"><?=$post->title?></a>
                                        <?php else:?>
                                        <a style="text-transform:uppercase;color:#ccc;" href="/posts/view/<?=$post->id?>"><?=$post->title?></a>
                                        <?php endif?>
                                    </h2>
                                    <?php
                                    $tags = explode('|', $post->tags);
                                    $tagstring = '';
                                    foreach($tags as $tag):
                                        $tagstring[] = '<a class="blogtaglink" href="/posts?tag=' . urlencode($tag) . '">' . $tag . '</a>';
                                    endforeach;
                                    ?>
                                    <p style="text-transform:uppercase;font-size:11px;color:#666666"><?=date('d.m.Y', strtotime($post->created))?> &bull; <?=date('H:i', strtotime($post->created))?> &bull; <?php echo implode(' &bull; ', $tagstring)?></p>
                                    <div class="regular" style="margin-top:10px">
                                        <?php echo $post->short?>
                                    </div>
                                    <div style="height:1px;width:200px;margin-bottom:10px;"></div>

                                    <?php if($this->user->isEditor()):?>
                                    <a target="_blank" class="more-editor" href="/posts/edit/<?=$post->id?>" >редактировать</a>
                                    <a target="_blank" class="more-editor delete-post" href="/posts/delete/<?=$post->id?>" >удалить</a>
                                    <?php elseif($this->user->isPostAuthor($post->user_id)):?>
                                        <a target="_blank" class="more-editor" href="/posts/edit/<?=$post->id?>" >редактировать</a>
                                    <?php endif?>
                                    <a style="" class="more" href="/posts/view/<?=$post->id?>">Подробнее</a>
                                </div>
                                <div style="float:left;width:500px;margin-bottom: 20px; height:1px;"></div>
                            </div>
                            <?php if($currentIndex != $count):
                                $currentIndex += 1;
                                ?>
                            <div style="clear:both;height:3px; background: url(/img/sep.png) repeat-x scroll 0 0 transparent;width:588px;margin-bottom:20px;"></div>
                            <?php endif?>
                        <?php endforeach?>

                    <?php if(false): ?>
                                        <div class="page-nambe-nav">
                    <?php if($total > 1):
                        $url = '';
                        if($currenttag) $url = '&tag=' . $currenttag;
                        $prev = $page - 1;
                        if($prev < 1) $prev = 1?>
                        <a href="/posts?page=<?=$prev . $url?>">&#60;</a>

                        <?php if($total <= 5):?>
                            <?php for($i = 1; $i <= $total; $i++):?>
                                <?php if($page == $i):?>
                                    <a href="/posts?page=<?=$i . $url?>" class="this-page nav-page" rel="<?=$i?>"><?=$i?></a>
                                <?php else:?>
                                    <a href="/posts?page=<?=$i . $url?>" class="nav-page" rel="<?=$i?>"><?=$i?></a>
                                <?php endif?>
                            <?php endfor?>
                        <?php else:?>
                            <?php if(($page - 3) <= 0): ?>
                                <?php for($i = 1; $i <= 4; $i++): ?>
                                    <?php if($page == $i):?>
                                        <a href="/posts?page=<?=$i . $url?>" class="this-page nav-page" rel="<?=$i?>"><?=$i?></a>
                                    <?php else:?>
                                        <a href="/posts?page=<?=$i . $url?>" class="nav-page" rel="<?=$i?>"><?=$i?></a>
                                    <?php endif?>
                                <?php endfor?>
                                ... <a href="/posts?page=<?=$total . $url?>" class="nav-page" rel="<?=$total?>"><?=$total?></a>
                            <?php endif?>

                            <?php if((($page - 3) > 0) && ($total > ($page + 2))): ?>
                                <a href="/posts?page=1<?=$url?>" class="nav-page" rel="1">1</a> ...
                                <?php for($i = $page - 1 ; $i <= $page + 1; $i++):?>
                                    <?php if($page == $i):?>
                                        <a href="/posts?page=<?=$i . $url?>" class="this-page nav-page" rel="<?=$i?>"><?=$i?></a>
                                    <?php else:?>
                                        <a href="/posts?page=<?=$i . $url?>" class="nav-page" rel="<?=$i?>"><?=$i?></a>
                                    <?php endif?>
                                <?php endfor?>
                                 ... <a href="/posts?page=<?=$total . $url?>" class="nav-page" rel="<?php $total?>"><?=$total?></a>
                            <?php endif?>

                            <?php if($total <= ($page + 2)):?>
                                <a href="/posts?page=1<?=$url?>" class="nav-page" rel="1">1</a> ...
                                <?php for($i = $total - 3; $i <= $total; $i++): ?>
                                    <?php if($page == $i):?>
                                        <a href="/posts?page=<?=$i . $url?>" class="this-page nav-page" rel="<?=$i?>"><?=$i?></a>
                                    <?php else:?>
                                        <a href="/posts?page=<?=$i . $url?>" class="nav-page" rel="<?=$i?>"><?=$i?></a>
                                    <?php endif?>
                                <?php endfor?>
                            <?php endif?>

                        <?php endif;
                        $next = $page + 1;
                        if($next > $total) $next = $total;
                        ?>
                        <a href="/posts?page=<?=$next . $url?>">&#62;</a>
                    <?php endif?>
                    <!--a href="#">&#60;</a><a href="#" class="this-page">1</a><a href="#">2</a><a href="#">3</a><a href="#">4</a><a href="#">5</a><a href="#">6</a> ... <a href="#">7</a><a href="#">&#62;</a-->
                </div>
                <?php endif ?>
                    </section>
                </div>
                <div id="right_sidebar_help" style="width:200px;">
                    <h2 style="padding-right: 25px;font:20px 'RodeoC',serif; text-shadow: 1px 0 1px #FFFFFF;color:#999;text-transform: uppercase; text-align: center;margin-bottom:30px">Рубрики</h2>
                    <table style="width: 200px;margin-bottom: 30px;">
                        <tr height="25"><td width="110"><a class="blogtaglink" href="/posts?tag=<?=urlencode('заказчикам')?>">заказчикам</a></td><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('дизайнерам')?>">дизайнерам</a></td></tr>
                        <tr height="25"><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('фриланс')?>">фриланс</a></td><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('интервью')?>">интервью</a></td></tr>
                        <tr height="25"><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('команда go')?>">команда go</a></td><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('герой месяца')?>">герой месяца</a></td></tr>
                        <tr height="25"><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('cовет в обед')?>">cовет в обед</a></td><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('топ 10')?>">топ 10</a></td></tr>
                        <tr height="25"><td><a class="blogtaglink" href="/posts?tag=<?=urlencode('фриланс под пальмами')?>">фриланс под пальмами</a></td><td></td></tr>
                        <!--tr height="25"><td></td><td></td></tr-->
                    </table>
                    <div id="current_pitch">
                    <?php echo $this->stream->renderStream();?>
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