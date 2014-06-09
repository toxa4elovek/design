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
                    <p>Этот тест не претендует на замену госэкзаменов в художественных ВУЗах и не гарантирует диплом по специльности «Дизайнер». Нашей целью стал праздный интерес к уровню воды в реке под названием Графический дизайн. Мы создали 15 вопросов, в каждом из которых всего один верный ответ.</p>
                    <h2 class="largest-header-blog">Как прошли этот тест другие?</h1>
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
                        <?php if (true): ?>
                            <div style="">
                                <div style="float:left;height:20px;margin-right:15px;">
                                    <div class="fb-like" data-href="http://www.godesigner.ru/posts/view/<?=$post->id?>" data-send="false" data-layout="button_count" data-width="50" data-show-faces="false" data-font="arial"></div>
                                </div>
                                <div style="float:left;height:20px;width:95px;">
                                    <div id="vk_like"></div>
                                </div>
                                <div style="float:left;height:20px;width:90px;">
                                    <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.godesigner.ru/posts/view/<?=$post->id?>" data-text="<?=$post->title?>" data-lang="en" data-hashtags="Go_Deer">Tweet</a>
                                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                                </div>
                                <!--div style="float:left;height:20px;width:70px;">
                                    <div class="g-plusone" data-size="medium"></div>
                                </div>
                                <div style="float:left;height:20px;width:70px;">
                                    <a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fposts%2Fview%2F<?=$post->id?>&media=<?=$post->imageurl?>&description=<?=$post->title?>" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
                                </div>
                                <div style="float:left;height:20px;width:80px;">
                                    <a target="_blank" class="surfinbird__like_button" data-surf-config="{'layout': 'common', 'width': '120', 'height': '20'}" href="http://surfingbird.ru/share">Серф</a>
                                </div-->
                                <div style="clear:both;width:300px;height:1px;"></div>
                            </div>
                        <?php endif?>
                    </div>
                </section>
                <section class="nicht" style="display: none; position: relative;">
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
<?=$this->html->script(array('jquery.scrollto.min.js', 'questions/index'), array('inline' => false))?>
<?=$this->html->style(array('/questions'), array('inline' => false))?>
