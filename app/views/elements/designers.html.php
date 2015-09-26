<input type="hidden" class="designers-count" value="<?=$designersCount?>">
<?php foreach ($designers as $designer):?>
    <li class="designer_row">
        <div class="message_info1">
            <a href="/users/view/<?=$designer->user->id;?>">
                <img src="<?=$this->avatar->show($designer->user->data(), false, true);?>" alt="Портрет пользователя" width="41" height="41">
            </a>
            <a href="/users/view/<?=$designer->user->id;?>">
                <span class="designer_name"><?=$this->user->getFormattedName($designer->user->first_name, $designer->user->last_name);?></span><br />
                <span class="designer_plate"><?=count($designer->solutions);?> <?=$this->numInflector->formatString(count($designer->solutions), array('string' => array('first' => 'решение', 'second' => 'решения', 'third' => 'решений')))?></span>
            </a>
            <div class="clr"></div>
        </div>

        <?php
            $solutions = $designer->solutions;
        ?>
        <div class="designer_wrapper">
            <ul class="list_portfolio designers_tab">
                <?=$this->view()->render(array('element' => 'gallery'), compact('solutions', 'pitch', 'sort', 'canViewPrivate', 'fromDesignersTab', 'winnersUserIds'))?>
            </ul>
            <div class="scroll_left" style="display: none;"><i></i></div>
            <div class="scroll_right" style="display: none;"><i></i></div>
        </div>
    </li>
<?php endforeach;?>
