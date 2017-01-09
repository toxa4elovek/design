<div class="wrapper">

    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo'])?>

    <?php

    function highlight($text, $search)
    {
        if ($search != '') {
            $words = array_unique(explode(' ', $search));
            $splitted = explode(' ', $text);
            foreach ($splitted as &$textword) {
                $found = false;
                foreach ($words as $word) {
                    if ((!$found) & (preg_match('@(.?' . $word . '.?)@ui', $textword, $matches))) {
                        //var_dump($word);
                        //var_dump($textword);
                        $textword = preg_replace('@('.$matches[0].')@ui', '<span style="color: #ff585d;text-underline: none;">$1</span>', $textword);
                        $found = true;
                    }
                }
            }
            $text = implode(' ', $splitted);
        }
        return $text;
    }
    ?>

    <div class="middle">
        <div class="middle_inner">
            <div class="content group">
                <div id="content_help">
                    <section class="howitworks">
                        <h1>Помощь</h1>
                    </section>
                    <div id="content_help_seach" style="background: none repeat scroll 0 0 #F3F3F3;box-shadow: 3px 3px #D2D2D2;margin:20px 0;padding:20px 30px;">
                        <form action="/answers" method="get">
                            <input type="text" name="search" value="<?=$originalSearch?>" class="text">
                            <input type="submit" class="button second" style="margin-left: 30px" value="Поиск">
                        </form>
                    </div>

                    <div id="content_help_cont">

                        <div class="content_help_line"></div>
                        <div id="ajaxzone">
                            <?php if (count($answers) == 0):?>
                                <p class="regular">По вашему запросу ничего не найдено.</p>

                            <?php else: ?>
                            <div class="vp_one">
                                <table>
                                    <tr>
                                        <td></td>
                                        <td><h2>Результаты поиска</h2>
                                            <div class="answer-expand">
                                                <?php
                                                foreach ($answers as $answer):?>
                                                    <div style="background:url(/img/sep.png) repeat-x;height:4px;"></div>
                                                    <p class="regular" style="height: 26px; padding-top:6px;width:530px;"><?=$this->html->link(highlight($answer['title'], $search), ['Answers::view', 'id' => $answer['id']], ['escape' => false]);?></p>
                                                <?php endforeach;?>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <?php endif?>

                        </div>
                    </div>
                </div>
                <div id="right_sidebar_help">
                    <div id="r_h_v" class="regular">
                        <h2 class="greyboldheader">Возникли вопросы?</h2>
                        Если вы не можете найти ответ на свой <span style="white-space: nowrap;">вопрос - напишите</span> нам. Мы постараемся ответить вам в течении 24 часов по рабочим дням.
                        <?=$this->html->link('<img src="/img/otp_em.jpg" alt="">', 'Pages::contacts', ['escape' => false])?>
                    </div>
                </div>
            </div><!-- /content -->
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->script(['help/index'], ['inline' => false])?>
<?=$this->html->style(['/help'], ['inline' => false])?>