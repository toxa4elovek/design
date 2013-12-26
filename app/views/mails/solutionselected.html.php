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
            <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;">ЗДРАВСТВУЙТЕ <?=mb_strtoupper($user->first_name, 'utf-8')?>!</span><br>
            <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;">ВАШЕ РЕШЕНИЕ СТАЛО ПОБЕДИТЕЛЕМ!</span>
        </td></tr>
        <tr><td colspan="3" height="40"></td></tr>
        <tr><td width="5"></td><td valign="top">



        </td>
            <td>
                <a style="color: #ff585d; line-height: 17px; font-size: 20px; font-weight: bold; font-family: Arial, sans-serif;text-decoration: none;" href="http://www.godesigner.ru/pitches/view/<?=$pitch['id']?>"><?=$pitch['title']?> <?=(int) $pitch['price']?> Р.-</a><br/>
                <span style="color: #AEAEAE; line-height: 17px; font-size: 11px; font-family: Arial, sans-serif;"><?=$this->view()->render(array('template' => 'pitch-info'), array('pitch' => $pitch));?></span><br/>
                <span style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;">Ваше решение <a  target="_blank" style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;text-decoration:underline;" href="http://www.godesigner.ru/pitches/viewsolution/<?=$solution['id']?>">#<?=$solution['num']?></a> стало победителем. Мы поздравляем вас! У заказчика есть право на внесение 3 поправок до запроса исходных файлов. Сейчас вы можете перейти к <a style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;text-decoration:underline;" href="http://www.godesigner.ru/users/step1/<?=$solution['id']?>" target="_blank">процессу вознаграждения</a> или ознакомиться с заключительным этапом в разделе <a style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;text-decoration:underline;" href="http://www.godesigner.ru/answers/view/62" target="_blank">помощь</a>.
                </span><br/>
        </td></tr>
        <tr>
            <td colspan="3" height="40"></td>
        </tr>
    </table>
</body></html>