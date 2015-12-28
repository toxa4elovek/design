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
        <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;">ЗДРАВСТВУЙТЕ <?=mb_strtoupper($user['first_name'], 'utf-8')?>!</span><br>
    </td></tr>
    <tr><td colspan="3" height="40"></td></tr>
    <tr><td width="5"></td><td valign="top"></td>
        <td>
            <span style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;">
                Пожалуйста, соблюдайте правила: <a href="http://godesigner.ru/answers/view/37" target="_blank">http://godesigner.ru/answers/view/37</a><br><br>

                Мы были вынуждены <?php echo (is_null($term)) ? '' : 'приостановить вашу возможность комментирования на ' .
                $term . ' ' . $this->numInflector->formatString($term, array('string' => array('first' => 'день', 'second' => 'дня', 'third' => 'дней'))) .
                ' и ';?>удалить ваш комментарий в связи с несоблюдением правил и <?php switch ($reason) {
                    case 'critique':
                        echo 'публичной критикой.';
                    break;
                    case 'link':
                        echo 'предоставлением ссылок.';
                    break;
                    default:
                        echo 'по следующей причине:<br>';
                    break;
                } ?>
                <br>
                <?php if (!empty($explanation)):?>
                    <?=$explanation;?>
                    <br><br>
                <?php endif;?>
                Если вы заметили плагиат, вовремя подайте жалобу, и мы рассмотрим её в порядке очереди: позвольте нам утрясти все конфликтные вопросы и удалить плагиат: <a href="https://www.godesigner.ru/answers/view/75">https://www.godesigner.ru/answers/view/75</a>
                <br><br>
                <?php if (!is_null($text)):?>
                    «<?php echo $text;?>»
                    <br><br>
                <?php endif;?>
            </span><br/>
        </td></tr>
    <tr>
        <td colspan="3" height="100"></td>
    </tr>
</table>

</body></html>