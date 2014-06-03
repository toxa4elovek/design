<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner">
            <div class="content group">
                <section class="howitworks quiz">
                    <h1><?php echo 'Пройти тест на профпригодность'; ?></h1>
                    <p>Этот тест не претендует на замену госэкзаменов в художественных ВУЗах и не гарантирует диплом по специльности «Дизайнер». Нашей целью стал праздный интерес к уровню воды в реке под названием Графический дизайн. Мы создали 15 вопросов, в каждом из которых всего один верный ответ.</p>
                    <h2 class="largest-header-blog">Как прошли этот тест другие?</h1>
                    <ul class="quiz-bars">
                        <?php foreach ($params as $data): ?>
                        <li>
                            <h3><?=$data['text']?></h3>
                            <div class="bar">
                                <div class="line<?php echo ($data['percent'] > 98) ? ' all-round' : ''; ?>" style="width: <?=$data['percent']?>%"></div>
                                <div class="shadow-b"></div>
                            </div>
                            <h6><?=$data['value']?> чел.</h6>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="/questions" class="button" style="margin-right: 15px;">Начать тест</a>
                    <a href="/questions" class="button">Отказаться от теста</a>
                </section>
            </div><!-- /content -->
            <div class="onTopMiddle">&nbsp;</div>
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<div class="onTop">&nbsp;</div>
<?=$this->html->style(array('/questions'), array('inline' => false))?>
