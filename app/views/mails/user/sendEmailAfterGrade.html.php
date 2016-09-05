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
            <span style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;">Здравствуйте, <?=$user->first_name?>!</span><br>
        </td></tr>
    <tr><td colspan="3" height="40"></td></tr>
    <tr><td width="5"></td>
        <td valign="top"></td>
        <td>
            <span style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;">
                <?php if ((int) $gradeRecord->work_rating > 3):?>
                Вы завершили проект на сайте, но остались недовольны GoDesigner. Напишите на <a href="mailto:team@godesigner.ru">team@godesigner.ru</a>, что не понравилось, и как улучшить сервис? Мы работаем над ошибками и хотим стать лучше. В благодарность примите разовый промокод «<?=$promocode->code?>», который сэкономит 20% сервисного сбора при новом заказе.
                <?php elseif ((int) $gradeRecord->work_rating === 3): ?>
                    Очень жаль, что вы остались недовольны GoDesigner. Мы работаем над своими ошибками и в качестве компенсации дарим 25% скидку на сервисный сбор при создании нового проекта по промокоду «<?=$promocode->code?>». Промокод действителен в течение месяца.<br><br>
                    Пожалуйста, напишите, что не понравилось, и как улучшить сервис?
                <?php else:?>
                    Очень жаль, что вы остались недовольны GoDesigner. Мы работаем над своими ошибками и в качестве компенсации дарим 50% скидку на сервисный сбор при создании нового проекта по промокоду «<?=$promocode->code?>». Напишите нам, что не понравилось, и как улучшить сервис?<br><br>
                    Промокод действителен в течение месяца.
                <?php endif?>
            </span><br/>
        </td>
    </tr>
    <tr>
        <td colspan="3" height="70"></td>
    </tr>
    <tr>
        <td width="5"></td>
        <td valign="top"></td>
        <td><img src="https://www.godesigner.ru/img/logo_email.png" ><br>

        <span style="font-family: Arial, sans-serif; font-size:13px; color: rgb(181, 181, 181); font-style: italic">С уважением,</span><br>
        <span style="font-family: Arial, sans-serif;font-size:13px; color: rgb(181, 181, 181); font-style: italic">команда Go Designer</span><br>
        <span style="font-family: Arial, sans-serif;font-size:13px; color: rgb(181, 181, 181); font-style: italic">Санкт-Петербург</span><br>
        <span style="font-family: Arial, sans-serif;font-size:13px; color: rgb(181, 181, 181); font-style: italic">+7 (812) 648 24 12</span><br>
        <br>
        <span style="font-family: Arial, sans-serif;font-size:13px; color: rgb(181, 181, 181); font-style: italic">Следите за нами на:</span><br>
        <a style="font-family: Arial, sans-serif;font-size:13px; font-style: italic; color: rgb(17, 50, 204)" href="https://www.facebook.com/pages/Go-Designer/160482360714084">facebook</a><br>
        <a style="font-family: Arial, sans-serif;font-size:13px; font-style: italic; color: rgb(17, 50, 204)" href="https://vk.com/public36153921">vkontakte</a><br>
        <a style="font-family: Arial, sans-serif;font-size:13px; font-style: italic; color: rgb(17, 50, 204)" href="https://twitter.com/Go_Deer">twitter</a><br>
        </td>
    </tr>
</table>

</body></html>