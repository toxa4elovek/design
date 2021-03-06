<div class="wrapper auth login">

    <?=$this->view()->render(['element' => 'header'])?>

    <div class="middle" id="login-section">
        <h1>Восстановление пароля</h1>

        <div class="main">

            <section class="facebook-thing">
                <?php if ($success):?>
                <h2>Письмо отправлено!</h2>
                <p>В течение пяти минут на указанный вами ящик придет письмо с инструкциями по восстановлению пароля!</p>
                <p>Если вам не приходит письмо, проверьте папку «спам» вашего почтового сервиса.</p>
                <?php else: ?>
                <h2>Введите email</h2>

                <form action="/users/recover/" method="post">
                <p>
                    <?=$this->form->text('email', ['value' => '', 'placeholder' => 'Email', 'class' => 'email', 'required' => 'required']) ?>
                    <?php if (!empty($errors)):?>
                    <strong class="error" style="display:block;">Email отсутствует в базе</strong>
                    <?php endif?>
                </p>

                <p class="submit">
                    <input type="submit" value="Начать процесс восстановления" class="button">
                    <small><a href="/login/">Перейти к странице входа на сайт</a></small>
                </p><!-- .submit -->
                </form>
                <?php endif?>
            </section>
        </div><!-- .main -->
    </div><!-- .middle -->


</div><!-- .wrapper -->
