<div class="wrapper pitchpanel login">

	<?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>

	<script>var allowComments = false;</script>
	<?php if((($pitch->status > 0) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true) && (($this->user->isPitchOwner($pitch->user_id)) || ($this->user->isExpert()) || ($this->user->isAdmin()) )) ||
        (($pitch->status == 0) && ($pitch->published == 1) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true)) && ($this->session->read('user.id'))):?>
        <script>allowComments = true;</script>
    <?php endif?>
	<div class="middle">
        <?=$this->view()->render(array('element' => 'scripts/viewsolution_init'), array('pitch' => $pitch, 'expertsIds' => $expertsIds))?>
        <!-- start: Solution Container -->
        <div class="solution-container page">
            <div class="pitch-info">
            <?php if($pitch->user_id != $this->session->read('user.id') || $pitch->status > 0): ?>
                <?=$this->view()->render(array('element' => 'pitch-info/designers_infotable'), array('pitch' => $pitch))?>
            <?php else: ?>
                <?=$this->view()->render(array('element' => 'pitch-info/clients_infotable'), array('pitch' => $pitch))?>
            <?php endif ?>
            </div>
            <div style="height:1px; clear:both;"></div>
            <!-- start: Solution Right Panel -->
            <div class="solution-right-panel page">
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
                            <td>Просмотров</td>
                            <td class="value-views isField"><!--  --></td>
                        </tr>
                        <tr>
                            <td class="icon icon-thumb"></td>
                            <td>Лайков</td>
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
                    <h2>ПОДЕЛИТЬСЯ</h2>
                    <div class="body" style="display: block;">
                        <table width="100%">
                            <tbody>
                                <tr height="35">
                                    <td width="137" valign="middle">
                                        <a id="facebook<?=$solution->id?>" class="socialite facebook-like" href="http://www.facebook.com/sharer.php?u=http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>" data-href="http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>" data-send="false" data-layout="button_count">
                                            Share on Facebook
                                        </a>
                                    </td>
                                    <td width="137" valign="middle">
                                        <div id="vk_like" style="height: 22px; width: 100px; background-image: none; position: relative; clear: both; background-position: initial initial; background-repeat: initial initial;"></div>
                                    </td>
                                </tr>
                                <tr height="35">
                                    <td valign="middle">
                                        <?php
                                        if (rand(1, 100) <= 50) {
                                            $tweetLike = 'Мне нравится этот дизайн! А вам?';
                                        } else {
                                            $tweetLike = 'Из всех ' . $pitch->ideas_count . ' мне нравится этот дизайн';
                                        }
                                        ?>
                                        <a id="twitter<?=$solution->id?>" class="socialite twitter-share" href="" data-url="http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>?utm_source=twitter&utm_medium=tweet&utm_content=like-tweet&utm_campaign=sharing" data-text="<?php echo $tweetLike; ?>" data-lang="ru" data-hashtags="Go_Deer">
                                            Share on Twitter
                                        </a>
                                    </td>
                                    <td valign="middle">
                                        <a href="//pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F<?= $solution->id ?>&amp;media=http%3A%2F%2Fwww.godesigner.ru%2F<?=$this->solution->renderImageUrl($solution->images['solution_solutionView'])?>&amp;description=%D0%9E%D1%82%D0%BB%D0%B8%D1%87%D0%BD%D0%BE%D0%B5%20%D1%80%D0%B5%D1%88%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BD%D0%B0%20%D1%81%D0%B0%D0%B9%D1%82%D0%B5%20GoDesigner.ru" target="_blank" data-pin-log="button_pinit" data-pin-config="beside"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>
                                    </td>
                                </tr>
                                <tr height="35">
                                    <td valign="middle"><iframe frameborder="0" scrolling="no" class="surfinbird__like_iframe" src="//surfingbird.ru/button?layout=common&amp;url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F<?= $solution->id ?>%3Fsorting%3Dcreated&amp;caption=%D0%A1%D0%B5%D1%80%D1%84&amp;referrer=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fview%2F101057&amp;current_url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F<?= $solution->id ?>%3Fsorting%3Dcreated" style="width: 120px; height: 20px;"></iframe><a target="_blank" class="surfinbird__like_button __sb_parsed__" data-surf-config="{'layout': 'common', 'width': '120', 'height': '20'}" href="http://surfingbird.ru/share"></a></td>
                                    <td valign="middle"><div id="___plusone_0" style="text-indent: 0px; margin: 0px; padding: 0px; background-color: transparent; border-style: none; float: none; line-height: normal; font-size: 1px; vertical-align: baseline; display: inline-block; width: 106px; height: 24px; background-position: initial initial; background-repeat: initial initial;"><iframe frameborder="0" hspace="0" marginheight="0" marginwidth="0" scrolling="no" style="position: static; top: 0px; width: 106px; margin: 0px; border-style: none; left: 0px; visibility: visible; height: 24px;" tabindex="0" vspace="0" width="100%" id="I0_1372837769255" name="I0_1372837769255" src="https://apis.google.com/_/+1/fastbutton?bsv&amp;hl=ru&amp;origin=http%3A%2F%2Fwww.godesigner.ru&amp;url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F<?= $solution->id ?>%3Fsorting%3Dcreated&amp;ic=1&amp;jsh=m%3B%2F_%2Fscs%2Fapps-static%2F_%2Fjs%2Fk%3Doz.gapi.ru.fjfk_NiG5Js.O%2Fm%3D__features__%2Fam%3DEQ%2Frt%3Dj%2Fd%3D1%2Frs%3DAItRSTPAdsSDioxjaY0NLzoPJdX-TT1dfg#_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe%2C_renderstart%2Concircled%2Conload&amp;id=I0_1372837769255&amp;parent=http%3A%2F%2Fwww.godesigner.ru&amp;pfname=&amp;rpctoken=95865586" allowtransparency="true" data-gapiattached="true" title="+1"></iframe></div></td>
                                </tr>
                                <tr height="35">
                                    <!--td valign="middle"><script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
                                        <span class="IN-widget" style="line-height: 1; vertical-align: baseline; display: inline-block; text-align: center;"><span style="padding: 0px !important; margin: 0px !important; text-indent: 0px !important; display: inline-block !important; vertical-align: baseline !important; font-size: 1px !important;"><span id="li_ui_li_gen_1372837769632_0"><a id="li_ui_li_gen_1372837769632_0-link" href="javascript:void(0);"><span id="li_ui_li_gen_1372837769632_0-logo">in</span><span id="li_ui_li_gen_1372837769632_0-title"><span id="li_ui_li_gen_1372837769632_0-mark"></span><span id="li_ui_li_gen_1372837769632_0-title-text">Share</span></span></a></span></span><span style="padding: 0px !important; margin: 0px !important; text-indent: 0px !important; display: inline-block !important; vertical-align: baseline !important; font-size: 1px !important;"><span id="li_ui_li_gen_1372837769647_1-container" class="IN-right IN-hidden"><span id="li_ui_li_gen_1372837769647_1" class="IN-right"><span id="li_ui_li_gen_1372837769647_1-inner" class="IN-right"><span id="li_ui_li_gen_1372837769647_1-content" class="IN-right">0</span></span></span></span></span></span><script type="IN/Share+init" data-counter="right"></script></td-->
                                    <td valign="middle"><a href="http://www.tumblr.com/share" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:81px; height:20px; background:url('http://platform.tumblr.com/v1/share_1.png') top left no-repeat transparent;">Share on Tumblr</a></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="separator"></div>
                <div class="solution-info solution-abuse isField"><!--  --></div>
                <div class="separator"></div>
            <!-- end: Solution Right Panel -->
            </div>
            <!-- start: Solution Left Panel -->
            <div class="solution-left-panel">
                <h1 class="solution-title-page">
                    <a href="/pitches">
                        Все питчи /
                    </a>
                    <a href="/pitches/view/<?=$pitch->id?>">
                        <?=$pitch->title?>
                    </a>
                </h1>
                <!-- start: Soluton Images -->
                <section class="solution-images isField bla">
                    <div style="text-align:center;height:220px;padding-top:180px"><img alt="" src="/img/blog-ajax-loader.gif"></div>
                <!-- end: Solution Images -->
                </section>
                <section class="allow-comments">
                    <div class="separator full"></div>
                    <input type="hidden" value="<?=$pitch->category_id?>" name="category_id" id="category_id">
                    <?php if (($this->session->read('user.id') == $pitch->user->id) || $this->user->isAdmin()): ?>
                    <form class="createCommentForm" method="post" action="/comments/add">
                    	<textarea id="newComment" name="text"></textarea>
                    	<input type="hidden" value="<?=$solution->id?>" name="solution_id">
                    	<input type="hidden" value="" name="comment_id">
                        <input type="hidden" value="/pitches/viewsolution/<?=$solution->id?>" name="from">
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
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
	</div><!-- /middle -->
