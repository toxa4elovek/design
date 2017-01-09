<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/LocalBusiness">
<head>
    <?= $this->html->charset();?>
    <?php
    $title = 'Зашёл, заполнил, получил. Go Designer — первый краудсорсинг-сервис в сфере дизайна в Рунете.';
    if ((isset($solution)) && (isset($solution->images)) && (isset($solution->images['solution_solutionView'])) && (is_object($solution->pitch)) && (is_object($solution->user))):
        $title = 'Go Designer; ' . $solution->pitch->title . '; Дизайнер: ' . $this->user->getFormattedName($solution->user->first_name, $solution->user->last_name);
    endif;
    if ((isset($post)) && (isset($post->title))):
        $title = $post->title . '; Go Designer — первый краудсорсинг-сервис в сфере дизайна в Рунете.';
    endif;
    ?>
    <title><?=$title?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="/img/icon_57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/img/icon_72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/img/icon_114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/img/icon_144.png" />
    <?= $this->html->link('Icon', 'favicon.png', ['type' => 'icon']); ?>
    <!--[if lt IE 9]><script src="js/html5.js"></script><![endif]-->
    <?= $this->html->style(['/global', '/main', '/css/common/fonts', '/panel', '/contact2']); ?>
    <?= $this->styles() ?>
    <!--[if lte IE 9]><?= $this->html->style(['/ie.css']); ?><![endif]-->
    <!--[if lte IE 8]><?= $this->html->style(['/ie8.css']); ?><![endif]-->
    <!--[if lte IE 7]><?= $this->html->style(['/ie7.css']); ?><![endif]-->
    <?php if ((isset($solution)) && (isset($solution->images)) && (isset($solution->images['solution_solutionView']))):
    if (!isset($solution->images['solution_galleryLargeSize'][0])):
        $url = 'https://godesigner.ru' . $solution->images['solution_galleryLargeSize']['weburl'];
    else:
        $url = 'https://godesigner.ru' . $solution->images['solution_galleryLargeSize'][0]['weburl'];
    endif;
    $description = '';
    ?>
    <meta property="og:image" content="<?=$url?>"/>
    <meta property="og:description" content="<?=$description?>"/>
    <?php endif;?>
    <?php if (preg_match('@/posts/view@', $_SERVER['REQUEST_URI'])):
    /*if(!isset($solution->images['solution_galleryLargeSize'][0])):
        $url = 'http://godesigner.ru' . $solution->images['solution_galleryLargeSize']['weburl'];
    else:
        $url = 'http://godesigner.ru' . $solution->images['solution_galleryLargeSize'][0]['weburl'];
    endif;
    $description = '';*/
    echo '<meta content="article" property="og:type"/>';
    echo '<meta property="og:url" content="https://godesigner.ru/posts/view/' . $post->id . '/"/>';
    echo '<meta property="og:description" content="' . str_replace('&nbsp;', ' ', strip_tags($post->short)) . '"/>';
    echo '<meta property="og:title" content="' . $post->title . '"/>';
    echo '<meta property="og:image" content="' . $post->imageurl . '"/>';
    echo '<meta property="fb:admins" content="nyudmitriy"/>';
    echo '<meta property="fb:app_id" content="202765613136579"/>';
    ?>
    <?php endif;?>
</head>
<body style="background: none;background-color:white;">
<?php echo $this->content() ?>
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-9235854-5']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>
</body>
</html>