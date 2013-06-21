<div class="wrapper register">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>
    <?php
    $userdata = unserialize($user->userdata);
    ?>
    <div class="middle">

        <div class="middle_inner">
            <nav class="main_nav clear">
                <?=$this->html->link('<span>Обновления</span>', array('controller' => 'users', 'action' => 'office'), array('escape' => false)) ?>
                <?=$this->html->link('<span>Мои питчи</span>', array('controller' => 'users', 'action' => 'mypitches'), array('escape' => false)) ?>
                <!--a href="#"><span>Сообщения</span></a-->
                <?=$this->html->link('<span>Профиль</span>', array('controller' => 'users', 'action' => 'profile'), array('escape' => false, 'class' => 'active')) ?>
                <?=$this->html->link('<span>Решения</span>', array('controller' => 'users', 'action' => 'solutions'), array('escape' => false)) ?>
                <?=$this->html->link('<span>Реквизиты</span>', array('controller' => 'users', 'action' => 'details'), array('escape' => false)) ?>
            </nav>
                <div class="profile">

                    <div class="info_profile">

                        <div class="info_profile_photo">
                            <?=$this->avatar->show($user->data(), 'true')?>
                        </div>
                        <div class="info_profile_about">
                            <span class="nickname"><?=$this->nameInflector->renderName($user->first_name, $user->last_name )?>!</span>
                            <ul class="profile-list-info">
                                <li class="regular-small-grey" style="color:#666666;"><?=$this->brief->stripemail($userdata['birthdate'])?></li>
                                <li class="regular-small-grey" style="color:#666666;"><?=$this->brief->stripemail($userdata['city'])?></li>
                                <li class="regular-small-grey" style="color:#666666;"><?=$this->brief->stripemail($userdata['profession'])?></li>
                            </ul>
                            <div class="pitches">
                                <ul class="profile-list">
                                    <li class="regular-small-grey" style="color:#666666;">Питчей:<span> <?=$pitchCount?></span></li>
                                    <li class="regular-small-grey" style="color:#666666;">Решений:<span> <?=$totalSolutionNum?></li>
                                    <li class="regular-small-grey" style="color:#666666;">Выиграно:<span> <?=$awardedSolutionNum?></span></li>
                                </ul>
                            </div>
                            <div class="likes">
                                <ul class="profile-list">
                                    <li class="regular-small-grey" style="color:#666666;">Total Likes:<span> <?=(int)$totalLikes?></span></li>
                                    <li class="regular-small-grey" style="color:#666666;">Просмотров решений:<span> <?=(int)$totalViews?></span></li>
                                    <?php if($isClient):?>
                                    <li class="regular-small-grey" style="color:#666666;">Рейтинг у заказчика:<span> <?=$averageGrade?></span></li>
                                    <?php else:?>
                                    <li class="regular-small-grey" style="color:#666666;">Рейтинг у заказчика:<span> <?=$averageGrade?></span></li>
                                    <?php endif?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <img src="/img/profile-separate.png" />

                    <div class="about_profile">
                        <dl>
                            <dt class="greyboldheader">Обо мне:</dt>
                            <dd class="regular">
                                <?=$this->brief->stripemail(nl2br($userdata['about']))?>
                            </dd>
                            <div class="separate"> </div>
                        </dl>
                    </div>
                </div>

            <?php if(count($selectedSolutions) > 0):?>
            <div class="portfolio">
                <ul class="list_portfolio" style="margin-left:-30px;">
                    <?php foreach($selectedSolutions as $solution):
                    if(($solution->pitch->private == 1) || ($solution->pitch->category_id == 7)):
                        continue;
                    endif
                    ?>
                    <li>
                        <div class="photo_block">
                            <?php if($solution->pitch->category_id == 7):?>
                            <a href="/pitches/viewsolution/<?=$solution->id?>" style="width:147px;height:104px;background-color:#efefef;display:block;color:#666666;text-decoration:none;font-weight:bold;padding-top:16px;padding: 16px;">
                                <?php if(mb_strlen(trim($solution->description)) > 100):?>
                                <?=mb_substr(trim($solution->description), 0, 100, 'UTF-8')?>
                                <?php else:?>
                                <?=trim($solution->description)?>
                                <?php endif?>
                            </a>
                            <?php else:?>
                            <a href="/pitches/viewsolution/<?=$solution->id?>"><img src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>" alt=""></a>
                            <?php endif?>
                            <?php if($solution->awarded == 1):?>
                            <span class="medal"></span>
                            <?php endif;?>
                            <div class="photo_opt">
                                <span class="rating_block"><img src="/img/<?=$solution->rating?>-rating.png" alt="" /></span>
                                            <span class="like_view"><img src="/img/looked.png" alt="" class="icon_looked" /><span><?=$solution->views?></span>
                                            <img src="/img/like.png" alt="" /><span><?=$solution->likes?></span></span>
                                <!--span class="bottom_arrow"><a href="#"><img src="/img/marker5_2.png" alt=""></a></span-->
                            </div>
                        </div>
                        <div class="selecting_numb">
                            <span class="number_img">#<?=$solution->num?></span>
                            <?=$this->html->link($solution->pitch->title, array('controller' => 'pitches', 'action' => 'view', 'id' => $solution->pitch->id), array('escape' => false))?>   </div>
                    </li>
                    <?php endforeach;?>
                </ul>
            </div>
            <?php endif?>

                <!--div class="send_message">
                    <a href=""><img src="/img/send-message.png" /></a>
                </div-->


        </div><!-- .main -->
        <div id="under_middle_inner"></div>
    </div><!-- .middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/cabinet', '/portfolio.css'), array('inline' => false))?>