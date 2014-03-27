<div class="wrapper pitchpanel login">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>

    <div class="middle">
        <div class="middle_inner_gallery" style="padding-top:25px">
            <?=$this->view()->render(array('element' => 'pitch-info/infotable'), array('pitch' => $pitch))?>
            <ul class="tabs-curve group">
                <li style="z-index: 3;">
                    <?=$this->html->link('Решения', array('controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id), array('class' => 'menu-toggle', 'data-page' => 'gallery'))?>
                </li>
                <li style="z-index: 2;">
                    <?=$this->html->link('Бриф', array('controller' => 'pitches', 'action' => 'details', 'id' => $pitch->id), array('class' => 'menu-toggle', 'data-page' => 'brief'))?>
                </li>
                <li class="active" style="z-index: 1;">
                    <?=$this->html->link('Участники', array('controller' => 'pitches', 'action' => 'designers', 'id' => $pitch->id), array('class' => 'menu-toggle', 'data-page' => 'designers'))?>
                </li>
            </ul>

            <nav class="other_nav_gallery clear">
                <p class="supplement4" style="float:left;height:30px;padding-top:20px;font-weight: bold; color:#b2afaf;">
                    <span style="display: inline-block; margin-top: 4px; vertical-align: top;">СОРТИРОВАТЬ ПО:</span>
                    <a class="sort-by-rating<?php if ($sort == 'rating'):?> active<?php endif;?>" href="/pitches/view/<?=$pitch->id?>?sorting=rating"><span title="сортировать по рейтингу"></span></a>
                    <a class="sort-by-likes<?php if ($sort == 'likes'):?> active<?php endif;?>" href="/pitches/view/<?=$pitch->id?>?sorting=likes"><span title="сортировать по лайкам"></span></a>
                    <a class="sort-by-created<?php if ($sort == 'created'):?> active<?php endif;?>" href="/pitches/view/<?=$pitch->id?>?sorting=created"><span title="сортировать по дате создания"></span></a>
                </p>
                <?php
                if(!$this->user->isPitchOwner($pitch->user_id) && ($pitch->status < 1) && ($pitch->published == 1)):?>
                    <a href="/pitches/upload/<?=$pitch->id?>" class="button" style="font-family:Arial,sans-serif;color:#ffffff;display:block;float:right;margin-right:20px;margin-top: -45px; width:155px">предложить решение</a>
                    <?php elseif(($pitch->status == 1) && ($pitch->awarded == 0)):?>
                    <!-- <img src="/img/status1.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Идет выбор победителя"/> -->
                    <?php elseif(($pitch->status == 1) && ($pitch->awarded != 0)):?>
                    <!-- <img src="/img/winner-selected.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Победитель выбран"/> -->
                    <?php elseif($pitch->status == 2):?>
                    <!-- <img src="/img/status2.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Питч завершен"/> -->
                <?php endif;?>
            </nav>

            <ul class="portfolio_gallery designers_tab">
            <?php foreach ($designers as $designer):?>
                <li>
                    <div class="message_info1">
                        <a href="/users/view/<?=$designer->user->id;?>">
                            <img src="<?=$this->avatar->show($designer->user->data(), false, true);?>" alt="Портрет пользователя" width="41" height="41">
                        </a>
                        <a href="/users/view/<?=$designer->user->id;?>">
                            <span><?=$this->user->getFormattedName($designer->user->first_name, $designer->user->last_name);?></span><br />
                            <span class="designer_plate"><?=count($designer->solutions);?> <?=$this->numInflector->formatString(count($designer->solutions), array('string' => array('first' => 'решение', 'second' => 'решения', 'third' => 'решений')))?></span>
                        </a>
                        <div class="clr"></div>
                    </div>

                    <?php $solutions = $designer->solutions;?>
                    <ul class="list_portfolio designers_tab">
                        <?=$this->view()->render(array('element' => 'gallery'), compact('solutions', 'pitch', 'selectedsolution', 'sort', 'canViewPrivate', 'solutionsCount'))?>
                        <li class="scroll_left" style="display: none;"><</li>
                        <li class="scroll_right" style="display: none;">></li>
                    </ul>
                </li>
            <?php endforeach;?>
            </ul>

            <div class="gallery_postload">
                <div class="separator"></div>
                <div class="gallery_postload_loader"><img alt="" src="/img/blog-ajax-loader.gif"></div>
                <a href="#" class="button_more next_part">Показать ещё <?php echo $limitDesigners; ?></a>
                <a href="#" class="button_more rest_part">Показать всех</a>
                <div class="separator"></div>
                <div style="clear: both;"></div>
            </div>

            <section class="white" style="margin: 0 -34px">
            <?=$this->view()->render(array('element' => 'pitchcommentform'), array('pitch' => $pitch, 'initialSeparator' => $initialSeparator))?>
            </section>

        </div><!-- /middle_inner -->
    </div><!-- /middle -->
</div><!-- .wrapper -->

<?=$this->view()->render(array('element' => 'popups/warning'))?>

<?=$this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), '//assets.pinterest.com/js/pinit.js', 'http://surfingbird.ru/share/share.min.js?v=5', 'jcarousellite_1.0.1.js', 'jquery.simplemodal-1.4.2.js', 'jquery.scrollto.min.js', 'socialite.js', 'pitches/view.js?' . mt_rand(100, 999), 'pitches/designers.js', 'jquery.hover.js', 'jquery.raty.min.js'), array('inline' => false))?>
<?=$this->html->style(array('/messages12', '/pitches12', '/view', '/pitch_overview'), array('inline' => false))?>
