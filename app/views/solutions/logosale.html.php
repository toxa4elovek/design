<script type="text/javascript">
    var isCurrentAdmin = <?php echo ($this->user->isAdmin() ? 1 : 0 ); ?>;
    var allowComments = false;
    var currentUserId = <?= ($this->user->getId()) ? $this->user->getId() : 0 ?>;
    var isClient = false;
</script>
<div class="wrapper pitchpanel login">
    <?= $this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2')) ?>
    <div class="middle">
        <div class="middle_inner_gallery" style="padding-top:25px">
            <input type="hidden" value="<?= isset($data['pitch_id']) ? $data['pitch_id'] : 0 ?>" id="pitch_id"/>
            <h1 class="sale-head regular">Распродажа логотипов</h1>
            <p class="sale-str regular">
                Тут вы найдете готовые решения для вашего бизнеса: выберите логотип, и<br />
                дизайнер доделает его согласно вашим комментариям. Это самый быстрый<br />
                и экономичный способ получить результат на GoDesigner. <a href="#" title="Подробнее">Подробнее..</a>
            </p>
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
                <div id="filtertab">
                    <ul class="activityType filterlist">
                        <li class="first">Вид деятельности</li>
                        <li><a class="prepTag" href="#">Недвижимость / Строительство</a></li>
                        <li><a class="prepTag" href="#">Автомобили / Транспорт</a></li>
                        <li><a class="prepTag" href="#">Финансы / Бизнес</a></li>
                        <li><a class="prepTag" href="#">Еда / Напитки</a></li>
                        <li><a class="prepTag" href="#">Реклама / Коммуникации</a></li>
                        <li><a class="prepTag" href="#">Туризм / Путешествие</a></li>
                        <li><a class="prepTag" href="#">Спорт</a></li>
                        <li><a class="prepTag" href="#">Образование / Наука</a></li>
                        <li><a class="prepTag" href="#">Красота / Мода</a></li>
                        <li><a class="prepTag" href="#">Развлечение / Музыка</a></li>
                        <li><a class="prepTag" href="#">Искусство / Культура</a></li>
                        <li><a class="prepTag" href="#">Животные</a></li>
                        <li><a class="prepTag" href="#">Дети</a></li>
                        <li><a class="prepTag" href="#">Охрана / Безопасность</a></li>
                        <li><a class="prepTag" href="#">Медицина / Здоровье</a></li>
                    </ul>
                    <ul class="activityType twoCollumn filterlist">
                        <li class="first">Популярные теги</li>
                        <?php foreach ($sort_tags as $k => $v): ?>
                            <li><a class="prepTag" href="#"><?= $k ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <ul class="filterlist">
                        <li class="first">Популярные запросы</li>
                        <?php foreach ($search_tags as $v):?>
                            <li><a class="prepTag" href="#"><?= $v->name ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <div style="clear:both"></div>
                </div>
            </div>
            <div class="container-adv_search">
                <a id="adv_search" href="#">Расширенный поиск</a>
            </div>
            <div id="logosaleAjaxLoader" style="text-align: center; display: none; margin-top: 10px;"><img src="/img/blog-ajax-loader.gif"></div>
            <?= $this->view()->render(array('element' => 'solution/logo_1')) ?>
            <ul class="marsh">
                <li>
                    <h2 class="greyboldheader"><?=$total_count?> логотипов по цене 9500 рублей</h2>
                </li>
                <li>
                    <h2 class="greyboldheader">Уникальность: каждый лого продается только один раз</h2>
                </li>
                <li class="clear">
                    <h2 class="greyboldheader">Внесение 3 правок бесплатно!</h2>
                </li>
            </ul>
            <div class="portfolio_gallery">
                <h1 id="search_result" class="sale-head regular" style="padding-bottom: 30px;">Результат поиска: <span id="logo_found">0</span></h1>
                <div id="not-found-container">
                    <p class="sale-str regular">
                        К сожалению, мы ничего не нашли.<br />
                        Попробуйте уточнить по виду деятельности, синонимам или характеристикам логотипа,<br />
                        используя все инструменты расширенного поиска:
                    </p>
                    <div class="not-found-background"></div>
                </div>
                <ul class="list_portfolio main_portfolio">
                    <?php
                    foreach ($solutions as $solution):
                        $picCounter2 = 0;
                        if (isset($solution['images']['solution_galleryLargeSize'][0])) {
                            $picCounter2 = count($solution['images']['solution_galleryLargeSize']);
                        } else {
                            if (!isset($solution['images']['solution_galleryLargeSize'])) {
                                $solution['images']['solution_galleryLargeSize'] = $solution['images']['solution'];
                                $picCounter2 = 0;
                                if (is_array($solution['images']['solution_galleryLargeSize'])) {
                                    $picCounter2 = count($solution['images']['solution_galleryLargeSize']);
                                }
                            }
                        }
                        if (!empty($solution['images']['solution_galleryLargeSize'])):
                            ?>
                            <li id="li_<?= $solution['id'] ?>"<?= ($picCounter2 > 1) ? 'class=multiclass' : '' ?>>
                                <div class="photo_block">
                                    <?php if ($this->solution->getImageCount($solution['images']['solution_galleryLargeSize']) > 1): ?>
                                        <div class="image-count"><?= $this->solution->getImageCount($solution['images']['solution_solutionView']) ?></div>
                                    <?php endif ?>
                                    <a data-solutionid="<?= $solution['id'] ?>" class="imagecontainer" href="/pitches/viewsolution/<?= $solution['id'] ?>">
                                        <?php if (!isset($solution['images']['solution_galleryLargeSize'][0])): ?>
                                            <img rel="#<?= $solution['num'] ?>"  width="180" height="135" src="<?= $this->solution->renderImageUrl($solution['images']['solution_galleryLargeSize']) ?>">
                                        <?php else: ?>
                                            <?php
                                            $picCounter = 0;
                                            foreach ($solution['images']['solution_galleryLargeSize'] as $image):
                                                ?>
                                                <img class="multi"  width="180" height="135" style="position: absolute;left:10px;top:9px;z-index:1;<?php echo ($picCounter > 0) ? 'display:none;' : 'opacity:1;'; ?>" rel="#<?= $solution['num'] ?>" src="<?= $this->solution->renderImageUrl($solution['images']['solution_galleryLargeSize'], $picCounter) ?>">
                                                <?php
                                                $picCounter++;
                                            endforeach;
                                            ?>
                                        <?php endif ?>
                                    </a>                    
                                    <div class="photo_opt">
                                        <div class="" style="display: block; float:left;">
                                            <span class="rating_block">
                                                <div class="ratingcont" data-default="<?= $solution['rating'] ?>" data-solutionid="<?= $solution['id'] ?>" style="float: left; height: 9px; background: url(/img/<?= $solution['rating'] ?>-rating.png) repeat scroll 0% 0% transparent; width: 56px;"></div>
                                            </span>
                                            <span class="like_view" style="margin-top:2px;">
                                                <img src="/img/looked.png" alt="" class="icon_looked"><span><?= $solution->views ?></span>
                                            </span>
                                        </div>
                                        <ul style="margin-left: 78px;" class="right">
                                            <li class="like-hoverbox" style="float: left; margin-top: 0px; padding-top: 0px; height: 15px; padding-right: 0px; margin-right: 0px; width: 38px;">
                                                <a href="#" style="float:left" class="like-small-icon" data-id="<?= $solution['id'] ?>"><img src="/img/like.png" alt="количество лайков"></a>
                                                <span class="underlying-likes" style="color: rgb(205, 204, 204); font-size: 10px; vertical-align: middle; display: block; float: left; height: 16px; padding-top: 5px; margin-left: 2px;" data-id="<?= $solution['id'] ?>" rel="http://www.godesigner.ru/pitches/viewsolution/<?= $solution['id'] ?>"><?= $solution['likes'] ?></span>
                                                <div class="sharebar" style="padding:0 0 4px !important;background:url('/img/tooltip-bg-bootom-stripe.png') no-repeat scroll 0 100% transparent !important;position:relative;z-index:10000;display: none; left: -10px; right: auto; top: 20px;height: 178px;width:288px;">
                                                    <div class="tooltip-wrap" style="height: 140px; background: url(/img/tooltip-top-bg.png) no-repeat scroll 0 0 transparent !important;padding:39px 10px 0 16px !important">
                                                        <div class="body" style="display: block;">
                                                            <table width="100%">
                                                                <tbody><tr height="35">
                                                                        <td width="137" valign="middle">
                                                                            <a id="facebook<?= $solution['id'] ?>" class="socialite facebook-like" href="http://www.facebook.com/sharer.php?u=http://www.godesigner.ru/pitches/viewsolution/<?= $solution['id'] ?>" data-href="http://www.godesigner.ru/pitches/viewsolution/<?= $solution['id'] ?>" data-send="false" data-layout="button_count">
                                                                                Share on Facebook
                                                                            </a>
                                                                        </td>
                                                                        <td width="137" valign="middle">
                                                                            <?php
                                                                            if (rand(1, 100) <= 50) {
                                                                                $tweetLike = 'Мне нравится этот дизайн! А вам?';
                                                                            } else {
                                                                                $tweetLike = 'Из всех мне нравится этот дизайн';
                                                                            }
                                                                            ?>
                                                                            <a id="twitter<?= $solution['id'] ?>" class="socialite twitter-share" href="" data-url="http://www.godesigner.ru/pitches/viewsolution/<?= $solution['id'] ?>?utm_source=twitter&utm_medium=tweet&utm_content=like-tweet&utm_campaign=sharing" data-text="<?php echo $tweetLike; ?>" data-lang="ru" data-hashtags="Go_Deer">
                                                                                Share on Twitter
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr height="35">
                                                                        <td valign="middle">
                                                                            <a href="http://www.tumblr.com/share" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:81px; height:20px; background:url('http://platform.tumblr.com/v1/share_1.png') top left no-repeat transparent;">Share on Tumblr</a>
                                                                        </td>
                                                                        <td valign="middle">
                                                                            <a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F<?= $solution['id'] ?>&media=<?= urlencode('http://www.godesigner.ru' . $this->solution->renderImageUrl($solution['images']['solution_solutionView'])) ?>&description=%D0%9E%D1%82%D0%BB%D0%B8%D1%87%D0%BD%D0%BE%D0%B5%20%D1%80%D0%B5%D1%88%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BD%D0%B0%20%D1%81%D0%B0%D0%B9%D1%82%D0%B5%20GoDesigner.ru" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
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
                                <div class="selecting_numb"><span class="price"><?= $solution['pitch']['total'] ?> р.</span><span class="new-price">9500р.-</span></div>
                                <div class="solution_menu" style="display: none;">
                                    <ul class="solution_menu_list">
                                        <li class="sol_hov"><a data-solutionid="<?= $solution['id'] ?>" class="imagecontainer" href="/pitches/viewsolution/<?= $solution['id'] ?>" class="imagecontainer">Купить</a></li>
                                        <li class="sol_hov"><a href="/solutions/warn/<?= $solution['id'] ?>.json" class="warning" data-solution-id="<?= $solution['id'] ?>">Пожаловаться</a></li>
                                    </ul>
                                </div>
                            </li>
                            <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
            </div>
            <div id="officeAjaxLoader" style="text-align: center; display: none; margin-top: 10px;"><img src="/img/blog-ajax-loader.gif"></div>
        </div>
        <div id="under_middle_inner"></div>
    </div>
</div>
<?= $this->view()->render(array('element' => 'popups/solution_sale'), array('data' => $data)) ?>
<?= $this->view()->render(array('element' => 'popups/warning')) ?>
<?= $this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), '//assets.pinterest.com/js/pinit.js', 'jquery.simplemodal-1.4.2.js', 'jquery.keyboard.js', 'jquery.scrollto.min.js', 'socialite.js', 'jquery.hover.js', 'jquery-ui-1.8.23.custom.min.js', 'jquery.raty.min.js', 'jquery.timeago.js', 'kinetic-v4.5.4.min.js', 'solutions/logosale.js', 'pitches/gallery.js'), array('inline' => false)) ?>
<?=
$this->html->style(array('/messages12', '/pitches12', '/view', '/pitch_overview', '/css/logosale.css', '/step3'), array('inline' => false))?>