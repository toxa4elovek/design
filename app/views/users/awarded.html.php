<div class="wrapper">

    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo', 'header' => 'header2'])?>

    <div class="middle">
        <div class="middle_inner conteiners" style="margin-top: 0px;">
            <section>
                <div class="menu" style="background:none;border:none;width:857px;margin:0 55px 36px 0px;">
                    <nav class="main_nav clear" style="width:832px;">
                        <?=$this->view()->render(['element' => 'office/nav']);?>
                    </nav>
                </div>
            </section>
            <?=$this->view()->render(['element' => 'complete-process/filtersmenu'], ['link' => 2])?>
            <div class="portfolio" style="min-height:500px;">
                <?php if (count($solutions) > 0):?>
                    <ul class="list_portfolio">
                        <?php foreach ($solutions as $solution):?>
                        <li>
                            <div class="photo_block">
                                <?php if ($solution['pitch']['category_id'] == 7):?>
                                <a href="/pitches/viewsolution/<?=$solution['id']?>" style="width:147px;height:104px;background-color:#efefef;display:block;color:#666666;text-decoration:none;font-weight:bold;padding-top:16px;padding: 16px;">
                                    <?php if (mb_strlen(trim($solution['description'])) > 100):?>
                                    <?=mb_substr(trim($solution['description']), 0, 100, 'UTF-8')?>
                                    <?php else:?>
                                    <?=trim($solution['description'])?>
                                    <?php endif?>
                                </a>
                                <?php else:?>
                                    <img src="<?=$this->solution->renderImageUrl($solution['images']['solution_galleryLargeSize'])?>" alt="">
                                    <a class="end-pitch-hover" href="/users/step3/<?=$solution['id']?>"></a>
                                <?php endif?>
                                <?php if ($solution['awarded'] == 1):?>
                                <span class="medal"></span>
                                <?php endif;?>
                                <div class="photo_opt">
                                    <span class="rating_block"><img src="/img/<?=$solution['rating']?>-rating.png" alt="" /></span>
                                    <span class="like_view"><img src="/img/looked.png" alt="" class="icon_looked" /><span><?=$solution['views']?></span>
                                    <img src="/img/like.png" alt="" /><span><?=$solution['likes']?></span></span>
                                    <!--span class="bottom_arrow"><a href="#"><img src="/img/marker5_2.png" alt=""></a></span-->
                                </div>
                            </div>
                            <div class="selecting_numb">
                                <?php if ($filterType != 'nominating'):?>
                                <!--input type="checkbox" class="select_checkbox"-->
                                <?php endif;?>
                                <span class="number_img">#<?=$solution['num']?></span>
                            <?=$this->html->link($solution['pitch']['title'], ['controller' => 'pitches', 'action' => 'view', 'id' => $solution['pitch']['id']], ['escape' => false])?>      </div>
                        </li>
                        <?php endforeach;?>
                    </ul>
                <?php else:?>
                    <h2 class="largest-header" style="margin-top: 40px;text-transform: uppercase;text-align: center;">У вас еще нет награжденных решений!<h2>
                    <!--h2 class="largest-header" style="margin-top: 40px;text-transform: uppercase;text-align: center;">у вас еще нет победивших решений,<br> мы знаем как вам <a href="http://godesigner.ru/answers">помочь</a>!</h2>
                    <div style="text-align:center;">
                        <img style="margin-top:35px" src="/img/no-win-yet.png" alt="помощь"/>
                        <div class="supplement3" style="margin-top:10px;">
                            ОЗНАКОМТЕСЬ С НАШИМИМ СОВЕТАМИ В РУБРИКЕ<br> <a href="http://godesigner.ru/answers">ПОМОЩЬ ДИЗАЙНЕРУ</a>
                        </div>
                    </div-->
                <?php endif?>
                <?php if ($filterType != 'nominating'):?>
                <!--span class="link_block"><a href="" class="add_pdf_link"><span class="red_arrow"></span>Cобрать<br>Потрфолио в pdf</a></span--->
                <?php endif;?>
            </div>
        </div><!-- .conteiner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div>
</div><!-- .wrapper -->
<?=$this->html->script([    '/js/users/office/PushNotificationsStatus.js', 'jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'users/office.js'], ['inline' => false])?>
<?=$this->html->style(['/main2.css', '/pitches2.css', '/edit', '/view', '/messages12', '/pitches12', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css', ], ['inline' => false])?>