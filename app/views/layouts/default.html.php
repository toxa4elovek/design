<!DOCTYPE html>
<html lang="ru">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# godesigner: http://ogp.me/ns/fb/godesigner#">
    <?= $this->html->charset();?>
    <?php
    $requestUri = $_SERVER['REQUEST_URI'];
    $request = $this->_request;
    $vars = compact('solution', 'post', 'expert', 'pitch', 'answer', 'requestUri', 'request') ?>
    <?= $this->HtmlExtended->title($this->_request->params, $vars)?>
    <meta name="viewport" content="width=1024"/>
    <link rel="apple-touch-icon" href="/img/icon_57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/img/icon_72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/img/icon_114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/img/icon_144.png" />
    <link rel="manifest" href="/manifest.json">
    <?= $this->html->link('Icon', 'favicon.png', ['type' => 'icon']); ?>
    <!--[if lt IE 9]><script src="js/html5.js"></script><![endif]-->
    <?= $this->html->style('/dist/components.css', ['inline' => false, 'weight' => 9]); ?>
    <?= $this->html->style('/global', ['inline' => false, 'weight' => 10]); ?>
    <?= $this->html->style('/main', ['inline' => false, 'weight' => 11]); ?>
    <?= $this->html->style('/css/common/fonts', ['inline' => false, 'weight' => 12]); ?>
    <?= $this->html->style('/panel', ['inline' => false, 'weight' => 13]); ?>
    <?= $this->html->style('/contact2', ['inline' => false, 'weight' => 14]); ?>
    <?= $this->html->style('/dist/build', ['inline' => false, 'weight' => 20]); ?>
    <?php echo $this->optimize->styles();?>
    <!--[if lte IE 9]><?= $this->html->style(['/ie.css']); ?><![endif]-->
    <!--[if lte IE 8]><?= $this->html->style(['/ie8.css']); ?><![endif]-->
    <!--[if lte IE 7]><?= $this->html->style(['/ie7.css']); ?><![endif]-->
    <meta property="og:type" content="website"/>
    <meta property="fb:app_id" content="202765613136579"/>
    <meta name="yandex-verification" content="1e23f2e572159f90" />
    <?php if(isset($pitch) && ((int) $pitch->private === 1) && preg_match('@/pitches/@', $_SERVER['REQUEST_URI'])): ?>
        <meta name="robots" content="noindex" />
    <?php endif?>
    <?php echo $this->Og->getOgUrl(''); ?>
    <?php if (isset($solution, $solution->images, $solution->images['solution_solutionView'])):
        if (!isset($solution->images['solution_galleryLargeSize'][0])):
            $url = 'https://godesigner.ru' . $solution->images['solution_gallerySiteSize']['weburl'];
        else:
            $url = 'https://godesigner.ru' . $solution->images['solution_gallerySiteSize'][0]['weburl'];
        endif;
        $description = isset($description) ? $description : '';
        ?>
        <?php echo $this->Og->getOgImage($url); ?>
        <?php echo $this->Og->getOgTitle($this->HtmlExtended->title($this->_request->params, $vars, true));?>
        <?php echo $this->Og->getOgDescription($description);?>
    <?php elseif (preg_match('@/posts/view@', $_SERVER['REQUEST_URI'])):
        $first_img = $post->imageurl;
        preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->full, $matches);
        if (!empty($matches[1])) {
            $first_img = $matches[1][0];
        }
        if (stripos($first_img, 'https://') === false) {
            $first_img = 'https://godesigner.ru' . $first_img;
        }
        echo '<meta content="article" property="og:type"/>';
        echo $this->Og->getOgDescription($post->short);
        echo $this->Og->getOgTitle($post->title);
        echo $this->Og->getOgImage($first_img);
        echo '<meta property="fb:admins" content="nyudmitriy"/>';
        echo '<meta property="fb:app_id" content="202765613136579"/>';
    ?>
    <?php
    elseif (preg_match('@/golden-fish/1@', $_SERVER['REQUEST_URI'])):
        echo '<meta content="godesigner:pitch" property="og:type"/>';
        echo $this->Og->getOgDescription('Станьте директором собственного рекламного агентства.');
        echo $this->Og->getOgTitle('«Золотая рыбка»');
        echo $this->Og->getOgImage('');
        echo '<meta property="fb:admins" content="nyudmitriy"/>';
        echo '<meta property="fb:app_id" content="202765613136579"/>';
    elseif (preg_match('@/golden-fish/2@', $_SERVER['REQUEST_URI'])):
        echo '<meta content="godesigner:pitch" property="og:type"/>';
        echo $this->Og->getOgDescription('Решайте задачи клиентов силами 35 000 дизайнеров и копирайтеров.');
        echo $this->Og->getOgTitle('«Золотая рыбка»');
        echo $this->Og->getOgImage('');
        echo '<meta property="fb:admins" content="nyudmitriy"/>';
        echo '<meta property="fb:app_id" content="202765613136579"/>';
    elseif (preg_match('@/golden-fish/3@', $_SERVER['REQUEST_URI'])):
        echo '<meta content="godesigner:pitch" property="og:type"/>';
        echo $this->Og->getOgDescription('35 000 дизайнеров и копирайтеров, которые сделают работу за вас.');
        echo $this->Og->getOgTitle('«Золотая рыбка»');
        echo $this->Og->getOgImage('');
        echo '<meta property="fb:admins" content="nyudmitriy"/>';
        echo '<meta property="fb:app_id" content="202765613136579"/>';
    elseif (preg_match('@/golden-fish@', $_SERVER['REQUEST_URI'])):
        echo '<meta content="godesigner:pitch" property="og:type"/>';
        echo $this->Og->getOgDescription('35 000 дизайнеров и копирайтеров сервиса, которые сделают работу за вас. Ваши заказы и цены — наши дизайнеры и идеи.');
        echo $this->Og->getOgTitle('«Золотая рыбка»');
        echo $this->Og->getOgImage('');
        echo '<meta property="fb:admins" content="nyudmitriy"/>';
        echo '<meta property="fb:app_id" content="202765613136579"/>';
    elseif (preg_match('@/pitches/(details|view)@', $_SERVER['REQUEST_URI'])):
        echo '<meta content="godesigner:pitch" property="og:type"/>';
        echo $this->Og->getOgDescription($pitch->description);
        echo $this->Og->getOgTitle($pitch->title);
        echo $this->Og->getOgImage('');
        echo '<meta property="fb:admins" content="nyudmitriy"/>';
        echo '<meta property="fb:app_id" content="202765613136579"/>';
    elseif (preg_match('@/questions@', $_SERVER['REQUEST_URI'])):
        if ((!empty($_GET)) && (isset($_GET['result']))):
            if ($_GET['result'] === 'dvornik'):
                echo $this->Og->getOgImage('https://godesigner.ru/img/questions/dvornik_468_246.png');
                echo $this->Og->getOgTitle('Тест «Какой ты дизайнер на самом деле» показал, что я дворник, совсем не дизайнер!');
            endif;

            if ($_GET['result'] === 'malyar'):
                echo $this->Og->getOgImage('https://godesigner.ru/img/questions/malyar_468_246.png');
                echo $this->Og->getOgTitle('Тест «Какой ты дизайнер на самом деле» показал, что я маляр, лучший кандидат в команду Тома Сойера!');
            endif;

            if ($_GET['result'] === 'master'):
                echo $this->Og->getOgImage('https://godesigner.ru/img/questions/master_468_246.png');
                echo $this->Og->getOgTitle('Тест «Какой ты дизайнер на самом деле» показал, что я Большой мастер, и выше только бог!');
            endif;

            if ($_GET['result'] === 'apollo'):
                echo $this->Og->getOgImage('https://godesigner.ru/img/questions/apollo_468_246.png');
                echo $this->Og->getOgTitle('Тест «Какой ты дизайнер на самом деле» показал, что я Аполлон, бог искусств!');
            endif;
        else:
            echo $this->Og->getOgTitle("Тест на знание основ графического дизайна");
            echo $this->Og->getOgImage('https://godesigner.ru/img/questions/general.jpg');
        endif;
        echo $this->Og->getOgDescription("Узнай, какой ты дизайнер на самом деле!");
    elseif (isset($shareEvent)):
        if (!empty($shareEvent->news->og_title)) {
            echo $this->Og->getOgTitle($shareEvent->news->og_title);
        } else {
            echo $this->Og->getOgTitle($shareEvent->news->title);
        }
        if (!empty($shareEvent->news->og_description)) {
            echo $this->Og->getOgDescription($shareEvent->news->og_description);
        } else {
            echo $this->Og->getOgDescription($shareEvent->news->short);
        }
        if (!empty($shareEvent->news->og_image)) {
            echo $this->Og->getOgImage($shareEvent->news->og_image);
        } else {
            echo $this->Og->getOgImage($shareEvent->news->imageurl);
        } else:
        echo $this->Og->getOgImage('');
        ?>
    <?php endif;?>

    <?=$this->view()->render(['element' => 'newrelic/newrelic_header'])?>
    <?=$this->view()->render(['element' => 'scripts/popup_cookies'])?>
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "WebSite",
      "url": "https://godesigner.ru/",
      "name" : "GoDesigner",
      "alternateName" : "GoDesigner.ru",
      "image": "https://godesigner.ru/icon_512.png",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://godesigner.ru/logosale?search={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "LocalBusiness",
      "description": "Сервис, на котором можно получить десятки и сотни вариантов решений ваших дизайн задач.",
      "name": "GoDesigner",
      "image": "https://godesigner.ru/icon_512.png",
      "telephone": "+7 812 648 24 12",
      "priceRange": "от 500 рублей",
      "address": {
        "@type": "PostalAddress",
        "addressCountry": "Российская Федерация",
        "addressLocality": "Санкт-Петербург",
        "postalCode": "190000",
        "streetAddress": "ул. Беринга, д. 27"
      }
    }
    </script>
