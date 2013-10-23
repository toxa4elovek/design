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

    <p>
        <label>Есть ли у вас существующий сайт?</label>
        <input type="text" name="site-existing" placeholder="http://" value="<?=$specifics['site-existing']?>" class="specific-prop">
    </p>

    <p>
        <label>Какие сайты вам нравятся? Откуда дизайнерам черпать вдохновение?</label>
        <textarea placeholder="" rows="5" cols="30" name="site-inspiration" class="<?php if(empty($specifics['site-inspiration'])) echo 'placeholder'?> specific-prop"><?=$specifics['site-inspiration']?></textarea>
    </p>

    <p>
        <label>Какую CMS вы предпочитаете?</label>
    <div class="radiodiv">
        <input type="radio" name="cms" value="0" <?php if($specifics['cms'] == '0'): echo 'checked'; endif;?> class="specific-group"/><span class="radiospan">не уверен</span>
    </div>
    <div class="radiodiv">
        <input type="radio" name="cms" value="1" <?php if($specifics['cms'] == '1'): echo 'checked'; endif;?> class="specific-group"/><span class="radiospan">Wordpress</span>
    </div>
    <div class="radiodiv">
        <input type="radio" name="cms" value="2" <?php if($specifics['cms'] == '2'): echo 'checked'; endif;?> class="specific-group"/><span class="radiospan">Joomla</span>
    </div>
    <div class="radiodiv">
        <input type="radio" name="cms" value="3" <?php if($specifics['cms'] == '3'): echo 'checked'; endif;?> class="specific-group"/><span class="radiospan">DLE</span>
    </div>
    <div class="radiodiv">
        <input type="radio" name="cms" value="4" <?php if($specifics['cms'] == '4'): echo 'checked'; endif;?> class="specific-group"/><span class="radiospan">1С-Битрикс</span>
    </div>
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
        <input type="text" name="qualities" placeholder="Прагматичный, надежный, элегантный" value="<?=$specifics['qualities']?>" class="specific-prop">
    </p>

</div>