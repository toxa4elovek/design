<div class="new-wrapper login">
    <script id="twitter-wjs" type="text/javascript" async defer src="//platform.twitter.com/widgets.js"></script>
    <?= $this->view()->render(['element' => 'header'], ['header' => 'header2', 'logo' => 'logo']) ?>
    <?php

        date_default_timezone_set('Europe/Kaliningrad');
    ?>
    <div class="new-middle">
        <div class="new-middle_inner">
            <input type="hidden" value="<?= $this->user->getId() ?>" id="user_id">
            <script type="text/javascript">
                var offsetDate = Date.parse('<?= date('Y/m/d H:i:s', strtotime($date)) ?>');
                var isCurrentAdmin = <?php echo $this->user->isAdmin() ? 1 : 0 ?>;
                var isCurrentExpert = <?php echo $this->user->isExpert() ? 1 : 0 ?>;
                var isAdmin = <?php echo($this->user->isAdmin() ? 1 : 0); ?>;
                var isFeedWriter = <?php echo $this->user->isFeedWriter() ? 1 : 0 ?>;
                var isAllowedToComment = <?php echo($this->user->isAllowedToComment() ? 1 : 0); ?>;
                var userName = '<?php echo ($this->user->getId()) ? $this->user->getFormattedName($this->user->firstname, $this->user->lastname) : ''; ?>';
                var userGender = <?php echo $this->user->getGender(); ?>;
                var tag = '<?= $tag?>';
            </script>
                <?php if ($this->user->isAdmin() || $this->user->isFeedWriter()): ?>
                <div id="news-add" style="display:none;">
                    <input type="text" name="news-title" placeholder="Заголовок">
                    <input type="text" name="news-link" placeholder="Ссылка">
                    <span id="show-all-fileds">Показать все поля</span>
                    <textarea rows="4" name="news-description" placeholder="Текст поста"></textarea>
                    <input id="news-add-tag" type="text" name="news-tag" placeholder="Тег">
                    <p>
                        <input id="news-file" type="file" name="news-banner">
                        <label for="news-file" id="news-add-photo">Добавить фотографию 620 х 415 px</label>
                        <label><input type="checkbox" id="isBanner" name="news-made-banner">Сделать баннером</label>
                        <a id="submit-news" class="button" href="#">Отправить</a>
                    </p>
                    <div id="previewImage"></div>
                </div>
                <div id="news-add-separator" style="display:none;"></div>
                <div class="new-content group" style="margin-top:10px">
                <?php endif; ?>
                <?php
                if (($banner) && $this->user->getId() && (!$_COOKIE['closedbanner' . $banner->id])): ?>
                    <div class="banner-block">
                        <div>
                            <div data-bannerid="<?= $banner->id ?>" class="close-gender"></div>
                            <span><?= $banner->title ?></span>
                            <p><?= $this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($banner->short) ?></p>
                        </div>
                    </div>
                    <nav class="main_nav clear" style="width:832px;margin:30px auto 25px;">
                        <?= $this->view()->render(['element' => 'office/nav']); ?>
                    </nav>
                    <div class="new-content group" style="margin-top:10px">
                <?php elseif ($this->user->getGender() < 1 && $this->user->getId()): ?>
                        <div id="gender-box">
                            <div>
                                <div class="close-gender"></div>
                                <span>
                                    <span>Укажите ваш пол, пожалуйста:</span> 
                                    <label><input type="radio" name="gender" id="male"> Мужской</label>
                                    <label><input type="radio" name="gender" id="female"> Женский</label>
                                </span>
                                <p>Это необходимо для корректного отображения ваших действий в ленте обновлений</p>
                            </div>
                        </div>
                        <nav class="main_nav clear" style="width:832px;margin:66px auto -50px;">
                            <?= $this->view()->render(['element' => 'office/nav']); ?>
                        </nav>
                        <div class="new-content group" style="margin-top:10px">
                <?php else: ?>
                            <?php if ($this->user->getId()): ?>
                            <nav class="main_nav clear" style="width:832px;margin:56px auto -50px;">
                                <?= $this->view()->render(['element' => 'office/nav']); ?>
                            </nav>
                            <?php endif; ?>
                            <div class="new-content group">
                            <?php if((!isset($_COOKIE['closed-email-banner'])) && (!$this->user->getId() || ($this->user->getId() && (int) $this->user->read('user.email_digest') === 0))):?>
                                <div class="email-prompt">
                                    <?php if($this->user->getId() && (int) $this->user->read('user.email_digest') === 0):?>
                                        <form method="post" action="/users/activateEmailSubscription/">
                                            <a href="#" class="close"></a>
                                            <h2>Активируйте подписку на новости дизайна</h2>
                                            <input type="submit" name="submit" value="Включить" style="float:right;">
                                            <input type="hidden" name="email" placeholder="Email" value="<?=$this->user->read('user.email')?>">
                                            <div class="clear"></div>
                                        </form>
                                    <?php elseif(!$this->user->getId()):?>
                                        <form method="post" action="/users/activateEmailSubscription/">
                                            <a href="#" class="close"></a>
                                            <h2>Подпишитесь на новости дизайна</h2>
                                            <input type="submit" name="submit" value="Подписаться">
                                            <input type="email" name="email" placeholder="Email">
                                            <div class="clear"></div>
                                        </form>
                                    <?php endif ?>
                                </div>
                            <?php endif; ?>
                <?php endif; ?>
                            <div id="l-sidebar-office">
                                <?php
                                $solutionDate = '';
                                $count = 0;
                                if ((isset($solutions)) && ($solutions) && (count($solutions) > 0)):
                                foreach ($solutions as $solution):
                                    if (isset($solution->solution->images['solution_leftFeed'])) :
                                        if (isset($solution->solution->images['solution_leftFeed'][0]['weburl'])) {
                                            $image = $solution->solution->images['solution_leftFeed'][0]['weburl'];
                                        } else {
                                            $image = $solution->solution->images['solution_leftFeed']['weburl'];
                                        }

                                        if ($count == 0) {
                                            $solutionDate = $solution->created;
                                        }
                                        $count++;
                                        ?>
                                        <div class="solutions-block">
                                            <a href="https://godesigner.ru/pitches/viewsolution/<?= $solution->solution_id ?>"><div class="left-sol" style="background: url(https://godesigner.ru<?= $image ?>)"></div></a>
                                            <div class="solution-info">
                                                <p class="creator-name"><a target="_blank" href="https://godesigner.ru/users/view/<?= $solution->user_id ?>"><?= $solution->creator ?></a></p>
                                                <p class="ratingcont" data-default="<?= $solution->solution->rating ?>" data-solutionid="<?= $solution->solution->id ?>" style="height: 9px; background: url(https://godesigner.ru/img/<?= $solution->solution->rating ?>-rating.png) no-repeat scroll 0% 0% transparent;display:inline-block;width: 56px;"></p>
                                                <a data-id="<?= $solution->solution->id ?>" class="like-small-icon" href="#"><span><?= $solution->solution->likes ?></span></a>
                                                <?php if ((($solution->pitch->private != 1) && ($solution->pitch->category_id != 7))):
                                                    if (rand(1, 100) <= 50) {
                                                        $tweetLike = 'Мне нравится этот дизайн! А вам?';
                                                    } else {
                                                        $tweetLike = 'Из всех ' . $solution->pitch->ideas_count . ' мне нравится этот дизайн';
                                                    }
                                                    if (!isset($solution->solution->images['solution_galleryLargeSize'][0])):
                                                        $url = 'https://godesigner.ru' . $solution->solution->images['solution_gallerySiteSize']['weburl'];
                                                    else:
                                                        $url = 'https://godesigner.ru' . $solution->solution->images['solution_gallerySiteSize'][0]['weburl'];
                                                    endif;
                                                    ?>
                                                    <div class="sharebar" style="position: absolute; display: none; top: 40px; left: 165px;">
                                                        <div class="tooltip-block">
                                                            <div class="social-likes" data-counters="no" data-url="https://godesigner.ru/pitches/viewsolution/<?=$solution->solution->id?>" data-title="<?= $tweetLike ?>">
                                                                <div class="facebook" title="Поделиться ссылкой на Фейсбуке">SHARE</div>
                                                                <div class="twitter" data-via="Go_Deer">TWITT</div>
                                                                <div class="vkontakte" title="Поделиться ссылкой во Вконтакте" data-image="<?= 'https://godesigner.ru'. $this->solution->renderImageUrl($solution->solution->images['solution_solutionView'])?>">SHARE</div>
                                                                <div class="pinterest" title="Поделиться картинкой на Пинтересте" data-media="<?= 'https://godesigner.ru'. $this->solution->renderImageUrl($solution->solution->images['solution_solutionView'])?>">PIN</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif;?>
                                                <span class="bottom_arrow">
                                                    <a href="#" class="solution-menu-toggle"><img src="https://godesigner.ru/img/marker5_2.png" alt=""></a>
                                                </span>
                                                <div class="solution_menu" style="display: none;">
                                                    <ul class="solution_menu_list" style="position:absolute;z-index:6;">
                                                        <?php if ($solution->pitch->user_id == $this->user->getId() && ($solution->pitchesCount < 1) && (!$record->selectedSolutions)): ?>
                                                            <li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">
                                                                <a class="select-winner" href="https://godesigner.ru/solutions/select/<?= $solution->solution->id ?>.json" data-solutionid="<?= $solution->solution->id ?>" data-user="<?= $this->user->getFormattedName($solution->solution->user->first_name, $solution->solution->user->last_name) ?>" data-num="<?= $solution->solution->num ?>" data-userid="<?= $solution->solution->user_id ?>">Назначить победителем</a>
                                                            </li>
                                                        <?php elseif ($solution->pitch->user_id == $this->user->getId() && ($solution->pitch->awarded != $solution->solution->id) && (($solution->pitch->status == 1) || ($solution->pitch->status == 2)) && ($solution->pitch->awarded != 0)): ?>
                                                            <li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">
                                                                <a class="select-multiwinner" href="https://godesigner.ru/pitches/setnewwinner/<?= $solution->solution->id ?>" data-solutionid="<?= $solution->solution->id ?>" data-user="<?= $this->user->getFormattedName($solution->solution->user->first_name, $solution->solution->user->last_name) ?>" data-num="<?= $solution->solution->num ?>" data-userid="<?= $solution->solution->user_id ?>">Назначить <?= $solution->pitchesCount + 2 ?> победителя</a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if ($solution->pitch->user_id == $this->user->getId() && $this->user->isAllowedToComment()): ?>
                                                            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="#" class="solution-link-menu" data-id="<?= $solution->solution->id ?>" data-comment-to="#<?= $solution->solution->num ?>">Комментировать</a></li>
                                                        <?php endif; ?>
                                                        <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="https://godesigner.ru/solutions/warn/<?= $solution->solution->id ?>.json" class="warning" data-solution-id="<?= $solution->solution->id ?>">Пожаловаться</a></li>
                                                        <?php if ($this->user->isAdmin()): ?>
                                                            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a class="delete-solution" data-solution="<?= $solution->solution->id ?>" data-solution_num="<?= $solution->solution->num ?>" href="https://godesigner.ru/solutions/delete/<?= $solution->solution->id ?>.json">Удалить</a></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    else:
                                        //var_dump($solution->solution->data());
                                    endif;
                                endforeach;
                                endif;
                                ?>
                                <script type="text/javascript">
                                    var solutionDate = '<?= date('Y-m-d H:i:s', strtotime($solutionDate)) ?>';
                                </script>
                                <div id="SolutionAjaxLoader" style="text-align: center; display: none; margin-top: 10px;"><img src="https://godesigner.ru/img/blog-ajax-loader.gif"></div>
                            </div>
                            <div id="r-sidebar-office">
                                <div id="floatingLayer">
                                    <div id="container-job-designers">
                                        <div class="rs-header"><a href="https://twitter.com/hashtag/%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%B0%D0%B4%D0%BB%D1%8F%D0%B4%D0%B8%D0%B7%D0%B0%D0%B9%D0%BD%D0%B5%D1%80%D0%BE%D0%B2?f=realtime&src=hash" target="_blank" style="color: #fff;">Twitter #работадлядизайнеров</a></div>
                                        <div id="content-job">
                                            <?php echo $this->stream->renderStreamFeed(6); ?>
                                        </div>
                                        <div id="all-tweets"><a href="https://twitter.com/hashtag/%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%B0%D0%B4%D0%BB%D1%8F%D0%B4%D0%B8%D0%B7%D0%B0%D0%B9%D0%BD%D0%B5%D1%80%D0%BE%D0%B2?f=realtime&src=hash" target="_blank">Посмотреть все твиты</a></div>
                                    </div>
                                    <!--div id="container-new-pitches">
                                        <div class="rs-header">Новые питчи</div>
                                        <div id="content-pitches">
                                    <?php
                                    $count = 0;
                                    foreach ($pitches as $pitch) :
                                        if ($count == 0) {
                                            $pitchDate = $pitch->started;
                                        }
                                        $count++;
                                        ?>
                                                            <div class="new-pitches">
                                                                <div class="new-price"><?= $this->moneyFormatter->formatMoney($pitch->price, ['suffix' => 'р.']) ?></div>
                                                                <div class="new-title"><a href="/pitches/view/<?= $pitch->id ?>"><?= $pitch->title ?></a></div>
                                                            </div>
                                    <?php endforeach; ?>
                                            <script type="text/javascript">
                                                var pitchDate = '<?= date('Y-m-d H:i:s', strtotime($pitchDate)) ?>';
                                            </script>
                                        </div>
                                        <div id="all-pitches"><a href="/pitches" target="_blank" title="Все питчи">Все питчи</a></div>
                                    </div-->
                                    <div id="container-design-news">
                                        <div class="rs-header">Новости дизайна и культуры</div>
                                        <div id="content-news">
                                            <?php
                                            $newsDate = '';
                                            $designNewsInitialData = [];
                                            if ($news) :
                                            foreach ($news as $n): $host = parse_url($n->link);
                                                if (strtotime($newsDate) < strtotime($n->created)) {
                                                    $newsDate = $n->created;
                                                }
                                                if ($n->title == '') {
                                                    continue;
                                                }
                                                $n->host = $host;
                                                $designNewsInitialData[] = $n->data();
                                                ?>
                                                <div class="design-news"><a target="_blank" href="https://godesigner.ru/users/click?link=<?= $n->link ?>&id=<?= $n->id ?>"><?= $n->title ?></a> <br><a class="clicks" href="https://godesigner.ru/users/click?link=<?= $n->link ?>&id=<?= $n->id ?>"><?= $host['host'] ?></a></div>
                                            <?php endforeach;
                                                $jsonDesignNewsInitialData = json_encode($designNewsInitialData);
                                            endif; ?>
                                            <script type="text/javascript">
                                                var newsDate = '<?= date('Y-m-d H:i:s', strtotime($newsDate)) ?>';
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="center_sidebar">
                                <div class="center-boxes" id="updates-box-">

                                    

                                    <?php
                                    if (($shareEvent) && ($shareEvent->type == 'newsAdded')):
                                    $object = $shareEvent->data();
                                        if (!empty($object['news']['og_image'])) {
                                            $newsImage = $object['news']['og_image'];
                                        } else {
                                            $newsImage = $object['news']['imageurl'];
                                        }
                                    $isValidImage = function ($url) {
                                    if (empty($url)):
                                    return false;
                                    endif;
                                    return true;
                                    }
                                    ?>
                                    <div class="box" data-newsid="<?= $object['news']['id'] ?>" <?php if (!$isValidImage($newsImage)): echo 'style="margin-top: 34px;"'; endif;?> data-eventid="<?= $object['id'] ?>">
                                        <?php if ($isValidImage($object['news']['imageurl'])):?>
                                            <p class="img-box">
                                                <a class="post-link" href="<?= $object['news']['link'] ?>"><img onerror="imageLoadError(this);" class="img-post" src="<?= ((strpos($object['news']['imageurl'], '/events/') !== false) && (strpos($object['news']['imageurl'], '/events/') === 0)) ? 'https://godesigner.ru'.$object['news']['imageurl'] : $object['news']['imageurl']?>"></a>
                                            </p>
                                        <?php elseif ($this->feed->isEmbeddedLink($object['news']['link'])):?>
                                            <p class="img-box">
                                                <?php echo $this->feed->generateEmbeddedIframe($object['news']['link'])?>
                                            </p>
                                        <?php endif?>
                                        <div class="r-content post-content" <?php if ((!$object['news']['tags']) || (preg_match('/iframe/', $object['news']['short']))): ?>style="padding-top: 0px;"<?php endif; ?>>
                                            <?php if (($object['news']['tags']) && (!preg_match('/iframe/', $object['news']['short']))): ?>
                                                <p class="img-tag"><a class="tag-title" href="/news?tag=<?= urlencode($object['news']['tags']) ?>"><?= $object['news']['tags'] ?></a></p>
                                            <?php endif; ?>
                                            <a class="img-post" href="<?= $object['news']['link'] ?>" target="_blank"><h2><?= $object['news']['title'] ?></h2></a>
                                            <p class="img-short"><?php echo $object['news']['short'] ?></p>
                                            <p class="timeago">
                                                <time class="timeago" datetime="<?= date('c', strtotime($object['news']['created'])) ?>"><?= $object['news']['created'] ?></time> <?php if (!empty($object['host'])):?>с сайта <?php endif?><?= $object['host'] ?>
                                            </p>
                                        </div>
                                            <div class="box-info" style="margin-top: 0;">
                                                <?php if ($this->user->getId()):?>
                                                <a style="padding-left: 1px;padding-right: 10px;" data-news="1" data-id="<?= $object['news']['id'] ?>" class="like-small-icon-box" data-userid="<?= $this->user->getId() ?>" data-vote="<?= $object['allowLike'] ?>" data-likes="<?= $object['news']['liked'] ?>" href="#"><?= $object['allowLike'] ? 'Нравится' : 'Не нравится' ?></a>
                                                    <?php if (($isValidImage($newsImage)) or ($object['news']['link'] == '')):?>
                                                        <span style="font-size: 28px;position: relative;top: 4px;">·</span>
                                                    <?php endif?>
                                                <?php endif?>
                                                <?php if (($isValidImage($newsImage)) or ($object['news']['link'] == '')):?>
                                                <a style="padding-left: <?php if ($this->user->getId()):?>5px<?php else:?>2px<?php endif?>;padding-right: 10px; font-size: 14px;" class="share-news-center" href="#">Поделиться</a>
                                                <?php endif?>
                                                <?php
                                                if (!empty($object['news']['og_title'])) {
                                                    $tweetLike = $object['news']['title'];
                                                } else {
                                                    $tweetLike = $object['news']['og_title'];
                                                }
                                                $image = $newsImage;
                                                if ($isValidImage($newsImage)):
                                                    $url = 'https://godesigner.ru/news?event=' . $object['id'];

                                                elseif ((!$isValidImage($newsImage)) and ($object['news']['link'] != '')):
                                                    $url = $object['news']['link'];
                                                    $image = '';
                                                else:
                                                    $url = 'https://godesigner.ru/news?event=' . $object['id'];
                                                endif;
                                                ?>
                                                <div class="sharebar" style="position: absolute; display: none; top: 30px; left: 120px;">
                                                    <div class="tooltip-block" data-link="<?= $object['news']['link']?>">
                                                        <div class="social-likes" data-counters="no" data-url="<?= $url?>" data-title="<?= $tweetLike ?>">
                                                            <div class="facebook" style="display: inline-block;" title="Поделиться ссылкой на Фейсбуке" data-url="<?= $url?>">SHARE</div>
                                                            <div class="twitter" style="display: inline-block;" data-via="Go_Deer">TWITT</div>
                                                            <div class="vkontakte" style="display: inline-block;" title="Поделиться ссылкой во Вконтакте" data-url="<?= $url?>" data-image="<?= $image?>">SHARE</div>
                                                            <?php if ($isValidImage($newsImage)):?>
                                                            <div class="pinterest" style="display: inline-block;" title="Поделиться картинкой на Пинтересте" data-url="<?= $url?>" data-media="<?= $image?>">PIN</div>
                                                            <?php endif?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (($this->user->isAdmin()) || ($this->user->isFeedWriter())):?>
                                                    <span style="font-size: 28px;position: relative;top: 4px;">·</span>
                                                    <a style="padding-left: 5px; font-size: 14px;" data-id="<?= $object['news_id'] ?>" class="hide-news" href="#">Удалить новость</a>
                                                <?php endif?>
                                            </div>
                                        <div data-id="<?= $object['news']['id'] ?>" class="likes">
                                            <?php
                                            $likes_count = 0;
                                            $html_likes = '';
                                            $likes = (int) $object['news']['liked'];
                                            if ($likes) {
                                                foreach ($object['news']['likes'] as $like) {
                                                    ++$likes_count;
                                                    if ($likes > 4) {
                                                        if ($likes_count == 1) {
                                                            $html_likes .= '<span class="who-likes">';
                                                        }
                                                        if ($likes_count == 4) {
                                                            $other = $likes - $likes_count;
                                                            $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a>
                                                                      и <a class="show-other-likes" data-solid="' . $object['news']['id'] . '" href="#">' . $other . ' других</a> <span>лайкнули новость</span></span>';
                                                            break;
                                                        } else {
                                                            $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . ', </a>';
                                                        }
                                                    } elseif (($likes >= 2) && ($likes <= 4)) {
                                                        if ($likes_count == 1) {
                                                            $html_likes .= '<span class="who-likes">';
                                                        }
                                                        if ($likes_count != $likes) {
                                                            $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . ', </a>';
                                                        }
                                                        if ($likes_count == $likes) {
                                                            $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a>';
                                                            $html_likes .= ' <span>лайкнули новость</span></span>';
                                                        }
                                                    } elseif ($likes < 2) {
                                                        $html_likes .= '<span class="who-likes"><a data-id="' . $like['user_id'] . '" target="_blank" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a> <span>' . $this->user->getGenderTxt('лайкнул', $like['user']['gender']) . ' новость</span></span>';
                                                    }
                                                }
                                            }
                                            echo $html_likes;
                                            ?>
                                        </div>
                                    </div>
