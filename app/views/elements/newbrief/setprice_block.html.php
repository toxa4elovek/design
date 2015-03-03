<div class="set-price">
    <?= $this->view()->render(array('element' => 'newbrief/awardfield'), array('pitch' => $pitch, 'category' => $category)); ?>
    <div class="clr"></div>
    <!-- <div id="indicator" class="indicator low tooltip" title="С помощью этой шкалы мы информируем вас о средних финансовых запросах современного фрилансера. Чем больше сумма вознаграждения, тем больше дизайнеров откликнется, тем больше вариантов на выбор вы получите."> -->
    <div id="indicator" class="indicator low" data-normal="183" data-high="366">
        <div class="bar">
            <div class="line"></div>
            <div class="shadow-b"></div>
        </div><!-- .bar -->
        <ul>
            <li>мало</li>
            <li>хорошо</li>
            <li>самое то!</li>
        </ul>
    </div><!-- .indicator -->
    <img src="/img/comissions.png" style="margin-bottom: 30px;">
</div><!-- .set-price -->