<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/LocalBusiness" lang="ru">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# godesigner: http://ogp.me/ns/fb/godesigner#">
    <?= $this->html->charset();?>
    <?php $vars = compact('solution', 'post', 'pitch', 'answer') ?>
    <?= $this->HtmlExtended->title($this->_request->params, $vars)?>
    <meta name="viewport" content="width=1024"/>
    <link rel="apple-touch-icon" href="/img/icon_57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/img/icon_72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/img/icon_114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/img/icon_144.png" />
    <link rel="manifest" href="/manifest.json">
    <?= $this->html->link('Icon', 'favicon.png', array('type' => 'icon')); ?>
    <!--[if lt IE 9]><script src="js/html5.js"></script><![endif]-->
    <?= $this->html->style('/global', array('inline' => false, 'weight' => 10)); ?>
    <?= $this->html->style('/main', array('inline' => false, 'weight' => 11)); ?>
    <?= $this->html->style('/css/common/fonts', array('inline' => false, 'weight' => 12)); ?>
    <?= $this->html->style('/panel', array('inline' => false, 'weight' => 13)); ?>
    <?= $this->html->style('/contact2', array('inline' => false, 'weight' => 14)); ?>
    <?= $this->html->style('/dist/build', array('inline' => false, 'weight' => 20)); ?>
    <?php echo $this->optimize->styles();?>
    <!--[if lte IE 9]><?= $this->html->style(array('/ie.css')); ?><![endif]-->
    <!--[if lte IE 8]><?= $this->html->style(array('/ie8.css')); ?><![endif]-->
    <!--[if lte IE 7]><?= $this->html->style(array('/ie7.css')); ?><![endif]-->
    <meta property="og:type" content="website"/>
    <meta property="fb:app_id" content="202765613136579"/>

    <?php echo $this->Og->getOgUrl(''); ?>
    <?php if((isset($solution)) && (isset($solution->images)) && (isset($solution->images['solution_solutionView']))):
        if(!isset($solution->images['solution_galleryLargeSize'][0])):
            $url = 'https://www.godesigner.ru' . $solution->images['solution_gallerySiteSize']['weburl'];
        else:
            $url = 'https://www.godesigner.ru' . $solution->images['solution_gallerySiteSize'][0]['weburl'];
        endif;
        $description = isset($description) ? $description : '';
        ?>
        <?php echo $this->Og->getOgImage($url); ?>
        <?php echo $this->Og->getOgTitle($this->HtmlExtended->title($this->_request->params, $vars, true));?>
        <?php echo $this->Og->getOgDescription($description);?>
    <?php elseif(preg_match('@/posts/view@', $_SERVER['REQUEST_URI'])):
        $first_img = $post->imageurl;
        preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->full, $matches);
        if (!empty($matches[1])) {
            $first_img = $matches[1][0];
        }
        if (stripos($first_img, 'https://') === false) {
            $first_img = 'https://www.godesigner.ru' . $first_img;
        }
        echo '<meta content="article" property="og:type"/>';
        echo $this->Og->getOgDescription($post->short);
        echo $this->Og->getOgTitle($post->title);
        echo $this->Og->getOgImage($first_img);
        echo '<meta property="fb:admins" content="nyudmitriy"/>';
        echo '<meta property="fb:app_id" content="202765613136579"/>';
    ?>
    <?php elseif (preg_match('@/pitches/(details|view)@', $_SERVER['REQUEST_URI'])):
        echo '<meta content="godesigner:pitch" property="og:type"/>';
        echo $this->Og->getOgDescription($pitch->description);
        echo $this->Og->getOgTitle($pitch->title);
        echo $this->Og->getOgImage('');
        echo '<meta property="fb:admins" content="nyudmitriy"/>';
        echo '<meta property="fb:app_id" content="202765613136579"/>';
    elseif(preg_match('@/questions@', $_SERVER['REQUEST_URI'])):
        if((!empty($_GET)) && (isset($_GET['result']))):
            if($_GET['result'] == 'dvornik'):
                echo $this->Og->getOgImage('https://www.godesigner.ru/img/questions/dvornik_468_246.png');
                echo $this->Og->getOgTitle('Тест «Какой ты дизайнер на самом деле» показал, что я дворник, совсем не дизайнер!');
            endif;

            if($_GET['result'] == 'malyar'):
                echo $this->Og->getOgImage('https://www.godesigner.ru/img/questions/malyar_468_246.png');
                echo $this->Og->getOgTitle('Тест «Какой ты дизайнер на самом деле» показал, что я маляр, лучший кандидат в команду Тома Сойера!');
            endif;

            if($_GET['result'] == 'master'):
                echo $this->Og->getOgImage('https://www.godesigner.ru/img/questions/master_468_246.png');
                echo $this->Og->getOgTitle('Тест «Какой ты дизайнер на самом деле» показал, что я Большой мастер, и выше только бог!');
            endif;

            if($_GET['result'] == 'apollo'):
                echo $this->Og->getOgImage('https://www.godesigner.ru/img/questions/apollo_468_246.png');
                echo $this->Og->getOgTitle('Тест «Какой ты дизайнер на самом деле» показал, что я Аполлон, бог искусств!');
            endif;
        else:
            echo $this->Og->getOgTitle("Тест на знание основ графического дизайна");
            echo $this->Og->getOgImage('https://www.godesigner.ru/img/questions/general.jpg');
        endif;
        echo $this->Og->getOgDescription("Узнай, какой ты дизайнер на самом деле!");
    elseif(isset($shareEvent)):
        if(!empty($shareEvent->news->og_title)) {
            echo $this->Og->getOgTitle($shareEvent->news->og_title);
        }else {
            echo $this->Og->getOgTitle($shareEvent->news->title);
        }
        if(!empty($shareEvent->news->og_description)) {
            echo $this->Og->getOgDescription($shareEvent->news->og_description);
        }else {
            echo $this->Og->getOgDescription($shareEvent->news->short);
        }
        if(!empty($shareEvent->news->og_image)) {
            echo $this->Og->getOgImage($shareEvent->news->og_image);
        }else {
            echo $this->Og->getOgImage($shareEvent->news->imageurl);
        }
    else:
        echo $this->Og->getOgImage('');
        ?>
    <?php endif;?>

    <?=$this->view()->render(array('element' => 'newrelic/newrelic_header'))?>
    <?=$this->view()->render(array('element' => 'scripts/popup_cookies'))?>
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "WebSite",
      "url": "https://www.godesigner.ru/",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://www.godesigner.ru/logosale?search={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <script type="application/ld+json">
    {  "@context" : "http://schema.org",
       "@type" : "WebSite",
       "name" : "GoDesigner",
       "alternateName" : "GoDesigner.ru",
       "url" : "https://www.godesigner.ru"
    }
    </script>
