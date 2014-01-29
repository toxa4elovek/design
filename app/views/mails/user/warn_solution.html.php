Пользователем <a href="http://www.godesigner.ru/users/view/<?=$user['id']?>"><?=$user['first_name'] . ' ' . $user['last_name']?></a> (<?=$user['email']?>) отправлена следующая жалоба на <a href="http://www.godesigner.ru/pitches/viewsolution/<?=$solution['id']?>">решение</a>:<br/><br/>
<img src="http://www.godesigner.ru<?=$this->solution->renderImageUrl($solution['images']['solution_solutionView']);?>" />
<br/><br/>
<?=$text?>
