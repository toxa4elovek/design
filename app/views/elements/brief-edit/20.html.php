<?php
if (!isset($specifics['logoType'])) {
    $specifics['logoType'] = [];
}
?>
<div class="groupc">
    <p>
        <label><span class="subscriber-qualities-title">Какие 3 качества нужно донести<?php if (!$pitch->isSubscriberProjectForCopyrighting()):?> через дизайн? <?php else: ?>?<?php endif; ?></span> <a href="#" class="second tooltip" title="Прилагательные, ассоциации, которые должны возникнуть у потребителя."></a><a href="#" class="visibility-eye-tooltip tooltip private" title="Эта информация будет доступна вам и участникам, которые подписали соглашение о неразглашении"><img src="/img/private-comment-eye.png" alt="Информация скрыта"></a></label>
        <input type="text" name="qualities" value="<?=$specifics['qualities']?>" placeholder="Прагматичный, надежный, элегантный" class="specific-prop">
    </p>
</div><!-- .group -->

<div class="groupc">
    <p style="margin-top: 24px; margin-bottom: 38px;">
        <label><a href="#" class="expand_extra">+ Дополнительная информация</a><a href="#" class="visibility-eye-tooltip tooltip private" title="Эта информация будет доступна вам и участникам, которые подписали соглашение о неразглашении"><img src="/img/private-comment-eye.png" alt="Информация скрыта"></a></label>
    </p>

    <div class="extra_options" style="display: none">

        <div class="groupc">
            <p><label class="subscriber-audience-title">Какими свойствами должен обладать <?php if (!$pitch->isSubscriberProjectForCopyrighting()):?> ваш дизайн?<?php else:?> копирайтинг?<?php endif; ?></label></p>

            <ul class="logo-properties sliderul" data-name="logo-properties">
                <li>
                    <span class="label-a">Женственный</span>
                    <span class="slider-wrapper"><span class="slider"></span></span>
                    <span class="label-b">Мужественный</span>
                </li>
                <li>
                    <span class="label-a">Молодой</span>
                    <span class="slider-wrapper"><span class="slider"></span></span>
                    <span class="label-b">Зрелый</span>
                </li>
                <li>
                    <span class="label-a">Роскошный</span>
                    <span class="slider-wrapper"><span class="slider"></span></span>
                    <span class="label-b">Экономичный</span>
                </li>
                <li>
                    <span class="label-a">Современный</span>
                    <span class="slider-wrapper"><span class="slider"></span></span>
                    <span class="label-b">Консервативный</span>
                </li>
                <li>
                    <span class="label-a">Игривый</span>
                    <span class="slider-wrapper"><span class="slider"></span></span>
                    <span class="label-b">Серьезный</span>
                </li>
                <li>
                    <span class="label-a">Простой</span>
                    <span class="slider-wrapper"><span class="slider"></span></span>
                    <span class="label-b">Комплексный</span>
                </li>
                <li>
                    <span class="label-a">Утонченный</span>
                    <span class="slider-wrapper"><span class="slider"></span></span>
                    <span class="label-b">Заметный</span>
                </li>
            </ul><!-- .logo-properties -->


        </div><!-- .group -->