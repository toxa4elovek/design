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
?>
<?php if(count($this->session->read('user.currentpitches')) > 0):?>
<div id="pitch-panel">
    <div class="conteiner" style="margin-top: 0px;">
        <div class="content">
            <table class="all-pitches" id="header-table">
                <tbody>
                    <?php
                        $i = 0;
                        $pool = array(
                            'needpay' => array('<a href="/answers/view/6"><i id="help"></i>Какие способы оплаты вы принимаете?</a>'),
                            'current' => array('<a href="/answers/view/78"><i id="help"></i>Инструменты заказчика</a>', '<a href="/answers/view/73"><i id="help"></i>Как мотивировать дизайнеров</a>'),
                            'finish' => array('<a href="/answers/view/63"><i id="help"></i>Как работает завершающий этап?</a>'),
                            'winner' => array('<a href="/answers/view/70"><i id="help"></i>Как объявить победителя или номинировать работу?</a>')

                        );
                        $types = array();
                        foreach($this->session->read('user.currentpitches') as $mypitch):?>
                            <?php $pitchPath = 'view';
                            if($mypitch->ideas_count == 0) {
                                $pitchPath = 'details';
                            } ?>
                        <tr data-id="<?=$mypitch->id?>" class="selection <?php if($i == 0): echo 'even'; else: echo 'odd'; endif;?> coda">
                            <td class="pitches-name">
                                    <?php if($mypitch->billed == 0):?>
                                    <a data-id="<?=$mypitch->id?>" href="/pitches/delete/2" class="delete deleteheader mypitch_delete_link" title="удалить"><img width="12px" height="12px" class="pitches-name-td-img" src="/img/delete_icon_white.png"></a>
                                    <a href="/pitches/edit/<?=$mypitch->id?>" class="edit mypitch_edit_link" title="редактировать"><img width="18" height="18" class="pitches-name-td-img" src="/img/edit_icon_white.png"></a>
                                    <a href="/pitches/edit/<?=$mypitch->id?>#step3" class="mypitch_pay_link buy" title="оплатить"><img width="18" height="18" class="pitches-name-td2-img" src="/img/buy_icon_white.png"></a>
                                    <?php elseif($mypitch->status < 1):?>
                                    <a href="/pitches/edit/<?=$mypitch->id?>" class="edit mypitch_edit_link" title="редактировать"><img width="18" height="18" class="pitches-name-td-img" src="/img/edit_icon_white.png"></a>
                                    <?php endif?>

                                <div style="background-image: none;padding: 15px 0 17px 40px">
                                    <?php if($mypitch->awarded != 0):
                                    $step = $mypitch->winner->step;

                                    if($step < 1) {
                                        $step = 1;
                                    }
                                    ?>
                                    <a href="/users/step<?=$step?>/<?=$mypitch->awarded?>"><?=$mypitch->title?></a>
                                    <?php elseif($mypitch->published == 0):?>
                                    <a class="" href="/pitches/edit/<?=$mypitch->id?>"><?=$mypitch->title?></a>
                                    <?php else:?>
                                    <a class="" href="/pitches/<?=$pitchPath?>/<?=$mypitch->id?>"><?=$mypitch->title?></a>

                                    <?php endif?>
                                    <span style="font-size: 11px;"><?=$mypitch->industry?></span></div></td>
                                <td class="pitches-cat"><a href="#" style="font-size: 11px;"><?=$mypitch->category->title?></a></td>
                                <td class="idea" style="font-size: 11px;"><?=$mypitch->ideas_count?></td>
                                <?php if(($mypitch->published == 1) && ($mypitch->status == 0)):
                                    $types['current'] += 1?>
                                <td><a style="color:#7e7e7e;font-size: 11px;" href="/pitches/<?=$pitchPath?>/<?=$mypitch->id?>">Текущий питч</a></td>
                                <?php endif;?>
                                <?php if(($mypitch->published == 0) && ($mypitch->billed == 0) && ($mypitch->status == 0)):
                                    $types['needpay'] += 1?>
                                <td><a style="color:#7e7e7e;font-size: 11px;" href="/pitches/edit/<?=$mypitch->id?>" >Ожидание оплаты</a></td>
                                <?php endif;?>
                                <?php if(($mypitch->published == 0) &&($mypitch->brief == 1) && ($mypitch->billed == 1) && ($mypitch->published == 0)):
                                    $types['needpay'] += 1?>
                                <td><a style="color:#7e7e7e;font-size: 11px;" href="/pitches/edit/<?=$mypitch->id?>">Ожидайте звонка</a></td>
                                <?php endif;?>
                                <?php if(($mypitch->status == 1) && ($mypitch->awarded != 0)):
                                    $types['finish'] += 1?>
                                <td class="pitches-finish"><a style="color:#FF575D;font-size: 11px;" href="/users/step<?=$step?>/<?=$mypitch->awarded?>" style="font-size: 11px;">Завершительный этап</a></td>
                                <?php endif?>
                                <?php if(($mypitch->status == 1) && ($mypitch->awarded == 0)):
                                    $types['winner'] += 1?>
                                <td class="pitches-time"><a style="color:#639F6D;font-size: 11px;" href="/pitches/<?=$pitchPath?>/<?=$mypitch->id?>">Выбор победителя</a></td>
                                <?php endif?>
                                <td class="price"><?=$this->moneyFormatter->formatMoney($mypitch->price)?></td></tr>
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
                <i id="delete" style="background: url(/img/header_delete.png) no-repeat scroll 0 2px transparent"></i>удалить
                <i id="edit" style="background: url(/img/header_edit.png) no-repeat scroll 0 2px transparent"></i>редактировать
                <i id="buy" style="background: url(/img/header_buy.png) no-repeat scroll 0 2px transparent"></i>оплатить
                <?php echo $helpLink?>
            </p>
        </div>
    </div>
