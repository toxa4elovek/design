<div class="groupc">
    <p>
        <label class="required">Название проекта <?= $this->view()->render(array('element' => 'newbrief/required_star')) ?> <!--a href="#" class="second tooltip" title="Кратко напишите, что вам необходимо создать и для какого бренда. (прим.: обёртка для шоколада “Мишка на севере”) Подробнее о брифе в разделе “Помощь”.">(?)</a-->
            <a href="#" class="visibility-eye-tooltip tooltip" title="Название видно всем"><img src="/img/public-comment-eye.png" alt="Виден всем"></a>
        </label>
        <?php if ($category->id != 7): ?>
            <input id="pitch-title" type="text" name="title" placeholder="<?= $word1 ?> для Star Lift" data-placeholder="<?= $word1 ?> для Star Lift" value="<?php if($pitch): echo $pitch->title; endif;?>" required>
        <?php else: ?>
            <input id="pitch-title" type="text" name="title" placeholder="Название для строительной фирмы" data-placeholder="Название для строительной фирмы" value="<?php if($pitch): echo $pitch->title; endif;?>" required>
        <?php endif ?>
        <input type="hidden" name="category_id" value="<?=$category->id?>">
    </p>
</div><!-- .group -->