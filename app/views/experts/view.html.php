<div class="wrapper" xmlns="http://www.w3.org/1999/html">

    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo'])?>

    <div class="middle">
        <div class="middle_inner">

            <div class="content group">

                <div class="margins-1 expert-personal">
                    <section class="howitworks">
                        <h1 class="h2link"><a href="/experts"/>наши эксперты</a></h1>
                    </section>
                    <div class="margins-2">
                        <?php
                        switch ($expert->id):
                            case 1: $image = '/img/experts/fedchenko.jpg'; echo '<img src="/img/experts/fedchenko.jpg" alt="Альберт Федченко">';break;
                            case 3: $image = '/img/experts/kojara.jpg'; echo '<img src="/img/experts/kojara.jpg" alt="Кожара Сергей">';break;
                            case 2: $image = '/img/experts/pavlov.jpg'; echo '<img src="/img/experts/pavlov.jpg" alt="Владимир Павлов">';break;
                            case 4: $image = '/img/experts/chern.jpg'; echo '<img src="/img/experts/chern.jpg" alt="Михаил Чернышев">';break;
                            case 5: $image = '/img/experts/nesterenko218.jpg'; echo '<img src="/img/experts/nesterenko218.jpg" alt="Максим Нестеренко">';break;
                            case 6: $image = '/img/experts/efremov218.jpg'; echo '<img src="/img/experts/efremov218.jpg" alt="Станислав Ефремов">';break;
                            case 7: $image = '/img/experts/percia_218.png'; echo '<img src="/img/experts/percia_218.png" alt="Валентин Перция">';break;
                            case 8: $image = '/img/experts/makarov_dmitry.png'; echo '<img src="/img/experts/makarov_dmitry.png" alt="Дмитрий Макаров">';break;
                        endswitch;
                        ?>

                        <div class="about regular">
                            <h2><?=$expert->name?></h2>
                            <?php if (!empty($expert->spec)):?>
                            <p><?=$expert->spec?></p>
                            <?php endif?>
                            <?php echo $this->brief->insertHtmlLinkInTextForSeo($expert->text)?>

                            <a style="
                            width: 85px;
                            height: 8px;
                            color: #6990a1;
                            font-family: Helvetica;
                            font-size: 11px;
                            font-weight: 400;
                            text-transform: uppercase;" href="/experts">все эксперты</a>
                        </div>
                    </div>
                    <div class="clear"></div>

                    <div class="margins-1" style="margin-top: 47px; margin-bottom: 33px;">
                        <?php if ($comments):?>
                        <section class="howitworks" style="background: url(/img/experts/line.png) no-repeat 50% 43%">
                            <h1 style="background: url(/img/experts/header-background.png) no-repeat 50%">ПОСЛЕДНИЕ ОТЗЫВЫ И МНЕНИЯ</h1>
                        </section>

                            <?php foreach ($comments as $comment):?>
                            <section class="" data-id="140601" data-type="designer">
                                <div class="message_info5" style="margin-left: -3px; margin-top: 50px;">
                                    <a href="/users/view/<?=$expert->user_id ?>" target="_blank">
                                        <img style="border: 0; position: static;" src="<?= $image?>" alt="Портрет пользователя" width="41" height="41">
                                    </a>
                                    <a href="/users/view/<?=$expert->user_id ?>">
                                        <span><?=$this->NameInflector->renderName($expert->name)?></span><br>
                                        <span style="font-weight: normal;"><?= date('d.m.Y H:m', strtotime($comment->created))?></span>
                                    </a>
                                    <div class="clr"></div>
                                </div>
                                <div data-id="140601" class="message_text" style="float: right;width: 485px; display:block;  margin-top: 47px; ">
                                    <span class="regular comment-container"><?php echo $comment->text?></span>
                                    <a href="/pitches/view/<?= $comment->pitch->id ?>" style="
                                    margin-left: 2px;
                                    display: block;
                                    margin-top: 10px;
                                    width: 344px;
                                    height: 12px;
                                    color: #648fa4;
                                    font-family: Arial;
                                    font-size: 12px;
                                    font-weight: 700;
                                    line-height: 20px;"><?= $comment->pitch->title ?></a>
                                </div>
                                <div class="clr"></div>
                            </section>
                            <?php endforeach?>
                        <?php endif?>
                    </div>
                    <div class="margins-1" style="margin-top: 47px; margin-bottom: 33px;">
                        <section class="howitworks">
                            <h1>faq</h1>
                        </section>
                    </div>

                    <ul class="faq vp_one" style="margin-left: 0; margin-bottom: 20px;">
                        <?php foreach ($questions as $answer): ?>
                            <li>
                                <p class="regular" style="padding: 0;"><a href="/answers/view/<?=$answer->id ?>" target="_blank"><?= $answer->title ?></a></p>
                                <div style="background:url(/img/sep.png) repeat-x;height:4px;"></div>
                            </li>
                        <?php endforeach?>
                    </ul>

                </div>
            </div><!-- /content -->

        </div><!-- /middle_inner -->

        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->script(['jquery.hover.js', 'experts/view'], ['inline' => false])?>
<?=$this->html->style(['/text', '/howitworks', '/expert-personal', '/messages12.css'], ['inline' => false])?>