<div class="wrapper">


    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo'])?>
    <div class="middle">
        <div class="middle_inner">
            <div class="content group">

                <div id="right_sidebar_help">
                    <div id="r_h_v">

                        <h2>Возникли вопросы?</h2>
                        Если вы&nbsp;не&nbsp;можете найти ответ на&nbsp;свой вопрос&nbsp;&mdash; напишите нам. Мы&nbsp;постараемся ответить вам в&nbsp;течении 24&nbsp;часов по&nbsp;рабочим дням.
                        <?=$this->html->link('<img src="/img/otp_em.jpg">', 'Pages::contacts', ['escape' => false])?>
                    </div>

                </div><!-- /right_sidebar_help -->

                <div id="content_help">

                    <div class="answer regular">
                        <nav class="breadcrumbs">
                            <a href="/answers">Помощь</a> / <a href="/answers?category=<?= $answer->questioncategory_id?>"><?= $answer->category?></a> / <a href="/answers/view/<?= $answer->id ?>"><?= $answer->title?></a>
                            <div style="margin-top:20px; margin-bottom:0px; width: 611px; background: url(/img/sep.png) repeat-x scroll 0 0 transparent" class="separator"></div>
                        </nav>
                        <?php echo $answer->text;?>
                        <?=$this->html->link('все вопросы', 'Answers::index', ['class' => 'back', 'style' => 'margin-left:5px;margin-top:20px;display: block;'])?>

                    </div>

                </div><!-- /content_help -->


            </div><!-- /content -->

            <div class="margins-1" style="margin-top: 47px; margin-bottom: 33px;">
                <section class="howitworks">
                    <h1>faq</h1>
                </section>
            </div>

            <ul class="faq vp_one" style="margin-bottom: 40px;">
                <?php foreach ($similar as $answer): ?>
                    <li>
                        <p class="regular" style=""><a href="/answers/view/<?=$answer->id ?>" target="_blank"><?= $answer->title ?></a></p>
                        <div style="background:url(/img/sep.png) repeat-x;height:4px;"></div>
                    </li>
                <?php endforeach?>
            </ul>
        </div><!-- /middle_inner -->

        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->script(['help/view'], ['inline' => false])?>
<?=$this->html->style(['/help', '/howitworks', '/answer'], ['inline' => false])?>