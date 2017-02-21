<?php
    if (!isset($header)) {
        $header = 'header';
        if (!isset($logo)) {
            $logo = "logo light";
        }
    } else {
        if (!isset($logo)) {
            $logo = "logo";
        }
    }
?>
<?php if ($this->user->getCountOfCurrentPitches() > 0):?>
<div id="pitch-panel">
    <div class="conteiner">
        <div class="content">
            <table class="all-pitches" id="header-table">
                <tbody>
                    <?php
                        $i = 0;
                        $pool = [
                            'needpay' => ['<a href="/answers/view/6"><i id="help"></i>Какие способы оплаты вы принимаете?</a>'],
                            'current' => ['<a href="/answers/view/78"><i id="help"></i>Инструменты заказчика</a>', '<a href="/answers/view/73"><i id="help"></i>Как мотивировать дизайнеров</a>'],
                            'finish' => ['<a href="/answers/view/63"><i id="help"></i>Как работает завершающий этап?</a>'],
                            'winner' => ['<a href="/answers/view/70"><i id="help"></i>Как объявить победителя или номинировать работу?</a>', '<a href="/answers/view/97"><i id="help"></i>Как выбрать второго победителя?</a>'],
                        ];
                        $types = [];
                        foreach ($this->user->getCurrentPitches() as $mypitch):?>
                            <?php $pitchPath = 'view';
                            if ($mypitch->ideas_count == 0) {
                                $pitchPath = 'details';
                            }
                            $step = 0;
                            if ($mypitch->awarded != 0) {
                                $step = $mypitch->winner->step;
                            }
                            if ($step < 1) {
                                $step = 1;
                            }
                            $types = [];
                            $types['current'] = 0;
                            $types['needpay'] = 0;
                            $types['finish'] = 0;
                            $types['winner'] = 0;
                            $fast_url = '';
                            $fastpitch = strpos($mypitch->title, 'Логотип в один клик');
                            if ($fastpitch !== false) {
                                $fast_url = '/pitches/fastpitch/'. $mypitch->id;
                            } else {
                                $fast_url = '/pitches/edit/'. $mypitch->id;
                            }
                            $discount = '';
                            $viewUrl = "/pitches/view/$mypitch->id";

                            if (($mypitch->type == 'plan-payment') && ($mypitch->billed == 1)):
                                continue;
                            endif;
                            if (($mypitch->blank == 1) && ($mypitch->billed == 0)):
                                continue;
                            endif;
                            if (($mypitch->type == 'penalty') or ($mypitch->type == 'fund-balance')):
                                continue;
                            endif;
                            if ($mypitch->type == 'plan-payment') {
                                $viewUrl = '/pages/subscribe';
                                $fast_url = '/subscription_plans/subscriber/' . $this->pitch->getPlanForPayment($mypitch->id);
                                if ($discountAmount = $this->user->getSubscriptionDiscount($this->user->getId())) {
                                    $discount = " (скидка — $discountAmount%)";
                                }
                            }
                            ?>
                            <tr data-id="<?=$mypitch->id?>" class="selection <?php if ($i == 0): echo 'even'; else: echo 'odd'; endif;?> coda">
							<td>
							<?php if ($mypitch->category_id != 7 &&($mypitch->status == 1) && ($mypitch->awarded != 0)):?>
                                                    <img data="<?= $mypitch->category_id ?>" class="pitches-image" src="<?php echo isset($mypitch->winner->images['solution_tutdesign'][0]) ? $mypitch->winner->images['solution_tutdesign'][0]['weburl'] :$mypitch->winner->images['solution_tutdesign']['weburl']?>">
							<?php endif?>
							</td>
                                <td class="pitches-name mypitches">
                                    <a href="<?= $viewUrl?>"><?=$this->PitchTitleFormatter->renderTitle($mypitch->title, 80)?><?=$discount?></a>
                                </td>
                                <td <?php echo (($mypitch->status < 1) || ($mypitch->multiwinner > 0 && $mypitch->billed == 0)) ? '' : 'colspan="2"' ?> class="pitches-status mypitches">
                                    <?php if (($mypitch->published == 1) && ($mypitch->status == 0)):
                                        $types['current'] += 1?>
                                    <a href="/pitches/<?=$pitchPath?>/<?=$mypitch->id?>">Текущий проект</a>
                                    <?php endif;?>
                                    <?php if (($mypitch->published == 0) && ($mypitch->billed == 0) && ($mypitch->status == 0) && ($mypitch->moderated != 1) && ($mypitch->type != 'plan-payment')):
                                        $types['needpay'] += 1?>
                                        <a href="https://godesigner.ru<?= ($fastpitch !== false) ? '/pitches/fastpitch/' : '/pitches/edit/'?><?=$mypitch->id?>">Ожидание оплаты</a>
                                    <?php endif;?>
                                    <?php if (($mypitch->published == 0) && ($mypitch->billed == 0) && ($mypitch->status > 0) && ($mypitch->multiwinner != 0) && ($mypitch->type != 'plan-payment')):
                                        $types['needpay'] += 1?>
                                        <a href="https://godesigner.ru<?= ($fastpitch !== false) ? '/pitches/fastpitch/' : '/pitches/edit/'?><?=$mypitch->id?>">Ожидание оплаты</a>
                                        <a data-id="<?=$mypitch->id?>" href="/pitches/delete/<?=$mypitch->id?>" class="delete deleteheader mypitch_delete_link" title="удалить">удалить</a>
                                    <?php endif;?>
                                    <?php if (($mypitch->billed == 0) && ($mypitch->status == 0) && ($mypitch->type == 'plan-payment')):
                                        $types['needpay'] += 1?>
                                        <a href="<?= $fast_url?>">Ожидание оплаты</a>
                                    <?php endif;?>
                                    <?php if (($mypitch->published == 0) && ($mypitch->billed == 0) && ($mypitch->status == 0) && ($mypitch->moderated == 1)):
                                        $types['needpay'] += 1?>
                                        <a href="/pitches/edit/<?=$mypitch->id?>" >Ожидание модерации</a>
                                    <?php endif;?>
                                    <?php if (($mypitch->published == 0) &&($mypitch->brief == 1) && ($mypitch->billed == 1) && ($mypitch->published == 0)):
                                        $types['needpay'] += 1?>
                                        <a href="/pitches/edit/<?=$mypitch->id?>">Ожидайте звонка</a>
                                    <?php endif;?>
                                    <?php if (($mypitch->status == 1) && ($mypitch->billed == 1) &&  ($mypitch->awarded != 0)):
                                        $types['finish'] += 1?>
                                        <?php if (($mypitch->blank == 1) && ($mypitch->confirmed == 0)):?>
                                        <span style="text-transform: uppercase; font-size: 11px;color:#639F6D;padding-left: 14px;" >Ожидание подтверждения</span><br>
                                        <span style="text-transform: uppercase; font-size: 11px;color:#639F6D;padding-left: 14px;" class="countdown" data-deadline="<?=(strtotime($mypitch->started)) + 3 * DAY;?>"><?php echo ($interval = $this->pitch->confirmationTimeRemain($mypitch)) ? $interval->format('%d дн. %H:%I:%S') : ''; ?></span>
                                        <?php else:?>
                                        <a class="pitches-finish" href="/users/step<?=$step?>/<?=$mypitch->awarded?>">Перейти<br>на завершающий этап</a>
                                        <?php endif?>
                                    <?php endif?>
                                    <?php if (($mypitch->status == 1) && ($mypitch->awarded == 0)):
                                        $types['winner'] += 1;
                                        ?>
                                        <?php if (($mypitch->status == 1) && ($mypitch->awarded == 0) && (time() > $this->pitch->getChooseWinnerTime($mypitch)) && !$this->pitch->isWaitingForExperts($mypitch)):
                                        $style = 'color: #ff5360;';
                                        $delay = '&nbsp;&nbsp;(–' . (round((time() - $this->pitch->getChooseWinnerTime($mypitch)) / 60 / 60, 0, PHP_ROUND_HALF_DOWN)) . ' Ч.)';
                                        ?>
                                        <a class="pitches-time" style="<?= $style ?>" href="/pitches/<?=$pitchPath?>/<?=$mypitch->id?>">
                                            Истёк срок выбора победителя<br/>Штраф &mdash; <?= $this->pitch->getPenalty($mypitch)?> р.
                                        </a>
                                        <?php else: ?>
                                        <a class="pitches-time" href="/pitches/<?=$pitchPath?>/<?=$mypitch->id?>">Выбор победителя</a>
                                        <?php endif?>
                                    <?php endif?>
                                </td>
                                <td class="price mypitches">
                                    <?=$this->moneyFormatter->formatMoney($mypitch->price)?>
                                </td>
                                <?php if (($mypitch->status < 1) || ($mypitch->multiwinner > 0 && $mypitch->billed = 0)):?>
                                <td class="pitches-edit mypitches">
                                    <?php if ($mypitch->billed == 0):?>
                                        <?php if ($mypitch->multiwinner == 0):?>
                                            <a href="<?= $fast_url ?>#step3" class="mypitch_pay_link buy" title="оплатить">оплатить</a>
                                        <?php else:?>
                                            <a href="/pitches/newwinner/<?=$mypitch->id?>" class="mypitch_pay_link buy" title="оплатить">оплатить1</a>
                                        <?php endif?>
                                        <?php if (($fastpitch === false) && ($mypitch->multiwinner == 0) && ($mypitch->type != 'plan-payment')):?>
                                            <a href="/pitches/edit/<?=$mypitch->id?>" class="edit mypitch_edit_link" title="редактировать">редактировать</a>
                                        <?php endif; ?>
                                        <?php if ($mypitch->multiwinner == 0):?>
                                        <a data-id="<?=$mypitch->id?>" href="/pitches/delete/<?=$mypitch->id?>" class="delete deleteheader mypitch_delete_link" title="удалить">удалить</a>
                                        <?php endif; ?>
                                    <?php elseif (($mypitch->multiwinner == 0) && ($mypitch != 'plan-payment')):?>
                                        <a href="/pitches/edit/<?=$mypitch->id?>" class="edit mypitch_edit_link" title="редактировать">редактировать</a>
                                    <?php endif?>
                                </td>
                                <?php endif ?>
                            </tr>
                            <?php
                                $i++;
                                if ($i > 1) {
                                    $i = 0;
                                }

                        endforeach;
                        if (!empty($types)):
                            $randomPool = [];
                            foreach ($types as $key => $type):
                                $randomPool[] = $pool[$key][array_rand($pool[$key])];
                            endforeach;
                        endif;
                        $helpLink =$randomPool[array_rand($randomPool)];
                    ?>
                </tbody>
            </table>
            <p class="pitch-buttons-legend">
                <?php echo $helpLink?>
            </p>
        </div>
    </div>
