<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/LocalBusiness">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# godesigner: http://ogp.me/ns/fb/godesigner#">
    <?= $this->html->charset();?>
    <?= $this->HtmlExtended->title($this->_request->params, $solution, $post)?>
    <meta name="description" content="">
    <meta name="viewport" content="width=1024"/>
    <link rel="apple-touch-icon" href="/img/icon_57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/img/icon_72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/img/icon_114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/img/icon_144.png" />
    <?= $this->html->link('Icon', 'favicon.png', array('type' => 'icon')); ?>
    <!--[if lt IE 9]><script src="js/html5.js"></script><![endif]-->
    <?= $this->html->style('/global', array('inline' => false, 'weight' => 10)); ?>
    <?= $this->html->style('/main', array('inline' => false, 'weight' => 11)); ?>
    <?= $this->html->style('/fonts', array('inline' => false, 'weight' => 12)); ?>
    <?= $this->html->style('/panel', array('inline' => false, 'weight' => 13)); ?>
    <?= $this->html->style('/contact2', array('inline' => false, 'weight' => 14)); ?>
    <?php echo $this->optimize->styles();?>
    <!--[if lte IE 9]><?= $this->html->style(array('/ie.css')); ?><![endif]-->
    <!--[if lte IE 8]><?= $this->html->style(array('/ie8.css')); ?><![endif]-->
    <!--[if lte IE 7]><?= $this->html->style(array('/ie7.css')); ?><![endif]-->
    <?php if((isset($solution)) && (isset($solution->images)) && (isset($solution->images['solution_solutionView']))):
    if(!isset($solution->images['solution_galleryLargeSize'][0])):
        $url = 'http://www.godesigner.ru' . $solution->images['solution_galleryLargeSize']['weburl'];
    else:
        $url = 'http://www.godesigner.ru' . $solution->images['solution_galleryLargeSize'][0]['weburl'];
    endif;
    $description = '';
    ?>
    <meta property="og:image" content="<?=$url?>"/>
    <meta property="og:description" content="<?=$description?>"/>
    <?php elseif(preg_match('@/posts/view@', $_SERVER['REQUEST_URI'])):
    /*if(!isset($solution->images['solution_galleryLargeSize'][0])):
        $url = 'http://www.godesigner.ru' . $solution->images['solution_galleryLargeSize']['weburl'];
    else:
        $url = 'http://www.godesigner.ru' . $solution->images['solution_galleryLargeSize'][0]['weburl'];
    endif;
    $description = '';*/
    echo '<meta content="article" property="og:type"/>';
    echo '<meta property="og:url" content="http://www.godesigner.ru/posts/view/' . $post->id . '/"/>';
    echo '<meta property="og:description" content="' . str_replace('&nbsp;', ' ', strip_tags($post->short)) . '"/>';
    echo '<meta property="og:title" content="' . $post->title . '"/>';
    echo '<meta property="og:image" content="' . $post->imageurl . '"/>';
    echo '<meta property="fb:admins" content="nyudmitriy"/>';
    echo '<meta property="fb:app_id" content="202765613136579"/>';
    ?>
    <?php elseif (preg_match('@/pitches/details@', $_SERVER['REQUEST_URI'])):
    echo '<meta content="godesigner:pitch" property="og:type"/>';
    echo '<meta property="og:url" content="http://www.godesigner.ru/pitches/details/' . $pitch->id . '/"/>';
    echo '<meta property="og:description" content="' . str_replace('"', '\'', str_replace("\n\r", '', str_replace('&nbsp;', ' ', strip_tags(mb_substr($pitch->description, 0, 100, 'UTF-8') . '...')))) . '"/>';
    echo '<meta property="og:title" content="' . htmlspecialchars($pitch->title) . '"/>';
    echo '<meta property="og:image" content="http://www.godesigner.ru/img/fb_icon.jpg"/>';
    echo '<meta property="fb:admins" content="nyudmitriy"/>';
    echo '<meta property="fb:app_id" content="202765613136579"/>';
    else:
        echo '<meta property="og:image" content="http://www.godesigner.ru/img/fb_icon.jpg"/>';

        ?>
    <?php endif;?>

    <?=$this->view()->render(array('element' => 'newrelic/newrelic_header'))?>

    <script>
        var showSocialPopup = false;
        var needSocialWrite = false;
        <?php if ($this->session->read('user.id')):?>
            <?php if ($this->session->read('user.social') == 0):?>
                <?php if (!isset($_COOKIE['scl']) || $_COOKIE['scl'] == ''):?>
                    <?php setcookie('scl', '1', strtotime('+6 month'), '/');?>
                    needSocialWrite = 1;
                <?php elseif ($_COOKIE['scl'] == '1'):?>
                    <?php setcookie('scl', '2', strtotime('+6 month'), '/');?>
                    needSocialWrite = 2;
                    showSocialPopup = true;
                <?php else:?>
                    needSocialWrite = 2;
                <?php endif;?>
            <?php elseif ($this->session->read('user.social') == 1):?>
                <?php if (!isset($_COOKIE['scl']) || $_COOKIE['scl'] == ''):?>
                    <?php setcookie('scl', '2', strtotime('+6 month'), '/');?>
                    needSocialWrite = 2;
                    showSocialPopup = true;
                <?php elseif ($_COOKIE['scl'] == '1'):?>
                    <?php setcookie('scl', '2', strtotime('+6 month'), '/');?>
                    needSocialWrite = 2;
                    showSocialPopup = true;
                <?php else:?>
                    needSocialWrite = 2;
                <?php endif;?>
            <?php else:?>
                <?php setcookie('scl', '2', strtotime('+6 month'), '/');?>
            <?php endif?>
        <?php else:?>
            <?php if (!isset($_COOKIE['scl']) || $_COOKIE['scl'] == ''):?>
                <?php setcookie('scl', '1', strtotime('+6 month'), '/');?>
            <?php elseif ($_COOKIE['scl'] == '1'):?>
                <?php setcookie('scl', '2', strtotime('+6 month'), '/');?>
                showSocialPopup = true;
            <?php endif;?>
        <?php endif?>
    </script>
    <?php echo $this->html->script('http://vk.com/js/api/openapi.js');?>
