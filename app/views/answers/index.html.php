<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <?php
    $first = 0;
    $second = 0;
    $third = 0;
    $fourth = 0;
    $fifth = 0;
    foreach($answers as $fanswer):
        switch($fanswer['questioncategory_id']):
            case 1: $first++;break;
            case 2: $second++;break;
            case 3: $third++;break;
            case 4: $fourth++;break;
            case 5: $fifth++;break;
        endswitch;
    endforeach;

    function highlight($text, $search) {

        if($search != '') {
            $words = explode(' ', $search);
            $splitted = explode(' ', $text);
            foreach($splitted as &$textword) {
                foreach($words as $word) {
                    $textword = preg_replace('@('.$word.')@', '<span style="color: #ff585d;text-underline: none;">$1</span>', $textword);
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
                        <form action="" method="get">
                            <input type="text" name="search" value="<?=$search?>" class="text">
                            <input type="submit" class="button second" style="margin-left: 30px" value="Поиск">
                        </form>
                    </div>

                    <div id="content_help_cont">

                        <div class="content_help_line"></div>
                        <div id="ajaxzone">
                            <?php if(($first == 0) && ($second == 0) && ($third == 0) && ($fourth == 0) && ($fifth == 0)):?>
                            <p class="regular">По вашему запросу ничего не найдено.</p>
                            <?php endif?>
                            <?php if($first > 0):?>
                            <div class="vp_one">
                                <table>
                                    <tr>
                                        <td><img src="/img/cont_help_data_1.gif"></td>
                                        <td><h2>Общие вопросы</h2>
                                            <div class="answer-expand" style="overflow:hidden;height:107px;">
                                            <?php foreach($answers as $answer):
                                            if($answer['questioncategory_id'] != 1) continue;
                                            ?>
                                            <div style="background:url(img/sep.png) repeat-x;height:4px;"></div>
                                            <p class="regular" style="height: 26px; padding-top:6px;width:530px;"><?=$this->html->link(highlight($answer['title'], $search), array('Answers::view', 'id' => $answer['id']), array('escape' => false));?></p>
                                            <?php endforeach;?>
                                            </div>
                                            <p style="margin-top:8px;"><a href="#" class="av answer-expand-link">Все вопросы</a></p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="content_help_line"></div>
                            <?php endif?>
                            <?php if($second > 0):?>
                            <div class="vp_one">
                                <table>
                                    <tr>
                                        <td><img src="/img/cont_help_data_2.png" style="margin-left: 14px;"></td>
                                        <td><h2>Помощь заказчикам</h2>
                                            <div class="answer-expand" style="overflow:hidden;height:107px;">
                                            <?php foreach($answers as $answer):
                                                if($answer['questioncategory_id'] != 2) continue;
                                                ?>
                                                <div style="background:url(img/sep.png) repeat-x;height:4px;"></div>
                                                <p class="regular" style="height: 26px; padding-top:6px;width:530px;"><?=$this->html->link(highlight($answer['title'], $search), array('Answers::view', 'id' => $answer['id']), array('escape' => false));?></p>
                                            <?php endforeach;?>
                                            </div>
                                            <p style="margin-top:8px;"><a href="#" class="av answer-expand-link">Все вопросы</a></p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="content_help_line"></div>
                            <?php endif?>
                            <?php if($third > 0):?>
                            <div class="vp_one">
                                <table>
                                    <tr>
                                        <td><img src="/img/designers.png" style=""></td>
                                        <td><h2>Помощь дизайнерам</h2>
                                            <div class="answer-expand" style="overflow:hidden;height:107px;">
                                            <?php foreach($answers as $answer):
                                                if($answer['questioncategory_id'] != 3) continue;
                                                ?>
                                                <div style="background:url(img/sep.png) repeat-x;height:4px;"></div>
                                                <p class="regular" style="height: 26px; padding-top:6px;width:530px;"><?=$this->html->link(highlight($answer['title'], $search), array('Answers::view', 'id' => $answer['id']), array('escape' => false));?></p>
                                            <?php endforeach;?>
                                            </div>
                                            <p style="margin-top:8px;"><a href="#" class="av answer-expand-link">Все вопросы</a></p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="content_help_line"></div>
                            <?php endif?>
                            <?php if($fourth > 0):?>
                            <div class="vp_one">
                                <table>
                                    <tr>
                                        <td><img src="/img/cont_help_data_4.gif"></td>
                                        <td><h2>Оплата и денежные вопросы</h2>
                                            <div class="answer-expand" style="overflow:hidden;height:107px;">
                                                <?php foreach($answers as $answer):
                                                if($answer['questioncategory_id'] != 4) continue;
                                                ?>
                                                <div style="background:url(img/sep.png) repeat-x;height:4px;"></div>
                                                <p class="regular" style="height: 26px; padding-top:6px;width:530px;"><?=$this->html->link(highlight($answer['title'], $search), array('Answers::view', 'id' => $answer['id']), array('escape' => false));?></p>
                                                <?php endforeach;?>
                                            </div>
                                            <p style="margin-top:8px;"><a href="#" class="av answer-expand-link">Все вопросы</a></p>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="content_help_line"></div>
                            <?php endif?>
                            <?php if($fifth > 0):?>
                                <div class="vp_one">
                                    <table>
                                        <tr>
                                            <td><img src="/img/jur.png"></td>
                                            <td><h2>Для юридических лиц</h2>
                                                <div class="answer-expand" style="overflow:hidden;height:107px;">
                                                    <?php foreach($answers as $answer):
                                                        if($answer['questioncategory_id'] != 5) continue;
                                                        ?>
                                                        <div style="background:url(img/sep.png) repeat-x;height:4px;"></div>
                                                        <p class="regular" style="height: 26px; padding-top:6px;width:530px;"><?=$this->html->link(highlight($answer['title'], $search), array('Answers::view', 'id' => $answer['id']), array('escape' => false));?></p>
                                                    <?php endforeach;?>
                                                </div>
                                                <p style="margin-top:8px;"><a href="#" class="av answer-expand-link">Все вопросы</a></p>

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
                        Если вы не можете найти ответ на свой вопрос - напишите нам. Мы постараемся ответить вам в течении 24 часов по рабочим дням.
                        <?=$this->html->link('<img src="/img/otp_em.jpg">', 'Pages::contacts', array('escape' => false))?>
                    </div>
                    <!--div id="tp">
                        <h2>текущие питчи</h2>
                    </div-->
                </div>
            </div><!-- /content -->
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->script(array('help/index'), array('inline' => false))?>
<?=$this->html->style(array('/help'), array('inline' => false))?>