</div><!-- .wrapper -->
<div id="popup-final-step" class="popup-final-step" style="display:none">
    <h3>Убедитесь в правильном выборе!</h3>
    <p>Эта процедура является окончательной, и в дальнейшем вы не сможете изменить своё мнение. Пожалуйста, убедитесь ещё раз в верности вашего решения. Вы уверены, что победителем питча становится <a id="winner-user-link" href="#" target="_blank"></a> c решением <a id="winner-num" href="#" target="_blank"></a>?</p>
    <div class="portfolio_gallery" style="width:200px;margin-bottom:5px;">
        <ul class="list_portfolio">
            <li>
                <div class="photo_block">
                    <?php
                        if($this->solution->getImageCount($solution->images['solution_galleryLargeSize']) > 1):?>
                    <div style="position:absolute;color:white;font-weight:bold;font-size: 14px;padding-top:7px;top:0px;left:168px;text-align:center;width:31px;height:24px;background-image: url('/img/multifile_small.png')"><?=$this->solution->getImageCount($solution->images['solution_galleryLargeSize'])?></div>
                    <?php endif;?>
                    <a href="/pitches/viewsolution/<?=$solution->id?>"><img alt="" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>"></a>
                    <div class="photo_opt">
                        <div style="display: block; float:left;" class="">
                            <span class="rating_block"><img alt="" src="/img/<?=$solution->rating?>-rating.png"></span>
                                <span class="like_view"><img class="icon_looked" alt="" src="/img/looked.png"><span><?=$solution->views?></span>
                            </span></div>

                        <div style="display: block; float:left;">
                            <a data-id="<?=$solution->id?>" class="like-small-icon" href="#"><img alt="количество лайков" src="/img/like.png"></a>
                            <span rel="http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>" data-id="<?=$solution->id?>" style="color: #CDCCCC;font-size: 10px;margin-right:8px;vertical-align: middle;"><?=$solution->likes?></span>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="final-step-nav wrapper"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="confirmWinner" value="Да, подтвердить"></div>
