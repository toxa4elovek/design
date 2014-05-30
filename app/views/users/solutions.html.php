<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>

    <div class="middle">
        <div class="middle_inner conteiners" style="padding:66px 34px 20px 63px !important;margin-top:0;">
            <section>
                <div class="menu" style="background:none;border:none;width:857px;margin:0 55px 36px 0px;">
                    <nav class="main_nav clear" style="width:832px;">
                        <?=$this->view()->render(array('element' => 'office/nav'));?>
                    </nav>
                </div>
            </section>
            <?=$this->view()->render(array('element' => 'complete-process/filtersmenu'), array('link' => 1))?>
            <div class="portfolio" style="min-height:500px;">
                <?php if(count($solutions) > 0):?>
                    <p class="supplement2" style="margin-top:10px; margin-bottom: 10px;">Выберите работы для отображения портфолио в просмотре вашего <a target="_blank" href="/users/view/<?=$this->user->getId()?>">профиля</a>.</p>
                    <ul class="list_portfolio">
                        <?php foreach($solutions as $solution):                    ?>
                        <li>
                            <div class="photo_block">
                                <?php if($solution->pitch->category_id == 7):?>
                                    <a href="/pitches/viewsolution/<?=$solution->id?>" style="width:147px;height:104px;background-color:#efefef;display:block;color:#666666;text-decoration:none;font-weight:bold;padding-top:16px;padding: 16px;">
                                        <?php if(mb_strlen(trim($solution->description)) > 100):?>
                                        <?=mb_substr(trim($solution->description), 0, 100, 'UTF-8')?>
                                        <?php else:?>
                                        <?=trim($solution->description)?>
                                        <?php endif?>
                                    </a>
                                <?php else:?>
                                    <a href="/pitches/viewsolution/<?=$solution->id?>"><img width="180" height="135" src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>" alt=""></a>
                                <?php endif?>
                                <?php if($solution->awarded == 1):?>
                                <span class="medal"></span>
                                <?php endif;?>
                                <div class="photo_opt">
                                    <span class="rating_block"><img src="/img/<?=$solution->rating?>-rating.png" alt="" /></span>
                                    <span class="like_view"><img src="/img/looked.png" alt="" class="icon_looked" /><span><?=$solution->views?></span>
                                    <img src="/img/like.png" alt="" /><span><?=$solution->likes?></span></span>
                                    <!--span class="bottom_arrow"><a href="#"><img src="/img/marker5_2.png" alt=""></a></span-->
                            </div>
                            </div>
                            <div class="selecting_numb">
                                <?php if(($filterType != 'nominating') && ($this->user->isSolutionAuthor($solution->user_id))):?>
                                    <?php if(($solution->pitch->private != 1) && ($solution->pitch->category_id != 7)):?>
                                        <input type="checkbox" <?php if($solution->selected):?>checked="checked"<?php endif?> class="select_checkbox" data-id="<?=$solution->id?>" style="margin-right: 5px;">
                                    <?php endif; ?>
                                <?php endif;?>
                                <span class="number_img">#<?=$solution->num?></span>
                            <?=$this->html->link($solution->pitch->title, array('controller' => 'pitches', 'action' => 'view', 'id' => $solution->pitch->id), array('escape' => false))?>   </div>
                        </li>
                        <?php endforeach;?>
                    </ul>
                <?php else:?>
                    <h2 class="largest-header" style="margin-top: 40px;text-transform: uppercase;text-align: center;">У вас еще нет ни одного решения!</h2>
                    <!--h2 class="largest-header" style="margin-top: 40px;text-transform: uppercase;text-align: center;">вы еще не участвовали в <a href="/pitches">питчах</a>!</h2>
                    <h2 class="largest-header" style="margin-top: 40px;text-transform: uppercase;text-align: center;">выкладывайте свои решения,<br> зарабатывайте деньги<br> и пополняйте <a href="/users/solutions">портфолио</a>!</h2-->
                    <!--div style="text-align:center;">
                        <img style="margin-top:35px" src="/img/no-solution.png" alt="помощь"/>
                    </div-->
                <?php endif?>
                <?php if($filterType != 'nominating'):?>
                <!--span class="link_block"><a href="#" id="save" class="add_pdf_link"><span class="red_arrow"></span>Сохранить выбранные решения<br>для отображения в профиле</a></span-->
                <!--span class="link_block"><a href="" class="add_pdf_link"><span class="red_arrow"></span>Cобрать<br>Потрфолио в pdf</a></span-->
                <?php endif;?>
            </div>
        </div><!-- .conteiner -->
        <div id="popup-warning" class="popup-warn generic-window" style="display:none;height:300px;">
            <p style="margin-top:120px;">Изменения, внесенные вами в список отображаемых работ в вашем профиле, сохранены!<br> Вы можете просмотреть ваш профиль по этой ссылке:<br> <a href="http://www.godesigner.ru/user/view/<?=$this->user->getId()?>" target="_blank">http://www.godesigner.ru/user/view/<?=$this->user->getId()?></a></p>
            <div class="final-step-nav wrapper" style="margin-top:20px;"><input type="submit" style="width:108px;" class="button popup-close" value="ОК"></div>
        </div>
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div>
</div><!-- .wrapper -->



<?=$this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'jquery.tooltip.js', 'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array('/main2.css', '/pitches2.css', '/edit','/view', '/messages12', '/pitches12', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css',), array('inline' => false))?>