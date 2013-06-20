<div class="about_pitch">
    <ul class="pitch_info">
        <li id="client_name"><?=$this->html->link($this->nameInflector->renderName($pitch->user->first_name, $pitch->user->last_name), array('users::view', 'id' => $pitch->user->id), array('class' => 'client-link'))?></li>
        <li id="cena"><?=$this->moneyFormatter->formatMoney($pitch->price, array('suffix' => 'р.-'))?></li>
        <li id="period" style="padding-top:1px;">
            <?php if($pitch->status == 0):?>
            <?=preg_replace('@(м).*@', '$1. ', preg_replace('@(ч).*@', '$1. ', preg_replace('@(.*)(дн).*?\s@', '$1$2. ', $pitch->startedHuman)))?></li>
            <?php elseif($pitch->status == 1):?>
            Выбор победителя
            <?php elseif($pitch->status == 2):?>
            Питч завершен
            <?php endif?>
        <li id="solutions"><?=$pitch->ideas_count ?> <?=$this->numInflector->formatString($pitch->ideas_count, array(
            'string' => "решен",
            'first' => 'ие',
            'second' => 'ия',
            'third' => 'ий'
        ));?></li>
    </ul>
    <input type="hidden" value="<?=$pitch->ideas_count?>" id="hidden-solutions-count"/>
    <ul class="social_share">
        <li><a data-pitchid="<?=$pitch->id?>" id="fav" href="#"><img width="29" height="29" alt="" src="/img/1.gif" class="favourites"><div class="tooltip">добавить в избранное</div></a></li>
        <!--li><a href="http://www.facebook.com/pages/Go-Designer/160482360714084"><img width="29" height="29" alt="" src="/img/1.gif" class="facebook"><div class="tooltip">страница в facebook</div></a></li>
        <li><a href="https://twitter.com/#!/Go_Deer"><img width="29" height="29" alt="" src="/img/1.gif" class="twitter"><div class="tooltip">наш twitter</div></a></li-->
    </ul>
</div>

<div class="pitch_name">
    <?=$this->html->link($pitch->title, array('controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id))?>
</div>