</div>

<div id="popup-warning" class="popup-warn generic-window" style="display:none">
    <p style="margin-top:120px;">Вы можете пожаловаться, если обнаружены грубые высказывания, реклама, спам, контент для взрослых, ссылки на работы, сделки вне Go Designer, копирование чужой работы или плагиат. В последнем случае важно предоставить ссылку на оригинал. Важно однако учитывать, что в питче с одним брифом некоторая степень похожести работ допускается. Подробнее <a href="http://www.godesigner.ru/answers/view/38" target="_blank">тут</a>.</p>
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

<!-- Moderation Popups -->
<?php if($this->user->isAdmin()):?>
    <?=$this->view()->render(array('element' => 'moderation'))?>
<?php endif; ?>

<!-- Start: Tooltips -->
<div style="display:none;">
<?php if((count($solutions) > 0) && ($pitch->published == 1)):?>
    <?php foreach($solutions as $solution):	?>
        <?php if($pitch->private != 1):
            if($pitch->category_id == 7):
                //
            ?>
            <?php else:?>
                <?php if(!isset($solution->images['solution_galleryLargeSize'][0])):?>
                    <input type="hidden" rel="#<?=$solution->num?>" data-src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>">
                <?php else:?>
                    <input type="hidden" rel="#<?=$solution->num?>" data-src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'][0])?>">
                <?php endif?>
            <?php endif?>
        <?php else:?>
            <?php if($pitch->category_id == 7):
                //
            ?>
            <?php else:?>
                <?php if(($this->user->isPitchOwner($pitch->user_id)) || ($this->user->isExpert()) || ($this->user->isAdmin()) || ($this->user->isSolutionAuthor($solution->user_id))):?>
                    <?php if(!isset($solution->images['solution_galleryLargeSize'][0])):?>
                        <input type="hidden" rel="#<?=$solution->num?>" data-src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>">
                    <?php else:?>
                        <input type="hidden" rel="#<?=$solution->num?>" data-src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'][0])?>">
                    <?php endif?>
                <?php else:?>
                    <input type="hidden" rel="#<?=$solution->num?>" data-src="/img/copy-inv.png">
                <?php endif?>
            <?php endif?>
        <?php endif?>
    <?php endforeach;?>
<?php endif;?>
</div>
<!-- End: Tooltips -->
<div id="bridge" style="display:none;"></div>
<?=$this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), '//assets.pinterest.com/js/pinit.js', 'http://surfingbird.ru/share/share.min.js', 'jcarousellite_1.0.1.js', 'jquery.simplemodal-1.4.2.js', 'fancybox/jquery.mousewheel-3.0.4.pack.js', 'fancybox/jquery.fancybox-1.3.4.pack.js', 'jquery.raty.js', 'jquery.scrollto.min.js', 'jquery.damnUploader.js', 'jquery.hover.js', 'socialite.js', 'pitches/viewsolution.js?' . mt_rand(100, 999)), array('inline' => false))?>
<?=$this->html->style(array('/view', '/messages12', '/pitches12', '/pitch_overview', '/jquery.fancybox-1.3.4.css'), array('inline' => false))?>