<div class="status<?php echo ($type == 'designer') ? '' : ' for-client'; ?>">
    <?php if ($type == 'designer'):?>
        <?php if ($step == 1):?>
        <div class="requisites_active">
        <?php else:?>
        <div class="requisites">
        <?php endif?>
        <?=$this->html->link('Реквизиты', ['controller' => 'users', 'action' => 'step1', 'id' => $solution->id])?>
        </div>
    <?php endif;?>

    <?php if ($solution->pitch->category_id != 7):?>
        <?php if ($step == 2):?>
        <div class="requisites_active layouts<?php echo ($type == 'designer') ? '' : ' for-client'; ?>">
        <?php else:?>
        <div class="requisites layouts<?php echo ($type == 'designer') ? '' : ' for-client'; ?>">
        <?php endif?>
        <?=$this->html->link('Доработка макетов', ['controller' => 'users', 'action' => 'step2', 'id' => $solution->id])?>
        </div>
        <?php if ($step == 3):?>
        <div class="source_active<?php echo ($type == 'designer') ? '' : ' for-client'; ?>">
        <?php else:?>
            <?php if ($solution->step > 2):?>
                <div class="source<?php echo ($type == 'designer') ? '' : ' for-client'; ?>">
            <?php else:?>
                <div class="source-nonactive<?php echo ($type == 'designer') ? '' : ' for-client'; ?>">
            <?php endif?>
        <?php endif?>
        <?php if ($solution->step > 2):?>
            <?=$this->html->link('Предоставление исходников', ['controller' => 'users', 'action' => 'step3', 'id' => $solution->id])?>
        <?php else:?>
            <span>Предоставление исходников</span>
        <?php endif?>
        </div>
    <?php else: ?>
        <?php if ($step == 2): ?>
            <div class="requisites_active layouts<?php echo ($type == 'designer') ? '' : ' for-client'; ?>">
        <?php else:?>
            <div class="requisites layouts<?php echo ($type == 'designer') ? '' : ' for-client'; ?>">
        <?php endif; ?>
            <?=$this->html->link('Доработка', ['controller' => 'users', 'action' => 'step2', 'id' => $solution->id])?>
            </div>
    <?php endif?>

    <?php if ($step == 4):?>
    <div class="rating_active<?php echo ($type == 'designer') ? '' : ' for-client'; ?>">
    <?php else:?>
        <?php if ($solution->step > 3):?>
            <div class="rating<?php echo ($type == 'designer') ? '' : ' for-client'; ?>">
        <?php else:?>
            <div class="rating-nonactive<?php echo ($type == 'designer') ? '' : ' for-client'; ?>">
        <?php endif?>
    <?php endif;?>

    <?php if ($solution->step > 3):?>
        <?=$this->html->link('Рейтинг', ['controller' => 'users', 'action' => 'step4', 'id' => $solution->id])?>
    <?php else:?>
        <span>Рейтинг</span>
    <?php endif?>
    </div>
    <div class="clr"></div>
</div>