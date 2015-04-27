<input type="hidden" class="solutions-count" value="<?=$solutionsCount?>">
<?php
foreach($solutions as $solution):
    if($this->user->isSolutionAuthor($solution->user_id)) {
        $mySolutionList[] = $solution->id;
        $mySolutionNumList[] = '#' . $solution->num;
    }

    $picCounter2 = 0;
    if(isset($solution->images['solution_galleryLargeSize'][0]) && ($pitch->category_id != 7)):
        foreach($solution->images['solution_galleryLargeSize'] as $image):
            $picCounter2++;
        endforeach;
    else:
        if(!isset($solution->images['solution_galleryLargeSize']) && ($pitch->category_id != 7)):
            $solution->images['solution_galleryLargeSize'] = $solution->images['solution'];
            $picCounter2 = 0;
            if(is_array($solution->images['solution_galleryLargeSize'])):
                foreach($solution->images['solution_galleryLargeSize'] as $image):
                    $picCounter2++;
                endforeach;
            endif;
        endif;
    endif;
?>
<li <?php if(($picCounter2 > 1) && ($pitch->category_id != 7)): echo 'class="multiclass"'; endif;?> id="li_<?=$solution->num;?>">
    <!-- multisolution branch -->
    <div class="photo_block" <?php if(($picCounter2 > 1) && (($solution->hidden) && ($this->user->isPitchOwner($pitch->user_id)))):?>style="background: url(/img/copy-inv.png) 10px 10px no-repeat white"<?php endif;?>>
        <?php
        $visible = false;

        if($pitch->private != 1):
            if($pitch->category_id == 7):
                if($this->user->isLoggedIn() && (($this->user->isPitchOwner($pitch->user_id)) || ($this->user->isExpert()) || ($this->user->isAdmin()) || ($this->user->isSolutionAuthor($solution->user_id)))):
                    $visible = true;?>
                    <?php if(($solution->hidden == 1) && ($this->user->isPitchOwner($pitch->user_id))):?><div class="hidedummy" style="background-image: url(/img/copy-inv.png)"><?php endif ?>
                    <a class="imagecontainer" href="/pitches/viewsolution/<?=$solution->id?>?sorting=<?=$sort?>" style="width:147px;height:104px;background-color:#efefef;display:block;color:#666666;text-decoration:none;font-weight:bold;padding-top:16px;padding: 16px;<?php if(($solution->hidden) && ($this->user->isPitchOwner($pitch->user_id))):?>opacity:0.1;<?php endif?>">
                        <?php if(mb_strlen(trim($solution->description)) > 100):?>
                        <?=mb_substr(trim($solution->description), 0, 100, 'UTF-8')?>
                        <?php else:?>
                        <?=trim($solution->description)?>
                        <?php endif?>
                    </a>
                    <?php if(($solution->hidden) && ($this->user->isPitchOwner($pitch->user_id))):?></div><?php endif?>
                <?php else:?>
                    <a href="/pitches/viewsolution/<?=$solution->id?>?sorting=<?=$sort?>" style="background-image: url(/img/copy-inv.png);width:179px;height:136px;background-color:#efefef;display:block;"></a>
                <?php endif?>
            <?php else:
                if($this->solution->getImageCount($solution->images['solution_galleryLargeSize']) > 1):
                    $visible = true;?>
                <div style="z-index: 2; position: absolute; color: rgb(102, 102, 102); font-weight: bold; font-size: 14px; padding-top: 7px; height: 16px; top: -34px; text-align: right; width: 18px; padding-right: 21px; background: url(/img/multi-icon.png) no-repeat scroll 22px 5px transparent; left: 169px;"><?=$picCounter2?></div>
                <?php endif?>
                <?php if(($solution->hidden == 1) && ($this->user->isPitchOwner($pitch->user_id))):?><div class="hidedummy" style="background-image: url(/img/copy-inv.png)"><?php endif ?>
                <a style="<?php if(($solution->hidden) && ($this->user->isPitchOwner($pitch->user_id))):?>opacity:0.1;<?php endif?>display:block;" data-solutionid="<?=$solution->id?>" class="imagecontainer" href="/pitches/viewsolution/<?=$solution->id?>?sorting=<?=$sort?>">
                    <?php if(!isset($solution->images['solution_galleryLargeSize'][0])):?>
                        <?php if(!isset($solution->images['solution_galleryLargeSize'])):
                            $solution->images['solution_galleryLargeSize'] = $solution->images['solution'];
                            $picCounter = 0;
                            if(is_array($solution->images['solution_galleryLargeSize'])):
                                foreach($solution->images['solution_galleryLargeSize'] as $image):?>
                                    <img class="multi" width="180" height="135" style="position: absolute;left:10px;top:9px;z-index:1;<?php echo ($picCounter > 0) ? 'display:none;' : 'opacity:1;'; ?>" rel="#<?=$solution->num?>" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'], $picCounter)?>" alt="<?=($pitch->status == 2) ? $this->solution->getShortDescription($solution, 80) : '';?>">
                                    <?php $picCounter++;
                                endforeach;
                            endif;
                        endif; ?>
                        <img rel="#<?=$solution->num?>"  width="180" height="135" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>" alt="<?=($pitch->status == 2) ? $this->solution->getShortDescription($solution, 80) : '';?>">
                    <?php else:?>
                        <?php
                        $picCounter = 0;
                        foreach($solution->images['solution_galleryLargeSize'] as $image):?>
                            <img class="multi" width="180" height="135" style="position: absolute;left:10px;top:9px;z-index:1;<?php echo ($picCounter > 0) ? 'display:none;' : 'opacity:1;'; ?>" rel="#<?=$solution->num?>" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'], $picCounter)?>" alt="<?=($pitch->status == 2) ? $this->solution->getShortDescription($solution, 80) : '';?>">
                        <?php $picCounter++;
                        endforeach;?>
                    <?php endif?>

                </a><?php if(($solution->hidden) && ($this->user->isPitchOwner($pitch->user_id))):?></div><?php endif?>
            <?php endif?>
        <?php else:?>
            <!-- solo branch -->
            <?php
            if($pitch->category_id == 7):
                if(($this->user->isPitchOwner($pitch->user_id)) || ($this->user->isExpert()) || ($this->user->isAdmin()) || ($this->user->isSolutionAuthor($solution->user_id))):
                    $visible = true;?>
                    <?php if(($solution->hidden == 1) && ($this->user->isPitchOwner($pitch->user_id))):?><div class="hidedummy" style="background-image: url(/img/copy-inv.png)"><?php endif ?>
                    <a href="/pitches/viewsolution/<?=$solution->id?>?sorting=<?=$sort?>" style="width:147px;height:104px;background-color:#efefef;display:block;color:#666666;text-decoration:none;font-weight:bold;padding-top:16px;padding: 16px;<?php if(($solution->hidden) && ($this->user->isPitchOwner($pitch->user_id))):?>opacity:0.1;<?php endif?>">
                        <?php if(mb_strlen(trim($solution->description)) > 100):?>
                        <?=mb_substr(trim($solution->description), 0, 100, 'UTF-8')?>
                        <?php else:?>
                        <?=trim($solution->description)?>
                        <?php endif?>
                    </a>
                    <?php if(($solution->hidden) && ($this->user->isPitchOwner($pitch->user_id))):?></div><?php endif?>
                <?php else:?>
                    <a href="/pitches/viewsolution/<?=$solution->id?>?sorting=<?=$sort?>" style="background-image: url(/img/copy-inv.png);width:179px;height:136px;background-color:#efefef;display:block;"></a>
                <?php endif?>
            <?php else:
                    if($this->user->isPitchOwner($pitch->user_id) || ($this->user->isExpert()) || ($this->user->isAdmin()) || ($this->user->isSolutionAuthor($solution->user_id)) || ($canViewPrivate)):
                        $visible = true;
                        if($this->solution->getImageCount($solution->images['solution_galleryLargeSize']) > 1):?>
                        <div style="z-index: 2; position: absolute; color: rgb(102, 102, 102); font-weight: bold; font-size: 14px; padding-top: 7px; height: 16px; top: -34px; text-align: right; width: 18px; padding-right: 21px; background: url(/img/multi-icon.png) no-repeat scroll 22px 5px transparent; left: 169px;"><?=$this->solution->getImageCount($solution->images['solution_solutionView'])?></div>
                        <?php endif?>
                        <?php

                        if(($solution->hidden == 1) && ($this->user->isPitchOwner($pitch->user_id))):?><div class="hidedummy" style="background-image: url(/img/copy-inv.png)"><?php endif ?>
                    <a style="<?php if(($solution->hidden) && ($this->user->isPitchOwner($pitch->user_id))):?>opacity:0.1;<?php endif?>display:block;" data-solutionid="<?=$solution->id?>" class="imagecontainer" href="/pitches/viewsolution/<?=$solution->id?>?sorting=<?=$sort?>">

                        <?php if(!isset($solution->images['solution_galleryLargeSize'][0])):?>
                            <img rel="#<?=$solution->num?>"  width="180" height="135" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>" alt="<?=($pitch->status == 2) ? $this->solution->getShortDescription($solution, 80) : '';?>">
                        <?php else:?>
                        <?php
                        $picCounter = 0;
                        foreach($solution->images['solution_galleryLargeSize'] as $image):?>
                            <img class="multi"  width="180" height="135" style="position: absolute;left:10px;top:9px;z-index:1;<?php echo ($picCounter > 0) ? 'display:none;' : 'opacity:1;'; ?>" rel="#<?=$solution->num?>" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'], $picCounter)?>" alt="<?=($pitch->status == 2) ? $this->solution->getShortDescription($solution, 80) : '';?>">
                            <?php $picCounter++;
                        endforeach;?>

                        <?php endif?>

                    </a><?php if(($solution->hidden) && ($this->user->isPitchOwner($pitch->user_id))):?></div><?php endif?>

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
                        <?php if($this->user->isPitchOwner($pitch->user_id)):?>
                        <a data-rating="1" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                        <a data-rating="2" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                        <a data-rating="3" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                        <a data-rating="4" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                        <a data-rating="5" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>
                        <?php endif;?>
                    </div>
                </span>
                <?php if (!isset($fromDesignersTab)):?>
                    <span class="like_view" style="margin-top:2px;"><img src="/img/looked.png" alt="" class="icon_looked" /><span><?=$solution->views?></span>
                <?php endif;?>
            </div>
            <ul style="margin-left: 78px;" class="right">
                <?php if (!isset($fromDesignersTab)):?>
                <li class="like-hoverbox" style="float: left; margin-top: 0px; padding-top: 0px; height: 15px; padding-right: 0px; margin-right: 0px; width: 38px;">
                    <?php if ($pitch->status == 2):?>
                        <img src="/img/like.png" style="float: left;" alt="количество лайков" />
                    <?php else:?>
                        <a href="#" style="float:left" class="like-small-icon" data-id="<?=$solution->id?>"><img src="/img/like.png" alt="количество лайков" /></a>
                    <?php endif;?>
                    <span class="underlying-likes" style="color: rgb(205, 204, 204); font-size: 10px; vertical-align: middle; display: block; float: left; height: 16px; padding-top: 5px; margin-left: 2px;" data-id="<?=$solution->id?>" rel="http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>"><?=$solution->likes?></span>
                    <?php if((($pitch->private != 1) && ($pitch->category_id != 7))):
                    if (rand(1, 100) <= 50) {
                            $tweetLike = 'Мне нравится этот дизайн! А вам?';
                    } else {
                            $tweetLike = 'Из всех ' . $pitch->ideas_count . ' мне нравится этот дизайн';
                    }
                        if(!isset($solution->images['solution_galleryLargeSize'][0])):
                            $url = 'http://www.godesigner.ru' . $solution->images['solution_gallerySiteSize']['weburl'];
                        else:
                            $url = 'http://www.godesigner.ru' . $solution->images['solution_gallerySiteSize'][0]['weburl'];
                        endif;
                    ?>
                    <div class="sharebar">
                        <div class="tooltip-block">
                            <div class="social-likes" data-counters="no" data-url="http://www.godesigner.ru/pitches/viewsolution/<?=$solution->id?>" data-title="<?= $tweetLike ?>">
                                <div class="facebook" title="Поделиться ссылкой на Фейсбуке">SHARE</div>
                                <div class="twitter" data-via="Go_Deer">TWITT</div>
                                <div class="vkontakte" title="Поделиться ссылкой во Вконтакте" data-image="<?= 'http://www.godesigner.ru'. $this->solution->renderImageUrl($solution->images['solution_solutionView'])?>">SHARE</div>
                                <div class="pinterest" title="Поделиться картинкой на Пинтересте" data-media="<?= 'http://www.godesigner.ru'. $this->solution->renderImageUrl($solution->images['solution_solutionView'])?>">PIN</div>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                </li>
                <?php else:?>
                <li style="float: left; margin: 1px -10px 0 57px; padding: 0; width: auto;">
                    <a href="#" class="number_img_gallery" style="color: #ccc;" data-comment-to="#<?=$solution->num?>" >#<?=$solution->num?></a>
                </li>
                <?php endif;?>
                <li style="padding-left:0;margin-left:0;float: left; padding-top: 1px; height: 16px; margin-top: 0;width:30px">
                    <span class="bottom_arrow">
                    <?php if($this->user->isLoggedIn()):?>
                         <a href="#" class="solution-menu-toggle"><img src="/img/marker5_2.png" alt=""></a>
                    <?php endif?>
                    </span>
                </li>
            </ul>

            </span>

        </div>

    </div>
    <?php if (!isset($fromDesignersTab)):?>
    <div class="selecting_numb"><a href="/users/view/<?=$solution->user->id?>" class="portfolio_gallery_username"><?=$this->user->getFormattedName($solution->user->first_name, $solution->user->last_name)?></a><a href="#" class="number_img_gallery" data-comment-to="#<?=$solution->num?>" >#<?=$solution->num?></a></div>
    <?php endif; ?>
    <?php if(!$pitch->multiwinner):?>

    <div class="solution_menu" style="display: none;">
        <ul data-winners="<?= serialize($winnersUserIds)?>" class="solution_menu_list" style="position:absolute;z-index:6;">

            <?php if($this->pitch->isReadyForLogosale($pitch) && ($pitch->awarded != $solution->id) && !in_array($solution->user_id, $winnersUserIds)):?>
            <li class="sol_hov"><a data-solutionid="<?= $solution->id ?>" class="imagecontainer" href="/pitches/viewsolution/<?= $solution->id ?>" class="imagecontainer">Купить</a></li>
            <?php endif?>

            <?php if($this->user->isLoggedIn() && ($solution->hidden == 0) && ($this->user->isPitchOwner($pitch->user_id))): ?>
            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="/solutions/hide/<?=$solution->id?>.json" class="hide-item" data-to="<?=$solution->num?>">С глаз долой</a></li>
            <?php endif;?>


            <?php if(($selectedsolution) && ($this->user->isPitchOwner($pitch->user_id)) && ($pitch->awarded == $solution->id)):?>
            <li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;"><a href="/users/step4/<?=$solution->id?>">Перейти к завершению</a></li>
            <?php endif;?>
    <?php
    if(
        (($pitch->status > 0) && $this->user->isAllowedToComment() && ($this->user->isPitchOwner($pitch->user_id) || $this->user->isExpert() || $this->user->isAdmin())) ||
        (($pitch->status == 0) && ($pitch->published == 1) && $this->user->isAllowedToComment() && ($this->user->isSolutionAuthor($solution->user_id) || $this->user->isPitchOwner($pitch->user_id) || $this->user->isAdmin()))
    ):?>
            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="#" class="solution-link-menu" data-comment-to="#<?=$solution->num?>">Комментировать</a></li>
        <?php endif;?>
            <?php if($this->user->isLoggedIn()):?>
            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="/solutions/warn/<?=$solution->id?>.json" class="warning" data-solution-id="<?=$solution->id?>">Пожаловаться</a></li>
            <?php endif;?>
            <?php if($this->user->isPitchOwner($pitch->user_id)):?>
                <?php if (($pitchesCount<1) && (!$selectedsolution)): ?>
                    <li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">
                        <a class="select-winner" href="/solutions/select/<?=$solution->id?>.json" data-solutionid="<?=$solution->id?>" data-user="<?=$this->user->getFormattedName($solution->user->first_name, $solution->user->last_name)?>" data-num="<?=$solution->num?>" data-userid="<?=$solution->user->id?>">Назначить победителем</a>
                    </li>
                <?php elseif (($pitch->awarded != $solution->id) && (($pitch->status == 1) or ($pitch->status == 2)) && ($pitch->awarded != 0)): ?>
                    <li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">
                        <a class="select-multiwinner" href="/pitches/setnewwinner/<?=$solution->id?>" data-solutionid="<?=$solution->id?>" data-user="<?=$this->user->getFormattedName($solution->user->first_name, $solution->user->last_name)?>" data-num="<?=$solution->num?>" data-userid="<?=$solution->user->id?>">Назначить <?=$pitchesCount+2?> победителя</a>
                    </li>
                <?php endif; ?>
            <?php endif;?>

            <?php if(($this->user->isLoggedIn()) && ($solution->hidden == 1) && ($this->user->isPitchOwner($pitch->user_id))): ?>
            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="/solutions/unhide/<?=$solution->id?>.json" class="unhide-item" data-to="<?=$solution->num?>">Сделать видимой</a></li>
            <?php endif;?>
            <?php if(($this->user->isSolutionAuthor($solution->user_id)) || ($this->user->isAdmin())):?>
            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a class="delete-solution" data-solution="<?=$solution->id?>" data-solution_num="<?=$solution->num?>" href="/solutions/delete/<?=$solution->id?>.json">Удалить</a></li>
            <?php endif;?>

        </ul>
    </div>
    <?php endif;?>
</li>
<?php endforeach;?>