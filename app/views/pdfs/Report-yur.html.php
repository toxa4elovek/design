<img src="<?php echo LITHIUM_APP_PATH;?>/webroot/img/logo-01.png" width="180" style="margin-bottom:40px;" />
<div style="font-family: Arial, sans-serif;
            font-size: 13px;
            line-height: 25px;
            margin: 0 20px 0 30px;">
<p style="margin: 20px 0;">Отчет ООО «КРАУД МЕДИА» об исполнении агентского поручения по агентскому договору
№ <?=$pitch->id?>, размещённому на сайте godesigner.ru.
</p>

<p style="margin: 20px 0;">
    <?php if ($pitch->totalFinishDate != '0000-00-00 00:00:00'):?>
    <?=date('d.m.Y', strtotime($pitch->totalFinishDate));?>
    <?php else: ?>
    <?=date('d.m.Y', strtotime($pitch->finishDate));?>
    <?php endif?>
    <br />
г. Санкт-Петербург<br />
Общество с ограниченной ответственностью «КРАУД МЕДИА», именуемое в дальнейшем Агент, в лице генерального директора Федченко Максима Юрьевича, действующего на основании Устава, предоставляет,
а <?=$bill->name?>, именуемое в дальнейшем Принципал,
принимает настоящий отчет об исполнении агентского поручения по договору, размещённому на сайте godesigner.ru со следующими результатами:
</p>

<p style="margin: 20px 0;">1. C помощью средств сайта godesigner.ru Агентом проведён проект (конкурс) № <?=$pitch->id?> от <?=date('d.m.Y', strtotime($pitch->started));?>,
на сумму <?=$money->formatMoney($pitch->price - $prolongfees, ['suffix' => ' руб. 00 коп.', 'dropspaces' => true])?> НДС не облагается в соответствии с главой 26.2 Налогового кодекса РФ.
</p>

<p style="margin: 20px 0;">2. Агентское вознаграждение ООО «КРАУД МЕДИА» составляет <?=$money->formatMoney($commission + $totalfees - $prolongfees, ['suffix' => ' руб. 00 коп.', 'dropspaces' => true])?>,
в том числе <?=$money->formatMoney($totalfees - $commission - $prolongfees, ['suffix' => ' руб. 00 коп.', 'dropspaces' => true])?> за услуги с предоставлением дополнительных опций.
Агент не является плательщиком НДС в соответствии с главой 26.2 Налогового кодекса РФ согласно уведомлению налогового органа.
</p>

<p style="margin: 20px 0;">3. Итого сумма расходов, поступивших от Принципала, составляет <?=$money->formatMoney($commission + $totalfees - $prolongfees + $pitch->price, ['suffix' => ' руб. 00 коп.', 'dropspaces' => true])?> НДС не облагается в соответствии с главой 26.2 Налогового кодекса РФ.</p>

<?php if ($pitch->moneyback):?>
    <p style="margin: 20px 0;">4. В соответствии с пунктом 7.9. агентского договора, размещённого на сайте godesigner.ru, Принципалу возвращена сумма проекта (конкурса) <?=$money->formatMoney($pitch->price, ['suffix' => ' руб. 00 коп.', 'dropspaces' => true])?> НДС не облагается в соответствии с главой 26.2 Налогового кодекса РФ.</p>
<?php endif?>
<br />
<table width="750" cellspacing="0" border="0" cellpadding="0">
    <tr>
        <td colspan="3" width="360" valign="top">
            <p style="font-family: Arial, sans-serif; font-size: 15px; line-height: 25px;">Отчет принял от принципала:</p></td>
        <td width="30"></td>
        <td colspan="3" width="360" valign="top">
            <p style="font-family: Arial, sans-serif; font-size: 15px; line-height: 25px;">Отчет представил от Агента:<br /><br />
            генеральный&nbsp;директор ООО&nbsp;«КРАУД&nbsp;МЕДИА»<br /><br />
            Федченко М. Ю.</p>
        </td>
    </tr>
    <tr>
        <td width="200" height="180" valign="top" style="border-top: 1.5px solid #E8585D;"><sup style="font-family: Arial, sans-serif; font-size: 12px; color: #999;">ФИО</sup></td>
        <td width="30"></td>
        <td width="130" height="180" valign="top" style="border-top: 1.5px solid #E8585D;"><sup style="font-family: Arial, sans-serif; font-size: 12px; color: #999;">подпись</sup></td>
        <td width="30"></td>
        <td width="200" height="180" valign="top" align="right" style="border-top: 1.5px solid #E8585D;"><sup style="font-family: Arial, sans-serif; font-size: 12px; color: #999;">ФИО</sup></td>
        <td width="30"></td>
        <td width="130" height="180" valign="top" align="right" style="border-top: 1.5px solid #E8585D;"><sup style="font-family: Arial, sans-serif; font-size: 12px; color: #999;">подпись</sup></td>
    </tr>
    <tr>
        <td  colspan="3" width="360" style="border-top: 1.5px solid #E8585D;"><sup style="font-family: Arial, sans-serif; font-size: 12px; color: #999;">место печати</sup></td>
        <td width="30"></td>
        <td  colspan="3" width="360" align="right" style="border-top: 1.5px solid #E8585D;"><sup style="font-family: Arial, sans-serif; font-size: 12px; color: #999;">место печати</sup></td>
    </tr>
</table>
</div>