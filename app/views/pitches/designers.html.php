<div class="wrapper pitchpanel login">

    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo', 'header' => 'header2'])?>

    <div class="middle">
        <div class="middle_inner_gallery" style="padding-top:25px">
            <?=$this->view()->render(['element' => 'pitch-info/infotable'], ['pitch' => $pitch])?>
            <ul class="tabs-curve group">
                <li style="z-index: 3;">
                    <?=$this->html->link('Решения', ['controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id], ['class' => 'menu-toggle ajaxgallery', 'data-page' => 'gallery'])?>
                </li>
                <li style="z-index: 2;">
                    <a href="/pitches/view/<?= $pitch->id ?>/?tab=details" class="menu-toggle ajaxgallery" data-page="brief">Бриф</a>
                </li>
                <li class="active" style="z-index: 1;">
                    <a href="/pitches/view/<?= $pitch->id ?>/?tab=designers" class="menu-toggle ajaxgallery" data-page="designers">Участники</a>
                </li>
            </ul>
            <div class="gallery_container">
                <nav class="other_nav_gallery clear">
                    <form id="designers-search">
                        <input type="text" id="designer-name-search" name="search" value="<?=$search?>" class="text" placeholder="Поиск по имени" data-placeholder="Поиск по имени">
                        <input type="submit" class="designer-submit" value="">
                    </form>
                    <p class="supplement4" style="float:left;height:30px;padding-top:20px;font-weight: bold; color:#b2afaf;">
                        <span style="display: inline-block; margin-top: 4px; vertical-align: top;">СОРТИРОВАТЬ ПО:</span>
                        <a class="sort-by-number<?php if ($sort == 'number'):?> active<?php endif;?>" href="/pitches/designers/<?=$pitch->id?>?sorting=number"><span title="сортировать по количеству решений"></span></a>
                        <a class="sort-by-created<?php if ($sort == 'created'):?> active<?php endif;?>" href="/pitches/designers/<?=$pitch->id?>?sorting=created"><span title="сортировать по дате создания"></span></a>
                        <a class="sort-by-rating<?php if ($sort == 'rating'):?> active<?php endif;?>" href="/pitches/designers/<?=$pitch->id?>?sorting=rating"><span title="сортировать по рейтингу"></span></a>
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
                        <?php elseif (($pitch->status == 1) && ($pitch->awarded == 0)):?>
                        <!-- <img src="/img/status1.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Идет выбор победителя"/> -->
                        <?php elseif (($pitch->status == 1) && ($pitch->awarded != 0)):?>
                        <!-- <img src="/img/winner-selected.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Победитель выбран"/> -->
                        <?php elseif ($pitch->status == 2):?>
                        <!-- <img src="/img/status2.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Питч завершен"/> -->
                    <?php endif;?>
                </nav>

                <?php if (($designersCount > 0) && ($pitch->published == 1)): ?>
                <ul class="portfolio_gallery designers_tab">
                    <?=$this->view()->render(['element' => 'designers'], compact('designers', 'pitch', 'sort', 'canViewPrivate', 'fromDesignersTab', 'designersCount', 'winnersUserIds'))?>
                </ul>
                <?php else: ?>
                <div class="bigfont clr" style="margin-bottom: 76px; padding-top: 80px;">
                    <h2 class="title clr">Ещё никто не выложил свои идеи.</h2>
                    <?php if (!$this->user->isPitchOwner($pitch->user_id)):?>
                    <h2 class="title"><?=$this->html->link('предложи свое решение', ['controller' => 'pitches', 'action' => 'upload', 'id' => $pitch->id], ['escape' => false])?></h2>
                    <h2 class="title">и стань первым!</h2>
                    <?php endif?>
                </div>
                <?php endif; ?>

                <?php $initialSeparator = false;
                if (count($designers) < $designersCount): $initialSeparator = true; ?>
                <div class="gallery_postload designers_tab">
                    <div class="separator"></div>
                    <div class="gallery_postload_loader"><img alt="" src="/img/blog-ajax-loader.gif"></div>
                    <a href="#" class="button_more next_part_design">Показать ещё <?php echo $limitDesigners; ?></a>
                    <a href="#" class="button_more rest_part_design">Показать всех</a>
                    <div class="separator"></div>
                    <div style="clear: both;"></div>
                </div>
                <?php endif; ?>
                <section class="white" style="margin: 0 -34px">
                <?=$this->view()->render(['element' => 'pitchcommentform'], compact('pitch', 'initialSeparator', 'fromDesignersTab'))?>
                </section>
            </div><!-- /gallery_container -->
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->
</div><!-- .wrapper -->

<script>
    var autosuggestUsers = <?php echo json_encode($autosuggestUsers)?>;
</script>

<?=$this->view()->render(['element' => 'popups/warning'], ['freePitch' => $freePitch, 'pitchesCount' => $pitchesCount, 'pitch' => $pitch])?>

<?=$this->html->script([
    'flux/flux.min.js',
    '/js/enjoyhint.js',
    'http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999),
    '//assets.pinterest.com/js/pinit.js',
    'jquery.simplemodal-1.4.2.js',
    'jquery-plugins/jquery.scrollto.min.js',
    'jquery.hover.js',
    'jquery.raty.min.js',
    'jquery-ui-1.11.4.min.js',
    'jquery.timeago.js',
    'social-likes.min.js',
    'konva.0.9.5.min.js',
    '/js/common/comments/UserAutosuggest.js',
    '/js/common/comments/actions/CommentsActions.js',
    'pitches/plot.js',
    'pitches/view.js',
    'pitches/gallery.js'
], ['inline' => false])?>
<?=$this->html->style(['/css/enjoyhint.css', '/messages12', '/pitches12', '/view', '/pitch_overview'], ['inline' => false])?>
