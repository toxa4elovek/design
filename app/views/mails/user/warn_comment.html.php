Пользователем <a href="https://godesigner.ru/users/view/<?=$user['id']?>"><?=$user['first_name'] . ' ' . $user['last_name']?></a> (<?=$user['email']?>) отправлена следующая жалоба на комментарий <a href="https://godesigner.ru/pitches/view/<?=$comment['pitch_id']?>">(проект №<?=$comment['pitch_id']?>)</a>:<br/><br/>
<?=$text?>
<br><br>
Текст комментария:<br><br>
<?php echo $comment['text']?>