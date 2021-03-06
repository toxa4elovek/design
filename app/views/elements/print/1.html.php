<?php
$details = unserialize($pitch->specifics);
$types = [
    '1' => 'Типографика',
    '2' => 'Знак + название',
    '3' => 'Абстрактный знак',
    '4' => 'Эмблема',
    '5' => 'Персонаж',
    '6' => 'Буква',
    '7' => 'Вэб-кнопка'
];
?>
<script type="text/javascript">var logoProperties = <?php echo json_encode($details["logo-properties"])?>;</script>

<?php if (!empty($details['logoType'])):?>
<h2 class="blueheading">Предпочтительный вид логотипа</h2>


<div class="group">
    <ul class="look-variants">
        <?php
        foreach ($details['logoType'] as $id):?>
            <li>
                <span class="photo"><img src="/img/logo-looks/logo-looks-0<?=$id?>.jpg" alt="<?=$types[$id]?>"></span>
                <label class="supplement"><?=$types[$id]?></label>
            </li>
            <?php endforeach;?>
    </ul><!-- .look-variants -->
</div><!-- .group -->
<?php endif;?>

<?php if (!empty($details['qualities'])):?>
<h2 class="blueheading">Какие 3 качества нужно донести через логотип?</h2>
<p class="regular"><?=$details['qualities']?></p>
<?php endif?>

<div class="group">
    <h2 class="blueheading">Какими свойствами должен обладать ваш логотип?</h2>

    <ul class="logo-properties">
        <li>
            <span class="label-a">Женственный</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['logo-properties'][0] ?>.png" alt=""></span>
            <span class="label-b">Мужественный</span>
        </li>
        <li>
            <span class="label-a" style="">Молодой</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['logo-properties'][1] ?>.png" alt=""></span>
            <span class="label-b">Зрелый</span>
        </li>
        <li>
            <span class="label-a">Роскошный</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['logo-properties'][2] ?>.png" alt=""></span>
            <span class="label-b">Экономичный</span>
        </li>
        <li>
            <span class="label-a">Современный</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['logo-properties'][3] ?>.png" alt=""></span>
            <span class="label-b">Консервативный</span>
        </li>
        <li>
            <span class="label-a">Игривый</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['logo-properties'][4] ?>.png" alt=""></span>
            <span class="label-b">Серьезный</span>
        </li>
        <li>
            <span class="label-a">Простой</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['logo-properties'][5] ?>.png" alt=""></span>
            <span class="label-b">Комплексный</span>
        </li>
        <li>
            <span class="label-a">Утонченный</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['logo-properties'][6] ?>.png" alt=""></span>
            <span class="label-b">Заметный</span>
        </li>
    </ul><!-- .logo-properties -->
</div>