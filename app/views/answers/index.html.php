<div class="wrapper">

    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo'])?>

    <?php
    $first = 0;
    $second = 0;
    $third = 0;
    $fourth = 0;
    $fifth = 0;
    foreach ($answers as $fanswer):
        switch ($fanswer['questioncategory_id']):
            case 1: $first++;break;
            case 2: $second++;break;
            case 3: $third++;break;
            case 4: $fourth++;break;
            case 5: $fifth++;break;
        endswitch;
    endforeach;

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
                            <?php if (($first == 0) && ($second == 0) && ($third == 0) && ($fourth == 0) && ($fifth == 0)):?>
                            <p class="regular">По вашему запросу ничего не найдено.</p>
                            <?php endif?>
                            <?php if ($first > 0):?>
                            <div class="vp_one" data-count="<?=$first?>">
                                <table>
                                    <tr>
                                        <td><img src="/img/cont_help_data_1.gif" alt=""></td>
                                        <td><h2>Общие вопросы</h2>
                                            <div class="answer-expand" style="overflow:hidden;height:<?php if ($first < 4): echo($first) * 36; else: if ($category == null): echo '108'; else: echo 'auto'; endif; endif;?>px;">
                                            <?php
                                            $count = 0;
                                            foreach ($answers as $answer):
                                            if ($answer['questioncategory_id'] != 1) {
                                                continue;
                                            }
                                                $count++;
                                            ?>
                                            <div style="background:url(/img/sep.png) repeat-x;height:4px;"></div>
                                            <p class="regular" style="height: 26px; padding-top:6px;width:530px;"><?=$this->html->link(highlight($answer['title'], $search), ['Answers::view', 'id' => $answer['id']], ['escape' => false]);?></p>
                                            <?php endforeach;?>
                                            </div>
                                            <?php if (($count > 3) && ((!isset($category)) || ($category == null))):?>
                                            <p style="margin-top:8px;"><a href="#" class="av answer-expand-link">Все вопросы</a></p>
                                            <?php endif?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="content_help_line"></div>
                            <?php endif?>
                            <?php if ($second > 0):?>
                            <div class="vp_one" data-count="<?=$second?>">
                                <table>
                                    <tr>
                                        <td><img src="/img/cont_help_data_2.png" style="margin-left: 14px;" alt=""></td>
                                        <td><h2>Помощь заказчикам</h2>
                                            <div class="answer-expand" style="overflow:hidden;height:<?php if ($second < 4): echo($second) * 36; else: if ((!isset($category)) || ($category == null)): echo '108'; else: echo 'auto'; endif; endif;?>px;">
                                            <?php
                                            $count = 0;
                                            foreach ($answers as $answer):
                                                if ($answer['questioncategory_id'] != 2) {
                                                    continue;
                                                }
                                                $count++;
                                                ?>
                                                <div style="background:url(img/sep.png) repeat-x;height:4px;"></div>
                                                <p class="regular" style="height: 26px; padding-top:6px;width:530px;"><?=$this->html->link(highlight($answer['title'], $search), ['Answers::view', 'id' => $answer['id']], ['escape' => false]);?></p>
                                            <?php endforeach;?>
                                            </div>
                                            <?php if (($count > 3) && ((!isset($category)) || ($category == null))):?>
                                                <p style="margin-top:8px;"><a href="#" class="av answer-expand-link">Все вопросы</a></p>
                                            <?php endif?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="content_help_line"></div>
                            <?php endif?>
                            <?php if ($third > 0):?>
                            <div class="vp_one" data-count="<?=$third?>">
                                <table>
                                    <tr>
                                        <td><img src="/img/designers.png" style="" alt=""></td>
                                        <td><h2>Помощь дизайнерам</h2>
                                            <div class="answer-expand" style="overflow:hidden;height:<?php if ($third < 4): echo($third) * 36; else: if ((!isset($category)) || ($category == null)): echo '108'; else: echo 'auto'; endif; endif;?>px;">
                                            <?php
                                            $count = 0;
                                            foreach ($answers as $answer):
                                                if ($answer['questioncategory_id'] != 3) {
                                                    continue;
                                                }
                                                $count++;
                                                ?>
                                                <div style="background:url(img/sep.png) repeat-x;height:4px;"></div>
                                                <p class="regular" style="height: 26px; padding-top:6px;width:530px;"><?=$this->html->link(highlight($answer['title'], $search), ['Answers::view', 'id' => $answer['id']], ['escape' => false]);?></p>
                                            <?php endforeach;?>
                                            </div>
                                            <?php if (($count > 3) && ((!isset($category)) || ($category == null))):?>
                                                <p style="margin-top:8px;"><a href="#" class="av answer-expand-link">Все вопросы</a></p>
                                            <?php endif?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="content_help_line"></div>
                            <?php endif?>
                            <?php if ($fourth > 0):?>
                            <div class="vp_one" data-count="<?=$fourth?>">
                                <table>
                                    <tr>
                                        <td><img src="/img/cont_help_data_4.gif" alt=""></td>
                                        <td><h2>Оплата и денежные вопросы</h2>
                                            <div class="answer-expand" style="overflow:hidden;height:<?php if ($fourth < 4): echo($fourth) * 36; else: if ($category == null): echo '108'; else: echo 'auto'; endif; endif;?>px;">
                                                <?php
                                                $count = 0;
                                                foreach ($answers as $answer):
                                                if ($answer['questioncategory_id'] != 4) {
                                                    continue;
                                                }
                                                    $count++;
                                                    ?>
                                                <div style="background:url(img/sep.png) repeat-x;height:4px;"></div>
                                                <p class="regular" style="height: 26px; padding-top:6px;width:530px;"><?=$this->html->link(highlight($answer['title'], $search), ['Answers::view', 'id' => $answer['id']], ['escape' => false]);?></p>
                                                <?php endforeach;?>
                                            </div>
                                            <?php if (($count > 3) && ((!isset($category)) || ($category == null))):?>
                                                <p style="margin-top:8px;"><a href="#" class="av answer-expand-link">Все вопросы</a></p>
                                            <?php endif?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="content_help_line"></div>
                            <?php endif?>
                            <?php if ($fifth > 0):?>
                                <div class="vp_one" data-count="<?=$fifth?>">
                                    <table>
                                        <tr>
                                            <td><img src="/img/jur.png" alt=""></td>
                                            <td><h2>Для юридических лиц</h2>
                                                <div class="answer-expand" style="overflow:hidden;height:<?php if ($fifth < 4): echo($fifth) * 36; else: if ($category == null): echo '108'; else: echo 'auto'; endif; endif;?>px;">
                                                    <?php
                                                    $count = 0;
                                                    foreach ($answers as $answer):
                                                        if ($answer['questioncategory_id'] != 5) {
                                                            continue;
                                                        }
                                                        $count++;
                                                        ?>
                                                        <div style="background:url(img/sep.png) repeat-x;height:4px;"></div>
                                                        <p class="regular" style="height: 26px; padding-top:6px;width:530px;"><?=$this->html->link(highlight($answer['title'], $search), ['Answers::view', 'id' => $answer['id']], ['escape' => false]);?></p>
                                                    <?php endforeach;?>
                                                </div>
                                                <?php if (($count > 3) && ((!isset($category)) || ($category == null))):?>
                                                    <p style="margin-top:8px;"><a href="#" class="av answer-expand-link">Все вопросы</a></p>
                                                <?php endif?>
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