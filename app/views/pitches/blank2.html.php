<div class="wrapper pitchpanel login">

<?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>

<div class="middle">
<div class="middle_inner_gallery" style="padding-top:25px">

<div style="margin-left:280px;width: 560px; height:70px;margin-bottom:40px;">
    <?php if($pitch->user_id != $this->session->read('user.id') || $pitch->status > 0): ?>
    <table class="pitch-info-table" border="1">
        <tr><td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1"><span class="regular">Гонорар:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$this->moneyFormatter->formatMoney($pitch->price, array('suffix' => 'р.-'))?></span></td>
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

<div style="height:36px;margin-bottom:5px;">
    <div id="pitch-title" class="breadcrumbs-view" style="width:770px;float:left;">
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


<div class="main_carous clear" style="margin-top: 0">
<!--div>
				<div id="prev" class="arrow arrow_left"><a href="#"></a></div>
				<div id="next" class="arrow arrow_right"><a href="#"></a></div>
                    <div id="big_carousel">
                        <ul class="group">
                        	<?php foreach($solutions as $gallerySolution):?>
                        	<li><?=$this->html->link('<img width="99" height="75" src="' . $gallerySolution->images['solution_galleryLargeSize']['weburl'] . '" alt="">', array('controller' => 'pitches', 'action' => 'viewsolution', 'id' => $gallerySolution->id), array('escape' => false))?></li>
                        	<?php endforeach;?>
                        </ul>
                    </div>
			</div-->
