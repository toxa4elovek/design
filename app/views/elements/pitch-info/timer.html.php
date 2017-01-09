<?php if ($pitch->status == 0):?>
    <span class="regular">Приём работ:</span>&nbsp;&nbsp;&nbsp;
    <span class="pitch-info-text" style="color: #ff585d; display: inline-block;" id="countdown" data-deadline="<?=strtotime($pitch->finishDate);?>">
        <span class="days">00</span>
        <span class="timeRefDays">дн</span>
        <span class="hours">00</span><span class="timeRefHours">:</span><span class="minutes">00</span><span class="timeRefMinutes">:</span><span class="seconds">00</span><span class="timeRefSeconds"></span>
    </span>
<?php elseif (($pitch->status == 1) && ($pitch->expert == 0) && ($pitch->awarded == 0)):?>
    <?php if ((strtotime($pitch->finishDate) + DAY * 3) > time()):
        $color = '#62a26e;';
    else:
        $color = '#ff585d';
    endif?>
    <span class="regular">Выбор победителя:&nbsp;</span>
    <?php if (($pitch->chooseWinnerFinishDate === '0000-00-00 00:00:00') && (time() > $this->pitch->getChooseWinnerTime($pitch))):?>
    <span class="pitch-info-text" style="color: <?= $color ?>; display: inline-block;"><a href="/answers/view/70" style="color: #ff585d" target="_blank">- <?= $this->pitch->getPenalty($pitch)?> р. штраф</a>&nbsp;<a href="/answers/view/70" style="width: 16px;
height: 13px;
color: #6b92a2;
font-family: Arial;
font-size: 14px;
font-weight: 400;" target="_blank">(?)</a></span>
    <?php else: ?>&nbsp;&nbsp;
    <span class="pitch-info-text" style="color: <?= $color ?>; display: inline-block;" id="countdown" data-deadline="<?= $this->pitch->getChooseWinnerTime($pitch) ?>">
        <span class="days">00</span>
        <span class="timeRefDays">дн</span>
        <span class="hours">00</span><span class="timeRefHours">:</span><span class="minutes">00</span><span class="timeRefMinutes">:</span><span class="seconds">00</span><span class="timeRefSeconds"></span>
    </span>
    <?php endif?>
<?php elseif (($pitch->status == 1 && $pitch->awarded == 0) && ($pitch->expert == 1) && ($allowSelect = $this->pitch->expertOpinion($pitch->id)) && ($allowSelect == strtotime($pitch->finishDate))):?>
    <span class="regular">Ожидание эксперта:</span>&nbsp;&nbsp;&nbsp;
    <span class="pitch-info-text" style="color: #ff585d; display: inline-block;" id="countdown" data-deadline="<?=$allowSelect + DAY * 2;?>">
        <span class="days">00</span>
        <span class="timeRefDays">дн</span>
        <span class="hours">00</span><span class="timeRefHours">:</span><span class="minutes">00</span><span class="timeRefMinutes">:</span><span class="seconds">00</span><span class="timeRefSeconds"></span>
    </span>
<?php elseif (($pitch->status == 1 && $pitch->awarded == 0) && ($pitch->expert == 1) && ($allowSelect = $this->pitch->expertOpinion($pitch->id)) && ($allowSelect != strtotime($pitch->finishDate))):?>
    <?php if ($this->pitch->getChooseWinnerTime($pitch) > time()):
        $color = '#62a26e;';
    else:
        $color = '#ff585d';
    endif?>
    <span class="regular">Выбор победителя:</span>&nbsp;
    <?php if (($pitch->chooseWinnerFinishDate === '0000-00-00 00:00:00') && (time() > $this->pitch->getChooseWinnerTime($pitch))):?>
        <span class="pitch-info-text" style="color: <?= $color ?>; display: inline-block;"><a href="/answers/view/70" style="color: #ff585d" target="_blank">- <?= $this->pitch->getPenalty($pitch)?> р. штраф</a>&nbsp;<a href="/answers/view/70" style="width: 16px;
height: 13px;
color: #6b92a2;
font-family: Arial;
font-size: 14px;
font-weight: 400;" target="_blank">(?)</a></span>
    <?php else: ?>&nbsp;&nbsp;
    &nbsp;&nbsp;<span class="pitch-info-text" style="color: <?= $color ?>; display: inline-block;" id="countdown" data-deadline="<?=$this->pitch->getChooseWinnerTime($pitch);?>">
        <span class="days">00</span>
        <span class="timeRefDays">дн</span>
        <span class="hours">00</span><span class="timeRefHours">:</span><span class="minutes">00</span><span class="timeRefMinutes">:</span><span class="seconds">00</span><span class="timeRefSeconds"></span>
    </span>
<?php endif?>
<?php elseif (($pitch->status == 2) || ($pitch->status == 1 && $pitch->awarded > 0)):?>
    <span class="pitch-info-text">Победитель выбран</span>
<?php endif?>