</head>

<body class="<?=$this->_request->controller;?>_<?=$this->_request->action;?>">
<?php if($this->_request->action != 'feed'): ?>
<a target="_blank" id="feedback-link" href="http://godesigner.userecho.com/" style="width:67px;position:fixed;top:25%;z-index: 100000;left:-5px;"><img src="/img/LABEL_transparent.png" alt="Отзывы и советы"></a>
<?php endif?>
<?php echo $this->content() ?>

<?=$this->view()->render(array('element' => 'footer'))?>
<?php
$env = lithium\core\Environment::get();
echo $this->html->script('https://vk.com/js/api/openapi.js');
if($env == 'development') {
    echo $this->html->script('/js/react/0.14.0-dev/react-0.14.0.js', array('inline' => false, 'weight' => 8));
    echo $this->html->script('/js/react/0.14.0-dev/react-dom-0.14.0.js', array('inline' => false, 'weight' => 9));
}else {
    echo $this->html->script('/js/react/0.14.0/react-0.14.0.min.js', array('inline' => false, 'weight' => 8));
    echo $this->html->script('/js/react/0.14.0/react-dom-0.14.0.min.js', array('inline' => false, 'weight' => 9));
}
if(($this->_request->params['controller'] == 'users') && ($this->_request->params['action'] == 'subscriber')) {
    echo $this->html->script('/js/jquery/jquery-1.9.1.min.js', array('inline' => false, 'weight' => 10));
    echo $this->html->script('/js/jquery/jquery-migrate-1.1.0.min.js', array('inline' => false, 'weight' => 11));
} else {
    echo $this->html->script('jquery-1.8.3.min.js', array('inline' => false, 'weight' => 10));
}
echo $this->html->script('jquery.validate.min', array('inline' => false, 'weight' => 12));
echo $this->html->script('jquery.simplemodal-1.4.2.js', array('inline' => false, 'weight' => 13));
echo $this->html->script('jquery.detectmobilebrowser.min.js', array('inline' => false, 'weight' => 14));
echo $this->html->script('plugins', array('inline' => false, 'weight' => 15));
echo $this->html->script('moment.min.js', array('inline' => false, 'weight' => 15));
echo $this->html->script('app', array('inline' => false, 'weight' => 16));
echo $this->html->script('/js/common/BaseComponent.js', array('inline' => false));
?>
<?= $this->optimize->scripts() ?>
<?=$this->view()->render(array('element' => 'popups/contact_form'))?>
<?=$this->view()->render(array('element' => 'popups/social_popup'))?>
<?=$this->view()->render(array('element' => 'popups/mobile_popup'))?>
<?=$this->view()->render(array('element' => 'popups/email_change'))?>
<?=$this->view()->render(array('element' => 'popups/decline_popup'))?>
<?=$this->view()->render(array('element' => 'popups/project_delete'))?>
<?=$this->view()->render(array('element' => 'scripts/ga'))?>
<?=$this->view()->render(array('element' => 'scripts/ua'))?>
<?=$this->view()->render(array('element' => 'newrelic/newrelic_footer'))?>
<script>
<?php
    if($this->debug->isDebugInfoExists()) {
        $debugQueries = $this->debug->getDebugQueries();
        $debugQueries = $this->debug->sortQueriesByTimestamp($debugQueries);
        $totalTimeMysql = 0;
        $totalTimeRedis = 0;
        $redisCount = 0;
        foreach($debugQueries as $query):
            if($query['type'] == 'sql'):
                $totalTimeMysql += $this->debug->roundTime($query['elapsed_time']);
            elseif($query['type'] == 'redis'):
                $redisCount++;
                $totalTimeRedis += $this->debug->roundTime($query['elapsed_time']);
            endif;
            echo $this->debug->getHtmlForQuery($query);
        endforeach;
        $totalCount = count($debugQueries);
        $style = $this->debug->getVisualStyle('info');
        echo "console.log('%cTotal queries: $totalCount (redis - $redisCount), time in MySql: $totalTimeMysql, time in Redis: $totalTimeRedis', '$style');\r\n";
        $this->debug->clearDebugInfo();
    }
?>
</script>
<?php
if($env !== 'development'):?>
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
<script>
    var OneSignal = OneSignal || [];
    OneSignal.push(["init", {
        appId: "46001cba-49be-4cc5-945a-bac990a6d995",
        autoRegister: true,
        safari_web_id: 'web.onesignal.auto.5a33fe23-ccc7-4feb-afe0-cf26b0b7b29c',
        welcomeNotification: {
            "disable": true
        }
    }]);
</script>
<?php endif?>
</body>
</html>