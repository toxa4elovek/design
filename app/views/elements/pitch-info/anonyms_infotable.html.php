<table class="pitch-info-table" border="1">
    <tr>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1">
            <span class="regular">Решений:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$pitch->ideas_count ?></span>
        </td>
        <td width="15"></td>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1">
            <span class="regular">Гонорар:</span>&nbsp;&nbsp;&nbsp;&nbsp;<?php if($this->pitch->isReadyForLogosale($pitch)):?>
                <span class="pitch-info-text" style="color: #999; text-decoration: line-through; "><?=$this->moneyFormatter->formatMoney($pitch->price, array('suffix' => 'р.-'))?></span>&nbsp;&nbsp;<span class="pitch-info-text" style="color: #ff585d">9 500р.-</span>
            <?php else: ?>
                <span class="pitch-info-text"><?=$this->moneyFormatter->formatMoney($pitch->price, array('suffix' => 'р.-'))?><?php echo ($pitch->guaranteed == 1) ? ' гарантированы' : ''; ?></span>
                <?php endif?>
        </td>
        <td width="15"></td>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Заказчик:</span>&nbsp;&nbsp;<?=$this->html->link($this->user->getFormattedName($pitch->user->first_name, $pitch->user->last_name), array('users::view', 'id' => $pitch->user->id), array('class' => 'client-linknew'))?>
        </td>
    </tr>
    <tr>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Просмотры брифа:</span>&nbsp;<span class="pitch-info-text"><?=$pitch->views?></span>
        </td>
        <td width="15"></td>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Мнение эксперта:</span>&nbsp;<span class="pitch-info-text"><?php echo ($pitch->expert == 1) ? 'есть' : 'нет'; ?></span>
        </td>
        <td width="15"></td>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Был online:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text">
                <?php if($pitch->user):?>
                <?=date('d.m.Y', $pitch->user->getLastActionTime())?> в <?=date('H:i', $pitch->user->getLastActionTime()) ?>
                <?php endif?>
            </span>
        </td>
    </tr>
    <tr>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Количество участников:</span>&nbsp;<span class="pitch-info-text"><?=$pitch->applicantsCount;?></span>
        </td>
        <td width="15"></td>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <?=$this->view()->render(array('element' => 'pitch-info/timer'), array('pitch' => $pitch))?>
        </td>
        <td width="15"></td>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Бриф заполнен:</span>&nbsp;
            <?php echo ($pitch->brief == 1) ? '<a class="client-linknew" href="/answers/view/68" target="_blank">GoDesigner</a>' : $this->html->link($this->user->getFormattedName($pitch->user->first_name, $pitch->user->last_name), array('users::view', 'id' => $pitch->user->id), array('class' => 'client-linknew')); ?>
        </td>
    </tr>
</table>
