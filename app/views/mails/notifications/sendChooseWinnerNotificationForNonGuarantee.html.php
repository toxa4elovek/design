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
        </td></tr>
    <tr><td colspan="3" height="40"></td></tr>
    <tr><td width="5"></td>
        <td valign="top"></td>
        <td>
            <span style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;">
                Приём работ в проекте <a href="https://www.godesigner.ru/pitches/view/<?= $project->id ?>">&laquo;<?= $project->title ?>&raquo;</a> завершен.
                У вас есть 4 дня на выбор лучшего решения, либо на отказ от всех решений.
                Как это сделать, написано в разделе <a href="https://www.godesigner.ru/answers/view/71">Помощи: Как вернуть деньги?</a>
            </span><br/>
        </td>
    </tr>
    <tr>
        <td colspan="3" height="100"></td>
    </tr>
</table>

</body></html>