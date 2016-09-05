<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title></title>
</head>
<body style="margin: 0; padding: 0;">
    <img src="https://www.godesigner.ru/img/logo_original-01.png" width="200">

    <table>
        <tr><td colspan="3" height="15"></td></tr>
        <tr><td width="25"></td><td valign="top"></td>
            <td>
                <a style="color: #ff585d; line-height: 17px; font-size: 20px; font-weight: bold; font-family: Arial, sans-serif;text-decoration: none;" href="https://www.godesigner.ru/pitches/view/<?=$pitch->id?>"><?=$pitch->title?></a><br/>
                <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;"><?=$this->view()->render(array('template' => 'pitch-info'), array('pitch' => $pitch));?></span><br/><br/>
                <span style="color: #666666; line-height: 23px; font-size: 14px; font-family: Arial, sans-serif;">
                    Здравствуйте, <?=$user->first_name?>!<br>
                    GoDesigner оставил комментарий.<br><br>
                    <?php echo $comment->text?></span><br/>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="40"></td>
        </tr>
    </table>
</body></html>