</head>

<body class="<?=$this->_request->controller;?>_<?=$this->_request->action;?>">
<?php if ($this->_request->action !== 'feed'): ?>
<a target="_blank" id="feedback-link" href="http://godesigner.userecho.com/" style="width:67px;position:fixed;top:25%;z-index: 100000;left:-5px;"><img src="/img/LABEL_transparent.png" alt="Отзывы и советы"></a>
<?php endif?>
<?php echo $this->content() ?>
<div id="push-notifications"></div>
<?=$this->view()->render(['element' => 'footer'])?>
<?php
$env = lithium\core\Environment::get();
echo $this->html->script('https://vk.com/js/api/openapi.js');?>
<?php if ($env === 'development') {
    echo $this->html->script('/js/react/react-15.1.0.js', ['inline' => false, 'weight' => 8]);
    echo $this->html->script('/js/react/react-dom-15.1.0.js', ['inline' => false, 'weight' => 9]);
} else {
    echo $this->html->script('/js/react/react-15.1.0.min.js', ['inline' => false, 'weight' => 8]);
    echo $this->html->script('/js/react/react-dom-15.1.0.min.js', ['inline' => false, 'weight' => 9]);
}
if (($this->_request->params['controller'] === 'users') && ($this->_request->params['action'] === 'subscriber')) {
    echo $this->html->script('/js/jquery/jquery-1.9.1.min.js', ['inline' => false, 'weight' => 10]);
    echo $this->html->script('/js/jquery/jquery-migrate-1.1.0.min.js', ['inline' => false, 'weight' => 11]);
} else {
    echo $this->html->script('jquery-1.8.3.min.js', ['inline' => false, 'weight' => 10]);
}
?>
<?php
echo $this->html->script('jquery.validate.min', ['inline' => false, 'weight' => 12]);
echo $this->html->script('jquery.simplemodal-1.4.2.js', ['inline' => false, 'weight' => 13]);
echo $this->html->script('jquery.detectmobilebrowser.min.js', ['inline' => false, 'weight' => 14]);
echo $this->html->script('plugins', ['inline' => false, 'weight' => 15]);
echo $this->html->script('moment.min.js', ['inline' => false, 'weight' => 15]);
echo $this->html->script('app', ['inline' => false, 'weight' => 16]);
echo $this->html->script('/js/common/BaseComponent.js', ['inline' => false]);
?>
<?= $this->optimize->scripts() ?>
<?=$this->view()->render(['element' => 'popups/contact_form'])?>
<?=$this->view()->render(['element' => 'popups/social_popup'])?>
<?=$this->view()->render(['element' => 'popups/mobile_popup'])?>
<?=$this->view()->render(['element' => 'popups/email_change'])?>
<?=$this->view()->render(['element' => 'popups/decline_popup'])?>
<?=$this->view()->render(['element' => 'popups/project_delete'])?>
<?=$this->view()->render(['element' => 'scripts/ga'])?>
<?=$this->view()->render(['element' => 'scripts/ua'])?>
<?=$this->view()->render(['element' => 'newrelic/newrelic_footer'])?>

<?php
if ($env !== 'development'):?>
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js"></script>
    <script>
        var OneSignal = OneSignal || [];
    </script>
<?php endif;
echo $this->html->script('/dist/components.js', []);
?>

<script>
<?php
    if ($this->debug->isDebugInfoExists()) {
        $debugQueries = $this->debug->getDebugQueries();
        $debugQueries = $this->debug->sortQueriesByTimestamp($debugQueries);
        $totalTimeMysql = 0;
        $totalTimeRedis = 0;
        $redisCount = 0;
        foreach ($debugQueries as $query):
            if ($query['type'] === 'sql'):
                $totalTimeMysql += $this->debug->roundTime($query['elapsed_time']); elseif ($query['type'] === 'redis'):
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
</body>
</html>