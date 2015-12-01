<?php
$details = unserialize($pitch->specifics);
$subValues = array('Имя / название', 'Адрес сайта', 'Слоган / лозунг');
?>
<input type="hidden" id="logo_properties" data-props="<?php echo urlencode(json_encode($details["audience"])); ?>">
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
<p class="regular"><?php echo nl2br($details['qualities'])?></p>
<?php endif?>

<div class="group">
    <h2 class="blueheading">Критерии</h2>

        <ul class="logo-properties sliderul" data-name="audience">
        <li>
            <span class="label-a">Короткое</span>
            <span class="slider-wrapper"><span class="slider"></span></span>
            <span class="label-b">Длинное</span>
        </li>
        <li>
            <span class="label-a">Локальное</span>
            <span class="slider-wrapper"><span class="slider"></span></span>
            <span class="label-b">Интернациональное</span>
        </li>
        <li>
            <span class="label-a">Существующее слово</span>
            <span class="slider-wrapper"><span class="slider"></span></span>
            <span class="label-b">Неологизм</span>
        </li>
        <li>
            <span class="label-a">Современный</span>
            <span class="slider-wrapper"><span class="slider"></span></span>
            <span class="label-b">Консервативный</span>
        </li>
        <li>
            <span class="label-a">Эмоциональное</span>
            <span class="slider-wrapper"><span class="slider"></span></span>
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