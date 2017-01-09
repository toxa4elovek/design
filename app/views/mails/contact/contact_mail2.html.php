Имя: <?=$name?><br>
Email: <?=$email?><br>
Сообщение:<br><br>

<?=$message?><br>

<?php if ($needInfo): ?>
<br>
==============================<br>
OS: <?=$info['os'] . ' ' . $info['osVersion']?><br>
Browser: <?=$info['browser'] . ' ' . $info['browserVersion']?><br>
Resolution: <?=$info['screen']?><br>

<?php if (isset($user)):?>
<br>
Имя: <?=$user['firstName']?><br>
Фамилия: <?=$user['lastName']?><br>
<a href="https://godesigner.ru/users/view/<?=$user['id']?>">Профиль</a><br>
<a href="http://cp.godesigner.ru/users/loginasadmin?query=redirect&redirect=https://godesigner.ru/users/loginasuser/<?=$user['id']?>">Войти под именем</a><br>

<br>
<?php
    $noProjects = true;
    if ($user['pitches'] != null) {
        $noProjects = false;
    }
        if (!$noProjects):
    foreach ($user['pitches'] as $pitch):?>
        <a href="https://godesigner.ru/pitches/view/<?=$pitch['id']; ?>"><?=(empty($pitch['title'])) ? '— ' : $pitch['title'];?></a> (<a href="http://cp.godesigner.ru/pitches/edit/<?=$pitch['id']; ?>">просмотреть в админке</a>)<br>
    <?php endforeach;?>
<?php endif; ?>

<?php endif; ?>

<?php endif; ?>
