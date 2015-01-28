<div class="groupc">
<p>
    <label>Какие 3 основных качества нужно донести до аудитории?</label>
    <input type="text" name="qualities" value="<?=$specifics['qualities']?>"  placeholder="Надежный, технологичный, инновационный" class="specific-prop">
</p>
</div>

<div class="groupc">

    <p style="margin-top: 24px; margin-bottom: 38px;">
        <label><a href="#" class="expand_extra">+ Дополнительная информация</a></label>
    </p>

    <div class="extra_options" style="display: none">

        <div class="groupc">

    <?php if($pitch->billed == 1):?>
        <?php if ($this->user->isAdmin()):?>
            <p><label>Ведите количество макетов/страниц</label>
            <input type="text" class="specific-prop" value="<?=$specifics['site-sub']?>" name="site-sub" />
            </p>
        <?php else:?>
            <input type="hidden" class="specific-prop" value="<?=$specifics['site-sub']?>" name="site-sub" />
        <?php endif;?>
    <?php endif?>

    <?php if (!empty($specifics['site-image'])): ?>
    <p>
        <label>Какой образ о себе (продукте/услуге) вы бы хотели создать?</label>
        <textarea rows="5" cols="30" name="site-image" class="specific-prop"><?=$specifics['site-image']?></textarea>
    </p>
    <?php endif; ?>

    <?php if (!empty($specifics['site-inspiration'])): ?>
    <p>
        <label>Предпочтения</label>
        <textarea placeholder="Цвета, стилистические направления, ссылки на примеры" rows="5" cols="30" name="site-inspiration" class="specific-prop"><?=$specifics['site-inspiration']?></textarea>
    </p>
    <?php endif; ?>

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