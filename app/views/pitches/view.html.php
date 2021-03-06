<div class="wrapper pitchpanel login">

	<?=$this->view()->render(['element' => 'header'], ['logo' => 'logo', 'header' => 'header2'])?>

	<div class="middle">
	   <div class="middle_inner_gallery" style="padding-top:25px">
           <input type="hidden" value="<?=$pitch->id?>" name="pitch_id">
           <input type="hidden" value="<?= serialize($order) ?>">
           <?php if ($pitch->status == 1): ?>
           <?php $days = ($pitch->experts) ? 6 : 4;?>
               <input type="hidden" value="<?=(time() > strtotime($pitch->finishDate.' +'.$days.' days')) ? 1 : 0?>" name="notFinish">
           <?php else: ?>
               <input type="hidden" value="0" name="notFinish">
           <?php endif ?>
            <?=$this->view()->render(['element' => 'pitch-info/infotable'], ['pitch' => $pitch])?>
            <ul class="tabs-curve group">
                <li class="active" style="z-index: 3;">
                    <?=$this->html->link('Решения', ['controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id], ['class' => 'menu-toggle ajaxgallery', 'data-page' => 'gallery'])?>
                </li>
                <li style="z-index: 2;">
                    <a href="/pitches/view/<?= $pitch->id ?>/?tab=details" class="menu-toggle ajaxgallery" data-page="brief">Бриф</a>
                </li>
                <li style="z-index: 1;">
                    <a href="/pitches/view/<?= $pitch->id ?>/?tab=designers" class="menu-toggle ajaxgallery" data-page="designers">Участники</a>
                </li>
            </ul>
            <div class="gallery_container">
                <nav class="other_nav_gallery clear">
                    <p class="supplement4" style="float:left;height:30px;padding-top:20px;font-weight: bold; color:#b2afaf;">
                        <span style="display: inline-block; margin-top: 4px; vertical-align: top;">СОРТИРОВАТЬ ПО:</span>
                        <a class="sort-by-rating<?php if ($sort == 'rating'):?> active<?php endif;?>" href="/pitches/view/<?=$pitch->id?>?sorting=rating"><span title="сортировать по рейтингу"></span></a>
                        <a class="sort-by-likes<?php if ($sort == 'likes'):?> active<?php endif;?>" href="/pitches/view/<?=$pitch->id?>?sorting=likes"><span title="сортировать по лайкам"></span></a>
                        <a class="sort-by-created<?php if ($sort == 'created'):?> active<?php endif;?>" href="/pitches/view/<?=$pitch->id?>?sorting=created"><span title="сортировать по дате создания"></span></a>
                    </p>

                    <?php
                    if (
                        (((int) $pitch->premium === 0) && (!$this->user->isPitchOwner($pitch->user_id)) && ($pitch->status < 1) && ($pitch->published == 1) && $disableUpload === false)
                        ||
                        (((int) $pitch->premium === 1) &&
                            ($pitch->status < 1) &&
                            ($pitch->published == 1) &&
                            (($this->user->isPitchOwner($pitch->id) === true) ||
                            ($this->user->isAdmin() === true) ||
                            ($this->user->getAwardedSolutionNum() > 0)))
                    ):?>
                        <a href="/pitches/upload/<?=$pitch->id?>" class="button add_solution <?php if ($this->session->read('user.confirmed_email') == '0') {
    echo 'needConfirm';
}?> <?php echo ($this->user->designerTimeRemain($pitch)) ? ' needWait' : '';?>">предложить решение</a>
                    <?php endif;?>

                </nav>

                <div class="portfolio_gallery">
                    <div class="pht"></div>
                    <?php
                    $mySolutionList = [];
                    $mySolutionNumList = [];
                    if (((!empty($solutions) > 0) && ($pitch->published == 1)) || ($pitch->multiwinner != 0) && (!empty($solutions) > 0)): ?>
                    <ul class="list_portfolio main_portfolio">
                        <?=$this->view()->render(['element' => 'gallery'], compact('solutions', 'pitch', 'selectedsolution', 'sort', 'canViewPrivate', 'solutionsCount', 'pitchesCount', 'winnersUserIds'))?>
                    </ul>
                    <?php else:?>
                    <div class="bigfont">
                        <h2 class="title">
                            <?php if ($pitch->billed == 1):?>
                            Ещё никто не выложил свои идеи.
                            <?php else: ?>
                            Проект будет запущен после <a href="https://godesigner.ru/pitches/edit/<?= $pitch->id?>#step3">оплаты.</a>
                            <?php endif;?>
                        </h2>
                        <?php if (!$this->user->isPitchOwner($pitch->user_id)):?>
                        <h2 class="title"><?=$this->html->link('предложи свое решение', ['controller' => 'pitches', 'action' => 'upload', 'id' => $pitch->id], ['escape' => false])?></h2>
                        <h2 class="title">и стань первым!</h2>
                        <?php endif?>
                    </div>
                    <?php endif;?>
                </div>
                <?php $initialSeparator = false;
                if (count($solutions) < $solutionsCount): $initialSeparator = true; ?>
                <div class="gallery_postload">
                    <div class="separator"></div>
                    <div class="gallery_postload_loader"><img alt="" src="/img/blog-ajax-loader.gif"></div>
                    <a href="#" class="button_more next_part">Показать ещё <?php echo $limitSolutions; ?></a>
                    <a href="#" class="button_more rest_part">Показать все</a>
                    <div class="separator"></div>
                    <div style="clear: both;"></div>
                </div>
                <?php endif; ?>

                <section class="white" style="margin: 0 -34px">
                    <?=$this->view()->render(['element' => 'pitchcommentform'], ['pitch' => $pitch, 'initialSeparator' => $initialSeparator])?>
                </section>

                <?php if (((strtotime($pitch->started) > strtotime('2013-01-31'))) && ($pitch->published == 1) && ($pitch->id != 103263)):?>
                <div id="placeholder" style="height:215px;width:958px;position:relative;left:-63px;background-image: url('/img/zaglushka.png')"></div>
                <div style="display:none;" id="floatingblock" class="floatingblock">
                    <table style="width:500px;float:left">
                    <tr style="width:500px;float:left; height:28px">
                        <td><img style="margin-left:19px;margin-top:9px" src="/img/top.png"/></td>
                    </tr>
                    <tr>
                        <td height="200">
                            <a href="/answers/view/71" target="_blank">
                                <div style="margin-left:18px;padding:0;float:left;margin-top:5px;width:474px;height:150px; border:1px black; background-color: #f2f1f0;overflow:hidden;" id="container"></div>
                            </a>
                            <div style="clear:both"></div>
                            <div id="scrollerarea" style="display:none;height:12px;margin-left:45px;width:425px;background-image: url('/img/scrollbarfiller.png')">
                                <div id="scroller" style="margin-top: 10px;background-image:url('/img/scroller.png');height:12px;width:76px;background-color:black;left:349px;"></div>
                            </div>
                        </td>
                    </tr>
                    </table>

                    <div style="float:right;height:209px;width:456px;">
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
                            <a target="_blank" href="/answers/view/71" style="background: url('/img/krug-small.png') no-repeat scroll 32px 30px transparent;height: 82px; width: 87px; position: relative; top: -132px; bottom: 0px; z-index: 15; padding-top: 50px; padding-left: 45px; display: block;">
                                <h2 id="avgPoints" style="font-size:28px;color:#666666;text-shadow: -1px 0 0 #FFFFFF;width:40px;text-align:center"></h2>
                                <h2 id="avgPointsString" style="color: rgb(102, 102, 102); text-align: center; text-shadow: -1px 0px 0px rgb(255, 255, 255); margin-left: 0px; margin-top: 4px; font-size: 9px; width: 44px;">БАЛЛА</h2>
                            </a>
                        </div>
                        <?php if ($pitch->guaranteed == 0):?>
                        <div style="width:200px;float:left;height:190px;text-align;center">
                            <h2 style="margin-top: 80px; font-size: 15px; font-weight: bold; color: rgb(102, 102, 102); text-shadow: -1px 0px 0px rgb(255, 255, 255); margin-left: 12px; width: 163px; text-align: center;" id="refundLabel"></h2>
                            <?php if ($pitch->type == 'company_project'):?>
                                <p style="color: rgb(102, 102, 102); margin-left: 0; margin-top: 17px; font: 14px/15px arial;">
                                    <a target="_blank" id="whatIsIt" href="https://godesigner.ru/answers/view/102">Абонентское обслуживание</a>
                                    <br />
                                </p>
                            <?php else: ?>
                                <p style="color: rgb(102, 102, 102); margin-left: 34px; margin-top: 17px; font: 14px/15px arial;">
                                    <a target="_blank" id="whatIsIt" href="https://godesigner.ru/answers/view/71">Что это значит?</a>
                                    <br />
                                </p>
                            <?php endif?>
                        </div>
                        <?php else:?>
                        <div style="width:200px;float:left;height:190px;text-align:center">
                            <h2 style="margin-top: 11px; font-size: 15px; font-weight: bold; color: rgb(102, 102, 102); text-shadow: -1px 0px 0px rgb(255, 255, 255);"><?php echo $this->user->isPitchOwner($pitch->user->id) ? 'Ваш проект' : 'Проект';?><br> гарантированный</h2>
                            <img src="/img/bigg.png" style="margin-bottom:10px;margin-top: 15px; margin-left: 54px; padding-right:50px;">
                            <a href="/answers/view/79" target="_blank" style="margin-left:10px;text-decoration: underline;margin-top: 23px;">Что это такое?</a>
                        </div>
                        <?php endif?>
                    </div>
                </div>
                <?php endif?>
                <?php if (!$this->pitch->isReadyForLogosale($pitch)):?>
                    <input type="hidden" value="0" name="isReadyForLogoale">
                <!-- Solution Popup Dummy --><?=$this->view()->render(['element' => 'popups/solution'], ['pitch' => $pitch])?>
                <?php else: ?>
                    <input type="hidden" value="1" name="isReadyForLogoale">
                    <!-- Solution Popup Dummy --><?=$this->view()->render(['element' => 'popups/solution_sale'], ['data' => $data])?>
                <?php endif?>
                <?php if (($this->user->isPitchOwner($pitch->user_id)) && ($pitch->id != 103263)):?>
                    <?php if (($pitch->status == 1) && ($pitch->awarded == 0)):
                        if (($pitch->expert == 0) || (($pitch->expert == 1) && ($allowSelect = $this->pitch->expertOpinion($pitch->id)) && ($allowSelect != strtotime($pitch->finishDate)))):
                        ?>
                    <div id="timer" data-expert="<?= ($this->pitch->expertOpinion($pitch->id) + 4 * DAY)?>" data-deadline="<?= $this->pitch->getChooseWinnerTime($pitch) ?>" data-currenttime="<?= time();?>">
                        <a href="/answers/view/70" target="_blank" class="lcd"></a>
                    </div>
                    <?php endif; endif?>
                    <!-- Rating Pancake -->
                <div id="dinamic" style="display:none;position: fixed; z-index: 15; bottom: 0; opacity:0.8; margin-left: 740px">
                    <div class="bubble">
                        <div class="bubble-content">
                            <span>Возврат денег недоступен:</span><br>
                            <span class="lowReason"></span><br><br>
                            <a href="/answers/view/71">Как это исправить?</a>
                        </div>
                    <div id="bubble-close"></div>
                    </div>
                    <div style="width:150px;height:190px;text-align;center">
                        <div style="background-image:url(/img/big-krug.png);margin-top:4px;height:132px;width:132px;">
                            <canvas id="canFloat" height="132" width="132" style="">
                            </canvas></div>
                        <div style="background: url('/img/krug-small.png') no-repeat scroll 32px 30px transparent;height: 82px; width: 87px; position: relative; top: -132px; bottom: 0px; z-index: 15; padding-top: 50px; padding-left: 45px;">
                            <h2 id="avgPointsFloat" style="font-size:28px;color:#666666;text-shadow: -1px 0 0 #FFFFFF;width:40px;text-align:center;cursor:pointer;"></h2>
                            <h2 id="avgPointsStringFloat" style="color: rgb(102, 102, 102); text-align: center; text-shadow: -1px 0px 0px rgb(255, 255, 255); margin-left: 0px; margin-top: 4px; font-size: 9px; width: 44px;cursor:pointer;">БАЛЛА</h2>
                        </div>
                    </div>
                </div>
                <?php endif?>
            </div><!-- /gallery_container -->
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
	</div><!-- /middle -->
</div><!-- .wrapper -->

<script>
var needConfirmation = <?php if (($this->user->isPitchOwner($pitch->user_id)) && (($pitch->user->phone === '') || ($pitch->user->phone_valid == 0))): echo 'true'; else: echo 'false'; endif?>;
var autosuggestUsers = <?php echo json_encode($autosuggestUsers)?>;
</script>

<?=$this->view()->render(['element' => 'popups/warning'], ['freePitch' => $freePitch, 'pitchesCount' => $pitchesCount, 'pitch' => $pitch])?>
<?=$this->view()->render(['element' => 'popups/phone_confirm'])?>
    <div id="bridge" style="display:none;"></div>
<?php if ((strtotime($pitch->started) > strtotime('2013-01-31'))):?>
<?=$this->html->script(['flux/flux.min.js', '/js/enjoyhint.js', 'http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), '//assets.pinterest.com/js/pinit.js', 'jquery.simplemodal-1.4.2.js', 'jquery-plugins/jquery.scrollto.min.js', 'socialite.js', 'jquery.hover.js', 'jquery.raty.min.js', 'jquery-ui-1.11.4.min.js', 'jquery.timeago.js', 'social-likes.min.js', 'konva.0.9.5.min.js', 'jquery.tooltip.js', '/js/common/comments/UserAutosuggest.js', '/js/common/comments/actions/CommentsActions.js', 'pitches/plot.js', 'pitches/view.js', 'pitches/gallery.js'], ['inline' => false])?>
    <?php else:?>
    <?=$this->html->script(['http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), '//assets.pinterest.com/js/pinit.js', 'jquery.simplemodal-1.4.2.js', 'jquery-plugins/jquery.scrollto.min.js', 'socialite.js', 'jquery.hover.js', 'jquery.raty.min.js', 'jquery-ui-1.11.4.min.js', 'jquery.timeago.js', 'konva.0.9.5.min.js', 'social-likes.min.js', 'jquery.tooltip.js',  'pitches/view.js', 'pitches/gallery.js'], ['inline' => false])?>
    <?php endif?>
<?=$this->html->style([
    '/css/common/receipt.css',
    '/css/common/buttons.css',
    '/css/common/clear.css',
    '/css/enjoyhint.css',
    '/messages12', '/pitches12', '/view', '/pitch_overview', '/css/viewsolution', '/css/social-likes_flat'], ['inline' => false])?>