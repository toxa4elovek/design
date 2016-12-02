<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title></title>
</head>
<body style="margin: 0; padding: 0;">
<img src="https://www.godesigner.ru/img/logo_original-01.png" width="200">

<table>
    <tr><td width="5"></td><td width="30"></td><td>
            <span style="color: #C7C6C7; line-height: 17px; font-size: 9px; font-family: Arial, sans-serif;">ЗДРАВСТВУЙТЕ, <?=mb_strtoupper($user->first_name, 'utf-8')?>!</span><br>
            <span style="color: #C7C6C7; line-height: 17px; font-size: 9px; font-family: Arial, sans-serif;">НА НАШ САЙТ ДОБАВЛЕН НОВЫЙ ПРОЕКТ:</span>
        </td></tr>
    <tr><td colspan="3" height="40"></td></tr>
    <tr><td width="5"></td><td valign="top">
        </td>
        <td>
            <a style="color: #ff585d; line-height: 17px; font-size: 20px; font-weight: bold; font-family: Arial, sans-serif;text-decoration: none;" href="https://www.godesigner.ru/pitches/details/<?=$pitch->id?>"><?=$pitch->title?></a><br/>
            <span style="color: #C7C6C7; line-height: 17px; font-size: 9px; font-family: Arial, sans-serif;"><?=$this->view()->render(array('template' => 'pitch-info'), array('pitch' => $pitch));?></span><br/>
            <?php if($pitch->private == 1):?>
                <span style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;">Это <a target="_blank" href="https://www.godesigner.ru/answers/view/64">закрытый проект</a> и вам нужно подписать соглашение о неразглашении!</span><br/>
            <?php else:?>
                <span style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;">
                    Поздравляем, вы как победитель GoDesigner приглашены к участию в «ПРЕМИУМ» проекте «<?=$pitch->title?>»!<br/><br/>
                    <?php echo strip_tags($pitch->editedDescription, '<br><br/><p><a><ul><li><ol>')?></span><br/>
            <?php endif?>
        </td></tr>
    <tr>
        <td colspan="3" height="40"></td>
    </tr>
    <tr>
        <td width="5"></td><td valign="top"></td>
        <td height="40">
            <a href="https://www.godesigner.ru/users/unsubscribe<?php echo $user->unsubscribeToken(); ?>" style="color: #7ea0ac; line-height: 17px; font-size: 9px; font-family: Arial, sans-serif;">ОТПИСАТЬСЯ ОТ РАССЫЛКИ.</a><span style="color: #C7C6C7; line-height: 17px; font-size: 9px; font-family: Arial, sans-serif;">ОТПРАВЛЕНО ИЗ ГОЛОВНОГО ОФИСА GODESIGNER.RU, САНКТ-ПЕТЕРБУРГ, РОССИЯ</span>
        </td>
    </tr>
</table>
</body></html>