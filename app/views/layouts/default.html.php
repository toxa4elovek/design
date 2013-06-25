<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/LocalBusiness">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# godesigner: http://ogp.me/ns/fb/godesigner#">
	<?= $this->html->charset();?>
    <?php
    $title = 'Лого, сайт и дизайн от всего креативного интернет сообщества';
    if((isset($solution)) && (isset($solution->images)) && (isset($solution->images['solution_solutionView'])) && (is_object($solution->pitch)) && (is_object($solution->user))):
        $title = 'Go Designer; ' . $solution->pitch->title . '; Дизайнер: ' . $this->nameInflector->renderName($solution->user->first_name, $solution->user->last_name);
    endif;
    if((isset($post)) && (isset($post->title))):
        $title = $post->title . '; Лого, сайт и дизайн от всего креативного интернет сообщества';
    endif;
    if((strtolower($this->_request->params['controller']) == 'pages') && ($this->_request->params['action'] == 'view') && ($this->_request->params['args'][0] == 'howitworks')):
        $title = 'Как это работает';
    endif;
    echo '<!-- ' . ($this->_request->params['controller']) . ' -->';
    echo '<!-- ' . ($this->_request->params['action']) . ' -->';
    echo '<!-- ' . ($this->_request->params['args'][0]) . ' -->';
    if(($this->_request->params['controller'] == 'pages') && ($this->_request->params['action'] == 'view') && ($this->_request->params['args'][0] == 'to_designers')):
        $title = 'Дизайнерам';
    endif;
    if(($this->_request->params['controller'] == 'pages') && ($this->_request->params['action'] == 'contacts')):
        $title = 'Контакты';
    endif;
    if((($this->_request->params['controller'] == 'posts')) && ($this->_request->params['action'] == 'index')):
        $title = 'Блог';
    endif;
    if(($this->_request->params['controller'] == 'Pitches') && ($this->_request->params['action'] == 'index')):
        $title = 'Все питчи';
    endif;
    if(($this->_request->params['controller'] == 'pitches') && ($this->_request->params['action'] == 'create')):
        $title = 'Создание питча';
    endif;
    if(($this->_request->params['controller'] == 'pitches') && (($this->_request->params['action'] == 'details') || ($this->_request->params['action'] == 'viewsolution') || ($this->_request->params['action'] == 'view'))):
        $title = $pitch->title;
    endif;
    if((($this->_request->params['controller'] == 'answers')) && ($this->_request->params['action'] == 'index')):
        $title = 'Помощь';
    endif;
    if((($this->_request->params['controller'] == 'answers')) && ($this->_request->params['action'] == 'view')):
        $title = $answer->title;
    endif;
    if((($this->_request->params['controller'] == 'posts')) && ($this->_request->params['action'] == 'view')):
        $title = $post->title;
    endif;
    if((($this->_request->params['controller'] == 'users')) && ($this->_request->params['action'] == 'registration')):
        $title = 'Зарегистрироваться';
    endif;
    if((($this->_request->params['controller'] == 'users')) && ($this->_request->params['action'] == 'login')):
        $title = 'Войти';
    endif;
    if((($this->_request->params['controller'] == 'pages')) && ($this->_request->params['action'] == 'view') && ($this->_request->params['args'][0] == 'about')):
        $title = 'О проекте';
    endif;
    $title .= ' | GoDesigner'
    ?>
	<title><?=$title?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="/img/icon_57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/img/icon_72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/img/icon_114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/img/icon_144.png" />
	<?= $this->html->link('Icon', 'favicon.png', array('type' => 'icon')); ?>
	<!--[if lt IE 9]><script src="js/html5.js"></script><![endif]-->
	<?= $this->html->style(array('/global', '/main', '/fonts', '/panel', '/contact2')); ?>
	<?= $this->styles() ?>
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
    <?php endif;?>
    <?php if(preg_match('@/posts/view@', $_SERVER['REQUEST_URI'])):
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
    <?php endif;?>
    <?php if(preg_match('@/pitches/details@', $_SERVER['REQUEST_URI'])):
        echo '<meta content="godesigner:pitch" property="og:type"/>';
        echo '<meta property="og:url" content="http://www.godesigner.ru/pitches/details/' . $pitch->id . '/"/>';
        echo '<meta property="og:description" content="' . str_replace('"', '\'', str_replace("\n\r", '', str_replace('&nbsp;', ' ', strip_tags(mb_substr($pitch->description, 0, 100, 'UTF-8') . '...')))) . '"/>';
        echo '<meta property="og:title" content="' . htmlspecialchars($pitch->title) . '"/>';
        echo '<meta property="og:image" content="http://www.godesigner.ru/img/fb_icon.jpg"/>';
        echo '<meta property="fb:admins" content="nyudmitriy"/>';
        echo '<meta property="fb:app_id" content="202765613136579"/>';
    ?>
    <?php endif;?>
</head>
<?php
$clientNotice = $this->session->read('user.attentionpitch');
$designerNotice = $this->session->read('user.attentionsolution');
$timeoutNotice = $this->session->read('user.timeoutpitch');
$showPanel = ((count($clientNotice) > 0) || (count($designerNotice) > 0) || (!is_null($timeoutNotice)));
?>
<body>
<a target="_blank" id="feedback-link" href="http://godesigner.copiny.com/" style="width:67px;position:fixed;top:25%;z-index: 100000;left:-5px;"><img src="/img/LABEL_transparent.png" alt="Отзывы и советы"></a>
<?php echo $this->content() ?>

<?=$this->view()->render(array('element' => 'footer'))?>

<?php
//echo $this->html->script(array('http://code.jquery.com/jquery-1.7.1.min.js', 'jquery.validate.min', 'plugins', 'scripts', 'app'))
echo  $this->html->script(array('jquery-1.7.1.min.js', 'jquery.validate.min', 'jquery.simplemodal-1.4.2.js', 'plugins', 'scripts', 'app'))
?>
<?= $this->scripts() ?>
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
            <div id="contactlist" style="background:url('/img/requestback.png');display:none;top:-556px;margin-left:42px;width:399px;height:140px;position:relative;z-index:15">
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
<script type="text/javascript">
    /*
    var proto = (document.location.protocol=='https:')?'https:':'http:';
    var host = proto+'//widget.copiny.com';
    document.write(unescape("%3Cscript src='" + host + "/static/js/widget.js' type='text/javascript'%3E%3C/script%3E"));
    */
</script>
<script type="text/javascript">
    /*
    var copinyWidgetOptions = {
        position: 'left',
        hostcommunity:'http://godesigner.copiny.com',
        newwindow: '0',
        type: 'question',
        color:       '#526c7d',
        border:   '#526c7d',
        round:    '1',
        title:      "\u043e\u0442\u0437\u044b\u0432\u044b - \u0441\u043e\u0432\u0435\u0442\u044b",
        cache:   "af442c92e4d3aaf6ee76a84b8c66d8fb\/af442c92e4d3aaf6ee76a84b8c66d8fb\/ejOwVXUxUHVyVXUxVLUwArPNwWwnMNsIwtbW1QYzDGGKIVJA0hSmEaQMAA--",
        community:4109
    };
    initCopinyWidget(copinyWidgetOptions);
    CopinyWidget.showTab();
    */
</script>
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