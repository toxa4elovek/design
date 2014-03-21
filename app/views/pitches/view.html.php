<div class="wrapper pitchpanel login">

	<?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>

	<div class="middle">
	<div class="middle_inner_gallery" style="padding-top:25px">
    <?php if($this->user->isPitchOwner($pitch->user_id)):?>
    <div id="dinamic" style="display:none;position: fixed; z-index: 15; bottom: 0; opacity:0.8; margin-left: 740px">
        <div class="bubble">
            <span>Возврат денег недоступен:</span><br>
            <span class="lowReason"></span><br><br>
            <a href="/answers/view/71">Как это исправить?</a>
        <div id="bubble-close"></div>
        </div>
        <div style="width:150px;height:190px;text-align;center">
            <div style="background-image:url(/img/big-krug.png);margin-top:4px;height:132px;width:132px;">
                <canvas id="canFloat" height="132" width="132" style="">
                </canvas></div>
            <div style="background: url('/img/krug-small.png') no-repeat scroll 32px 30px transparent;height: 82px; width: 87px; position: relative; top: -132px; bottom: 0px; z-index: 15; padding-top: 50px; padding-left: 45px;">
                <h2 id="avgPointsFloat" style="font-size:28px;color:#666666;text-shadow: -1px 0 0 #FFFFFF;width:40px;text-align:center"></h2>
                <h2 id="avgPointsStringFloat" style="color: rgb(102, 102, 102); text-align: center; text-shadow: -1px 0px 0px rgb(255, 255, 255); margin-left: 0px; margin-top: 4px; font-size: 9px; width: 44px;">БАЛЛА</h2>
            </div>
        </div>
    </div>
    <?php endif?>

    <input type="hidden" value="<?=$pitch->id?>" name="pitch_id">
                <div style="margin-left:280px;width: 560px; height:70px;margin-bottom:40px;">
                    <?=$this->view()->render(array('element' => 'pitch-info/infotable'), array('pitch' => $pitch))?>
                </div>

                <div id="pitch-title" style="height:36px;margin-bottom:5px;">
                    <div class="breadcrumbs-view" style="<?php if($pitch->status == 0): echo 'width: 770px;'; else: echo 'width: 640px;'; endif?>float:left;">
                        <a href="/pitches">Все питчи /</a> <a href="/pitches/view/<?=$pitch->id?>"><?=$pitch->title?></a>
                    </div>
                    <?php if($pitch->status == 0):?>
                        <?= $this->view()->render(array('element' => 'pitch-info/favourite_status'), array('pitch' => $pitch))?>
                    <?php endif?>
                </div>

                <div class="menu">
                    <ul>
                        <li class="first_li">
                            <?=$this->html->link('Решения', array('controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id), array('class' => 'selected menu-toggle', 'data-page' => 'gallery'))?>
                        </li>
                        <li>
                            <?=$this->html->link('Бриф', array('controller' => 'pitches', 'action' => 'details', 'id' => $pitch->id), array('class' => 'menu-toggle', 'data-page' => 'brief'))?>
                        </li>
                    </ul>
                </div>

        <nav class="other_nav_gallery clear">
            <p class="supplement4" style="margin-left:200px;float:left;height:30px;padding-top:4px;font-weight: bold; color:#b2afaf;">
                <span style="display: inline-block; margin-top: 4px; vertical-align: top;">СОРТИРОВАТЬ ПО:</span>
                <a class="sort-by-rating<?php if ($sort == 'rating'):?> active<?php endif;?>" href="/pitches/view/<?=$pitch->id?>?sorting=rating"><span title="сортировать по рейтингу"></span></a>
                <a class="sort-by-likes<?php if ($sort == 'likes'):?> active<?php endif;?>" href="/pitches/view/<?=$pitch->id?>?sorting=likes"><span title="сортировать по лайкам"></span></a>
                <a class="sort-by-created<?php if ($sort == 'created'):?> active<?php endif;?>" href="/pitches/view/<?=$pitch->id?>?sorting=created"><span title="сортировать по дате создания"></span></a>
            </p>
            <?php
            if(!$this->user->isPitchOwner($pitch->user_id) && ($pitch->status < 1) && ($pitch->published == 1)):?>
                <a href="/pitches/upload/<?=$pitch->id?>" class="button" style="font-family:Arial,sans-serif;color:#ffffff;display:block;float:right;margin-right:20px;width:155px">предложить решение</a>
                <?php elseif(($pitch->status == 1) && ($pitch->awarded == 0)):?>
                <img src="/img/status1.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Идет выбор победителя"/>
                <?php elseif(($pitch->status == 1) && ($pitch->awarded != 0)):?>
                <img src="/img/winner-selected.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Победитель выбран"/>
                <?php elseif($pitch->status == 2):?>
                <img src="/img/status2.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Питч завершен"/>
            <?php else:?>
                <a href="http://www.godesigner.ru/answers/view/78" class="button" style="font-family:Arial,sans-serif;color:#ffffff;display:block;float:right;margin-right:20px;width:178px">инструменты заказчика</a>
            <?php endif;?>
        </nav>

    <div class="portfolio_gallery" style="padding-top:32px;">
    <div class="pht"></div>
            <?php
            $mySolutionList = array();
            $mySolutionNumList = array();
            if((count($solutions) > 0) && ($pitch->published == 1)): ?>
            <ul class="list_portfolio main_portfolio">
                <?=$this->view()->render(array('element' => 'gallery'), compact('solutions', 'pitch', 'selectedsolution', 'sort', 'canViewPrivate', 'solutionsCount'))?>
            </ul>
            <?php else:?>
            <div class="bigfont">
                <h2 class="title">Ещё никто не выложил свои идеи.</h2>
                <?php if(!$this->user->isPitchOwner($pitch->user_id)):?>
                <h2 class="title"><?=$this->html->link('предложи свое решение', array('controller' => 'pitches', 'action' => 'upload', 'id' => $pitch->id), array('escape' => false))?></h2>
                <h2 class="title">и стань первым!</h2>
                <?php endif?>
            </div>
            <?php endif;?>
        </div>
        <?php if (count($solutions) < $solutionsCount): ?>
        <div class="gallery_postload">
            <div class="separator"></div>
            <div class="gallery_postload_loader"><img alt="" src="/img/blog-ajax-loader.gif"></div>
            <a href="#" class="button_more next_part">Показать ещё <?php echo $limitSolutions; ?></a>
            <a href="#" class="button_more rest_part">Показать все</a>
            <div class="separator"></div>
            <div style="clear: both;"></div>
        </div>
        <?php endif; ?>
        </section>

        <section class="white" style="margin: 0 -34px">
            <?=$this->view()->render(array('element' => 'pitchcommentform'), array('pitch' => $pitch))?>
        </section>
                <?php if((strtotime($pitch->started) > strtotime('2013-01-31'))):?>
    <div id="placeholder" style="height:215px;width:958px;position:relative;left:-63px;background-image: url('/img/zaglushka.png')"></div>
    <div style="display:none;" id="floatingblock" class="floatingblock">
                    <table style="width:500px;float:left">
                    <tr style="width:500px;float:left; height:28px">
                        <td><img style="margin-left:19px;margin-top:9px" src="/img/top.png"/></td>
                    </tr>
                    <tr>
                        <td height="200">
                            <div style="margin-left:18px;padding:0;float:left;margin-top:5px;width:474px;height:150px; border:1px black; background-color: #f2f1f0;overflow:hidden;" id="container"></div>
                            <div style="clear:both"></div>
                            <div id="scrollerarea" style="display:none;height:12px;margin-left:45px;width:425px;background-image: url('/img/scrollbarfiller.png')">
                                <div id="scroller" style="margin-top: 10px;background-image:url('/img/scroller.png');height:12px;width:76px;background-color:black;left:349px;"></div>
                            </div>
                        </td>
                    </tr>
                    </table>
                    <div style="float:right;height:209px;width:458px;">
                        <ul style="margin-top: 51px;margin-left:4px; float:left;width: 100px;height:190px;">
                            <li data-points="5" style="font-size:10px;text-transform: uppercase;color:#a9a8a8;margin-bottom:3px">Фантастик!!!</li>
                            <li data-points="4" style="font-size:10px;text-transform: uppercase;color:#a9a8a8;margin-bottom:3px">Хорошо</li>
                            <li data-points="3" style="font-size:10px;text-transform: uppercase;color:#a9a8a8;margin-bottom:3px">ОК</li>
                            <li data-points="2" style="font-size:10px;text-transform: uppercase;color:#a9a8a8;margin-bottom:3px">Так себе</li>
                            <li data-points="1" style="font-size:10px;text-transform: uppercase;margin-bottom:3px;">Плохо</li>
                            <li data-points="0" style="font-size:10px;text-transform: uppercase;color:#a9a8a8;margin-bottom:3px">Так не пойдёт</li>
                        </ul>
                        <div style="width:150px;float:left;height:190px;text-align;center">
                            <h2 style="margin-top: 11px; font-size: 15px; font-weight: bold; color: rgb(102, 102, 102); text-shadow: -1px 0px 0px rgb(255, 255, 255); margin-left: 7px;">Средняя оценка</h2>
                            <p style="color: rgb(102, 102, 102); font: 12px/15px arial; margin-left: -7px; text-align: center;"><?php echo $this->user->isPitchOwner($pitch->user->id) ? 'вашей активности' : 'активности заказчика';?> из 5</p>
                            <div style="background-image:url(/img/big-krug.png);margin-top:4px;height:132px;width:132px;">
                            <canvas id="can" height="132" width="132" style="">
                            </canvas></div>
                            <div style="background: url('/img/krug-small.png') no-repeat scroll 32px 30px transparent;height: 82px; width: 87px; position: relative; top: -132px; bottom: 0px; z-index: 15; padding-top: 50px; padding-left: 45px;">
                                <h2 id="avgPoints" style="font-size:28px;color:#666666;text-shadow: -1px 0 0 #FFFFFF;width:40px;text-align:center"></h2>
                                <h2 id="avgPointsString" style="color: rgb(102, 102, 102); text-align: center; text-shadow: -1px 0px 0px rgb(255, 255, 255); margin-left: 0px; margin-top: 4px; font-size: 9px; width: 44px;">БАЛЛА</h2>
                            </div>
                        </div>
                        <?php if($pitch->guaranteed == 0):?>
                        <div style="width:200px;float:left;height:190px;text-align;center">
                            <h2 style="margin-top: 80px; font-size: 15px; font-weight: bold; color: rgb(102, 102, 102); text-shadow: -1px 0px 0px rgb(255, 255, 255); margin-left: 12px; width: 163px; text-align: center;" id="refundLabel"></h2>
                            <p style="color: rgb(102, 102, 102); margin-left: 34px; margin-top: 17px; font: 14px/15px arial;"><a target="_blank" href="http://www.godesigner.ru/answers/view/71">Что это значит?</a><br>
                            </p>
                        </div>
                        <?php else:?>
                        <div style="width:200px;float:left;height:190px;text-align:center">
                            <h2 style="margin-top: 11px; font-size: 15px; font-weight: bold; color: rgb(102, 102, 102); text-shadow: -1px 0px 0px rgb(255, 255, 255);"><?php echo $this->user->isPitchOwner($pitch->user->id) ? 'Ваш питч' : 'Питч';?><br> гарантированный</h2>

                            <img src="/img/bigg.png" style="margin-bottom:10px;margin-top: 15px; margin-left: 54px; padding-right:50px;">
                            <a href="/answers/view/79" target="_blank" style="margin-left:10px;text-decoration: underline;margin-top: 23px;">Что это такое?</a>
                        </div>
                        <?php endif?>
                    </div>
                </div>
                <?php endif?>

                </div><!-- /solution -->
                <div id="under_middle_inner"></div><!-- /under_middle_inner -->
            </div>

        </div><!-- /middle_inner -->


	</div><!-- /middle -->

</div><!-- .wrapper -->
<div id="popup-final-step" class="popup-final-step" style="display:none">
    <h3>Убедитесь в правильном выборе!</h3>
    <p>Эта процедура является окончательной, и в дальнейшем вы не сможете изменить своё мнение. Пожалуйста, убедитесь ещё раз в верности вашего решения. Вы уверены, что победителем питча становится <a id="winner-user-link" href="#" target="_blank"></a> c решением <a id="winner-num" href="#" target="_blank"></a>?</p>
    <div class="portfolio_gallery" style="width:200px;margin-bottom:5px;">
        <ul class="list_portfolio">
            <li>
                <div id="replacingblock">
                    <a href="#"><img alt="" src="#"></a>
                    <div class="photo_opt">
                    <span class="rating_block"><img alt="" src="/img/0-rating.png"></span>
                                <span class="like_view" style="margin-top:1px;"><img class="icon_looked" alt="" src="/img/looked.png"><span>0</span>
                                <a data-id="57" class="like-small-icon" href="#"><img alt="" src="/img/like.png"></a><span>0</span></span>
                    <span class="bottom_arrow"><a class="solution-menu-toggle" href="#"><img alt="" src="/img/marker5_2.png"></a></span>
                </div>
            </li>
        </ul>
    </div>
    <div class="final-step-nav wrapper"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="confirmWinner" value="Да, подтвердить"></div>
</div>

<div id="popup-warning" class="popup-warn generic-window" style="display:none">
    <p style="margin-top:120px;">Вы можете пожаловаться, если обнаружены грубые высказывания, реклама, спам, контент для взрослых, ссылки на работы, сделки вне Go Designer, копирование чужой работы или плагиат. В последнем случае важно предоставить ссылку на оригинал. Важно однако учитывать, что в питче с одним брифом некоторая степень похожести работ допускается. Подробнее <a href="http://www.godesigner.ru/answers/view/38" target="_blank">тут</a></p>
    <p>Пожалуйста, прокомментируйте суть жалобы:</p>
    <textarea id="warn-solution" class="placeholder" placeholder="ВАША ЖАЛОБА"></textarea>
    <div class="final-step-nav wrapper" style="margin-top:20px;"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="sendWarn" value="Да, подтвердить"></div>
</div>

<div id="popup-warning-comment" class="popup-warn generic-window" style="display:none">
    <p style="margin-top:120px;">Вы можете пожаловаться, если обнаружены грубые высказывания, реклама, спам, контент для взрослых, ссылки на работы, сделки вне Go Designer, копирование чужой работы или плагиат. В последнем случае важно предоставить ссылку на оригинал. Важно однако учитывать, что в питче с одним брифом некоторая степень похожести работ допускается. Подробнее <a href="http://www.godesigner.ru/answers/view/38" target="_blank">тут</a></p>
    <p>Пожалуйста, прокомментируйте суть жалобы:</p>
    <textarea id="warn-comment" class="placeholder" placeholder="ВАША ЖАЛОБА"></textarea>
    <div class="final-step-nav wrapper" style="margin-top:20px;"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="sendWarnComment" value="Да, подтвердить"></div>
</div>

<!-- Solution Popup -->

<!-- start: Solution overlay -->
<div class="solution-overlay">
    <!-- start: Solution Container -->
    <div class="solution-container">
        <div class="solution-nav-wrapper">
            <div class="solution-prev"></div>
            <a class="solution-prev-area" href="#"></a>
            <div class="solution-next"></div>
            <a class="solution-next-area" href="#"></a>
        </div>
        <!-- start: Solution Right Panel -->
        <div class="solution-right-panel">
            <div class="solution-popup-close"></div>
            <div class="solution-info solution-summary">
                <div class="solution-number">#<span class="number isField"><!--  --></span></div>
                <div class="solution-rating"><div class="rating-image star0"></div> рейтинг заказчика</div>
            </div>
            <div class="separator"></div>
            <div class="solution-info solution-author chapter">
                <h2>АВТОР</h2>
                <img class="author-avatar" src="/img/default_small_avatar.png" alt="Портрет автора" />
                <a class="author-name isField" href="#"><!--  --></a>
                <div class="author-from isField"><!--  --></div>
                <div class="clr"></div>
            </div>
            <div class="separator"></div>
            <div class="solution-info solution-about chapter">
                <h2>О РЕШЕНИИ</h2>
                <span class="solution-description isField"><!--  --></span><a class="description-more">… Подробнее</a>
            </div>
            <div class="separator"></div>
            <div class="solution-copyrighted"><!--  --></div>
            <div class="solution-info">
                <table class="solution-stat">
                    <col class="icon">
                    <col class="description">
                    <col class="value">
                    <tr>
                        <td class="icon icon-eye"></td>
                        <td>Просмотры</td>
                        <td class="value-views isField"><!--  --></td>
                    </tr>
                    <tr>
                        <td class="icon icon-thumb"></td>
                        <td>Лайки</td>
                        <td class="value-likes isField"><!--  --></td>
                    </tr>
                    <tr>
                        <td class="icon icon-comments"></td>
                        <td>Комментарии</td>
                        <td class="value-comments isField"><!--  --></td>
                    </tr>
                </table>
            </div>
            <div class="separator"></div>
            <div class="solution-info solution-share chapter">

            </div>
            <div class="separator"></div>
            <div class="solution-info solution-abuse isField"><!--  --></div>
        <!-- end: Solution Right Panel -->
        </div>
        <!-- start: Solution Left Panel -->
        <div class="solution-left-panel">
            <a class="solution-title" href="/pitches/view/<?=$pitch->id?>">
                <h1>
                    <?=$pitch->title?>
                </h1>
            </a>
            <!-- start: Soluton Images -->
            <section class="solution-images isField">
                <div style="text-align:center;height:220px;padding-top:180px"><img alt="" src="/img/blog-ajax-loader.gif"></div>
            <!-- end: Solution Images -->
            </section>
            <section class="allow-comments">
                <div class="all_messages">
                	<div class="clr"></div>
                </div>
                <input type="hidden" value="<?=$pitch->category_id?>" name="category_id" id="category_id">
                <?php if ($this->user->isPitchOwner($pitch->user->id) || $this->user->isAdmin()): ?>
                <div class="separator full"></div>
                <form class="createCommentForm" method="post" action="/comments/add">
                	<textarea id="newComment" name="text"></textarea>
                	<input type="hidden" value="" name="solution_id">
                	<input type="hidden" value="" name="comment_id">
                	<input type="hidden" value="<?=$pitch->id?>" name="pitch_id">
                    <input type="button" src="/img/message_button.png" value="Публиковать комментарий для всех" class="button createComment" data-is_public="1" style="margin: 15px 18px 15px 0;">
                    <input type="button" src="/img/message_button.png" value="Отправить только дизайнеру" class="button createComment" data-is_public="0" style="margin: 15px 0 15px 18px;">
                	<div class="clr"></div>
                </form>
                <?php endif; ?>
            </section>
            <!-- start: Comments -->
            <section class="solution-comments isField">

            <!-- end: Comments -->
            </section>
        <!-- end: Solution Left Panel -->
        </div>
        <div class="clr"></div>
    <!-- end: Solution Container -->
    </div>
<!-- end: Solution overlay -->
</div>
    <div id="bridge" style="display:none;"></div>
<?php if((strtotime($pitch->started) > strtotime('2013-01-31'))):?>
<?=$this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), '//assets.pinterest.com/js/pinit.js', 'http://surfingbird.ru/share/share.min.js?v=5', 'jcarousellite_1.0.1.js', 'jquery.simplemodal-1.4.2.js', 'jquery.scrollto.min.js', 'socialite.js', 'pitches/view.js?' . mt_rand(100, 999), 'jquery.hover.js', 'jquery-ui-1.8.23.custom.min.js', 'kinetic-v4.5.4.min.js', 'pitches/plot.js', 'jquery.raty.min.js'), array('inline' => false))?>
    <?php else:?>
    <?=$this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), '//assets.pinterest.com/js/pinit.js', 'http://surfingbird.ru/share/share.min.js?v=5', 'jcarousellite_1.0.1.js', 'jquery.simplemodal-1.4.2.js', 'jquery.scrollto.min.js', 'socialite.js', 'pitches/view.js?' . mt_rand(100, 999), 'jquery.hover.js', 'kinetic-v4.5.4.min.js'), array('inline' => false))?>
    <?php endif?>
<?=$this->html->style(array('/messages12', '/pitches12', '/view', '/pitch_overview'), array('inline' => false))?>