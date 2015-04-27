<?php if(($this->user->isLoggedIn()) && ($this->user->hasFavouritePitches()) && (!$this->user->isPitchFavourite($pitch->id))):?>
    <a class="order-button rss-img" data-pitchid="<?=$pitch->id?>" id="fav" data-type="add" href="#" title="Следить за проектом">Следить за проектом</a>
<?php elseif($this->user->isLoggedIn()):?>
    <a class="order-button rss-img" style="width: 100%;" data-pitchid="<?=$pitch->id?>" id="fav" data-type="remove" href="#" title="Перестать следить">Перестать следить</a>
<?php endif?>