<div class="wrapper auth login">

    <?=$this->view()->render(array('element' => 'header'))?>

    <div class="middle" id="login-section">
        <h1>Восстановление пароля</h1>

        <div class="main">

            <section class="facebook-thing">
                <?php if(isset($user->success)):?>
                <h2>Вы успешно установили новый пароль!</h2>
                <p>Теперь вы можете использовать новый пароль для входа на сервис!</p>
                <p>Вы можете начать работу в <a href="/users/office">личном кабинете</a> или в <a href="/pitches">списке проектов</a>.</p>
                <?php else:?>
                <h2>Введите и сохраните новый пароль!</h2>
                <?=$this->form->create(null, array('url' => '/users/setnewpassword?token=' . $token)) ?>
                <p>
                    <?=$this->form->password('password', array('value' => '', 'placeholder' => 'Пароль', 'class' => 'password', 'required' => 'required')) ?>
                    <?php if(isset($errors['password'])):?>
                    <strong class="error" style="display:block"><?=$errors['password']?></strong>
                    <?php endif?>
                </p>
                <p>
                    <?=$this->form->password('confirm_password', array('value' => '', 'placeholder' => 'Подтвердите пароль', 'class' => 'password', 'required' => 'required')) ?>
                    <?php if(isset($errors['confirm_password'])):?>
                    <strong class="error" style="display:block"></strong>
                    <?php endif?>
                </p>
                <p class="submit">
                    <input type="submit" value="Установить новый пароль" class="button">
                </p><!-- .submit -->
                <?=$this->form->end() ?>

                <?php endif?>
            </section>
        </div><!-- .main -->
    </div><!-- .middle -->


</div><!-- .wrapper -->
