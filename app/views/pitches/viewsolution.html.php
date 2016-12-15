<div class="wrapper pitchpanel login">

	<?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>

    <?=$this->view()->render(array('element' => 'scripts/viewsolution_init'), array('pitch' => $pitch))?>
	<?php if((($pitch->status > 0) && ($this->user->isAllowedToComment()) && (($this->user->isPitchOwner($pitch->user_id)) || $this->user->isManagerOfProject($pitch->id) || ($this->user->isExpert()) || ($this->user->isAdmin()) )) ||
        (($pitch->status == 0) && ($pitch->published == 1) && ($this->user->isAllowedToComment())) && ($this->user->isLoggedIn())):?>
        <script>allowComments = true;</script>
    <?php endif?>
	<div class="middle">
        <!-- start: Solution Container -->
        <div class="solution-container page">
            <div style="padding: 25px 0 0 63px;">
                <?=$this->view()->render(array('element' => 'pitch-info/infotable'), array('pitch' => $pitch))?>
            </div>
            <!-- start: Solution Right Panel -->
            <div class="solution-right-panel page">
                <div class="solution-info solution-summary">
                    <div class="solution-number">#<span class="number isField"><!--  --></span></div>
                    <div style="height: 180px;margin-top: 20px;margin-bottom: 20px;">
                        <aside style="position: relative; top: 0; left: 0; margin-left: 0;" class="summary-price expanded">
                            <h3>Итого:</h3>
                            <p class="summary"><strong id="total-tag">
                                    <?php if($this->user->isSubscriptionActive()):?>
                                        7500р.-
                                    <?php else: ?>
                                        9500р.-
                                    <?php endif ?>
                                </strong></p><!-- .summary -->
                            <ul id="check-tag">
                            </ul>
                            <div class="hide">
                                <span id="to-pay">Перейти к оплате</span>
                            </div>
                        </aside><!-- .summary-price -->
                        <!-- end: Solution Left Panel -->
                    </div>
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
                    <span id="date" style="color:#878787;">Опубликовано <?=$date?></span><br/>
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
                <?php if(!$this->pitch->isReadyForLogosale($solution->pitch)):?>
                <div class="solution-info solution-tags chapter">
                    <h2>ТЕГИ</h2>
                    <ul class="tags">
                        <?php foreach ($solution->tags as $v) : ?>
                        <li><?= $v ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="clear"></div>
                </div>
                <div class="separator"></div>
                <?php endif ?>
                <div class="solution-info solution-share chapter">
                    <h2>ПОДЕЛИТЬСЯ</h2>
                    <?php if((($solution->pitch->private != 1) && ($solution->pitch->category_id != 7))):
                        if (rand(1, 100) <= 50) {
                            $tweetLike = 'Мне нравится этот дизайн! А вам?';
                        } else {
                            $tweetLike = 'Из всех ' . $solution->pitch->ideas_count . ' мне нравится этот дизайн';
                        }
                        if($this->pitch->isReadyForLogosale($solution->pitch)) {
                            $tweetLike = "Этот логотип можно приобрести у автора за 9500 рублей на распродаже; адаптация названия и 2 правки включены»";
                        }
                        if(!isset($solution->solution->images['solution_galleryLargeSize'][0])):
                            $url = 'https://www.godesigner.ru' . $solution->images['solution_gallerySiteSize']['weburl'];
                        else:
                            $url = 'https://www.godesigner.ru' . $solution->images['solution_gallerySiteSize'][0]['weburl'];
                        endif;
                        ?>
                        <div style="display: block; height: 75px">
                            <div class="social-likes" data-counters="no" data-url="https://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>" data-title="<?= $tweetLike ?>">
                                <div class="facebook" title="Поделиться ссылкой на Фейсбуке">SHARE</div>
                                <div class="twitter">TWITT</div>
                                <div class="vkontakte" title="Поделиться ссылкой во Вконтакте" data-image="<?= 'https://www.godesigner.ru'. $this->solution->renderImageUrl($solution->images['solution_solutionView'])?>">SHARE</div>
                                <div class="pinterest" title="Поделиться картинкой на Пинтересте" data-media="<?= 'https://www.godesigner.ru'. $this->solution->renderImageUrl($solution->images['solution_solutionView'])?>">PIN</div>
                            </div>
                            <div style="clear:both;width:300px;height:1px;"></div>
                        </div>
                    <?php endif;?>
                </div>
                <div class="separator"></div>
                <div class="solution-info solution-abuse isField"><!--  --></div>
            <!-- end: Solution Right Panel -->
            </div>
            <!-- start: Solution Left Panel -->
            <div class="solution-left-panel">
                <!-- start: Soluton Images -->
                <section class="solution-images isField bla">
                    <div style="text-align:center;height:220px;padding-top:180px"><img alt="" src="/img/blog-ajax-loader.gif"></div>
                <!-- end: Solution Images -->
                </section>
                <section class="allow-comments">
                    <input type="hidden" value="<?=$pitch->category_id?>" name="category_id" id="category_id">
                    <input type="hidden" value="<?=$solution->id?>" name="solution_id">
                    <input type="hidden" value="<?=$pitch->id?>" name="pitch_id">
                    <?php if ($this->user->isPitchOwner($pitch->user->id) || $this->user->isManagerOfProject($pitch->id) ||  $this->user->isAdmin()): ?>
                    <div class="separator full"></div>
                    <form class="createCommentForm" method="post" action="/comments/add">
                    	<textarea id="newComment" data-user-autosuggest="true" name="text"></textarea>
                        <div></div>
                    	<input type="hidden" value="" name="comment_id">
                        <input type="hidden" value="/pitches/viewsolution/<?=$solution->id?>" name="from">
                        <input type="button" src="/img/message_button.png" value="Публиковать комментарий для всех" class="button createComment" data-is_public="1" style="margin: 15px 18px 15px 0;">
                        <input type="button" src="/img/message_button.png" value="Отправить только дизайнеру" class="button createComment" data-is_public="0" style="margin: 15px 0 15px 18px;">
                        <div class="public-loader-container">
                            <img class="public-loader" src="/img/blog-ajax-loader.gif" alt="Идёт загрузка">
                        </div>
                        <div class="private-loader-container">
                            <img class="private-loader" src="/img/blog-ajax-loader.gif" alt="Идёт загрузка">
                        </div>
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
            <?php if($this->pitch->isReadyForLogosale($pitch)):?>
                <?= $this->view()->render(array('element' => 'logosalepay'), array('data' => $data)) ?>
            <?php endif?>
        <!-- end: Solution Container -->
        </div>
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
	</div><!-- /middle -->
