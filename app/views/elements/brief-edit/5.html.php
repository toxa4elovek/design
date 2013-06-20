<div class="groupc">

    <p>
        <label>Перечислите все единицы фирменного стиля, которые вам необходимы</label>
        <textarea placeholder="Визитка
Бланк А4
Коверт А4
Папка А4
Шаблон для рекламы 6X3" rows="5" cols="30" name="site-units" class="<?php if(empty($specifics['site-units'])) echo 'placeholder'?> specific-prop"><?=$specifics['site-units']?></textarea>
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

    <p>
        <label>Какие 3 основных качества нужно донести до аудитории?</label>
        <input type="text" name="qualities" value="<?=$specifics['qualities']?>" placeholder="Вкусный, изысканный, современный" class="specific-prop">
    </p>

    <p>
        <label>Предпочтения</label>
        <textarea placeholder="Цвета, стилистические направления, ссылки на примеры" rows="5" cols="30" name="site-inspiration" class="<?php if(empty($specifics['site-inspiration'])) echo 'placeholder'?> specific-prop"><?=$specifics['site-inspiration']?></textarea>
    </p>

</div>