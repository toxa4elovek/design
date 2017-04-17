<div class="set-price">
    <?= $this->view()->render(['element' => 'newbrief/awardfield'], ['pitch' => $pitch, 'category' => $category]); ?>
    <div class="clr"></div>
    <!-- <div id="indicator" class="indicator low tooltip" title="С помощью этой шкалы мы информируем вас о средних финансовых запросах современного фрилансера. Чем больше сумма вознаграждения, тем больше дизайнеров откликнется, тем больше вариантов на выбор вы получите."> -->
    <?php if((int) $category->id !== 22):?>
    <div id="indicator" class="indicator low" data-normal="183" data-high="366">
        <div class="bar">
            <div class="line"></div>
            <div class="shadow-b"></div>
        </div><!-- .bar -->
        <ul>
            <li>Новички
                <?php /*if (($category->id == 1) && ($this->pitch->getStatisticalAverages($category->id, 'minimal') != 0)):?>
                ~ <?= $this->pitch->getStatisticalAverages($category->id, 'minimal')?> <?= $this->NumInflector->formatString($this->pitch->getStatisticalAverages($category->id, 'minimal'), ['string' => 'решени',
                    'first' => 'е',
                    'second' => 'я',
                    'third' => 'й'])?>
                <?php else: ?>
                    мало
                <?php endif;*/?>
            </li>
            <li>Профессионалы
                <?php /*if (($category->id == 1) && ($this->pitch->getStatisticalAverages($category->id, 'normal') != 0)):?>
                    ~ <?= $this->pitch->getStatisticalAverages($category->id, 'normal')?> <?= $this->NumInflector->formatString($this->pitch->getStatisticalAverages($category->id, 'normal'), ['string' => 'решени',
                        'first' => 'е',
                        'second' => 'я',
                        'third' => 'й'])?>
                <?php else: ?>
                    хорошо
                <?php endif;*/?>
            </li>
            <li>ТОП-10
                <?php /*if (($category->id == 1) && ($this->pitch->getStatisticalAverages($category->id, 'good') != 0)):?>
                ~ <?= $this->pitch->getStatisticalAverages($category->id, 'good')?> <?= $this->NumInflector->formatString($this->pitch->getStatisticalAverages($category->id, 'good'), ['string' => 'решени',
                    'first' => 'е',
                    'second' => 'я',
                    'third' => 'й'])?>
                <?php else: ?>
                    самое то!
                <?php endif;*/?></li>
        </ul>
    </div><!-- .indicator -->
    <?php endif?>
    <?php if ($category->id != 20): ?>
    <!--img src="/img/comissions.png" style="margin-bottom: 30px;"-->
    <?php endif?>
</div><!-- .set-price -->