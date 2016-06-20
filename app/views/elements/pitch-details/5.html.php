<?php
$details = unserialize($pitch->specifics);
?>
<input type="hidden" id="logo_properties" data-props="<?php echo urlencode(json_encode($details["audience"])); ?>">

<div class="groupc">

    <?php if(!empty($details['site-units'])):?>
    <h2 class="blueheading">Необходимые единицы фирменного стиля</h2>
    <p class="regular"><?php echo nl2br($this->brief->insertHtmlLinkInText($details['site-units']))?></p>
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

    <?php if(!empty($details['qualities'])):?>
    <h2 class="blueheading">Какие 3 основных качества нужно донести до аудитории?</h2>
    <p class="regular"><?=$this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($details['qualities'])?></p>
    <?php endif;?>

    <?php if(!empty($details['site-inspiration'])):?>
    <h2 class="blueheading">Предпочтения</h2>
    <p class="regular"><?=$this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($details['site-inspiration'])?></p>
    <?php endif;?>

</div>