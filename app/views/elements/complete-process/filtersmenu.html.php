<div class="block-toggler" style="margin:10px 0 0 15px;font-family: 'RodeoC';font-size:13px">
    <?php if($link == 1):?>
    <?=$this->html->link('все решения', array('controller' => 'users', 'action' => 'solutions'), array('class' => 'link'))?> /
    <?php else:?>
    <?=$this->html->link('все решения', array('controller' => 'users', 'action' => 'solutions'), array('class' => 'ajaxoffice'))?> /
    <?php endif;?>

    <?php if($link == 2):?>
    <?=$this->html->link('награждённые решения', array('controller' => 'users', 'action' => 'awarded'), array('class' => 'link'))?> /
    <?php else:?>
    <?=$this->html->link('награждённые решения', array('controller' => 'users', 'action' => 'awarded'), array('class' => 'ajaxoffice'))?> /
    <?php endif;?>

    <?php if($link == 3):?>
    <?=$this->html->link('в процессе завершения', array('controller' => 'users', 'action' => 'nominated'), array('class' => 'link'))?>
    <?php else:?>
    <?=$this->html->link('в процессе завершения', array('controller' => 'users', 'action' => 'nominated'), array('class' => 'ajaxoffice'))?>
    <?php endif;?>
</div>