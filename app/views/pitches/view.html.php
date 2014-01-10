<div class="wrapper pitchpanel login">

	<?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>
    <script type="text/javascript">
        var extraimages = {};
    </script>
	<div class="middle">
        <?php //$this->view()->render(array('element' => 'pitchinfo'), array('pitch' => $pitch))?>
	<div class="middle_inner_gallery" style="padding-top:25px">
    <?php if((int)$this->session->read('user.id') == $pitch->user_id):?>
    <div id="dinamic" style="display:none;position: fixed; z-index: 15; bottom: 0; opacity:0.8; margin-left: 740px">
        <div class="bubble">
            <span>Возврат денег недоступен:</span><br>
            <span class="lowReason"></span><br><br>
            <a href="/answers/view/71">Как это исправить?</a>
        <div id="bubble-close"></div>
        </div>
        <div style="width:150px;height:190px;text-align;center">
            <div style="background-image:url(/img/big-krug.png);margin-top:4px;height:132px;width:132px;">
                <canvas id="canFloat" height="132" width="132" style="">
                </canvas></div>
            <div style="background: url('/img/krug-small.png') no-repeat scroll 32px 30px transparent;height: 82px; width: 87px; position: relative; top: -132px; bottom: 0px; z-index: 15; padding-top: 50px; padding-left: 45px;">
                <h2 id="avgPointsFloat" style="font-size:28px;color:#666666;text-shadow: -1px 0 0 #FFFFFF;width:40px;text-align:center"></h2>
                <h2 id="avgPointsStringFloat" style="color: rgb(102, 102, 102); text-align: center; text-shadow: -1px 0px 0px rgb(255, 255, 255); margin-left: 0px; margin-top: 4px; font-size: 9px; width: 44px;">БАЛЛА</h2>
            </div>
        </div>
    </div>
        <?php endif?>


    <input type="hidden" value="<?=$pitch->id?>" name="pitch_id">
                <div style="margin-left:280px;width: 560px; height:70px;margin-bottom:40px;">
                    <?php if($pitch->user_id != $this->session->read('user.id') || $pitch->status > 0): ?>
                    <table class="pitch-info-table" border="1">
                        <tr><td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1"><span class="regular">Гонорар:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$this->moneyFormatter->formatMoney($pitch->price, array('suffix' => 'р.-'))?><?php echo ($pitch->guaranteed == 1) ? ' гарантированы' : ''; ?></span></td>
                            <td width="15"></td>
                            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Заказчик:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$this->html->link($this->nameInflector->renderName($pitch->user->first_name, $pitch->user->last_name), array('users::view', 'id' => $pitch->user->id), array('class' => 'client-linknew'))?></td></tr>
                        <tr><td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Решений:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$pitch->ideas_count ?></span></td>
                            <td width="15"></td>
                            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Был online:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=date('d.m.Y', strtotime($pitch->user->lastActionTime))?> в <?=date('H:i', strtotime($pitch->user->lastActionTime)) ?></span></td></tr>
                        <tr>
                            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Просмотры брифа:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$pitch->views?></span></td>
                            <td width="15"></td>
                            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                                <span class="regular">Срок:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($pitch->status == 0):?>
                                <span class="pitch-info-text"><?=preg_replace('@(м).*@', '$1. ', preg_replace('@(ч).*@', '$1. ', preg_replace('@(.*)(дн).*?\s@', '$1$2. ', $pitch->startedHuman)))?></span>
                                <?php elseif($pitch->status == 1):?>
                                <span class="pitch-info-text">Выбор победителя</span>
                                <?php elseif($pitch->status == 2):?>
                                <span class="pitch-info-text">Питч завершен</span>
                                <?php endif?>
                            </td>
                        </tr>
                    </table>
                    <?php else: ?>
                    <table class="pitch-info-table" border="1">
                        <tr><td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1"><span class="regular">Гонорар:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$this->moneyFormatter->formatMoney($pitch->price, array('suffix' => 'р.-'))?></span></td>
                            <td width="15"></td>
                            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                                <table width="100%"><tr><td>
                                    <td><span class="regular">Мнение эксперта <a href="http://www.godesigner.ru/answers/view/66" target="_blank">(?)</a></span></td>
                                    <td style="width:86px"><a style="position: relative; top: -2px; left: -1;" class="order1" href="/pitches/addon/<?= $pitch->id?>?click=experts-checkbox"><img src="/img/order1.png" alt="заказать"></a></td>
                                </td></tr></table>
                            </td></tr>
                        <tr><td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Решений:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$pitch->ideas_count ?></span></td>
                            <td width="15"></td>
                            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                                <table width="100%"><tr><td>

                                <span class="regular">Заполнить бриф <a href="http://www.godesigner.ru/answers/view/68" target="_blank">(?)</a></span>
                                </td><td style="width:86px">

                                <a style="position: relative; top: -2px;" class="order1" href="/pitches/addon/<?= $pitch->id?>?click=phonebrief"><img src="/img/order1.png" alt="заказать"></a>
                                </td></tr></table>
                        <tr>    </td></tr>
                            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Просмотры брифа:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$pitch->views?></span></td>
                            <td width="15"></td>
                            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                                <table width="100%"><tr><td>

                                <span class="regular">Срок:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($pitch->status == 0):?>
                                <span class="pitch-info-text"><?=preg_replace('@(м).*@', '$1. ', preg_replace('@(ч).*@', '$1. ', preg_replace('@(.*)(дн).*?\s@', '$1$2. ', $pitch->startedHuman)))?></span>

                                </td><td style="width:86px">
                                <a style="position: relative; top: -2px;  left: 1;" href="/pitches/addon/<?= $pitch->id?>?click=prolong" class="order2"><img src="/img/order2.png" alt="продлить"></a>
                                <?php elseif($pitch->status == 1):?>
                                <span class="pitch-info-text">Выбор победителя</span>
                                <?php elseif($pitch->status == 2):?>
                                <span class="pitch-info-text">Питч завершен</span>
                                <?php endif?>


                                </td></tr></table>
                            </td>
                        </tr>
                    </table>
                    <?php endif ?>
                </div>

                <div id="pitch-title" style="height:36px;margin-bottom:5px;">
                    <div class="breadcrumbs-view" style="<?php if($pitch->status == 0): echo 'width: 770px;'; else: echo 'width: 640px;'; endif?>float:left;">
                        <a href="/pitches">Все питчи /</a> <a href="/pitches/view/<?=$pitch->id?>"><?=$pitch->title?></a>

                    </div>
                    <?php if($pitch->status == 0):?>
                    <?php if(!in_array($pitch->id, $this->session->read('user.faves'))):?>
                    <div style="width:36px;height:36px;float:right;margin-right: 20px;">
                        <a data-pitchid="<?=$pitch->id?>" id="fav" data-type="add" href="#" title="Добавить в избранное"><img class="fav-plus" alt="добавить в избранное" src="/img/plus 2.png"></a>
                    </div>
                    <?php else:?>
                    <div style="width:36px;height:36px;float:right;margin-right: 20px;">
                        <a data-pitchid="<?=$pitch->id?>" id="fav" data-type="remove" href="#" title="Удалить из избранного"><img class="fav-minus" alt="Удалить из избранного" src="/img/minus.png"></a>
                    </div>
                    <?php endif?><?php endif?>
                </div>

                <div class="menu">
                    <ul>
                        <li class="first_li">
                            <?=$this->html->link('Решения', array('controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id), array('class' => 'selected menu-toggle', 'data-page' => 'gallery'))?>
                        </li>
                        <li>
                            <?=$this->html->link('Бриф', array('controller' => 'pitches', 'action' => 'details', 'id' => $pitch->id), array('class' => 'menu-toggle', 'data-page' => 'brief'))?>
                        </li>
                    </ul>

                </div>


        <nav class="other_nav_gallery clear">
            <p class="supplement4" style="margin-left:200px;float:left;height:30px;padding-top:4px;font-weight: bold; color:#b2afaf;">
                <span style="display: inline-block; margin-top: 4px; vertical-align: top;">СОРТИРОВАТЬ ПО:</span>
                <a class="sort-by-rating<?php if ($sort == 'rating'):?> active<?php endif;?>" href="/pitches/view/<?=$pitch->id?>?sorting=rating"><span title="сортировать по рейтингу"></span></a>
                <a class="sort-by-likes<?php if ($sort == 'likes'):?> active<?php endif;?>" href="/pitches/view/<?=$pitch->id?>?sorting=likes"><span title="сортировать по лайкам"></span></a>
                <a class="sort-by-created<?php if ($sort == 'created'):?> active<?php endif;?>" href="/pitches/view/<?=$pitch->id?>?sorting=created"><span title="сортировать по дате создания"></span></a>
            </p>
            <?php
            if(($this->session->read('user.id') != $pitch->user_id) && ($pitch->status < 1) && ($pitch->published == 1)):?>
                <?php //echo $this->html->link('<img src="/img/1.gif" width="184" height="34" alt="" /><br /><span>предложить решение</span>', array('controller' => 'pitches', 'action' => 'upload', 'id' => $pitch->id), array('class' => 'other-nav-right active', 'escape' => false))?>
                <a href="/pitches/upload/<?=$pitch->id?>" class="button" style="font-family:Arial,sans-serif;color:#ffffff;display:block;float:right;margin-right:20px;width:155px">предложить решение</a>
                <?php elseif(($pitch->status == 1) && ($pitch->awarded == 0)):?>
                <img src="/img/status1.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Идет выбор победителя"/>
                <?php elseif(($pitch->status == 1) && ($pitch->awarded != 0)):?>
                <img src="/img/winner-selected.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Победитель выбран"/>
                <?php elseif($pitch->status == 2):?>
                <img src="/img/status2.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Питч завершен"/>
            <?php else:?>
                <a href="http://www.godesigner.ru/answers/view/78" class="button" style="font-family:Arial,sans-serif;color:#ffffff;display:block;float:right;margin-right:20px;width:178px">инструменты заказчика</a>
            <?php endif;?>
        </nav>

    <div class="portfolio_gallery" style="padding-top:32px;">
    <div class="pht"></div>
            <?php
            $expertsIds = array();
            foreach($experts as $expert) :
                $expertsIds[] = $expert->user_id;
            endforeach;
            $mySolutionList = array();
            $mySolutionNumList = array();
            if((count($solutions) > 0) && ($pitch->published == 1)): ?>
            <ul class="list_portfolio">
                <?php
                $i = 1;

                foreach($solutions as $solution):
                    if($this->session->read('user.id') == $solution->user_id) {
                        $mySolutionList[] = $solution->id;
                        $mySolutionNumList[] = '#' . $solution->num;
                    }

                    $picCounter2 = 0;
                    if(isset($solution->images['solution_galleryLargeSize'][0])):
                        foreach($solution->images['solution_galleryLargeSize'] as $image):
                            $picCounter2++;
                        endforeach;
                    else:
                        if(!isset($solution->images['solution_galleryLargeSize'])):
                            $solution->images['solution_galleryLargeSize'] = $solution->images['solution'];
                            $picCounter2 = 0;
                            foreach($solution->images['solution_galleryLargeSize'] as $image):
                                $picCounter2++;
                            endforeach;
                        endif;
                    endif;
                ?>
                <li <?php if(($picCounter2 > 1) && ($pitch->category_id != 7)): echo 'class="multiclass"'; endif;?>>
                    <!-- multisolution branch -->
                    <div class="photo_block" <?php if(($picCounter2 > 1) && (($solution->hidden) && ($pitch->user_id == $this->session->read('user.id')))):?>style="background: url(/img/copy-inv.png) 10px 10px no-repeat white"<?php endif;?>>
                        <?php
                        $visible = false;

                        if($pitch->private != 1):
                            if($pitch->category_id == 7):
                                if(($this->session->read('user.id') != null) && (($pitch->user_id == $this->session->read('user.id')) || (in_array($this->session->read('user.id'), $expertsIds)) || (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))) || ($solution->user_id == $this->session->read('user.id')))):
                                    $visible = true;?>
                                    <a class="imagecontainer" href="/pitches/viewsolution/<?=$solution->id?>?sorting=<?=$sort?>" style="width:147px;height:104px;background-color:#efefef;display:block;color:#666666;text-decoration:none;font-weight:bold;padding-top:16px;padding: 16px;">
                                        <?php if(mb_strlen(trim($solution->description)) > 100):?>
                                        <?=mb_substr(trim($solution->description), 0, 100, 'UTF-8')?>
                                        <?php else:?>
                                        <?=trim($solution->description)?>
                                        <?php endif?>
                                    </a>
                                <?php else:?>
                                    <a href="/pitches/viewsolution/<?=$solution->id?>?sorting=<?=$sort?>" style="background-image: url(/img/copy-inv.png);width:179px;height:136px;background-color:#efefef;display:block;"></a>
                                <?php endif?>
                            <?php else:
                                if($this->solution->getImageCount($solution->images['solution_galleryLargeSize']) > 1):
                                    $visible = true;?>
                                <div style="z-index: 2; position: absolute; color: rgb(102, 102, 102); font-weight: bold; font-size: 14px; padding-top: 7px; height: 16px; top: -34px; text-align: right; width: 18px; padding-right: 21px; background: url(/img/multi-icon.png) no-repeat scroll 22px 5px transparent; left: 169px;"><?=$picCounter2?></div>
                                <?php endif?>
                            <?php if(($solution->hidden == 1) && ($pitch->user_id == $this->session->read('user.id'))):?><div class="hidedummy" style="background-image: url(/img/copy-inv.png)"><?php endif ?>
                                <a style="<?php if(($solution->hidden) && ($pitch->user_id == $this->session->read('user.id'))):?>opacity:0.1;<?php endif?>display:block;" data-solutionid="<?=$solution->id?>" class="imagecontainer" href="/pitches/viewsolution/<?=$solution->id?>?sorting=<?=$sort?>">
                                    <?php if(!isset($solution->images['solution_galleryLargeSize'][0])):?>
                                        <!-- 3 <?php var_dump($extra) ?> -->
                                    <?php
                                    if(!isset($solution->images['solution_galleryLargeSize'])):
                                        $solution->images['solution_galleryLargeSize'] = $solution->images['solution'];
                                        $picCounter = 0;
                                        $extra = array();
                                        foreach($solution->images['solution_galleryLargeSize'] as $image):
                                            if($picCounter == 0):?>
                            <img class="multi" width="180" height="135" style="position: absolute;left:10px;top:9px;z-index:1;<?php if($picCounter > 0): echo 'display:none;'; else: echo 'opacity:1;'; endif;?>" rel="#<?=$solution->num?>" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'], $picCounter)?>" alt="<?=($pitch->status == 2) ? $this->solution->getShortDescription($solution, 80) : '';?>">
                            <?php
                                            else:
                                                $extra[] = $this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'], $picCounter);
                                            endif;

                                        $picCounter++;
                                        endforeach;
                                    endif;
                                     ?>
                                        <img rel="#<?=$solution->num?>"  width="180" height="135" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>" alt="<?=($pitch->status == 2) ? $this->solution->getShortDescription($solution, 80) : '';?>">
                                    <?php else:?>

                                        <?php
                                        $picCounter = 0;
                                        $extra = array();
                                        foreach($solution->images['solution_galleryLargeSize'] as $image):
                                            if($picCounter == 0):?>
                                            <img class="multi" width="180" height="135" style="position: absolute;left:10px;top:9px;z-index:1;<?php if($picCounter > 0): echo 'display:none;'; else: echo 'opacity:1;'; endif;?>" rel="#<?=$solution->num?>" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'], $picCounter)?>" alt="<?=($pitch->status == 2) ? $this->solution->getShortDescription($solution, 80) : '';?>">
                                        <?php
                                            else:
                                                $extra[] = $this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'], $picCounter);
                                            endif;

                                        $picCounter++;
                                        endforeach;?>
                                    <?php endif?>

                                </a><?php if(($solution->hidden) && ($pitch->user_id == $this->session->read('user.id'))):?></div><?php endif?>

                                <?php if(isset($solution->images['solution_galleryLargeSize'][0])):?>
                                <script type="text/javascript">
                                    extraimages[<?= $solution->id?>] = <?php echo json_encode($extra)?>;</script>
                                <?php endif?>
                            <?php endif?>
                        <?php else:?>
                            <!-- solo branch -->
                            <?php
                            if($pitch->category_id == 7):
                                if(($pitch->user_id == $this->session->read('user.id')) || (in_array($this->session->read('user.id'), $expertsIds)) || (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))) || ($solution->user_id == $this->session->read('user.id'))):
                                    $visible = true;?>
                                <a href="/pitches/viewsolution/<?=$solution->id?>?sorting=<?=$sort?>" style="width:147px;height:104px;background-color:#efefef;display:block;color:#666666;text-decoration:none;font-weight:bold;padding-top:16px;padding: 16px;">
                                    <?php if(mb_strlen(trim($solution->description)) > 100):?>
                                    <?=mb_substr(trim($solution->description), 0, 100, 'UTF-8')?>
                                    <?php else:?>
                                    <?=trim($solution->description)?>
                                    <?php endif?>
                                </a>
                                <?php else:?>
                                <a href="/pitches/viewsolution/<?=$solution->id?>?sorting=<?=$sort?>" style="background-image: url(/img/copy-inv.png);width:179px;height:136px;background-color:#efefef;display:block;"></a>
                                <?php endif?>
                            <?php else:
                                    if(($pitch->user_id == $this->session->read('user.id')) || (in_array($this->session->read('user.id'), $expertsIds)) || (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))) || ($solution->user_id == $this->session->read('user.id')) || ($canViewPrivate)):
                                        $visible = true;
                                        if($this->solution->getImageCount($solution->images['solution_galleryLargeSize']) > 1):?>
                                        <div style="z-index: 2; position: absolute; color: rgb(102, 102, 102); font-weight: bold; font-size: 14px; padding-top: 7px; height: 16px; top: -34px; text-align: right; width: 18px; padding-right: 21px; background: url(/img/multi-icon.png) no-repeat scroll 22px 5px transparent; left: 169px;"><?=$this->solution->getImageCount($solution->images['solution_solutionView'])?></div>
                                        <?php endif?>
                                        <?php


                                        if(($solution->hidden == 1) && ($pitch->user_id == $this->session->read('user.id'))):?><div class="hidedummy" style="background-image: url(/img/copy-inv.png)"><?php endif ?>
                                    <a style="<?php if(($solution->hidden) && ($pitch->user_id == $this->session->read('user.id'))):?>opacity:0.1;<?php endif?>display:block;" data-solutionid="<?=$solution->id?>" class="imagecontainer" href="/pitches/viewsolution/<?=$solution->id?>?sorting=<?=$sort?>">


                                        <?php if(!isset($solution->images['solution_galleryLargeSize'][0])):?>
                                        <img rel="#<?=$solution->num?>"  width="180" height="135" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>" alt="<?=($pitch->status == 2) ? $this->solution->getShortDescription($solution, 80) : '';?>">
                                        <?php else:?>
                                        <?php
                                        $picCounter = 0;
                                        $extra = array();
                                        foreach($solution->images['solution_galleryLargeSize'] as $image):
                                            if($picCounter == 0):
                                            ?>
                                            <img class="multi"  width="180" height="135" style="position: absolute;left:10px;top:9px;z-index:1;<?php if($picCounter > 0): echo 'display:none;'; else: echo 'opacity:1;'; endif;?>" rel="#<?=$solution->num?>" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'], $picCounter)?>" alt="<?=($pitch->status == 2) ? $this->solution->getShortDescription($solution, 80) : '';?>">
                                            <?php
                                            else:
                                                $extra[] = $this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'], $picCounter);
                                            endif;
                                            $picCounter++;
                                        endforeach;?>

                                        <?php endif?>


                                    </a><?php if(($solution->hidden) && ($pitch->user_id == $this->session->read('user.id'))):?></div><?php endif?>
                                        <?php if(isset($solution->images['solution_galleryLargeSize'][0])):?>
                                        <script type="text/javascript">
                                            extraimages[<?= $solution->id?>] = <?php echo json_encode($extra)?>;</script>
                                        <?php endif?>
                                    <?php else:?>
                                    <a href="/pitches/viewsolution/<?=$solution->id?>?sorting=<?=$sort?>" style="background-image: url(/img/copy-inv.png);width:179px;height:136px;background-color:#efefef;display:block;"></a>
                                    <?php endif?>
                                <?php endif?>
                        <?php endif?>



                        <?php if(($solution->nominated == 1) || ($solution->awarded == 1)):?>
                        <span class="medal"></span>
                        <?php endif?>
                        <div class="photo_opt" <?php if(($visible == true) && ($pitch->category_id != 7) && (isset($solution->images['solution_galleryLargeSize'][0]))): echo "style=\"padding-top:0; margin-top:144px;\""; endif?> >
                            <div class="" style="display: block; float:left;">
                                <span class="rating_block">
                                    <div class="ratingcont" data-default="<?=$solution->rating?>" data-solutionid="<?=$solution->id?>" style="float: left; height: 9px; background: url(/img/<?=$solution->rating?>-rating.png) repeat scroll 0% 0% transparent; width: 56px;">
                                        <?php if($pitch->user_id == $this->session->read('user.id')):?>
                                        <a data-rating="1" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                                        <a data-rating="2" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                                        <a data-rating="3" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                                        <a data-rating="4" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                                        <a data-rating="5" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                                        <?php endif;?>
                                    </div>
                                </span>
                                <span class="like_view" style="margin-top:2px;"><img src="/img/looked.png" alt="" class="icon_looked" /><span><?=$solution->views?></span>
                            </div>
                            <ul style="margin-left: 78px;" class="right">
                                <li class="like-hoverbox" style="float: left; margin-top: 0px; padding-top: 0px; height: 15px; padding-right: 0px; margin-right: 0px; width: 38px;">
                                    <?php if ($pitch->status == 2):?>
                                        <img src="/img/like.png" style="float: left;" alt="количество лайков" />
                                    <?php else:?>
                                        <a href="#" style="float:left" class="like-small-icon" data-id="<?=$solution->id?>"><img src="/img/like.png" alt="количество лайков" /></a>
                                    <?php endif;?>
                                    <span class="underlying-likes" style="color: rgb(205, 204, 204); font-size: 10px; vertical-align: middle; display: block; float: left; height: 16px; padding-top: 5px; margin-left: 2px;" data-id="<?=$solution->id?>" rel="http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>"><?=$solution->likes?></span>
                                    <?php if((($pitch->private != 1) && ($pitch->category_id != 7))):?>
                                    <div class="sharebar" style="padding:0 0 4px !important;background:url('/img/tooltip-bg-bootom-stripe.png') no-repeat scroll 0 100% transparent !important;position:relative;z-index:10000;display: none; left: -10px; right: auto; top: 20px;height: 178px;width:288px;">
                                        <div class="tooltip-wrap" style="height: 140px; background: url(/img/tooltip-top-bg.png) no-repeat scroll 0 0 transparent !important;padding:39px 10px 0 16px !important">
                                        <div class="body" style="display: block;">
                                            <table  width="100%">
                                                <tr height="35">
                                                    <td width="137" valign="middle">
                                                        <a id="facebook<?=$solution->id?>" class="socialite facebook-like" href="http://www.facebook.com/sharer.php?u=http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>" data-href="http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>" data-send="false" data-layout="button_count">
                                                            Share on Facebook
                                                        </a>
                                                        <!--div class="fb-like" data-href="http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>" data-send="false" data-layout="button_count" data-width="120" style="" data-show-faces="false" data-font="arial">

                                                        </div--></td>
                                                    <td width="137" valign="middle">
                                                        <?php
                                                        if (rand(1, 100) <= 50) {
                                                            $tweetLike = 'Мне нравится этот дизайн! А вам?';
                                                        } else {
                                                            $tweetLike = 'Из всех ' . $pitch->ideas_count . ' мне нравится этот дизайн';
                                                        }
                                                        ?>
                                                        <a id="twitter<?=$solution->id?>" class="socialite twitter-share" href="" data-url="http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>?utm_source=twitter&utm_medium=tweet&utm_content=like-tweet&utm_campaign=sharing" data-text="<?php echo $tweetLike; ?>" data-lang="ru" data-hashtags="Go_Deer">
                                                            Share on Twitter
                                                        </a>
                                                        <!--div id="vk_like<?=$solution->id?>"></div>
                                                        <script type="text/javascript">
                                                            window.onload = function () {
                                                                console.log('load widgit');
                                                                console.log($('#vk_like<?=$solution->id?>'));
                                                                VK.Widgets.Like("vk_like<?=$solution->id?>", {type: "mini"});
                                                            }
                                                        </script--></td>
                                                </tr>
                                                <tr height="35">
                                                    <td valign="middle">
                                                        <a href="http://www.tumblr.com/share" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:81px; height:20px; background:url('http://platform.tumblr.com/v1/share_1.png') top left no-repeat transparent;">Share on Tumblr</a>

                                                    </td>
                                                    <td valign="middle">
                                                        <a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F<?=$solution->id?>&media=<?=urlencode('http://www.godesigner.ru' . $this->solution->renderImageUrl($solution->images['solution_solutionView']))?>&description=%D0%9E%D1%82%D0%BB%D0%B8%D1%87%D0%BD%D0%BE%D0%B5%20%D1%80%D0%B5%D1%88%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BD%D0%B0%20%D1%81%D0%B0%D0%B9%D1%82%D0%B5%20GoDesigner.ru" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
                                                    </td>
                                                </tr>
                                                <!--tr height="35">
                                                    <td valign="middle"><a href="http://www.tumblr.com/share?v=3&u=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F<?=$solution->id?>&t=%D0%9E%D1%82%D0%BB%D0%B8%D1%87%D0%BD%D0%BE%D0%B5%20%D1%80%D0%B5%D1%88%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BD%D0%B0%20%D1%81%D0%B0%D0%B9%D1%82%D0%B5%20GoDesigner.ru" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:81px; height:20px; background:url('http://platform.tumblr.com/v1/share_1.png') top left no-repeat transparent;">Share on Tumblr</a></td>
                                                    <td valign="middle"><div class="g-plusone" data-href="http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>"></div></td>
                                                </tr-->
                                                <!--tr height="35">
                                                    <td valign="middle"><script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
                                                        <script type="IN/Share" data-counter="right"></script></td>
                                                    <td valign="middle"><a target="_blank" class="surfinbird__like_button" data-surf-config="{'url': 'http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>', 'layout': 'common', 'width': '120', 'height': '20'}" href="http://surfingbird.ru/share">Серф</a></td>
                                                </tr-->
                                            </table>


                                        </div>
                                        <!--div class="url" style="display: block;">#</div-->
                                    </div></div>
                                    <?php endif;?>
                                </li>
                                <li style="padding-left:0;margin-left:0;float: left; padding-top: 1px; height: 16px; margin-top: 0;width:30px">
                                    <span class="bottom_arrow">
                                    <?php if($this->session->read('user.id')):?>
                                         <a href="#" class="solution-menu-toggle"><img src="/img/marker5_2.png" alt=""></a>
                                    <?php endif?>
                                    </span>
                                </li>
                            </ul>

                            </span>

                        </div>

                    </div>
                    <div class="selecting_numb"><a href="/users/view/<?=$solution->user->id?>" class="portfolio_gallery_username"><?=$this->nameInflector->renderName($solution->user->first_name, $solution->user->last_name)?></a><a href="#" class="number_img_gallery" data-comment-to="#<?=$solution->num?>" >#<?=$solution->num?></a></div>
                    <div class="solution_menu" style="display: none;">
                        <ul class="solution_menu_list" style="position:absolute;z-index:6;">
                            <?php if(($this->session->read('user.id') > 0) && ($solution->hidden == 0) && ($this->session->read('user.id') == $pitch->user_id)): ?>
                            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="/solutions/hide/<?=$solution->id?>.json" class="hide-item" data-to="<?=$solution->num?>">С глаз долой</a></li>
                            <?php endif;?>




                            <?php if(($selectedsolution) && ($this->session->read('user.id') == $pitch->user_id) && ($pitch->awarded == $solution->id)):?>
                            <li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;"><a href="/users/step4/<?=$solution->id?>">Перейти к завершению</a></li>
                            <?php endif;?>
                    <?php
                    if(

                        (($pitch->status > 0) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true) && (($this->session->read('user.id') == $pitch->user_id) || (in_array($this->session->read('user.id'), $expertsIds)) || ($this->session->read('user.isAdmin')))) ||
                        (($pitch->status == 0) && ($pitch->published == 1) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true))

                        && ($this->session->read('user.id'))
                    ):?>
                            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="#" class="solution-link-menu" data-comment-to="#<?=$solution->num?>">Комментировать</a></li>
                        <?php endif;?>
                            <?php if($this->session->read('user.id') > 0):?>
                            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="/solutions/warn/<?=$solution->id?>.json" class="warning" data-solution-id="<?=$solution->id?>">Пожаловаться</a></li>
                            <?php endif;?>
                            <?php if((!$selectedsolution) && ($this->session->read('user.id') == $pitch->user_id)):?>
                            <li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;"><a class="select-winner" href="/solutions/select/<?=$solution->id?>.json" data-solutionid="<?=$solution->id?>" data-user="<?=$this->nameInflector->renderName($solution->user->first_name, $solution->user->last_name)?>" data-num="<?=$solution->num?>" data-userid="<?=$solution->user->id?>">Назначить победителем</a></li>
                            <?php endif;?>

                            <?php if(($this->session->read('user.id') > 0) && ($solution->hidden == 1) && ($this->session->read('user.id') == $pitch->user_id)): ?>
                            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="/solutions/unhide/<?=$solution->id?>.json" class="unhide-item" data-to="<?=$solution->num?>">Сделать видимой</a></li>
                            <?php endif;?>
                            <?php if(($this->session->read('user.id') == $solution->user_id) || \app\models\User::checkRole('admin') || ($this->session->read('user.isAdmin') == 1)):?>
                            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a class="delete-solution" data-solution="<?=$solution->id?>" href="/solutions/delete/<?=$solution->id?>.json">Удалить</a></li>
                            <?php endif;?>

                        </ul>
                    </div>
                </li>
                <?php
                $i++;
                endforeach;?>
            </ul>
            <?php else:?>
            <div class="bigfont">
                <h2 class="title">Ещё никто не выложил свои идеи.</h2>
                <?php if($pitch->user_id != $this->session->read('user.id')):?>
                <h2 class="title"><?=$this->html->link('предложи свое решение', array('controller' => 'pitches', 'action' => 'upload', 'id' => $pitch->id), array('escape' => false))?></h2>
                <h2 class="title">и стань первым!</h2>
                <?php endif?>
            </div>
            <?php endif;?>
        </div>
        <div class="foot-content" style="display:none">
                <ul class="icons-infomation">
                </ul>
                <div class="page-nambe-nav">
                    <a href="">&#60;</a><a href="" class="this-page">1</a><a href="">2</a><a href="">3</a><a href="">4</a><a href="">5</a><a href="">6</a> … <a href="">7</a><a href="">&#62;</a>
                </div>
            </div>
        </section>

        <section class="white" style="margin: 0 -34px">
            <div class="messages_gallery">
                <?php
                if(

                (($pitch->status > 0) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true) && (($this->session->read('user.id') == $pitch->user_id) || (in_array($this->session->read('user.id'), $expertsIds)) || (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))) || ($this->session->read('user.isAdmin')))) ||
                (($pitch->status == 0) && ($pitch->published == 1) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true))

                && ($this->session->read('user.id'))
                ):?>
                <script>var allowComments = true;</script>
                <section>
                    <div class="all_messages">
                        <div class="clr"></div>
                    </div>
                    <div class="separator" style="width: 810px; margin-left: 30px;"></div>
                    <div class="comment" id="comment-anchor">
                    <?php if ((($this->session->read('user.id') == $pitch->user_id) || (in_array($this->session->read('user.id'), $expertsIds)) || (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))) || ($this->session->read('user.isAdmin')))):
                        $buttonText = 'Отправить'; ?>
                        Оставьте комментарий всем участникам
                    <?php else:
                        $buttonText = 'Отправить вопрос'; ?>
                        Задайте вопрос заказчику
                    <?php endif; ?>
                    </div>
                    <input type="hidden" value="<?=$pitch->category_id?>" name="category_id" id="category_id">
                    <form class="createCommentForm" method="post" action="/comments/add">
                        <div style="display:none; background: url(/img/tooltip-bg-top-stripe.png) no-repeat scroll 0 0 transparent !important; padding: 4px 0 0 !important; height: auto; width: 205px; position: absolute; z-index: 2147483647;" id="tooltip-bubble">
                            <div style="background:url(/img/tooltip-bottom-bg2.png) no-repeat scroll 0 100% transparent; padding: 10px 10px 22px 16px;height:100px;">
                                <div style="" id="tooltipContent" class="supplement3">
                                    <p>Укажите номер комментируемого варианта, используя хештег #. Например:
                                    #2, нравится!<br>
                                    Обратитесь к автору решения, используя @. Например:<br>
                                    @username, спасибо!
                                    </p>
                                </div>
                            </div>
                        </div>
                        <textarea id="newComment" name="text"></textarea>
                        <input type="hidden" value="" name="solution_id">
                        <input type="hidden" value="" name="comment_id">
                        <input type="hidden" value="<?=$pitch->id?>" name="pitch_id">
                        <input type="submit" style="margin-left:16; width: 200px;" id="createComment" class="button" value="<?php echo $buttonText; ?>" src="/img/message_button.png" />
                        <div class="clr"></div>
                    </form>
                </section>
                <?php else:?>
                <script>var allowComments = false;</script>
                <?php endif?>
                <!-- start: Pitch Comments -->
                <section class="pitch-comments isField">
                    <div class="ajax-loader"></div>
                <!-- end: Pitch Comments -->
                </section>
            </div>
        </section>
                <?php if((strtotime($pitch->started) > strtotime('2013-01-31'))):?>
    <div id="placeholder" style="height:215px;width:958px;position:relative;left:-63px;background-image: url('/img/zaglushka.png')"></div>
    <div style="display:none;" id="floatingblock" class="floatingblock">
                    <table style="width:500px;float:left">
                    <tr style="width:500px;float:left; height:28px">
                        <td><img style="margin-left:19px;margin-top:9px" src="/img/top.png"/></td>
                    </tr>
                    <tr>
                        <td height="200">
                            <div style="margin-left:18px;padding:0;float:left;margin-top:5px;width:474px;height:150px; border:1px black; background-color: #f2f1f0;overflow:hidden;" id="container"></div>
                            <div style="clear:both"></div>
                            <div id="scrollerarea" style="display:none;height:12px;margin-left:45px;width:425px;background-image: url('/img/scrollbarfiller.png')">
                                <div id="scroller" style="margin-top: 10px;background-image:url('/img/scroller.png');height:12px;width:76px;background-color:black;left:349px;"></div>
                            </div>
                        </td>
                    </tr>
                    </table>
                    <div style="float:right;height:209px;width:458px;">
                        <ul style="margin-top: 51px;margin-left:4px; float:left;width: 100px;height:190px;">
                            <li data-points="5" style="font-size:10px;text-transform: uppercase;color:#a9a8a8;margin-bottom:3px">Фантастик!!!</li>
                            <li data-points="4" style="font-size:10px;text-transform: uppercase;color:#a9a8a8;margin-bottom:3px">Хорошо</li>
                            <li data-points="3" style="font-size:10px;text-transform: uppercase;color:#a9a8a8;margin-bottom:3px">ОК</li>
                            <li data-points="2" style="font-size:10px;text-transform: uppercase;color:#a9a8a8;margin-bottom:3px">Так себе</li>
                            <li data-points="1" style="font-size:10px;text-transform: uppercase;margin-bottom:3px;">Плохо</li>
                            <li data-points="0" style="font-size:10px;text-transform: uppercase;color:#a9a8a8;margin-bottom:3px">Так не пойдёт</li>
                        </ul>
                        <div style="width:150px;float:left;height:190px;text-align;center">
                            <h2 style="margin-top: 11px; font-size: 15px; font-weight: bold; color: rgb(102, 102, 102); text-shadow: -1px 0px 0px rgb(255, 255, 255); margin-left: 7px;">Средняя оценка</h2>
                            <p style="color: rgb(102, 102, 102); font: 12px/15px arial; margin-left: 7px;">вашей активности из 5</p>
                            <div style="background-image:url(/img/big-krug.png);margin-top:4px;height:132px;width:132px;">
                            <canvas id="can" height="132" width="132" style="">
                            </canvas></div>
                            <div style="background: url('/img/krug-small.png') no-repeat scroll 32px 30px transparent;height: 82px; width: 87px; position: relative; top: -132px; bottom: 0px; z-index: 15; padding-top: 50px; padding-left: 45px;">
                                <h2 id="avgPoints" style="font-size:28px;color:#666666;text-shadow: -1px 0 0 #FFFFFF;width:40px;text-align:center"></h2>
                                <h2 id="avgPointsString" style="color: rgb(102, 102, 102); text-align: center; text-shadow: -1px 0px 0px rgb(255, 255, 255); margin-left: 0px; margin-top: 4px; font-size: 9px; width: 44px;">БАЛЛА</h2>
                            </div>
                        </div>
                        <?php if($pitch->guaranteed == 0):?>
                        <div style="width:200px;float:left;height:190px;text-align;center">
                            <h2 style="margin-top: 80px; font-size: 15px; font-weight: bold; color: rgb(102, 102, 102); text-shadow: -1px 0px 0px rgb(255, 255, 255); margin-left: 12px; width: 163px; text-align: center;" id="refundLabel"></h2>
                            <p style="color: rgb(102, 102, 102); margin-left: 34px; margin-top: 17px; font: 14px/15px arial;"><a target="_blank" href="http://www.godesigner.ru/answers/view/71">Что это значит?</a><br>
                            </p>
                        </div>
                        <?php else:?>
                        <div style="width:200px;float:left;height:190px;text-align:center">
                            <h2 style="margin-top: 11px; font-size: 15px; font-weight: bold; color: rgb(102, 102, 102); text-shadow: -1px 0px 0px rgb(255, 255, 255);">Ваш питч<br> гарантированный</h2>

                            <img src="/img/bigg.png" style="margin-bottom:10px;margin-top: 15px; margin-left: 54px; padding-right:50px;">
                            <a href="/answers/view/79" target="_blank" style="margin-left:10px;text-decoration: underline;margin-top: 23px;">Что это такое?</a>
                        </div>
                        <?php endif?>
                    </div>
                </div>
                <?php endif?>

                </div><!-- /solution -->
                <div id="under_middle_inner"></div><!-- /under_middle_inner -->
            </div>

        </div><!-- /middle_inner -->


	</div><!-- /middle -->

