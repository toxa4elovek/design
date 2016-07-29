<p>
    <?php
    $defaultLow = (date('N') > 5) ? $category->discountPrice : $category->minAward;
    if(($pitch) && $pitch->category_id == 11) {
        $specifics = unserialize($pitch->specifics);
        if($specifics['package-type'] == 1) {
            $defaultLow = '22500';
        }
    }
    $low = $defaultLow;

    if($category->id == 20):
        $labelText = '1. Укажите вознаграждения победителю';
    else:
        $labelText = 'Сумма вознаграждения победителю';
    endif;
    ?>

    <label><?=$labelText?> (от <span id="labelPrice"><?= $this->moneyFormatter->formatMoney($low, array('suffix' => 'Р.')) ?></span>) <?= $this->view()->render(array('element' => 'newbrief/required_star')) ?></label>
    <input type="text" name="" id="award" data-low="<?= $defaultLow ?>" data-normal="<?= $category->normalAward ?>" data-high="<?= $category->goodAward ?>" data-low-def="<?= $defaultLow ?>" data-normal-def="<?= $category->normalAward ?>" data-high-def="<?= $category->goodAward ?>" data-option-title="<?php echo ($category->id == 7) ? 'Награда копирайтеру' : 'Награда Дизайнеру' ?>" data-minimal-award="<?= $low ?>" class="<?php if(!$pitch):?>initial-price placeholder<?php endif; ?> " <?php if(date('N') > 5): echo 'data-discount="true"'; else: echo 'data-discount="false"'; endif;?> placeholder="<?= (date('N') > 5) ? $category->discountPrice : round(($category->goodAward + $category->normalAward) / 2) ?>" value="<?php

    if($pitch):
        echo (int) $pitch->price;
    else:
        echo (date('N') > 5) ? $category->discountPrice : round(($category->goodAward + $category->normalAward) / 2);
    endif;
    ?>">
    <?php if($category->id == 1):?>
    <div id="fastpitch-tooltip" style="top: 216px;left: 240px;display: none;
    position: absolute;width: 285px;
    padding-left: 30px;
    padding-top: 13px;
height: 137px;
background: url(/img/brief/fastpitch_tooltip.png) left top no-repeat;
font-family: Arial;
font-size: 14px;
font-weight: 400;
line-height: 22px;text-transform: none;">
    Кейс «<a href="/pages/fastpitch" target="_blank">Логотип в один клик</a>» экономит<br/> 4530 руб. и час на заполнение брифа, включает самый популярный набор опций</br> и стоит всего 19800 руб.,<br/>
    включая сборы. <a href="/pages/fastpitch" target="_blank">Подробнее</a>...
</div>
    <?php endif;?>
</p>