<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title></title>
</head>
<body style="margin: 0; padding: 30px;">
Добрый день!<br><br>

Семь дней назад был создан проект:
«<a href="https://www.godesigner.ru/pitches/details/<?=$project->id?>"><?= $project->title?></a>»<br><br>

Пользователь:
<a href="https://www.godesigner.ru/users/view/<?=$user->id?>"><?=$user->first_name . ' ' . $user->last_name ?></a>
<a href="mailto:<?=$user->email?>"><?=$user->email?></a>

</body></html>