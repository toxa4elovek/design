<div class="wrapper auth login">

    <?=$this->view()->render(array('element' => 'header'))?>

    <div class="middle" id="login-section">
        <h1>Восстановление пароля</h1>

        <div class="main">

            <section class="facebook-thing">
                <?php if($success):?>
                <h2>Письмо отправлено!</h2>
                <p>В течение пяти минут на указанный вами ящик придет письмо с инструкциями по восстановлению пароля!</p>
                <?php else: ?>
                <h2>Введите email</h2>


                <?=$this->form->create(null, array('url' => '/users/recover')) ?>
                <p>
                    <?=$this->form->text('email', array('value' => '', 'placeholder' => 'Email', 'class' => 'email', 'required' => 'required')) ?>
                    <?php if(!empty($errors)):?>
                    <strong class="error" style="display:block;">Email отсутствует в базе</strong>
                    <?php endif?>
                </p>

                <p class="submit">
                    <input type="submit" value="Начать процесс восстановления" class="button">
                    <small><a href="/users/login">Перейти к странице входа на сайт</a></small>
                </p><!-- .submit -->
                <?=$this->form->end() ?>
                <?php endif?>
            </section>
        </div><!-- .main -->
    </div><!-- .middle -->


</div><!-- .wrapper -->
