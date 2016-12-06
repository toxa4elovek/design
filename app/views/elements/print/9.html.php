<?php
$details = unserialize($pitch->specifics);
if(isset($details['logo-properties'])) {
    $sliders = $details['logo-properties'];
}else if($details['audience']) {
    $sliders = $details["audience"];
}
?>
<script type="text/javascript">var logoProperties = <?php echo json_encode($sliders)?>;</script>

<?php if(isset($details['site-sub'])):?>
<h2 class="blueheading">Сколько иллюстраций необходимо создать?</h2>
<p class="regular"><?=$details['site-sub']?> <?=$this->numInflector->formatString($details['site-sub'], array('first' => 'иллюстрация', 'second' => 'иллюстрации', 'third' => 'иллюстраций'))?></p>
<?php endif?>

<div class="groupc">
    <h2 class="blueheading">Целевая аудитория</h2>

    <ul class="logo-properties sliderul" data-name="audience">
        <li>
            <span class="label-a">Женщины</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $sliders[0] ?>.png" alt=""></span>
            <span class="label-b">Мужчины</span>
        </li>
        <li>
            <span class="label-a">Молодой</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $sliders[1] ?>.png" alt=""></span>
            <span class="label-b">Зрелый</span>
        </li>
        <li>
            <span class="label-a">Богатый</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $sliders[2] ?>.png" alt=""></span>
            <span class="label-b">Экономный</span>
        </li>
        <li>
            <span class="label-a">Современный</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $sliders[3] ?>.png" alt=""></span>
            <span class="label-b">Консервативный</span>
        </li>
        <li>
            <span class="label-a">Энергичный</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $sliders[4] ?>.png" alt=""></span>
            <span class="label-b">Пассивный</span>
        </li>
    </ul><!-- .logo-properties -->

    <?php if(!empty($details['site-inspiration'])):?>
    <h2 class="blueheading">Предпочтения</h2>
    <p class="regular"><?=$this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($details['site-inspiration'])?></p>
    <?php endif;?>
    
</div>