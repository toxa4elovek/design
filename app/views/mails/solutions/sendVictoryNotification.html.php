<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title></title>
</head>
<body style="margin: 0; padding: 0;">
    <img src="https://godesigner.ru/img/logo_original-01.png" width="200">

    <table>
        <tr><td colspan="3" height="30"></td></tr>
        <tr><td width="5"></td><td valign="top">
        </td>
            <td>
                <a style="color: #ff585d; line-height: 17px; font-size: 20px; font-weight: bold; font-family: Arial, sans-serif;text-decoration: none;" href="https://godesigner.ru/pitches/view/<?=$pitch->id?>"><?=$pitch->title?> <?=(int) $pitch->price?> Р.-</a><br><br>
                <span style="color: #444444;
                font-family: Arial, serif;
font-size: 14px;
font-weight: 400;
line-height: 20px;">
                    Здравствуйте, <?= $user->first_name ?>!<br>
                    Ваше решение <a target="_blank" style="color: #ff585d; line-height: 20px; font-size: 14px; font-family: Arial, sans-serif; text-decoration: none;" href="https://godesigner.ru/pitches/viewsolution/<?=$solution->id?>">#<?=$solution->num?></a> стало победителем. Мы поздравляем вас!
                    У заказчика есть право на внесение 3 правок на <a style="color: #ff585d; line-height: 20px; font-size: 14px; font-family: Arial, sans-serif;text-decoration: none;" href="https://godesigner.ru/users/step1/<?=$solution->id?>" target="_blank">этапе завершения</a>, пожалуйста, ознакомьтесь с <a style="color: #ff585d; line-height: 20px; font-size: 14px; font-family: Arial, sans-serif; text-decoration: none;" href="https://godesigner.ru/answers/view/62" target="_blank">инструкциями</a>.<br><br>
                </span>
        </td></tr>
        <tr><td colspan="3" height="30">
                <?php if ((int) $pitch->category_id !== 7):?>
                    <a href="https://godesigner.ru/pitches/viewsolution/<?=$solution->id?>"><img src="https://godesigner.ru/<?=$this->solution->renderImageUrl($solution->images['solution_solutionView'])?>" alt=""></a>
                <?php endif?>
            </td></tr>
        <tr>
            <td colspan="3" height="40"></td>
        </tr>
    </table>
</body></html>