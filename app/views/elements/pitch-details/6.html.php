<?php
$details = unserialize($pitch->specifics);
?>
<input type="hidden" id="logo_properties" data-props="<?php echo urlencode(json_encode($details["audience"])); ?>">
<?php if (isset($details['site-sub'])):?>
<h2 class="blueheading">Сколько страниц необходимо создать?</h2>
<p class="regular"><?php echo nl2br($details['site-sub'])?> <?=$this->numInflector->formatString($details['site-sub'], ['first' => 'страница', 'second' => 'страницы', 'third' => 'страниц'])?></p>
<?php endif?>

<div class="groupc">

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

    <?php if (!empty($details['site-inspiration'])):?>
    <h2 class="blueheading">Предпочтения</h2>
    <p class="regular"><?=$this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($details['site-inspiration'])?></p>
    <?php endif;?>

    <?php if (!empty($details['qualities'])):?>
    <h2 class="blueheading">Какие 3 основных качества нужно донести до аудитории?</h2>
    <p class="regular"><?=$this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($details['qualities'])?></p>
    <?php endif;?>

</div>