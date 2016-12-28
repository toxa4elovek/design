<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title></title>
</head>
<body style="margin: 0; padding: 0;">
    <img src="https://godesigner.ru/img/logo_original-01.png" width="200">

    <table>
        <tr>
            <td width="5"></td><td width="30"></td>
            <td>
                <a style="color: #ff5360; line-height: 23px; font-size: 20px; font-weight: bold; font-family: Arial, sans-serif;text-decoration: none;" href="https://godesigner.ru/pitches/view/<?=$pitch->id?>"><?=$pitch->title?></a><br/>
                <span style="color: #CCCCCC; line-height: 23px; font-size: 12px; font-family: Arial, sans-serif;"><?=$this->view()->render(array('template' => 'pitch-info'), array('pitch' => $pitch));?></span><br/>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="5"></td>
        </tr>
        <tr>
            <td width="5"></td><td width="30"></td>
            <td>
                <span style="color: #737171; line-height: 23px; font-size: 14px; font-family: Arial, sans-serif;">
                Здравствуйте, <?=$user->first_name?>!<br>
                Эксперт оставил комментарий к вашему проекту. Ознакомиться с ним вы можете ниже, или перейти по ссылке:<br>
                <a href="https://godesigner.ru/pitches/view/<?=$pitch->id?>#comment-anchor">https://godesigner.ru/pitches/view/<?=$pitch->id?></a>
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="30"></td>
        </tr>
        <tr>
            <td width="5"></td><td width="30"></td>
            <td>
                <span style="color: #666666; line-height: 23px; font-size: 14px; font-family: Arial, sans-serif;">— <?=$text?></span><br/>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="40"></td>
        </tr>
    </table>
</body></html>