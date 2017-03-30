<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title></title>
</head>
<body bgcolor="#2d2f3a" style="background-color:#2d2f3a; margin: 0; padding: 0;">
<table cellpadding="0" cellspacing="0" width="570" align="center" style="border-collapse: collapse;">
    <tr>
        <td height="84"><div style="line-height: 0;"><a href="http://godesigner.ru" target="_blank" style="border: none; text-decoration: none;"><span style="border: none; text-decoration: none;"></span><img src="http://godesigner.ru/img/mail/logo.png" alt="Go Designer" width="201" height="84" border="0" valign="top" /></a></div></td>
    </tr>
    <tr><td height="30"></td></tr>

    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" width="570" align="left" style="border-collapse: collapse;" bgcolor="#e1e1e1">
                <tr>
                    <td width="30"></td>
                    <td width="530">
                        <table cellpadding="0" cellspacing="0" width="100%" align="left" style="border-collapse: collapse;">
                            <tr><td height="20"></td></tr>
                            <tr>
                                <td align="center"><span style="color: #666666; line-height: 24px; font-size: 16px; font-family: Arial, sans-serif;">Та-дам!</td>
                            </tr>
                            <tr><td height="30"></td></tr>
                            <tr><td><span style="color: #666666; line-height: 24px; font-size: 16px; font-family: Arial, sans-serif;">
                                        Ваше решение <a style="text-decoration: none;color: #6990a0;" href="https://godesigner.ru/pitches/viewsolution/<?= $solution->id?>">#<?= $solution->num?></a> для проекта <a style="text-decoration: none;color: #6990a0;" href="https://godesigner.ru/pitches/view/<?=$pitch->id?>">«<?= $pitch->title?>»</a> хочет выкупить посетитель GoDesigner за 6000 р. в рамках <a style="text-decoration: none;color: #6990a0;" href="https://godesigner.ru/logosale">распродажи логотипов</a>.</span></td></tr>
                            <tr><td height="30"></td></tr>
                            <tr><td align="center">
                                    <table style="background-color: #ffffff;">
                                        <tr height="8"><td colspan="3"></td></tr>
                                        <tr height="180"><td width="12"></td>
                                            <td width="220">
                                                <a href="https://godesigner.ru/pitches/viewsolution/<?= $solution->id?>">
                                                <img width="220" height="180" src="https://godesigner.ru/<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>" alt="">
                                                </a>
                                            </td>
                                            <td width="12"></td>
                                        </tr>
                                        <tr height="30"><td colspan="3"></td></tr>
                                    </table>
                                </td></tr>
                            <tr><td height="30"></td></tr>
                            <tr><td><span style="color: #666666; line-height: 24px; font-size: 16px; font-family: Arial, sans-serif;">
                                        В случае согласия, у заказчика есть право на внесение 3 правок, включая адаптацию названия. Пожалуйста, подтвердите запрос на готовность продолжить работу в течение 3 дней; в случае отказа, мы вернем заказчику деньги.
                                    </span>
                                </td></tr>
                            <tr><td height="40"></td></tr>
                            <tr><td align="center">
                                    <a href="http://godesigner.ru/users/step2/<?= $solution->id?>">
                                        <img src="http://godesigner.ru/img/mail/spam_logo_sale.png" alt="Подтвердить">
                                    </a>
                                </td></tr>
                            <tr><td height="20"></td></tr>

                            <tr><td height="1" bgcolor="#dddddd"></td></tr>
                            <tr><td height="1" bgcolor="#d4d4d4"></td></tr>
                            <tr><td height="1" bgcolor="#f7f7f7"></td></tr>

                            <tr><td height="10"></td></tr>

                            <tr>
                                <td align="center"><span style="color: #666666; line-height: 18px; font-size: 11px; font-family: Arial, sans-serif; text-transform: uppercase;">А еще мы часто рассказываем, что у нас происходит, на</td>
                            </tr>

                            <tr><td height="5"></td></tr>

                            <tr>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td align="center">
                                                <a href="https://twitter.com/#!/Go_Deer" style="margin: 7px;"><img src="http://godesigner.ru/img/mail/twitter.png" /></a>
                                                <a href="http://www.facebook.com/pages/Go-Designer/160482360714084" style="margin: 7px;"><img src="http://godesigner.ru/img/mail/facebook.png" /></a>
                                                <a href="http://vk.com/public36153921" style="margin: 7px;"><img src="http://godesigner.ru/img/mail/vk.png" /></a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr><td height="5"></td></tr>

                            <tr><td height="1" bgcolor="#dddddd"></td></tr>
                            <tr><td height="1" bgcolor="#d4d4d4"></td></tr>
                            <tr><td height="1" bgcolor="#f7f7f7"></td></tr>

                            <tr><td height="8"></td></tr>
                            <tr>
                                <td align="center" style="line-height: 13px; font-size: 9px; font-family: Arial, sans-serif;"><span style="color: #666; text-transform: uppercase;">ЕСЛИ ДАННОЕ СООБЩЕНИЕ ОТОБРАЖАЕТСЯ НЕПРАВИЛЬНО, НАЖМИТЕ <a href="http://godesigner.ru/viewmail/<?=$hash?>" target="_blank" style="color: #6990a0; text-decoration: underline;">ЗДЕСЬ</a>.<br />
                                </span>
                                    </br></td>
                            </tr>
                            <tr><td height="13"></td></tr>

                        </table>
                    </td>
                    <td width="10"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr><td height="30"></td></tr>
</table>
</body>
</html>