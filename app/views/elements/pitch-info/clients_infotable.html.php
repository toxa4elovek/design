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
        <?php if($pitch->billed == 1): ?>
            <td width="15"></td>
            <?php if ($pitch->status == 0):?>
            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                <span class="regular">Мнение эксперта <a href="http://www.godesigner.ru/answers/view/66" target="_blank">(?)</a></span>
                <a class="order-button" href="/pitches/addon/<?= $pitch->id?>?click=experts-checkbox">Заказать</a>
            </td>
            <?php else: ?>
            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                <span class="regular">Заказчик:</span>&nbsp;&nbsp;<?=$this->html->link($this->user->getFormattedName($pitch->user->first_name, $pitch->user->last_name), array('users::view', 'id' => $pitch->user->id), array('class' => 'client-linknew'))?>
            </td>
            <?php endif; ?>
        <?php endif; ?>
    </tr>
    <tr>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Просмотры брифа:</span>&nbsp;<span class="pitch-info-text"><?=$pitch->views?></span>
        </td>
        <td width="15"></td>
        <?php if (($pitch->pinned == 0) && ($pitch->status == 0)):?>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Прокачать бриф <a href="http://www.godesigner.ru/answers/view/67" target="_blank">(?)</a></span>
            <a class="order-button" href="/pitches/addon/<?= $pitch->id?>?click=pinned">Заказать</a>
        </td>
        <?php else: ?>
            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                <?php if($pitch->pinned == 1): ?>
                    <span class="regular">Бриф прокачен</span>
                <?php else: ?>
                    <span class="regular">Бриф не прокачен</span>
                <?php endif;?>
            </td>
        <?php endif; ?>
        <?php if($pitch->billed == 1): ?>
            <td width="15"></td>
            <?php if ($pitch->status == 0):?>
            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                <?php if ($pitch->brief == 1):?>
                    <span class="regular">Бриф заполнен:</span>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->html->link($this->user->getFormattedName($pitch->user->first_name, $pitch->user->last_name), array('users::view', 'id' => $pitch->user->id), array('class' => 'client-linknew'));?>
                <?php else:?>
                    <span class="regular">Заполнить бриф <a href="http://www.godesigner.ru/answers/view/68" target="_blank">(?)</a></span>
                    <a class="order-button" href="/pitches/addon/<?= $pitch->id?>?click=phonebrief">Заказать</a>
                <?php endif;?>
            </td>
            <?php else: ?>
            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                <span class="regular">Был online:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=date('d.m.Y', $pitch->user->getLastActionTime())?> в <?=date('H:i', $pitch->user->getLastActionTime()) ?></span>
            </td>
            <?php endif; ?>
        <?php endif; ?>
    </tr>
    <tr>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Количество участников:</span>&nbsp;<span class="pitch-info-text"><?=$pitch->applicantsCount;?></span>
        </td>
        <td width="15"></td>
                <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Спросить:</span>
            <a class="order-button" href="mailto:?&subject=<?php echo $pitch->title?>
&amp;body=<?php echo 'Привет,%0D%0A%0D%0Aя сейчас создаю ' . $pitch->title . ' (http://www.godesigner.ru/pitches/view/' . $pitch->id . '). Мне бы было приятно, если бы кто-то помог мне с выбором. Какие идеи тебе нравятся больше всего?%0D%0A
Спасибо за ответ!%0D%0A%0D%0A
' . $pitch->user->first_name . ' ' . $pitch->user->last_name .'.'?>" style="width: 178px; top: -3px;">Совет друга (бесплатно)</a>
        </td>
        <?php if($pitch->billed == 1): ?>
            <td width="15"></td>
            <?php if ($pitch->status == 0):?>
            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                <span class="regular">Продлить срок <a href="http://www.godesigner.ru/answers/view/88" target="_blank">(?)</a></span>
                <a class="order-button" href="/pitches/addon/<?= $pitch->id?>?click=prolong">Заказать</a>
            </td>
            <?php else: ?>
            <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                <span class="regular">Бриф заполнен:</span>&nbsp;
                <?php echo ($pitch->brief == 1) ? '<a class="client-linknew" href="/answers/view/68" target="_blank">GoDesigner</a>' : $this->html->link($this->user->getFormattedName($pitch->user->first_name, $pitch->user->last_name), array('users::view', 'id' => $pitch->user->id), array('class' => 'client-linknew')); ?>
            </td>
            <?php endif; ?>
        <?php endif; ?>
    </tr>
    <tr>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <span class="regular">Гонорар:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$this->moneyFormatter->formatMoney($pitch->price, array('suffix' => 'р.-'))?><?php echo ($pitch->guaranteed == 1) ? ' гарантированы' : ''; ?></span>
        </td>
        <td width="15"></td>
        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
            <?php if ($pitch->published == 0 && $pitch->billed == 1 && $pitch->brief == 1): ?>
                <span class="regular">Ожидайте звонка</span>
            <?php elseif ($pitch->published == 0 && $pitch->billed == 0 && $pitch->brief == 0): ?>
                <span class="regular">Ожидание оплаты</span>
            <?php else: ?>
                <?=$this->view()->render(array('element' => 'pitch-info/timer'), array('pitch' => $pitch))?>
            <?php endif; ?>
        </td>
        <?php if($pitch->billed == 1): ?>
            <td width="15"></td>
            <?php if (($pitch->guaranteed == 0) && ($pitch->status == 0) && ($pitch->type != 'company_project')):?>
                <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                    <span class="regular">Гарантировать проект <a href="http://www.godesigner.ru/answers/view/79" target="_blank">(?)</a></span>
                    <a class="order-button" href="/pitches/addon/<?= $pitch->id?>?click=guarantee">Заказать</a>
                </td>
            <?php else: ?>
                <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                    <?php if(($pitch->type == 'company_project') && ($pitch->status == 1) && (!$pitch->awarded)):?>
                        <a class="order-button" style="width: 252px; top: -3px; float: left;" href="#">Отказаться от всех решений</a>
                    <?php else :?>
                        <?php if($pitch->guaranteed == 1):?>
                        <span class="regular">Проект гарантирован</span>
                        <?php else: ?>
                        <span class="regular">Проект не гарантирован</span>
                        <?php endif;?>
                    <?php endif;?>
                </td>
            <?php endif; ?>
        <?php endif; ?>
    </tr>
</table>