</div><!-- .wrapper -->
<div id="popup-final-step" class="popup-final-step" style="display:none">
    <h3>Убедитесь в правильном выборе!</h3>
    <p>Эта процедура является окончательной, и в дальнейшем вы не сможете изменить своё мнение. Пожалуйста, убедитесь ещё раз в верности вашего решения. Вы уверены, что победителем питча становится <a id="winner-user-link" href="#" target="_blank"></a> c решением <a id="winner-num" href="#" target="_blank"></a>?</p>
    <div class="portfolio_gallery" style="width:200px;margin-bottom:5px;">
        <ul class="list_portfolio">
            <li>
                <div id="replacingblock">
                    <a href="#"><img alt="" src="#"></a>
                    <div class="photo_opt">
                    <span class="rating_block"><img alt="" src="/img/0-rating.png"></span>
                                <span class="like_view" style="margin-top:1px;"><img class="icon_looked" alt="" src="/img/looked.png"><span>0</span>
                                <a data-id="57" class="like-small-icon" href="#"><img alt="" src="/img/like.png"></a><span>0</span></span>
                    <span class="bottom_arrow"><a class="solution-menu-toggle" href="#"><img alt="" src="/img/marker5_2.png"></a></span>
                </div>
            </li>
        </ul>
    </div>
    <div class="final-step-nav wrapper"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="confirmWinner" value="Да, подтвердить"></div>
