<div class="wrapper">

    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo'])?>

    <div class="middle">
        <div class="middle_inner">



            <div class="margins-1 experts-page">

                <section>
                    <h1 class="page-title-with-flag">наши эксперты</h1>
                </section>
                <div class="w100">

                    <div style="background: url('/img/experts/gavel.gif') no-repeat scroll center 100% transparent; margin:0 0 25px;padding:25px 0 80px; text-align: center; text-transform: uppercase;" class="largest-header">наши Опытные <a href="/answers/view/66">эксперты</a><br />будут рады помочь вам<br />с выбором варианта...</div>

                    <div class="right-col regular">

                        <h2>Возникли вопросы?</h2>
                        <p>Более подробно об опции <a href="/answers/view/66">«Эксперт&shy;ное мнение»</a> вы можете ознакомить&shy;ся в разделе <a href="/answers">«Помощь»</a></p>
                    </div>
                    <div class="left-col regular">
                        <p>Если вам нужно знать мнение профессионалов, спросите наших экспертов. С&nbsp;их помощью вы узнаете, какое креативное решения является самым выи&shy;грышным, исходя из вашего брифа. Одни из самых видных деятелей реклам&shy;ной индустрии объяснят вам, что к чему и обоснуют свои аргументы. Будьте спокойны, с вами работают абсолютные профессионалы!</p>

                    </div>
                    <div class="clear"></div>

                    <ul>
                        <?php
                        $counter = 1;
                        $imageArray = [
                            1 => '/img/experts/fedchenko.jpg',
                            3 => '/img/experts/kojara.jpg',
                            2 => '/img/experts/pavlov.jpg',
                            4 => '/img/experts/chern.jpg',
                            5 => '/img/experts/nesterenko218.jpg',
                            6 => '/img/experts/efremov218.jpg',
                            7 => '/img/experts/percia_218.png',
                            8 => '/img/experts/makarov_dmitry.png',
                        ];
                        foreach ($experts as $expert):
                            if ($counter > 3): $counter = 1; endif
                        ?>
                        <li class="<?php if ($counter == 1):?>next first-in-line<?php elseif ($counter ==3):?>last-in-line<?php else:?>next<?php endif?>">
                            <div class="regular">
                                <a href="/experts/view/<?=$expert->id?>"><img src="<?=$imageArray[$expert->id]?>" alt="<?=$expert->name?>"></a>
                                <h2><a href="/experts/view/<?=$expert->id?>"><?php echo $expert->name?></a></h2>
                                <p><?php echo $expert->title?></p>

                                <a href="/experts/view/<?=$expert->id?>" class="more">подробнее</a>
                            </div>
                        </li>
                        <?php
                        $counter++;
                        endforeach?>

                        <li>

                            <div class="regular">
                                <div style="width:218px;" class="empty-expert"></div>
                                <h2 style="color:#FF585D;margin-top: 240px;">Кто следующий?</h2>
                                <p>Следите за нашими новостями и вы заметите пополнения!</p>
                                <!--a href="" class="more">подробнее</a-->
                            </div>

                        </li>
                        <!-- // Дубль -->
                    </ul>

                </div>
            </div>

            <div class="clear"></div>


        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(['/css/common/page-title-with-flag.css', '/text', '/howitworks', '/experts'], ['inline' => false])?>