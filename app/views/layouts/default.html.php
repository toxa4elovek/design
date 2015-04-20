<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/LocalBusiness" lang="ru">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# godesigner: http://ogp.me/ns/fb/godesigner#">
    <?= $this->html->charset();?>
    <?php $vars = compact('solution', 'post', 'pitch', 'answer') ?>
    <?= $this->HtmlExtended->title($this->_request->params, $vars)?>
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
    <meta property="og:type" content="website"/>
    <?php if((isset($solution)) && (isset($solution->images)) && (isset($solution->images['solution_solutionView']))):
    if(!isset($solution->images['solution_galleryLargeSize'][0])):
        $url = 'http://www.godesigner.ru' . $solution->images['solution_gallerySiteSize']['weburl'];
    else:
        $url = 'http://www.godesigner.ru' . $solution->images['solution_gallerySiteSize'][0]['weburl'];
    endif;
    $description = isset($description) ? $description : '';
    ?>
    <meta name="twitter:card" content="photo" />
    <meta name="twitter:site" content="@Go_Deer" />
    <meta name="twitter:title" content="<?=$this->HtmlExtended->title($this->_request->params, $vars)?>" />
    <meta name="twitter:image" content="<?= $url ?>" />
    <meta name="twitter:url" content='http://www.godesigner.ru/pitches/viewsolution/<?= $solution->id ?>' />
    <meta property="og:image" content="<?=$url?>"/>
    <meta property="og:url" content='http://www.godesigner.ru/pitches/viewsolution/<?= $solution->id ?>' />
    <meta property="og:title" content="<?= $this->HtmlExtended->title($this->_request->params, $vars, true)?>"/>
    <meta property="og:description" content="<?=strip_tags($description)?>"/>
    <?php elseif(preg_match('@/posts/view@', $_SERVER['REQUEST_URI'])):
        $first_img = $post->imageurl;
        preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->full, $matches);
        if (!empty($matches[1])) {
            $first_img = $matches[1][0];
        }
        if (stripos($first_img, 'http://') === false) {
            $first_img = 'http://www.godesigner.ru' . $first_img;
        }
        echo '<meta content="article" property="og:type"/>';
        echo '<meta property="og:url" content="http://www.godesigner.ru/posts/view/' . $post->id . '/"/>';
        echo '<meta property="og:description" content="' . str_replace('&nbsp;', ' ', strip_tags($post->short)) . '"/>';
        echo '<meta property="og:title" content="' . $post->title . '"/>';
        echo '<meta property="og:image" content="' . $first_img . '"/>';
        echo '<meta property="fb:admins" content="nyudmitriy"/>';
        echo '<meta property="fb:app_id" content="202765613136579"/>';
    ?>
    <?php elseif (preg_match('@/pitches/(details|view)@', $_SERVER['REQUEST_URI'])):
        echo '<meta content="godesigner:pitch" property="og:type"/>';
        echo '<meta property="og:url" content="http://www.godesigner.ru/pitches/details/' . $pitch->id . '/"/>';
        echo '<meta property="og:description" content="' . str_replace('"', '\'', str_replace("\n\r", '', str_replace('&nbsp;', ' ', strip_tags(mb_substr($pitch->description, 0, 100, 'UTF-8') . '...')))) . '"/>';
        echo '<meta property="og:title" content="' . htmlspecialchars($pitch->title) . '"/>';
        echo '<meta property="og:image" content="http://www.godesigner.ru/img/fb_icon.jpg"/>';
        echo '<meta property="fb:admins" content="nyudmitriy"/>';
        echo '<meta property="fb:app_id" content="202765613136579"/>';
    elseif(preg_match('@/questions@', $_SERVER['REQUEST_URI'])):
        if((!empty($_GET)) && (isset($_GET['result']))):
            if($_GET['result'] == 'dvornik'):
                echo '<meta property="og:url" content="http://www.godesigner.ru/questions?result=dvornik"/>';
                echo '<meta property="og:image" content="http://www.godesigner.ru/img/questions/dvornik_468_246.png"/>';
                echo '<meta property="og:title" content="' . htmlspecialchars('Тест «Какой ты дизайнер на самом деле» показал, что я дворник, совсем не дизайнер!') . '"/>';
            endif;

            if($_GET['result'] == 'malyar'):
                echo '<meta property="og:url" content="http://www.godesigner.ru/questions?result=malyar"/>';
                echo '<meta property="og:image" content="http://www.godesigner.ru/img/questions/malyar_468_246.png"/>';
                echo '<meta property="og:title" content="' . htmlspecialchars('Тест «Какой ты дизайнер на самом деле» показал, что я маляр, лучший кандидат в команду Тома Сойера!') . '"/>';
            endif;

            if($_GET['result'] == 'master'):
                echo '<meta property="og:url" content="http://www.godesigner.ru/questions?result=master"/>';
                echo '<meta property="og:image" content="http://www.godesigner.ru/img/questions/master_468_246.png"/>';
                echo '<meta property="og:title" content="' . htmlspecialchars('Тест «Какой ты дизайнер на самом деле» показал, что я Большой мастер, и выше только бог!') . '"/>';
            endif;

            if($_GET['result'] == 'apollo'):
                echo '<meta property="og:url" content="http://www.godesigner.ru/questions?result=apollo"/>';
                echo '<meta property="og:image" content="http://www.godesigner.ru/img/questions/apollo_468_246.png"/>';
                echo '<meta property="og:title" content="' . htmlspecialchars('Тест «Какой ты дизайнер на самом деле» показал, что я Аполлон, бог искусств!') . '"/>';
            endif;
        else:
            echo '<meta property="og:image" content="http://www.godesigner.ru/img/questions/general.jpg"/>';
        endif;
        echo '<meta property="og:title" content="Тест на знание основ графического дизайна"/>';
        echo '<meta property="og:description" content="Узнай, какой ты дизайнер на самом деле!"/>';
    elseif(isset($shareEvent)):
        echo '<meta property="og:url" content="http://www.godesigner.ru/news?event=' . $shareEvent->id . '"/>';
        if(!empty($shareEvent->news->imageurl)):
            echo '<meta property="og:title" content="' . htmlspecialchars($shareEvent->news->title) . '"/>';
            echo '<meta property="og:description" content="' . str_replace('"', '\'', str_replace("\n\r", '', str_replace('&nbsp;', ' ', strip_tags(mb_substr($shareEvent->news->short, 0, 100, 'UTF-8') . '...'))))  . '"/>';
            $url = $shareEvent->news->imageurl;
            if(!preg_match('/http/', $url)) {
                $url = 'http://www.godesigner.ru' . $url;
            }
            echo '<meta property="og:image" content="' . $url . '"/>';
        endif;
    else:
        echo '<meta property="og:image" content="http://www.godesigner.ru/img/fb_icon.jpg"/>';
        ?>
    <?php endif;?>

    <?=$this->view()->render(array('element' => 'newrelic/newrelic_header'))?>
    <?=$this->view()->render(array('element' => 'scripts/popup_cookies'))?>
    <?php echo $this->html->script('http://vk.com/js/api/openapi.js');?>
