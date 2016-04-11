<div class="wrapper register">

    <?= $this->view()->render(array('element' => 'header'), array('logo' => 'logo')) ?>
    <?php
    $userdata = unserialize($user->userdata);
    ?>
    <div class="middle">

        <div class="middle_inner">
            <nav class="main_nav clear">
                <?= $this->view()->render(array('element' => 'office/nav')); ?>
            </nav>
            <div class="profile">

                <div class="info_profile">

                    <div class="info_profile_photo">
                        <?= $this->avatar->show($user->data(), 'true') ?>
                    </div>
                    <div class="info_profile_about">
                        <span class="nickname"><?= $this->user->getFormattedName($user->first_name, $user->last_name) ?></span>
                        <?php if((bool) $user->subscription_status):?>
                            <br/><span style="position: relative; top: 6px; left: 2px; font-size: 13px; font-family: OfficinaSansBookC; text-decoration: none; text-transform:  none; color: #666666;">тариф <a href="/pages/subscribe#plans" target="_blank">«<?= $this->user->getCurrentPlanData($user->id)['title']?>»</a></span>
                        <?php endif ?>
                        <ul class="profile-list-info"></ul>
                        <div class="pitches">
                            <ul class="profile-list">
                                <li class="regular-small-grey" style="color:#666666;">Город:<span> <?= $this->brief->stripUrl($this->brief->removeEmailClean($userdata['city'])) ?></span></li>
                                <div class="g_line"></div>
                                <li class="regular-small-grey" style="color:#666666;">Проектов:<span> <?= $pitchCount ?></span></li>
                                <div class="g_line"></div>
                                <li class="regular-small-grey" style="color:#666666;">Решений:<span> <?= $totalSolutionNum ?></span></li>
                                <div class="g_line"></div>
                                <li class="regular-small-grey" style="color:#666666;">Выиграно:<span> <?= $awardedSolutionNum ?></span></li>
                                <div class="g_line"></div>
                                <li class="regular-small-grey" style="color:#666666;">Фолловеров:<span> <?= $totalFavoriteMe ?></span></li>
                                <div class="g_line"></div>
                            </ul>
                        </div>
                        <div class="likes">
                            <ul class="profile-list">
                                <li class="regular-small-grey" style="color:#666666;">Дата рожд:<span> <?= $this->brief->stripUrl($this->brief->removeEmailClean($userdata['birthdate'])) ?></span></li>
                                <div class="g_line"></div>
                                <li class="regular-small-grey" style="color:#666666;">Total Likes:<span> <?= $totalLikes ?></span></li>
                                <div class="g_line"></div>
                                <li class="regular-small-grey" style="color:#666666;">Просмотров решений:<span> <?= $totalViews ?></span></li>
                                <div class="g_line"></div>
                                <?php if ($isClient): ?>
                                    <li class="regular-small-grey" style="color:#666666;">Рейтинг у дизайнеров:<span> <?= $averageGrade ?></span></li>
                                <?php else: ?>
                                    <li class="regular-small-grey" style="color:#666666;">Рейтинг у заказчика:<span> <?= $averageGrade ?></span></li>
                                <?php endif ?>
                                <div class="g_line"></div>
                                <li class="regular-small-grey" style="color:#666666;">Подписан(а):<span> <?= $totalUserFavorite ?></span></li>
                                <div class="g_line"></div>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="about_profile clr">
                    <!--dl>
                        <dt class="greyboldheader">Обо мне:</dt>
                        <dd class="regular">
                            <?= $this->brief->stripEmail(nl2br($userdata['about'])) ?>
                        </dd>
                        <div class="separate"> </div>
                    </dl-->
                </div>

            </div>
            <!--h3 style="text-shadow: 0 1px 1px #FFFFFF;
