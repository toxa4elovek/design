<?php if(!$this->user->isPitchOwner($pitch->user_id) || $pitch->status > 0): ?>
    <?=$this->view()->render(array('element' => 'pitch-info/designers_infotable'), array('pitch' => $pitch))?>
<?php else: ?>
    <?=$this->view()->render(array('element' => 'pitch-info/clients_infotable'), array('pitch' => $pitch))?>
<?php endif ?>