<div class="wrapper auth login">

    <?= $this->view()->render(['element' => 'header']) ?>

    <div class="middle" id="reg-section" style="display:none;">

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
                <?= $this->form->create($user, ['action' => 'registration', 'id' => 'registration']) ?>
                <input type="hidden" name="case" value="h4820g838f">
                <p>
                    <?= $this->form->text('first_name', ['value' => $user->first_name, 'placeholder' => 'Имя', 'class' => 'name', 'required' => 'required']) ?>
                    <?php if (isset($errors['first_name'])): ?>
                        <strong class="error" style="display:block">Имя обязательно</strong>
                    <?php endif ?>
                </p>
                <p>
                    <?= $this->form->text('last_name', ['value' => $user->last_name, 'placeholder' => 'Фамилия', 'class' => 'name', 'required' => 'required']) ?>
                    <?php if (isset($errors['last_name'])): ?>
                        <strong class="error" style="display:block">Фамилия обязательна</strong>
                    <?php endif ?>
                </p>
                <p>
                    <?= $this->form->text('email', ['value' => $user->email, 'placeholder' => 'Email', 'class' => 'email', 'required' => 'required']) ?>
                    <?php if (isset($errors['email'])): ?>
                        <strong class="error" style="display:block;"><?= $errors['email'][0] ?></strong>
                    <?php endif ?>
                </p>
                <p>
                    <?= $this->form->password('password', ['value' => '', 'placeholder' => 'Пароль', 'class' => 'password', 'required' => 'required']) ?>
                    <?php if (isset($errors['password'])): ?>
                        <strong class="error" style="display:block">Пароль обязателен</strong>
                    <?php endif ?>
                </p>
                <p>
                    <?= $this->form->password('confirm_password', ['value' => '', 'placeholder' => 'Подтвердите пароль', 'class' => 'password', 'required' => 'required']) ?>
                    <?php if (isset($errors['confirm_password'])): ?>
                        <strong class="error" style="display:block">Подтвердите пароль</strong>
                    <?php endif ?>
                </p>
                <p class="register_who">
                    <label><?= $this->form->radio('who_am_i', ['value' => 'designer', 'class' => 'radio-input', 'checked' => 'checked']) ?>Я дизайнер</label>
                    <label><?= $this->form->radio('who_am_i', ['value' => 'client', 'class' => 'radio-input']) ?>Я заказчик</label>
                    <label class="last"><?= $this->form->radio('who_am_i', ['value' => 'company', 'class' => 'radio-input']) ?>Я юр. лицо</label>
                <div class="clr"></div>
                </p>
                <p class="short-company-name">
                    <?= $this->form->text('short_company_name', ['value' => $user->short_company_name, 'placeholder' => 'Краткое название компании', 'class' => 'name', 'required']) ?>
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

                <p><?= $this->html->link('Войти', 'Users::login', ['class' => 'button', 'id' => 'login-button']) ?></p>
            </section>

        </div><!-- .main -->
    </div><!-- .middle -->

    <div class="middle" id="login-section">
        <h1>Вход</h1>

        <div class="main">

            <section class="facebook-thing">
                <h2>Войти, используя Facebook</h2>

                <p><a class="facebook-logon button facebook" style="cursor: pointer">Связаться через Facebook</a></p>
            </section>

            <section class="facebook-thing">
                <h2>Войти, используя Vkontakte</h2>

                <p><a class="vkontakte-logon button vkontakte" style="cursor: pointer">Связаться через Vkontakte</a></p>
            </section>

            <section>
                <h2 class="or">или</h2>
                <?php if ($this->session->read('flash.login')): ?>
                    <p class="wrong-login"><?= $this->session->read('flash.login'); ?></p>
                    <?php
                    $this->session->delete('flash.login');
                endif;
                ?>
                <form action="/login/" method="post">
                <p>
                    <input type="text" name="email" value="" placeholder="Email" class="email" id="Email" />
                    <strong class="error">Email обязателен</strong>
                </p>
                <p>
                    <input type="password" name="password" placeholder="Пароль" class="password" id="Password" />
                    <strong class="error">Пароль обязателен</strong>
                </p>
                <p class="submit">
                    <input type="submit" value="Войти" class="button">

                    <label class="dont-logout"><input type="checkbox" name="remember"> Не выходить из системы</label>

                    <small><a href="/users/recover">Забыли пароль?</a></small>
                </p><!-- .submit -->
                </form>
            </section>
            <?php if (!$this->user->isLoggedIn()): ?>
                <section>
                    <h2>Вы еще не зарегистрированы?</h2>

                    <p><?= $this->html->link('Создать аккаунт', 'Users::registration', ['class' => 'button third', 'id' => 'reg-button']) ?></p>
                </section>
            <?php endif; ?>
        </div><!-- .main -->
    </div><!-- .middle -->

    <?= $this->view()->render(['element' => 'popups/register']) ?>

</div><!-- .wrapper -->
