<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title></title>
</head>
<body style="margin: 0; padding: 0;">
<img src="https://godesigner.ru/img/logo_original-03.png" width="200">

<table>
    <tr><td width="5"></td><td width="30"></td><td>
        <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;">ЗДРАВСТВУЙТЕ <?=mb_strtoupper($user->first_name, 'utf-8')?>!</span><br>
        <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;">ДОБАВЛЕН НОВЫЙ ПРОЕКТ НА МОДЕРАЦИЮ:</span>
    </td></tr>
    <tr><td colspan="3" height="40"></td></tr>
    <tr><td width="5"></td><td valign="top">



    </td>
        <td>
            <a style="color: #648fa4; line-height: 17px; font-size: 20px; font-weight: bold; font-family: Arial, sans-serif;text-decoration: none;" href="http://cp.godesigner.ru/pitches/edit/<?=$pitch->id?>"><?=$pitch->title?></a><br/>
        </td></tr>
    <tr>
        <td colspan="3" height="40"></td>
    </tr>
    <tr>
        <td width="5"></td><td valign="top"></td>
    </tr>
</table>
</body></html>