<a id="prevsol" style="position: relative; left: -10px; margin-bottom: 0px; top: 150px; bottom: 0px;" href="/pitches/viewsolution/<?=$prev?>?sorting=<?=$sort?>"><img src="/img/arrow_solution_left.png"></a>
<a id="nextsol" style="position: relative; right: 0px; left: 757px; margin-top: 0px; top: 150px;" href="/pitches/viewsolution/<?=$next?>?sorting=<?=$sort?>"><img src="/img/arrow_solution_right.png"></a>
<div class="solution"  style="background-image:none;padding-right: 0; margin-top:0">
<input type="hidden" name="solution_id" value="<?=$solution->id?>">
<div class="img-solution">
    <?php if($this->solution->getImageCount($solution->images['solution_solutionView']) > 1):?>
    <div id="img-num-counter" style="position:absolute;z-index:2;color:white;font-weight:bold;font-size:14px;padding-top:11px;top:15px;left:515px;text-align:center;width:40px;height:29px;background-image: url('/img/multifile.png')"><?=$this->solution->getImageCount($solution->images['solution_solutionView'])?></div>
    <?php endif?>
    <input type="hidden" value="<?=$solution->rating?>" name="rating">
    <?php if($this->session->read('user.id')):?>
    <input type="hidden" value="<?=$this->session->read('user.id')?>" name="user_id">
    <?php else:?>
    <input type="hidden" value="0" name="user_id">
    <?php endif;?>
    <input type="hidden" value="<?=$pitch->user_id?>" name="pitch_user_id">
    <?php
    $expertsIds = array();
    foreach($experts as $expert) :
        $expertsIds[] = $expert->user_id;
    endforeach;
    if($pitch->category_id != 7):
        if(($pitch->private == 1) && (($solution->user_id != $this->session->read('user.id')) && (!in_array($this->session->read('user.id'), $expertsIds)) && ($pitch->user_id != $this->session->read('user.id')) && (!in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))))):?>
            <div class="preview" style="width:488px;height:366px;background-color:#efefef;">
                <img src="/img/copy-inv.jpg" width="" height="" alt="" />
            </div>
            <?php else:
            if(isset($solution->images['solution_solutionView'][0])):
                $image = $solution->images['solution_solutionView'][0]['weburl'];
                $filelist = $zoomfilelist = array();
                foreach($solution->images['solution_solutionView'] as $file):
                    $filelist[] = '"' . $file['weburl'] . '"';
                endforeach;
                foreach($solution->images['solution_gallerySiteSize'] as $file):
                    $zoomfilelist[] = '"' . $file['weburl'] . '"';
                endforeach;
                ?>
                <script type="text/javascript">
                    var fileSet = [<?php echo implode(',', $filelist)?>];
                    var zoomFileSet = [<?php echo implode(',', $zoomfilelist)?>];
                </script>
                <div class="preview" style="display: block; height:366px; width: 488px;background-image: url(/img/fullzoom.png);">
                    <a href="#" id="left" style="position:absolute; display:none;opacity:0; background-color:#000; z-index: 1; height:366px;"><img width="60" height="366" src="/img/1x1px.png" alt="" /></a>
                    <a href="<?=$this->solution->renderImageUrl($solution->images['solution_gallerySiteSize'])?>" id="zoom" target="_blank" style="position:absolute; display:none;opacity:0.2; left:113px;background-color:#000; z-index: 1;width:363px;"><img src="/img/multi-center.png" height="366" alt="увеличить"/></a>
                    <a href="#" id="right" style="position:absolute; display:none;left:478px;opacity:0; background-color:#000; z-index: 1; height:366px;"><img src="/img/1x1px.png" width="60" height="366" alt="следующий файл"/></a>
                    <img style="" id="image" src="<?=$this->solution->renderImageUrl($solution->images['solution_solutionView'])?>" width="488" height="366" alt="" />
                </div>
                <?php
            else:
                $origFileInfo = getimagesize($solution->images['solution']['filename']);
                $width = $origFileInfo[0];
                if((isset($solution->images['solution_gallerySiteSize']['filename'])) && (file_exists($solution->images['solution_gallerySiteSize']['filename']))):
                    if($width >= 800) {
                        $image = $solution->images['solution_gallerySiteSize']['weburl'];
                    }else {
                        $image = $solution->images['solution']['weburl'];
                    }
                elseif(isset($solution->images['solution_gallerySiteSize'][0])):
                    $image = $solution->images['solution_gallerySiteSize'][0]['weburl'];
                    if($width >= 800) {
                        $image = $solution->images['solution_gallerySiteSize'][0]['weburl'];
                    }else {
                        $image = $solution->images['solution'][0]['weburl'];
                    }
                endif;?>
                <a target="_blank" href="<?=$image?>" class="preview">
                    <img src="<?=$this->solution->renderImageUrl($solution->images['solution_solutionView'])?>" width="488" height="366" alt="" />
                </a>
                <?php
            endif;
        endif;
    else:
        if(($pitch->user_id == $this->session->read('user.id')) || (in_array($this->session->read('user.id'), $expertsIds)) || (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))) || ($solution->user_id == $this->session->read('user.id'))):?>
            <div class="preview" style="width:408px;padding:40px; height:286px;background-color:#efefef;">
                                    <span style="color:#666;font-size:34px;line-height:45px;">
                                        <?php if(mb_strlen(trim($solution->description)) > 105):?>
                                        <?=mb_substr(trim($solution->description), 0, 105, 'UTF-8')?>
                                        <?php else:?>
                                        <?=trim($solution->description)?>
                                        <?php endif?>
                                    </span>
            </div>
            <?php else:?>
            <div class="preview" style="width:488px;height:366px;background-color:#efefef;">
                <img src="/img/copy-inv.jpg" width="" height="" alt="" />
            </div>
            <?php endif;
    endif;
    ?>
    <div id="rating" class="rating" style="float: left;margin-top:22px;margin-left:10px;"></div>
    <span class="username" style="margin-top:18px;padding-top:2px;"><a href="/users/view/<?=$solution->user->id?>"><?=$this->nameInflector->renderName($solution->user->first_name, $solution->user->last_name)?></a></span>

    <ul class="right" style="margin-top:18px;">
        <li class="number" style="float:right;padding-top:1px;height:16px;">
            <?php if($this->session->read('user.id')):?>
            <a href="#" id="solution-menu-toggle" style="margin-right:5px;">
                <img src="/img/big-arrow.png" width="19" height="19" alt=""/>
            </a>
            <?php endif?>
        </li>
        <li class="likes" style="padding-top: 4px;margin-right:0;float:right;">
            <a href="#" id="like" style="margin-right:5px;"><img src="/img/likes.png" width="24" height="21" alt=""/></a><span id="like-count"><?=$solution->likes?></span>
            <?php if((($pitch->id == '100625') || ($pitch->id == '100534')) || (($pitch->status < 1) && ($pitch->private != 1) && ($pitch->category_id != 7))):?>
            <div id="sharebar" style="display: none; left: -10px; right: auto; top: 20px;height: 178px;"><div class="tooltip-wrap" style="height: 140px; padding-bottom: 0px;background: url(/img/tooltip-top-bg.png) no-repeat scroll 0 0 transparent !important;padding:39px 10px 0px 16px !important;">
                <div class="body" style="display: block;">
                    <table  width="100%">
                        <tr height="35">
                            <td width="137" valign="middle"><div class="fb-like" data-href="http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>" data-send="false" data-layout="button_count" data-width="50" data-show-faces="false" data-font="arial"></div></td>
                            <td width="137" valign="middle">
                                <script type="text/javascript">

                                </script>
                                <div id="vk_like"></div>
                                <script type="text/javascript">

                                </script></td>
                        </tr>
                        <tr height="35">
                            <td valign="middle">
                                <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>" data-text="Отличное решение на сайте GoDesigner.ru:" data-lang="ru" data-hashtags="Go_Deer">Твитнуть</a>
                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                            </td>
                            <td valign="middle">
                                <a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F<?=$solution->id?>&media=<?=urlencode('http://www.godesigner.ru' . $this->solution->renderImageUrl($solution->images['solution_solutionView']))?>&description=%D0%9E%D1%82%D0%BB%D0%B8%D1%87%D0%BD%D0%BE%D0%B5%20%D1%80%D0%B5%D1%88%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BD%D0%B0%20%D1%81%D0%B0%D0%B9%D1%82%D0%B5%20GoDesigner.ru" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
                            </td>
                        </tr>
                        <tr height="35">
                            <td valign="middle"><a target="_blank" class="surfinbird__like_button" data-surf-config="{'layout': 'common', 'width': '120', 'height': '20'}" href="http://surfingbird.ru/share">Серф</a></td>
                            <td valign="middle"><div class="g-plusone"></div></td>
                        </tr>
                        <tr height="35">
                            <td valign="middle"><script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
                                <script type="IN/Share" data-counter="right"></script></td>
                            <td valign="middle"><a href="http://www.tumblr.com/share" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:81px; height:20px; background:url('http://platform.tumblr.com/v1/share_1.png') top left no-repeat transparent;">Share on Tumblr</a></td>
                        </tr>
                    </table>


                </div>
                <!--div class="url" style="display: block;">#</div-->
            </div></div>
            <?php endif;?>

        </li>
        <li class="looks" style="float:right;margin-right:5px; padding-top:4px;height:16px;"><a href="#" style="margin-right:5px;"><img src="/img/looks.png" width="21" height="14" alt=""/></a><span><?=$solution->views?></span></li>
        <li class="number" style="float:right;padding-top:4px;height:16px;"><a href="#" class="number_img_gallery" data-comment-to="#<?=$solution->num?>">#<?=$solution->num?></a></li>
    </ul>
    <div class="solution_menu" style="display: none;left:310px;top:5px;">
        <ul class="solution_menu_list" style="position:absolute;z-index:1;">
            <?php if((!$selectedsolution) && ($this->session->read('user.id') == $pitch->user_id)):?>
            <li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;"><a class="select-winner" href="/solutions/select/<?=$solution->id?>.json" data-solutionid="<?=$solution->id?>" data-user="<?=$this->nameInflector->renderName($solution->user->first_name, $solution->user->last_name)?>" data-num="<?=$solution->num?>" data-userid="<?=$solution->user->id?>">Назначить победителем</a></li>
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
            <?php if(($this->session->read('user.id') == $solution->user_id) || (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))) ||  ($this->session->read('user.isAdmin') == 1)):?>
            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a class="delete-solution" href="/solutions/delete/<?=$solution->id?>.json">Удалить</a></li>
            <?php endif;?>
        </ul>
    </div>
