<?php
    if($pitch->moneyback) {
        $moneyInAct = $pitch->total - $pitch->price;
    }else {
        $moneyInAct = $pitch->total;
    }
?>
<img src="<?php echo LITHIUM_APP_PATH;?>/webroot/img/logo-01.png" width="180" style="margin-bottom:40px;" />
<table style="" width="550" cellspacing="0" border="0" cellpadding="0">
<tr><td colspan="2" style="border-bottom:1px solid;"><h2 style="margin:0">Акт № <?=$pitch->id?> от <?php if($pitch->totalFinishDate != '0000-00-00 00:00:00'):?>
                <?=date('d.m.Y', strtotime($pitch->totalFinishDate));?>
            <?php else: ?>
                <?=date('d.m.Y', strtotime($pitch->finishDate));?>
            <?php endif?></h2></td></tr>
<tr><td style="padding-bottom:1em;"></td></tr>
<tr><td valign="top" style="padding-right:1em;"><b>Исполнитель:</b></td><td valign="top">ООО "КРАУД&nbsp;МЕДИА", 199397, г.&nbsp;Санкт-Петербург, ул.&nbsp;Беринга, д.&nbsp;27, ИНН&nbsp;7801563047, КПП&nbsp;780101001</td></tr>
<tr><td height="30"></td></tr>
<tr><td valign="top" style="padding-right:1em;"><b>Заказчик:</b></td>
    <?php if($bill->individual == 0) :?>
        <td valign="top"><?=$bill->name?>, <?=$bill->address?>, ИНН:&nbsp;<?=$bill->inn?>, КПП:&nbsp;<?=$bill->kpp?></td>
    <?php else: ?>
        <td valign="top"><?=$bill->name?></td>
    <?php endif; ?>
</tr>
<tr><td height="30"></td></tr>
<tr><td valign="top" style="padding-right:1em;"><b>По счету:</b></td><td valign="top">№ <?=$pitch->id?></td></tr>
</table>

<br />
<table style="" width="550" cellspacing="0" cellpadding="1">
<tr height="25">
<td style="border-left:1px solid;border-top:1px solid; text-align:center;" width="25">№</td>
<td style="border-left:1px solid;border-top:1px solid; text-align:center;">Название товара, работ, услуг</td>
<td style="border-left:1px solid;border-top:1px solid; text-align:center;" width="40">Ед. изм.</td>
<td style="border-left:1px solid;border-top:1px solid; text-align:center;" width="40">Кол-во</td>
<td style="border-left:1px solid;border-top:1px solid; text-align:center;" width="70">Цена</td>
<td style="border-left:1px solid;border-top:1px solid;border-right:1px solid; text-align:center;" width="70">Сумма</td>
</tr>
<tr  valign="top">
<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">1</td>
<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">
    <?php if($pitch->moneyback == false):?>Оказание услуг на условиях агентского соглашения, размещённого на сайте
godesigner.ru, за проект № <?=$pitch->id?>. НДС не предусмотрен.<?php else:?>Агентское вознаграждение на условиях агентского соглашения, размещённого на сайте godesigner.ru, за проект (конкурс) № <?=$pitch->id?> . НДС не предусмотрен.
    <?php endif?></td>
<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">шт.</td>
<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">1</td>
<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">
    <?=$money->formatMoney($moneyInAct, array('suffix' => '.00р', 'dropspaces' => true))?>
</td>
<td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;">
    <?=$money->formatMoney($moneyInAct, array('suffix' => '.00р', 'dropspaces' => true))?>
</td>
</tr>
<tr height="25">
<td height="25" colspan="5" style="text-align:right;"><b>Итого:&nbsp;&nbsp;</b></td>
<td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;">
    <?=$money->formatMoney($moneyInAct, array('suffix' => '.00р', 'dropspaces' => true))?>
</td>
</tr>
<tr height="25">
<td height="25" colspan="5" style="text-align:right;"><b>Без НДС:&nbsp;&nbsp;</b></td>
<td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;">---</td>
</tr>
<tr height="25">
<td height="25" colspan="5" style="text-align:right;"><b>Всего к оплате:&nbsp;&nbsp;</b></td>
<td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;">
    <b><?=$money->formatMoney($moneyInAct, array('suffix' => '.00р', 'dropspaces' => true))?></b>
</td>
</tr>
</table>
<p style="">Всего оказано услуг на сумму <?=$money->num2str($moneyInAct)?>.</p>
<p style="">Вышеперечисленные услуги выполнены полностью и в срок. Заказчик претензий по объему, качеству и срокам оказания услуг не имеет.</p>
<br /><br /><br />
<table width="750">
<tr>
<td colspan="3" width="350"><h3>Исполнитель</h3></td>
<td width="25" style="padding:0 2em;"></td>
<td colspan="3" width="350"><h3>Заказчик</h3></td>
</tr>
<tr>
<td colspan="3">Генеральный директор</td>
<td></td>
<td colspan="3"><?=$bill->name?></td>
</tr>
<tr><td height="40"></td></tr>
<tr>
<td width="150" height="50" style="border-bottom:1px solid;text-align:center;padding:0 5em;"></td>
<td width="25" style="padding:0 2em;"></td>
<td width="150" valign="bottom" style="border-bottom:1px solid;text-align:center;padding:0 1em;">/Федченко М. Ю./</td>
<td width="25"></td>
<td width="150" style="border-bottom:1px solid;text-align:center;padding:0 5em;"></td>
<td width="25" style="padding:0 2em;"></td>
<td width="150" style="border-bottom:1px solid;text-align:center;padding:0 1em;"></td>
</tr>
<tr>
<td style="text-align:center;"><sup>подпись</sup></td>
<td></td>
<td style="text-align:center;"><sup>расшифровка подписи</sup></td>
<td></td>
<td style="text-align:center;"><sup>подпись</sup></td>
<td></td>
<td style="text-align:center;"><sup>расшифровка подписи</sup></td>
</tr>
<tr>
<td width="180" height="180">М.П.</td>
<td colspan="2"></td>
<td style="padding:0 2em;"></td>
<td colspan="3">М.П.</td>
</tr>

</table>