<?php endif ?>



                                    <?php
                                    $html = '';
                                    $dateEvent = '';
                                    $count = 0;
                                    foreach ($updates as $object):
                                        if ($count == 0) {
                                            $dateEvent = $object['created'];
                                        }
                                        $imageurl = null;
                                        $count++;
                                        if (isset($object['user']) && isset($object['user']['isAdmin']) && ($object['user']['isAdmin'])) {
                                            $avatar = 'https://godesigner.ru/img/icon_57.png';
                                        } else {
                                            $avatar = isset($object['user']['images']['avatar_small']) ? $object['user']['images']['avatar_small']['weburl'] : 'https://godesigner.ru/img/default_small_avatar.png';
                                        }
                                        if ($object['type'] != 'LikeAdded') {
                                            if (isset($object['solution']['images']['solution_solutionView'])) {
                                                if (isset($object['solution']['images']['solution_solutionView'][0]['weburl'])) {
                                                    $imageurl = $object['solution']['images']['solution_solutionView'][0]['weburl'];
                                                } else {
                                                    $imageurl = $object['solution']['images']['solution_solutionView']['weburl'];
                                                }
                                            }
                                        } else {
                                            if (isset($object['solution']['images']['solution_leftFeed'])) {
                                                if (isset($object['solution']['images']['solution_leftFeed'][0]['weburl'])) {
                                                    $imageurl = $object['solution']['images']['solution_leftFeed'][0]['weburl'];
                                                } else {
                                                    $imageurl = $object['solution']['images']['solution_leftFeed']['weburl'];
                                                }
                                            }
                                        }
                                        if ($object['type'] == 'CommentAdded' && !is_null($object['comment'])) :
                                            // Если закрытй питч, или коммент не к решению, то надо скрывать картинки
                                            $long = false;
                                            if ((($object['solution']) || ($object['solution_id'] != 0)) && ($object['pitch']['private'] != '1')):
                                                $long = true;
                                            endif;
                                            if (
                                                (preg_match("/Дизайнеры больше не могут/", $object['updateText']))
                                                ||
                                                (preg_match("/питч завершен и ожидает/", $object['updateText']))
                                                ||
                                                (preg_match("/проект завершен и ожидает/", $object['updateText']))
                                                ||
                                                (preg_match("/Друзья, выбран победитель/", $object['updateText']))
                                                ||
                                                (preg_match("/Друзья, в брифе возникли изменения/", $object['updateText']))
                                            ):
                                                continue;
                                            endif;
                                            ?>
                                            <div class="box" data-eventid="<?= $object['id'] ?>" data-type="<?php echo $object['type'] ?>" data-long="<?php echo $long ?>">
                                                <?php if ($long): ?>
                                                    <div class="l-img l-img-box" style="padding-top: 0">
                                                        <a target="_blank" href="https://godesigner.ru/users/view/<?= $object['user_id'] ?>"><img class="avatar" src="<?= $avatar ?>"></a>
                                                    </div>
                                                    <div class="r-content box-comment">
                                                        <?php if ($this->user->getId() == $object['pitch']['user_id'] || ($object['comment']['public'] && $object['comment']['reply_to'] != 0)): ?>
                                                            <a href="https://godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('оставил', $object['user']['gender']) ?> комментарий в проекте <a href="https://godesigner.ru/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>:
                                                        <?php else: ?>
                                                            <a href="https://godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('прокомментировал', $object['user']['gender']) ?> <a href="https://godesigner.ru/pitches/viewsolution/<?= $object['solution']['id'] ?>">решение #<?= $object['solution']['num'] ?></a> для проекта <a href="https://godesigner.ru/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>:
                                                        <?php endif; ?>
                                                    </div>
                                                    <a href="https://godesigner.ru/pitches/viewsolution/<?= $object['solution']['id'] ?>"><div class="sol"><img src="https://godesigner.ru<?= $imageurl ?>"></div></a>
                                                    <?php if ($this->user->getId()):?>
                                                    <div class="box-info">
                                                        <a href="https://godesigner.ru/solutions/warn/<?= $object['solution']['id'] ?>.json" class="warning-box" data-solution-id="<?= $object['solution']['id'] ?>">Пожаловаться</a>
                                                        <a data-id="<?= $object['solution']['id'] ?>" class="like-small-icon-box" data-userid="<?= $object['solution']['user_id'] ?>" data-vote="<?= $object['allowLike'] ?>" data-likes="<?= $object['solution']['likes'] ?>" href="#"><?= $object['allowLike'] ? 'Нравится' : 'Не нравится' ?></a>
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="r-content box-comment">
                                                        &laquo;<?php echo $object['updateText'] ?>&raquo;
                                                    </div>
                                                <?php else: ?>
                                                    <div class="l-img l-img-box" style="padding-top: 0">
                                                        <a target="_blank" href="https://godesigner.ru/users/view/<?= $object['user_id'] ?>"><img class="avatar" src="<?= $avatar ?>"></a>
                                                    </div>
                                                    <?php if ($this->user->getId() == $object['pitch']['user_id'] || ($object['comment']['public'] && $object['comment']['reply_to'] != 0) || !$object['solution']): ?>
                                                        <div class="r-content box-comment">
                                                            <a href="https://godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('оставил', $object['user']['gender']) ?> комментарий в проекте <a href="https://godesigner.ru/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>:<br /> &laquo;<?php echo $object['updateText'] ?>&raquo;
                                                            <p class="timeago">
                                                                <time class="timeago" datetime="<?= $object['created'] ?>"><?= $object['created'] ?></time>
                                                            </p>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="r-content box-comment" style="padding-bottom: 0">
                                                            <a href="https://godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('прокомментировал', $object['user']['gender']) ?> <a href="https://godesigner.ru/pitches/viewsolution/<?= $object['solution']['id'] ?>">решение #<?= $object['solution']['num'] ?></a> для проекта <a href="https://godesigner.ru/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>: &laquo;<?php echo $object['updateText'] ?>&raquo;
                                                            <p class="timeago">
                                                                <time class="timeago" datetime="<?= $object['created'] ?>"><?= $object['created'] ?></time>
                                                            </p>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php elseif ($object['type'] == 'SolutionAdded' && !is_null($object['solution'])) :
                                            if ($object['pitch']['private'] == 1):
                                                continue;
                                            endif;
                                            ?>
                                            <div class="box" data-eventid="<?= $object['id'] ?>">
                                                <div class="l-img">
                                                    <a target="_blank" href="https://godesigner.ru/users/view/<?= $object['user_id'] ?>"><img class="avatar" src="<?= $avatar ?>"></a>
                                                </div>
                                                <div class="r-content">
                                                    <a href="https://godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('предложил', $object['user']['gender']) ?> решение для проекта <a href="https://godesigner.ru/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>:
                                                </div>
                                                <a href="https://godesigner.ru/pitches/viewsolution/<?= $object['solution']['id'] ?>"><div class="sol"><img src="https://godesigner.ru<?= $imageurl ?>"></div></a>
                                                <?php if ($this->user->getId()): ?>
                                                <div class="box-info">
                                                    <a href="https://godesigner.ru/solutions/warn/<?= $object['solution']['id'] ?>.json" class="warning-box" data-solution-id="<?= $object['solution']['id'] ?>">Пожаловаться</a><span>&middot;</span>
                                                    <a data-id="<?= $object['solution']['id'] ?>" class="like-small-icon-box" data-userid="<?= $object['solution']['user_id'] ?>" data-vote="<?= $object['allowLike'] ?>" data-likes="<?= $object['solution']['likes'] ?>" href="#"><?= $object['allowLike'] ? 'Нравится' : 'Не нравится' ?></a>
                                                </div>
                                                <?php endif;?>
                                                <div id="likes-<?= $object['solution']['id'] ?>" data-id="<?= $object['solution']['id'] ?>" class="likes">
                                                    <?php
                                                    $id = $object['solution']['id'];
                                                    $likes_count = 0;
                                                    $html_likes = '';
                                                    $likes = (int) $object['solution']['likes'];
                                                    if ($likes) {
                                                        foreach ($object['likes'] as $like) {
                                                            ++$likes_count;
                                                            if ($likes > 4) {
                                                                if ($likes_count == 1) {
                                                                    $html_likes .= '<span class="who-likes">';
                                                                }
                                                                if ($likes_count == 4) {
                                                                    $other = $likes - $likes_count;
                                                                    $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a>
                                                                      и <a class="show-other-likes" data-solid="' . $object['solution']['id'] . '" href="#">' . $other . ' других</a> <span>лайкнули решение</span></span>';
                                                                    break;
                                                                } else {
                                                                    $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . ', </a>';
                                                                }
                                                            } elseif (($likes >= 2) && ($likes <= 4)) {
                                                                if ($likes_count == 1) {
                                                                    $html_likes .= '<span class="who-likes">';
                                                                }
                                                                if ($likes_count != $likes) {
                                                                    $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . ', </a>';
                                                                }
                                                                if ($likes_count == $likes) {
                                                                    $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a>';
                                                                    $html_likes .= ' <span>лайкнули решение</span></span>';
                                                                }
                                                            } elseif ($likes < 2) {
                                                                $html_likes .= '<span class="who-likes"><a data-id="' . $like['user_id'] . '" target="_blank" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a> <span>' . $this->user->getGenderTxt('лайкнул', $like['user']['gender']) . ' решение</span></span>';
                                                            }
                                                        }
                                                    }
                                                    echo $html_likes;
                                                    ?>
                                                </div></div>
                                        <?php elseif ($object['type'] == 'newsAdded'):
                                            if ($shareEvent->news->id == $object['news']['id']) {
                                                continue;
                                            }
                                            if (!empty($object['news']['og_image'])) {
                                                $newsImage = $object['news']['og_image'];
                                            } else {
                                                $newsImage = $object['news']['imageurl'];
                                            }
                                            if ((preg_match('@fb-xfbml-parse-ignore@', $object['news']['short'])) || (preg_match('@instagram-media@', $object['news']['short'])) || (preg_match('@vk_post@', $object['news']['short']))):
                                                if (preg_match('@vk_post@', $object['news']['short'])) {
                                                    $text = str_replace('{width: 500}', '{width: 600}', $object['news']['short']);
                                                } else {
                                                    $text = str_replace('data-width="500"', 'data-width="600"', $object['news']['short']);
                                                    $text = str_replace('<div id="fb-root"></div>', '', $text);
                                                }
                                                ?>
                                                <div data-created="<?= $object['news']['created'] ?>" data-eventid="<?= $object['id']?>" data-newsid="<?= $object['news']['id'] ?>">
                                                    <?php echo $text ?>
                                                </div>
                                            <?php
                                            else:
                                            $isValidImage = function ($url) {
                                                if (empty($url)):
                                                    return false;
                                                endif;
                                                return true;
                                            }
                                            ?>
                                            <?php
                                            $style = '';
                                            $coub = false;
                                            if ((bool) preg_match('#<iframe src="//coub.com#', $object['news']['short'])):
                                                $style = '';
                                                $coub = true;
                                            endif;
                                            ?>
                                            <div class="box" data-created="<?= $object['news']['created'] ?>" data-newsid="<?= $object['news']['id'] ?>" <?php if ((!$isValidImage($newsImage)) && (!$coub)): echo 'style="margin-top: 34px;"'; endif;?> data-eventid="<?= $object['id'] ?>">
                                                <?php if ($isValidImage($object['news']['imageurl'])):?>
                                                <p class="img-box">
                                                    <a class="post-link" href="<?= $object['news']['link'] ?>" target="_blank"><img onerror="imageLoadError(this);" class="img-post" src="<?= ((strpos($object['news']['imageurl'], '/events/') !== false) && (strpos($object['news']['imageurl'], '/events/') === 0)) ? 'https://godesigner.ru'.$object['news']['imageurl'] : $object['news']['imageurl']?>"></a>
                                                </p>
                                                <?php elseif ($this->feed->isEmbeddedLink($object['news']['link'])):?>
                                                <p class="img-box">
                                                    <?php echo $this->feed->generateEmbeddedIframe($object['news']['link'])?>
                                                </p>
                                                <?php elseif ($coub):?>
                                                    <p class="img-box">
                                                        <?php echo $this->feed->generateEmbeddedIframe($object['news']['short'])?>
                                                    </p>
                                                <?php endif?>

                                                <div class="r-content post-content" <?php if ((!$object['news']['tags']) || (preg_match('/iframe/', $object['news']['short']))): ?>style="padding-top: 0; <?=$style?>"<?php endif; ?>>
                                                    <?php if (($object['news']['tags']) && (!preg_match('/iframe/', $object['news']['short']))): ?>
                                                        <p class="img-tag"><a class="tag-title" href="/news?tag=<?= urlencode($object['news']['tags']) ?>"><?= $object['news']['tags'] ?></a></p>
                                                    <?php endif; ?>
                                                    <?php if ($coub == false):?>
                                                    <a class="img-post" href="<?= $object['news']['link'] ?>" target="_blank"><h2><?= $object['news']['title'] ?></h2></a>
                                                    <?php endif?>
                                                    <?php if (!$coub):?>
                                                    <p class="img-short"><?php echo $object['news']['short'] ?></p>
                                                    <?php endif?>
                                                    <p class="timeago">
                                                        <time class="timeago" datetime="<?= date('c', strtotime($object['news']['created'])) ?>"><?= $object['news']['created'] ?></time> <?php if (!empty($object['host'])):?>с сайта <?php endif ?><?= $object['host'] ?>
                                                        <?php if ($object['news']['original_title'] != ''):?>
                                                            <span style="font-size: 20px;position: relative;top: 2px;margin-left: 2px;margin-right: 2px;">·</span> переведено автоматически
                                                        <?php endif;?>
                                                    </p>
                                                </div>

                                                <div class="box-info" style="margin-top: 0;">
                                                    <?php if ($this->user->getId()):?>
                                                    <a style="padding-left: 1px;padding-right: 10px;" data-news="1" data-id="<?= $object['news']['id'] ?>" class="like-small-icon-box" data-userid="<?= $this->user->getId() ?>" data-vote="<?= $object['allowLike'] ?>" data-likes="<?= $object['news']['liked'] ?>" href="#"><?= $object['allowLike'] ? 'Нравится' : 'Не нравится' ?></a>
                                                        <?php if (($isValidImage($newsImage)) or ($object['news']['link'] == '')):?>
                                                        <span style="font-size: 28px;position: relative;top: 4px;">·</span>
                                                        <?php endif?>
                                                    <?php endif?>
                                                    <?php if (($isValidImage($newsImage)) or ($object['news']['link'] == '')):?>
                                                    <a style="padding-left: <?php if ($this->user->getId()):?>5px<?php else:?>2px<?php endif?>;padding-right: 10px; font-size: 14px;" class="share-news-center" href="#">Поделиться</a>
                                                    <?php endif?>
                                                    <?php
                                                    if (!empty($object['news']['og_title'])) {
                                                        $tweetLike = $object['news']['og_title'];
                                                    } else {
                                                        $tweetLike = $object['news']['title'];
                                                    }
                                                    $image = $newsImage;
                                                    if ($isValidImage($newsImage)):
                                                        $url = 'https://godesigner.ru/news?event=' . $object['id'];
                                                    elseif ((!$isValidImage($newsImage)) and ($object['news']['link'] != '')):
                                                        $url = $object['news']['link'];
                                                        $image = '';
                                                    else:
                                                        $url = 'https://godesigner.ru/news?event=' . $object['id'];
                                                    endif;
                                                    ?>
                                                    <div class="sharebar" style="position: absolute; display: none; top: 30px;<?php if ($this->user->getId()):?> left: 120px; <?php else:?> left : 50px;<?php endif?>">
                                                        <div class="tooltip-block">
                                                            <div class="social-likes" data-counters="no" data-url="<?= $url ?>" data-title="<?= $tweetLike ?>">
                                                                <div class="facebook" style="display: inline-block;" title="Поделиться ссылкой на Фейсбуке" data-url="<?= $url ?>">SHARE</div>
                                                                <div class="twitter" style="display: inline-block;" data-via="Go_Deer">TWITT</div>
                                                                <div class="vkontakte" style="display: inline-block;" title="Поделиться ссылкой во Вконтакте" data-image="<?= $image?>">SHARE</div>
                                                                <?php if ($isValidImage($newsImage)):?>
                                                                    <div class="pinterest" style="display: inline-block;" title="Поделиться картинкой на Пинтересте" data-url="<?= $url ?>" data-media="<?= $image?>">PIN</div>
                                                                <?php endif?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if ($object['news']['original_title'] != ''):?>

                                                    <a style="padding-left: 5px;padding-right: 10px; font-size: 14px;" class="translate" href="#" data-translated="true" data-original-short="<?= $object['news']['original_short']?>" data-original-title="<?= $object['news']['original_title']?>">Показать оригинальный текст</a>
                                                    <?php endif?>
                                                    <?php if (($this->user->isAdmin()) || ($this->user->isFeedWriter())):?>
                                                        <span style="font-size: 28px;position: relative;top: 4px;">·</span>
                                                        <a style="padding-left: 5px; font-size: 14px;" data-id="<?= $object['news_id'] ?>" class="hide-news" href="#">Удалить новость</a>
                                                    <?php endif?>
                                                </div>

                                                <div data-id="<?= $object['news']['id'] ?>" class="likes">
                                                    <?php
                                                    $likes_count = 0;
                                                    $html_likes = '';
                                                    $likes = (int) $object['news']['liked'];
                                                    if ($likes) {
                                                        if (is_array($object['news']['likes'])) {
                                                            foreach ($object['news']['likes'] as $like) {
                                                                ++$likes_count;
                                                                if ($likes > 4) {
                                                                    if ($likes_count == 1) {
                                                                        $html_likes .= '<span class="who-likes">';
                                                                    }
                                                                    if ($likes_count == 4) {
                                                                        $other = $likes - $likes_count;
                                                                        $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a>
                                                                          и <a class="show-other-likes" data-solid="' . $object['news']['id'] . '" href="#">' . $other . ' других</a> <span>лайкнули новость</span></span>';
                                                                        break;
                                                                    } else {
                                                                        $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . ', </a>';
                                                                    }
                                                                } elseif (($likes >= 2) && ($likes <= 4)) {
                                                                    if ($likes_count == 1) {
                                                                        $html_likes .= '<span class="who-likes">';
                                                                    }
                                                                    if ($likes_count != $likes) {
                                                                        $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . ', </a>';
                                                                    }
                                                                    if ($likes_count == $likes) {
                                                                        $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a>';
                                                                        $html_likes .= ' <span>лайкнули новость</span></span>';
                                                                    }
                                                                } elseif ($likes < 2) {
                                                                    $html_likes .= '<span class="who-likes"><a data-id="' . $like['user_id'] . '" target="_blank" href="https://godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a> <span>' . $this->user->getGenderTxt('лайкнул', $like['user']['gender']) . ' новость</span></span>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                    echo $html_likes;
                                                    ?>
                                                </div>
                                            </div>
                                        <?php
                                            endif;
                                        elseif ($object['type'] == 'RatingAdded'): ?>
                                            <div class="box" data-eventid="<?= $object['id'] ?>">
                                                <div class="l-img">
                                                    <img class="avatar" src="<?= $avatar ?>">
                                                </div>
                                                <div class="r-content rating-content">
                                                    <a target="_blank" href="https://godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('оценил', $object['user']['gender']) ?> <?= ($object['solution']['user_id'] == $this->user->getId()) ? 'ваше' : '' ?> решение
                                                    <div class="rating-image star<?= $object['solution']['rating'] ?>"></div>
                                                    <div class="rating-block">
                                                        <img class="img-rate" src="https://godesigner.ru<?= $imageurl ?>">
                                                    </div>
                                                    <p class="timeago rating-time">
                                                        <time class="timeago" datetime="<?= $object['created'] ?>"><?= $object['created'] ?></time>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php
                                        elseif ($object['type'] == 'FavUserAdded'):
                                            $avatarFav = isset($object['user_fav']['images']['avatar_small']) ? 'https://godesigner.ru'.$object['user_fav']['images']['avatar_small']['weburl'] : 'https://godesigner.ru/img/default_small_avatar.png';
                                            ?>
                                            <div class="box" data-eventid="<?= $object['id'] ?>">
                                                <div class="l-img">
                                                    <a href="https://godesigner.ru/users/view/<?= $object['user_id'] ?>"><img class="avatar" src="<?= $avatar ?>"></a>
                                                    <a href="https://godesigner.ru/users/view/<?= $object['fav_user_id'] ?>"><img class="avatar" src="<?= $avatarFav ?>"></a>
                                                </div>
                                                <div class="r-content box-comment">
                                                    <?php if ($this->user->getId() == $object['user_id']):?>
                                                        Вы подписались на <a href="https://godesigner.ru/users/view/<?= $object['fav_user_id'] ?>"><?= $object['creator_fav'] ?></a>
                                                    <?php elseif ($this->user->getId() == $object['fav_user_id']): ?>
                                                        <a href="https://godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> подписан<?php if ($object['user']['gender'] == 2): echo 'а'; endif?> на вас
                                                    <?php else:?>
                                                        <a href="https://godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> подписан<?php if ($object['user']['gender'] == 2): echo 'а'; endif?> на <a href="https://godesigner.ru/users/view/<?= $object['fav_user_id'] ?>"><?= $object['creator_fav'] ?></a>
                                                    <?php endif; ?>
                                                    <p class="timeago">
                                                        <time class="timeago" datetime="<?= date('Y-m-d H:i:s', strtotime($object['created']) - 3 * HOUR) ?>"><?= $object['created'] ?></time>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php
                                        elseif ($object['type'] == 'LikeAdded'):?>
                                            <div class="box" data-eventid="<?= $object['id'] ?>">
                                                <div class="l-img">
                                                    <img class="avatar" src="<?= $avatar ?>">
                                                </div>
                                                <div class="r-content rating-content">
                                                    <a target="_blank" href="https://godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('лайкнул', $object['user']['gender']) ?> <?= ($object['solution']['user_id'] == $this->user->getId()) ? 'ваше' : '' ?> решение
                                                    <div style="background: none;" class="rating-image star<?= $object['solution']['rating'] ?>"></div>
                                                    <div class="rating-block">
                                                        <img class="img-rate" src="https://godesigner.ru<?= $imageurl ?>">
                                                    </div>
                                                    <p class="timeago rating-time">
                                                        <time class="timeago" datetime="<?= $object['created'] ?>"><?= $object['created'] ?></time>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php elseif ($object['type'] == 'RetweetAdded'):
                                            echo $object['html'];
                                        endif;
                                    endforeach;
                                    ?>
                                    <script type="text/javascript">
                                        var eventsDate = '<?= date('Y-m-d H:i:s', strtotime($dateEvent)) ?>';
                                        var likesDate = '<?= date('Y-m-d H:i:s', time() - (2 * HOUR)) ?>';
                                    </script>
                                </div>
                                <div id="officeAjaxLoader" style="text-align: center; display: none; margin-top: 10px;"><img src="https://godesigner.ru/img/blog-ajax-loader.gif"></div>
                            </div>

                        </div>
                        <div class="onTopMiddle">&nbsp;</div>
                    </div><!-- /middle_inner -->
                </div><!-- /middle -->
            </div><!-- .wrapper -->

            <script>
                var designNewsInitialData = <?php echo $jsonDesignNewsInitialData ?>;
            </script>

            <div class="onTop">&nbsp;</div>

            <div id="popup-other-likes" style="display: none;">
                <div class="other-header">Люди, которым это нравится</div>
                <ul id="who-its-liked"></ul>
                <div id="likedAjaxLoader"><img src="https://godesigner.ru/img/blog-ajax-loader.gif"></div>
                <div class="popup-close"></div>
            </div>
<?= $this->html->script([
    'jcarousellite_1.0.1.js',
    'jquery.timers.js',
    'jquery.simplemodal-1.4.2.js',
    'tableloader.js',
    'jquery.timeago.js',
    'fileuploader',
    'jquery.tooltip.js',
    'social-likes.min.js',
    'typeahead.jquery.min.js',
    'bloodhound.min.js',
    'users/news/DesignNewsRow.js',
    'users/news/DesignNews.js',
    'users/feed.js',
    'users/activation.js'], ['inline' => false]) ?>
<?= $this->html->style(['/main2.css', '/pitches2.css', '/view', '/messages12', '/pitches12', '/win_steps2_final3.css', '/blog', '/portfolio.css', 'main.css', '/css/office.css', '/css/social-likes_flat'], ['inline' => false]) ?>
<?= $this->view()->render(['element' => 'popups/activation_popup']) ?>
<?php
/* @todo fox this */
//$this->view()->render(array('element' => 'popups/warning')) ?>
<?= $this->view()->render(['element' => 'moderation']) ?>