</div>
<div class="info-solution">
    <h2><?=$pitch->title?></h2>
    <span style="color:#666;"><?=date('d.m.Y H:i', strtotime($solution->created))?></span><br /> <span style="color:#666;"><?=mb_strtoupper($pitch->industry, 'UTF-8')?></span><br />
    <div style="margin-top: 40px">
        <?php
        if((($pitch->private == 1) || $pitch->category_id == 7) && (($solution->user_id != $this->session->read('user.id')) && ($pitch->user_id != $this->session->read('user.id')) && (!in_array($this->session->read('user.id'), $expertsIds)) && (!in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))))):?>

            <?php else:?>
            <span style="font:14px/20px 'Arial',sans-serif;text-shadow:-1px 0 0 #FFFFFF;color:#666666"><?=$this->brief->e($solution->description)?></span>
            <?php if($pitch->category_id == 7):?>
                <?php
                if((isset($solution->images['solution'])) && (isset($solution->images['solution'][0]))):
                    foreach($solution->images['solution'] as $iSolution):?>

                        <?php /*if(!empty($solution->images['solution']['originalbasename'])):?>
                                        <a target="_blank" href="/solutionfiles/download/<?=base64_encode($iSolution['id']  . 'separator' . $iSolution['model_id'])?>/<?=$iSolution['originalbasename']?>" class="regular" style="color:#658FA5;word-wrap: break-word;"><?=$iSolution['originalbasename']?></a><br/>
                                    <?php else:?>
                                        <a target="_blank" href="/solutionfiles/download/<?=base64_encode($iSolution['id']  . 'separator' . $iSolution['model_id'])?>/<?=$iSolution['originalbasename']?>" class="regular" style="color:#658FA5;word-wrap: break-word;"><?=$iSolution['originalbasename']?></a><br/>
                                    <?php endif*/?>

                        <?php if(!empty($iSolution['originalbasename'])):?>
                            <a target="_blank" href="<?=$iSolution['weburl']?>" class="regular" style="color:#658FA5;word-wrap: break-word;"><?=$iSolution['originalbasename']?></a><br/>
                            <?php else:?>
                            <a target="_blank" href="<?=$iSolution['weburl']?>" class="regular" style="color:#658FA5;word-wrap: break-word;"><?=basename($iSolution['filename'])?></a><br/>
                            <?php endif?>
                        <?php endforeach?>
                    <?php elseif(isset($solution->images['solution'])):
                    if(!empty($solution->images['solution']['originalbasename'])):?>
                        <a target="_blank" href="<?=$solution->images['solution']['weburl']?>" class="regular" style="color:#658FA5;word-wrap: break-word;"><?=$solution->images['solution']['originalbasename']?></a><br/>
                        <?php else:?>
                        <a target="_blank" href="<?=$solution->images['solution']['weburl']?>" class="regular" style="color:#658FA5;word-wrap: break-word;"><?=basename($solution->images['solution']['filename'])?></a><br/>
                        <?php endif?>
                    <?php endif?>
                <?php endif?>
            <?php endif?>

    </div>
