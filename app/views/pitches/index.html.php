<div class="wrapper pitchpanel" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     xmlns="http://www.w3.org/1999/html">

<?=$this->view()->render(array('element' => 'header'), array('header' => 'header2'))?>
	<div class="conteiner">
        <div class="pitches-ajax-wrapper">
            <div class="pitches-ajax-loader">&nbsp;</div>
        </div>
		<section>
            <div style="margin-top:75px;height: 75px; padding-top: 15px; background-color: rgb(243, 243, 243); width: 788px; margin-left: 77px;">
                <table><tr><td>
                <div id="filterContainer" style="border-radius:4px 4px 4px 4px;border:4px solid #F3F3F3; height:41px;padding-top:10px;background-color:white;box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) inset; width:618px;margin-left:25px">
                    <ul class="tags" id="filterbox" style="margin-left: 9px"></ul>
                    <input type="text" id="searchTerm" style="padding-bottom:10px; width:545px; box-shadow:none;line-height:12px; height:13px; padding-top: 7px;margin-left:4px;">
                    <a href="#" id="filterToggle" data-dir="up" style="float:right;"><img style="padding-top:4px;margin-right:1px;" src="/img/filter-arrow-down.png" alt=""></a>
                    <a href="#" id="filterClear"></a>
                </div></td><td>
                <a style="margin-left:15px;margin-top:4px" href="#" id="goSearch" class="button second">Поиск</a>
                </td></tr></table>
                <div id="filtertab" style="display:none;border-radius:10px;padding-top:14px;margin-left:25px;width: 617px;height:347px;background-color: white;z-index:10;position:absolute;">
                    <ul class="filterlist" style="float:left;width:105px;margin-left:25px;text-transform: none">
                        <li class="first">питчи</li>
                        <li style="width:85px"><a data-group="type" data-value="all" href="#">все</a></li>
                        <!--li><a href="#">по новизне</a></li-->
                        <li style="width:85px"><a data-group="type" data-value="current" href="#">текущие</a></li>
                        <li style="width:85px"><a data-group="type" data-value="finished" href="#">завершенные</a></li>
                    </ul>
                    <ul class="filterlist" style="float:left;width:151px;margin-left:25px;text-transform: none">
                        <li class="first">категория</li>
                        <li><a data-group="category" data-value="all" href="#">все</a></li>
                        <?php foreach($categories as $category):?>
                        <li><a data-group="category" data-value="<?=$category->id?>" href="#"><?=mb_strtolower($category->title, 'utf-8')?></a></li>
                        <?php endforeach?>
                    </ul>
                    <ul class="filterlist" style="float:left;width:85px;margin-left:25px;text-transform: none">
                        <li class="first">сроки</li>
                        <li><a data-group="timeframe" data-value="1"href="#">до 3 дней</a></li>
                        <li><a data-group="timeframe" data-value="2"href="#">до 7 дней</a></li>
                        <li><a data-group="timeframe" data-value="3"href="#">до 10 дней</a></li>
                        <li><a data-group="timeframe" data-value="4"href="#">более 14 дней</a></li>
                    </ul>
                    <ul class="filterlist" style="float:left;width:160px;margin-left:25px;text-transform: none">
                        <li class="first">гонорар</li>
                        <li style="width:130px"><a data-group="priceFilter" data-value="3" href="#">от 20 000 Р.-</a></li>
                        <li style="width:130px"><a data-group="priceFilter" data-value="2" href="#">от 10 000 - 20 000 Р.-</a></li>
                        <li style="width:130px"><a data-group="priceFilter" data-value="1" href="#">от 3 000 - 10 000 Р.-</a></li>
                    </ul>
                    <div style="clear:both"></div>
                </div>
            </div>
			<!--nav>
				<ul class="pitches-type">
					<li class="active-pitches"><a href="/pitches" class="status-switch" rel="current">Текущие</a></li>
					<li><a href="/pitches/finished" class="status-switch" rel="finished">Завершённые</a></li>
				</ul>
			</nav>
            <nav>
				<ul class="navigation-pitches">
					<li><a href="#" id="cat-menu-toggle"><span id="cat-menu-label" data-default="Категория">Категория</span></a>
						<ul id="cat-menu" class="list-collapsed" style="display:none;">
							<li><a href="#" class="category-filter" rel="all"><span>Всё</span></a></li>
							<?php foreach($categories as $category):?>
							<li><a href="#" class="category-filter" rel="<?= $category->id?>"><span><?=$category->title?></span></a></li>
							<?php endforeach;?>
						</ul>
					</li>
					<li><a href="#" id="price-menu-toggle"><span id="price-menu-label" data-default="Цена">Цена</span></a>
					<ul id="price-menu" class="list-collapsed" style="display:none;">
						<li><a href="#" class="price-filter" rel="all"><span>Всё</span></a></li>
						<li><a href="#" class="price-filter" rel="1"><span>1000-3000</span></a></li>
						<li><a href="#" class="price-filter" rel="2"><span>3001-6000</span></a></li>
						<li><a href="#" class="price-filter" rel="3"><span>Более 6000</span></a></li>
						</ul>
					</li>
					<li><a href="#" id="timelimit-menu-toggle" data-default="Срок"><span id="timelimit-menu-label" data-default="Срок">Срок</span></a>
                        <ul id="timelimit-menu" class="list-collapsed" style="display:none;">
                            <li><a href="#" class="timelimit-filter" rel="all"><span>Всё</span></a></li>
                            <li><a href="#" class="timelimit-filter" rel="1"><span>Меньше дня</span></a></li>
                            <li><a href="#" class="timelimit-filter" rel="2"><span>Меньше 4-ех дней</span></a></li>
                            <li><a href="#" class="timelimit-filter" rel="3"><span>Более 4-ех дней</span></a></li>
                            <li><a href="#" id="sort-started" rel="desc"><span>По новизне</span></a></li>
                        </ul>
					</li>
				</ul>
			</nav-->
			<table id="primary" class="all-pitches">
				<thead>
					<tr>
						<td class="icons"></td>
						<td class="" style="text-align: left; padding:0 10px 0 40px"><a href="#" id="sort-title" class="sort-link" rel="asc">название питча</a></td>
						<td class="pitches-cat"><a href="#" id="sort-category" class="sort-link" rel="asc">Категории</a></td>
						<td class="idea"><a href="#" id="sort-ideas_count" class="sort-link" rel="desc">Идеи</a></td>
						<td class="pitches-time"><a href="#" id="sort-finishDate" class="sort-link" rel="asc">Срок</a></td>
						<td style="text-align: left; padding:0 10px 0 40px"><a href="#" id="sort-price" class="sort-link" rel="desc">Цена</a></td>
					</tr>
				</thead>
				<tbody id="table-content">
				<?php
                $i = 1;
                foreach($data['pitches'] as $pitch):
                    $rowClass = 'odd';
                    if(($i % 2 == 0)) {
                        $rowClass = 'even';
                    }

                    if((strtotime($pitch['started']) + DAY) > time())  {
                        $rowClass .= ' newpitch';
                    }else {
                        if($pitch['pinned'] == 1) {
                            $rowClass .= ' highlighted';
                        }
                    }

                    $icons = '';
                    if($pitch['guaranteed'] == 1) {
                        $icons = '<img style="width:30px; margin-top:4px;float:left" src="/img/guarantee.png" alt="Награда гарантирована">';
                    }
                    $timeleft = '';
                    if($pitch['status'] == 0) {

                        if(($pitch['private'] == 1) && ($pitch['expert'] == 0)){
                                    $rowClass .= ' close';
                                    $icons .= '<img style="margin-right: 5px;margin-top: 1px" src="/img/icon-4.png" title="Закрытый питч." alt="Закрытый питч.">';
                                }
                        if(($pitch['private'] == 0) && ($pitch['expert'] == 1)){
                                    $rowClass .= ' expert';
                                    $icons .= '<img style="margin-right: 5px;margin-top: 1px" src="/img/icon-5.png" title="Важно мнение эксперта." alt="Важно мнение эксперта.">';

                                }
                        if(($pitch['private'] == 1) && ($pitch['expert'] == 1)){
                                    $rowClass .= ' close-and-expert';
                                    $icons .= '<img style="margin-right: 5px;margin-top: 1px" src="/img/icon-3.png" title="Закрытый питч. Важно мнение эксперта." alt="Закрытый питч. Важно мнение эксперта.">';
                                }
                        if (($pitch['published'] == 0) && ($pitch['billed'] == 0) && ($pitch['moderated'] != 1)) {
                            $timeleft = 'Ожидание оплаты';
                        } else if (($pitch['published'] == 0) && ($pitch['billed'] == 0) && ($pitch['moderated'] == 1)) {
                            $timeleft = 'Ожидание<br />модерации';
                        } else if (($pitch['published'] == 0) && ($pitch['billed'] == 1) && ($pitch['brief'] == 1)) {
                            $timeleft = 'Ожидайте звонка';
                        } else {
                            $timeleft = $pitch['startedHuman'];
                        }
                    } else if (($pitch['status'] == 1) && ($pitch['awarded'] == 0)) {
                        $rowClass .= ' selection';
                        $icons .= '<img style="margin-right: 5px;margin-top: 1px" src="/img/icon-1.png" title="Идёт выбор победителя." alt="Идёт выбор победителя.">';
                        $timeleft = 'Выбор победителя';
                    } else if (($pitch['status'] == 2) || (($pitch['status'] == 1) && ($pitch['awarded'] > 0))) {
                        $rowClass .= ' pitch-end';
                        $icons .= '<img style="margin-right: 5px;margin-top: 1px" src="/img/icon-2.png" title="Питч завершён, победитель выбран" alt="Закрытый питч. Важно мнение эксперта.">';
                        if ($pitch['status'] == 2) {
                            $timeleft = 'Питч завершен';
                        }else if(($pitch['status'] == 1) && ($pitch['awarded'] > 0)) {
                            $timeleft = 'Победитель выбран';
                        }else if(($pitch['status'] == 1) && ($pitch['awarded'] == 0)) {
                            $timeleft = 'Выбор победителя';
                        }else {
                            $timeleft = $pitch['startedHuman'];
                        }
                    }

                    $imgForDraft = '';
                    if ($pitch['published'] == 1) {
                        $imgForDraft = ' not-draft';
                    }
                    if($pitch['user_id'] == $this->session->read('user.id')){
                        if($pitch['billed'] == 1) {
                            $userString = '<a title="Редактировать" href="/pitches/edit/' . $pitch['id'] . '" class="mypitch_edit_link' . $imgForDraft . '"><img class="pitches-name-td-img" src="/img/1.gif"></a>';
                        }else {
                            $userString = '<a href="/pitches/edit/' . $pitch['id'] . '" class="mypitch_edit_link" title="Редактировать"><img src="/img/1.gif" class="pitches-name-td-img"></a><a href="/pitches/delete/'  . $pitch['id'] .  '" rel="' . $pitch['id']  .'" class="mypitch_delete_link" title="Удалить"><img src="/img/1.gif" class="pitches-name-td-img"></a><a href="/pitches/edit/' . $pitch['id'] . '#step3" class="mypitch_pay_link" title="Оплатить"><img src="/img/1.gif" class="pitches-name-td2-img"></a>';
                        }
                    }else {
                        $userString = '<a href="#"><img class="pitches-name-td-img expand-link" src="/img/arrow.png" /></a>';
                    }

                    if($pitch['private'] == 1) {
                            $pitch['editedDescription'] = 'Это закрытый питч и вам нужно подписать соглашение о неразглашении.';
                        }
                    $shortIndustry = $pitch['industry'];
                    $shortIndustry = (iconv('UTF-8', 'Windows-1251', $shortIndustry));
                    if(strlen($shortIndustry) > 80) {
                        $shortIndustry = substr($shortIndustry, 0, 75) . '...';

                    }
                    $shortIndustry = iconv('Windows-1251', 'UTF-8', $shortIndustry);
                    $textGuarantee = '';
                    if($pitch['guaranteed'] == 1) {
                        $textGuarantee = '<br><span style="font-size: 11px; font-weight: normal; font-family: Arial;text-transform:uppercase">гарантированы</span>';
                    }
                    $pitchPath = 'view';
                    if($pitch['ideas_count'] == 0) {
                        $pitchPath = 'details';
                    }
                    $multiple = (is_null($pitch['multiple'])) ? '' : '<br>' . $pitch['multiple'];
                    $html = '<tr data-id="' . $pitch['id'] . '" class="' . $rowClass . '">' .
                        '<td class="icons">' . $icons . '</td>' .
                        '<td class="pitches-name">' .
                        $userString .
                        '<div style="padding-left: 34px; padding-right: 12px;">' .
                        '<a href="/pitches/' . $pitchPath . '/' . $pitch['id'] . '" class="newpitchfont" >' . $this->PitchTitleFormatter->renderTitle($pitch['title']) . '</a>' .
                        '<!--span style="font-size:11px;">' . $shortIndustry . '</span-->' .
                        '</div>' .
                        '</td>' .
                        '<td class="pitches-cat" style="padding-left: 10px; width: 102px; padding-right: 10px;">' .
                        '<a href="#" style="font-size:11px;">' . $pitch['category']['title'] . $multiple . '</a>' .
                        '</td>' .
                        '<td class="idea"  style="font-size:11px;">' . $pitch['ideas_count'] . '</td>' .
                        '<td class="pitches-time"  style="font-size:11px;">' . $timeleft . '</td>' .
                        '<td class="price">' . $this->moneyFormatter->formatMoney($pitch['price'], array('suffix' => ' Р.-')) .
                        $textGuarantee
                        .'</td>' .
                        '</tr>' .
                        '<tr class="pitch-collapsed">' .
                        '<td class="icons"></td>' .
                        '<td colspan="3" class="al-info-pitch"><p>' . $pitch['editedDescription'] .
                        '</p><a href="/pitches/' . $pitchPath . '/' . $pitch['id'] . '" class="go-pitch">Перейти к питчу</a>' .
                        '</td>' .
                        '<td></td>' .
                        '<td></td>' .
                        '</tr>';
                    echo $html;
                ?>

                <?php
                $i++;
                endforeach;?>
				</tbody>
			</table>
			<div class="foot-content">
                <div class="pitches-ajax-wrapper no-separator">
                    <div class="pitches-ajax-loader">&nbsp;</div>
                </div>
				<div class="page-nambe-nav">
                    <?php
                    if($data['info']['total'] > 1):
                    $navBar = $prepend = $append = '';
                    if($data['info']['total'] > 1) {
                        $prepend = '<a href="#" class="nav-page" rel="prev"><</a>';
                        $append = '<a href="#" class="nav-page" rel="next">></a>';
                    }

                    if($data['info']['total'] <= 5) {
                        for($i = 1; $i <= $data['info']['total']; $i++) {
                            if($data['info']['page'] == $i) {
                                $navBar .= '<a href="#" class="this-page nav-page" rel="' . $i . '">' . $i . '</a>';
                            }else {
                                $navBar .= '<a href="#" class="nav-page" rel="' . $i . '">' . $i . '</a>';
                            }
                        }
                    }else {
                        if(($data['info']['page'] - 3) <= 0) {
                            for($i = 1; $i <= 4; $i++) {
                                if($data['info']['page'] == $i) {
                                    $navBar .= '<a href="#" class="this-page nav-page" rel="' . $i . '">' . $i . '</a>';
                                }else {
                                    $navBar .= '<a href="#" class="nav-page" rel="' . $i . '">' . $i . '</a>';
                                }
                            }
                            $navBar .= ' ... ';
                            $navBar .= '<a href="#" class="nav-page" rel="' . $data['info']['total'] . '">' . $data['info']['total'] . '</a>';
                        }

                        if((($data['info']['page'] - 3) > 0) && ($data['info']['total'] > ($data['info']['page'] + 2))) {
                            $navBar .= '<a href="#" class="nav-page" rel="1">1</a>';
                            $navBar .= ' ... ';
                            for($i = $data['info']['page'] - 1 ; i <= $data['info']['page'] + 1; $i++) {
                                if($data['info']['page'] == $i) {
                                    $navBar .= '<a href="#" class="this-page nav-page" rel="' . $i . '">' . $i . '</a>';
                                }else {
                                    $navBar .= '<a href="#" class="nav-page" rel="' . i . '">' . $i . '</a>';
                                }
                            }
                            $navBar .= ' ... ';
                            $navBar .= '<a href="#" class="nav-page" rel="' . $data['info']['total'] . '">' . $data['info']['total'] . '</a>';
                        }

                        if($data['info']['total'] <= ($data['info']['page'] + 2)) {
                            $navBar .= '<a href="#" class="nav-page" rel="1">1</a>';
                            $navBar .= ' ... ';
                            for($i = $data['info']['total'] - 3; $i <= $data['info']['total']; $i++) {
                                if($data['info']['page'] == $i) {
                                    $navBar .= '<a href="#" class="this-page nav-page" rel="' . $i . '">' . $i . '</a>';
                                }else {
                                    $navBar .= '<a href="#" class="nav-page" rel="' . $i . '">' . $i . '</a>';
                                }
                            }
                        }
                    }
                    $navBar = $prepend . $navBar . $append;
                    echo $navBar;
                    endif ?>
					<!--a href="#">&#60;</a><a href="#" class="this-page">1</a><a href="#">2</a><a href="#">3</a><a href="#">4</a><a href="#">5</a><a href="#">6</a> ... <a href="#">7</a><a href="#">&#62;</a-->
				</div>
				<ul class="icons-infomation">
					<li class="icons-infomation-one supplement3"><a href="/answers/view/66" target="_blank">Мнение экспертов</a><br> важно для этого клиента</li>
					<li class="icons-infomation-two supplement3"><a href="/answers/view/64" target="_blank">Закрытый питч</a></li>
					<li class="icons-infomation-three supplement3">Идеи больше не принимаются, идет выбор победителя</li>
					<li class="icons-infomation-four supplement3">Питч завершен,<br> победитель выбран</li>
				</ul>
				<div style="margin-top:70px;height:40px;background: url('/img/guarantee2.png') no-repeat scroll 0 0 transparent" class="you-profile supplement3">
                    <a href="/answers/view/80" target="_blank">Гарантированный питч</a>,<br>1 участник будет награждён, что бы ни случилось <a style="color:#6891a2" href="/answers/view/80">(?)</a>
				</div>
                <div style="margin-top:20px;height:40px;margin-right: 128px;" class="you-profile supplement3">
                    Хотите узнать о добавлении новых питчей?<br>Измените <a href="/users/profile">настройки своего профиля</a>
                </div>
			</div>
			<div class="no-result">
                <h1>Упс, мы ничего не нашли!</h2>
                <p class="regular">Попробуйте ввести другое слово, или используйте<br /> стрелку в поле, повторив поиск с выбранным<br /> фильтром. <a href="/answers/view/85">Подробнее…</a></p>
                <p><img src="http://www.godesigner.ru/img/help/d3fa990a965b8ebf1cf8691586140165.jpg" alt="" width="610" height="292"></p>

			</div>
			<div class="clr"></div>
		</section>
	</div>
	<div class="conteiner-bottom">
	<input type="hidden" value="<?=$selectedCategory?>" name="category">
    <input type="hidden" value="<?=$this->session->read('user.id')?>" id="user_id">
    </div>
	</div>
</div><!-- .wrapper -->

<div id="popup-final-step" class="popup-final-step" style="display:none">
    <h3>Вы уверены, что хотите удалить этот питч?</h3>
    <p>Эта процедура является окончательной, и в дальнейшем вы не сможете изменить свое решение. Нажав "Да, одобряю", вы подтверждаете, что хотите удалить его из списка. Убедитесь, что это черновой вариант и не ждет поступления оплаты на наш счет. За справкой <a href="/pages/contacts">обратитесь к нам</a>.</p>
    <div class="final-step-nav wrapper" style="margin-top: 180px;"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="confirmDelete" value="Да, одобряю"></div>
</div>
<?=$this->html->script(array('jquery-deparam.js', 'tableloader.js', 'jquery.simplemodal-1.4.2.js', 'jquery.keyboard.js', 'pitches/index.js'), array('inline' => false))?>