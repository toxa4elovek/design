<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title></title>
</head>
<body style="margin: 0; padding: 0;">
    <img src="https://godesigner.ru/img/logo_original-01.png" width="200">

    <table>
        <tr><td width="5"></td><td width="30"></td><td>
            <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;">ЗДРАВСТВУЙТЕ <?=mb_strtoupper($user->first_name, 'utf-8')?>!</span><br>
            <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;">ДОБАВЛЕНО РЕШЕНИЕ ИЛИ КОММЕНТАРИЙ:</span>
        </td></tr>
        <tr><td colspan="3" height="40"></td></tr>
        <?php foreach ($blocks as $block):?>
        <tr><td width="5"></td><td valign="top">



        </td>
            <td>
                <a style="color: #ff585d; line-height: 17px; font-size: 20px; font-weight: bold; font-family: Arial, sans-serif;text-decoration: none;" href="https://godesigner.ru/pitches/view/<?=$block['pitch']->id?>"><?=$block['pitch']->title?></a><br/>
                <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;"><?=$this->view()->render(['template' => 'pitch-info'], ['pitch' => $pitch]);?></span><br/>
                <span style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;">
                <?php echo $block['solutions']?>
                <br><br>
                <?php echo $block['comments']?>
                </span><br/>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="40"></td>
        </tr>
        <?php endforeach;?>
        <tr>
            <td width="5"></td><td valign="top"></td>
        </tr>
    </table>
</body></html>