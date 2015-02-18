<div class="new-wrapper login">
    <script id="twitter-wjs" type="text/javascript" async defer src="//platform.twitter.com/widgets.js"></script>
    <?= $this->view()->render(array('element' => 'header'), array('header' => 'header2', 'logo' => 'logo')) ?>

    <div class="new-middle">
        <div class="new-middle_inner">
            <input type="hidden" value="<?= $this->user->getId() ?>" id="user_id">
            <script type="text/javascript">
                var offsetDate = Date.parse('<?= date('Y/m/d H:i:s', strtotime($date)) ?>');
                var isCurrentAdmin = <?php echo $this->user->isAdmin() ? 1 : 0 ?>;
                var isCurrentExpert = <?php echo $this->user->isExpert() ? 1 : 0 ?>;
                var isClient = <?php echo ($this->user->isPitchOwner($pitch->user->id)) ? 1 : 0; ?>;
                var isAdmin = <?php echo ($this->user->isAdmin() ? 1 : 0 ); ?>;
                var isAllowedToComment = <?php echo ($this->user->isAllowedToComment() ? 1 : 0 ); ?>;
                var userName = '<?php echo ($this->user->getId()) ? $this->user->getFormattedName($this->user->firstname, $this->user->lastname) : ''; ?>';
                var userGender = <?php echo $this->user->getGender(); ?>;
            </script>
            <?php if ($this->user->isAdmin()): ?>
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
                if (($banner) && ($this->user->getId()) && (!$_COOKIE['closedbanner' . $banner->id])): ?>
                    <div class="banner-block">
                        <div>
                            <div data-bannerid="<?= $banner->id ?>" class="close-gender"></div>
                            <span><?= $banner->title ?></span>
                            <p><?= $this->brief->ee($banner->short) ?></p>
                        </div>
                    </div>
                    <nav class="main_nav clear" style="width:832px;margin:30px auto 25px;">
                        <?= $this->view()->render(array('element' => 'office/nav')); ?>
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
                        <?php if($this->user->getId()): ?>
                        <nav class="main_nav clear" style="width:832px;margin:66px auto -50px;">
                            <?= $this->view()->render(array('element' => 'office/nav')); ?>
                        </nav>
                        <?php endif; ?>
                        <div class="new-content group" style="margin-top:10px">
                        <?php else: ?>
                            <?php if($this->user->getId()): ?>
                            <nav class="main_nav clear" style="width:832px;margin:56px auto -50px;">
                                <?= $this->view()->render(array('element' => 'office/nav')); ?>
                            </nav>
                            <?php endif; ?>
                            <div class="new-content group">
                            <?php endif; ?>
                            <div id="l-sidebar-office" data-count="<?= count($solutions->data());?>">
                                <?php
                                $solutionDate = '';
                                $count = 0;
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
                                            <a href="http://www.godesigner.ru/pitches/viewsolution/<?= $solution->solution_id ?>"><div class="left-sol" style="background: url(http://www.godesigner.ru<?= $image ?>)"></div></a>
                                            <div class="solution-info">
                                                <p class="creator-name"><a target="_blank" href="http://www.godesigner.ru/users/view/<?= $solution->user_id ?>"><?= $solution->creator ?></a></p>
                                                <p class="ratingcont" data-default="<?= $solution->solution->rating ?>" data-solutionid="<?= $solution->solution->id ?>" style="height: 9px; background: url(http://www.godesigner.ru/img/<?= $solution->solution->rating ?>-rating.png) no-repeat scroll 0% 0% transparent;display:inline-block;width: 56px;"></p>
                                                <a data-id="<?= $solution->solution->id ?>" class="like-small-icon" href="#"><span><?= $solution->solution->likes ?></span></a>
                                                <?php if((($solution->pitch->private != 1) && ($solution->pitch->category_id != 7))):
                                                    if (rand(1, 100) <= 50) {
                                                        $tweetLike = 'Мне нравится этот дизайн! А вам?';
                                                    } else {
                                                        $tweetLike = 'Из всех ' . $solution->pitch->ideas_count . ' мне нравится этот дизайн';
                                                    }
                                                    if(!isset($solution->solution->images['solution_galleryLargeSize'][0])):
                                                        $url = 'http://www.godesigner.ru' . $solution->solution->images['solution_gallerySiteSize']['weburl'];
                                                    else:
                                                        $url = 'http://www.godesigner.ru' . $solution->solution->images['solution_gallerySiteSize'][0]['weburl'];
                                                    endif;
                                                    ?>
                                                    <div class="sharebar" style="position: absolute; display: none; top: 40px; left: 165px;">
                                                        <div class="tooltip-block">
                                                            <div class="social-likes" data-counters="no" data-url="http://www.godesigner.ru/pitches/viewsolution/<?=$solution->solution->id?>" data-title="<?= $tweetLike ?>">
                                                                <div class="facebook" title="Поделиться ссылкой на Фейсбуке">SHARE</div>
                                                                <div class="twitter" data-via="Go_Deer">TWITT</div>
                                                                <div class="vkontakte" title="Поделиться ссылкой во Вконтакте" data-image="<?= 'http://www.godesigner.ru'. $this->solution->renderImageUrl($solution->solution->images['solution_solutionView'])?>">SHARE</div>
                                                                <div class="pinterest" title="Поделиться картинкой на Пинтересте" data-media="<?= 'http://www.godesigner.ru'. $this->solution->renderImageUrl($solution->solution->images['solution_solutionView'])?>">PIN</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif;?>
                                                <span class="bottom_arrow">
                                                    <a href="#" class="solution-menu-toggle"><img src="http://www.godesigner.ru/img/marker5_2.png" alt=""></a>
                                                </span>
                                                <div class="solution_menu" style="display: none;">
                                                    <ul class="solution_menu_list" style="position:absolute;z-index:6;">
                                                        <?php if ($solution->pitch->user_id == $this->user->getId() && ($solution->pitchesCount < 1) && (!$record->selectedSolutions)): ?>
                                                            <li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">
                                                                <a class="select-winner" href="http://www.godesigner.ru/solutions/select/<?= $solution->solution->id ?>.json" data-solutionid="<?= $solution->solution->id ?>" data-user="<?= $this->user->getFormattedName($solution->solution->user->first_name, $solution->solution->user->last_name) ?>" data-num="<?= $solution->solution->num ?>" data-userid="<?= $solution->solution->user_id ?>">Назначить победителем</a>
                                                            </li>
                                                        <?php elseif ($solution->pitch->user_id == $this->user->getId() && ($solution->pitch->awarded != $solution->solution->id) && (($solution->pitch->status == 1) || ( $solution->pitch->status == 2)) && ($solution->pitch->awarded != 0)): ?>
                                                            <li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">
                                                                <a class="select-multiwinner" href="http://www.godesigner.ru/pitches/setnewwinner/<?= $solution->solution->id ?>" data-solutionid="<?= $solution->solution->id ?>" data-user="<?= $this->user->getFormattedName($solution->solution->user->first_name, $solution->solution->user->last_name) ?>" data-num="<?= $solution->solution->num ?>" data-userid="<?= $solution->solution->user_id ?>">Назначить <?= $solution->pitchesCount + 2 ?> победителя</a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if ($solution->pitch->user_id == $this->user->getId() && $this->user->isAllowedToComment()): ?>
                                                            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="#" class="solution-link-menu" data-id="<?= $solution->solution->id ?>" data-comment-to="#<?= $solution->solution->num ?>">Комментировать</a></li>
                                                        <?php endif; ?>
                                                        <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="http://www.godesigner.ru/solutions/warn/<?= $solution->solution->id ?>.json" class="warning" data-solution-id="<?= $solution->solution->id ?>">Пожаловаться</a></li>
                                                        <?php if ($this->user->isAdmin()): ?>
                                                            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a class="delete-solution" data-solution="<?= $solution->solution->id ?>" data-solution_num="<?= $solution->solution->num ?>" href="http://www.godesigner.ru/solutions/delete/<?= $solution->solution->id ?>.json">Удалить</a></li>
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
                                ?>
                                <script type="text/javascript">
                                    var solutionDate = '<?= date('Y-m-d H:i:s', strtotime($solutionDate)) ?>';
                                </script>
                                <div id="SolutionAjaxLoader" style="text-align: center; display: none; margin-top: 10px;"><img src="http://www.godesigner.ru/img/blog-ajax-loader.gif"></div>
                            </div>
                            <div id="r-sidebar-office">
                                <div id="floatingLayer">
                                    <div id="container-job-designers">
                                        <div class="rs-header"><a href="https://twitter.com/go_deer" target="_blank" style="color: #fff;">Twitter #работадлядизайнеров</a></div>
                                        <div id="content-job">
                                            <?php echo $this->stream->renderStreamFeed(6); ?>
                                        </div>
                                        <div id="all-tweets"><a href="https://twitter.com/go_deer" target="_blank">Посмотреть все твиты</a></div>
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
                                                                <div class="new-price"><?= $this->moneyFormatter->formatMoney($pitch->price, array('suffix' => 'р.')) ?></div>
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
                                            if($news) :
                                            foreach ($news as $n): $host = parse_url($n->link);
                                                if (strtotime($newsDate) < strtotime($n->created)) {
                                                    $newsDate = $n->created;
                                                }
                                                ?>
                                                <div class="design-news"><a target="_blank" href="http://www.godesigner.ru/users/click?link=<?= $n->link ?>&id=<?= $n->id ?>"><?= $n->title ?></a> <br><a class="clicks" href="http://www.godesigner.ru/users/click?link=<?= $n->link ?>&id=<?= $n->id ?>"><?= $host['host'] ?></a></div>
                                            <?php endforeach;endif; ?>
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
                                    $isValidImage = function($url){
                                    if(empty($url)):
                                    return false;
                                    endif;
                                    return true;
                                    }
                                    ?>
                                    <div class="box" data-newsid="<?= $object['news']['id'] ?>" <?php if(!$isValidImage($object['news']['imageurl'])): echo 'style="margin-top: 34px;"'; endif;?> data-eventid="<?= $object['id'] ?>">
                                        <?php if($isValidImage($object['news']['imageurl'])):?>
                                            <p class="img-box">
                                                <a class="post-link" href="<?= $object['news']['link'] ?>" target="_blank"><img onerror="imageLoadError(this);" class="img-post" src="<?= (strpos($object['news']['imageurl'],'/events/') !== false) ? 'http://www.godesigner.ru'.$object['news']['imageurl'] : $object['news']['imageurl']?>"></a>
                                            </p>
                                        <?php endif?>
                                        <div class="r-content post-content" <?php if (!$object['news']['tags']): ?>style="padding-top: 0px;"<?php endif; ?>>
                                            <?php if ($object['news']['tags']): ?>
                                                <p class="img-tag"><?= $object['news']['tags'] ?></p>
                                            <?php endif; ?>
                                            <a class="img-post" href="<?= $object['news']['link'] ?>" target="_blank"><h2><?= $object['news']['title'] ?></h2></a>
                                            <p class="img-short"><?php echo $object['news']['short'] ?></p>
                                            <p class="timeago">
                                                <time class="timeago" datetime="<?= $object['news']['created'] ?>"><?= $object['news']['created'] ?></time> с сайта <?= $object['host'] ?>
                                            </p>
                                        </div>
                                            <div class="box-info" style="margin-top: 0;">
                                                <?php if($this->user->getId()):?>
                                                <a style="padding-left: 0;padding-right: 10px;" data-news="1" data-id="<?= $object['news']['id'] ?>" class="like-small-icon-box" data-userid="<?= $this->user->getId() ?>" data-vote="<?= $object['allowLike'] ?>" data-likes="<?= $object['news']['liked'] ?>" href="#"><?= $object['allowLike'] ? 'Нравится' : 'Не нравится' ?></a>
                                                <span>·</span>
                                                <?php endif?>
                                                <a style="padding-left: 5px;padding-right: 10px; font-size: 14px;" class="share-news-center" href="#">Поделиться</a>
                                                <?php
                                                $tweetLike = $object['news']['title'];
                                                $url = 'http://www.godesigner.ru/news?event=' . $object['id'];
                                                ?>
                                                <div class="sharebar" style="position: absolute; display: none; top: 30px; left: 120px;">
                                                    <div class="tooltip-block">
                                                        <div class="social-likes" data-counters="no" data-url="http://www.godesigner.ru/news?event=<?= $object['id']?>" data-title="<?= $tweetLike ?>">
                                                            <div class="facebook" style="display: inline-block;" title="Поделиться ссылкой на Фейсбуке" data-url="http://www.godesigner.ru/news?event=<?= $object['id']?>">SHARE</div>
                                                            <div class="twitter" style="display: inline-block;" data-via="Go_Deer">TWITT</div>
                                                            <div class="vkontakte" style="display: inline-block;" title="Поделиться ссылкой во Вконтакте" data-url="http://www.godesigner.ru/news?event=<?= $object['id']?>" data-image="<?= $object['news']['imageurl']?>">SHARE</div>
                                                            <?php if($isValidImage($object['news']['imageurl'])):?>
                                                            <div class="pinterest" style="display: inline-block;" title="Поделиться картинкой на Пинтересте" data-url="http://www.godesigner.ru/news?event=<?= $object['id']?>" data-media="<?= $object['news']['imageurl']?>">PIN</div>
                                                            <?php endif?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if($this->user->isAdmin()):?>
                                                    <span>·</span>
                                                    <a style="padding-left: 5px; font-size: 14px;" data-id="<?= $object['news']['id'] ?>" class="hide-news" href="#">Удалить новость</a>
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
                                                            $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a>
                                                                      и <a class="show-other-likes" data-solid="' . $object['news']['id'] . '" href="#">' . $other . ' других</a> <span>лайкнули новость</span></span>';
                                                            break;
                                                        } else {
                                                            $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . ', </a>';
                                                        }
                                                    } elseif (($likes >= 2) && ($likes <= 4)) {
                                                        if ($likes_count == 1) {
                                                            $html_likes .= '<span class="who-likes">';
                                                        }
                                                        if ($likes_count != $likes) {
                                                            $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . ', </a>';
                                                        }
                                                        if ($likes_count == $likes) {
                                                            $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a>';
                                                            $html_likes .= ' <span>лайкнули новость</span></span>';
                                                        }
                                                    } elseif ($likes < 2) {
                                                        $html_likes .= '<span class="who-likes"><a data-id="' . $like['user_id'] . '" target="_blank" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a> <span>' . $this->user->getGenderTxt('лайкнул', $like['user']['gender']) . ' новость</span></span>';
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
                                        if ($object['user']['isAdmin']) {
                                            $avatar = 'http://www.godesigner.ru/img/icon_57.png';
                                        } else {
                                            $avatar = isset($object['user']['images']['avatar_small']) ? $object['user']['images']['avatar_small']['weburl'] : 'http://www.godesigner.ru/img/default_small_avatar.png';
                                        }
                                        if($object['type'] != 'LikeAdded') {
                                            if (isset($object['solution']['images']['solution_solutionView'])) {
                                                if (isset($object['solution']['images']['solution_solutionView'][0]['weburl'])) {
                                                    $imageurl = $object['solution']['images']['solution_solutionView'][0]['weburl'];
                                                } else {
                                                    $imageurl = $object['solution']['images']['solution_solutionView']['weburl'];
                                                }
                                            }
                                        }else{
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
                                            if(
                                                (preg_match("/Дизайнеры больше не могут/", $object['updateText']))
                                                ||
                                                (preg_match("/питч завершен и ожидает/", $object['updateText']))
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
                                                        <a target="_blank" href="http://www.godesigner.ru/users/view/<?= $object['user_id'] ?>"><img class="avatar" src="<?= $avatar ?>"></a>
                                                    </div>
                                                    <div class="r-content box-comment">
                                                        <?php if ($this->user->getId() == $object['pitch']['user_id'] || ($object['comment']['public'] && $object['comment']['reply_to'] != 0)): ?>
                                                            <a href="http://www.godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('оставил', $object['user']['gender']) ?> комментарий в питче <a href="http://www.godesigner.ru/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>:
                                                        <?php else: ?>
                                                            <a href="http://www.godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('прокомментировал', $object['user']['gender']) ?> <a href="http://www.godesigner.ru/pitches/viewsolution/<?= $object['solution']['id'] ?>">решение #<?= $object['solution']['num'] ?></a> для питча <a href="http://www.godesigner.ru/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>:
                                                        <?php endif; ?>
                                                    </div>
                                                    <a href="http://www.godesigner.ru/pitches/viewsolution/<?= $object['solution']['id'] ?>"><div class="sol"><img src="http://www.godesigner.ru<?= $imageurl ?>"></div></a>
                                                    <?php if($this->user->getId()):?>
                                                    <div class="box-info">
                                                        <a href="http://www.godesigner.ru/solutions/warn/<?= $object['solution']['id'] ?>.json" class="warning-box" data-solution-id="<?= $object['solution']['id'] ?>">Пожаловаться</a>
                                                        <a data-id="<?= $object['solution']['id'] ?>" class="like-small-icon-box" data-userid="<?= $object['solution']['user_id'] ?>" data-vote="<?= $object['allowLike'] ?>" data-likes="<?= $object['solution']['likes'] ?>" href="#"><?= $object['allowLike'] ? 'Нравится' : 'Не нравится' ?></a>
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="r-content box-comment">
                                                        &laquo;<?php echo $object['updateText'] ?>&raquo;
                                                    </div>
                                                <?php else: ?>
                                                    <div class="l-img l-img-box" style="padding-top: 0">
                                                        <a target="_blank" href="http://www.godesigner.ru/users/view/<?= $object['user_id'] ?>"><img class="avatar" src="<?= $avatar ?>"></a>
                                                    </div>
                                                    <?php if ($this->user->getId() == $object['pitch']['user_id'] || ($object['comment']['public'] && $object['comment']['reply_to'] != 0) || !$object['solution']): ?>
                                                        <div class="r-content box-comment">
                                                            <a href="http://www.godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('оставил', $object['user']['gender']) ?> комментарий в питче <a href="http://www.godesigner.ru/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>:<br /> &laquo;<?php echo $object['updateText'] ?>&raquo;
                                                            <p class="timeago">
                                                                <time class="timeago" datetime="<?= $object['created'] ?>"><?= $object['created'] ?></time>
                                                            </p>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="r-content box-comment" style="padding-bottom: 0">
                                                            <a href="http://www.godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('прокомментировал', $object['user']['gender']) ?> <a href="http://www.godesigner.ru/pitches/viewsolution/<?= $object['solution']['id'] ?>">решение #<?= $object['solution']['num'] ?></a> для питча <a href="http://www.godesigner.ru/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>: &laquo;<?php echo $object['updateText'] ?>&raquo;
                                                            <p class="timeago">
                                                                <time class="timeago" datetime="<?= $object['created'] ?>"><?= $object['created'] ?></time>
                                                            </p>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php elseif ($object['type'] == 'SolutionAdded' && !is_null($object['solution'])) :
                                            if($object['pitch']['private'] == 1):
                                                continue;
                                            endif;
                                            ?>
                                            <div class="box" data-eventid="<?= $object['id'] ?>">
                                                <div class="l-img">
                                                    <a target="_blank" href="http://www.godesigner.ru/users/view/<?= $object['user_id'] ?>"><img class="avatar" src="<?= $avatar ?>"></a>
                                                </div>
                                                <div class="r-content">
                                                    <a href="http://www.godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('предложил', $object['user']['gender']) ?> решение для питча <a href="http://www.godesigner.ru/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>:
                                                </div>
                                                <a href="http://www.godesigner.ru/pitches/viewsolution/<?= $object['solution']['id'] ?>"><div class="sol"><img src="http://www.godesigner.ru<?= $imageurl ?>"></div></a>
                                                <?php if($this->user->getId()): ?>
                                                <div class="box-info">
                                                    <a href="http://www.godesigner.ru/solutions/warn/<?= $object['solution']['id'] ?>.json" class="warning-box" data-solution-id="<?= $object['solution']['id'] ?>">Пожаловаться</a><span>&middot;</span>
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
                                                                    $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a>
                                                                      и <a class="show-other-likes" data-solid="' . $object['solution']['id'] . '" href="#">' . $other . ' других</a> <span>лайкнули решение</span></span>';
                                                                    break;
                                                                } else {
                                                                    $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . ', </a>';
                                                                }
                                                            } elseif (($likes >= 2) && ($likes <= 4)) {
                                                                if ($likes_count == 1) {
                                                                    $html_likes .= '<span class="who-likes">';
                                                                }
                                                                if ($likes_count != $likes) {
                                                                    $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . ', </a>';
                                                                }
                                                                if ($likes_count == $likes) {
                                                                    $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a>';
                                                                    $html_likes .= ' <span>лайкнули решение</span></span>';
                                                                }
                                                            } elseif ($likes < 2) {
                                                                $html_likes .= '<span class="who-likes"><a data-id="' . $like['user_id'] . '" target="_blank" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a> <span>' . $this->user->getGenderTxt('лайкнул', $like['user']['gender']) . ' решение</span></span>';
                                                            }
                                                        }
                                                    }
                                                    echo $html_likes;
                                                    ?>
                                                </div></div>
                                        <?php elseif ($object['type'] == 'newsAdded'):
                                            $isValidImage = function($url){
                                                if(empty($url)):
                                                    return false;
                                                endif;
                                                return true;
                                            }
                                            ?>
                                            <div class="box" data-newsid="<?= $object['news']['id'] ?>" <?php if(!$isValidImage($object['news']['imageurl'])): echo 'style="margin-top: 34px;"'; endif;?> data-eventid="<?= $object['id'] ?>">
                                                <?php if($isValidImage($object['news']['imageurl'])):?>
                                                <p class="img-box">
                                                    <a class="post-link" href="<?= $object['news']['link'] ?>" target="_blank"><img onerror="imageLoadError(this);" class="img-post" src="<?= (strpos($object['news']['imageurl'],'/events/') !== false) ? 'http://www.godesigner.ru'.$object['news']['imageurl'] : $object['news']['imageurl']?>"></a>
                                                </p>
                                                <?php endif?>
                                                <div class="r-content post-content" <?php if (!$object['news']['tags']): ?>style="padding-top: 0px;"<?php endif; ?>>
                                                    <?php if ($object['news']['tags']): ?>
                                                        <p class="img-tag"><?= $object['news']['tags'] ?></p>
                                                    <?php endif; ?>
                                                    <a class="img-post" href="<?= $object['news']['link'] ?>" target="_blank"><h2><?= $object['news']['title'] ?></h2></a>
                                                    <p class="img-short"><?php echo $object['news']['short'] ?></p>
                                                    <p class="timeago">
                                                        <time class="timeago" datetime="<?= $object['news']['created'] ?>"><?= $object['news']['created'] ?></time> с сайта <?= $object['host'] ?>
                                                    </p>
                                                </div>

                                                <div class="box-info" style="margin-top: 0;">
                                                    <?php if($this->user->getId()):?>
                                                    <a style="padding-left: 0;padding-right: 10px;" data-news="1" data-id="<?= $object['news']['id'] ?>" class="like-small-icon-box" data-userid="<?= $this->user->getId() ?>" data-vote="<?= $object['allowLike'] ?>" data-likes="<?= $object['news']['liked'] ?>" href="#"><?= $object['allowLike'] ? 'Нравится' : 'Не нравится' ?></a>
                                                    <span>·</span>
                                                    <?php endif?>
                                                    <a style="padding-left: 5px;padding-right: 10px; font-size: 14px;" class="share-news-center" href="#">Поделиться</a>
                                                    <?php
                                                    $tweetLike = $object['news']['title'];
                                                    $url = 'http://www.godesigner.ru/news?event=' . $object['id'];
                                                    ?>
                                                    <div class="sharebar" style="position: absolute; display: none; top: 30px;<?php if($this->user->getId()):?> left: 120px; <?php else:?> left : 50px;<?php endif?>">
                                                        <div class="tooltip-block">
                                                            <div class="social-likes" data-counters="no" data-url="http://www.godesigner.ru/news?event=<?= $object['id']?>" data-title="<?= $tweetLike ?>">
                                                                <div class="facebook" style="display: inline-block;" title="Поделиться ссылкой на Фейсбуке" data-url="http://www.godesigner.ru/news?event=<?= $object['id']?>">SHARE</div>
                                                                <div class="twitter" style="display: inline-block;" data-via="Go_Deer">TWITT</div>
                                                                <div class="vkontakte" style="display: inline-block;" title="Поделиться ссылкой во Вконтакте" data-image="<?= $object['news']['imageurl']?>">SHARE</div>
                                                                <?php if($isValidImage($object['news']['imageurl'])):?>
                                                                    <div class="pinterest" style="display: inline-block;" title="Поделиться картинкой на Пинтересте" data-url="http://www.godesigner.ru/news?event=<?= $object['id']?>" data-media="<?= $object['news']['imageurl']?>">PIN</div>
                                                                <?php endif?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if($object['news']['original_title'] != ''):?>
                                                    <span>·</span>
                                                    <a style="padding-left: 5px;padding-right: 10px; font-size: 14px;" class="translate" href="#" data-translated="true" data-original-short="<?= $object['news']['original_short']?>" data-original-title="<?= $object['news']['original_title']?>">Показать оригинал</a>
                                                    <?php endif?>
                                                    <?php if($this->user->isAdmin()):?>
                                                        <span>·</span>
                                                        <a style="padding-left: 5px; font-size: 14px;" data-id="<?= $object['news']['id'] ?>" class="hide-news" href="#">Удалить новость</a>
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
                                                                    $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a>
                                                                      и <a class="show-other-likes" data-solid="' . $object['news']['id'] . '" href="#">' . $other . ' других</a> <span>лайкнули новость</span></span>';
                                                                    break;
                                                                } else {
                                                                    $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . ', </a>';
                                                                }
                                                            } elseif (($likes >= 2) && ($likes <= 4)) {
                                                                if ($likes_count == 1) {
                                                                    $html_likes .= '<span class="who-likes">';
                                                                }
                                                                if ($likes_count != $likes) {
                                                                    $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . ', </a>';
                                                                }
                                                                if ($likes_count == $likes) {
                                                                    $html_likes .= '<a target="_blank" data-id="' . $like['user_id'] . '" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a>';
                                                                    $html_likes .= ' <span>лайкнули новость</span></span>';
                                                                }
                                                            } elseif ($likes < 2) {
                                                                $html_likes .= '<span class="who-likes"><a data-id="' . $like['user_id'] . '" target="_blank" href="http://www.godesigner.ru/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a> <span>' . $this->user->getGenderTxt('лайкнул', $like['user']['gender']) . ' новость</span></span>';
                                                            }
                                                        }
                                                    }
                                                    echo $html_likes;
                                                    ?>
                                                </div>
                                            </div>
                                        <?php elseif ($object['type'] == 'RatingAdded'): ?>
                                            <div class="box" data-eventid="<?= $object['id'] ?>">
                                                <div class="l-img">
                                                    <img class="avatar" src="<?= $avatar ?>">
                                                </div>
                                                <div class="r-content rating-content">
                                                    <a target="_blank" href="http://www.godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('оценил', $object['user']['gender']) ?> <?= ($object['solution']['user_id'] == $this->user->getId()) ? 'ваше' : '' ?> решение
                                                    <div class="rating-image star<?= $object['solution']['rating'] ?>"></div>
                                                    <div class="rating-block">
                                                        <img class="img-rate" src="http://www.godesigner.ru<?= $imageurl ?>">
                                                    </div>
                                                    <p class="timeago rating-time">
                                                        <time class="timeago" datetime="<?= $object['created'] ?>"><?= $object['created'] ?></time>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php
                                        elseif ($object['type'] == 'FavUserAdded'):
                                            $avatarFav = isset($object['user_fav']['images']['avatar_small']) ? 'http://www.godesigner.ru'.$object['user_fav']['images']['avatar_small']['weburl'] : 'http://www.godesigner.ru/img/default_small_avatar.png';
                                            ?>
                                            <div class="box" data-eventid="<?= $object['id'] ?>">
                                                <div class="l-img">
                                                    <a href="http://www.godesigner.ru/users/view/<?= $object['user_id'] ?>"><img class="avatar" src="<?= $avatar ?>"></a>
                                                    <a href="http://www.godesigner.ru/users/view/<?= $object['fav_user_id'] ?>"><img class="avatar" src="<?= $avatarFav ?>"></a>
                                                </div>
                                                <div class="r-content box-comment">
                                                    <?php if($this->user->getId() == $object['user_id']):?>
                                                        Вы подписались на <a href="http://www.godesigner.ru/users/view/<?= $object['fav_user_id'] ?>"><?= $object['creator_fav'] ?></a>
                                                    <?php elseif($this->user->getId() == $object['fav_user_id']): ?>
                                                        <a href="http://www.godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> подписан<?php if($object['user']['gender'] == 2): echo 'а'; endif?> на вас
                                                    <?php else:?>
                                                        <a href="http://www.godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> подписан<?php if($object['user']['gender'] == 2): echo 'а'; endif?> на <a href="http://www.godesigner.ru/users/view/<?= $object['fav_user_id'] ?>"><?= $object['creator_fav'] ?></a>
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
                                                    <a target="_blank" href="http://www.godesigner.ru/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('лайкнул', $object['user']['gender']) ?> <?= ($object['solution']['user_id'] == $this->user->getId()) ? 'ваше' : '' ?> решение
                                                    <div style="background: none;" class="rating-image star<?= $object['solution']['rating'] ?>"></div>
                                                    <div class="rating-block">
                                                        <img class="img-rate" src="http://www.godesigner.ru<?= $imageurl ?>">
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

                                    <!--                        <div class="box">
                                                                <div class="l-img">
                                                                    <img class="avatar" src="/img/default_small_avatar.png">
                                                                    <img class="avatar" src="/img/default_small_avatar.png">
                                                                </div>
                                                                <div class="r-content">
                                                                    <a href="">Dima D.</a> и <a href="">Fedya T.</a> подписаны на вас
                                                                    <span class="back-time">2 часа назад</span>
                                                                </div>
                                                            </div>
                                                            <div class="box">
                                                                <div class="l-img">
                                                                    <img class="avatar" src="/img/default_small_avatar.png">
                                                                </div>
                                                                <div class="r-content">
                                                                    <a href="">Oxana D.</a> оценила ваше решение
                                                                    <img class="rat" src="/img/rating.png">
                                                                    <span class="back-time">2 часа назад</span>
                                                                </div>
                                                                <div>
                                                                    <img class="img-rate" src="http://www.godesigner.ru/solutions/d628d5c19862c2d32a2415934f64c905_galleryLargeSize.png">
                                                                </div>
                                                            </div>
                                                            <div class="box">
                                                                <div class="l-img">
                                                                    <img class="avatar" src="/img/default_small_avatar.png">
                                                                </div>
                                                                <div class="r-content">
                                                                    <a href="">Dima P.</a> предложил решение для питча <a href="">Логотип Capanna Design</a>:
                                                                </div>
                                                                <img class="sol" src="http://www.godesigner.ru/solutions/89ea1168f9a0f4150a33782fd1be3cbf_solutionView.png">
                                                                <div class="likes">
                                                                    <div>
                                                                        <div class="l-img">
                                                                            <img class="avatar" src="/img/default_small_avatar.png">
                                                                        </div>
                                                                        <span><a href="">Maxim F.</a> лайкнул ваше решение</span>
                                                                    </div>
                                                                    <div>
                                                                        <div class="l-img">
                                                                            <img class="avatar" src="/img/default_small_avatar.png">
                                                                        </div>
                                                                        <span><a href="">Oxana D.</a> лайкнула ваше решение</span>
                                                                    </div>
                                                                    <div>
                                                                        <div class="l-img">
                                                                            <img class="avatar" src="/img/default_small_avatar.png">
                                                                        </div>
                                                                        <span><a href="">Oxana D.</a> лайкнула ваше решение</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="box"></div>
                                                            <div class="box">
                                                                <img src="http://tutdesign.ru/wp-content/uploads/2014/10/16.jpg">
                                                                <div class="r-content">
                                                                    <h2>Розовый как признак неповиновения: новый бренд «Русская принцесса»</h2>
                                                                    <time class="timeago" datetime="2013-05-15T18:20:00">20 мая 2013 18:20:20</time>
                                                                </div>
                                                            </div>-->
                                </div>
                                <div id="officeAjaxLoader" style="text-align: center; display: none; margin-top: 10px;"><img src="http://www.godesigner.ru/img/blog-ajax-loader.gif"></div>
                            </div>

                        </div>
                        <div class="onTopMiddle">&nbsp;</div>
                    </div><!-- /middle_inner -->
                </div><!-- /middle -->
            </div><!-- .wrapper -->
            <div class="onTop">&nbsp;</div>

            <div id="popup-other-likes" style="display: none;">
                <div class="other-header">Люди, которым это нравится</div>
                <ul id="who-its-liked">
                    <!--            <li>
                                    <img src="/img/default_small_avatar.png" class="avatar">
                                    <a class="user-title" href="/users/view/202">Test T.</a>
                                    <a id="fav-user" class="order-button" href="#">Подписаться</a>
                                </li>-->
                </ul>
                <div id="likedAjaxLoader"><img src="http://www.godesigner.ru/img/blog-ajax-loader.gif"></div>
                <div class="popup-close"></div>
            </div>

            <?= $this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'jquery.tooltip.js', 'social-likes.min.js', 'typeahead.jquery.min.js', 'bloodhound.min.js', 'users/feed.js', 'users/activation.js'), array('inline' => false)) ?>
            <?= $this->html->style(array('/main2.css', '/pitches2.css', '/view', '/messages12', '/pitches12', '/win_steps2_final3.css', '/blog', '/portfolio.css', 'main.css', '/css/office.css', '/css/social-likes_flat'), array('inline' => false)) ?>
            <?= $this->view()->render(array('element' => 'popups/activation_popup')) ?>
            <?= $this->view()->render(array('element' => 'popups/warning')) ?>
            <?= $this->view()->render(array('element' => 'moderation')) ?>