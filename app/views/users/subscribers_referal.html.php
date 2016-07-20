<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>
    <?php
    $userdata = unserialize($user->userdata);
    ?>
    <div class="middle">
        <div class="main">
            <nav class="main_nav clear" style="width:832px;margin-left:2px;">
                <?=$this->view()->render(array('element' => 'office/nav'));?>
            </nav>

            <div class="sideblock">
                <div style="width:200px">
                    <?php echo $this->stream->renderStream(3);?>
                </div>
            </div>
            <section class="mainblock">
                <section class="subscriber-referal-section">
                    <span class="referal-title">Вы получите 10 000 рублей на карту,<br/>если ваш друг станет <a href="/pages/subscribe">абонентом GoDesigner</a></span>
                    <a href="/answers/view/110" target="_blank">Правила и условия</a>
                    <div class="image-box">
                        <img src="/img/users/subscribers_referal/referal-illustration2.png" alt="" />
                    </div>
                    <h3>Используйте ссылку, и ваш друг получит скидку<br> 20% при оплате тарифа</h3>
                    <input type="text" id="url-to-copy" value="<?=$shortUrl?>" />
                    <div class="link-box">
                        <a class="copy-link" data-clipboard-target="#url-to-copy">Скопировать ссылку</a>
                        <a href="<?=$shortUrl?>" target="_blank">Открыть в новом окне</a>
                    </div>
                    <h3>а вы — деньги на счёт!</h3>
                </section>
            </section>
            <div class="clr"></div>
        </div><!-- .main -->
    </div><!-- .middle -->
</div><!-- .wrapper -->

<?=$this->html->script(array(    '/js/users/office/PushNotificationsStatus.js', 'jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'jquery.tooltip.js', 'clipboard.min.js', 'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array('/main2.css', '/pitches2.css', '/edit','/view', '/messages12', '/pitches12', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css', '/css/users/subscribers_referal.css'), array('inline' => false))?>