</div><!-- .wrapper -->

<?=$this->view()->render(array('element' => 'popups/warning'), array('freePitch' => $freePitch, 'pitchesCount' => $pitchesCount, 'pitch' => $pitch))?>

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
                    <input type="hidden" rel="#<?=$solution->num?>" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>">
                <?php else:?>
                    <input type="hidden" rel="#<?=$solution->num?>" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'][0])?>">
                <?php endif?>
            <?php endif?>
        <?php else:?>
            <?php if($pitch->category_id == 7):
                //
            ?>
            <?php else:?>
                <?php if(($this->user->isPitchOwner($pitch->user_id)) || ($this->user->isManagerOfProject($pitch->id)) || ($this->user->isExpert()) || ($this->user->isAdmin()) || ($this->user->isSolutionAuthor($solution->user_id))):?>
                    <?php if(!isset($solution->images['solution_galleryLargeSize'][0])):?>
                        <input type="hidden" rel="#<?=$solution->num?>" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>">
                    <?php else:?>
                        <input type="hidden" rel="#<?=$solution->num?>" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'][0])?>">
                    <?php endif?>
                <?php else:?>
                    <input type="hidden" rel="#<?=$solution->num?>" src="/img/copy-inv.png">
                <?php endif?>
            <?php endif?>
        <?php endif?>
    <?php endforeach;?>
<?php endif;?>
</div>
<!-- End: Tooltips -->
<div id="bridge" style="display:none;"></div>

<script>
    var autosuggestUsers = <?php echo json_encode($autosuggestUsers)?>;
</script>

<?=$this->html->script(array('flux/flux.min.js', 'http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), '//assets.pinterest.com/js/pinit.js', 'jquery.simplemodal-1.4.2.js', 'fancybox/jquery.mousewheel-3.0.4.pack.js', 'fancybox/jquery.fancybox-1.3.4.pack.js', 'jquery.raty.js', 'jquery-plugins/jquery.scrollto.min.js', 'jquery.damnUploader.js', 'jquery.hover.js', 'socialite.js', 'social-likes.min.js',     '/js/common/comments/UserAutosuggest.js',
    '/js/common/comments/actions/CommentsActions.js', 'pitches/viewsolution.js?' . mt_rand(100, 999)), array('inline' => false))?>
<?=$this->html->style(array(
    '/css/common/receipt.css',
    '/view', '/messages12', '/pitches12', '/pitch_overview', '/css/viewsolution', '/jquery.fancybox-1.3.4.css', '/css/social-likes_flat'), array('inline' => false))?>