</head>
<?php
$clientNotice = $this->session->read('user.attentionpitch');
$designerNotice = $this->session->read('user.attentionsolution');
$timeoutNotice = $this->session->read('user.timeoutpitch');
$showPanel = ((count($clientNotice) > 0) || (count($designerNotice) > 0) || (!is_null($timeoutNotice)));
?>
<body>
<a target="_blank" id="feedback-link" href="http://godesigner.userecho.com/" style="width:67px;position:fixed;top:25%;z-index: 100000;left:-5px;display:hidden;"><img src="/img/LABEL_transparent.png" alt="Отзывы и советы"></a>
<?php echo $this->content() ?>

<?=$this->view()->render(array('element' => 'footer'))?>

<?php
//echo $this->html->script(array('http://code.jquery.com/jquery-1.7.1.min.js', 'jquery.validate.min', 'plugins', 'scripts', 'app'))
//echo $this->html->script(array('jquery-1.7.1.min.js', 'jquery.validate.min', 'jquery.simplemodal-1.4.2.js', 'jquery.detectmobilebrowser.min.js', 'plugins', 'scripts', 'app'));

echo  $this->html->script('jquery-1.7.1.min.js', array('inline' => false, 'weight' => 10));
echo  $this->html->script('jquery.validate.min', array('inline' => false, 'weight' => 11));
echo  $this->html->script('jquery.simplemodal-1.4.2.js', array('inline' => false, 'weight' => 12));
echo  $this->html->script('jquery.detectmobilebrowser.min.js', array('inline' => false, 'weight' => 13));
echo  $this->html->script('plugins', array('inline' => false, 'weight' => 14));
echo  $this->html->script('scripts', array('inline' => false, 'weight' => 15));
echo  $this->html->script('app', array('inline' => false, 'weight' => 16));
?>
<?= $this->optimize->scripts() ?>
<?php
echo '<!--' . $this->session->read('user.events.date') . '-->';
echo '<!--' . $this->session->read('user.blogpost.date') . '-->';
?>
<div id="fb-root"></div>
<div id="loading-overlay2" style="overflow:visible;display:none;" class="" style="display:none;text-align:center;text-shadow:none;overflow:hidden;">
    <div style="width:486px;height:690px;padding-top:7px;background:url(/img/requesthelpform2.png);">
        <div id="reqmainform">
            <a class="close-request" style="color: rgb(100, 143, 164); font-size: 12px; padding-right: 20px; background: url('/img/closerequestform.png') no-repeat scroll 50px 0px transparent; margin-top: 0px; margin-left: 405px;" href="#">закрыть</a>
            <form id="requesthelp" method="post" action="/users/requesthelp.json" style="padding-top: 59px;">
                <input type="text" style="display:block;opacity:0.1;width:1px;height:1px;" name="reqfiller" value="">
                <input type="hidden" name="case" value="h4820g838f">
                <div>
                    <span style="margin-left: 45px; font-size: 11px; color: #666666;text-shadow: 0 1px 1px white">АДРЕСУЮ ЭТО...</span>
                    <input type="text" class="i1" style="margin-left: 43px; width: 333px; height: 43px; float: left; margin-top: 6px;" id="reqto" name="to">
                    <a href="#" style="float: right; display: block; margin-right: 43px; margin-top: 7px;" id="requesthelpselector"><img src="/img/requestselector.png"></a>
                </div>
                <div style="height: 1px; clear: both; margin-bottom: 10px;"></div>
                <span style="margin-left: 45px; font-size: 11px; color: #666666;text-shadow: 0 1px 1px white">ВАШ EMAIL</span><span style="text-decoration:none;margin-left:5px;color:#ff5a5e;font-size:11px;">*</span>
                <input name="email" value="<?= $this->session->read('user.email')?>" id="reqemail" type="text" style="height:43px;
                margin-left: 43px;margin-top:5px;width:365px;" name="name" class="i1">
                <div style="height: 1px; clear: both; margin-bottom: 10px;"></div>
                <span style="margin-left: 45px; font-size: 11px; color: #666666;text-shadow: 0 1px 1px white">ВАШЕ ИМЯ</span>
                <input name="name" id="reqname" type="text" value="<?= $this->session->read('user.first_name') . ' ' . $this->session->read('user.last_name')?>" style="height:43px;margin-left: 43px;margin-top:5px;width:365px;" name="name" class="i1">
                <input name="target" id="reqtarget" type="hidden" value="0">
                <div style="height: 1px; clear: both; margin-bottom: 10px;"></div>
                <span style="margin-left: 45px; font-size: 11px; color: #666666;text-shadow: 0 1px 1px white">ОПИШИТЕ ПРОБЛЕМУ И ЗАДАЙТЕ ВОПРОС</span>
                <textarea name="message" id="reqmessage" style="margin-left: 43px;margin-top:5px;width:365px;height:240px;"></textarea>

                <center>
                    <input type="submit" id="reqsend" class="reqbutton" value="Отправить" style="margin-bottom: 20px; width: 184px; margin-top:19px; margin-right: 18px; color:#FFFFFF;font-size: 12px;text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);height:48px">
                </center>
            </form>
            <div id="contactlist" style="background:url('/img/requestback.png');display:none;top:-541px;margin-left:42px;width:399px;height:140px;position:relative;z-index:15">
                <ul>
                    <li class="requestli"><a href="#" class="reqlink" data-id="1">дизайн консультация (Оксана Девочкина)</a></li>
                    <li class="requestli" style="padding-top:16px"><a href="#" class="reqlink" data-id="2">бухгалтерия (Слава Афанасьев)</a></li>
                    <li class="requestli" style="padding-top:16px"><a href="#" class="reqlink" data-id="3">тех. поддержка (Дима Ню)</a></li>
                    <li class="requestli" style="padding-top:16px"><a href="#" class="reqlink" data-id="4">другое (Максим Федченко)</a></li>
                </ul>
            </div>
        </div>
        <div id="reqformthankyou" style="display:none;">
            <a href="#" style="color: rgb(100, 143, 164); font-size: 12px; padding-right: 20px; background: url('/img/closerequestform.png') no-repeat scroll 50px 0px transparent; margin-top: 0px; margin-left: 405px;" class="close-request">закрыть</a>
            <img style="margin-left:45px;margin-top:0px" src="/img/reqthank.png" alt="">
        </div>
        <div id="fbimg" style="display:none;">
            <img src="/img/share-image.jpg" alt="" />
        </div>
    </div>
