<div class="groupc">

    <p>
        <label>Какие 3 основных качества нужно донести до аудитории?</label>
        <input type="text" name="qualities" value="<?=$specifics['qualities']?>"  placeholder="Надежный, технологичный, инновационный" class="specific-prop">
    </p>

    <p>
        <label>Какой образ о себе (продукте/услуге) вы бы хотели создать?</label>
        <textarea placeholder="Нужно, чтобы потенциальные потребители поверили, что напиток Coca-Cola - для молодых, энергичных, веселых ребят, что он дарит бодрость и настроение, что без него не обходится ни одна вечеринка..." rows="5" cols="30" name="site-image" class="<?php if(empty($specifics['site-image'])) echo 'placeholder'?> specific-prop"><?=$specifics['site-image']?></textarea>
    </p>

    <p>
        <label>Предпочтения</label>
        <textarea placeholder="Цвета, стилистические направления, ссылки на примеры" rows="5" cols="30" name="site-inspiration" class="<?php if(empty($specifics['site-inspiration'])) echo 'placeholder'?>s specific-prop"><?=$specifics['site-inspiration']?></textarea>
    </p>

    <p><label>Опишите целевую аудиторию</label></p>

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

</div>