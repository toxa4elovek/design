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
            <!--span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;"></span><br-->
        </td></tr>
    <tr><td colspan="3" height="40"></td></tr>
    <tr><td width="5"></td><td valign="top"></td>
        <td>
            <span style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;">
                Здравствуйте <?=$user['first_name']?>! Мы блокировали ваш аккаунт на 30 дней и удалили решение #<?= $solution_num ?> из-за несоблюдения правил платформы: <br>
                <a href="http://godesigner.ru/answers/view/37" target="_blank">http://godesigner.ru/answers/view/37</a><br><br>

                <?php if (!empty($explanation)):?>
                    <?=$this->brief->insertHtmlLinkInText($explanation);?>
                    <br><br>
                <?php endif;?>
                <?php if (!is_null($text)):?>
                    «<?=$text;?>»
                    <br><br>
                <?php endif;?>
                <?php if (!is_null($image)):?>
                    <img src="<?=$image;?>">
                    <br><br>
                <?php endif;?>
            </span><br/>
        </td></tr>
    <tr>
        <td colspan="3" height="100"></td>
    </tr>
</table>

</body></html>