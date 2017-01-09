<div class="block-toggler filter-steps">
    <?php if ($link == 1):?>
    <?=$this->html->link('все решения', ['controller' => 'users', 'action' => 'solutions'], ['class' => 'link'])?> /
    <?php else:?>
    <?=$this->html->link('все решения', ['controller' => 'users', 'action' => 'solutions'], ['class' => ''])?> /
    <?php endif;?>

    <?php if ($link == 2):?>
    <?=$this->html->link('награждённые решения', ['controller' => 'users', 'action' => 'awarded'], ['class' => 'link'])?> /
    <?php else:?>
    <?=$this->html->link('награждённые решения', ['controller' => 'users', 'action' => 'awarded'], ['class' => ''])?> /
    <?php endif;?>

    <?php if ($link == 3):?>
    <?=$this->html->link('в процессе завершения', ['controller' => 'users', 'action' => 'nominated'], ['class' => 'link'])?>
    <?php else:?>
    <?=$this->html->link('в процессе завершения', ['controller' => 'users', 'action' => 'nominated'], ['class' => ''])?>
    <?php endif;?>
</div>