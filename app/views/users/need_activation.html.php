<div class="wrapper auth login">

    <?=$this->view()->render(array('element' => 'header'))?>

    <div class="middle" id="reg-section" style="display:block;">

        <h1>Аккаунт не активирован</h1>

        <div class="main">

            <section>
                <p class="regular">
                    Ваш аккаунт пока не активирован и многие функции сайта для вас недоступны. Чтобы активировать аккаунт, нажмите на соответствующую ссылку в письме &laquo;Активация аккаунта на сайте Godesigner.ru&raquo;, которые пришло вам на ящик электронной почты, указанный вами при регистрации.<br><br> Если вы не видите этого письма, проверьте папку &laquo;спам&raquo; или вы можете <a id="resend" href="#">получить это письмо еще раз.</a></p>
                <p class="regular" id="mailsent" style="display:none;font-weight: bold;"><br>Письмо было отправлено вам на почту!</p>
            </section>

        </div><!-- .main -->
    </div><!-- .middle -->

</div><!-- .wrapper -->
<?=$this->html->script(array('users/need_activation.js'), array('inline' => false))?>