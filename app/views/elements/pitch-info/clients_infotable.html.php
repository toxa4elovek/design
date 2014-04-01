<table class="pitch-info-table" border="1">
    <tr>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1">
            <span class="regular">Решений:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$pitch->ideas_count ?></span>
        </td>
        <td width="15"></td>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Инструменты заказчика:</span>
            <a class="order-button" href="/answers/view/78">Просмотреть</a>
        </td>
        <td width="15"></td>
        <?php if ($pitch->status == 0):?>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Мнение эксперта <a href="http://www.godesigner.ru/answers/view/66" target="_blank">(?)</a></span>
            <a class="order-button" href="/pitches/addon/<?= $pitch->id?>?click=experts-checkbox">Заказать</a>
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
            <?php if ($pitch->brief == 1):?>
                <span class="regular">Бриф заполнен:</span>&nbsp;&nbsp;&nbsp;&nbsp;<a class="client-linknew" href="/answers/view/68" target="_blank">GoDesigner</a>
            <?php else:?>
                <span class="regular">Заполнить бриф <a href="http://www.godesigner.ru/answers/view/68" target="_blank">(?)</a></span>
                <a class="order-button" href="/pitches/addon/<?= $pitch->id?>?click=phonebrief">Заказать</a>
            <?php endif;?>
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
            <span class="regular">Продлить срок:</span>
            <a class="order-button" href="/pitches/addon/<?= $pitch->id?>?click=prolong">Заказать</a>
        </td>
        <?php else: ?>
        <td width="255" height="25"></td>
        <?php endif; ?>
    </tr>
</table>
