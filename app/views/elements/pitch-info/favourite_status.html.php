<?php if(($this->user->isLoggedIn()) && ($this->user->hasFavouritePitches()) && (!$this->user->isPitchFavourite($pitch->id))):?>
    <a class="order-button rss-img" data-pitchid="<?=$pitch->id?>" id="fav" data-type="add" href="#" title="Добавить в избранное">Добавить в избранное</a>
<?php elseif($this->user->isLoggedIn()):?>
    <a class="order-button" style="width: 100%;" data-pitchid="<?=$pitch->id?>" id="fav" data-type="remove" href="#" title="Удалить из избранного">Убрать из избранного</a>
<?php endif?>