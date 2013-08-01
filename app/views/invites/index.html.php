<div class="wrapper">
	<h1 class="logo"><a href="">Go Designer</a></h1>
	<p>
		Дизайн на <em><span>1<small>раз</small></span> - <span>2<small>два</small></span> - <span>3<small>три!</small></span></em>
	</p>
	<?php if($success == true): ?>
	<p>
		<em style="font-size:20px; padding-top:0px;"><small style="position:static;">спасибо!</small></em>
	</p>
	<?php else:?>
	<form action="" method="post" accept-charset="utf-8">
		<p>
			<input type="text" name="email" placeholder="Оставь свой e-mail и узнай о запуске первым!" required>
			<input type="submit" value="Отправить" class="button second">
			<?php if(!empty($error)): ?>
			<strong class="error" style="display:block"><?php echo $error?></strong>
			<?php endif; ?>
			<?php if($success): ?>

			<?php endif;?>
		</p>
        <a href="http://www.facebook.com/pages/Go-Designer/160482360714084" class="facebook-button" style=""><img src="/img/1.gif" alt=""></a>
        <a href="https://twitter.com/#!/Go_Deer" class="twitter-button" style=""><img src="/img/1.gif" alt=""></a>
        <a href="http://vk.com/public36153921" class="vk-button" style=""><img src="/img/1.gif" alt=""></a>
	</form>

    <a href="/users/login" class="beta-test-enter" style=""><img src="/img/1.gif" alt=""></a>
	<?php endif;?>
</div><!-- .wrapper -->