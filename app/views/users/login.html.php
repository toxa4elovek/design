<div class="wrapper auth login">

	<?=$this->view()->render(array('element' => 'header'))?>

	<div class="middle" id="reg-section" style="display:none;">

		<h1>Зарегистрироваться</h1>

		<div class="main">

			<section class="facebook-thing">
				<h2>Зарегистрируйтесь, используя Facebook</h2>

				<p><a class="button facebook facebook-logon" style="cursor: pointer;">Связаться через Facebook</a></p>
			</section>

			<section>
				<h2 class="or">или</h2>

		        <?php $errors = $user->errors();?>
				<?=$this->form->create($user, array('action' => 'registration', 'id' => 'registration')) ?>
                <input type="hidden" name="case" value="h4820g838f">
				<p>
					<?=$this->form->text('first_name', array('value' => $user->first_name, 'placeholder' => 'Имя', 'class' => 'name', 'required' => 'required')) ?>
					<?php if(isset($errors['first_name'])):?>
					<strong class="error" style="display:block">Имя обязательно</strong>
					<?php endif?>
				</p>
				<p>
					<?=$this->form->text('last_name', array('value' => $user->last_name, 'placeholder' => 'Фамилия', 'class' => 'name', 'required' => 'required')) ?>
					<?php if(isset($errors['last_name'])):?>
					<strong class="error" style="display:block">Фамилия обязательна</strong>
					<?php endif?>
				</p>
				<p>
					<?=$this->form->text('email', array('value' => $user->email, 'placeholder' => 'Email', 'class' => 'email', 'required' => 'required')) ?>
					<?php if(isset($errors['email'])):?>
					<strong class="error" style="display:block;"><?=$errors['email'][0]?></strong>
					<?php endif?>
				</p>
				<p>
					<?=$this->form->password('password', array('value' => '', 'placeholder' => 'Пароль', 'class' => 'password', 'required' => 'required')) ?>
					<?php if(isset($errors['password'])):?>
					<strong class="error" style="display:block">Пароль обязателен</strong>
					<?php endif?>
				</p>
				<p>
					<?=$this->form->password('confirm_password', array('value' => '', 'placeholder' => 'Подтвердите пароль', 'class' => 'password', 'required' => 'required')) ?>
					<?php if(isset($errors['confirm_password'])):?>
					<strong class="error" style="display:block">Подтвердите пароль</strong>
					<?php endif?>
				</p>
				<p class="submit">
					<input type="submit" value="Создать аккаунт" class="button second">

                    <small>НАЖИМАЯ «СОЗДАТЬ АККАУНТ» Я ВЫРАЖАЮ БЕЗУСЛОВНОЕ СОГЛАСИЕ С УСЛОВИЯМИ НАСТОЯЩЕГО <a target="_blank" href="/pages/terms-and-privacy">ПОЛЬЗОВАТЕЛЬСКОГО СОГЛАШЕНИЯ.</a></small>
				</p><!-- .submit -->
				<?=$this->form->end() ?>
			</section>

			<section>
				<h2>Вы уже зарегистрированы?</h2>

				<p><?=$this->html->link('Войти', 'Users::login', array('class' => 'button', 'id' => 'login-button'))?></p>
			</section>

		</div><!-- .main -->
	</div><!-- .middle -->

	<div class="middle" id="login-section">
		<h1>Войти</h1>

		<div class="main">

			<section class="facebook-thing">
				<h2>Войти, используя Facebook</h2>

				<p><a class="facebook-logon button facebook" style="cursor: pointer">Связаться через Facebook</a></p>
			</section>

			<section>
				<h2 class="or">или</h2>
				<?php if ($this->session->read('flash.login')):?>
                    <p class="wrong-login"><?=$this->session->read('flash.login');?></p>
                <? $this->session->delete('flash.login');
                endif;
                ?>
				<?=$this->form->create(null, array('action' => 'login')) ?>
					<p>
                        <input type="text" name="email" value="" placeholder="email" class="email" id="Email" />
						<strong class="error">Email обязателен</strong>
					</p>
					<p>
                        <input type="password" name="password" placeholder="пароль" class="password" id="Password" />
						<strong class="error">Пароль обязателен</strong>
					</p>
					<p class="submit">
						<input type="submit" value="Войти" class="button">

						<label class="dont-logout"><input type="checkbox" name="remember"> Не выходить из системы</label>

						<small><a href="/users/recover">Забыли пароль?</a></small>
					</p><!-- .submit -->
				<?=$this->form->end() ?>
			</section>
            <?php if($this->session->read('user')):?>
			<section>
				<h2>Вы еще не зарегистрированы?</h2>

				<p><?=$this->html->link('Создать аккаунт', 'Users::registration', array('class' => 'button second', 'id' => 'reg-button'))?></p>
			</section>
            <?php endif;?>
		</div><!-- .main -->
	</div><!-- .middle -->


</div><!-- .wrapper -->
