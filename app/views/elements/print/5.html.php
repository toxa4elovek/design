<?php
$details = unserialize($pitch->specifics);
?>
<script type="text/javascript">var logoProperties = <?php echo json_encode($details["audience"])?>;</script>

<div class="groupc">

    <?php if(!empty($details['site-units'])):?>
    <h2 class="blueheading">Необходимые единицы фирменного стиля</h2>
    <p class="regular"><?=$details['site-units']?></p>
    <?php endif;?>

    <h2 class="blueheading">Целевая аудитория</h2>

    <ul class="logo-properties sliderul" data-name="audience">
        <li>
            <span class="label-a">Женщины</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['audience'][0] ?>.png" alt=""></span>
            <span class="label-b">Мужчины</span>
        </li>
        <li>
            <span class="label-a">Молодой</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['audience'][1] ?>.png" alt=""></span>
            <span class="label-b">Зрелый</span>
        </li>
        <li>
            <span class="label-a">Богатый</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['audience'][2] ?>.png" alt=""></span>
            <span class="label-b">Экономный</span>
        </li>
        <li>
            <span class="label-a">Современный</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['audience'][3] ?>.png" alt=""></span>
            <span class="label-b">Консервативный</span>
        </li>
        <li>
            <span class="label-a">Энергичный</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['audience'][4] ?>.png" alt=""></span>
            <span class="label-b">Пассивный</span>
        </li>
    </ul><!-- .logo-properties -->
        
    <?php if(!empty($details['qualities'])):?>
    <h2 class="blueheading">Какие 3 основных качества нужно донести до аудитории?</h2>
    <p class="regular"><?=$this->brief->e($details['qualities'])?></p>
    <?php endif;?>

    <?php if(!empty($details['site-inspiration'])):?>
    <h2 class="blueheading">Предпочтения</h2>
    <p class="regular"><?=$this->brief->e($details['site-inspiration'])?></p>
    <?php endif;?>

</div>