</div>

<div id="popup-warning" class="popup-warn generic-window" style="display:none">
    <p style="margin-top:120px;">Вы можете пожаловаться, если обнаружены грубые высказывания, реклама, спам, контент для взрослых, ссылки на работы, сделки вне Go Designer, копирование чужой работы или плагиат. В последнем случае важно предоставить ссылку на оригинал. Важно однако учитывать, что в питче с одним брифом некоторая степень похожести работ допускается. Подробнее <a href="http://www.godesigner.ru/answers/view/38" target="_blank">тут</a></p>
    <p>Пожалуйста, прокомментируйте суть жалобы:</p>
    <textarea id="warn-solution" class="placeholder" placeholder="ВАША ЖАЛОБА"></textarea>
    <div class="final-step-nav wrapper" style="margin-top:20px;"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="sendWarn" value="Да, подтвердить"></div>
</div>

<div id="popup-warning-comment" class="popup-warn generic-window" style="display:none">
    <p style="margin-top:120px;">Вы можете пожаловаться, если обнаружены грубые высказывания, реклама, спам, контент для взрослых, ссылки на работы, сделки вне Go Designer, копирование чужой работы или плагиат. В последнем случае важно предоставить ссылку на оригинал. Важно однако учитывать, что в питче с одним брифом некоторая степень похожести работ допускается. Подробнее <a href="http://www.godesigner.ru/answers/view/38" target="_blank">тут</a></p>
    <p>Пожалуйста, прокомментируйте суть жалобы:</p>
    <textarea id="warn-comment" class="placeholder" placeholder="ВАША ЖАЛОБА"></textarea>
    <div class="final-step-nav wrapper" style="margin-top:20px;"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="sendWarnComment" value="Да, подтвердить"></div>
