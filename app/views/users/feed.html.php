<div class="new-wrapper login">

    <?= $this->view()->render(array('element' => 'header'), array('header' => 'header2', 'logo' => 'logo')) ?>

    <div class="new-middle">
        <div class="new-middle_inner" style="margin-top: 0px;">
            <input type="hidden" value="<?= $this->user->getId() ?>" id="user_id">
            <script type="text/javascript">
                var offsetDate = Date.parse('<?= date('Y/m/d H:i:s', strtotime($date)) ?>');
            </script> 
            <div class="new-content group">
                <div id="l-sidebar-office">
                    <?php
                    $solutionDate = '';
                    foreach ($solutions as $solution):
                        if (isset($solution->solution->images['solution_tutdesign'])) {
                            if (isset($solution->solution->images['solution_tutdesign'][0]['weburl'])) {
                                $image = $solution->solution->images['solution_tutdesign'][0]['weburl'];
                            } else {
                                $image = $solution->solution->images['solution_tutdesign']['weburl'];
                            }
                        }
                        if ($count == 0) {
                            $solutionDate = $solution->created;
                        }
                        $count++;
                        ?>
                        <div class="solutions-block">
                            <a href="/pitches/viewsolution/<?= $solution->solution_id ?>"><img width="260" src="<?= $image ?>"></a>
                            <div>
                                <p class="creator-name"><?= $solution->creator ?></p>
                                <p class="ratingcont" data-default="<?= $solution->solution->rating ?>" data-solutionid="<?= $solution->solution->id ?>" style="height: 9px; background: url(/img/<?= $solution->solution->rating ?>-rating.png) no-repeat scroll 0% 0% transparent;display:inline-block;width: 56px;"></p>
                                <p class="fb_like">
                                    <a href="#"><?= $solution->solution->likes ?></a>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <script type="text/javascript">
                        var solutionDate = '<?= date('Y-m-d H:i:s', strtotime($solutionDate)) ?>';
                    </script> 
                </div>
                <div id="r-sidebar-office">
                    <div id="container-job-designers">
                        <div class="rs-header">Работа для дизайнера</div>
                        <div id="content-job">
                            <?php echo $this->stream->renderStreamFeed(6); ?>
                        </div>
                    </div>
                    <div id="container-new-pitches">
                        <div class="rs-header">Новые питчи</div>
                        <div id="content-pitches">
                            <?php foreach ($pitches as $pitch) : ?>
                                <div class="new-pitches">
                                    <div class="new-price"><?= $this->moneyFormatter->formatMoney($pitch->price) ?></div>
                                    <div class="new-title"><a href="/pitches/view/<?= $pitch->id ?>"><?= $pitch->title ?></a></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div id="container-design-news">
                        <div class="rs-header">Новости дизайна</div>
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
                <div id="center_sidebar">
                    <div class="center-boxes" id="updates-box-">
                        <?php if ($post) : ?>
                            <div class="box"> 
                                <a class="post-link" href="<?= $post->link ?>"><img class="img-post" src="<?= $post->imageurl ?>"></a> 
                                <div class="r-content"> 
                                    <a img-post href="/users/click?link=<?= $post->link ?>&id=<?= $post->id ?>"><h2><?= $post->title ?></h2></a>
                                    <time class="timeago" datetime="<?= $post->created ?>"><?= $post->created ?></time>
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
                            $count++;
                            $avatar = isset($object['user']['images']['avatar_small']) ? $object['user']['images']['avatar_small']['weburl'] : '/img/default_small_avatar.png';
                            if (isset($object['solution']['images']['solution_solutionView'])) {
                                if (isset($object['solution']['images']['solution_solutionView'][0]['weburl'])) {
                                    $imageurl = $object['solution']['images']['solution_solutionView'][0]['weburl'];
                                } else {
                                    $imageurl = $object['solution']['images']['solution_solutionView']['weburl'];
                                }
                            }
                            if ($object['type'] == 'CommentAdded') :
                                ?>
                                <div class="box"> 
                                    <div class="l-img"> 
                                        <img class="avatar" src="<?= $avatar ?>"> 
                                    </div> 
                                    <div class="r-content"> 
                                        <a href="/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> прокомментировал ваше <a href="/pitches/viewsolution/<?= $object['solution']['id'] ?>">решение #<?= $object['solution']['num'] ?></a> для питча <a href="/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>: &laquo;<?php echo $object['updateText'] ?>&raquo;
                                    </div> 
                                    <img class="sol" src="<?= $imageurl ?>">
                                </div>
                            <?php elseif ($object['type'] == 'SolutionAdded') : ?>
                                <div class="box">
                                    <div class="l-img">
                                        <img class="avatar" src="<?= $avatar ?>">
                                    </div>
                                    <div class="r-content">
                                        <a href="/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> предложил решение для питча <a href="/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>:
                                    </div>
                                    <a href="<?= $object['solution']['id'] ?>"><img class="sol" src="<?= $imageurl ?>"></a>
                                    <div id="likes-<?= $object['solution']['id'] ?>" data-id="<?= $object['solution']['id'] ?>" class="likes">
                                        <?php
                                        $id = $object['solution']['id'];
                                        foreach ($updates as $like):
                                            if ($object['type'] == 'LikeAdded' && $object['solution_id'] == $id):
                                                $avatar = isset($object['user']['images']['avatar_small']) ? $object['user']['images']['avatar_small']['weburl'] : '/img/default_small_avatar.png';
                                                ?>
                                                <div>
                                                    <div class="l-img">
                                                        <img class="avatar" src="<?= $avatar ?>">
                                                    </div>
                                                    <span><a href="/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> лайкнул ваше решение</span>
                                                </div>
                                                <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </div></div>
                                <?php
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
<?= $this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'jquery.tooltip.js', 'users/feed.js', 'users/activation.js'), array('inline' => false)) ?>
<?= $this->html->style(array('/main2.css', '/pitches2.css', '/edit', '/view', '/messages12', '/pitches12', '/win_steps1.css', '/win_steps2_final3.css', '/blog', '/portfolio.css', '/css/office.css'), array('inline' => false)) ?>
<?= $this->view()->render(array('element' => 'popups/activation_popup')) ?>