</div>

<div class="new-comment-solution">

    <?php
    if(($solution->copyrightedMaterial == 1) && (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))  || $this->session->read('user.id') == $pitch->user_id || $this->session->read('user.id') == $solution->user_id)):
        $copyData = unserialize($solution->copyrightedInfo);
        $copydataArray = array();
        foreach($copyData['filename'] as $index => $filename):
            $copydataArray[] = array('filename' => $filename, 'source' => $copyData['source'][$index], 'needtobuy' => $copyData['needtobuy'][$index]);
        endforeach;?>
        <table style="width: 760px;">

            <?php foreach($copydataArray as $row):
            $row['source'] = "http://www.godesigner.ru/urls/". $this->Url->view($row['source']);
            ?>
            <tr height="30">
                <td width="300" class="regular"><?=$row['filename']?></td>
                <td width="275" class="regular"><a href="<?=$row['source']?>" target="_blank"><?=$row['source']?></a></td>
                <td class="regular">
                    <?php if($row['needtobuy'] == 'on'):?>
                    Нужно покупать
                    <?php else:?>
                    Не нужно покупать
                    <?php endif?>
                </td>
            </tr>
            <?php endforeach;?>
            <tr height="30"><td colspan="3"></td></tr>
        </table>
        <?php
    endif;
    ?>
    <?php
    if(

        (($pitch->status > 0) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true) && (($this->session->read('user.id') == $pitch->user_id) || (in_array($this->session->read('user.id'), $expertsIds))  || (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))) || ($this->session->read('user.isAdmin')))) || (($pitch->status == 0) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true) && ($pitch->published == 1))

        && ($this->session->read('user.id'))
    ):?>
        <form id="createCommentForm" method="post" action="/comments/add">
            <input type="hidden" id="solution_id" name="solution_id" value="<?=$solution->id?>">
            <input type="hidden" value="" name="comment_id">
            <input type="hidden" value="<?=$pitch->id?>" name="pitch_id">
            <input type="hidden" value="viewsolution" name="action">
            <div class="new-comment-text" id="comment-anchor">
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
                <textarea name="text" id="newComment">#<?=$solution->num?>, </textarea>
            </div>
            <div class="new-comment-user">
                <a href="#"><span><?=$this->nameInflector->renderName($this->session->read('user.first_name'), $this->session->read('user.last_name'))?></span></a>
                <div style="margin-right:16px;float:right;">
                    <?=$this->avatar->show($this->session->read('user'));?>
                </div>
            </div>
            <div class="new-comment-send">
                <input type="submit" value="Отправить" class="button" name="new-comment-send" id="createComment">
            </div>
        </form>
        <span class="clear"></span>
        <?php endif?>