</div>

<!-- Moderation Popups -->
<?php if ( (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81)) || ($this->session->read('user.isAdmin') == 1)) ):?>
    <?=$this->view()->render(array('element' => 'moderation'))?>
<?php endif; ?>

<!-- Solution Popup -->
<script>
var pitchNumber = <?php echo $pitch->id; ?>;
var currentUserId = <?php echo (int)$this->session->read('user.id'); ?>;
var currentUserName = '<?=$this->nameInflector->renderName($this->session->read('user.first_name'), $this->session->read('user.last_name'))?>';
var isCurrentAdmin = <?php echo ((int)$this->session->read('user.isAdmin') || \app\models\User::checkRole('admin')) ? 1 : 0 ?>;
var isCurrentExpert = <?php echo (in_array($this->session->read('user.id'), $expertsIds)) ? 1 : 0; ?>;
var isNewComments = <?php echo (time() > NEW_COMMENT_DATE) ? 1 : 0 ?>;
var isClient = <?php echo ((int)$this->session->read('user.id') == $pitch->user->id) ? 1 : 0; ?>;
</script>
<!-- start: Solution overlay -->
<div class="solution-overlay">
    <!-- start: Solution Container -->
    <div class="solution-container">
        <div class="solution-nav-wrapper">
            <div class="solution-prev"></div>
            <a class="solution-prev-area" href="#"></a>
            <div class="solution-next"></div>
            <a class="solution-next-area" href="#"></a>
        </div>
        <!-- start: Solution Right Panel -->
        <div class="solution-right-panel">
            <div class="solution-popup-close"></div>
            <div class="solution-info solution-summary">
                <div class="solution-number">#<span class="number isField"><!--  --></span></div>
                <div class="solution-rating"><div class="rating-image star0"></div> рейтинг заказчика</div>
            </div>
            <div class="separator"></div>
            <div class="solution-info solution-author chapter">
                <h2>АВТОР</h2>
                <img class="author-avatar" src="/img/default_small_avatar.png" alt="Портрет автора" />
                <a class="author-name isField" href="#"><!--  --></a>
                <div class="author-from isField"><!--  --></div>
                <div class="clr"></div>
            </div>
            <div class="separator"></div>
            <div class="solution-info solution-about chapter">
                <h2>О РЕШЕНИИ</h2>
                <span class="solution-description isField"><!--  --></span><a class="description-more">… Подробнее</a>
            </div>
            <div class="separator"></div>
            <div class="solution-copyrighted"><!--  --></div>
            <div class="solution-info">
                <table class="solution-stat">
                    <col class="icon">
                    <col class="description">
                    <col class="value">
                    <tr>
                        <td class="icon icon-eye"></td>
                        <td>Просмотры</td>
                        <td class="value-views isField"><!--  --></td>
                    </tr>
                    <tr>
                        <td class="icon icon-thumb"></td>
                        <td>Лайки</td>
                        <td class="value-likes isField"><!--  --></td>
                    </tr>
                    <tr>
                        <td class="icon icon-comments"></td>
                        <td>Комментарии</td>
                        <td class="value-comments isField"><!--  --></td>
                    </tr>
                </table>
            </div>
            <div class="separator"></div>
            <div class="solution-info solution-share chapter">

            </div>
            <div class="separator"></div>
            <div class="solution-info solution-abuse isField"><!--  --></div>
            <div class="separator"></div>
        <!-- end: Solution Right Panel -->
        </div>
        <!-- start: Solution Left Panel -->
        <div class="solution-left-panel">
            <a class="solution-title" href="/pitches/view/<?=$pitch->id?>">
                <h1>
                    <?=$pitch->title?>
                </h1>
            </a>
            <!-- start: Soluton Images -->
            <section class="solution-images isField">
                <div style="text-align:center;height:220px;padding-top:180px"><img alt="" src="/img/blog-ajax-loader.gif"></div>
            <!-- end: Solution Images -->
            </section>
            <section class="allow-comments">
                <div class="all_messages">
                	<div class="clr"></div>
                </div>
                <div class="separator full"></div>
                <input type="hidden" value="<?=$pitch->category_id?>" name="category_id" id="category_id">
                <form class="createCommentForm" method="post" action="/comments/add">
                	<div style="display:none; background: url(/img/tooltip-bg-top-stripe.png) no-repeat scroll 0 0 transparent !important; padding: 4px 0 0 !important; height: auto; width: 205px; position: absolute; z-index: 2147483647;" id="tooltip-bubble">
                		<div style="background:url(/img/tooltip-bottom-bg2.png) no-repeat scroll 0 100% transparent; padding: 10px 10px 22px 16px;height:100px;">
                			<div style="" id="tooltipContent" class="supplement3">
                				<p>Укажите номер комментируемого варианта, используя хештег #. Например:
                				#2, нравится!<br>
                				Обратитесь к автору решения, используя @. Например:<br>
                				@username, спасибо!
                				</p>
                			</div>
                		</div>
                	</div>
                	<textarea id="newComment" name="text"></textarea>
                	<input type="hidden" value="" name="solution_id">
                	<input type="hidden" value="" name="comment_id">
                	<input type="hidden" value="<?=$pitch->id?>" name="pitch_id">
                	<input type="submit" id="createComment" class="button" value="Отправить комментарий">
                	<div class="clr"></div>
                </form>
            </section>
            <!-- start: Comments -->
            <section class="solution-comments isField">

            <!-- end: Comments -->
            </section>
        <!-- end: Solution Left Panel -->
        </div>
        <div class="clr"></div>
    <!-- end: Solution Container -->
    </div>
