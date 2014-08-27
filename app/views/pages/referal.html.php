<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>
    <div class="middle">
        <div class="main">
            <div class="sideblock">

                    <div id="r_h_v">

                        <h2>Возникли вопросы?</h2>
                        Если вы&nbsp;не&nbsp;можете найти ответ на&nbsp;свой вопрос&nbsp;&mdash; напишите нам. Мы&nbsp;постараемся ответить вам в&nbsp;течении 24&nbsp;часов по&nbsp;рабочим дням.
                        <?=$this->html->link('<img src="/img/otp_em.jpg" alt="Контакты">', 'Pages::contacts', array('escape' => false))?>
                    </div>

            </div>

            <div class="mainblock">
                <section class="referal-section">
                    <h1 class="separator-flag">ПРИГЛАШАЙ ДРУЗЕЙ</h1>
                    <span class="referal-title">Вы получите 500 рублей на телефон<span style="color: #FF585D;">*</span>,<br /> когда ваши друзья создадут питч на GoDesigner</span>
                    <a href="#">Правила и условия</a>
                    <img src="/img/referal-illustration.png" alt="Вы получите 500 рублей на телефон, когда ваши друзья создадут питч на GoDesigner" style="margin: 30px 0;" />
                    <?php if (!$this->user->isLoggedIn()):?>
                    <span class="referal-title">Зарегистрируйтесь,<br /> чтобы  принять участие в акции!</span>
                    <a href="/register" class="button third" style="width: 180px; text-decoration: none; margin: 20px 0 10px 0;">Зарегистрироваться</a>
                    <div class="separator-flag-empty">
                        <img src="/img/text-ili.png" alt="или" />
                    </div>
                    <?php endif; ?>
                    <span class="referal-title">получите ссылку в личном кабинете!</span>
                    <a href="/users/referal" class="button" style="width: 180px; text-decoration: none; margin: 20px 0 30px 0;">На страницу партнерки</a>
                    <br />
                    <div class="remark">
                        <hr>
                        <span>— В акции могут принять участие владельцы номеров следующих операторов - Россия: МТС, Мегафон, Билайн, Теле-2, Беларусь: МТС Беларусь, Velcom, Life Беларусь, DIALLOG, Украина: Киевстар, МТС Украина, Life Украина.</span>
                    </div>
                </section>
            </div>
        </div>
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/help', '/howitworks', '/answer', '/edit'), array('inline' => false))?>