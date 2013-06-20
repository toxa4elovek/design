<div class="groupc">

    <p>
        <label>Какие три качества должно отражать название/слоган?</label>
        <input type="text" name="qualities" placeholder="Мужественность, уверенность в себе, традиционность" class="<?php if(empty($specifics['copy-difference'])) echo 'placeholder'?> specific-prop" value="<?=$specifics['qualities']?>">
    </p>


    <p><label>Критерии</label></p>

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

    <p>
        <label>Есть что добавить?</label>
        <textarea placeholder="" rows="5" cols="30" name="copy-extra" class="specific-prop"><?=$specifics['copy-extra']?></textarea>
    </p>

    <p>
        <label>Референсы</label>
        <textarea placeholder="" rows="5" cols="30" name="copy-ref" class="specific-prop"><?=$specifics['copy-ref']?></textarea>
    </p>

    <p>
        <label style="font:14px/20px 'Arial',sans-serif;text-shadow:-1px 0 0 #FFFFFF;color:#666666;"><input type="checkbox" style="vertical-align: middle; margin-right: 5px;" name="copy-trademark" class="specific-prop" <?php if(isset($specifics['copy-trademark']) && $specifics['copy-trademark'] == 'on') echo "checked";?>> я буду регистрировать название как уникальную торговую марку</label>
    </p>

    <p>
        <label style="font:14px/20px 'Arial',sans-serif;text-shadow:-1px 0 0 #FFFFFF;color:#666666;"><input type="checkbox" style="vertical-align: middle; margin-right: 5px;" name="copy-website" class="specific-prop" <?php if(isset($specifics['copy-website']) && $specifics['copy-website'] == 'on') echo "checked";?>> я буду регистрировать сайт</label>
    </p>

</div>