</div>
    <script>
        var currentActiveProjects = [];
        <?php foreach ($this->user->getCurrentPitches() as $mypitch):?>
            currentActiveProjects.push({
              'id': <?=$mypitch->id?>, 'title': "<?=$mypitch->title?>"
            });
        <?php endforeach ?>
    </script>
<?php endif?>
<?php if ($this->user->getCountOfCurrentDesignersPitches() > 0):?>
<div id="pitch-panel">
    <div class="conteiner">
        <div class="content">
            <table class="all-pitches">
                <tbody>
                    <?php
                    $i = 0;
                    $pool = [
                        '<a href="/answers/view/54"><i id="help"></i>Как работает завершающий этап?</a>',
                        '<a href="/answers/view/56"><i id="help"></i>Что, если заказчик просит вас сделать то, что не указано в брифе?</a>'
                    ];
                    $types = [];
                    foreach ($this->user->getCurrentDesignersPitches() as $mypitch):
                        if (($mypitch->multiwinner != 0) && ($mypitch->billed == 0)):
                            continue;
                        endif;
                        $closing = false;
                        if ($mypitch->status > 0) {
                            $closing = true;
                        }
                    ?>
                    <tr data-id="<?=$mypitch->id?>" class="selection <?php if ($i == 0): echo 'even'; else: echo 'odd'; endif;?> coda">
                        <?php if ($mypitch->invite === false):?>
                        <td width="105" style="text-align: left; border: 0;">
                            <?php if ((int) $mypitch->category_id !== 7 && (((int) $mypitch->status === 2) || ((int) $mypitch->status === 1)) && ((int) $mypitch->awarded !== 0)):?>
                                <img data="<?= $mypitch->category_id ?>" class="pitches-image" src="<?php echo isset($mypitch->winner->images['solution_tutdesign'][0]) ? $mypitch->winner->images['solution_tutdesign'][0]['weburl'] :$mypitch->winner->images['solution_tutdesign']['weburl']?>">
                            <?php endif?>
                        </td>
                        <?php endif ?>
                        <td class="pitches-name" <?php if ($closing):?>colspan="3"<?php endif ?>>
                            <div style="background-image: none; padding: 15px 0 17px 40px;">
                                <?php if ((int) $mypitch->awarded !== 0):
                                $step = $mypitch->winner->step;
                                if ($step < 1) {
                                    $step = 1;
                                }
                                ?>
                                <a href="/users/step<?=$step?>/<?=$mypitch->awarded?>"><?=$mypitch->title?></a>
                                <?php endif?>
                                <?php if ($mypitch->invited):?>
                                    <span style="text-transform: none; color: #e2e2e2; font-size: 13px; font-family: Arial, sans-serif">Заказчик приглашает вас в проект:</span><br>
                                    <a href="/pitches/view/<?=$mypitch->id?>"><?=$mypitch->title?></a>
                                <?php endif?>
                            </div></td>
                        <?php if (((int) $mypitch->blank !== 1) && (!$mypitch->invited)):?>
                            <?php if (!$closing):?>
                            <td class="pitches-cat"><a href="#"><?=$mypitch->category->title?></a></td>
                            <td class="idea"><?=$mypitch->ideas_count?></td>
                            <?php endif ?>
                        <td class="pitches-time"><a style="color:#639F6D; font-size: 12px;" href="/users/step<?=$step?>/<?=$mypitch->awarded?>">
                        <?php if ((int) $mypitch->status === 1):?>
                                Победа!<br/> Завершите проект!
                        <?php elseif ((int) $mypitch->status === 2): ?>
                            Победа!<br/> Проставьте рейтинг!
                        <?php endif?>
                        </a></td>
                        <?php elseif (((int) $mypitch->blank === 1) && ((int) $mypitch->confirmed === 0)): ?>
                        <td class="pitches-time"  style="text-align: left; padding-left: 8px; width: 175px;">
                            <a style="font-size: 11px;color:#639F6D;background: url(/img/header/header_tick.png) 0 2px no-repeat;padding-left: 14px;" href="/pitches/accept/<?=$mypitch->id?>">Подтвердить &nbsp;&nbsp;<span class="countdown" data-deadline="<?=(strtotime($mypitch->started)) + 3 * DAY;?>"><?php echo ($interval = $this->pitch->confirmationTimeRemain($mypitch)) ? $interval->format('%d дн. %H:%I:%S') : ''; ?></span></a><br><br>
                            <a style="font-size: 11px;color:#666666;background: url(/img/header/header_cross.png) 0 2px no-repeat;padding-left: 14px;" class="popup-decline" data-title="<?=$mypitch->title?>" data-solutionid="<?=$mypitch->awarded?>" data-solutionnum="<?=$mypitch->winner->num?>" data-pitchid="<?=$mypitch->id?>" href="/pitches/decline/<?=$mypitch->id?>">Отказать</a>
                        </td>
                        <?php elseif (((int) $mypitch->blank === 1) && ((int) $mypitch->confirmed === 1)): ?>
                        <td class="pitches-time"  style="text-align: left; padding-left: 8px; width: 175px;">
                            <a style="font-size: 11px;color:#639F6D;" class="pitches-finish" href="/users/step2/<?=$mypitch->awarded?>">Перейти<br>на завершающий этап</a>
                        </td>
                        <?php elseif ((int) $mypitch->invited === 1): ?>
                            <td class="pitches-time"  style="text-align: left; padding-left: 8px; width: 175px;">
                                <a style="font-size: 11px;color:#639F6D;background: url(/img/header/header_tick.png) 0 2px no-repeat;padding-left: 14px;" href="/invites/accept/<?=$mypitch->id?>">Подтвердить участие</a><br><br>
                                <a style="font-size: 11px;color:#666666;background: url(/img/header/header_cross.png) 0 2px no-repeat;padding-left: 14px;" href="/invites/decline/<?=$mypitch->id?>">Отказаться от участия</a>
                            </td>
                        <?php endif ?>
                        <td class="price"><?=$this->moneyFormatter->formatMoney($mypitch->price)?></td></tr>
                        <?php
                        $i++;
                        if ($i > 1) {
                            $i = 0;
                        }

                    endforeach;
                    $helpLink = $pool[array_rand($pool)];
                    ?>
                </tbody>
            </table>
            <p class="pitch-buttons-legend" style="height:16px;">
                <?php echo $helpLink?>
            </p>
        </div>
    </div>
