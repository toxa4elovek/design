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
                <div id="news-add">
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
                <div id="news-add-separator"></div>
                <div class="new-content group" style="margin-top:10px">
                <?php endif; ?>
                <?php if ($banner): ?>
                    <div class="banner-block">
                        <div>
                            <div class="close-gender"></div>
                            <span><?= $banner->title ?></span>
                            <p><?= $banner->short ?></p>
                        </div>
                    </div>
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
                        <div class="new-content group" style="margin-top:10px">
                        <?php else: ?>
                            <div class="new-content group">
                            <?php endif; ?>
                            <div id="l-sidebar-office">
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
                                            <a href="/pitches/viewsolution/<?= $solution->solution_id ?>"><div class="left-sol" style="background: url(<?= $image ?>)"></div></a>
                                            <div class="solution-info">
                                                <p class="creator-name"><a target="_blank" href="/users/view/<?= $solution->user_id ?>"><?= $solution->creator ?></a></p>
                                                <p class="ratingcont" data-default="<?= $solution->solution->rating ?>" data-solutionid="<?= $solution->solution->id ?>" style="height: 9px; background: url(/img/<?= $solution->solution->rating ?>-rating.png) no-repeat scroll 0% 0% transparent;display:inline-block;width: 56px;"></p>
                                                <a data-id="<?= $solution->solution->id ?>" class="like-small-icon" href="#"><span><?= $solution->solution->likes ?></span></a>
                                                <div class="sharebar" style="padding:0 0 4px !important;background:url('/img/tooltip-bg-bootom-stripe.png') no-repeat scroll 0 100% transparent !important;position:absolute;z-index:10000;display: none; left: 121px; top: 27px;height: 178px;width:288px;">
                                                    <div class="tooltip-wrap" style="height: 140px; background: url(/img/tooltip-top-bg.png) no-repeat scroll 0 0 transparent !important;padding:39px 10px 0 16px !important">
                                                        <div class="body" style="display: block;">
                                                            <table  width="100%">
                                                                <tr height="35">
                                                                    <td width="137" valign="middle">
                                                                        <a id="facebook<?= $solution->solution->id ?>" class="socialite facebook-like" href="http://www.facebook.com/sharer.php?u=http://www.godesigner.ru/pitches/viewsolution/<?= $solution->solution->id ?>" data-href="http://www.godesigner.ru/pitches/viewsolution/<?= $solution->solution->id ?>" data-send="false" data-layout="button_count">
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
                                                                        <a id="twitter<?= $solution->solution->id ?>" class="socialite twitter-share" href="" data-url="http://www.godesigner.ru/pitches/viewsolution/<?= $solution->solution->id ?>?utm_source=twitter&utm_medium=tweet&utm_content=like-tweet&utm_campaign=sharing" data-text="<?php echo $tweetLike; ?>" data-lang="ru" data-hashtags="Go_Deer">
                                                                            Share on Twitter
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <tr height="35">
                                                                    <td valign="middle">
                                                                        <a href="http://www.tumblr.com/share" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:81px; height:20px; background:url('http://platform.tumblr.com/v1/share_1.png') top left no-repeat transparent;">Share on Tumblr</a>

                                                                    </td>
                                                                    <td valign="middle">
                                                                        <a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fpitches%2Fviewsolution%2F<?= $solution->solution->id ?>&media=<?= urlencode('http://www.godesigner.ru' . $this->solution->renderImageUrl($solution->solution->images['solution_solutionView'])) ?>&description=%D0%9E%D1%82%D0%BB%D0%B8%D1%87%D0%BD%D0%BE%D0%B5%20%D1%80%D0%B5%D1%88%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BD%D0%B0%20%D1%81%D0%B0%D0%B9%D1%82%D0%B5%20GoDesigner.ru" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="bottom_arrow">
                                                    <a href="#" class="solution-menu-toggle"><img src="/img/marker5_2.png" alt=""></a>
                                                </span>
                                                <div class="solution_menu" style="display: none;">
                                                    <ul class="solution_menu_list" style="position:absolute;z-index:6;">
                                                        <?php if ($solution->pitch->user_id == $this->user->getId() && ($solution->pitchesCount < 1) && (!$record->selectedSolutions)): ?>
                                                            <li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">
                                                                <a class="select-winner" href="/solutions/select/<?= $solution->solution->id ?>.json" data-solutionid="<?= $solution->solution->id ?>" data-user="<?= $this->user->getFormattedName($solution->solution->user->first_name, $solution->solution->user->last_name) ?>" data-num="<?= $solution->solution->num ?>" data-userid="<?= $solution->solution->user_id ?>">Назначить победителем</a>
                                                            </li>
                                                        <?php elseif ($solution->pitch->user_id == $this->user->getId() && ($solution->pitch->awarded != $solution->solution->id) && (($solution->pitch->status == 1) || ( $solution->pitch->status == 2)) && ($solution->pitch->awarded != 0)): ?>
                                                            <li class="sol_hov select-winner-li" style="margin:0;width:152px;height:20px;padding:0;">
                                                                <a class="select-multiwinner" href="/pitches/setnewwinner/<?= $solution->solution->id ?>" data-solutionid="<?= $solution->solution->id ?>" data-user="<?= $this->user->getFormattedName($solution->solution->user->first_name, $solution->solution->user->last_name) ?>" data-num="<?= $solution->solution->num ?>" data-userid="<?= $solution->solution->user_id ?>">Назначить <?= $solution->pitchesCount + 2 ?> победителя</a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if ($solution->pitch->user_id == $this->user->getId() && $this->user->isAllowedToComment()): ?>
                                                            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="#" class="solution-link-menu" data-id="<?= $solution->solution->id ?>" data-comment-to="#<?= $solution->solution->num ?>">Комментировать</a></li>
                                                        <?php endif; ?>
                                                        <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a href="/solutions/warn/<?= $solution->solution->id ?>.json" class="warning" data-solution-id="<?= $solution->solution->id ?>">Пожаловаться</a></li>
                                                        <?php if ($this->user->isAdmin()): ?>
                                                            <li class="sol_hov" style="margin:0;width:152px;height:20px;padding:0;"><a class="delete-solution" data-solution="<?= $solution->solution->id ?>" data-solution_num="<?= $solution->solution->num ?>" href="/solutions/delete/<?= $solution->solution->id ?>.json">Удалить</a></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    endif;
                                endforeach;
                                ?>
                                <script type="text/javascript">
                                    var solutionDate = '<?= date('Y-m-d H:i:s', strtotime($solutionDate)) ?>';
                                </script>
                                <div id="SolutionAjaxLoader" style="text-align: center; display: none; margin-top: 10px;"><img src="/img/blog-ajax-loader.gif"></div>
                            </div>
                            <div id="r-sidebar-office">
                                <div id="floatingLayer">
                                    <div id="container-job-designers">
                                        <div class="rs-header"><a href="https://twitter.com/go_deer" target="_blank" style="color: #fff;">Twitter лента</a></div>
                                        <div id="content-job">
                                            <?php echo $this->stream->renderStreamFeed(6); ?>
                                        </div>
                                        <div id="all-tweets"><a href="https://twitter.com/go_deer" target="_blank">Посмотреть все твиты</a></div>
                                    </div>
                                    <div id="container-new-pitches">
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
                                    </div>
                                    <div id="container-design-news">
                                        <div class="rs-header">Новости дизайна и культуры</div>
                                        <div id="content-news">
                                            <?php
                                            $newsDate = '';
                                            foreach ($news as $n): $host = parse_url($n->link);
                                                if (strtotime($newsDate) < strtotime($n->created)) {
                                                    $newsDate = $n->created;
                                                }
                                                ?>
                                                <div class="design-news"><a target="_blank" href="/users/click?link=<?= $n->link ?>&id=<?= $n->id ?>"><?= $n->title ?></a> <br><a class="clicks" href="/users/click?link=<?= $n->link ?>&id=<?= $n->id ?>"><?= $host['host'] ?></a></div>
                                            <?php endforeach; ?>
                                            <script type="text/javascript">
                                                var newsDate = '<?= date('Y-m-d H:i:s', strtotime($newsDate)) ?>';
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="center_sidebar">
                                <div class="center-boxes" id="updates-box-">
                                    <?php if ($middlePost) : ?>
                                        <div class="box" data-eventid="<?= $middlePost->id ?>">
                                            <p class="img-box">
                                                <a class="post-link" href="<?= $middlePost->link ?>"><img class="img-post" src="<?= $middlePost->imageurl ?>"></a>
                                            </p>
                                            <div class="r-content post-content" <?php if (!$middlePost->tags): ?>style="padding-top: 0px;"<?php endif ?>>
                                                <?php if ($middlePost->tags): ?><p class="img-tag"><?= $middlePost->tags ?></p><?php endif; ?>
                                                <a class="img-post" href="<?= $middlePost->link ?>"><h2><?= $middlePost->title ?></h2></a>
                                                <p class="img-short"><?= $middlePost->short ?></p>
                                                <p class="timeago">
                                                    <time class="timeago" datetime="<?= $middlePost->created ?>"><?= $middlePost->created ?></time> с сайта <?= $middlePost->host ?></p>
                                            </div>
                                        </div>
                                        <?php
                                    endif;
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
                                            $avatar = '/img/icon_57.png';
                                        } else {
                                            $avatar = isset($object['user']['images']['avatar_small']) ? $object['user']['images']['avatar_small']['weburl'] : '/img/default_small_avatar.png';
                                        }
                                        if (isset($object['solution']['images']['solution_solutionView'])) {
                                            if (isset($object['solution']['images']['solution_solutionView'][0]['weburl'])) {
                                                $imageurl = $object['solution']['images']['solution_solutionView'][0]['weburl'];
                                            } else {
                                                $imageurl = $object['solution']['images']['solution_solutionView']['weburl'];
                                            }
                                        }
                                        if ($object['type'] == 'CommentAdded' && !is_null($object['comment'])) :
                                            // Если закрытй питч, или коммент не к решению, то надо скрывать картинки
                                            $long = false;
                                            if ((($object['solution']) || ($object['solution_id'] != 0)) && ($object['pitch']['private'] != '1')):
                                                $long = true;
                                            endif;
                                            ?>
                                            <div class="box" data-eventid="<?= $object->id ?>" data-type="<?php echo $object['type'] ?>" data-long="<?php echo $long ?>">
                                                <?php if ($long): ?>
                                                    <div class="l-img l-img-box" style="padding-top: 0">
                                                        <a target="_blank" href="/users/view/<?= $object['user_id'] ?>"><img class="avatar" src="<?= $avatar ?>"></a>
                                                    </div>
                                                    <div class="r-content box-comment">
                                                        <?php if ($this->user->getId() == $object['pitch']['user_id'] || ($object['comment']['public'] && $object['comment']['reply_to'] != 0)): ?>
                                                            <a href="/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('оставил', $object['user']['gender']) ?> комментарий в питче <a href="/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>:
                                                        <?php else: ?>
                                                            <a href="/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('прокомментировал', $object['user']['gender']) ?> <a href="/pitches/viewsolution/<?= $object['solution']['id'] ?>">решение #<?= $object['solution']['num'] ?></a> для питча <a href="/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>:
                                                        <?php endif; ?>
                                                    </div>
                                                    <a href="/pitches/viewsolution/<?= $object['solution']['id'] ?>"><div class="sol"><img src="<?= $imageurl ?>"></div></a>
                                                    <div class="box-info">
                                                        <a href="/solutions/warn/<?= $object['solution']['id'] ?>.json" class="warning-box" data-solution-id="<?= $object['solution']['id'] ?>">Пожаловаться</a>
                                                        <a data-id="<?= $object['solution']['id'] ?>" class="like-small-icon-box" data-userid="<?= $object['solution']['user_id'] ?>" data-vote="<?= $object['allowLike'] ?>" data-likes="<?= $object['solution']['likes'] ?>" href="#"><?= $object['allowLike'] ? 'Нравится' : 'Не нравится' ?></a>
                                                    </div>
                                                    <div class="r-content box-comment">
                                                        &laquo;<?php echo $object['updateText'] ?>&raquo;
                                                    </div>
                                                <?php else: ?>
                                                    <div class="l-img l-img-box" style="padding-top: 0">
                                                        <a target="_blank" href="/users/view/<?= $object['user_id'] ?>"><img class="avatar" src="<?= $avatar ?>"></a>
                                                    </div>
                                                    <?php if ($this->user->getId() == $object['pitch']['user_id'] || ($object['comment']['public'] && $object['comment']['reply_to'] != 0) || !$object['solution']): ?>
                                                        <div class="r-content box-comment">
                                                            <a href="/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('оставил', $object['user']['gender']) ?> комментарий в питче <a href="/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>:<br /> &laquo;<?php echo $object['updateText'] ?>&raquo;
                                                            <p class="timeago">
                                                                <time class="timeago" datetime="<?= $object['created'] ?>"><?= $object['created'] ?></time>
                                                            </p>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="r-content box-comment" style="padding-bottom: 0">
                                                            <a href="/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('прокомментировал', $object['user']['gender']) ?> <a href="/pitches/viewsolution/<?= $object['solution']['id'] ?>">решение #<?= $object['solution']['num'] ?></a> для питча <a href="/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>: &laquo;<?php echo $object['updateText'] ?>&raquo;
                                                            <p class="timeago">
                                                                <time class="timeago" datetime="<?= $object['created'] ?>"><?= $object['created'] ?></time>
                                                            </p>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php elseif ($object['type'] == 'SolutionAdded' && !is_null($object['solution'])) : ?>
                                            <div class="box" data-eventid="<?= $object->id ?>">
                                                <div class="l-img">
                                                    <a target="_blank" href="/users/view/<?= $object['user_id'] ?>"><img class="avatar" src="<?= $avatar ?>"></a>
                                                </div>
                                                <div class="r-content">
                                                    <a href="/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('предложил', $object['user']['gender']) ?> решение для питча <a href="/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>:
                                                </div>
                                                <a href="/pitches/viewsolution/<?= $object['solution']['id'] ?>"><div class="sol"><img src="<?= $imageurl ?>"></div></a>
                                                <div class="box-info">
                                                    <a href="/solutions/warn/<?= $object['solution']['id'] ?>.json" class="warning-box" data-solution-id="<?= $object['solution']['id'] ?>">Пожаловаться</a><span>&middot;</span>
                                                    <a data-id="<?= $object['solution']['id'] ?>" class="like-small-icon-box" data-userid="<?= $object['solution']['user_id'] ?>" data-vote="<?= $object['allowLike'] ?>" data-likes="<?= $object['solution']['likes'] ?>" href="#"><?= $object['allowLike'] ? 'Нравится' : 'Не нравится' ?></a>
                                                </div>
                                                <div id="likes-<?= $object['solution']['id'] ?>" data-id="<?= $object['solution']['id'] ?>" class="likes">
                                                    <?php
                                                    $id = $object['solution']['id'];
                                                    $likes_count = 0;
                                                    $html_likes = '';
                                                    $likes = (int) $object['solution']['likes'];
                                                    foreach ($object['likes'] as $like):
                                                        ++$likes_count;
                                                        $my_solution = ($object['solution']['user_id'] == $this->user->getId()) ? 'ваше' : '';
                                                        if ($likes > 4) {
                                                            if ($likes_count == 1) {
                                                                $html_likes .= '<span class="who-likes"><a class="show-other-likes" data-solid="' . $object['solution']['id'] . '" href="#">';
                                                            }
                                                            if ($likes_count == 4) {
                                                                $other = $likes - $likes_count;
                                                                $html_likes .= $like['creator'] . ' и ' . $other . ' других</a> <span>лайкнули ' . $my_solution . ' решение</span></span>';
                                                                break;
                                                            } else {
                                                                $html_likes .= $like['creator'] . ', ';
                                                            }
                                                        } elseif ($likes < 2) {
                                                            $html_likes .= '<span class="who-likes"><a target="_blank" href="/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a> <span>' . $this->user->getGenderTxt('лайкнул', $like['user']['gender']) . ' ' . $my_solution . ' решение</span></span>';
                                                        } elseif ($likes <= 4) {
                                                            if ($likes_count == 1) {
                                                                $html_likes .= '<span class="who-likes">';
                                                            }
                                                            $html_likes .= '<a target="_blank" href="/users/view/' . $like['user_id'] . '">' . $like['creator'] . '</a>';
                                                            if ($likes_count == $likes) {
                                                                $html_likes .= ' <span>лайкнули ' . $my_solution . ' решение</span></span>';
                                                            }
                                                        }
                                                    endforeach;
                                                    echo $html_likes;
                                                    ?>
                                                </div></div>
                                        <?php elseif ($object['type'] == 'newsAdded'): ?>
                                            <div class="box" data-eventid="<?= $object->id ?>">
                                                <p class="img-box">
                                                    <a class="post-link" href="<?= $object['news']['link'] ?>"><img class="img-post" src="<?= $object['news']['imageurl'] ?>"></a>
                                                </p>
                                                <div class="r-content post-content" <?php if (!$object['news']['tags']): ?>style="padding-top: 0px;"<?php endif; ?>>
                                                    <?php if ($object['news']['tags']): ?>
                                                        <p class="img-tag"><?= $object['news']['tags'] ?></p>
                                                    <?php endif; ?>
                                                    <a class="img-post" href="<?= $object['news']['link'] ?>"><h2><?= $object['news']['title'] ?></h2></a>
                                                    <p class="img-short"><?= $object['news']['short'] ?></p>
                                                    <p class="timeago">
                                                        <time class="timeago" datetime="<?= $object['news']['created'] ?>"><?= $object['news']['created'] ?></time> с сайта <?= $object['host'] ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php elseif ($object['type'] == 'RatingAdded'): ?>
                                            <div class="box" data-eventid="<?= $object->id ?>">
                                                <div class="l-img">
                                                    <img class="avatar" src="<?= $avatar ?>">
                                                </div>
                                                <div class="r-content rating-content">
                                                    <a target="_blank" href="/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?= $this->user->getGenderTxt('оценил', $object['user']['gender']) ?> <?= ($object['solution']['user_id'] == $this->user->getId()) ? 'ваше' : '' ?> решение
                                                    <div class="rating-image star<?= $object['solution']['rating'] ?>"></div>
                                                    <div class="rating-block">
                                                        <img class="img-rate" src="<?= $imageurl ?>">
                                                    </div>
                                                    <p class="timeago rating-time">
                                                        <time class="timeago" datetime="<?= $object['created'] ?>"><?= $object['created'] ?></time>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php
                                        elseif ($object['type'] == 'FavUserAdded'):
                                            $avatarFav = isset($object['user_fav']['images']['avatar_small']) ? $object['user_fav']['images']['avatar_small']['weburl'] : '/img/default_small_avatar.png';
                                            ?>
                                            <div class="box" data-eventid="<?= $object->id ?>">
                                                <div class="l-img">
                                                    <img class="avatar" src="<?= $avatar ?>">
                                                    <img class="avatar" src="<?= $avatarFav ?>">
                                                </div>
                                                <div class="r-content box-comment">
                                                    <a href="/users/view/<?= $object['fav_user_id'] ?>"><?= $object['creator_fav'] ?></a> подписан на вас
                                                    <p class="timeago">
                                                        <time class="timeago" datetime="<?= $object['created'] ?>"><?= $object['created'] ?></time>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php
                                        elseif ($object['type'] == 'RetweetAdded'):
                                            echo $object['html'];
                                        endif;
                                    endforeach;
                                    ?>
                                    <script type="text/javascript">
                                        var eventsDate = '<?= date('Y-m-d H:i:s', strtotime($dateEvent)) ?>';
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
                                <div id="officeAjaxLoader" style="text-align: center; display: none; margin-top: 10px;"><img src="/img/blog-ajax-loader.gif"></div>
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
                <div id="likedAjaxLoader"><img src="/img/blog-ajax-loader.gif"></div>
                <div class="popup-close"></div>
            </div>

            <?= $this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'jquery.tooltip.js', 'socialite.js', 'typeahead.jquery.min.js', 'bloodhound.min.js', 'users/feed.js', 'users/activation.js'), array('inline' => false)) ?>
            <?= $this->html->style(array('/main2.css', '/pitches2.css', '/view', '/messages12', '/pitches12', '/win_steps2_final3.css', '/blog', '/portfolio.css', 'main.css', '/css/office.css'), array('inline' => false)) ?>
            <?= $this->view()->render(array('element' => 'popups/activation_popup')) ?>
            <?= $this->view()->render(array('element' => 'popups/warning')) ?>
<?= $this->view()->render(array('element' => 'moderation')) ?>