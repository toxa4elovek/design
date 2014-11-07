<div class="new-wrapper login">

    <?= $this->view()->render(array('element' => 'header'), array('header' => 'header2', 'logo' => 'logo')) ?>

    <div class="new-middle">
        <div class="new-middle_inner">
            <input type="hidden" value="<?= $this->user->getId() ?>" id="user_id">
            <script type="text/javascript">
                var offsetDate = Date.parse('<?= date('Y/m/d H:i:s', strtotime($date)) ?>');
            </script>
            <?php if($this->user->getGender() < 1): ?>
            <div id="gender-box">
                <div>
                    <div id="close-gender"></div>
                    <span>
                        <span>Укажите ваш пол, пожалуйста:</span> 
                        <input type="radio" name="gender" id="male">
                        <label for="male" class="genderlabel first">Мужской</label>
                        <input type="radio" name="gender" id="female">
                        <label for="female" class="genderlabel">Женский</label>
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
                                <a href="/pitches/viewsolution/<?= $solution->solution_id ?>"><div class="left-sol" style="background: url(<?=$image?>)"></div></a>
                                <div class="solution-info">
                                    <p class="creator-name"><a target="_blank" href="/users/view/<?= $solution->user_id ?>"><?= $solution->creator ?></a></p>
                                    <p class="ratingcont" data-default="<?= $solution->solution->rating ?>" data-solutionid="<?= $solution->solution->id ?>" style="height: 9px; background: url(/img/<?= $solution->solution->rating ?>-rating.png) no-repeat scroll 0% 0% transparent;display:inline-block;width: 56px;"></p>
                                    <a data-id="<?= $solution->solution->id ?>" class="like-small-icon" href="#"><span><?= $solution->solution->likes ?></span></a>
                                    <div class="sharebar" style="padding:0 0 4px !important;background:url('/img/tooltip-bg-bootom-stripe.png') no-repeat scroll 0 100% transparent !important;position:absolute;z-index:10000;display: none; left: 250px; top: 27px;height: 178px;width:288px;">
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
                            <div class="rs-header">Twitter лента</div>
                            <div id="content-job">
                                <?php echo $this->stream->renderStreamFeed(6); ?>
                            </div>
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
                </div>
                <div id="center_sidebar">
                    <div class="center-boxes" id="updates-box-">
                        <?php if ($middlePost) : ?>
                            <div class="box">
                                <p class="img-box">
                                    <a class="post-link" href="/users/click?link=<?= $middlePost->link ?>&id=<?= $middlePost->id ?>"><img class="img-post" src="<?= $middlePost->imageurl ?>"></a>
                                </p>
                                <p class="img-tag"><?= $middlePost->tags ?></p>
                                <div class="r-content post-content"> 
                                    <a class="img-post" href="/users/click?link=<?= $middlePost->link ?>&id=<?= $middlePost->id ?>"><h2><?= $middlePost->title ?></h2></a>
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
                                ?>
                                <div class="box"> 
                                    <div class="l-img"> 
                                        <a target="_blank" href="/users/view/<?= $object['user_id'] ?>"><img class="avatar" src="<?= $avatar ?>"></a>
                                    </div>
                                    <?php if ($this->user->getId() == $object['pitch']['user_id'] || ($object['comment']['public'] && $object['comment']['reply_to'] != 0)): ?>
                                        <div class="r-content"> 
                                            <a href="/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?=$this->user->getGenderTxt('оставил',$object['user']['gender'])?> комментарий в питче <a href="/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>: &laquo;<?php echo $object['updateText'] ?>&raquo;
                                        </div> 
                                        <a href="/pitches/viewsolution/<?= $object['solution']['id'] ?>"><div class="sol"><img src="<?= $imageurl ?>"></div></a>
                                    <?php else: ?>
                                        <div class="r-content"> 
                                            <a href="/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?=$this->user->getGenderTxt('прокомментировал',$object['user']['gender'])?> <a href="/pitches/viewsolution/<?= $object['solution']['id'] ?>">решение #<?= $object['solution']['num'] ?></a> для питча <a href="/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>: &laquo;<?php echo $object['updateText'] ?>&raquo;
                                        </div>
                                        <a href="/pitches/viewsolution/<?= $object['solution']['id'] ?>"><div class="sol"><img src="<?= $imageurl ?>"></div></a>
                                    <?php endif; ?>
                                </div>
                            <?php elseif ($object['type'] == 'SolutionAdded' && !is_null($object['solution'])) : ?>
                                <div class="box">
                                    <div class="l-img">
                                        <a target="_blank" href="/users/view/<?= $object['user_id'] ?>"><img class="avatar" src="<?= $avatar ?>"></a>
                                    </div>
                                    <div class="r-content">
                                        <a href="/users/view/<?= $object['user_id'] ?>"><?= $object['creator'] ?></a> <?=$this->user->getGenderTxt('предложил',$object['user']['gender'])?> решение для питча <a href="/pitches/view/<?= $object['pitch_id'] ?>"><?= $object['pitch']['title'] ?></a>:
                                    </div>
                                    <a href="/pitches/viewsolution/<?= $object['solution']['id'] ?>"><div class="sol"><img src="<?= $imageurl ?>"></div></a>
                                    <div id="likes-<?= $object['solution']['id'] ?>" data-id="<?= $object['solution']['id'] ?>" class="likes">
                                        <?php
                                        $id = $object['solution']['id'];
                                        foreach ($updates as $like):
                                            if ($like['type'] == 'LikeAdded' && $like['solution_id'] == $id):
                                                $avatar = isset($like['user']['images']['avatar_small']) ? $like['user']['images']['avatar_small']['weburl'] : '/img/default_small_avatar.png';
                                                ?>
                                                <div>
                                                    <div class="l-img">
                                                        <a target="_blank" href="/users/view/<?= $like['user_id'] ?>"><img class="avatar" src="<?= $avatar ?>"></a>
                                                    </div>
                                                    <span><a href="/users/view/<?= $like['user_id'] ?>"><?= $like['creator'] ?></a> <?=$this->user->getGenderTxt('лайкнул',$like['user']['gender'])?> <?= ($like['user_id'] == $this->user->getId()) ? 'ваше' : '' ?> решение</span>
                                                </div>
                                                <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </div></div>
                                <?php elseif($object['type'] == 'newsAdded'): ?>
                                    <div class="box">
                                        <p class="img-box">
                                            <a class="post-link" href="/users/click?link=<?= $object['news']['link'] ?>&id=<?= $object['news']['id'] ?>"><img class="img-post" src="<?= $object['news']['imageurl'] ?>"></a>
                                        </p>
                                        <p class="img-tag"><?= $object['news']['tags'] ?></p>
                                        <div class="r-content post-content"> 
                                            <a class="img-post" href="/users/click?link=<?= $object['news']['link'] ?>&id=<?= $object['news']['id'] ?>"><h2><?= $object['news']['title'] ?></h2></a>
                                            <p class="timeago">
                                                <time class="timeago" datetime="<?= $object['news']['created'] ?>"><?= $object['news']['created'] ?></time> с сайта <?= $object['host'] ?>
                                            </p>
                                        </div>
                                    </div>
                            <?php endif;
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
<?= $this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'jquery.tooltip.js', 'socialite.js', 'users/feed.js', 'users/activation.js'), array('inline' => false)) ?>
<?= $this->html->style(array('/main2.css', '/pitches2.css', '/edit', '/view', '/messages12', '/pitches12', '/win_steps1.css', '/win_steps2_final3.css', '/blog', '/portfolio.css', '/css/office.css'), array('inline' => false)) ?>
<?= $this->view()->render(array('element' => 'popups/activation_popup')) ?>
