<div class="groupc">

    <p>
        <label>Какие три качества должно отражать название/слоган?<a href="#" class="visibility-eye-tooltip tooltip private" title="Эта информация будет доступна вам и участникам, которые подписали соглашение о неразглашении"><img src="/img/private-comment-eye.png" alt="Информация скрыта"></a></label>
        <input type="text" name="qualities" placeholder="Мужественность, уверенность в себе, традиционность" class="<?php if(empty($specifics['copy-difference'])) echo 'placeholder'?> specific-prop" value="<?=$specifics['qualities']?>">
    </p>

</div>

<div class="groupc">

    <p style="margin-top: 24px; margin-bottom: 38px;">
        <label><a href="#" class="expand_extra">+ Дополнительная информация</a><a href="#" class="visibility-eye-tooltip tooltip private" title="Эта информация будет доступна вам и участникам, которые подписали соглашение о неразглашении"><img src="/img/private-comment-eye.png" alt="Информация скрыта"></a></label>
    </p>

    <div class="extra_options" style="display: none">

        <div class="groupc">

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

    <?php if (!empty($specifics['copy-extra'])): ?>
    <p>
        <label>Есть что добавить?</label>
        <textarea placeholder="" rows="5" cols="30" name="copy-extra" class="specific-prop"><?=$specifics['copy-extra']?></textarea>
    </p>
    <?php endif; ?>
    <?php if (!empty($specifics['copy-ref'])): ?>
    <p>
        <label>Референсы</label>
        <textarea placeholder="" rows="5" cols="30" name="copy-ref" class="specific-prop"><?=$specifics['copy-ref']?></textarea>
    </p>
    <?php endif; ?>

    <p>
        <label style="font:14px/20px 'Arial',sans-serif;text-shadow:-1px 0 0 #FFFFFF;color:#666666;"><input type="checkbox" style="vertical-align: middle; margin-right: 5px;" name="copy-trademark" class="specific-prop" <?php if(isset($specifics['copy-trademark']) && $specifics['copy-trademark'] == 'on') echo "checked";?>> я буду регистрировать название как уникальную торговую марку</label>
    </p>

    <p>
        <label style="font:14px/20px 'Arial',sans-serif;text-shadow:-1px 0 0 #FFFFFF;color:#666666;"><input type="checkbox" style="vertical-align: middle; margin-right: 5px;" name="copy-website" class="specific-prop" <?php if(isset($specifics['copy-website']) && $specifics['copy-website'] == 'on') echo "checked";?>> я буду регистрировать сайт</label>
    </p>

</div>