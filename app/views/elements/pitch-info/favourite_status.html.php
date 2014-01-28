<?php if(($this->user->isLoggedIn()) && ($this->user->hasFavouritePitches()) && (!$this->user->isPitchFavourite($pitch->id))):?>
    <div style="width:36px;height:36px;float:right;margin-right: 20px;">
        <a data-pitchid="<?=$pitch->id?>" id="fav" data-type="add" href="#" title="Добавить в избранное"><img class="fav-plus" alt="добавить в избранное" src="/img/plus 2.png"></a>
    </div>
<?php elseif($this->user->isLoggedIn()):?>
    <div style="width:36px;height:36px;float:right;margin-right: 20px;">
        <a data-pitchid="<?=$pitch->id?>" id="fav" data-type="remove" href="#" title="Удалить из избранного"><img class="fav-minus" alt="Удалить из избранного" src="/img/minus.png"></a>
    </div>
<?php endif?>