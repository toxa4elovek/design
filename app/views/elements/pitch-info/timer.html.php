<?php if($pitch->status == 0):?>
    <span class="regular">Прием работ:</span>&nbsp;&nbsp;&nbsp;
    <span class="pitch-info-text" style="color: #ff585d; display: inline-block;" id="countdown" data-deadline="<?=strtotime($pitch->finishDate);?>">
        <span class="days">00</span>
        <span class="timeRefDays">дн</span>
        <span class="hours">00</span><span class="timeRefHours">:</span><span class="minutes">00</span><span class="timeRefMinutes">:</span><span class="seconds">00</span><span class="timeRefSeconds"></span>
    </span>
<?php elseif(($pitch->status == 1) && ($pitch->expert == 0) && ($pitch->awarded == 0)):?>
    <span class="regular">Выбор победителя:</span>&nbsp;&nbsp;&nbsp;
    <span class="pitch-info-text" style="color: #ff585d; display: inline-block;" id="countdown" data-deadline="<?=strtotime($pitch->finishDate) + DAY * 4;?>">
        <span class="days">00</span>
        <span class="timeRefDays">дн</span>
        <span class="hours">00</span><span class="timeRefHours">:</span><span class="minutes">00</span><span class="timeRefMinutes">:</span><span class="seconds">00</span><span class="timeRefSeconds"></span>
    </span>
<?php elseif(($pitch->status == 1) && ($pitch->expert == 1) && ($allowSelect = $this->pitch->expertOpinion($pitch->id)) && ($allowSelect == strtotime($pitch->finishDate))):?>
    <span class="regular">Ожидание эксперта:</span>&nbsp;&nbsp;&nbsp;
    <span class="pitch-info-text" style="color: #ff585d; display: inline-block;" id="countdown" data-deadline="<?=$allowSelect + DAY * 2;?>">
        <span class="days">00</span>
        <span class="timeRefDays">дн</span>
        <span class="hours">00</span><span class="timeRefHours">:</span><span class="minutes">00</span><span class="timeRefMinutes">:</span><span class="seconds">00</span><span class="timeRefSeconds"></span>
    </span>
<?php elseif(($pitch->status == 1) && ($pitch->expert == 1) && ($allowSelect = $this->pitch->expertOpinion($pitch->id)) && ($allowSelect != strtotime($pitch->finishDate))):?>
    <span class="regular">Выбор победителя:</span>&nbsp;&nbsp;&nbsp;
    <span class="pitch-info-text" style="color: #ff585d; display: inline-block;" id="countdown" data-deadline="<?=$allowSelect + DAY * 4;?>">
        <span class="days">00</span>
        <span class="timeRefDays">дн</span>
        <span class="hours">00</span><span class="timeRefHours">:</span><span class="minutes">00</span><span class="timeRefMinutes">:</span><span class="seconds">00</span><span class="timeRefSeconds"></span>
    </span>
<?php elseif(($pitch->status == 2) || ($pitch->status == 1 && $pitch->awarded > 0)):?>
    <span class="pitch-info-text">Победитель выбран</span>
<?php endif?>
