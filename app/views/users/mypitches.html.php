<div class="wrapper login">

    <?= $this->view()->render(['element' => 'header'], ['header' => 'header2', 'logo' => 'logo']) ?>

    <div class="middle">
        <div class="middle_inner conteiners" style="margin-top: 0px;padding-left: 0px;">
            <input type="hidden" value="<?= $this->user->getId() ?>" id="user_id">
            <section>
                <div class="menu" style="background:none;border:none;width:857px;margin-left:63px;margin-top:0;">
                    <nav class="main_nav clear" style="width:832px;">
                        <?= $this->view()->render(['element' => 'office/nav']); ?>
                    </nav>
                </div>
            </section>
            <!--div class="pitches-ajax-wrapper" style="top:122px">
                <div class="pitches-ajax-loader">&nbsp;</div>
            </div-->
            <section>
                <div style="margin-top:-12px;height: 75px; padding-top: 15px; background-color: rgb(243, 243, 243); width: 825px; margin-left: 60px;">
                    <table>
                        <tr>
                            <td>
                                <div id="filterContainer" style="border-radius:4px 4px 4px 4px;border:4px solid #F3F3F3; height:41px;padding-top:10px;background-color:white;box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) inset; width:638px;margin-left:25px">
                                    <ul class="tags" id="filterbox" style="margin-left: 9px"></ul>
                                    <input type="text" id="searchTerm" style="padding-bottom:10px; width:545px; box-shadow:none;line-height:12px; height:13px; padding-top: 3px;margin-left:4px;">
                                    <a href="#" id="filterToggle" data-dir="up" style="float:right;"><img style="padding-top:4px;margin-right:1px;" src="/img/filter-arrow-down.png" alt=""></a>
                                    <a href="#" id="filterClear"></a>
                                </div>
                            </td>
                            <td>
                                <a style="margin-left:15px;margin-top:4px" href="#" id="goSearch" class="button second primary">Поиск</a>
                            </td>
                        </tr>
                    </table>
                    <div id="filtertab" style="display:none;border-radius:10px;padding-top:14px;margin-left:25px;width: 637px;height:347px;background-color: white;z-index:10;position:absolute;">
                        <ul class="filterlist" style="float:left;width:190px;margin-left:25px;text-transform: none">
                            <li class="first">проекты</li>
                            <li style="width:205px"><a data-group="type" data-value="all" href="#">все проекты с моими решениями</a></li>
                            <!--li><a href="#">по новизне</a></li-->
                            <li style="width:85px"><a data-group="type" data-value="current" href="#">текущие</a></li>
                            <li style="width:85px"><a data-group="type" data-value="finished" href="#">завершенные</a></li>
                            <li style="width:85px"><a data-group="type" data-value="favourites" href="#">отслеживаемые</a></li>
                            <li style="width:140px"><a data-group="type" data-value="completion-stage" href="#">на стадии завершения</a></li>
                            <li style="width:85px"><a data-group="type" data-value="awarded" href="#">награжденные</a></li>
                        </ul>
                        <ul class="filterlist" style="float:left;width:151px;margin-left:45px;text-transform: none">
                            <li class="first">категория</li>
                            <li><a data-group="category" data-value="all" href="#">все</a></li>
                            <?php foreach ($categories as $category): ?>
                                <li style="width: 175px;"><a data-group="category" data-value="<?= $category->id ?>" href="#"><?= mb_strtolower($category->title, 'utf-8') ?></a></li>
                            <?php endforeach ?>
                        </ul>
                        <ul class="filterlist" style="float:left;width:160px;margin-left:65px;text-transform: none">
                            <li class="first">гонорар</li>
                            <li style="width:130px"><a data-group="priceFilter" data-value="3" href="#">от 20 000 Р.-</a></li>
                            <li style="width:130px"><a data-group="priceFilter" data-value="2" href="#">от 10 000 - 20 000 Р.-</a></li>
                            <li style="width:130px"><a data-group="priceFilter" data-value="1" href="#">от 5 000 - 10 000 Р.-</a></li>
                        </ul>
                        <div style="clear:both"></div>
                    </div>
                </div>
            </section>
            <section>
                <table id="primary" class="all-pitches">
                    <thead>
                        <tr>
                            <td class="icons" style="width: 20px;"></td>
                            <td class="" style="text-align: left; padding:0 10px 0 40px; width: 255px;"><a href="#" id="sort-title" class="sort-link" data-dir="asc">название проекта</a></td>
                            <td class="pitches-cat"><a href="#" id="sort-category" class="sort-link" data-dir="asc">Категории</a></td>
                            <td class="idea"><a href="#" id="sort-ideas_count" class="sort-link" data-dir="desc">Идеи</a></td>
                            <td class="pitches-time"><a href="#" id="sort-finishDate" class="sort-link" data-dir="asc">Статус</a></td>
                            <td style="text-align: left; padding:0 10px 0 40px"><a href="#" id="sort-price" class="sort-link" data-dir="desc">Цена</a></td>
                        </tr>
                    </thead>
                    <tbody id="table-content">


                    </tbody>
                </table>
                <div class="no-result">
                    <h1>Упс, мы ничего не нашли!</h1>
                    <p class="regular">Попробуйте ввести другое слово, или используйте<br /> стрелку в поле, повторив поиск с выбранным<br /> фильтром. <a href="/answers/view/85">Подробнее…</a></p>
                    <p><img src="https://godesigner.ru/img/help/d3fa990a965b8ebf1cf8691586140165.jpg" alt="" width="610" height="292"></p>
                </div>
                <div class="foot-content">
                    <div class="page-nambe-nav" id="topnav">
                    </div>
                </div>
                <input type="hidden" value="<?=$selectedCategory?>" name="category">
                <input type="hidden" value="<?=$this->user->getId()?>" id="user_id">
            </section>
        </div><!-- /middle_inner -->
        <div id="popup-final-step" class="popup-final-step" style="display:none">
            <h3>Вы уверены, что хотите удалить этот проект?</h3>
            <p>Эта процедура является окончательной, и в дальнейшем вы не сможете изменить свое решение. Нажав "Да, одобряю", вы подтверждаете, что хотите удалить его из списка. Убедитесь, что это черновой вариант и не ждет поступления оплаты на наш счет. За справкой <a href="/pages/contacts">обратитесь к нам</a>.</p>
            <div class="final-step-nav wrapper" style="margin-top: 180px;"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="confirmDelete" value="Да, одобряю"></div>
        </div>
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?= $this->view()->render(['element' => 'popups/mypitches_popup']); ?>
<?= $this->html->script([
    '/js/users/office/PushNotificationsStatus.js',
    'jcarousellite_1.0.1.js',
    'jquery.timers.js',
    'jquery.simplemodal-1.4.2.js',
    'tableloader.js',
    'jquery.timeago.js',
    'fileuploader',
    'jquery.tooltip.js',
    'users/office.js',
    '/js/users/office/ProfRadioList.js',
    '/js/users/office/ProfSelectBox.js',
    'jquery.keyboard.js',
    'users/mypitches_loader.js',
    'pitches/index.js'
], ['inline' => false]) ?>
<?=
$this->html->style(['/main2.css', '/pitches2.css', '/edit', '/view', '/messages12', '/pitches12', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css'], ['inline' => false])?>