</head>

<body class="<?=$this->_request->controller;?>_<?=$this->_request->action;?>">
<?php if($this->_request->action != 'feed'): ?>
<a target="_blank" id="feedback-link" href="http://godesigner.userecho.com/" style="width:67px;position:fixed;top:25%;z-index: 100000;left:-5px;display:hidden;"><img src="/img/LABEL_transparent.png" alt="Отзывы и советы"></a>
<?php endif?>
<?php echo $this->content() ?>

<?=$this->view()->render(array('element' => 'footer'))?>
<?php
echo  $this->html->script('jquery-1.7.1.min.js', array('inline' => false, 'weight' => 10));
echo  $this->html->script('jquery.validate.min', array('inline' => false, 'weight' => 11));
echo  $this->html->script('jquery.simplemodal-1.4.2.js', array('inline' => false, 'weight' => 12));
echo  $this->html->script('jquery.detectmobilebrowser.min.js', array('inline' => false, 'weight' => 13));
echo  $this->html->script('plugins', array('inline' => false, 'weight' => 14));
echo  $this->html->script('scripts', array('inline' => false, 'weight' => 15));
echo  $this->html->script('app', array('inline' => false, 'weight' => 16));
?>
<?= $this->optimize->scripts() ?>
<?=$this->view()->render(array('element' => 'popups/contact_form'))?>
<?=$this->view()->render(array('element' => 'popups/social_popup'))?>
<?=$this->view()->render(array('element' => 'popups/mobile_popup'))?>
<?=$this->view()->render(array('element' => 'popups/email_change'))?>
<?=$this->view()->render(array('element' => 'popups/decline_popup'))?>
<?=$this->view()->render(array('element' => 'scripts/ga'))?>
<?=$this->view()->render(array('element' => 'scripts/ua'))?>
<?=$this->view()->render(array('element' => 'newrelic/newrelic_footer'))?>
</body>
</html>