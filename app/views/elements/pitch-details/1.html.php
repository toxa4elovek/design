<?php
$details = unserialize($pitch->specifics);
$types = array(
    '1' => 'Типографика',
    '2' => 'Знак + название',
    '3' => 'Абстрактный знак',
    '4' => 'Эмблема',
    '5' => 'Персонаж',
    '6' => 'Буква',
    '7' => 'Вэб-кнопка'
);
?>
<input type="hidden" id="logo_properties" data-props="<?php echo urlencode(json_encode($details["logo-properties"])); ?>">

<?php if(!empty($details['logoType'])):?>
<h2 class="blueheading">Предпочтительный вид логотипа</h2>


<div class="group">
    <ul class="look-variants">
        <?php
        foreach($details['logoType'] as $id):?>
            <li>
                <span class="photo"><img src="/img/logo-looks/logo-looks-0<?=$id?>.jpg" alt="<?=$types[$id]?>"></span>
                <label class="supplement"><?=$types[$id]?></label>
            </li>
            <?php endforeach;?>
    </ul><!-- .look-variants -->
</div><!-- .group -->
<?php endif;?>

<?php if(!empty($details['qualities'])):?>
<h2 class="blueheading">Какие 3 качества нужно донести через логотип?</h2>
<p class="regular"><?php echo nl2br($details['qualities'])?></p>
<?php endif?>

<div class="group">
    <h2 class="blueheading">Какими свойствами должен обладать ваш логотип?</h2>

    <ul class="logo-properties">
        <li>
            <span class="label-a">Женственный</span>
            <span class="slider-wrapper"><span class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 50%; "></a></span></span>
            <span class="label-b">Мужественный</span>
        </li>
        <li>
            <span class="label-a" style="">Молодой</span>
            <span class="slider-wrapper"><span class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 50%; "></a></span></span>
            <span class="label-b">Зрелый</span>
        </li>
        <li>
            <span class="label-a">Роскошный</span>
            <span class="slider-wrapper"><span class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 50%; "></a></span></span>
            <span class="label-b">Экономичный</span>
        </li>
        <li>
            <span class="label-a">Современный</span>
            <span class="slider-wrapper"><span class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 50%; "></a></span></span>
            <span class="label-b">Консервативный</span>
        </li>
        <li>
            <span class="label-a">Игривый</span>
            <span class="slider-wrapper"><span class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 50%; "></a></span></span>
            <span class="label-b">Серьезный</span>
        </li>
        <li>
            <span class="label-a">Простой</span>
            <span class="slider-wrapper"><span class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 50%; "></a></span></span>
            <span class="label-b">Комплексный</span>
        </li>
        <li>
            <span class="label-a">Утонченный</span>
            <span class="slider-wrapper"><span class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 50%; "></a></span></span>
            <span class="label-b">Заметный</span>
        </li>
    </ul><!-- .logo-properties -->
</div>