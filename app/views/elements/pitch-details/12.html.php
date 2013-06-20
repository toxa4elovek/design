<?php
$details = unserialize($pitch->specifics);
?>
<script type="text/javascript">var logoProperties = <?php echo json_encode($details["audience"])?>;</script>

<?php if(isset($details['site-sub'])):?>
<h2 class="blueheading">Сколько макетов необходимо создать?</h2>
<p class="regular"><?=$details['site-sub']?> <?=$this->numInflector->formatString($details['site-sub'], array('first' => 'макет', 'second' => 'макета', 'third' => 'макетов'))?></p>
<?php endif?>

<div class="groupc">

    <?php if(!empty($details['qualities'])):?>
    <h2 class="blueheading">Какие 3 основных качества нужно донести до аудитории?</h2>
    <p class="regular"><?=$this->brief->e($details['qualities'])?></p>
    <?php endif;?>

    <?php if(!empty($details['site-image'])):?>
    <h2 class="blueheading">Какой образ о себе (продукте/услуге) вы бы хотели создать?</h2>
    <p class="regular"><?=$this->brief->e($details['site-image'])?></p>
    <?php endif;?>

    <?php if(!empty($details['site-inspiration'])):?>
    <h2 class="blueheading">Предпочтения</h2>
    <p class="regular"><?=$this->brief->e($details['site-inspiration'])?></p>
    <?php endif;?>

    <h2 class="blueheading">Целевая аудитория</h2>

    <ul class="logo-properties sliderul" data-name="audience">
        <li>
            <span class="label-a">Женщины</span>
            <span class="slider-wrapper"><span class="slider"></span></span>
            <span class="label-b">Мужчины</span>
        </li>
        <li>
            <span class="label-a">Молодой</span>
            <span class="slider-wrapper"><span class="slider"></span></span>
            <span class="label-b">Зрелый</span>
        </li>
        <li>
            <span class="label-a">Богатый</span>
            <span class="slider-wrapper"><span class="slider"></span></span>
            <span class="label-b">Экономный</span>
        </li>
        <li>
            <span class="label-a">Современный</span>
            <span class="slider-wrapper"><span class="slider"></span></span>
            <span class="label-b">Консервативный</span>
        </li>
        <li>
            <span class="label-a">Энергичный</span>
            <span class="slider-wrapper"><span class="slider"></span></span>
            <span class="label-b">Пассивный</span>
        </li>
    </ul><!-- .logo-properties -->

</div>