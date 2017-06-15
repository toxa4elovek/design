<div id="fb-root"></div>
<footer class="footer">
    <div class="footer-inner">

        <ul class="social">
            <li id="facebook"><!--noindex--><a rel="nofollow" href="http://www.facebook.com/pages/Go-Designer/160482360714084" target="_blank">Facebook</a><!--/noindex--></li>
            <li id="twitter"><!--noindex--><a rel="nofollow" href="https://twitter.com/#!/Go_Deer" target="_blank">Twitter</a><!--/noindex--></li>
            <li id="vkontakte"><!--noindex--><a rel="nofollow" href="http://vk.com/public36153921" target="_blank">Vkontakte</a><!--/noindex--></li>
            <li id="instagram"><!--noindex--><a rel="nofollow" href="http://instagram.com/godesigner.ru" target="_blank">Instagram</a><!--/noindex--></li>
            <!--li id="rss"><a href="#">RSS</a></li-->
        </ul><!-- .social -->

        <nav class="nav">
            <p>
                <?php if (preg_match('@pages/about@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('О проекте', 'Pages::about', ['style' => 'color: #4a4c55']) ?> /
                <?php else: ?>
                    <?= $this->html->link('О проекте', '/pages/about') ?> /
                <?php endif ?>
                <?php if (preg_match('@pages/howitworks@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('Как это работает', 'Pages::howitworks', ['style' => 'color: #4a4c55']) ?> /
                <?php else: ?>
                    <?= $this->html->link('Как это работает', '/pages/howitworks') ?> /
                <?php endif ?>
                <?php if (preg_match('@pages/to_designers@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('Дизайнерам', 'Pages::to_designers', ['style' => 'color: #4a4c55']) ?> /
                <?php else: ?>
                    <?= $this->html->link('Дизайнерам', '/pages/to_designers') ?> /
                <?php endif ?>
                <?php if (preg_match('@pages/referal$@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('Пригласи друга', 'Pages::referal', ['style' => 'color: #4a4c55']) ?> /
                <?php else: ?>
                    <?= $this->html->link('Пригласи друга', '/pages/referal') ?> /
                <?php endif ?>
                <?php if (preg_match('@questions@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('Тест', 'Questions::index', ['style' => 'color: #4a4c55']) ?> /
                <?php else: ?>
                    <?= $this->html->link('Тест', '/questions') ?> /
                <?php endif ?>
                <?php if (preg_match('@posts$@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('Блог', 'Posts::index', ['style' => 'color: #4a4c55']) ?> /
                <?php else: ?>
                    <?= $this->html->link('Блог', '/posts') ?> /
                <?php endif ?>
                <?php if (preg_match('@news@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('Лента', '/news', ['style' => 'color: #4a4c55']) ?> /
                <?php else: ?>
                    <?= $this->html->link('Лента', '/news') ?> /
                <?php endif ?>
                <?php if (preg_match('@answers@', $_SERVER["REQUEST_URI"])): ?>
                    <?= $this->html->link('Помощь', 'Answers::index', ['style' => 'color: #4a4c55']) ?> /
                <?php else: ?>
                    <?= $this->html->link('Помощь', '/answers') ?> /
                <?php endif ?>
                <?php if (preg_match('@pages/contacts@', $_SERVER["REQUEST_URI"])): ?>
                    <a href="/pages/contacts" style="color:#4a4c55">Контакты</a>
                <?php else: ?>
                    <a href="/pages/contacts">Контакты</a>
                <?php endif ?>
                <br>
                <?= $this->html->link('Создать проект', '/pitches/create', ['class' => 'bottom-link-footer', 'style' => 'margin-left: 0;']) ?> /
                    &nbsp;<?= $this->html->link('Логотип в один клик', '/pages/fastpitch', ['style' => 'margin-left: 0;']) ?> /
                &nbsp;<?= $this->html->link('Распродажа логотипов', '/logosale', ['style' => 'margin-left: 0;']) ?> /
                &nbsp;<?= $this->html->link('Абонентское обслуживание', '/pages/subscribe', ['style' => 'margin-left: 0;']) ?> /
                <strong>&nbsp;<?= $this->html->link('Золотая рыбка', '/golden-fish', ['style' => 'margin-left: 0;color: #5c9263']) ?></strong>
            </p>
        </nav><!-- .nav -->
        <ul class="pay-systems">
            <li style="width: 88px; padding-top: 0; margin-right: 12px;"></li>
            <li><img src="/img/mastercard.png" alt="MasteCard"/></li>
            <li><img src="/img/visa.png" alt="Visa" /></li>
        </ul><!-- .social -->
        <p class="info"><small>Опубликуй бриф на сайте и получи дизайн за лучшую цену. Если вы хотите иметь действительно большой выбор &mdash; вам к нам!<br/> &copy; 2012–<?= date('Y') ?> Go Designer</small></p><!-- .info -->

    </div><!-- .footer-inner -->
</footer><!-- .footer -->