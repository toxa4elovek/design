<?php
$details = unserialize($pitch->specifics);
if ((strtotime("2012-10-10 16:00:00") >= strtotime($pitch->started))):
    $subValues = ['главная и одна внутренная', 'главная и 3 внутренних', 'главная и 2 внутренних', '= /> 5 страниц'];
else:
    $subValues = ['главная страница', 'главная и 1 внутренняя', 'главная и 2 внутренних', '= /> 4 страниц'];
endif;
?>
<script type="text/javascript">var logoProperties = <?php echo json_encode($details["audience"])?>;</script>
<?php if ((!empty($subValues[$details['site-sub']])) && (strtotime($pitch->started) < strtotime('2012-11-19 12:00:00'))):?>
<h2 class="blueheading">Сколько шаблонов страниц необходимо создать для этого сайта</h2>
<p class="regular"><?=$subValues[$details['site-sub']]?></p>
<?php else:?>
<h2 class="blueheading">Сколько шаблонов страниц необходимо создать для этого сайта</h2>
<p class="regular"><?=$details['site-sub']?> <?=$this->numInflector->formatString($details['site-sub'], ['first' => 'страница', 'second' => 'страницы', 'third' => 'страниц'])?></p>
<?php endif?>

<?php if (!empty($details['site-existing'])):?>
<h2 class="blueheading">Cуществующий сайт</h2>
<p class="regular"><?= $this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($details['site-existing'])?></p>
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
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['audience'][4] ?>.png" alt=""></span>
            <span class="label-b">Пассивный</span>
        </li>
    </ul><!-- .logo-properties -->
</div>

<?php if (!empty($details['site-inspiration'])):?>
<h2 class="blueheading">Какие сайты вам нравятся? Откуда дизайнерам черпать вдохновение</h2>
<p class="regular"><?=$this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($details['site-inspiration'])?></p>
<?php endif;?>

<?php if (!empty($details['qualities'])):?>
<h2 class="blueheading">Какие 3 основных качества нужно донести до аудитории?</h2>
<p class="regular"><?=$details['qualities']?></p>
<?php endif;?>
