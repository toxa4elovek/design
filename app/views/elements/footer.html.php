<div id="fb-root"></div>
<footer class="footer">
    <div class="footer-inner">

        <ul class="social">
            <li id="facebook"><a href="http://www.facebook.com/pages/Go-Designer/160482360714084" target="_blank">Facebook</a></li>
            <li id="twitter"><a href="https://twitter.com/#!/Go_Deer" target="_blank">Twitter</a></li>
            <li id="vkontakte"><a href="http://vk.com/public36153921" target="_blank">Vkontakte</a></li>
            <li id="instagram"><a href="http://instagram.com/godesigner.ru" target="_blank">Instagram</a></li>
            <!--li id="rss"><a href="#">RSS</a></li-->
        </ul><!-- .social -->

        <nav class="nav">
            <p>
                <?php if (preg_match('@pages/about@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('О проекте', 'Pages::about', array('style' => 'color: #4a4c55')) ?> /
                <?php else: ?>
                    <?= $this->html->link('О проекте', 'http://www.godesigner.ru/pages/about') ?> /
                <?php endif ?>
                <?php if (preg_match('@pages/howitworks@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('Как это работает', 'Pages::howitworks', array('style' => 'color: #4a4c55')) ?> /
                <?php else: ?>
                    <?= $this->html->link('Как это работает', 'http://www.godesigner.ru/pages/howitworks') ?> /
                <?php endif ?>
                <?php if (preg_match('@pages/to_designers@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('Дизайнерам', 'Pages::to_designers', array('style' => 'color: #4a4c55')) ?> /
                <?php else: ?>
                    <?= $this->html->link('Дизайнерам', 'http://www.godesigner.ru/pages/to_designers') ?> /
                <?php endif ?>
                <?php if (preg_match('@answers@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('Помощь', 'Answers::index', array('style' => 'color: #4a4c55')) ?> /
                <?php else: ?>
                    <?= $this->html->link('Помощь', 'http://www.godesigner.ru/pages/answers') ?> /
                <?php endif ?>
                <?php if (preg_match('@pages/contacts@', $_SERVER["REQUEST_URI"])): ?>
                    <a href="/pages/contacts" style="color:#4a4c55">Контакты</a> /
                <?php else: ?>
                    <a href="http://www.godesigner.ru/pages/contacts">Контакты</a> /
                <?php endif ?>
                <?php if (preg_match('@pages/referal$@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('Пригласи друга', 'Pages::referal', array('style' => 'color: #4a4c55')) ?> /
                <?php else: ?>
                    <?= $this->html->link('Пригласи друга', 'http://www.godesigner.ru/pages/referal') ?> /
                <?php endif ?>
                <?php if (preg_match('@questions@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('Тест', 'Questions::index', array('style' => 'color: #4a4c55')) ?> /
                <?php else: ?>
                    <?= $this->html->link('Тест', 'http://www.godesigner.ru/questions') ?> /
                <?php endif ?>
                <?php if (preg_match('@posts$@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('Блог', 'Posts::index', array('style' => 'color: #4a4c55')) ?> /
                <?php else: ?>
                    <?= $this->html->link('Блог', 'http://www.godesigner.ru/posts') ?> /
                <?php endif ?>
                <strong><?= $this->html->link('Создать питч', 'http://www.godesigner.ru/pitches/create') ?></strong>
            </p>
        </nav><!-- .nav -->
        <ul class="pay-systems">
            <li style="width: 88px; padding-top: 0px; margin-right: 12px;"><a target="_blank" href="http://www.payanyway.ru"><img alt="PayAnyWay" src="http://www.godesigner.ru/img/88_31_paw8.gif"></a></li>
            <li><img src="http://www.godesigner.ru/img/mastercard.png" alt="MasteCard"/></li>
            <li><img src="http://www.godesigner.ru/img/visa.png" alt="Visa" /></li>
        </ul><!-- .social -->
        <p class="info"><small>Опубликуй бриф на сайте и получи дизайн за лучшую цену. Если вы хотите иметь действительно большой выбор – вам к нам!<br/> &copy; 2012 - <?= date('Y') ?> Go Designer</small></p><!-- .info -->

    </div><!-- .footer-inner -->
</footer><!-- .footer -->