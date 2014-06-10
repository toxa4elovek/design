<div class="wrapper pitchpanel login">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>

    <div class="middle">
        <div class="middle_inner_gallery" style="padding-top:25px">
            <?=$this->view()->render(array('element' => 'pitch-info/infotable'), array('pitch' => $pitch))?>
            <ul class="tabs-curve group">
                <li style="z-index: 3;">
                    <?=$this->html->link('Решения', array('controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id), array('class' => 'menu-toggle ajaxgallery', 'data-page' => 'gallery'))?>
                </li>
                <li style="z-index: 2;">
                    <?=$this->html->link('Бриф', array('controller' => 'pitches', 'action' => 'details', 'id' => $pitch->id), array('class' => 'menu-toggle ajaxgallery', 'data-page' => 'brief'))?>
                </li>
                <li class="active" style="z-index: 1;">
                    <?=$this->html->link('Участники', array('controller' => 'pitches', 'action' => 'designers', 'id' => $pitch->id), array('class' => 'menu-toggle ajaxgallery', 'data-page' => 'designers'))?>
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
                    if(!$this->user->isPitchOwner($pitch->user_id) && ($pitch->status < 1) && ($pitch->published == 1)):?>
                        <a href="/pitches/upload/<?=$pitch->id?>" class="button add_solution<?php echo ($this->user->designerTimeRemain()) ? ' needWait' : '';?>">предложить решение</a>
                        <?php elseif(($pitch->status == 1) && ($pitch->awarded == 0)):?>
                        <!-- <img src="/img/status1.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Идет выбор победителя"/> -->
                        <?php elseif(($pitch->status == 1) && ($pitch->awarded != 0)):?>
                        <!-- <img src="/img/winner-selected.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Победитель выбран"/> -->
                        <?php elseif($pitch->status == 2):?>
                        <!-- <img src="/img/status2.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Питч завершен"/> -->
                    <?php endif;?>
                </nav>

                <?php if(($designersCount > 0) && ($pitch->published == 1)): ?>
                <ul class="portfolio_gallery designers_tab">
                    <?=$this->view()->render(array('element' => 'designers'), compact('designers', 'pitch', 'sort', 'canViewPrivate', 'fromDesignersTab', 'designersCount'))?>
                </ul>
                <?php else: ?>
                <div class="bigfont clr" style="margin-bottom: 76px; padding-top: 80px;">
                    <h2 class="title clr">Ещё никто не выложил свои идеи.</h2>
                    <?php if(!$this->user->isPitchOwner($pitch->user_id)):?>
                    <h2 class="title"><?=$this->html->link('предложи свое решение', array('controller' => 'pitches', 'action' => 'upload', 'id' => $pitch->id), array('escape' => false))?></h2>
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
                <?=$this->view()->render(array('element' => 'pitchcommentform'), compact('pitch', 'initialSeparator', 'fromDesignersTab'))?>
                </section>
            </div><!-- /gallery_container -->
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->
</div><!-- .wrapper -->

<?=$this->view()->render(array('element' => 'popups/warning'))?>

<?=$this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), '//assets.pinterest.com/js/pinit.js', 'http://surfingbird.ru/share/share.min.js?v=5', 'jquery.simplemodal-1.4.2.js', 'jquery.scrollto.min.js', 'socialite.js', 'jquery.hover.js', 'jquery.raty.min.js', 'jquery-ui-1.8.23.custom.min.js', 'jquery.timeago.js', 'kinetic-v4.5.4.min.js', 'pitches/plot.js', 'pitches/view.js', 'pitches/gallery.js'), array('inline' => false))?>
<?=$this->html->style(array('/messages12', '/pitches12', '/view', '/pitch_overview'), array('inline' => false))?>
