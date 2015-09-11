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
    ?>
    <label>Сумма вознаграждения победителю (от <span id="labelPrice"><?= $this->moneyFormatter->formatMoney($low, array('suffix' => 'Р.')) ?></span>) <?= $this->view()->render(array('element' => 'newbrief/required_star')) ?><!--a href="#" class="second tooltip" title="Здесь вам нужно указать, сколько заработает победитель. Эта сумма не включает сбора Go Designer и стоимость опций.">(?)</a--></label>
    <input type="text" name="awardField" id="award" data-low="<?= $defaultLow ?>" data-normal="<?= $category->normalAward ?>" data-high="<?= $category->goodAward ?>" data-low-def="<?= $defaultLow ?>" data-normal-def="<?= $category->normalAward ?>" data-high-def="<?= $category->goodAward ?>" data-option-title="<?php echo ($category->id == 7) ? 'Награда копирайтеру' : 'Награда Дизайнеру' ?>" data-minimal-award="<?= $low ?>" class="<?php if(!$pitch):?>nitial-price placeholder<?php endif; ?> " placeholder="<?= (date('N') > 5) ? $category->discountPrice : round(($category->goodAward + $category->normalAward) / 2) ?>" value="<?php
    if($pitch):
        echo (int) $pitch->price;
    else:
        echo (date('N') > 5) ? $category->discountPrice : round(($category->goodAward + $category->normalAward) / 2);
    endif;
    ?>">
</p>