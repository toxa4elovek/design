<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner">
            <div class="content group">
                <section class="howitworks quiz result">
                    <h1><?php echo 'Пройти тест на профпригодность'; ?></h1>
                    <p>Результат: 12 из 15</p>
                    <h2 class="largest-header-blog">Вы— Маляр!</h1>
                    <p>Непыльная работёнка, вы лучший кандидат в команду Тома Сойера. <br>Хорошая занятость и сдельная зарплата вам обеспечены.</p>
                    <div class="share-this">
                        <span>Поделиться результатом:</span>
                        <?php if (true): ?>
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
                    </div>
                    <p>Этого,  однако, недостаточно для участия на платформе GoDesigner, <br> поэтому мы просим вас подтянуть профессиональные навыки! <br>Ваш аккаунт будет активирован через 9 дн. 21 ч. 38 мин.</p>
                </section>
            </div><!-- /content -->
            <div class="onTopMiddle">&nbsp;</div>
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<div class="onTop">&nbsp;</div>
<?=$this->html->style(array('/questions'), array('inline' => false))?>
