<input type="hidden" value="<?=$pitch->id?>" name="pitch_id">
<div id="pitch-title" style="height:36px;margin-bottom:5px;">
    <div class="breadcrumbs-view" style="width: 840px; margin: 30px 0 20px 0; float:left;">
        <a href="/pitches">Все проекты /</a> <a href="/pitches/view/<?=$pitch->id?>"><?=$pitch->title?></a>
    </div>
</div>
<div class="clr" style="width: 840px; margin-bottom: 30px;">
<?php if (!$this->user->isLoggedIn()): ?>
    <?=$this->view()->render(['element' => 'pitch-info/anonyms_infotable'], ['pitch' => $pitch])?>
<?php else: ?>
    <?php if (!$this->user->isPitchOwner($pitch->user_id)): ?>
        <?=$this->view()->render(['element' => 'pitch-info/designers_infotable'], ['pitch' => $pitch])?>
    <?php else: ?>
        <?=$this->view()->render(['element' => 'pitch-info/clients_infotable'], ['pitch' => $pitch])?>
    <?php endif ?>
<?php endif; ?>
</div>
