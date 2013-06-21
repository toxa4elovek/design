<div class="wrapper auth login">

	<?=$this->view()->render(array('element' => 'header'))?>

	<div class="middle" id="reg-section">

		<h1>Зарегистрироваться</h1>

		<div class="main">

			<section class="facebook-thing">
				<h2>Зарегистрируйтесь, используя Facebook</h2>

				<p><a class="button facebook facebook-logon" style="cursor: pointer;">Связаться через Facebook</a></p>

                <script src="http://vkontakte.ru/js/api/openapi.js" type="text/javascript"></script>


                <div id="vk_api_transport"></div>
                <div id="login_button" onclick="VK.Auth.login(authInfo, 1);"></div>

                <?php //echo $link = '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '">Аутентификация через ВКонтакте</a></p>'; ?>

                <a href="https://oauth.vk.com/authorize?client_id=2950889&display=popup&scope=friends&redirect_uri=http://godesigner.ru/users/vklogin&response_type=code" >vk</a>

                <script language="javascript">
                    function popitup(url) {
                        console.log(url)
                        newwindow=window.open(url,'name','height=200,width=600');
                        if (window.focus) {newwindow.focus()}
                        return false;
                    }
                    VK.init({
                        apiId: 2950889,
                        nameTransportPath: "http://godesigner.ru/xd_receiver.html"
                    });
                    function getUserProfileData(uid) {
                        var code;
                        code = 'return {'
                        code += 'me: API.getProfiles({uids: "' + uid + '", fields: "first_name, last_name, nickname, bdate"})[0]';
                        code += '};';
                        VK.Api.call('execute', { 'code': code }, getUserProfileDataCallback);
                    }

                    function getUserProfileDataCallback(data) {
                        console.log(data);
                        //data.response.me.first_name; // имя
                        //data.response.me.last_name;  // фамилия
                        //data.response.me.nickname;   // никнейм
                        //data.response.me.bdate;      // дата рождения
                    }
                    function authInfo(response) {
                        if (response.session) {
                            console.log('user: '+response.session.mid);
                            getUserProfileData(response.session.mid);
                        } else {
                            console.log('not auth');
                        }
                    }
                    VK.Auth.getLoginStatus(authInfo);
                    VK.UI.button('login_button');
                </script>

			</section>

			<section>
				<h2 class="or">или</h2>

		        <?php $errors = $user->errors();                ?>
				<?php echo $this->form->create($user, array('action' => 'registration', 'id' => 'registration'))
                /*if($invite):?>
                <form action="/users/registration?invite=<?=$invite?>" method="post">
                <?php endif; */
                ?>
                <!--input type="hidden" value="" id="invite"/-->
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

	<div class="middle" id="login-section" style="display:none;">
		<h1>Войти</h1>

		<div class="main">

			<section class="facebook-thing">
				<h2>Войти, используя Facebook</h2>

				<p><a class="button facebook facebook-logon" style="cursor:pointer;">Связаться через Facebook</a></p>
			</section>

			<section>
				<h2 class="or">или</h2>

				<?=$this->form->create(null, array('action' => 'login')) ?>
					<p>
						<?=$this->form->text('email', array('value' => '', 'placeholder' => 'Email', 'class' => 'email', 'required' => 'required')) ?>
						<strong class="error">Email обязателен</strong>
					</p>
					<p>
						<?=$this->form->password('password', array('value' => '', 'placeholder' => 'Пароль', 'class' => 'password', 'required' => 'required')) ?>
						<strong class="error">Пароль обязателен</strong>
					</p>
					<p class="submit">
						<input type="submit" value="Войти" class="button">

						<label class="dont-logout"><input type="checkbox" name="remember"> Не выходить из системы</label>

						<small><a href="">Забыли пароль?</a></small>
					</p><!-- .submit -->
				<?=$this->form->end() ?>
			</section>

			<section>
				<h2>Вы еще не зарегистрированы?</h2>

				<p><?=$this->html->link('Создать аккаунт', 'Users::registration', array('class' => 'button second', 'id' => 'reg-button'))?></p>
			</section>

		</div><!-- .main -->
	</div><!-- .middle -->
    <script type="text/javascript">
        var fb_param = {};
        fb_param.pixel_id = '6006509880528';
        fb_param.value = '0.00';
        (function(){
            var fpw = document.createElement('script');
            fpw.async = true;
            fpw.src = '//connect.facebook.net/en_US/fp.js';
            var ref = document.getElementsByTagName('script')[0];
            ref.parentNode.insertBefore(fpw, ref);
        })();
    </script>
    <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6006509880528&amp;value=0" /></noscript>

</div><!-- .wrapper -->
