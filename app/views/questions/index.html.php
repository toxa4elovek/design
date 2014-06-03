<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner">
            <div class="content group">
                <section class="howitworks quiz">
                    <h1><?php echo 'Пройти тест на профпригодность'; ?></h1>
                    <ol>
                    <?php foreach ($questions as $question):?>
                        <li>
                            <h2><?=$question['title'];?></h2>
                            <?php foreach ($question['variants'] as $variant): ?>
                                <label>
                                    <input type="radio" name="question_<?=$question['id']?>" class="radio-input">
                                    <?=$variant['text']?>
                                </label>
                            <?php endforeach; ?>
                        </li>
                    <?php endforeach?>
                    </ol>
                    <a href="#" class="button">Отправить</a>
                </section>
            </div><!-- /content -->
            <div class="onTopMiddle">&nbsp;</div>
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<div class="onTop">&nbsp;</div>
<?=$this->html->style(array('/questions'), array('inline' => false))?>
