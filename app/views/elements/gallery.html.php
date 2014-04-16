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
    <div class="solution_menu" style="display: none;">
        <ul class="solution_menu_list" style="position:absolute;z-index:6;">
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
            <?php if((!$selectedsolution) && ($this->user->isPitchOwner($pitch->user_id))):?>
            <li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;"><a class="select-winner" href="/solutions/select/<?=$solution->id?>.json" data-solutionid="<?=$solution->id?>" data-user="<?=$this->user->getFormattedName($solution->user->first_name, $solution->user->last_name)?>" data-num="<?=$solution->num?>" data-userid="<?=$solution->user->id?>">Назначить победителем</a></li>
            <?php endif;?>

            <?php if(($this->user->isLoggedIn()) && ($solution->hidden == 1) && ($this->user->isPitchOwner($pitch->user_id))): ?>
            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="/solutions/unhide/<?=$solution->id?>.json" class="unhide-item" data-to="<?=$solution->num?>">Сделать видимой</a></li>
            <?php endif;?>
            <?php if(($this->user->isSolutionAuthor($solution->user_id)) || ($this->user->isAdmin())):?>
            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a class="delete-solution" data-solution="<?=$solution->id?>" data-solution_num="<?=$solution->num?>" href="/solutions/delete/<?=$solution->id?>.json">Удалить</a></li>
            <?php endif;?>

        </ul>
    </div>
</li>
<?php endforeach;?>