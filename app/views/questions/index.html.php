<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner">
            <div class="content group">
                <section class="howitworks quiz">
                    <h1><?php echo 'Пройти тест на профпригодность'; ?></h1>
                    <form id="quiz_form" action="/questions/validate" method="post">
                    <ol>
                    <?php foreach ($questions as $question):?>
                        <li>
                            <h2><?=$question['title'];?></h2>
                            <?php foreach ($question['variants'] as $variant): ?>
                                <label>
                                    <input type="radio" name="questions[<?=$question['id']?>]" value="<?=$variant['id']?>" class="radio-input">
                                    <?=$variant['text']?>
                                </label>
                            <?php endforeach; ?>
                        </li>
                    <?php endforeach?>
                    </ol>
                    <input type="submit" class="button quiz_submit" value="Отправить">
                    </form>
                </section>
            </div><!-- /content -->
            <div class="onTopMiddle">&nbsp;</div>
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<div class="onTop">&nbsp;</div>
<?=$this->html->script(array('questions/index'), array('inline' => false))?>
<?=$this->html->style(array('/questions'), array('inline' => false))?>
