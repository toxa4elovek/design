<?php
$details = unserialize($pitch->specifics);
$subValues = ['Этикетка и контрэтикетка', 'Оформление коробки, развёртки, и прочее'];
?>
<script type="text/javascript">var logoProperties = <?php echo json_encode($details["audience"])?>;</script>

<?php if (isset($details['site-sub'])):?>
<h2 class="blueheading">Сколько макетов необходимо создать?</h2>
<p class="regular"><?=$details['site-sub']?> <?=$this->numInflector->formatString($details['site-sub'], ['first' => 'макет', 'second' => 'макета', 'third' => 'макетов'])?></p>
<?php endif?>

<?php if ((isset($details['package-type'])) && (!empty($subValues[$details['package-type']]))):?>
<h2 class="blueheading">Вид упаковки</h2>
<p class="regular"><?=$subValues[$details['package-type']]?></p>
<?php endif?>

<div class="group">
    <h2 class="blueheading">Целевая аудитория</h2>

    <ul class="logo-properties">
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
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['logo-properties'][4] ?>.png" alt=""></span>
            <span class="label-b">Пассивный</span>
        </li>
    </ul><!-- .logo-properties -->
</div>

    
<?php if (!empty($details['qualities'])):?>
<h2 class="blueheading">Какие 3 основных качества нужно донести до аудитории?</h2>
<p class="regular"><?=$this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($details['qualities'])?></p>
<?php endif;?>

<?php if (!empty($details['site-inspiration'])):?>
<h2 class="blueheading">Предпочтения</h2>
<p class="regular"><?=$this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($details['site-inspiration'])?></p>
 <?php endif;?>