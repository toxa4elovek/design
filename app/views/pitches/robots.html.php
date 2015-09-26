User-agent: Yandex
<?php foreach($pitches as $pitch):?>
Disallow: /pitches/view/<?=$pitch->id?>
Disallow: /pitches/details/<?=$pitch->id?>
<?php endforeach?>