</div>
<?php endif?>
<?php if(count($this->session->read('user.currentdesignpitches')) > 0):?>
<div id="pitch-panel">
    <div class="conteiner" style="margin-top: 0px;">
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
                    foreach($this->session->read('user.currentdesignpitches') as $mypitch):?>
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
                                <span><?=$mypitch->industry?></span>
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
<div id="header-bg">

    <header class="<?=$header?>">

	<p class="<?=$logo?>"><strong><a href="/">Go Designer</a></strong></p>
	<nav class="topnav">
        <?php if($this->Session->read('user')):?>
            <div class="avatar-top" style="width: 41px; float: left; height: 50px;">
                <?php if(($this->session->read('user.images.avatar_small.weburl') != '') || ($this->session->read('user.images.avatar_small.weburl') != false)):?>
                <img style="display:block; float:left;width:41px;"src="<?=$this->session->read('user.images.avatar_small.weburl')?>" alt="" />
                <?php else:?>
                <img style="display:block; float:left;width:41px;"src="/img/default_small_avatar.png" alt="" />
                <?php endif?>
            </div>
            <div class="topnav-menu" style="float:left;height:41px;padding-top:10px;">
            <?php if($this->session->read('user.events.count') > 0):?>
                <a href="/users/office" class="name-top" style="color:#fff;display:inline-block;">&nbsp;&nbsp;&nbsp;<?=$this->nameInflector->renderName($this->session->read('user.first_name'), $this->session->read('user.last_name'))?></a>
                <?=$this->html->link('(' . $this->session->read('user.events.count') . ')', 'Users::office', array('style' => 'color: #648FA4', 'class' => 'updatecurrent'))?><img class="name-top" id="menu_arrow" src="/img/arrow_down_header.png" alt="" style="padding-top:5px;"> /
            <?php else:?>
                <a href="/users/office" class="name-top" style="color:#fff;display:inline-block;">&nbsp;&nbsp;&nbsp;<?=$this->session->read('user.first_name') . ' ' . $this->session->read('user.last_name')?></a><img class="name-top" id="menu_arrow" src="/img/arrow_header_up.png" alt="" style="padding-top:3px;"> /
            <?php endif?>

        <?php else:?>
            <div class="topnav-menu" style="float:left;height:41px;padding-left:10px;padding-top:10px;">
                <?=$this->html->link('Зарегистрироваться', 'Users::registration')?> /
                <?=$this->html->link('Войти', 'Users::login', array())?> /
                <?php endif?>
                <a href="/pages/howitworks">Как это работает?</a> /
                <a href="/pitches">Все питчи</a> /
                <?php if($this->session->read('user.blogpost.count') > 0):?>
                <a href="/posts">Блог</a><?php echo $this->html->link('(' . $this->session->read('user.blogpost.count') . ')', 'Posts::index', array('style' => 'color: #648FA4', 'class' => 'updatecurrent', 'escape' => false))?>
                <?php else:?>
                <a href="/posts">Блог</a>
                <?php endif?>
            </div>
            <ul class="header-menu" style="display:none;position:relative;margin-top:39px;height:211px;width:180px;background-image:url(/img/menu.png);z-index: 120;">
                <li class="header-menu-item"><a style="display:block;width:165px;height:25px;text-decoration:none;" href="/users/office">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Обновления</a></li>
                <li class="header-menu-item"><a style="display:block;width:165px;height:25px;text-decoration:none;" href="/users/mypitches">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Мои питчи</a></li>
                <li class="header-menu-item"><a style="display:block;width:165px;height:25px;text-decoration:none;" href="/users/profile">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Профиль</a></li>
                <li class="header-menu-item"><a style="display:block;width:165px;height:25px;text-decoration:none;" href="/users/solutions">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Решения</a></li>
                <li class="header-menu-item"><a style="display:block;width:165px;height:25px;text-decoration:none;" href="/users/details">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Реквизиты</a></li>
                <li class="header-menu-item"><a style="display:block;width:165px;height:25px;text-decoration:none;" href="/users/logout">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Выйти</a></li>
            </ul>
	</nav><!-- .nav -->

	<div class="add-pitch">
        <div style="float: left; height: 47px; padding-top: 7px;">
            <span style="text-decoration: none; font-weight: bold; font-size: 12px; margin-left: 30px;" class="current">(812) 648 24 12</span>
            <br><a style="background: url(/img/smallmailicon.png) no-repeat 0 3px;padding-left:20px;font-size:11px;margin-right:10px;" href="#" id="requesthelplink">запросить помощь</a>
        </div>
        <?=$this->html->link('Cоздать питч', 'Pitches::create', array('class' => 'button third'))?>
    </div>

