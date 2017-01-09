<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title></title>
    </head>
    <body bgcolor="#2d2f3a" style="background-color:#2d2f3a; margin: 0; padding: 0;color:#7d7c7c;line-height: 17px; font-size: 14px; font-family: Arial, sans-serif;">
        <table cellpadding="0" cellspacing="0" width="570" align="center" style="border-collapse: collapse;">
            <tr>
                <td height="84">
                    <div style="line-height: 5px;">
                        <a href="http://godesigner.ru" target="_blank" style="border: none; text-decoration: none;"><span style="border: none; text-decoration: none;"></span><img src="http://godesigner.ru/img/mail/logo.png" alt="Go Designer" width="201" height="84" border="0" valign="top" /></a>
                        <img src="http://godesigner.ru/img/mail/welcome-designer.png" alt="Добро пожаловать!" width="330" align="right" style="margin-top:20px" />
                    </div>
                </td>
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
                                        <td align="center"><span style="color:#7d7c7c;line-height: 17px; font-size: 14px; font-family: Arial, sans-serif;line-height:2">
                                                Поздравляем, вы стали полноценным пользователем! <a style="color:#f45151;" href="http://godesigner.ru">GoDesigner</a>,<br />
                                                где сожно создавать дизайн и зарабатывать деньги.<br />
                                                Мы, однако, пока не знаем, куда вам отправлять вознаграждения.</span>
                                        </td>
                                    </tr>
                                    <tr><td height="30"></td></tr>
                                    <tr>
                                        <td align="center">
                                            <a href="https://godesigner.ru/users/profile" target="_blank" style="display: block; width: 285px; height: 65px;background-image: url('http://godesigner.ru/img/mail/enter-data.png'); background-position: 0 0; background-repeat: no-repeat; color: #ffffff; text-align: center; text-decoration: none; text-shadow: 0 1px 1px rgba(0, 0, 0, .2); line-height: 49px; font-weight: bold; font-size: 11px; font-family: Arial, sans-serif;"><img src="http://godesigner.ru/img/mail/enter-data.png" alt="Войти и заполнить реквизиты" valign="top" border="0" width="285" height="65" /></a>
                                        </td>
                                    </tr>
                                    <tr><td height="30"></td></tr>
                                    <tr>
                                        <td>
                                            <table width="100%">
                                                <?php foreach ($pitches as $pitch): $count++; ?>
                                                    <tr align="left" height="60" style="font-size:15px;font-weight:bold;color:#fff;background-color:<?= ($count == 2) ? '#2f313a' : '#454650' ?>">
                                                        <td style="padding-left:20px;vertical-align:middle;" width="120"><?= $pitch->price ?>.-р</td>
                                                        <td style="padding-left:20px;vertical-align:middle;" width="50%"><?= $pitch->title ?></td>
                                                        <td style="padding-left:8px;vertical-align:middle;"><?= $pitch->startedHuman ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr><td height="15"></td></tr>

                                    <tr><td height="1" bgcolor="#dddddd"></td></tr>
                                    <tr><td height="1" bgcolor="#d4d4d4"></td></tr>
                                    <tr><td height="1" bgcolor="#f7f7f7"></td></tr>

                                    <tr><td height="20"></td></tr>
                                    <tr>
                                        <td>
                                            <table cellpadding="0" cellspacing="0" width="100%" align="left" style="border-collapse: collapse;">
                                                <tr>
                                                    <?php
                                                    $count = 0;
                                                    foreach ($posts as $post):
                                                        $count++;
                                                        if ($count <= 2) :
                                                            ?>  
                                                            <td width="230" valign="top" sty>
                                                                <div style="line-height: 0;"><a href="http://godesigner.ru/posts/view/<?= $post->id ?>" target="_blank" style="border: none; text-decoration: none;"><img src="<?= $post->imageurl ?>" alt="<?= $post->title ?>" valign="top" border="0" width="250" /></a></div>
                                                                <a href="http://godesigner.ru/posts/view/<?= $post->id ?>" target="_blank" style="color:#658fa5;text-decoration:none;font-weight:bold;line-height: 2;"><?=mb_strtoupper($post->title, 'UTF-8') ?></a><br />
                                                                <span style="line-height:1.5;"><?php echo $post->short ?></span>
                                                            </td>
                                                            <?php if ($count == 1) : ?>
                                                                <td width="10" valign="top"></td>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr><td height="20"></td></tr>

                                    <tr><td height="1" bgcolor="#dddddd"></td></tr>
                                    <tr><td height="1" bgcolor="#d4d4d4"></td></tr>
                                    <tr><td height="1" bgcolor="#f7f7f7"></td></tr>

                                    <tr><td height="20"></td></tr>
                                    <tr>
                                        <td>
                                            <table cellpadding="0" cellspacing="0" width="100%" align="left" style="border-collapse: collapse;">
                                                <tr>
                                                    <td>
                                                        <table cellpadding="0" cellspacing="0" width="100%" align="left" style="border-collapse: collapse;">
                                                            <tr>
                                                                <?php
                                                                $count = 0;
                                                                foreach ($posts as $post):
                                                                    if ($count < 2):
                                                                        $count++;
                                                                    else:
                                                                        ?> 
                                                                        <td width="5" valign="top"></td>
                                                                        <td width="180" valign="top">
                                                                            <table cellpadding="0" cellspacing="0" width="100%" align="left" style="border-collapse: collapse;">
                                                                                <tr>
                                                                                    <td width="180" valign="top">
                                                                                        <div style="line-height: 0;"><a href="http://godesigner.ru/posts/view/<?= $post->id ?>" target="_blank" style="border: none; text-decoration: none;"><img src="<?= $post->imageurl ?>" alt="<?= $post->title ?>" valign="top" border="0" width="160" /></a></div>
                                                                                        <a href="http://godesigner.ru/posts/view/<?= $post->id ?>" target="_blank" style="color:#658fa5;text-decoration:none;font-weight:bold;line-height:2;"><?= $post->title ?></a><br />
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>

                                                <tr><td height="20"></td></tr>

                                                <tr><td height="1" bgcolor="#dddddd"></td></tr>
                                                <tr><td height="1" bgcolor="#d4d4d4"></td></tr>
                                                <tr><td height="1" bgcolor="#f7f7f7"></td></tr>

                                                <tr><td height="19"></td></tr>

                                                <tr><td align="center"><a href="https://godesigner.ru/answers/view/7" style="color:#f97a7d">Как победитель получит своё вознаграждение?</a></td></tr>
                                                <tr><td height="20"></td></tr>

                                                <tr><td height="1" bgcolor="#dddddd"></td></tr>
                                                <tr><td height="1" bgcolor="#d4d4d4"></td></tr>
                                                <tr><td height="1" bgcolor="#f7f7f7"></td></tr>

                                                <tr><td height="19"></td></tr>

                                                <tr><td align="center"><a href="https://godesigner.ru/answers/view/44" style="color:#f97a7d">Как принять участие в проекте?</a></td></tr>
                                                <tr><td height="20"></td></tr>

                                                <tr><td height="1" bgcolor="#dddddd"></td></tr>
                                                <tr><td height="1" bgcolor="#d4d4d4"></td></tr>
                                                <tr><td height="1" bgcolor="#f7f7f7"></td></tr>

                                                <tr><td height="19"></td></tr>
                                                <tr><td align="center"><a href="https://godesigner.ru/answers/view/50" style="color:#f97a7d">Как я узнаю, что выиграл проект?</a></td></tr>

                                                <tr><td height="20"></td></tr>

                                                <tr><td height="1" bgcolor="#dddddd"></td></tr>
                                                <tr><td height="1" bgcolor="#d4d4d4"></td></tr>
                                                <tr><td height="1" bgcolor="#f7f7f7"></td></tr>

                                                <tr><td height="19"></td></tr>
                                                <tr>
                                                    <td align="center"><span style="display: block; color: #7d7c7c; text-transform: uppercase; line-height: 14px; font-size: 11px; font-family: Arial, sans-serif;">А ЕЩЕ НАМ НРАВИТСЯ, КОГДА ЗА НАМИ ПОДСМАТРИВАЮТ:</span></td>
                                                </tr>
                                                <tr><td height="5"></td></tr>
                                                <tr>
                                                    <td align="center">
                                                        <a href="https://www.facebook.com/godesigner.ru" target="_blank"><img src="http://godesigner.ru/img/mail/facebook.png" alt="Facebook" border="0" /></a>
                                                        <a href="https://twitter.com/Go_Deer" target="_blank"><img src="http://godesigner.ru/img/mail/twitter.png" alt="Twitter" border="0" /></a>
                                                        <a href="https://vk.com/godesigner" target="_blank"><img src="http://godesigner.ru/img/mail/vk.png" alt="Вконтакте" border="0" /></a>
                                                    </td>
                                                </tr>
                                                <tr><td height="19"></td></tr>

                                                <tr><td height="1" bgcolor="#dddddd"></td></tr>
                                                <tr><td height="1" bgcolor="#d4d4d4"></td></tr>
                                                <tr><td height="1" bgcolor="#f7f7f7"></td></tr>

                                                <tr><td height="8"></td></tr>
                                                <tr>
                                                    <td align="center" style="line-height: 13px; font-size: 9px; font-family: Arial, sans-serif;"><span style="color: #999999; text-transform: uppercase; line-height: 13px; font-size: 9px; font-family: Arial, sans-serif;">ЕСЛИ ДАННОЕ СООБЩЕНИЕ ОТОБРАЖАЕТСЯ НЕПРАВИЛЬНО, НАЖМИТЕ <a href="http://godesigner.ru/viewmail/<?= $hash ?>" target="_blank" style="color: #999999; text-decoration: underline; text-transform: uppercase; line-height: 13px; font-size: 9px; font-family: Arial, sans-serif;"><span style="color:#658fa5;text-decoration:none; text-transform: uppercase; line-height: 13px; font-size: 9px; font-family: Arial, sans-serif;">ЗДЕСЬ</span></a>.<br />
                                                            <a href="http://godesigner.ru" target="_blank" style="color: #999999; text-decoration: underline; text-transform: uppercase; line-height: 13px; font-size: 9px; font-family: Arial, sans-serif;"><span style="color:#658fa5;text-decoration:none; text-transform: uppercase; line-height: 13px; font-size: 9px; font-family: Arial, sans-serif;">ОТПИСАТЬСЯ</span></a> ОТ РАССЫЛКИ.ОТПРАВЛЕНО ИЗ ГОЛОВНОГО ОФИСА GO DESIGNER, САНКТ- ПЕТЕРБУРГ, РОССИЯ.</span></td>
                                                </tr>
                                                <tr><td height="13"></td></tr>

                                            </table>
                                        </td>
                                        <td width="20"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>