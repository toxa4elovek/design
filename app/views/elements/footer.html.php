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
                <?php
                $textStyle = 'color: #4a4c55; margin-right: 4px;';
                $defaultOptions = ['textStyle' => $textStyle];
                $firstBottomOptions = [
                    'textStyle' => 'color: #4a4c55; margin-right: 4px; margin-left: 0;',
                    'linkStyle' => 'margin-right: 4px; margin-left: 0;',
                ];
                $goldFishOptions = [
                    'textStyle' => $textStyle,
                    'linkStyle' => 'color: #5c9263; margin-right: 4px; font-weight: bold;',
                ]
                ?>
                <?= $this->htmlExtended->seoLink('О проекте', '/pages/about/', $defaultOptions)?> /
                <?= $this->htmlExtended->seoLink('Как это работает', '/pages/howitworks/', $defaultOptions)?> /
                <?= $this->htmlExtended->seoLink('Политика конфиденциальности', '/answers/view/108/', $defaultOptions)?> /
                <?= $this->htmlExtended->seoLink('Блог', '/posts/', $defaultOptions)?> /
                <?= $this->htmlExtended->seoLink('Лента', '/news/', $defaultOptions)?> /
                <?= $this->htmlExtended->seoLink('Помощь', '/answers/', $defaultOptions)?> /
                <?= $this->htmlExtended->seoLink('Контакты', '/pages/contacts/', $defaultOptions)?>
                <br>
                <?= $this->htmlExtended->seoLink('Создать проект', '/pitches/create/', $firstBottomOptions)?> /
                <?= $this->htmlExtended->seoLink('Логотип в один клик', '/fastpitch/', $defaultOptions)?> /
                <?= $this->htmlExtended->seoLink('Распродажа логотипов', '/logosale/', $defaultOptions)?> /
                <?= $this->htmlExtended->seoLink('Абонентское обслуживание', '/pages/subscribe/', $defaultOptions)?> /
                <?= $this->htmlExtended->seoLink('Золотая рыбка', '/golden-fish/', $goldFishOptions)?>
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