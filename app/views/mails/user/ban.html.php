<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title></title>
</head>
<body style="margin: 0; padding: 0;">
<img src="http://www.godesigner.ru/img/logo_original-01.png" width="200">

<table width="800">
    <tr><td width="5"></td><td width="30"></td><td>
        <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;">ЗДРАВСТВУЙТЕ <?=mb_strtoupper($user['first_name'], 'utf-8')?>!</span><br>
    </td></tr>
    <tr><td colspan="3" height="40"></td></tr>
    <tr><td width="5"></td><td valign="top">



    </td>
        <td>
            <span style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;">
                Вы не можете оставлять комментарии в течение <?=$term?> <?=$this->numInflector->formatString($term, array('string' => array('first' => 'день', 'second' => 'дня', 'third' => 'дней')))?>. Мы были вынуждены принять меры, поскольку были нарушены <a href="http://www.godesigner.ru/answers/view/37" target="_blank">правила участия</a>. Тем не менее вы можете загружать решения и участвовать в проектах. В случае несоблюдения правил в следующий раз ваш аккаунт может быть заблокирован или ликвидирован.
            </span><br/>
        </td></tr>
    <tr>
        <td colspan="3" height="100"></td>
    </tr>
</table>

</body></html>