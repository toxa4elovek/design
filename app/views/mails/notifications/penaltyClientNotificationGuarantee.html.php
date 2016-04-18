<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title></title>
</head>
<body style="margin: 0; padding: 0;">
<img src="https://www.godesigner.ru/img/logo_original-01.png" width="200">

<table width="800">
    <tr><td width="5"></td><td width="30"></td><td>
            <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;">ЗДРАВСТВУЙТЕ <?=mb_strtoupper($user->first_name, 'utf-8')?>!</span><br>
            <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;">ВЫ УСПЕШНО ПОПОЛНИЛИ СЧЁТ.</span>
        </td></tr>
    <tr><td colspan="3" height="40"></td></tr>
    <tr><td width="5"></td>
        <td valign="top"></td>
        <td>
            <span style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;">

                <?= date('d.m.Y H:i:s', $time) ?> истекает время, отведенное на выбор победителя. Пожалуйста, активизируйтесь на сайте и примите решение.<br><br>
В противном случае вы сможете номинировать работу, перейти к завершительному этапу и вносить правки только после оплаты штрафа из расчета 25 руб./час. Подробнее – в <a href="https://www.godesigner.ru/answers/view/53" target="_blank">Регламенте</a>.


            </span><br/>
        </td>
    </tr>
    <tr>
        <td colspan="3" height="100"></td>
    </tr>
</table>

</body></html>