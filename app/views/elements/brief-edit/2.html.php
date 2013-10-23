<div class="groupc">

    <?php if($pitch->billed == 1):?>
        <?php if (($this->session->read('user.isAdmin') == 1) || \app\models\User::checkRole('admin')):?>
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