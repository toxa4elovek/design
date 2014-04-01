<table class="pitch-info-table" border="1">
    <tr>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1">
            <span class="regular">Решений:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$pitch->ideas_count ?></span>
        </td>
        <td width="15"></td>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Заказчик:</span>&nbsp;&nbsp;<?=$this->html->link($this->user->getFormattedName($pitch->user->first_name, $pitch->user->last_name), array('users::view', 'id' => $pitch->user->id), array('class' => 'client-linknew'))?>
        </td>
        <td width="15"></td>
        <?php if ($pitch->status == 0):?>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Был online:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=date('d.m.Y', $pitch->user->getLastActionTime())?> в <?=date('H:i', $pitch->user->getLastActionTime()) ?></span>
        </td>
        <?php else: ?>
        <td width="255" height="25"></td>
        <?php endif; ?>
    </tr>
    <tr>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Просмотры брифа:</span>&nbsp;<span class="pitch-info-text"><?=$pitch->views?></span>
        </td>
        <td width="15"></td>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1">
            <span class="regular">Гонорар:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$this->moneyFormatter->formatMoney($pitch->price, array('suffix' => 'р.-'))?><?php echo ($pitch->guaranteed == 1) ? ' гарантированы' : ''; ?></span>
        </td>
        <td width="15"></td>
        <?php if ($pitch->status == 0):?>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <?= $this->view()->render(array('element' => 'pitch-info/favourite_status'), array('pitch' => $pitch))?>
        </td>
        <?php else: ?>
        <td width="255" height="25"></td>
        <?php endif; ?>
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
        <?php if ($pitch->status == 0):?>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <a class="order-button" style="width: 100%;" href="/pitches/printpitch/<?=$pitch->id?>">Пропечатать бриф</a>
        </td>
        <?php else: ?>
        <td width="255" height="25"></td>
        <?php endif; ?>
    </tr>
</table>