</div>

<hr style=" display:block;background:url('/img/poloska.png') center repeat-x; width:100%; height:3px; border:none; clear:both; padding-top:30px;" />
<div class="messages_gallery comments-solution">
    <?php
    $totalComments = count($comments);
    $i = 0;
    foreach($comments as $comment):

        if(($pitch->user_id != $this->session->read('user.id')) && ($pitch->private == 1 || $pitch->category_id == 7 )) {
            if(($solution->user_id != $this->session->read('user.id')) && (!in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81)))) {
                continue;
            }
            if(($comment->user_id != $pitch->user_id) && (!in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))) && ($comment->user_id != $this->session->read('user.id'))) {
                continue;
            }
        }
        $i++;
        if($pitch->user_id == $comment->user->id) {
            $class = 'message_info2';
        }elseif ($comment->user->isAdmin) {
            $class = 'message_info4';
        }elseif((in_array($comment->user->id, $expertsIds))) {
            $class = 'message_info5';
        }else {
            $class = 'message_info1';
        }
        ?>
        <section data-id="<?=$comment->id?>" <?php if($comment->user_id == $pitch->user_id) echo 'data-type="client"'?> <?php if($comment->user_id != $pitch->user_id) echo 'data-type="designer"'?>>
            <div class="<?=$class?>" style="margin-top:20px;">
                <?php if ($comment->user->isAdmin):?>
                <?php //$this->avatar->show($comment->user->data());?>
                <?php else:?>
                <a href="/users/view/<?=$comment->user->id?>">
                    <?=$this->avatar->show($comment->user->data());?>
                </a>
                <?php endif;?>
                <a href="#" data-comment-id="<?=$comment->id?>" data-comment-to="<?=$this->nameInflector->renderName($comment->user->first_name, $comment->user->last_name)?>" class="replyto">
                    <?php if(!$comment->user->isAdmin):?>
                    <span><?=$this->nameInflector->renderName($comment->user->first_name, $comment->user->last_name)?></span><br/>
                    <?php else:?>
                    <span>GoDesigner</span><br/>
                    <?php endif;?>
                    <span style="font-weight: normal;"><?=date('d.m.y H:i', strtotime($comment->created))?></span></a>
                <div class="clr"></div>
            </div>
            <div class="message_text" style="margin-top:15px;width:480px;">
                <span class="regular comment-container"><?php echo $this->brief->stripemail($comment->text)?></span>
            </div>
            <div style="width:630px;float:right;margin-top: 6px;margin-right: 28px;padding-bottom: 2px;height:18px;">
                <div class="toolbar">
                    <?php
                    if($this->session->read('user.id') == $comment->user_id):?>
                        <?= $this->html->link('Удалить', array('controller' => 'comments', 'action' => 'delete', 'id' => $comment->id), array('style' => "float:right;", "class" => "delete-link-in-comment"));?>
                        <a href="#" style="float:right;" class="edit-link-in-comment" data-id="<?=$comment->id?>" data-text="<?=htmlentities($comment->originalText, ENT_COMPAT, 'utf-8')?>">Редактировать</a>
                        <?php elseif(($this->session->read('user.id') > 0) && (($this->session->read('user.id') != $comment->user_id))):?>
                        <?php if (($this->session->read('user.isAdmin') == 1) || (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81)))):?>
                            <?= $this->html->link('Удалить', array('controller' => 'comments', 'action' => 'delete', 'id' => $comment->id), array('style' => "float:right;", "class" => "delete-link-in-comment"));?>
                            <a href="#" style="float:right;" class="edit-link-in-comment" data-id="<?=$comment->id?>" data-text="<?=htmlentities($comment->originalText, ENT_COMPAT, 'utf-8')?>">Редактировать</a>
                            <?php endif?>


                        <?php
                        if(

                            (($pitch->status > 0) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true) && (($this->session->read('user.id') == $pitch->user_id) || (in_array($this->session->read('user.id'), $expertsIds)) || ($this->session->read('user.isAdmin')))) ||
                            (($pitch->status == 0) && ($pitch->published == 1) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true))

                            && ($this->session->read('user.id'))
                        ):?>
                            <a href="#" data-comment-id="<?=$comment->id?>" data-comment-to="<?=$this->nameInflector->renderName($comment->user->first_name, $comment->user->last_name)?>" class="replyto reply-link-in-comment" style="float:right;">Ответить</a>
                            <?php endif?>

                        <?php if(!$comment->user->isAdmin):?>
                            <a href="#" data-comment-id="<?=$comment->id?>" data-url="/comments/warn.json" class="warning-comment warn-link-in-comment" style="float:right;">Пожаловаться</a>
                            <?php endif;?>
                        <?php endif;?>
                </div></div>
            <div class="clr"></div>
            <div class="hiddenform" style="display:none">
                <section><form style="margin-bottom: 25px;margin-left:0px;" action="/comments/edit/<?=$comment->id?>" method="post">
                    <!--div id="tooltip-bubble" style="background: url(&quot;/img/tooltip-bg-top-stripe.png&quot;) no-repeat scroll 0px 0px transparent ! important; padding: 4px 0px 0px ! important; height: auto; width: 205px; position: absolute; z-index: 2147483647; top: 799.6px; left: 220.5px; display: none;">
                        <div style="background:url(/img/tooltip-bottom-bg2.png) no-repeat scroll 0 100% transparent; padding: 10px 10px 22px 16px;height:100px;">
                            <div class="supplement3" id="tooltipContent" style="">
                                <p>Укажите номер комментируемого варианта, используя хештег #. Например:
                                    #2, нравится!<br>
                                    Обратитесь к автору решения, используя @. Например:<br>
                                    @username, спасибо!
                                </p>
                            </div>
                        </div>
                    </div-->
                    <textarea name="text" data-id="<?=$comment->id?>" style="width:528px;padding-left:10px;padding-right:10px"></textarea>
                    <input style="width:171px;margin-left:28px;padding:0;" type="button" src="/img/message_button.png" value="Отправить" class="button editcomment" style="margin-left:16px;margin-bottom:5px; width: 200px;"><br>
                    <span style="margin-left:36px;" class="supplement3">Нажмите Esс, чтобы отменить</span>
                    <div class="clr"></div>
                </form>
                </section>
            </div>
        </section>
        <?php if($i != $totalComments): ?>
        <div class="separator" style="width: 710px; margin-left: 30px; margin-top:2px;"></div>
        <?php endif;?>
        <?php endforeach?></div>
