<table style="" width="550" cellspacing="0" border="0" cellpadding="1">
    <tr ><td width="275"><img src="<?php echo LITHIUM_APP_PATH;?>/webroot/img/logo-01.png" width="180"></td>
        <td>ООО "КРАУД МЕДИА"<br/>г. Санкт-Петербург, ул. Беринга, д. 27<br />ИНН: 7728168971<br />Телефон: +7 (812) 648 24 12</td></tr>
    <tr ><td colspan="2" style="text-align:center"><br><br>Образец заполнения платежного поручения</td></tr>
</table>
<br/>
<br/>
<br/>
<table style="" width="550" cellspacing="0" cellpadding="1">
    <tr height="25">
        <td style="border-left:1px solid;border-top:1px solid;" width="180">ИНН 7728168971</td>
        <td style="border-left:1px solid;border-top:1px solid;" width="180">КПП 783502001</td>
        <td style="border-left:1px solid;border-top:1px solid;" width="40">&nbsp;</td>
        <td style="border-left:1px solid;border-top:1px solid;border-right:1px solid;" width="100">&nbsp;</td>
    </tr>

    <tr height="100">
        <td height="25" colspan="2" style="border-left:1px solid;border-top:1px solid;">Получатель:<br>Общество с ограниченной ответственностью «КРАУД МЕДИА»</td>
        <td height="25" style="border-left:1px solid;">Сч. №</td>
        <td height="25" style="border-left:1px solid;border-right:1px solid;text-align:center;">40702810832320000346</td>
    </tr>

    <tr>
        <td height="25" rowspan="2"  height="50" colspan="2" style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid;">Банк получателя:<br>Филиал «Санкт-Петербургский» ОАО «АЛЬФА-БАНК» г. Санкт-Петербург</td>
        <td height="25" style="border-left:1px solid;border-top:1px solid;">БИК</td>
        <td height="25" style="border-left:1px solid;border-top:1px solid;border-right:1px solid;text-align:center;">044030786</td>
    </tr>

    <tr>
        <td height="25" style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid;">Сч. №</td>
        <td height="25" rowspan="2" style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid;border-right:1px solid;text-align:center;">30101810600000000786</td>
    </tr>
</table>
<H2 style="margin-top:50px">СЧЕТ № <?=$pitch->id?> от <?=date('d.m.Y', strtotime($pitch->started))?></H2>
<table cellpadding="10">
    <tr>
        <td valign="top"><h3>Плательщик:</h3></td>
        <td valign="top">
            <?php if ($bill->individual == 1):?>
                <?=$bill->name?>
            <?php else:?>
                <?=$bill->name?>,<br /><?=$bill->address?>,<br />ИНН: <?=$bill->inn?>, КПП: <?=$bill->kpp?>
            <?php endif;?>
        </td>
    </tr>
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
        <td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">Услуги дизайна на условиях агентского соглашения, размещённого на сайте godesigner.ru, за питч (конкурс) № <?=$pitch->id?> . НДС не предусмотрен.</td>
        <td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">шт.</td>
        <td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;">1</td>
        <td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid; text-align:center;"><?=$money->formatMoney($pitch->total, array('suffix' => '.00р', 'dropspaces' => true))?></td>
        <td style="border-left:1px solid;border-top:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;"><?=$money->formatMoney($pitch->total, array('suffix' => '.00р', 'dropspaces' => true))?></td>
    </tr>
    <tr height="25">
        <td height="25" colspan="5" style="text-align:right;"><b>Итого:&nbsp;&nbsp;</b></td>
        <td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;"><?=$money->formatMoney($pitch->total, array('suffix' => '.00р', 'dropspaces' => true))?></td>
    </tr>
    <tr height="25">
        <td height="25" colspan="5" style="text-align:right;"><b>Без НДС:&nbsp;&nbsp;</b></td>
        <td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;">---</td>
    </tr>
    <tr height="25">
        <td height="25" colspan="5" style="text-align:right;"><b>Всего к оплате:&nbsp;&nbsp;</b></td>
        <td height="25" style="border-left:1px solid;border-bottom:1px solid;border-right:1px solid; text-align:center;"><b><?=$money->formatMoney($pitch->total, array('suffix' => '.00р', 'dropspaces' => true))?></b></td>
    </tr>
</table>
<p style="font-weight:bold; margin-top:20px; font-size: 20px; color:red">Внимание!<br/><span style="font-weight:bold;font-size:13px; color: black;">В назначении платежа указывайте точную фразу из столбца название услуги.</span></p>
<p style="font-size:10px;font-weight:bold;">Закрывающие документы вы получите на e-mail сразу после того, как завершите питч. Распечатайте их, подпишите и поставьте печать. Отправьте их нам в двух экземплярах по почте (199397, Россия, Санкт-Петербург, ул. Беринга, д. 27). В ответном письме вы получите оригиналы документов с нашей печатью.</p>
<p style="">Всего наименований 1, на сумму <?=$money->formatMoney($pitch->total, array('suffix' => '.00р', 'dropspaces' => true))?>.</p>
<p style=""><?=$money->num2str($pitch->total)?></p>
<br /><br /><br />
<table>
    <tr>
        <td style="vertical-align:bottom;">Генеральный директор</td>
        <td style="padding:0 5em;"></td>
        <td style="border-bottom:1px solid;text-align:center;vertical-align:bottom;"><img width="120" height="50" src="<?php echo LITHIUM_APP_PATH;?>/webroot/img/sign.png"></td>
        <td style="padding:0 2em;"></td>
        <td style="border-bottom:1px solid;text-align:center;vertical-align:bottom;">
            <div style="position:relative;">
                <div style="position:absolute;right:270px;bottom:210px;">
                    <img width="120" height="auto" src="<?php echo LITHIUM_APP_PATH;?>/webroot/img/godesigner_stamp_trasp.png"></div>
                <div>/Федченко М. Ю./</div>
            </div>
        </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td style="text-align:center;"><sup>подпись</sup></td>
        <td></td>
        <td style="text-align:center;"><sup>расшифровка подписи</sup></td>
    </tr>
</table>