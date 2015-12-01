<?php
$details = unserialize($pitch->specifics);
$subValues = array('Имя / название', 'Адрес сайта', 'Слоган / лозунг');
?>
<script type="text/javascript">var logoProperties = <?php echo json_encode($details["audience"])?>;</script>
<h2 class="blueheading">Вид копирайтинга</h2>
<?php if(isset($details['first-option'])):?>
<p class="regular"><?=$subValues[0]?></p>
<?php endif;
if(isset($details['second-option'])):?>
<p class="regular"><?=$subValues[1]?></p>
<?php endif;
if(isset($details['third-option'])):?>
<p class="regular"><?=$subValues[2]?></p>
<?php endif;?>

<?php if(!empty($details['qualities'])):?>
<h2 class="blueheading">Какие три качества должно отражать название/слоган?</h2>
<p class="regular"><?=$details['qualities']?></p>
<?php endif?>

<div class="group">
    <h2 class="blueheading">Критерии</h2>

        <ul class="logo-properties sliderul" data-name="audience">
        <li>
            <span class="label-a">Короткое</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['audience'][0] ?>.png" alt=""></span>
            <span class="label-b">Длинное</span>
        </li>
        <li>
            <span class="label-a">Локальное</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['audience'][1] ?>.png" alt=""></span>
            <span class="label-b">Интернациональное</span>
        </li>
        <li>
            <span class="label-a">Существующее слово</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['audience'][2] ?>.png" alt=""></span>
            <span class="label-b">Неологизм</span>
        </li>
        <li>
            <span class="label-a">Современный</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['audience'][3] ?>.png" alt=""></span>
            <span class="label-b">Консервативный</span>
        </li>
        <li>
            <span class="label-a">Эмоциональное</span>
            <span style="float:left;width:319px;margin: 0 12px; padding: 0 9px"><img width="319" src="/img/print/<?= $details['audience'][4] ?>.png" alt=""></span>
            <span class="label-b">Рациональное</span>
        </li>
    </ul><!-- .logo-properties -->
</div>

<?php if(!empty($details['copy-extra'])):?>
<h2 class="blueheading">Есть что добавить?</h2>
<p class="regular"><?=$this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($details['copy-extra'])?></p>
<?php endif;?>
    
<?php if(!empty($details['copy-ref'])):?>
<h2 class="blueheading">Референсы</h2>
<p class="regular"><?=$this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($details['copy-ref'])?></p>
<?php endif;?>