<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner">
            <div class="right-sidebar">
                <?php echo $this->stream->renderStream(5, false);?>
            </div>
            <div class="content group narrow">
                <section class="howitworks quiz">
                    <h1><?php echo 'Пройти тест на профпригодность'; ?></h1>
                    <p>Если вы пройдете тест на отлично с <span style="font-style: italic;">первого раза</span>, вы сможете принимать участие в проектах уже через 5 дней (вместо 10 дней). Этот тест не претендует на замену госэкзаменов в художественных ВУЗах и не гарантирует диплом по специльности «Дизайнер». Нашей целью стал праздный интерес к уровню воды в реке под названием Графический дизайн. Мы создали 15 вопросов, в каждом из которых всего один верный ответ. На прохождение теста даётся 3 минуты!</p>
                    <h2 class="largest-header-blog">Как прошли этот тест другие?</h2>
                    <ul class="quiz-bars">
                        <?php foreach ($stats as $data): ?>
                        <li>
                            <h3><?=$data['text']?></h3>
                            <div class="bar">
                                <div class="line<?php echo ($data['percent'] > 98) ? ' all-round' : '';?><?php echo ($data['percent'] < 2) ? ' zero-people' : '' ;?>" style="width: <?=$data['percent']?>%"></div>
                                <div class="shadow-b"></div>
                            </div>
                            <h6><?=$data['value']?> чел.</h6>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="/questions" class="button js-start-test" style="margin-right: 15px;">Начать тест</a>
                    <a href="#" class="button" onclick="window.history.back();return false;">Отказаться от теста</a>

                    <div class="share-this" style="margin-top: -20px;">
                        <?php $shareImage = 'http://www.godesigner.ru/img/icon_512.png'; ?>
                        <div style="margin-top: -5px; margin-bottom: 20px;">
                            <div class="social-likes" data-counters="no" data-title="Узнай, какой ты дизайнер на самом деле" data-url="http://www.godesigner.ru/questions/index" style="padding-left: 110px;">
                                <div style="margin: 7px 0 0 9px;" class="facebook" data-image="<?=$shareImage ?>" title="Поделиться ссылкой на Фейсбуке">SHARE</div>
                                <div style="margin: 7px 0 0 7px;" class="twitter" data-via="Go_Deer">TWITT</div>
                                <div style="margin: 7px 0 0 7px;" class="vkontakte" data-image="<?=$shareImage ?>" title="Поделиться ссылкой во Вконтакте">SHARE</div>
                                <div style="margin: 7px 0 0 7px;" class="pinterest" data-media="<?=$shareImage ?>" title="Поделиться картинкой на Пинтересте">PIN</div>
                            </div>
                            <div style="clear:both;width:300px;height:1px;"></div>
                        </div>

                    </div>
                </section>
                <section class="nicht" style="display: none; position: relative;">
                    <h1><?php echo 'Пройти тест на профпригодность'; ?></h1>
                    <form id="quiz_form" action="/questions/validate" method="post">
                    <ol>
                    <?php
                    $i = 1;
                    foreach ($questions as $question):?>
                        <li>
                            <h2><?=$question['title'];?></h2>
                            <?php foreach ($question['variants'] as $variant): ?>
                                <label>
                                    <input type="radio" name="questions[<?=$question['id']?>]" value="<?=$variant['id']?>" class="radio-input">
                                    <?=$variant['text']?>
                                </label>
                            <?php endforeach; ?>
                        </li>
                    <?php
                        if($i >= $limit):
                            break;
                        endif;
                        $i++;
                    endforeach?>
                    </ol>
                    <input type="submit" class="button quiz_submit" value="Отправить">
                    </form>
                    <div class="test-timer" id="test-countdown">
                        <span class="minutes">03</span><span class="timeRefMinutes">:</span><span class="seconds">00</span><span class="timeRefSeconds"></span>
                    </div>
                </section>
            </div><!-- /content -->
            <div class="onTopMiddle clr">&nbsp;</div>
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<div class="onTop">&nbsp;</div>
<?=$this->html->script(array('jquery.scrollto.min.js', 'questions/index', 'social-likes.min.js'), array('inline' => false))?>
<?=$this->html->style(array('/questions', '/css/social-likes_flat'), array('inline' => false))?>