</div>
<!-- Start: Socials Popup -->
<div id="socials-modal" style="overflow: visible; display: none; opacity: 0; width: 700px; background: #282A34;">
    <div style="position: absolute; top: 460px; left: 25px; width: 652px; height: 10px; background: #454650; box-shadow: 0 5px 5px rgba(0,0,0,.4);"></div>
    <div style="position: relative; height: 70px; width: 100%; background: url('/img/go-social-header.png') no-repeat 18px 0px #282a34; box-shadow: inset 0 -1px 0 rgba(39,41,50,1);">
        <a class="close-request" style="float: right; color: rgb(100, 143, 164); font-size: 12px; padding-right: 17px; background: url('/img/closerequest.png') no-repeat right center; margin-top: 30px; margin-right: 20px;" href="#">закрыть</a>
    </div>
    <div style="position: relative; height: 390px; background: url('/img/404_bg.png') top center no-repeat; background-size: cover; box-shadow: inset 0 1px 0 rgba(45,47,56,1), 0 5px 5px rgba(0,0,0,.4);">
        <!-- Start: VK -->
        <div id="vk_groups" style="float: left; margin: 40px 30px 0 20px;"></div>
        <!-- End: VK -->

        <!-- Start: FB -->
        <div class="fb-like-box" style="float: left; margin: 40px 20px 0 30px; background: white;" data-href="https://www.facebook.com/pages/Go-Designer/160482360714084" data-width="300" data-height="290" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
        <!-- End: FB -->
    </div>
</div>
<!-- End: Socials Popup -->
<?=$this->view()->render(array('element' => 'external_scripts/ga'))?>
<?=$this->view()->render(array('element' => 'newrelic/newrelic_footer'))?>
</body>
</html>