</div>
</div><!-- /solution -->

<nav class="other_nav clear" style="padding-top:5px;">
    <?=$this->html->link('<img src="/img/other-nav-left.png" width="184" height="35" alt="" /><br /><span>Вернуться в галерею</span>', array('controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id), array('class' => 'other-nav-left', 'escape' => false))?>

    <?php
    if(($this->session->read('user.id') != $pitch->user_id) && ($pitch->status < 1) && ($pitch->published == 1)):?>
        <?=$this->html->link('<img src="/img/1.gif" width="184" height="34" alt="" /><br /><span>предложить решение</span>', array('controller' => 'pitches', 'action' => 'upload', 'id' => $pitch->id), array('class' => 'other-nav-right active', 'escape' => false))?>
        <?php elseif(($pitch->status == 1) && ($pitch->awarded == 0)):?>
        <img src="/img/status1.jpg" class="other-nav-right active" style="margin-right: 50px;" alt="Идет выбор победителя"/>
        <?php elseif(($pitch->status == 1) && ($pitch->awarded != 0)):?>
        <img src="/img/winner-selected.jpg" class="other-nav-right active" style="margin-right: 50px;" alt="Победитель выбран"/>
        <?php elseif($pitch->status == 2):?>
        <img src="/img/status2.jpg" class="other-nav-right active" style="margin-right: 50px;" alt="Питч завершен"/>
        <?php else:?>
        <a class="other-nav-right active" href="http://www.godesigner.ru/answers/view/78" target="_blank"><img src="/img/1.gif" width="184" height="34" alt="" /><br /><span>инструменты заказчика</span></a>
        <?php endif;?>

</nav>


</div>
<div id="under_middle_inner"></div><!-- /under_middle_inner -->
</div><!-- /middle_inner -->



</div><!-- /middle -->
</div><!-- .wrapper -->
<div id="popup-final-step" class="popup-final-step" style="display:none">
    <h3>Убедитесь в правильном выборе!</h3>
    <p>Эта процедура является окончательной, и в дальнейшем вы не сможете изменить своё мнение. Пожалуйста, убедитесь ещё раз в верности вашего решения. Вы уверены, что победителем питча становится <a id="winner-user-link" href="#" target="_blank"></a> c решением <a id="winner-num" href="#" target="_blank"></a>?</p>
    <div class="portfolio_gallery" style="width:200px;margin-bottom:5px;">
        <ul class="list_portfolio">
            <li>
                <div class="photo_block">
                    <?php
                    if($this->solution->getImageCount($solution->images['solution_galleryLargeSize']) > 1):?>
                        <div style="position:absolute;color:white;font-weight:bold;font-size: 14px;padding-top:7px;top:0px;left:168px;text-align:center;width:31px;height:24px;background-image: url('/img/multifile_small.png')"><?=$this->solution->getImageCount($solution->images['solution_galleryLargeSize'])?></div>
                        <?php endif;?>
                    <a href="/pitches/viewsolution/<?=$solution->id?>"><img alt="" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>"></a>
                    <div class="photo_opt">
                        <div style="display: block; float:left;" class="">
                            <span class="rating_block"><img alt="" src="/img/<?=$solution->rating?>-rating.png"></span>
                                <span class="like_view"><img class="icon_looked" alt="" src="/img/looked.png"><span><?=$solution->views?></span>
                            </span></div>

                        <div style="display: block; float:left;">
                            <a data-id="<?=$solution->id?>" class="like-small-icon" href="#"><img alt="количество лайков" src="/img/like.png"></a>
                            <span rel="http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>" data-id="<?=$solution->id?>" style="color: #CDCCCC;font-size: 10px;margin-right:8px;vertical-align: middle;"><?=$solution->likes?></span>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="final-step-nav wrapper"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="confirmWinner" value="Да, подтвердить"></div>
</div>

<div id="popup-warning" class="popup-warn generic-window" style="display:none">
    <p style="margin-top:120px;">Вы можете пожаловаться, если обнаружены грубые высказывания, реклама, спам, контент для взрослых, ссылки на работы, сделки вне Go Designer, копирование чужой работы или плагиат. В последнем случае важно предоставить ссылку на оригинал. Важно однако учитывать, что в питче с одним брифом некоторая степень похожести работ допускается. Подробнее <a href="http://www.godesigner.ru/answers/view/38" target="_blank">тут</a>.</p>
    <p>Пожалуйста, прокомментируйте суть жалобы:</p>
    <textarea id="warn-solution" class="placeholder" placeholder="ВАША ЖАЛОБА" style="border:0; width:540px; margin-top: 10px; height: 100px;"></textarea>
    <div class="final-step-nav wrapper" style="margin-top:20px;"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="sendWarn" value="Да, подтвердить"></div>
</div>

<div id="popup-warning-comment" class="popup-warn generic-window" style="display:none">
    <p style="margin-top:120px;">Вы можете пожаловаться, если обнаружены грубые высказывания, реклама, спам, контент для взрослых, ссылки на работы, сделки вне Go Designer, копирование чужой работы или плагиат. В последнем случае важно предоставить ссылку на оригинал. Важно однако учитывать, что в питче с одним брифом некоторая степень похожести работ допускается. Подробнее <a href="http://www.godesigner.ru/answers/view/38" target="_blank">тут</a></p>
    <p>Пожалуйста, прокомментируйте суть жалобы:</p>
    <textarea id="warn-comment" class="placeholder" placeholder="ВАША ЖАЛОБА" style="border:0; width:540px; margin-top: 10px; height: 100px;"></textarea>
    <div class="final-step-nav wrapper" style="margin-top:20px;"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="sendWarnComment" value="Да, подтвердить"></div>
</div>


<div id="bridge" style="display:none;"></div>
<?=$this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), '//assets.pinterest.com/js/pinit.js', 'http://surfingbird.ru/share/share.min.js', 'jcarousellite_1.0.1.js', 'jquery.simplemodal-1.4.2.js', 'fancybox/jquery.mousewheel-3.0.4.pack.js', 'fancybox/jquery.fancybox-1.3.4.pack.js', 'jquery.raty.js', 'jquery.scrollto.min.js', 'jquery.damnUploader.js',  'pitches/viewsolution.js?' . mt_rand(100, 999)), array('inline' => false))?>
<?=$this->html->style(array('/view', '/messages12', '/pitches12', '/pitch_overview', '/jquery.fancybox-1.3.4.css'), array('inline' => false))?>