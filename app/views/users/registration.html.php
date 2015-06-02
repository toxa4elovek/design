<div class="wrapper auth login">

    <?= $this->view()->render(array('element' => 'header')) ?>

    <div class="middle" id="reg-section">

        <h1>Зарегистрироваться</h1>

        <div class="main">

            <section class="facebook-thing">
                <h2>Зарегистрируйтесь, используя Facebook</h2>

                <p><a class="button facebook facebook-logon" style="cursor: pointer;">Связаться через Facebook</a></p>

                <div class="or-block">
                    <h2 style="margin-top: 0;" class="or">или</h2>
                </div>
                <h2>Зарегистрируйтесь, используя VKONTAKTE</h2>
                <p><a class="vkontakte-logon button vkontakte" style="cursor: pointer">Связаться через Vkontakte</a></p>

            </section>

            <section style="margin-top: 25px;">
                <h2 class="or">или</h2>

                <?php $errors = $user->errors(); ?>
                <?= $this->form->create($user, array('action' => 'registration', 'id' => 'registration')) ?>
                <input type="hidden" name="case" value="h4820g838f">
                <p>
                    <?= $this->form->text('first_name', array('value' => $user->first_name, 'placeholder' => 'Имя', 'class' => 'name', 'required' => 'required')) ?>
                    <?php if (isset($errors['first_name'])): ?>
                        <strong class="error" style="display:block">Имя обязательно</strong>
                    <?php endif ?>
                </p>
                <p>
                    <?= $this->form->text('last_name', array('value' => $user->last_name, 'placeholder' => 'Фамилия', 'class' => 'name', 'required' => 'required')) ?>
                    <?php if (isset($errors['last_name'])): ?>
                        <strong class="error" style="display:block">Фамилия обязательна</strong>
                    <?php endif ?>
                </p>
                <p>
                    <?= $this->form->text('email', array('value' => $user->email, 'placeholder' => 'Email', 'class' => 'email', 'required' => 'required')) ?>
                    <?php if (isset($errors['email'])): ?>
                        <strong class="error" style="display:block;"><?= $errors['email'][0] ?></strong>
                    <?php endif ?>
                </p>
                <p>
                    <?= $this->form->password('password', array('value' => '', 'placeholder' => 'Пароль', 'class' => 'password', 'required' => 'required')) ?>
                    <?php if (isset($errors['password'])): ?>
                        <strong class="error" style="display:block">Пароль обязателен</strong>
                    <?php endif ?>
                </p>
                <p>
                    <?= $this->form->password('confirm_password', array('value' => '', 'placeholder' => 'Подтвердите пароль', 'class' => 'password', 'required' => 'required')) ?>
                    <?php if (isset($errors['confirm_password'])): ?>
                        <strong class="error" style="display:block">Подтвердите пароль</strong>
                    <?php endif ?>
                </p>
                <p class="register_who">
                    <label><?= $this->form->radio('who_am_i', array('value' => 'designer', 'class' => 'radio-input', 'checked' => 'checked')) ?>Я дизайнер</label>
                    <label><?= $this->form->radio('who_am_i', array('value' => 'client', 'class' => 'radio-input')) ?>Я заказчик</label>
                    <label class="last"><?= $this->form->radio('who_am_i', array('value' => 'company', 'class' => 'radio-input')) ?>Я юр. лицо</label>
                    <div class="clr"></div>
                </p>
                <p class="short-company-name">
                    <?= $this->form->text('short_company_name', array('value' => $user->short_company_name, 'placeholder' => 'Краткое название компании', 'class' => 'name', 'required')) ?>
                    <?php if (isset($errors['short_company_name'])): ?>
                        <strong class="error" style="display:block">Название обязательно</strong>
                    <?php endif ?>
                </p>
                <span class="character-count" data-maxchars="10">10</span>
                <p class="submit">
                    <input type="submit" value="Создать аккаунт" class="button third">

                    <small>НАЖИМАЯ «СОЗДАТЬ АККАУНТ» ВЫ ПРИНИМАЕТЕ <a target="_blank" href="/pages/terms-and-privacy">УСЛОВИЯ И ПРАВИЛА ПОЛЬЗОВАНИЯ САЙТОМ</a></small>
                </p><!-- .submit -->
                <?= $this->form->end() ?>
            </section>

            <section>
                <h2>Вы уже зарегистрированы?</h2>

                <p><?= $this->html->link('Войти', 'Users::login', array('class' => 'button', 'id' => 'login-button')) ?></p>
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

            <section class="facebook-thing">
                <h2>Войти, используя Vkontakte</h2>

                <p><a class="vkontakte-logon button vkontakte" style="cursor: pointer">Связаться через Vkontakte</a></p>
            </section>

            <section>
                <h2 class="or">или</h2>

                <?= $this->form->create(null, array('action' => 'login')) ?>
                <p>
                    <?= $this->form->text('email', array('value' => '', 'placeholder' => 'Email', 'class' => 'email', 'required' => 'required')) ?>
                    <strong class="error">Email обязателен</strong>
                </p>
                <p>
                    <?= $this->form->password('password', array('value' => '', 'placeholder' => 'Пароль', 'class' => 'password', 'required' => 'required')) ?>
                    <strong class="error">Пароль обязателен</strong>
                </p>
                <p class="submit">
                    <input type="submit" value="Войти" class="button">

                    <label class="dont-logout"><input type="checkbox" name="remember"> Не выходить из системы</label>

                    <small><a href="">Забыли пароль?</a></small>
                </p><!-- .submit -->
                <?= $this->form->end() ?>
            </section>

            <section>
                <h2>Вы еще не зарегистрированы?</h2>

                <p><?= $this->html->link('Создать аккаунт', 'Users::registration', array('class' => 'button second', 'id' => 'reg-button')) ?></p>
            </section>

        </div><!-- .main -->
    </div><!-- .middle -->

    <?= $this->view()->render(array('element' => 'popups/register'), array('freePitch' => $freePitch)); ?>

    <script type="text/javascript">
        var fb_param = {};
        fb_param.pixel_id = '6006509880528';
        fb_param.value = '0.00';
        (function () {
            var fpw = document.createElement('script');
            fpw.async = true;
            fpw.src = '//connect.facebook.net/en_US/fp.js';
            var ref = document.getElementsByTagName('script')[0];
            ref.parentNode.insertBefore(fpw, ref);
        })();
    </script>
    <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6006509880528&amp;value=0" /></noscript>

</div><!-- .wrapper -->
<?= $this->html->script(array('users/registration.js', 'users/activation.js'), array('inline' => false)) ?>
<?= $this->view()->render(array('element' => 'popups/activation_popup')) ?>