margin-top: 50px;font-family: OfficinaSansC Bold, serif; text-align: center; font-size: 25px; font-weight: normal; color: #666666;">Укажите 5 тегов, которые описывают ваше решение</h3>
            <p style="text-shadow: 0 1px 1px #FFFFFF; margin-top: 20px;font-family: OfficinaSansC Book, serif; font-size: 17px; text-align: center; color: #666; margin-bottom: 40px;">Это поможет найти вашу идею тем, кто захочет его<br> купить. Т. о. мы дарим вам возможность продать работу,<br> если та не станет победителем с первого раза.</p-->

            <?php if (count($selectedSolutions) > 0): ?>
                <div class="portfolio">
                    <ul class="list_portfolio" style="margin-left:-25px;">
                        <?php
                        foreach ($selectedSolutions as $solution):
                            if (($solution->pitch->private == 1) || ($solution->pitch->isCopyrighting())):
                                continue;
                            endif
                            ?>
                            <li style="margin-bottom: 35px;">
                                <div class="selecting_numb">
                                    <span class="number_img_new">#<?= $solution->num ?></span>
                                    <?= $this->html->link($solution->pitch->title, array('controller' => 'pitches', 'action' => 'view', 'id' => $solution->pitch->id), array('escape' => false)) ?>   </div>
                                <div class="photo_block">
                                        <?php if ($solution->pitch->category_id == 7): ?>
                                        <a href="/pitches/viewsolution/<?= $solution->id ?>" style="width:147px;height:104px;background-color:#efefef;display:block;color:#666666;text-decoration:none;font-weight:bold;padding-top:16px;padding: 16px;">
                                            <?php if (mb_strlen(trim($solution->description)) > 100): ?>
                                                <?= mb_substr(trim($solution->description), 0, 100, 'UTF-8') ?>
                                            <?php else: ?>
                                                <?= trim($solution->description) ?>
                                        <?php endif ?>
                                        </a>
                                    <?php else: ?>
                                        <a href="/pitches/viewsolution/<?= $solution->id ?>"><img width="180" height="135" src="<?= $this->solution->renderImageUrl($solution->images['solution_galleryLargeSize']) ?>" alt=""></a>
                                    <?php endif ?>
                                    <?php if ($solution->awarded == 1): ?>
                                        <span class="medal"></span>
        <?php endif; ?>
                                    <div class="photo_opt">
                                        <span class="rating_block"><img src="/img/<?= $solution->rating ?>-rating.png" alt="" /></span>
                                        <span class="like_view"><img src="/img/looked.png" alt="" class="icon_looked" /><span><?= $solution->views ?></span>
                                            <img src="/img/like.png" alt="" /><span><?= $solution->likes ?></span></span>
                                <!--span class="bottom_arrow"><a href="#"><img src="/img/marker5_2.png" alt=""></a></span-->
                                    </div>
                                </div>
                                <!--div style="padding-top: 16px;">
                                    <form class="tag_submit" data-solutionid="<?= $solution->id ?>">
                                    <input type="text" name="tag" style="
                                        width: 168px;
                                        margin-bottom: 15px;
                                        height: 30px;
                                        <?php if(count($solution->tags) > 4):?>
                                            display: none;
                                        <?php endif?>
                                    ">
                                    </form>
                                    <ul class="tags" data-solutionid="<?= $solution->id ?>">
                                        <?php
                                        if(is_array($solution->tags)):
                                        foreach($solution->tags as $tag):?>
                                        <li style="padding-left: 10px; padding-right: 10px; margin-right:6px; height: 21px; padding-top: 5px; margin-bottom:3px;"><?= $tag?>
                                        <a class="removeTag" href="#" style="margin-left: 10px;">
                                            <img src="/img/delete-tag.png" alt="" style="padding-top: 2px;">
                                        </a>
                                        </li>
                                        <?php endforeach;endif;?>
                                    </ul>
                                </div-->
                            </li>
    <?php endforeach; ?>
                    </ul>
                </div>
<?php endif ?>

            <!--div class="send_message">
                <a href=""><img src="/img/send-message.png" /></a>
            </div-->


        </div><!-- .main -->
        <div id="under_middle_inner"></div>
    </div><!-- .middle -->

</div><!-- .wrapper -->
<?=
$this->html->script(array('users/preview'), array('inline' => false))?>
<?=
$this->html->style(array('/cabinet', '/portfolio.css'), array('inline' => false))?>