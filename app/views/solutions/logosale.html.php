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
                и экономичный способ получить результат на GoDesigner. <a href="https://www.godesigner.ru/answers/view/99" target="_blank" title="Подробнее">Подробнее..</a>
            </p>
            <div style="text-align:center;margin-top: 16px;margin-bottom: 30px;"><a class="needassist" href="#" style="height:16px;background: url('/img/category_icon.png') no-repeat;padding-left:24px;margin-top: 10px">Не нашли нужный логотип? Спросите у нас.</a></div>
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
                <div id="filtertab" style="display: none;">
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
                    <ul class="activityType twoCollumn filterlist" style="margin-left: 48px;">
                        <li class="first">Популярные теги</li>
                        <?php foreach ($sort_tags as $k => $v):
                            if($k == '') continue;
                            ?>
                            <li><a class="prepTag" href="#"><?= $k ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <ul class="filterlist" style="margin-left: 0;">
                        <li class="first">Популярные запросы</li>
                        <?php foreach ($search_tags as $v):
                            if(preg_match('/\s/', $v->name)) {
                                continue;
                            }
                            ?>
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
                    <h2 class="greyboldheader"><?=$total_count?> логотипов по цене <?php if($this->user->isSubscriptionActive()):?>
                            7500
                        <?php else: ?>
                            9500
                        <?php endif ?> рублей</h2>
                </li>
                <li>
                    <h2 class="greyboldheader">Уникальность: каждый лого продается только один раз</h2>
                </li>
                <li class="last-item">
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
                                                <img src="/img/looked.png" alt="" class="icon_looked"><span><?= $solution['views'] ?></span>
                                            </span>
                                        </div>
                                        <ul style="margin-left: 78px;" class="right">
                                            <li class="like-hoverbox" style="float: left; margin-top: 0px; padding-top: 0px; height: 15px; padding-right: 0px; margin-right: 0px; width: 38px;">
                                                <a href="#" style="float:left" class="like-small-icon" data-id="<?=$solution['id']?>"><img src="/img/like.png" alt="количество лайков" /></a>
                                                <span class="underlying-likes" style="color: rgb(205, 204, 204); font-size: 10px; vertical-align: middle; display: block; float: left; height: 16px; padding-top: 5px; margin-left: 2px;" data-id="<?=$solution['id']?>" rel="https://www.godesigner.ru/pitches/viewsolution/<?=$solution['id']?>"><?=$solution['likes']?></span>
                                                <?php if((($solution['pitch']['private'] != 1) && ($solution['pitch']['category_id'] != 7))):
                                                    $tweetLike = 'Этот логотип можно приобрести у автора за 9500 рублей на распродаже; адаптация названия и 2 правки включены»';
                                                    if(!isset($solution['images']['solution_galleryLargeSize'][0])):
                                                        $url = 'https://www.godesigner.ru' . $solution['images']['solution_gallerySiteSize']['weburl'];
                                                    else:
                                                        $url = 'https://www.godesigner.ru' . $solution['images']['solution_gallerySiteSize'][0]['weburl'];
                                                    endif;
                                                    ?>
                                                    <div class="sharebar">
                                                        <div class="tooltip-block">
                                                            <div class="social-likes" data-counters="no" data-url="https://www.godesigner.ru/pitches/viewsolution/<?=$solution['id']?>" data-title="<?= $tweetLike ?>">
                                                                <div class="facebook" title="Поделиться ссылкой на Фейсбуке">SHARE</div>
                                                                <div class="twitter">TWITT</div>
                                                                <div class="vkontakte" title="Поделиться ссылкой во Вконтакте" data-image="<?= 'https://www.godesigner.ru'. $this->solution->renderImageUrl($solution['images']['solution_solutionView'])?>">SHARE</div>
                                                                <div class="pinterest" title="Поделиться картинкой на Пинтересте" data-media="<?= 'https://www.godesigner.ru'. $this->solution->renderImageUrl($solution['images']['solution_solutionView'])?>">PIN</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif;?>
                                            </li>
                                            <li style="padding-left:0;margin-left:0;float: left; padding-top: 1px; height: 16px; margin-top: 0;width:30px">
                                                <span class="bottom_arrow">
                                                    <a href="#" class="solution-menu-toggle"><img src="/img/marker5_2.png" alt=""></a>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="selecting_numb"><span class="price"><?= round($solution['pitch']['total']) ?> р.</span><span class="new-price"><?php if($this->user->isSubscriptionActive()):?>
                                            7500р.-
                                        <?php else: ?>
                                            9500р.-
                                        <?php endif ?></span></div>
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
<?= $this->html->script(array(
    'flux/flux.min.js',
    '/js/common/comments/actions/CommentsActions.js',
    'http://userapi.com/js/api/openapi.js',
    '//assets.pinterest.com/js/pinit.js',
    'jquery.simplemodal-1.4.2.js',
    'typeahead.jquery.min.js',
    'bloodhound.min.js',
    'jquery.keyboard.js',
    'jquery-plugins/jquery.scrollto.min.js',
    '/js/jquery-plugins/jquery.history.js',
    'socialite.js',
    'jquery.hover.js',
    'jquery-ui-1.11.4.min.js',
    'jquery.raty.min.js',
    'jquery.timeago.js',
    'konva.0.9.5.min.js',
    'solutions/logosale.js',
    'social-likes.min.js',
    'pitches/gallery.js'
), array('inline' => false)) ?>
<?=
$this->html->style(array(
    '/css/common/receipt.css',
    '/messages12', '/pitches12', '/view', '/pitch_overview', '/css/logosale.css', '/step3', '/css/social-likes_flat'), array('inline' => false))?>