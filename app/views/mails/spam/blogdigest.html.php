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
        <td height="33" align="center" style="text-align: center;"><div style="line-height: 0;"><img src="http://godesigner.ru/img/mail/blogdigestheader.png" alt="Добро пожаловать" width="309" height="41" valign="top" /></div></td>
    </tr>
    <tr><td height="24"></td></tr>

    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" width="570" align="left" style="border-collapse: collapse;" bgcolor="#e1e1e1">
                <tr>
                    <td width="20"></td>
                    <td width="530">
                        <table cellpadding="0" cellspacing="0" width="100%" align="left" style="border-collapse: collapse;">
                            <tr><td height="18"></td></tr>
                            <tr>
                                <td align="center"><span style="color: #666666; line-height: 19px; font-size: 14px; font-family: Arial, sans-serif;">Мы пишем, чтобы вам было легче воплощать все ваши задумки, проще путешествовать и интереснее работать через интернет!</td>
                            </tr>
                            <tr><td height="15"></td></tr>
                            <tr><td align="center"><img src="http://godesigner.ru/img/mail/authors.png" alt="Авторы"></td></tr>
                            <tr><td height="15"></td></tr>

                            <tr><td height="1" bgcolor="#dddddd"></td></tr>
                            <tr><td height="1" bgcolor="#d4d4d4"></td></tr>
                            <tr><td height="1" bgcolor="#f7f7f7"></td></tr>


                            <?php
                            $total = count($posts);
                            $i = 0;
                            foreach($posts as $post):
                                $i++;?>

                                <tr><td height="20"></td></tr>
                                <tr>
                                    <td>
                                        <table cellpadding="0" cellspacing="0" width="100%" align="left" style="border-collapse: collapse;">
                                            <tr>
                                                <td width="10" valign="top"></td>
                                                <td width="230" valign="top">
                                                    <div style="line-height: 0;"><a href="http://godesigner.ru/posts/view/<?=$post->id?>" target="_blank" style="border: none; text-decoration: none;"><img src="<?=$post->imageurl?>" height="155" width="215"></a></div>
                                                </td>
                                                <td width="35" valign="top"></td>
                                                <td width="255" valign="top">
                                                    <table cellpadding="0" cellspacing="0" width="100%" align="left" style="border-collapse: collapse;">
                                                        <tr><td><a href="http://godesigner.ru/posts/view/<?=$post->id?>" style="color: #6990a1; text-decoration: none; text-transform: uppercase; line-height: 17px; font-weight:bold; font-size: 15px; font-family: Arial, sans-serif;"><?=$post->title ?></a></td></tr>
                                                        <tr><td height="15"></td></tr>
                                                        <tr>
                                                            <td>
                                                            <span style="color:#666666; font-size:14px;line-height: 19px;font-family: Arial, sans-serif;"><?php
                                                                $clean = strip_tags($post->short);
                                                                #$clean = str_replace('&nbsp;', '', $clean);
                                                                echo $clean;
                                                                ?></span>
                                                            </td>
                                                        </tr>

                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <?php
                                if($i != $total):?>
                                    <tr><td height="20"></td></tr>

                                    <tr><td height="1" bgcolor="#dddddd"></td></tr>
                                    <tr><td height="1" bgcolor="#d4d4d4"></td></tr>
                                    <tr><td height="1" bgcolor="#f7f7f7"></td></tr>
                                    <?php endif?>
                                <?php endforeach ?>

                            <tr><td height="20"></td></tr>

                            <tr><td height="1" bgcolor="#dddddd"></td></tr>
                            <tr><td height="1" bgcolor="#d4d4d4"></td></tr>
                            <tr><td height="1" bgcolor="#f7f7f7"></td></tr>

                            <tr><td height="19"></td></tr>
                            <tr>
                                <td align="center"><span style="display: block; color: #7d7c7c; text-transform: uppercase; line-height: 14px; font-size: 11px; font-family: Arial, sans-serif;">А ещё нам нравится, когда за нами подсматривают:</td>
                            </tr>
                            <tr><td height="15"></td></tr>
                            <tr><td align="center">
                                <table>
                                    <tr>
                                        <td width="67" align="center"><a href="https://www.facebook.com/pages/Go-Designer/160482360714084"><img src="http://godesigner.ru/img/mail/facebook.png" alt=""></a></td>
                                        <td width="67" align="center"><a href="https://twitter.com/Go_Deer"><img src="http://godesigner.ru/img/mail/twitter.png" alt=""></a></td>
                                        <td width="67" align="center"><a href="http://vk.com/godesigner"><img src="http://godesigner.ru/img/mail/vk.png" alt=""></a></td>
                                    </tr>
                                </table>
                            </td></tr>
                            <tr><td height="19"></td></tr>

                            <tr><td height="1" bgcolor="#dddddd"></td></tr>
                            <tr><td height="1" bgcolor="#d4d4d4"></td></tr>
                            <tr><td height="1" bgcolor="#f7f7f7"></td></tr>

                            <tr><td height="8"></td></tr>
                            <tr>
                                <td align="center" style="line-height: 13px; font-size: 9px; font-family: Arial, sans-serif;"><span style="color: #999999; text-transform: uppercase; line-height: 13px; font-size: 9px; font-family: Arial, sans-serif;">ЕСЛИ ДАННОЕ СООБЩЕНИЕ ОТОБРАЖАЕТСЯ НЕПРАВИЛЬНО, НАЖМИТЕ <a href="http://godesigner.ru/viewmail/<?= $hash?>" target="_blank" style="color: #999999; text-decoration: underline; text-transform: uppercase; line-height: 13px; font-size: 9px; font-family: Arial, sans-serif;"><span style="color: #999999; text-decoration: underline; text-transform: uppercase; line-height: 13px; font-size: 9px; font-family: Arial, sans-serif;">ЗДЕСЬ</span></a>.<br />
									<a href="http://godesigner.ru/users/profile" target="_blank" style="color: #999999; text-decoration: underline; text-transform: uppercase; line-height: 13px; font-size: 9px; font-family: Arial, sans-serif;"><span style="color: #999999; text-decoration: underline; text-transform: uppercase; line-height: 13px; font-size: 9px; font-family: Arial, sans-serif;">ОТПИСАТЬСЯ</span></a> ОТ РАССЫЛКИ.ОТПРАВЛЕНО ИЗ ГОЛОВНОГО ОФИСА GO DESIGNER, САНКТ- ПЕТЕРБУРГ, РОССИЯ.</span></td>
                            </tr>
                            <tr><td height="13"></td></tr>

                        </table>
                    </td>
                    <td width="20"></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr><td height="30"></td></tr>
</table>
</body>
</html>