</div>
<?php endif?>
<div id="header-bg" <?php if ($this->_request->action == 'feed'): echo 'style="height: 60px;"'; endif;?>>

    <header class="<?=$header?>">

	<p class="<?=$logo?>"><a href="/"
        <?php if (!$this->user->getId()):?>
             style="background: url(/img/logo2.png) 0 0 no-repeat"
        <?php endif?>>Go Designer</a></p>
	<nav class="topnav">
        <?php if ($this->user->isLoggedIn()):?>
            <div class="avatar-top" style="width: 41px; float: left; height: 50px;">
                <img style="display:block; float:left;width:41px;" src="<?=$this->user->getAvatarUrl()?>" alt="" />
                <script>var currentAvatar = '<?=$this->user->getAvatarUrl()?>'</script>
            </div>
            <div class="topnav-menu" style="float:left;height:41px;padding-top:10px;">
                <a href="/users/preview/<?= $this->user->getId()?>" class="name-top" style="color:#fff;display:inline-block;">&nbsp;&nbsp;&nbsp;<?=$this->user->getFormattedName()?></a>
                <?php if ($this->user->getBalance() > 0):
                    if ($this->user->isSubscriptionActive()):?>
                        <a href="/subscription_plans/subscriber" style="color: white;position: absolute;left: 81px;top: 34px;"><?= $this->MoneyFormatter->formatMoney($this->user->getBalance(), ['suffix' => ' р.']) ?></a>
                    <?php else:?>
                        <a href="/users/subscriber" style="color: white;position: absolute;left: 81px;top: 34px;"><?= $this->MoneyFormatter->formatMoney($this->user->getBalance(), ['suffix' => ' р.']) ?></a>
                    <?php endif;
                    endif?>
                <img class="name-top" id="menu_arrow" src="/img/arrow_header_up.png" alt="" style="padding-top:3px;"> /

        <?php else:?>
            <div class="topnav-menu" style="float:left;height:41px;padding-left:10px;padding-top:10px;">
                <?php endif?>
                <a href="/news">Лента</a> /
                <a href="/pages/howitworks">Как это работает?</a> /
                <a href="/pitches">Все проекты</a> /
                <?php if ($this->user->getNewBlogpostCount() > 0):?>
                <a href="/posts">Блог</a><?php echo $this->html->link('(' . $this->user->getNewBlogpostCount() . ')', 'Posts::index', ['style' => 'color: #648FA4', 'class' => 'updatecurrent', 'escape' => false])?>
                <?php else:?>
                <a href="/posts">Блог</a>
                <?php endif?>
                <?php if (!$this->user->isLoggedIn()):?>
                    /  <a href="/login">Вход</a>
                <?php endif?>
            </div>
            <ul class="header-menu" <?php if ($this->user->isSubscriptionActive()):?>style="height: 253px;"<?php endif?>>
                <li class="header-menu-item"><a href="/users/mypitches">Мои проекты</a></li>
                <li class="header-menu-item"><a href="/users/profile">Настройки</a></li>
                <li class="header-menu-item"><a href="/users/preview/<?=$this->user->getId()?>">Профиль</a></li>
                <?php if ($this->user->isSubscriptionActive()):?>
                    <li class="header-menu-item header-menu-item-new"><a href="/users/subscriber">Абонентский кабинет</a></li>
                <?php else:?>
                    <li class="header-menu-item header-menu-item-new"><a href="/pages/subscribe">Годовое обслуживание</a></li>
                <?php endif?>
                <li class="header-menu-item"><a href="/users/solutions">Решения</a></li>
                <?php if (!$this->user->isSubscriptionActive()):?>
                <li class="header-menu-item"><a href="/questions">Тест</a></li>
                <?php endif?>
                <?php
                //$referalUrl = '/users/referal';
                //if ($this->user->getAwardedSolutionNum() > 0) {
                    $referalUrl = '/users/subscribers_referal';
                //}
                ?>
                <li class="header-menu-item"><a href="<?= $referalUrl ?>">Пригласи друга</a></li>
                <li class="header-menu-item"><a href="/users/logout">Выйти</a></li>
            </ul>
	</nav><!-- .nav -->

	<div class="add-pitch">
        <div style="float: left; height: 47px; padding-top: 7px;">
            <span style="text-decoration: none; font-weight: bold; font-size: 12px; margin-left: 12px;" class="current">+7 (812) 648 24 12</span>
            <br><a style="background: url(/img/smallmailicon.png) no-repeat 0 3px;padding-left:20px;font-size:11px;margin-right:10px;" href="#" id="requesthelplink">запросить помощь</a>
        </div>
        <?=$this->html->link('Cоздать проект', 'Pitches::create', ['class' => 'top-button button third'])?>
    </div>

</header><!-- .header -->
</div>