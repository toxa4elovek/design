<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title></title>
</head>
<body style="margin: 0; padding: 0;">
<img src="http://www.godesigner.ru/img/logo_original-03.png" width="200">

<table width="800">
    <tr><td width="5"></td><td width="30"></td><td>
        <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;">ДОБАВЛЕН КОММЕНТАРИЙ ПОСЛЕ ЗАВЕРШЕНИЯ ПИТЧА!</span><br>
    </td></tr>
    <tr><td colspan="3" height="40"></td></tr>
    <tr><td width="5"></td><td valign="top">



    </td>
        <td>
            <a style="color: #648fa4; line-height: 17px; font-size: 20px; font-weight: bold; font-family: Arial, sans-serif;text-decoration: none;" href="http://www.godesigner.ru/pitches/view/<?=$pitch->id?>"><?=$pitch->title?></a><br/>
            <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;"><?= mb_strtoupper($pitch->industry . ', срок: ' . $pitch->startedHuman . ', гонорар: ' . (int) $pitch->price . ' Р.-', 'utf-8')?></span><br/>
            <span style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;"><?php echo $comment['text']?></span><br/>
        </td></tr>
    <tr>
        <td colspan="3" height="100"></td>
    </tr>
    <tr>
        <td width="5"></td><td valign="top"></td>
        <td height="40">
            <a href="http://www.godesigner.ru/users/unsubscribe?token=<?= base64_encode($user->id) ?>" style="color: #7ea0ac; line-height: 17px; font-size: 9px; font-family: Arial, sans-serif;">ОТПИСАТЬСЯ ОТ РАССЫЛКИ.</a><br/>
            <span style="color: #AEAEAE; line-height: 17px; font-size: 9px; font-family: Arial, sans-serif;">ОТПРАВЛЕНО ИЗ ГОЛОВНОГО ОФИСА GODESIGNER.RU. САНКТ-ПЕТЕРБУРГ, РОССИЯ</span>
        </td>
    </tr>
</table>

</body></html>