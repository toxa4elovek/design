<div class="wrapper register">

    <?= $this->view()->render(array('element' => 'header'), array('logo' => 'logo')) ?>
    <?php
    $userdata = unserialize($user->userdata);
    ?>
    <div class="middle">

        <div class="middle_inner user_view" style="min-height:330px;">

            <?php if ($this->user->isAdmin()): ?>
                <div class="right-sidebar-user" style="<?php if((bool) $user->subscription_status): echo 'margin-top: 66px;'; endif;?>">
                    <a id="enter-name" class="order-button" href="http://cp.godesigner.ru/users/loginasadmin?query=redirect&redirect=https://godesigner.ru/users/loginasuser/<?= $user->id ?>">Войти под именем</a>
                    <p style="<?php if((bool) $user->subscription_status): echo 'margin-top:32px'; else: echo 'margin-top:41px';endif;?>"><a class="email-profile" href="mailto:<?= $user->email ?>"><?= $user->email ?></a></p>
                    <div class="g_line" style="margin-top: 8px;"></div>
                    <p class="regular-small-grey">Online: <span class="date-profile"><?= date('d.m.Y H:i', strtotime($user->lastTimeOnline)) ?></span></p>
                    <div class="g_line" style="margin-top: 5px; <?php if((bool) $user->subscription_status): echo 'padding-bottom:0'; else: echo 'padding-bottom:4px;';endif;?>"></div>
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
                    <button class="order-button banhammer" data-term="10" style="<?php if((bool) $user->subscription_status): echo 'margin-bottom:7px';endif;?>" />Бан на 10 дней</button>
                    <button class="order-button banhammer" data-term="30" style="<?php if((bool) $user->subscription_status): echo 'margin-bottom:7px';endif;?>" />Бан на 1 месяц</button>
                    <button class="order-button block" data-term="" <?php if ($user->banned == 1): ?>style="display: none;"<?php else: ?>style="display: inline;<?php if((bool) $user->subscription_status): echo 'margin-bottom:7px';endif;?>"<?php endif ?>/>Навсегда</button>
                    <button class="order-button unblock" data-term="" <?php if(($user->banned_until != '0000-00-00 00:00:00') || ($user->banned == 1)): ?>style="display: inline;<?php if((bool) $user->subscription_status): echo 'margin-bottom:7px';endif;?>"<?php else: ?>style="display: none;"<?php endif ?>/>Разблокировать</button>
                    <div class="g_line"></div>
                </div>
            <?php elseif ($this->user->isLoggedIn()):?>
                <div class="right-sidebar-user" style="<?php if((bool) $user->subscription_status): echo 'margin-top: 66px;'; endif;?>">
                    <a id="invite-user" class="order-button" style="padding:5px 15px 5px 13px" href="http://cp.godesigner.ru/users/loginasadmin?query=redirect&redirect=https://godesigner.ru/users/invite/<?= $user->id ?>">Пригласить в проект</a>
                </div>
            <?php endif ?>

            <div class="profile">

                <div class="info_profile">

                    <div class="info_profile_photo">
                        <?= $this->avatar->show($user->data(), 'true') ?>
                    </div>
                    <div class="info_profile_about">
                        <span class="nickname"><?= $this->user->getFormattedName($user->first_name, $user->last_name, true) ?></span>
                        <?php if((bool) $user->subscription_status):?>
                        <br/><span style="position: relative; top: 6px; left: 2px; font-size: 13px; font-family: 'OfficinaSansC Book', serif; text-decoration: none; text-transform:  none; color: #666666;">тариф <a href="/pages/subscribe#plans" target="_blank">«<?= $this->user->getCurrentPlanData($user->id)['title']?>»</a></span>
                        <?php endif ?>
                        <?php if ($this->user->isLoggedIn()): ?>
                            <a id="fav-user" data-id="<?= $user->id ?>" class="order-button rss-img-profile <?= $isFav ? 'unfav-user' : 'fav-user' ?>" href="#"><?= $isFav ? 'Отписаться' : 'Подписаться' ?></a>
                        <?php endif; ?>
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

            <?php if($userPitches):?>
                <div class="middle_inner conteiners" style="text-transform: uppercase; margin-top: 0;padding-left: 0; padding-top: 45px;">
                    <section>
                        <table id="primary" style="margin-left: 0; width: 835px;" class="all-pitches">
                            <thead>
                            <tr>
                                <td class="" style="height: 38px !important; text-align: left; padding:0 10px 0 34px; width: 255px; font-family: OfficinaSansC Book, serif"><a id="sort-title" class="sort-link" style="background-image: none;padding: 0;">название</a></td>
                                <td class="pitches-cat" style="text-align: center; font-family: OfficinaSansC Book, serif;height: 38px !important;"><a id="sort-category" class="sort-link"  style="background-image: none;padding: 0;">Категории</a></td>
                                <td class="idea" style="text-align: center; font-family: OfficinaSansC Book, serif;height: 38px !important;"><a  id="sort-ideas_count" class="sort-link"  style="background-image: none;padding: 0;">Идеи</a></td>
                                <td class="pitches-time" style="text-align: center; font-family: OfficinaSansC Book, serif;height: 38px !important;"><a  id="sort-finishDate" class="sort-link"  style="background-image: none;padding: 0;">Статус</a></td>
                                <td style=" font-family: OfficinaSansC Book, serif;text-align: center; padding:0 10px 0 10px; height: 38px !important;"><a id="sort-price" class="sort-link" style="background-image: none;padding: 0;">Цена</a></td>
                            </tr>
                            </thead>
                            <tbody id="table-content">
                            <?php
                            $i = 1;
                            foreach($userPitches as $pitch):
                                $rowClass = 'odd';
                                if(($i % 2 == 0)) {
                                    $rowClass = 'even';
                                }
                                if((strtotime($pitch['started']) + DAY) > time())  {
                                    $rowClass .= ' newpitch';
                                }else {
                                    if(($pitch['pinned'] == 1) && ($pitch['status'] == 0)) {
                                        $rowClass .= ' highlighted';
                                    }
                                }

                                if($pitch['status'] == 0) {

                                    if (($pitch['published'] == 0) && ($pitch['billed'] == 0) && ($pitch['moderated'] != 1)) {
                                        $timeleft = '<a href="/pitches/edit/' . $pitch['id'] . '#step3">Ожидание оплаты</a>';
                                    } else if (($pitch['published'] == 0) && ($pitch['billed'] == 0) && ($pitch['moderated'] == 1)) {
                                        $timeleft = 'Ожидание<br />модерации';
                                    } else if (($pitch['published'] == 0) && ($pitch['billed'] == 1) && ($pitch['brief'] == 1)) {
                                        $timeleft = 'Ожидайте звонка';
                                    } else {
                                        $timeleft = $pitch['startedHuman'];
                                    }
                                } else if (($pitch['status'] == 1) && ($pitch['awarded'] == 0)) {
                                    $rowClass .= ' selection';
                                    $timeleft = 'Выбор победителя';
                                } else if (($pitch['status'] == 2) || (($pitch['status'] == 1) && ($pitch['awarded'] > 0))) {
                                    $rowClass .= ' pitch-end';
                                    if ($pitch['status'] == 2) {
                                        $timeleft = 'Проект завершен';
                                    }else if(($pitch['status'] == 1) && ($pitch['awarded'] > 0)) {
                                        $timeleft = 'Победитель выбран';
                                    }else if(($pitch['status'] == 1) && ($pitch['awarded'] == 0)) {
                                        $timeleft = 'Выбор победителя';
                                    }else {
                                        $timeleft = $pitch['startedHuman'];
                                    }
                                }
                                $textGuarantee = '';
                                if($pitch['guaranteed'] == 1) {
                                    $textGuarantee = '<br><span style="font-size: 11px; font-weight: normal; font-family: Arial;text-transform:uppercase">гарантированы</span>';
                                }
                                $categoryLinkHref = '#';
                                if($pitch['category_id'] == 20) {
                                    $categoryLinkHref = '/pages/subscribe';
                                }
                                $multiple = (is_null($pitch['multiple'])) ? '' : '<br>' . $pitch['multiple'];
                                ?>
                                <tr class="selection <?=$rowClass?>">
                                    <td class="pitches-name pitch-title" style="width: 255px;">
                                        <a href="#">
                                            <img class="pitches-name-td-img expand-link" src="/img/arrow.png" style="display: none;">
                                        </a>
                                        <div style="padding-left: 34px; padding-right: 12px;">
                                            <a href="/pitches/view/<?=$pitch['id']?>" class="" style="color: #fff;"><?=$pitch['title']?></a>
                                        </div>
                                    </td>
                                    <td class="pitches-cat" style="padding-left: 10px; width: 102px; padding-right: 10px;">
                                        <a href="<?=$categoryLinkHref?>" style="font-family: Helvetica, sans-serif;font-size: 11px;font-weight:bold;color:#fff;"><?=$pitch['category']['title'] . $multiple?></a>
                                    </td>
                                    <td class="idea" style="font-family: Helvetica, sans-serif;font-size: 11px;font-weight:bold;color:#fff;"><?= $pitch['ideas_count'] ?></td>
                                    <td class="pitches-status mypitches" style="font-family: Helvetica, sans-serif;font-size: 11px;font-weight:bold;color:#fff;"><?=$timeleft?></td>
                                    <td class="price"><?= $this->moneyFormatter->formatMoney($pitch['price'], array('suffix' => ' Р.-')) .
                                        $textGuarantee ?></td>
                                </tr>
                                <?php
                                $i++;
                            endforeach;?>
                            </tbody>
                        </table>
                    </section>
                </div>
                <div class="g_line" style="width: 835px;margin-bottom: 40px;margin-top: 10px;"></div>
            <?php endif?>

            <?php
            if (count($selectedSolutions) > 0): ?>
                <div class="portfolio">
                    <ul class="list_portfolio" style="margin-left:-25px;">
                        <?php
                        foreach ($selectedSolutions as $solution):
                            if ((($solution->pitch->private == 1) || ($solution->pitch->isCopyrighting())) && !$this->user->isAdmin()):
                                continue;
                            endif
                            ?>
                            <li style="margin-bottom: 35px;">
                                <div class="photo_block">
                                    <?php if ($solution->pitch->category_id == 7): ?>
                                        <a href="/pitches/viewsolution/<?= $solution->id ?>" style="width:147px;height:104px;background-color:#efefef;display:block;color:#666666;text-decoration:none;font-weight:normal;padding-top:16px;padding: 16px;">
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
<?= $this->view()->render(['element' => 'popups/invite_popup']); ?>
<?= $this->html->script(array('users/view'), array('inline' => false)) ?>
<?=
$this->html->style(array('/cabinet', '/portfolio', '/messages12'), array('inline' => false))?>