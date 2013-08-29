<img src="<?php echo LITHIUM_APP_PATH;?>/webroot/img/logo-01.png" width="180" style="margin-bottom:40px;" />
<div style="font-family: Arial, sans-serif;
            font-size: 13px;
            line-height: 25px;
            margin: 0 20px 0 30px;">
<p style="margin: 20px 0;">Отчет ООО «КРАУД МЕДИА» об исполнении агентского поручения по агентскому договору
№ <?=$pitch->id?>, размещённому на сайте godesigner.ru.
</p>

<p style="margin: 20px 0;"><?=date('d.m.Y', strtotime($pitch->totalFinishDate));?><br />
г. Санкт-Петербург<br />
Общество с ограниченной ответственностью «КРАУД МЕДИА», именуемое в дальнейшем Агент, в лице генерального директора Федченко Максима Юрьевича, действующего на основании Устава, предоставляет,
а <?=$bill->name?>, оплативший по банковской карте* с номером транзакции (РРН) 307103472423 , именуемый в дальнейшем Принципал,
принимает настоящий отчет об исполнении агентского поручения по договору, размещённому на сайте godesigner.ru со следующими результатами:
</p>

<p style="margin: 20px 0;">1. C помощью средств сайта godesigner.ru Агентом проведён питч (конкурс) № <?=$pitch->id?> от <?=date('d.m.Y', strtotime($pitch->totalFinishDate));?>,
на сумму <?=$money->formatMoney($pitch->total, array('suffix' => '.00р', 'dropspaces' => true))?> НДС не облагается в соответствии с главой 26.2 Налогового кодекса РФ.
</p>

<p style="margin: 20px 0;">2. Агентское вознаграждение ООО «КРАУД МЕДИА» составляет 892 руб. 00 коп., в том числе 0 руб. 00 коп. за услуги с предоставлением дополнительных опций.
Агент не является плательщиком НДС в соответствии с главой 26.2 Налогового кодекса РФ согласно уведомлению налогового органа.
</p>

<p style="margin: 20px 0;">3. Итого сумма расходов, поступивших от Принципала, составляет 7042 руб. 00 коп. НДС не облагается в соответствии с главой 26.2 Налогового кодекса РФ.</p>

<p style="margin: 20px 0;">Согласно условиям публичной оферты и отсутствием претензий, со стороны Принципала отчет Агента считается утвержденным автоматически, а поручение выполненным надлежащим образом.</p>
<br />
<table width="750" cellspacing="0" border="0" cellpadding="0">
    <tr>
        <td width="220" style="border-bottom:1.5px solid #E8585D;">
            <p style="font-family: Arial, sans-serif; font-size: 14px; line-height: 25px;">Отчет представил от Агента:<br /><br />
            генеральный&nbsp;директор<br />
            ООО&nbsp;«КРАУД&nbsp;МЕДИА»<br /><br />
            Федченко М. Ю.</p>
        </td>
        <td width="30"></td>
        <td width="220" style="border-bottom: 1.5px solid #E8585D;"></td>
        <td width="30"></td>
        <td width="220" style="border-bottom: 1.5px solid #E8585D;"></td>
    </tr>
    <tr>
        <td width="220"><sup style="font-family: Arial, sans-serif; font-size: 12px; color: #999;">ФИО</sup></td>
        <td width="30"></td>
        <td width="220"><sup style="font-family: Arial, sans-serif; font-size: 12px; color: #999;">подпись</sup></td>
        <td width="30"></td>
        <td width="220"><sup style="font-family: Arial, sans-serif; font-size: 12px; color: #999;">место печати</sup></td>
    </tr>
</table>
<p style="font-family: Arial, sans-serif; font-size: 10px; color: #AAA;">* - Согласно договору о переводе средств по платёжным картам №54/12 с «Мастер-Банк» (ОАО)</p>
</div>