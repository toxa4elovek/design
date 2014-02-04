<div class="status">
    <?php if($type == 'designer'):?>
        <?php if($step == 1):?>
        <div class="requisites_active">
        <?php else:?>
        <div class="requisites">
        <?php endif?>
        <?=$this->html->link('Реквизиты', array('controller' => 'users', 'action' => 'step1', 'id' => $solution->id))?>
        </div>
    <?php endif;?>

    <?php if($solution->pitch->category_id != 7):?>
        <?php if($step == 2):?>
        <div class="requisites_active layouts">
        <?php else:?>
        <div class="requisites layouts" <?php if($type == 'designer'): echo ''; endif;?>>
        <?php endif?>
        <?=$this->html->link('Доработка макетов', array('controller' => 'users', 'action' => 'step2', 'id' => $solution->id))?>
        </div>
        <?php if($step == 3):?>
        <div class="source_active">
        <?php else:?>
            <?php if($solution->step > 2):?>
                <div class="source">
            <?php else:?>
                <div class="source-nonactive">
            <?php endif?>
        <?php endif?>
        <?php if($solution->step > 2):?>
            <?=$this->html->link('Предоставление исходников', array('controller' => 'users', 'action' => 'step3', 'id' => $solution->id))?>
        <?php else:?>
            <span>Предоставление исходников</span>
        <?php endif?>
        </div>
    <?php else: ?>
        <?php if ($step == 2): ?>
            <div class="requisites_active layouts">
        <?php else:?>
            <div class="requisites layouts" <?php if($type == 'designer'): echo ''; endif;?>>
        <?php endif; ?>
            <?=$this->html->link('Доработка', array('controller' => 'users', 'action' => 'step2', 'id' => $solution->id))?>
            </div>
    <?php endif?>

    <?php if($step == 4):?>
    <div class="rating_active">
    <?php else:?>
        <?php if($solution->step > 3):?>
            <div class="rating">
        <?php else:?>
            <div class="rating-nonactive">
        <?php endif?>
    <?php endif;?>

    <?php if($solution->step > 3):?>
        <?=$this->html->link('Рейтинг', array('controller' => 'users', 'action' => 'step4', 'id' => $solution->id))?>
    <?php else:?>
        <span>Рейтинг</span>
    <?php endif?>
    </div>
    <div class="clr"></div>
</div>