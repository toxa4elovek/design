<div class="wrapper" xmlns="http://www.w3.org/1999/html">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner">

            <div class="content group">

                <div class="margins-1 expert-personal">
                    <section class="howitworks">
                        <h1 class="h2link"><a href="/experts"/>наши эксперты</a></h1>
                    </section>
                    <div class="margins-2">
                        <?php
                        switch($expert->id):
                            case 1: echo '<img src="/img/experts/fedchenko.jpg" alt="Альберт Федченко">';break;
                            case 3: echo '<img src="/img/experts/kojara.jpg" alt="Кожара Сергей">';break;
                            case 2: echo '<img src="/img/experts/pavlov.jpg" alt="Владимир Павлов">';break;
                            case 4: echo '<img src="/img/experts/chern.jpg" alt="Михаил Чернышев">';break;
                            case 5: echo '<img src="/img/experts/nesterenko218.jpg" alt="Максим Нестеренко">';break;
                            case 6: echo '<img src="/img/experts/efremov218.jpg" alt="Станислав Ефремов">';break;
                            case 7: echo '<img src="/img/experts/percia_218.png" alt="Валентин Перция">';break;
                            case 8: echo '<img src="/img/experts/makarov_dmitry.png" alt="Дмитрий Макаров">';break;
                        endswitch;
                        ?>

                        <div class="about regular">
                            <h2><?=$expert->name?></h2>
                            <?php if(!empty($expert->spec)):?>
                            <p><?=$expert->spec?></p>
                            <?php endif?>
                            <?php echo $expert->text?>

                            <a class="back" href="/experts">все эксперты</a>
                        </div>
                    </div>
                    <div class="clear"></div>

                    <?=$this->view()->render(array('element' => 'experts/expert-faq'), array('questions' => $questions))?>

                </div>
            </div><!-- /content -->

        </div><!-- /middle_inner -->

        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/text', '/howitworks', '/expert-personal'), array('inline' => false))?>