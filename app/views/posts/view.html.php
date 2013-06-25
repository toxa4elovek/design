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
                            <p style="margin-bottom: 15px;margin-top:5px;text-transform:uppercase;font-size:11px;color:#666666">опубликовано: <a style="text-decoration:none;" href="/users/view/<?=$post->user->id?>"><?=$this->nameInflector->renderName($post->user->first_name, $post->user->last_name)?></a> &bull; <?=date('d.m.Y', strtotime($post->created))?> &bull; <?=date('H:i', strtotime($post->created))?> &bull; <?php echo implode(' &bull; ', $tagstring)?>
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
                                        <a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fposts%2Fview%2F<?=$solution->id?>&media=<?=$post->imageurl?>&description=<?=$post->title?>" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
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
                            <!--div style="clear:both;height:3px; background: url(/img/sep.png) repeat-x scroll 0 0 transparent;width:640px;margin-bottom:20px;"></div-->
        <!--div id="disqus_thread"></div>
        <script type="text/javascript">
            /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
            var disqus_shortname = 'godesigner'; // required: replace example with your forum shortname
            var disqus_developer = 1;
            /* * * DON'T EDIT BELOW THIS LINE * * */
            (function() {
                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
            })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a-->
                        <!--script>
                            var idcomments_acct = '88393bdece6b387c5d426e2998f1806a';
                            var idcomments_post_id;
                            var idcomments_post_url;
                        </script>
                        <span id="IDCommentsPostTitle" style="display:none"></span>
                        <script type='text/javascript' src='http://www.intensedebate.com/js/genericCommentWrapperV2.js'></script-->
                        <!-- START: Livefyre Embed -->
                        <script type='text/javascript' src='http://zor.livefyre.com/wjs/v1.0/javascripts/livefyre_init.js'></script>
                        <script type='text/javascript'>
                            var fyre = LF({
                                site_id: 307155,
                                article_id: <?=$post->id?>
                            });
                        </script>
                        <!-- END: Livefyre Embed -->

                    </section>
                </div>
                <div id="right_sidebar_help" style="width:200px;">
                    <div id="current_pitch">
                    <?php echo $this->stream->renderStream();?>
                    </div>
                </div>
            </div><!-- /content -->
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), 'http://surfingbird.ru/share/share.min.js', 'http://assets.pinterest.com/js/pinit.js', 'jquery.timeago', 'posts/view'), array('inline' => false))?>
<?=$this->html->style(array('/help', '/blog', 'disqus'), array('inline' => false))?>