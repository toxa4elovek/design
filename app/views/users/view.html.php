<div class="wrapper register" xmlns="http://www.w3.org/1999/html">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>
    <?php
    $userdata = unserialize($user->userdata);
    ?>
    <div class="middle">

        <div class="middle_inner user_view">

            <div class="profile">

                <div class="info_profile">

                    <div class="info_profile_photo">
                        <?=$this->avatar->show($user->data(), 'true')?>
                    </div>
                    <div class="info_profile_about">
                        <span class="nickname"><?=$this->nameInflector->renderName($user->first_name, $user->last_name )?>!</span>
                        <?php if(in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))):?>
                        <p style="margin-top:10px"><a href="mailto:<?=$user->email?>"><?=$user->email?></a> <a href="http://cp.godesigner.ru/users/loginasadmin?query=redirect&redirect=http://www.godesigner.ru/users/loginasuser/<?=$user->id?>">(войти)</a></p>
                        <p style="margin-top:10px">Онлайн: <?=date('d.m.Y H:i', strtotime($user->lastTimeOnline))?></p>
                        <?php if($user->silenceCount > 0):?>
                              <p style="margin-top:10px">Запретов на общение: <?=$user->silenceCount?></p>
                              <p style="margin-top:10px">Молчит до: <?=date('d.m.Y H:i:s', strtotime($user->silenceUntil))?></p>
                            <?php if(($user->silenceCount > 0) && (strtotime($user->silenceUntil) > strtotime(date('Y-m-d H:i:s')))):?>
                                <button style="margin-top:10px" class="allowcomment" data-term=""/>Разрешить комментарии</button>
                            <?php endif?>
                        <?php endif?>
                        <div style="margin-top:10px;">
                            <input type="hidden" value="<?=$user->id?>" id="user_id"/>
                        <button class="banhammer" data-term="10" />10 дней</button>
                        <button class="banhammer" data-term="30" />1 месяц</button>
                        <button class="banhammer" data-term="90" />3 месяца</button>

                            <button class="block" data-term="" <?php if($user->banned == 1):?>style="display: none;"<?php else:?>style="display: block;"<?php endif?>/>Заблокировать</button>
                            <button class="unblock" data-term="" <?php if($user->banned == 1):?>style="display: block;"<?php else:?>style="display: none;"<?php endif?>/>Разблокировать</button>

                        </div>
                        <?php endif?>
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
                                <li class="regular-small-grey" style="color:#666666;">Total Likes:<span> <?=$totalLikes?></span></li>
                                <li class="regular-small-grey" style="color:#666666;">Просмотров решений:<span> <?=$totalViews?></span></li>
                                <?php if($isClient):?>
                                <li class="regular-small-grey" style="color:#666666;">Рейтинг у дизайнеров:<span> <?=$averageGrade?></span></li>
                                <?php else:?>
                                <li class="regular-small-grey" style="color:#666666;">Рейтинг у заказчика:<span> <?=$averageGrade?></span></li>
                                <?php endif?>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php if(in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))):?>
                <div class="about_profile">
                    <dl>
                        <dt class="greyboldheader" style="margin-left: 60px">Обо мне:</dt>

                        <dd class="regular">
                            <?=$this->brief->stripemail(nl2br($userdata['about']))?>
                        </dd>
                        <div class="separate"> </div>
                    </dl>
                </div>
                <?php endif?>

                <?php if ( (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81)) || ($this->session->read('user.isAdmin') == 1)) && (!empty($moderations)) ):?>
                    <hr class="clr">
                    <h2>История:</h2>
                    <?php foreach ($moderations as $moderation): ?>
                        <div style="clear: both;">
                            <?=$moderation->user_id;?>
                            <?php $model = ($moderation->model == '\app\models\Comment') ? 'Удаление комментария' : 'Удаление решения';?>
                            <?php switch($moderation->reason) {
                                case 'plagiat':
                                    $reason = 'плагиат';
                                    break;
                                case 'template':
                                    $reason = 'использование шаблонов';
                                    break;
                                case 'other':
                                    $reason = 'другое';
                                    break;
                                case 'critique':
                                    $reason = 'публичную критику';
                                    break;
                                case 'link':
                                    $reason = 'ссылку';
                                    break;
                                default:
                                    $reason = 'просто так';
                                    break;
                                } ?>
                            <?php switch($moderation->penalty) {
                                case 0:
                                    $penalty = 'без штрафа';
                                    break;
                                case 1:
                                    $penalty = 'заблокирован';
                                    break;
                                default:
                                    $penalty = 'бан ' . (int) $moderation->penalty . ' дней';
                                    break;
                                } ?>
                            <h3><?=$model . ' за ' . $reason . ', ' . $penalty;?></h3>
                            <?=$moderation->explanation;?>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                <?php endif?>

            </div>

    <?php if(count($selectedSolutions) > 0):?>
        <div class="portfolio">
            <ul class="list_portfolio" style="margin-left:-25px;">
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
                        <a href="/pitches/viewsolution/<?=$solution->id?>"><img width="180"  height="135" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>" alt=""></a>
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
<?=$this->html->script(array('users/view'), array('inline' => false))?>
<?=$this->html->style(array('/cabinet', '/portfolio.css'), array('inline' => false))?>