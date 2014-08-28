<input type="hidden" value="<?=$pitch->id?>" name="pitch_id">
<div id="pitch-title" style="height:36px;margin-bottom:5px;">
    <div class="breadcrumbs-view" style="width: 840px; margin: 30px 0 20px 0; float:left;">
        <a href="/pitches">Все питчи /</a> <a href="/pitches/view/<?=$pitch->id?>"><?=$pitch->title?></a>
    </div>
</div>
<div class="clr" style="width: 840px; margin-bottom: 30px;">
<?php if (!$this->user->isLoggedIn()): ?>
    <?=$this->view()->render(array('element' => 'pitch-info/anonyms_infotable'), array('pitch' => $pitch))?>
<?php else: ?>
    <?php if(!$this->user->isPitchOwner($pitch->user_id)): ?>
        <?=$this->view()->render(array('element' => 'pitch-info/designers_infotable'), array('pitch' => $pitch))?>
    <?php else: ?>
        <?=$this->view()->render(array('element' => 'pitch-info/clients_infotable'), array('pitch' => $pitch))?>
    <?php endif ?>
<?php endif; ?>
</div>
