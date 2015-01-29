<p>
    <label>Сумма вознаграждения победителю (от <span id="labelPrice"><?= $this->moneyFormatter->formatMoney((date('N') > 5) ? $category->discountPrice : $category->minAward, array('suffix' => 'Р.')) ?></span>) <?= $this->view()->render(array('element' => 'newbrief/required_star')) ?><!--a href="#" class="second tooltip" title="Здесь вам нужно указать, сколько заработает победитель. Эта сумма не включает сбора Go Designer и стоимость опций.">(?)</a--></label>
    <input type="text" name="" id="award" data-low="<?= (date('N') > 5) ? $category->discountPrice : $category->minAward ?>" data-normal="<?= $category->normalAward ?>" data-high="<?= $category->goodAward ?>" data-low-def="<?= (date('N') > 5) ? $category->discountPrice : $category->minAward ?>" data-normal-def="<?= $category->normalAward ?>" data-high-def="<?= $category->goodAward ?>" data-option-title="<?php echo ($category->id == 7) ? 'Награда копирайтеру' : 'Награда Дизайнеру' ?>" data-minimal-award="<?= (date('N') > 5) ? $category->discountPrice : $category->minAward ?>" class="<?php if(!$pitch):?>nitial-price placeholder<?php endif; ?> " placeholder="<?= (date('N') > 5) ? $category->discountPrice : $category->minAward ?>" value="<?php
    if($pitch):
        echo (int) $pitch->price;
    else:
        echo (date('N') > 5) ? $category->discountPrice : $category->minAward;
    endif;
    ?>">
</p>