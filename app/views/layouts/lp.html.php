<?php
/**
 * Created by PhpStorm.
 * User: apuc0
 * Date: 01.09.2017
 * Time: 14:31
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# godesigner: http://ogp.me/ns/fb/godesigner#">
    <?= $this->html->charset() ?>
    <title><?= $this->title() ?></title>
    <?= $this->scripts() ?>
    <meta name="description" content="Решайте задачи клиентов силами дизайнеров и копирайтеров сервиса. Зарабатывайте на разнице гонораров. Мы вернем деньги, если идеи не понравятся.">
    <meta name="viewport" content="width=1024"/>
    <link rel="apple-touch-icon" href="/img/icon_57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/img/icon_72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/img/icon_114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/img/icon_144.png" />
    <!--[if lt IE 9]><script src="js/html5.js"></script><![endif]-->
    <?= $this->html->style('/global.css', []); ?>
    <?= $this->html->style('/css/lp/main_lp.css', []); ?>
    <?= $this->html->style('/css/common/fonts.css', []); ?>
    <?= $this->html->style('/css/lp/panel.css', []); ?>
    <?= $this->html->style('/css/common/buttons.css', []); ?>
    <?= $this->html->style('/css/common/clear.css', []); ?>
    <?= $this->html->style('/css/common/backgrounds.css', []); ?>
    <?= $this->html->style('/css/pages/golden-fish.css', []); ?>

    <!--[if lte IE 9]><link rel="stylesheet" type="text/css" href="/ie.css" />
    <![endif]-->
    <!--[if lte IE 8]><link rel="stylesheet" type="text/css" href="/ie8.css" />
    <![endif]-->
    <!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="/ie7.css" />
    <![endif]-->
</head>
<body class="pages_goldenfish">
<?php if ($this->_request->action !== 'feed'): ?>
    <a target="_blank" id="feedback-link" href="http://godesigner.userecho.com/" style="width:67px;position:fixed;top:25%;z-index: 100000;left:-5px;"><img src="/img/LABEL_transparent.png" alt="Отзывы и советы"></a>
<?php endif?>
<?= $this->content() ?>
<?= $this->html->style('/css/lp/new-css.css', []); ?>
<?= $this->html->script('/js/lp/new-scripts.js', []); ?>
</body>
</html>
