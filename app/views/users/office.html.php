<div class="wrapper login">

    <?=$this->view()->render(array('element' => 'header'), array('header' => 'header2', 'logo' => 'logo'))?>

<div class="middle">
    <div class="middle_inner" style="margin-top: 0px;">
        <nav class="main_nav clear" style="width:832px;">
            <?=$this->view()->render(array('element' => 'office/nav'));?>
        </nav>

        <!-- div class="main_carous">
            <div>
                <div id="prev" class="arrow arrow_left"><a href="#"></a></div>
                <div id="next" class="arrow arrow_right"><a href="#"></a></div>
                <div id="big_carousel">
                    <ul class="group">
                        <?php
                        $total = count($gallery);
                        if($total < 6) {
                            $diff = 6 - $total;
                        }
                        foreach($gallery as $solution):
                        ?>
                        <li>
                            <?=$this->html->link('<img src="' . $this->solution->renderImageUrl($solution['images']['solution_galleryLargeSize']) . '" width="99" height="75" alt="" title="" />', array('controller' => 'pitches', 'action' => 'viewsolution', 'id' => $solution['id']), array('escape' => false))?>
                        </li>
                        <?php endforeach;
                        for($i=1; $i<= $diff; $i++):?>
                            <li>
                                <a href="#"><img src="/img/pic.jpg" width="99" height="75" alt="" title="" /></a>
                            </li>
                        <?php endfor;?>
                    </ul>
                </div>
            </div>
        </div -->

        <div class="content group">
            <div id="left_sidebar">
                <section class="obnovlenia">
                    <h1>Обновления</h1>
                </section>
                <script type="text/javascript">
                    var offsetDate = Date.parse('<?=date('Y/m/d H:i:s', strtotime($date))?>');
                </script>
                <div class="main_content" id="updates-box">

                    <?php
                    $html = '';
                    foreach($updates as $object):
                        if($object['solution'] == null) {

                        }else {
                            $newclass = '';
                            if(strtotime($object['created']) > strtotime($date)) {
                                $newclass = ' newevent ';
                            }
                            if($object['type'] == 'PitchCreated') {
                                $newclass = ' newpitchstream ';
                            }
                            //if(index == 0) {
                            //    self.date = object.created;
                            //}
                            if(isset($object['solution']['images']['solution_galleryLargeSize'])) {
                                echo '<!-- loop start -->';
                                if(!isset($object['solution']['images']['solution_galleryLargeSize'][0])) {
                                    $imageurl = $object['solution']['images']['solution_galleryLargeSize']['weburl'];
                                }else {
                                    $imageurl = $object['solution']['images']['solution_galleryLargeSize'][0]['weburl'];
                                }
                                if($object['type'] == 'PitchCreated') {
                                    $imageurl = '/img/zaglushka.jpg';
                                }else {
                                    if($object['pitch']['private'] == 1) {
                                        if(($object['user_id'] != $this->user->getId()) && (!$this->user->isPitchOwner($object['pitch']['user_id']))) {
                                            $imageurl = '/img/copy-inv.png';
                                        }
                                    }
                                }
                                $extraUI = '';
                                if($object['type'] != 'PitchCreated') {
                                    $extraUI = '<ul class="group">'.
                                    '<li><a href="#"></a></li>'.
                                    '<li><a href="#"></a></li>'.
                                    '<li><a href="#"></a></li>'.
                                    '<li><a href="#"></a></li>'.
                                    '<li><a href="#"></a></li>'.
                                    '</ul>'.
                                    '<p class="visit_number">' . $object['solution']['views'] . '</p>'.
                                    '<p class="fb_like"><a href="#">' . $object['solution']['likes'] . '</a></p>';
                                }
                                $html .=  '<div class="obnovlenia_box ' . $newclass . 'group">'.
                                '<section class="global_info">'.
                                    '<p>' . $object['humanType'] . '</p>'.
                                    '<p class="designer_name">' . $object['creator'] . '</p>'.
                                    '<p class="add_date">' . $object['humanCreated'] . '</p>'.
                                    $extraUI .
                                    '</section>'.

                                '<section class="global_picture">'.
                                    '<div class="pic_wrapper">'.
                                        '<a href="/pitches/viewsolution/' . $object['solution']['id'] . '"><img src="' . $imageurl . '" width="99" height="75" alt="" /></a>'.
                                        '<!--img class="winning" src="/img/winner_icon.png" width="25" height="59" /-->'.
                                        '</div>'.
                                    '</section>'.

                                '<section class="main_info">'.
                                    '<h2><a href="/pitches/view/' . $object['pitch']['id'] . '">' . $object['pitch']['title'] . '</a></h2>'.
                                    '<p class="subject">' . $object['pitch']['industry'] . '</p>'.
                                    '<p class="price"><span>' . $this->moneyFormatter->formatMoney($object['pitch']['price']) . '</span> P.-</p>'.
                                    '<p class="main_text">'.
                                        $object['updateText'] .
                                        '</p>'.
                                    '<p class="full_pitch"><a href="/pitches/view/' . $object['pitch']['id'] . '"></a></p>'.
                                    '</section>'.
                                '</div>';
                            }
                        }
                    endforeach;
                    $html .= '<div id="earlier_button"' . (($nextUpdates == 0) ? 'style="display: none"' : '') . '><a href="#" id="older-events">Ранее</a></div>';
                    echo $html
                    ?>
                </div><!-- .maincontent -->
                <div id="officeAjaxLoader" style="text-align: center; display: none; margin-top: 10px;"><image src="/img/blog-ajax-loader.gif"></div>

                <div id="no-updates" style="display:none;">
                    <section class="post">
                        <h2 class="title largest-header" style="width:640px;background: none;">участвуйте в <a href="/pitches">питчах</a>, и ваши решения появятся в галерее сверху</h2>
                        <p class="align-center"><img  src="/img/img-cont1.png" alt="" width="440" height="154"></p>
                    </section><!-- .post -->
                    <section class="post">
                        <h2 class="title largest-header" style="width:640px;background: none;">Добавляйте понравившийся <a href="/pitches">питч</a> в «избранное» и отслеживайте обновления!</h2>
                        <p class="align-center"><img class="align-center" src="/img/img-cont2.png" width="528px" height="161px" alt=""></p>
                    </section><!-- .post -->
                </div>

            </div><!-- #left_sidebar -->

            <div id="right_sidebar" style="width: 200px;">
                <?php if(count($winners) > 0):?>
                <div style="width:181px; margin-left:19px;">
                    <h2 style="text-transform: uppercase; text-align: center;">Победители</h2>
                    <div id="small_carousel">
                        <div id="prev2" class="arrow arrow_left"><a href="#"></a></div>
                        <div id="next2" class="arrow arrow_right"><a href="#"></a></div>
                        <div id="carousel_small">
                            <ul style="display:block;overflow:hidden;height:106px;">
                                <?php foreach($winners as $winner):?>
                                <li>
                                    <?php $image = '<img src="' . $this->solution->renderImageUrl($winner->images["solution_galleryLargeSize"]) . '" width="108" height="83" alt="" />';
                                    echo $this->html->link($image, array('Pitches::viewsolution', 'id' => $winner->id), array('escape' => false));
                                    ?>
                                </li>
                                <?php endforeach?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endif;?>
                <div id="current_pitch">
                    <?php echo $this->stream->renderStream(10, false);?>
                </div>

            </div><!-- /right_sidebar -->
        </div><!-- /content -->

    </div><!-- /middle_inner -->
    <div id="under_middle_inner"></div><!-- /under_middle_inner -->
</div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array('/main2.css', '/pitches2.css', '/edit','/view', '/messages12', '/pitches12', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css',), array('inline' => false))?>