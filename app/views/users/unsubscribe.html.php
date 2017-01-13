<div class="wrapper">
    <?=$this->view()->render(['element' => 'header'], ['header' => 'header2'])?>


    <div class="middle" id="login-section">
        <p style='	color: #fff;
	font: bold 44px/1 "RodeoC", sans-serif;
    text-align: center;
    margin-bottom: 27px;
    margin-top: 50px;
    text-shadow: 1px 1px 5px #333;' >
            Отказ от рассылки!
        </p>

        <div class="main" style="width: 381px; height:240px">


            <section style="padding-top: 40px;padding-left:40px;padding-right:40px;">
                <p style="color: #6a6a6a;margin-bottom:24px">
                    Вы уверены, что больше не хотите получать от нас рассылку о новостях, новых проектах, комментариях? Нажимая «Да, отписаться» мы больше не побеспокоим вас своими письмами.
                </p>

                    <form action="/users/unsubscribe" method="post" style="margin-bottom:20px;margin-left: -10px;">
                        <input type="hidden" name="token" value="<?=$this->_request->query['token']?>">
                        <input type="hidden" name="from" value="<?=$this->_request->query['from']?>">
                        <input style="width:150px; padding-left: 15px;" type="submit" value="Да, отписаться" class="button">
                <a style="width:82px; margin-left:5px" href="/" class="button second">Нет, отмена</a>
                    </form>

                <div style='background: url("/img/sep.png") repeat-x scroll 0 0 transparent;
    height: 3px;
    margin-bottom:5px;
    width: auto;'></div>
                <small style="margin-bottom: 20px;margin-left: 10px;"><a href="/users/profile">Настроить</a> рассылку уведомлений по email</small>
            </section>

        </div><!-- .main -->
    </div><!-- .middle -->

</div><!-- .wrapper -->