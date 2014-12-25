<?php
	if(!isset($header)) {
		$header = 'header';
		if(!isset($logo)) {
			$logo = "logo light";
		}
	}else{
		if(!isset($logo)) {
			$logo = "logo";
		}
	}

	$templateView = $this;
	$this->helper('html')->applyFilter('script', function($self, $params, $chain) use ($templateView) {
	    $templateView->compressor->check($params);
	    $result = $chain->next($self, $params, $chain);
	    return $result;
	});
	$this->helper('html')->applyFilter('style', function($self, $params, $chain) use ($templateView) {
	    $templateView->compressor->check($params);
	    $result = $chain->next($self, $params, $chain);
	    return $result;
	});

?>
<?php if($this->user->getCountOfCurrentPitches() > 0):?>
<div id="pitch-panel">
    <div class="conteiner">
        <div class="content">
            <table class="all-pitches" id="header-table">
                <tbody>
                    <?php
                        $i = 0;
                        $pool = array(
                            'needpay' => array('<a href="/answers/view/6"><i id="help"></i>Какие способы оплаты вы принимаете?</a>'),
                            'current' => array('<a href="/answers/view/78"><i id="help"></i>Инструменты заказчика</a>', '<a href="/answers/view/73"><i id="help"></i>Как мотивировать дизайнеров</a>'),
                            'finish' => array('<a href="/answers/view/63"><i id="help"></i>Как работает завершающий этап?</a>'),
                            'winner' => array('<a href="/answers/view/70"><i id="help"></i>Как объявить победителя или номинировать работу?</a>'),
                            'winner' => array('<a href="/answers/view/97"><i id="help"></i>Как выбрать второго победителя?</a>'),

                        );
                        $types = array();
                        foreach($this->user->getCurrentPitches() as $mypitch):?>
                            <?php $pitchPath = 'view';
                            if($mypitch->ideas_count == 0) {
                                $pitchPath = 'details';
                            }
                            $step = 0;
                            if ($mypitch->awarded != 0) {
                                $step = $mypitch->winner->step;
                            }
                            if ($step < 1) {
                                $step = 1;
                            }
                            $types = array();
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
                            
                            if(($mypitch->multiwinner != 0) && ($mypitch->billed == 0)):
                                continue;
                            endif;
                            ?>
                            <tr data-id="<?=$mypitch->id?>" class="selection <?php if($i == 0): echo 'even'; else: echo 'odd'; endif;?> coda">
							<td>
							<?php if($mypitch->category != 7 &&($mypitch->status == 1) && ($mypitch->awarded != 0)):?>
                                                    <img class="pitches-image" src="<?php echo isset($mypitch->winner->images['solution_tutdesign'][0]) ? $mypitch->winner->images['solution_tutdesign'][0]['weburl'] :$mypitch->winner->images['solution_tutdesign']['weburl']?>">
							<?php endif?>
							</td>
                                <td class="pitches-name mypitches">
                                    <a href="/pitches/view/<?=($mypitch->multiwinner>0) ? $mypitch->multiwinner :$mypitch->id?>"><?=$this->PitchTitleFormatter->renderTitle($mypitch->title, 80)?></a>
                                </td>
                                <td <?php echo ($mypitch->status < 1) ? '' : 'colspan="2"' ?> class="pitches-status mypitches">
                                    <?php if(($mypitch->published == 1) && ($mypitch->status == 0)):
                                        $types['current'] += 1?>
                                    <a href="/pitches/<?=$pitchPath?>/<?=$mypitch->id?>">Текущий питч</a>
                                    <?php endif;?>
                                    <?php if(($mypitch->published == 0) && ($mypitch->billed == 0) && ($mypitch->status == 0) && ($mypitch->moderated != 1)):
                                        $types['needpay'] += 1?>
                                    <a href="<?= ($fastpitch !== false) ? '/pitches/fastpitch/' : '/pitches/edit/'?><?=$mypitch->id?>">Ожидание оплаты</a>
                                    <?php endif;?>
                                    <?php if(($mypitch->published == 0) && ($mypitch->billed == 0) && ($mypitch->status == 0) && ($mypitch->moderated == 1)):
                                        $types['needpay'] += 1?>
                                        <a href="/pitches/edit/<?=$mypitch->id?>" >Ожидание модерации</a>
                                    <?php endif;?>
                                    <?php if(($mypitch->published == 0) &&($mypitch->brief == 1) && ($mypitch->billed == 1) && ($mypitch->published == 0)):
                                        $types['needpay'] += 1?>
                                        <a href="/pitches/edit/<?=$mypitch->id?>">Ожидайте звонка</a>
                                    <?php endif;?>
                                    <?php if(($mypitch->status == 1) && ($mypitch->awarded != 0)):
                                        $types['finish'] += 1?>
                                        <a class="pitches-finish" href="/users/step<?=$step?>/<?=$mypitch->awarded?>">Перейти<br>на завершающий этап</a>
                                    <?php endif?>
                                    <?php if(($mypitch->status == 1) && ($mypitch->awarded == 0)):
                                        $types['winner'] += 1?>
                                        <a class="pitches-time" href="/pitches/<?=$pitchPath?>/<?=$mypitch->id?>">Выбор победителя</a>
                                    <?php endif?>
                                </td>
                                <td class="price mypitches">
                                    <?=$this->moneyFormatter->formatMoney($mypitch->price)?>
                                </td>
                                <?php if ($mypitch->status < 1):?>
                                <td class="pitches-edit mypitches">
                                    <?php if($mypitch->billed == 0):?>
                                    <a href="<?= $fast_url ?>#step3" class="mypitch_pay_link buy" title="оплатить">оплатить</a>
                                    <?php if($fastpitch === false):?>
                                    <a href="/pitches/edit/<?=$mypitch->id?>" class="edit mypitch_edit_link" title="редактировать">редактировать</a>
                                    <?php endif; ?>
                                    <a data-id="<?=$mypitch->id?>" href="/pitches/delete/<?=$mypitch->id?>" class="delete deleteheader mypitch_delete_link" title="удалить">удалить</a>
                                    <?php else:?>
                                    <a href="/pitches/edit/<?=$mypitch->id?>" class="edit mypitch_edit_link" title="редактировать">редактировать</a>
                                    <?php endif?>
                                </td>
                                <?php endif ?>
                            </tr>
                            <?php
                                $i++;
                                if($i > 1) $i = 0;

                        endforeach;
                        if(!empty($types)):
                            $randomPool = array();
                            foreach($types as $key => $type):
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
<?php endif?>
<?php if($this->user->getCountOfCurrentDesignersPitches() > 0):?>
<div id="pitch-panel">
    <div class="conteiner">
        <div class="content">
            <table class="all-pitches">
                <tbody>
                    <?php
                    $i = 0;
                    $pool = array(
                        '<a href="/answers/view/54"><i id="help"></i>Как работает завершающий этап?</a>',
                        '<a href="/answers/view/56"><i id="help"></i>Что, если заказчик просит вас сделать то, что не указано в брифе?</a>'
                    );
                    $types = array();
                    foreach($this->user->getCurrentDesignersPitches() as $mypitch):
                        if(($mypitch->multiwinner != 0) && ($mypitch->billed == 0)):
                            continue;
                        endif;
                    ?>
                    <tr data-id="<?=$mypitch->id?>" class="selection <?php if($i == 0): echo 'even'; else: echo 'odd'; endif;?> coda">
                        <td class="pitches-name">
                            <div style="background-image: none; padding: 15px 0 17px 40px;">
                                <?php if($mypitch->awarded != 0):
                                $step = $mypitch->winner->step;

                                //echo '';
                                if($step < 1) {
                                    $step = 1;
                                }

                                ?>
                                <a href="/users/step<?=$step?>/<?=$mypitch->awarded?>"><?=$mypitch->title?></a>
                                <?php endif?>
                                <!--span><?=$mypitch->industry?></span-->
                            </div></td>
                        <td class="pitches-cat"><a href="#"><?=$mypitch->category->title?></a></td>
                        <td class="idea"><?=$mypitch->ideas_count?></td>
                        <td class="pitches-time"><a style="color:#639F6D" href="/users/step<?=$step?>/<?=$mypitch->awarded?>">Победа!<br/> Завершите питч!</a></td>
                        <td class="price"><?=$this->moneyFormatter->formatMoney($mypitch->price)?></td></tr>
                        <?php
                        $i++;
                        if($i > 1) $i = 0;

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
<div id="header-bg" <?php if($this->_request->action == 'feed'): echo 'style="height: 60px;"'; endif;?>>

    <header class="<?=$header?>">

	<p class="<?=$logo?>"><strong><a href="/">Go Designer</a></strong></p>
	<nav class="topnav">
        <?php if($this->user->isLoggedIn()):?>
            <div class="avatar-top" style="width: 41px; float: left; height: 50px;">
                <img style="display:block; float:left;width:41px;" src="<?=$this->user->getAvatarUrl()?>" alt="" />
                <script>var currentAvatar = '<?=$this->user->getAvatarUrl()?>'</script>
            </div>
            <div class="topnav-menu" style="float:left;height:41px;padding-top:10px;">
            <?php if($this->user->getNewEventsCount() > 0):?>
                <a href="/users/feed" class="name-top" style="color:#fff;display:inline-block;">&nbsp;&nbsp;&nbsp;<?=$this->user->getFormattedName()?></a>
                <?=$this->html->link('(' . $this->user->getNewEventsCount() . ')', 'Users::feed', array('style' => 'color: #648FA4', 'class' => 'updatecurrent'))?><img class="name-top" id="menu_arrow" src="/img/arrow_down_header.png" alt="" style="padding-top:5px;"> /
            <?php else:?>
                <a href="/users/feed" class="name-top" style="color:#fff;display:inline-block;">&nbsp;&nbsp;&nbsp;<?=$this->user->getFullname()?></a><img class="name-top" id="menu_arrow" src="/img/arrow_header_up.png" alt="" style="padding-top:3px;"> /
            <?php endif?>

        <?php else:?>
            <div class="topnav-menu" style="float:left;height:41px;padding-left:10px;padding-top:10px;">
                <?=$this->html->link('Зарегистрироваться', 'Users::registration')?> /
                <?=$this->html->link('Войти', 'Users::login', array())?> /
                <?php endif?>
                <a href="/pages/howitworks">Как это работает?</a> /
                <a href="/pitches">Все питчи</a> /
                <?php if($this->user->getNewBlogpostCount() > 0):?>
                <a href="/posts">Блог</a><?php echo $this->html->link('(' . $this->user->getNewBlogpostCount() . ')', 'Posts::index', array('style' => 'color: #648FA4', 'class' => 'updatecurrent', 'escape' => false))?>
                <?php else:?>
                <a href="/posts">Блог</a>
                <?php endif?>
            </div>
            <ul class="header-menu">
                <li class="header-menu-item"><a href="/news">Новости</a></li>
                <li class="header-menu-item"><a href="/users/mypitches">Мои питчи</a></li>
                <li class="header-menu-item"><a href="/users/profile">Профиль</a></li>
                <li class="header-menu-item"><a href="/users/solutions">Решения</a></li>
                <li class="header-menu-item"><a href="/users/details">Реквизиты</a></li>
                <li class="header-menu-item"><a href="/questions">Тест</a></li>
                <li class="header-menu-item"><a href="/users/referal">Пригласи друга</a></li>
                <li class="header-menu-item"><a href="/users/logout">Выйти</a></li>
            </ul>
	</nav><!-- .nav -->

	<div class="add-pitch">
        <div style="float: left; height: 47px; padding-top: 7px;">
            <span style="text-decoration: none; font-weight: bold; font-size: 12px; margin-left: 12px;" class="current">+7 (812) 648 24 12</span>
            <br><a style="background: url(/img/smallmailicon.png) no-repeat 0 3px;padding-left:20px;font-size:11px;margin-right:10px;" href="#" id="requesthelplink">запросить помощь</a>
        </div>
        <?=$this->html->link('Cоздать питч', 'Pitches::create', array('class' => 'button third'))?>
    </div>

</header><!-- .header -->
</div>