<div class="groupc" style="background: none;position:relative;">
    <p style="">
        <label>Описание бизнеса и что нужно получить на выходе <a href="#" class="second tooltip" title="<?php echo strip_tags($word2)   ?>">(?)</a><a href="#" class="visibility-eye-tooltip tooltip private" title="Эта информация будет доступна вам и участникам, которые подписали соглашение о неразглашении"><img src="/img/private-comment-eye.png" alt="Информация скрыта"></a></label>
        <textarea <?php if ((!$pitch) ||(($pitch) && (!$this->brief->isUsingPlainText($pitch)))) {
    echo 'class="enable-editor"';
}?> id="full-description" name="description" cols="40" rows="10" data-placeholder="<?= $word2 ?>" data-low="70" data-normal="340" data-high="680" ><?php if ($pitch):?><?=$pitch->description?><?php endif?></textarea>
        <div class="tooltip_brief_description" id="tooltip-brief-description">
            <div class="tooltip_brief_text">
            Опция «<a href="/answers/view/68" target="_blank">Заполнить бриф</a>» за 2750 р. сэкономит вам время и правильно поставит задачу перед дизайнерами (копирайтерами). Позвольте нашим специалистам вам помочь. <a href="/answers/view/68" target="_blank">Подробнее</a>
            </div>
        </div>
    </p>

    <div id="indicator-desc" class="indicator low tooltip" title="Шкала показывает, насколько подробно вы описали то, зачем пришли. Каждое ваше слово поможет дизайнеру.">
        <div class="bar">
            <div class="line"></div>
            <div class="shadow-b"></div>
        </div><!-- .bar -->
        <ul>
            <li>недостаточно подробно…</li>
            <li>вполне понятно</li>
            <li>самое то!</li>
        </ul>
    </div><!-- .indicator -->
</div><!-- .group -->
