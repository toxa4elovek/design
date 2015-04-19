<div class="wrapper register">

    <?= $this->view()->render(array('element' => 'header'), array('logo' => 'logo')) ?>
    <?php
    $userdata = unserialize($user->userdata);
    ?>
    <div class="middle">

        <div class="middle_inner user_view" style="min-height:330px;">

            <?php if ($this->user->isAdmin()): ?>
                <div class="right-sidebar-user">
                    <a id="enter-name" class="order-button" href="http://cp.godesigner.ru/users/loginasadmin?query=redirect&redirect=http://www.godesigner.ru/users/loginasuser/<?= $user->id ?>">Войти под именем</a>
                    <p style="margin-top:41px"><a class="email-profile" href="mailto:<?= $user->email ?>"><?= $user->email ?></a></p>
                    <div class="g_line" style="margin-top: 8px;"></div>
                    <p class="regular-small-grey">Online: <span class="date-profile"><?= date('d.m.Y H:i', strtotime($user->lastTimeOnline)) ?></span></p>
                    <div class="g_line" style="margin-top: 5px;"></div>
                    <?php if ($user->silenceCount > 0): ?>
                        <p class="regular-small-grey">Запретов на общение: <?= $user->silenceCount ?></p>
                        <div class="g_line" style="margin-top: 7px;"></div>
                        <p class="regular-small-grey">Молчит до: <?= date('d.m.Y H:i:s', strtotime($user->silenceUntil)) ?></p>
                        <div class="g_line" style="margin-top: 8px;"></div>
                        <?php if (($user->silenceCount > 0) && (strtotime($user->silenceUntil) > strtotime(date('Y-m-d H:i:s')))): ?>
                            <button class="order-button allowcomment" style="margin-top:10px" data-term=""/>Разрешить комментарии</button>
                        <?php endif ?>
                    <?php endif ?>
                    <input type="hidden" value="<?= $user->id ?>" id="user_id"/>
                    <button class="order-button banhammer" data-term="10" />Бан на 10 дней</button>
                    <button class="order-button banhammer" data-term="30" />Бан на 1 месяц</button>
                    <button class="order-button block" data-term="" <?php if ($user->banned == 1): ?>style="display: none;"<?php else: ?>style="display: inline;"<?php endif ?>/>Навсегда</button>
                    <button class="order-button unblock" data-term="" <?php if ($user->banned == 1): ?>style="display: inline;"<?php else: ?>style="display: none;"<?php endif ?>/>Разблокировать</button>
                    <div class="g_line"></div>
                </div>
            <?php endif ?>

            <div class="profile">

                <div class="info_profile">

                    <div class="info_profile_photo">
                        <?= $this->avatar->show($user->data(), 'true') ?>
                    </div>
                    <div class="info_profile_about">
                        <span class="nickname"><?= $this->user->getFormattedName($user->first_name, $user->last_name) ?></span>
                        <?php if ($this->user->isLoggedIn()): ?>
                            <a id="fav-user" data-id="<?= $user->id ?>" class="order-button rss-img-profile <?= $isFav ? 'unfav-user' : 'fav-user' ?>" href="#"><?= $isFav ? 'Отписаться' : 'Подписаться' ?></a>
                        <?php endif; ?>
                        <ul class="profile-list-info"></ul>
                        <div class="pitches">
                            <ul class="profile-list">
                                <li class="regular-small-grey" style="color:#666666;">Город:<span> <?= $this->brief->stripurl($this->brief->removeEmailClean($userdata['city'])) ?></span></li>
                                <div class="g_line"></div>
                                <li class="regular-small-grey" style="color:#666666;">Питчей:<span> <?= $pitchCount ?></span></li>
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
                                <li class="regular-small-grey" style="color:#666666;">Дата рожд:<span> <?= $this->brief->stripurl($this->brief->removeEmailClean($userdata['birthdate'])) ?></span></li>
                                <div class="g_line"></div>
                                <li class="regular-small-grey" style="color:#666666;">Лайков:<span> <?= $totalLikes ?></span></li>
                                <div class="g_line"></div>
                                <li class="regular-small-grey" style="color:#666666;">Просмотров:<span> <?= $totalViews ?></span></li>
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

                <?php if ($this->user->isAdmin() || $user->id == 18856): ?>
                    <div class="about_profile clr">
                        <dl>
                            <?php if (trim($userdata['about']) != ''): ?>
                                <dt class="greyboldheader" style="width: 119px;">Обо мне:
                                <?php if ($this->user->isAdmin()): ?>
                                    <br><span class="regular">(доступно только <br>администрации)</span>
                                <?php endif; ?>
                                </dt>
                                <dd class="regular">
                                    <?php echo nl2br($userdata['about']) ?>
                                </dd>
                            <?php endif ?>
                            <div class="separate"> </div>
                        </dl>
                    </div>
                <?php endif ?>

                <?php if ($this->user->isAdmin() && (count($moderations) > 0) && (!empty($moderations))): ?>
                    <hr class="tiny-hr">
                    <h2 class="greyboldheader">История:</h2>
                    <?php foreach ($moderations as $moderation): ?>
                        <?= $this->view()->render(array('element' => 'user/moderation'), array('moderation' => $moderation)) ?>
                    <?php endforeach; ?>
                <?php endif ?>
            </div>

            <?php
            if (count($selectedSolutions) > 0): ?>
                <div class="portfolio">
                    <ul class="list_portfolio" style="margin-left:-25px;">
                        <?php
                        foreach ($selectedSolutions as $solution):
                            if ((($solution->pitch->private == 1) || ($solution->pitch->category_id == 7)) && !$this->user->isAdmin()):
                                continue;
                            endif
                            ?>
                            <li>
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
                                        <a href="/pitches/viewsolution/<?= $solution->id ?>"><img width="180"  height="135" src="<?= $this->solution->renderImageUrl($solution->images['solution_galleryLargeSize']) ?>" alt=""></a>
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
                                <div class="selecting_numb">
                                    <span class="number_img">#<?= $solution->num ?></span>
                                    <?= $this->html->link($solution->pitch->title, array('controller' => 'pitches', 'action' => 'view', 'id' => $solution->pitch->id), array('escape' => false)) ?>   </div>
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
<?= $this->html->script(array('users/view'), array('inline' => false)) ?>
<?=
$this->html->style(array('/cabinet', '/portfolio', '/messages12'), array('inline' => false))?>