<!-- end: Solution overlay -->
</div>
    <div id="bridge" style="display:none;"></div>
<?php if((strtotime($pitch->started) > strtotime('2013-01-31'))):?>
<?=$this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), '//assets.pinterest.com/js/pinit.js', 'http://surfingbird.ru/share/share.min.js?v=5', 'jcarousellite_1.0.1.js', 'jquery.simplemodal-1.4.2.js', 'jquery.scrollto.min.js', 'socialite.js', 'pitches/view.js?' . mt_rand(100, 999), 'jquery.hover.js', 'jquery-ui-1.8.23.custom.min.js', 'kinetic-v4.5.4.min.js', 'pitches/plot.js', 'jquery.raty.min.js'), array('inline' => false))?>
    <?php else:?>
    <?=$this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), '//assets.pinterest.com/js/pinit.js', 'http://surfingbird.ru/share/share.min.js?v=5', 'jcarousellite_1.0.1.js', 'jquery.simplemodal-1.4.2.js', 'jquery.scrollto.min.js', 'socialite.js', 'pitches/view.js?' . mt_rand(100, 999), 'jquery.hover.js', 'kinetic-v4.5.4.min.js'), array('inline' => false))?>
    <?php endif?>
<?=$this->html->style(array('/messages12', '/pitches12', '/view', '/pitch_overview'), array('inline' => false))?>