</header><!-- .header -->
</div>
<?php
    $clientNotice = $this->session->read('user.attentionpitch');
    $designerNotice = $this->session->read('user.attentionsolution');
    $timeoutNotice = $this->session->read('user.timeoutpitch');
    //if(((count($clientNotice) > 0) || (count($designerNotice) > 0) || (!is_null($timeoutNotice))) && ($this->session->read('user.showpanel'))):
    if((count($designerNotice) > 0)):
?>
<!--div class="panel" id="panel">
    <!--a class="close" href="#" id="closepanel"></a-->


    <?php if(count($designerNotice) > 0):?>
    <!--div class="msg winner regular">
        <p class="titlepanel regular">Поздравляем с победой!!!</p>
        <p>Ура, ваше решение стало победителем питча. У заказчика есть право на внесение<br>3 поправок до запроса исходных файлов.</p>
        <ul>
            <li><p>Перейти <a href="/users/nominated">к процессу вознаграждения</a></p></li>
            <li><p>Ознакомиться с заключительным этапом в разделе <a href="/answers/view/54">помощь</a></p></li>
        </ul>
    </div-->
    <?php endif?>
    <?php /*if(count($clientNotice) > 0):?>
    <div class="msg winner regular">
        <p class="titlepanel regular">Поздравляем с выбором варианта!!!</p>
        <p>У вас есть право на внесение 3 поправок в течение 10 дней до запроса исходных файлов. Если вы удовлетворены макетами, пожалуйста, нажмите кнопку «Одобрить макеты</p>
        <ul>
            <li><p>Перейти <a href="/users/nominated">к процессу вознаграждения</a></p></li>
            <li><p>Ознакомиться с заключительным этапом в разделе <a href="/answers/view/63">помощь</a></p></li>
        </ul>
    </div>
    <?php endif*/?>
    <?php /*if(!is_null($timeoutNotice)):?>
    <div class="msg regular" style="width:80%;padding-top:45px;margin-left:150px;">
        <div style="float:left;width:500px;">
        <p class="titlepanel regular">Время питча подошло к концу!</p>
        <p>Настал момент определиться с победителем.</p>
        <p>У вас есть 3 рабочих дня для выбора лучшего решения.</p>
        <ul>
            <li><p>Ознакомиться с заключительным этапом в разделе <a href="/answers/view/63">помощь</a></p></li>
            <li><p>Перейти к завершенному <a href="/pitches/view/<?=$timeoutNotice->id?>">питчу</a></p></li>

        </ul>
        </div>
        <div style="float:left;width:200px;">
            <img src="/img/panel-help.png" alt="подсказка"/>
        </div>
    </div>
    <?php endif*/?>
</div-->
    <?php endif?>