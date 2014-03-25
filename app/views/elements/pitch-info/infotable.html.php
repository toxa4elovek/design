<?php if (!$this->user->isLoggedIn()): ?>
    <?=$this->view()->render(array('element' => 'pitch-info/anonyms_infotable'), array('pitch' => $pitch))?>
<?php else: ?>
    <?php if(!$this->user->isPitchOwner($pitch->user_id)): ?>
        <?=$this->view()->render(array('element' => 'pitch-info/designers_infotable'), array('pitch' => $pitch))?>
    <?php else: ?>
        <?=$this->view()->render(array('element' => 'pitch-info/clients_infotable'), array('pitch' => $pitch))?>
    <?php endif ?>
<?php endif; ?>