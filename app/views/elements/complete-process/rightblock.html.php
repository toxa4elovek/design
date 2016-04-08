<div class="right_block<?php echo ($type == 'designer') ? '' : ' for-client'; ?>">
    <div class="user_photo">
        <?php if($solution->pitch->category_id == 7):?>
            <a href="/users/step<?=$step?>" style="width:147px;height:104px;background-color:#efefef;display:block;color:#666666;text-decoration:none;font-weight:bold;padding-top:16px;padding: 16px;">
                <?php if(mb_strlen(trim($solution->description)) > 100):?>
                <?=mb_substr(trim($solution->description), 0, 100, 'UTF-8')?>
                <?php else:?>
                <?=trim($solution->description)?>
                <?php endif?>
            </a>
        <?php else:?>
            <a target="_blank" href="/pitches/viewsolution/<?=$solution->id?>"><img width="180" height="135" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>" /></a>
        <?php endif?>
        <img src="/img/<?=$solution->rating?>-rating.png" alt="" style="margin: 10px 0 0 0;" />
        <img src="/img/looked.png" style="margin: 10px 0 0 37px;" /><span><?=$solution->views?></span>
        <img src="/img/like.png" style="margin: 6px 0 0 0px;" /><span><?=$solution->likes?></span>
    </div>
    <div class="info ">
        <span class="bold supplement"><?=$this->user->getFormattedName($solution->user->first_name, $solution->user->last_name)?></span>
        <span class="supplement"><a href="/pitches/view/<?=$solution->pitch->id?>" target="_blank"><?=$solution->pitch->title?></a></span>
        <!--span class="bold supplement">Победил</span>
        <span class="supplement"><?=date('d.m.Y', strtotime($solution->change))?></span-->
        <span class="supplement">Дата окончания проекта <?php
            if($solution->pitch->blank == 0):
            echo date('d.m.Y', strtotime($solution->pitch->awardedDate));
            else:
            echo date('d.m.Y', strtotime($solution->pitch->started));
            endif;
            ?>. в <?php
            if($solution->pitch->blank == 0):
                echo date('H:i', strtotime($solution->pitch->awardedDate));
            else:
                echo date('H:i', strtotime($solution->pitch->started));
            endif;
            ?></span>
        <?php if ($solution->pitch->category_id == 7):?>
            <?php if($type == 'designer'):?>
                <span class="supplement">Со дня определения победителя у заказчика есть 10 дней для получения полного объема работ, запрошенного в брифе.</span><br>
            <?php else: ?>
                <span class="supplement">Со дня определения победителя у вас есть 10 дней для получения полного объема работ, запрошенного в брифе. Если вас все устраивает, пожалуйста, завершите проект.</span>
            <?php endif; ?>
        <?php else: ?>
        <span class="supplement">Ознакомьтесь с
            <?php if($type == 'designer'):?>
                <a href="/answers/view/54">инструкциями</a>
            <?php else:?>
                <a href="/answers/view/63">инструкциями</a>
            <?php endif?>
    заключительного этапа.</span>
        <span class="supplement">Со дня определения победителя у вас есть <?php
            $timelimit = $solution->pitch->category->default_timelimit;
            if(($solution->pitch->category_id == 20) && ($timelimit < 5)) {
                $timelimit = 5;
            }
            echo $timelimit;
            ?> дней, чтобы доработать макеты <?php if($solution->pitch->category_id == 1):?>(3 поправки)<?php endif?> и исходники.
        <?php if(!$this->user->isPitchOwner($solution->pitch->user_id)):?>
            <?php if(mt_rand(0, 1)):?>
                <br><br><a href="https://www.godesigner.ru/answers/view/101" target="_blank" class="supplement">Если заказчик пропал на завершительном этапе, что делать?</a>
            <?php else: ?>
                <br><br><a href="https://www.godesigner.ru/answers/view/105" target="_blank" class="supplement">Как подготовить исходники</a>
            <?php endif ?>
        <?php endif ?>
            <?php
            if(($step < 3) && ($this->user->isPitchOwner($solution->pitch->user_id))): echo ' Для начала вам нужно получить джипеги, внести правки и одобрить макеты.'; endif;?></span>
            <?php endif; ?>
    </div>
</div>