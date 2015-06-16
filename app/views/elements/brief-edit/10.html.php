<div class="groupc">

    <p style="margin-top: 24px; margin-bottom: 38px;">
        <label><a href="#" class="expand_extra">+ Дополнительная информация</a><a href="#" class="visibility-eye-tooltip tooltip private" title="Эта информация будет доступна вам и участникам, которые подписали соглашение о неразглашении"><img src="/img/private-comment-eye.png" alt="Информация скрыта"></a></label>
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