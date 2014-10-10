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
                    <div class="solutions-block">
                        <a href=""><img width="260" src="http://www.godesigner.ru/solutions/89ea1168f9a0f4150a33782fd1be3cbf_solutionView.png"></a>
                        <div>
                            <span>Вася Т.</span>
                            <img class="rat" src="/img/rating.png">
                            <p class="fb_like">
                                <a href="#">0</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div id="r-sidebar-office">
                    <div id="container-job-designers">
                        <div class="rs-header">Работа для дизайнера</div>
                        <div id="content-job">
                            <?php echo $this->stream->renderStream(10); ?>
                        </div>
                    </div>
                    <div id="container-new-pitches">
                        <div class="rs-header">Новые питчи</div>
                        <div class="new-pitches">
                            <div class="new-price">22000р</div>
                            <div class="new-title">Этикетка для подсолнечного масла</div>
                        </div>
                        <div class="new-pitches">
                            <div class="new-price">22000р</div>
                            <div class="new-title">Этикетка для подсолнечного масла Этикетка для подсолнечного масла Этикетка для подсолнечного масла</div>
                        </div>
                        <div class="new-pitches">
                            <div class="new-price">22000р</div>
                            <div class="new-title">Этикетка для подсолнечного масла</div>
                        </div>
                    </div>
                    <div id="container-design-news">
                        <div class="rs-header">Новости дизайна</div>
                        <div id="content-news">
                        <?php foreach ($news as $n): $host = parse_url($n->link); ?>
                                <div class="design-news"><?= $n->title ?> <br><a class="clicks" data-id="<?= $n->id ?>" href="<?= $n->link ?>"><?= $host['host'] ?></a></div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div id="center_sidebar">
                    <div class="center-boxes" id="updates-box-">
                        <div class="box">
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
                        </div>
                    </div>
                    <div id="officeAjaxLoader" style="text-align: center; display: none; margin-top: 10px;"><img src="/img/blog-ajax-loader.gif"></div>
                </div>

            </div>
            <div class="onTopMiddle">&nbsp;</div>
        </div><!-- /middle_inner -->
    </div><!-- /middle -->
</div><!-- .wrapper -->
<div class="onTop">&nbsp;</div>
<?= $this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'jquery.tooltip.js', 'users/office.js', 'users/activation.js'), array('inline' => false)) ?>
<?= $this->html->style(array('/main2.css', '/pitches2.css', '/edit', '/view', '/messages12', '/pitches12', '/win_steps1.css', '/win_steps2_final3.css', '/blog', '/portfolio.css', '/css/office.css'), array('inline' => false)) ?>
<?= $this->view()->render(array('element' => 'popups/activation_popup')) ?>
