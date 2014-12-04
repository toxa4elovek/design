<div class="wrapper pitchpanel login">
    <?= $this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2')) ?>
    <div class="middle">
        <div class="middle_inner_gallery" style="padding-top:25px">
            <h1 class="sale-head regular">Распродажа логотипов</h1>
            <div class="filterBackground">
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <div id="filterContainer">
                                    <ul style="margin-left: 9px" id="filterbox" class="tags"></ul>
                                    <input type="text" id="searchTerm" class="placeholder" placeholder="Найдите себе логотип по ключевому слову или типу">
                                    <a style="float:right;" data-dir="up" id="filterToggle" href="#"><img alt="" src="/img/filter-arrow-down.png" style="padding-top:4px;margin-right:1px;"></a>
                                    <a id="filterClear" href="#"></a>
                                </div>
                            </td>
                            <td>
                                <a class="button" id="goSearch" href="#" style="margin-left:15px;margin-top:4px">Поиск</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div style="border-radius: 10px; padding-top: 14px; margin-left: 25px; width: 617px; height: 347px; background-color: white; z-index: 10; position: absolute;" id="filtertab">
                    <ul style="float:left;width:105px;margin-left:25px;text-transform: none" class="filterlist">
                        <li class="first">питчи</li>
                        <li style="width: 85px;"><a href="#" data-value="all" data-group="type">все</a></li>
                        <li style="width: 85px;"><a href="#" data-value="current" data-group="type">текущие</a></li>
                        <li style="width: 85px;"><a href="#" data-value="finished" data-group="type">завершенные</a></li>
                    </ul>
                    <ul style="float:left;width:151px;margin-left:25px;text-transform: none" class="filterlist">
                        <li class="first">категория</li>
                        <li style=""><a href="#" data-value="all" data-group="category">все</a></li>
                        <li style=""><a href="#" data-value="1" data-group="category">логотип</a></li>
                        <li style=""><a href="#" data-value="2" data-group="category">web-баннер</a></li>
                        <li style=""><a href="#" data-value="3" data-group="category">сайт</a></li>
                        <li><a href="#" data-value="4" data-group="category">флаер</a></li>
                        <li><a href="#" data-value="5" data-group="category">фирменный стиль</a></li>
                        <li><a href="#" data-value="6" data-group="category">страница соцсети</a></li>
                        <li><a href="#" data-value="7" data-group="category">копирайтинг</a></li>
                        <li><a href="#" data-value="8" data-group="category">буклет</a></li>
                        <li><a href="#" data-value="9" data-group="category">иллюстрация</a></li>
                        <li><a href="#" data-value="10" data-group="category">другое</a></li>
                        <li><a href="#" data-value="11" data-group="category">упаковка</a></li>
                        <li><a href="#" data-value="12" data-group="category">реклама</a></li>
                    </ul>
                    <ul style="float:left;width:85px;margin-left:25px;text-transform: none" class="filterlist">
                        <li class="first">сроки</li>
                        <li><a href="#" data-value="1" data-group="timeframe">до 3 дней</a></li>
                        <li><a href="#" data-value="2" data-group="timeframe">до 7 дней</a></li>
                        <li><a href="#" data-value="3" data-group="timeframe">до 10 дней</a></li>
                        <li><a href="#" data-value="4" data-group="timeframe">более 14 дней</a></li>
                    </ul>
                    <ul style="float:left;width:160px;margin-left:25px;text-transform: none" class="filterlist">
                        <li class="first">гонорар</li>
                        <li style="width:130px"><a href="#" data-value="3" data-group="priceFilter">от 20 000 Р.-</a></li>
                        <li style="width:130px"><a href="#" data-value="2" data-group="priceFilter">от 10 000 - 20 000 Р.-</a></li>
                        <li style="width:130px"><a href="#" data-value="1" data-group="priceFilter">от 3 000 - 10 000 Р.-</a></li>
                    </ul>
                    <div style="clear:both"></div>
                </div>
            </div>
            <ul class="marsh">
                <li>
                    <h2 class="greyboldheader">Более 10 тысяч логотипов по цене 9500 рублей</h2>
                </li>
                <li>
                    <h2 class="greyboldheader">Уникальность: каждый лого продается только один раз</h2>
                </li>
                <li class="clear">
                    <h2 class="greyboldheader">Внесение 3 правок бесплатно!</h2>
                </li>
            </ul>
            <div class="portfolio_gallery">
                <ul class="list_portfolio main_portfolio">
                    <?php
                    foreach ($solutions as $solution):
                        $picCounter2 = 0;
                        if (isset($solution->images['solution_galleryLargeSize'][0])) {
                            foreach ($solution->images['solution_galleryLargeSize'] as $image):
                                $picCounter2++;
                            endforeach;
                        } else {
                            if (!isset($solution->images['solution_galleryLargeSize'])) {
                                $solution->images['solution_galleryLargeSize'] = $solution->images['solution'];
                                $picCounter2 = 0;
                                if (is_array($solution->images['solution_galleryLargeSize'])) {
                                    foreach ($solution->images['solution_galleryLargeSize'] as $image) {
                                        $picCounter2++;
                                    }
                                }
                            }
                        }
                        ?>
                        <li id="li_<?= $solution->id ?>"<?= ($picCounter2 > 1) ? 'class=multiclass' : '' ?>>
                            <div class="photo_block">
                                <?php if ($this->solution->getImageCount($solution->images['solution_galleryLargeSize']) > 1): ?>
                                    <div class="image-count"><?= $this->solution->getImageCount($solution->images['solution_solutionView']) ?></div>
                                <?php endif ?>
                                <a style="display:block;" data-solutionid="<?= $solution->id ?>" class="imagecontainer" href="/pitches/viewsolution/<?= $solution->id ?>">
                                    <?php if (!isset($solution->images['solution_galleryLargeSize'][0])): ?>
                                        <img rel="#<?= $solution->num ?>"  width="180" height="135" src="<?= $this->solution->renderImageUrl($solution->images['solution_galleryLargeSize']) ?>" alt="<?= ($pitch->status == 2) ? $this->solution->getShortDescription($solution, 80) : ''; ?>">
                                    <?php else: ?>
                                        <?php
                                        $picCounter = 0;
                                        foreach ($solution->images['solution_galleryLargeSize'] as $image):
                                            ?>
                                            <img class="multi"  width="180" height="135" style="position: absolute;left:10px;top:9px;z-index:1;<?php echo ($picCounter > 0) ? 'display:none;' : 'opacity:1;'; ?>" rel="#<?= $solution->num ?>" src="<?= $this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'], $picCounter) ?>" alt="<?= ($pitch->status == 2) ? $this->solution->getShortDescription($solution, 80) : ''; ?>">
                                            <?php
                                            $picCounter++;
                                        endforeach;
                                        ?>
                                    <?php endif ?>
                                </a>                    
                                <div class="photo_opt">
                                    <div class="" style="display: block; float:left;">
                                        <span class="rating_block">
                                            <div class="ratingcont" data-default="<?= $solution->rating ?>" data-solutionid="<?= $solution->id ?>" style="float: left; height: 9px; background: url(/img/<?= $solution->rating ?>-rating.png) repeat scroll 0% 0% transparent; width: 56px;"></div>
                                        </span>
                                        <span class="like_view" style="margin-top:2px;">
                                            <img src="/img/looked.png" alt="" class="icon_looked"><span><?= $solution->views ?></span>
                                        </span>
                                    </div>
                                    <ul style="margin-left: 78px;" class="right">
                                        <li class="like-hoverbox" style="float: left; margin-top: 0px; padding-top: 0px; height: 15px; padding-right: 0px; margin-right: 0px; width: 38px;">
                                            <a href="#" style="float:left" class="like-small-icon" data-id="<?= $solution->id ?>"><img src="/img/like.png" alt="количество лайков"></a>
                                            <span class="underlying-likes" style="color: rgb(205, 204, 204); font-size: 10px; vertical-align: middle; display: block; float: left; height: 16px; padding-top: 5px; margin-left: 2px;" data-id="<?= $solution->id ?>" rel="http://www.godesigner.ru/pitches/viewsolution/<?= $solution->id ?>"><?= $solution->likes ?></span>
                                            <div class="sharebar" style="padding:0 0 4px !important;background:url('/img/tooltip-bg-bootom-stripe.png') no-repeat scroll 0 100% transparent !important;position:relative;z-index:10000;display: none; left: -10px; right: auto; top: 20px;height: 178px;width:288px;">
                                                <div class="tooltip-wrap" style="height: 140px; background: url(/img/tooltip-top-bg.png) no-repeat scroll 0 0 transparent !important;padding:39px 10px 0 16px !important">
                                                    <div class="body" style="display: block;">
                                                        <table width="100%">
                                                            <tbody><tr height="35">
                                                                    <td width="137" valign="middle">
                                                                        <a id="facebook<?= $solution->id ?>" class="socialite facebook-like" href="http://www.facebook.com/sharer.php?u=http://www.godesigner.ru/pitches/viewsolution/<?= $solution->id ?>" data-href="http://www.godesigner.ru/pitches/viewsolution/<?= $solution->id ?>" data-send="false" data-layout="button_count">
                                                                            Share on Facebook
                                                                        </a>
                                                                    </td>
                                                                    <td width="137" valign="middle">
                                                                        <?php
                                                                        if (rand(1, 100) <= 50) {
                                                                            $tweetLike = 'Мне нравится этот дизайн! А вам?';
                                                                        } else {
                                                                            $tweetLike = 'Из всех ' . $pitch->ideas_count . ' мне нравится этот дизайн';
                                                                        }
                                                                        ?>
                                                                        <a id="twitter<?= $solution->id ?>" class="socialite twitter-share" href="" data-url="http://www.godesigner.ru/pitches/viewsolution/<?= $solution->id ?>?utm_source=twitter&utm_medium=tweet&utm_content=like-tweet&utm_campaign=sharing" data-text="<?php echo $tweetLike; ?>" data-lang="ru" data-hashtags="Go_Deer">
                                                                            Share on Twitter
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <tr height="35">
                                                                    <td valign="middle">
                                                                        <a href="http://www.tumblr.com/share" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:81px; height:20px; background:url('http://platform.tumblr.com/v1/share_1.png') top left no-repeat transparent;">Share on Tumblr</a>
                                                                    </td>
                                                                    <td valign="middle">
                                                                        <a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F<?= $solution->id ?>&media=<?= urlencode('http://www.godesigner.ru' . $this->solution->renderImageUrl($solution->images['solution_solutionView'])) ?>&description=%D0%9E%D1%82%D0%BB%D0%B8%D1%87%D0%BD%D0%BE%D0%B5%20%D1%80%D0%B5%D1%88%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BD%D0%B0%20%D1%81%D0%B0%D0%B9%D1%82%D0%B5%20GoDesigner.ru" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li style="padding-left:0;margin-left:0;float: left; padding-top: 1px; height: 16px; margin-top: 0;width:30px">
                                            <span class="bottom_arrow">
                                                <a href="#" class="solution-menu-toggle"><img src="/img/marker5_2.png" alt=""></a>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="selecting_numb"><span class="price">19000 р.</span><span class="new-price">9500р.-</span></div>
                            <div class="solution_menu" style="display: none;">
                                <ul class="solution_menu_list">
                                    <li class="sol_hov"><a href="/solutions/buy/<?= $solution->id ?>.json" class="hide-item">Купить</a></li>
                                    <li class="sol_hov"><a href="/solutions/warn/94.json" class="warning" data-solution-id="94">Пожаловаться</a></li>
                                </ul>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div id="under_middle_inner"></div>
    </div>
</div>
<?= $this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), '//assets.pinterest.com/js/pinit.js', 'http://surfingbird.ru/share/share.min.js?v=5', 'jquery.simplemodal-1.4.2.js', 'jquery.scrollto.min.js', 'socialite.js', 'jquery.hover.js', 'jquery.raty.min.js', 'jquery-ui-1.8.23.custom.min.js', 'jquery.timeago.js', 'kinetic-v4.5.4.min.js', 'solutions/logosale.js', 'pitches/gallery.js'), array('inline' => false)) ?>
<?=
$this->html->style(array('/messages12', '/pitches12', '/view', '/pitch_overview', '/css/logosale.css'), array('inline' => false))?>