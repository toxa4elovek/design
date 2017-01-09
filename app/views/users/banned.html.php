<div class="wrapper auth login">

    <?=$this->view()->render(['element' => 'header'])?>

    <div class="middle" id="reg-section" style="display:block;">
        <?php if ($shortTerm):?>
            <h1>Аккаунт заблокирован на 30 дней</h1>

            <div class="main">

                <section>
                    <p class="regular">Ваш аккаунт заблокирован на 30 дней, это произошло из-за несоблюдения <a href="https://godesigner.ru/answers/view/37">правил</a> Go Designer.</p>
                </section>

            </div><!-- .main -->
        <?php else: ?>
            <h1>Аккаунт заблокирован</h1>

            <div class="main">

                <section>
                    <p class="regular">Ваш аккаунт заблокирован, это произошло из-за несоблюдения <a href="https://godesigner.ru/answers/view/37">правил</a> Go Designer.</p>
                </section>

            </div><!-- .main -->
        <?php endif?>
    </div><!-- .middle -->

</div><!-- .wrapper -->