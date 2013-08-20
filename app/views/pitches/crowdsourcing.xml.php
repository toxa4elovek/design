<projects type="array">
    <?php foreach ($pitches as $pitch):?>
    <project>
        <platform>GoDesigner</platform>
        <name><?= $pitch->title?></name>
        <photo><?php if((count($pitch->solution) > 0) && ($pitch->category_id != 7) && ($pitch->private == 0)):?>
        <?php $solution = $pitch->solution;
            if(isset($solution->images['solution_solutionView'])):
                echo 'http://www.godesigner.ru' . $this->solution->renderImageUrl($solution->images['solution_solutionView']);
            else:

                echo 'http://www.godesigner.ru' . $this->solution->renderImageUrl($solution->images, 0);
            endif;
        else: ?>http://www.godesigner.ru/img/fb_icon.jpg<?php endif?></photo>
        <city></city>
        <user-name><?= $pitch->user->first_name . ' ' . $pitch->user->last_name ?></user-name>
        <short-description><?php if($pitch->private == 0):?><?php echo htmlspecialchars(nl2br($pitch->description)) ?><?php else:?>Это закрытый питч и вам нужно подписать соглашение о неразглашении!<?php endif?></short-description>
        <prize type="integer"><?= (int) $pitch->price ?></prize>
        <start-date><?=gmdate('Y-m-d H:i:s', strtotime($pitch->started))?> UTC</start-date>
        <start_unix_time type="integer"><?= strtotime($pitch->started) ?></start_unix_time>
        <finish-date><?=gmdate('Y-m-d H:i:s', strtotime($pitch->finishDate))?> UTC</finish-date>
        <finish-unix-time type="integer"><?= strtotime($pitch->finishDate) ?></finish-unix-time>
        <remains-seconds type="integer"><?= strtotime($pitch->finishDate) - time() ?></remains-seconds>
        <category nil="true"/>
        <parent-category>Дизайн</parent-category>
        <url>
            http://www.godesigner.ru/pitches/details/<?= $pitch->id ?>
        </url>
    </project>
    <?php endforeach?>
</projects>