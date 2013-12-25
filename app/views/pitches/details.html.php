<?php
function mb_basename($file)
{
    return end(explode('/',$file));
}

$expertsIds = array();
foreach($experts as $expert) {
	$expertsIds[] = $expert->user_id;
}
?>

<div class="wrapper pitchpanel login">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>

    <div class="middle">
        <div class="middle_inner_gallery"  style="padding-top:25px">

            <div style="margin-left:280px;width: 560px; height:70px;margin-bottom:40px;">
                <?php if($pitch->user_id != $this->session->read('user.id') || $pitch->status > 0): ?>
                <table class="pitch-info-table" border="1">
                    <tr><td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1"><span class="regular">Гонорар:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$this->moneyFormatter->formatMoney($pitch->price, array('suffix' => 'р.-'))?><?php echo ($pitch->guaranteed == 1) ? ' гарантированы' : ''; ?></span></td>
                        <td width="15"></td>
                        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Заказчик:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$this->html->link($this->nameInflector->renderName($pitch->user->first_name, $pitch->user->last_name), array('users::view', 'id' => $pitch->user->id), array('class' => 'client-linknew'))?></td></tr>
                    <tr><td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Решений:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$pitch->ideas_count ?></span></td>
                        <td width="15"></td>
                        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Был online:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=date('d.m.Y', strtotime($pitch->user->lastActionTime))?> в <?=date('H:i', strtotime($pitch->user->lastActionTime)) ?></span></td></tr>
                    <tr>
                        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Просмотры брифа:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$pitch->views?></span></td>
                        <td width="15"></td>
                        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                            <span class="regular">Срок:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($pitch->status == 0):?>
                            <span class="pitch-info-text"><?=preg_replace('@(м).*@', '$1. ', preg_replace('@(ч).*@', '$1. ', preg_replace('@(.*)(дн).*?\s@', '$1$2. ', $pitch->startedHuman)))?></span>
                            <?php elseif($pitch->status == 1):?>
                            <span class="pitch-info-text">Выбор победителя</span>
                            <?php elseif($pitch->status == 2):?>
                            <span class="pitch-info-text">Питч завершен</span>
                            <?php endif?>
                        </td>
                    </tr>
                </table>
                <?php else: ?>
                <table class="pitch-info-table" border="1">
                    <tr><td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1"><span class="regular">Гонорар:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$this->moneyFormatter->formatMoney($pitch->price, array('suffix' => 'р.-'))?></span></td>
                        <td width="15"></td>
                        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                            <table width="100%"><tr><td>
                                <td><span class="regular">Мнение эксперта <a href="http://www.godesigner.ru/answers/view/66" target="_blank">(?)</a></span></td>
                                <td style="width:86px"><a style="position: relative; top: -2px; left: -1;" class="order1" href="/pitches/addon/<?= $pitch->id?>?click=experts-checkbox"><img src="/img/order1.png" alt="заказать"></a></td>
                                </td></tr></table>
                        </td></tr>
                    <tr><td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Решений:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$pitch->ideas_count ?></span></td>
                        <td width="15"></td>
                        <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                            <table width="100%"><tr><td>

                                <span class="regular">Заполнить бриф <a href="http://www.godesigner.ru/answers/view/68" target="_blank">(?)</a></span>
                            </td><td style="width:86px">

                                <a style="position: relative; top: -2px;" class="order1" href="/pitches/addon/<?= $pitch->id?>?click=phonebrief"><img src="/img/order1.png" alt="заказать"></a>
                            </td></tr></table>
                    <tr>    </td></tr>
                    <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;"><span class="regular">Просмотры брифа:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$pitch->views?></span></td>
                    <td width="15"></td>
                    <td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1;border-bottom:1px solid #c1c1c1;">
                        <table width="100%"><tr><td>

                            <span class="regular">Срок:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($pitch->status == 0):?>
                                <span class="pitch-info-text"><?=preg_replace('@(м).*@', '$1. ', preg_replace('@(ч).*@', '$1. ', preg_replace('@(.*)(дн).*?\s@', '$1$2. ', $pitch->startedHuman)))?></span>

                                </td><td style="width:86px">
                                <a style="position: relative; top: -2px;  left: 1;" href="/pitches/addon/<?= $pitch->id?>?click=prolong" class="order2"><img src="/img/order2.png" alt="продлить"></a>
                                <?php elseif($pitch->status == 1):?>
                                <span class="pitch-info-text">Выбор победителя</span>
                                <?php elseif($pitch->status == 2):?>
                                <span class="pitch-info-text">Питч завершен</span>
                                <?php endif?>


                        </td></tr></table>
                    </td>
                    </tr>
                </table>
                <?php endif ?>
            </div>

            <div  id="pitch-title" style="height:36px;margin-bottom:5px;">
                <div class="breadcrumbs-view" style="width:770px;float:left;">
                    <a href="/pitches">Все питчи /</a> <a href="/pitches/view/<?=$pitch->id?>"><?=$pitch->title?></a>

                </div>
                <?php /* if($pitch->status == 0):?>
                <?php if(!in_array($pitch->id, $this->session->read('user.faves'))):?>
                    <div style="width:36px;height:36px;float:right;margin-right: 20px;">
                        <a data-pitchid="<?=$pitch->id?>" id="fav" data-type="add" href="#" title="Добавить в избранное"><img class="fav-plus" alt="добавить в избранное" src="/img/plus 2.png"></a>
                    </div>
                    <?php else:?>
                    <div style="width:36px;height:36px;float:right;margin-right: 20px;">
                        <a data-pitchid="<?=$pitch->id?>" id="fav" data-type="remove" href="#" title="Удалить из избранного"><img class="fav-minus" alt="Удалить из избранного" src="/img/minus.png"></a>
                    </div>
                    <?php endif?><?php endif */?>
            </div>

<div class="details-cont">

    <div class="menu">
        <ul>
            <li class="first_li">
                <?=$this->html->link('Решения', array('controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id))?>
            </li>
            <li>
                <?=$this->html->link('Бриф', array('controller' => 'pitches', 'action' => 'details', 'id' => $pitch->id), array('class' => 'selected'))?>
            </li>
        </ul>
    </div>

    <nav class="other_nav_gallery clear">
        <?php
        if(($this->session->read('user.id') != $pitch->user_id) && ($pitch->status < 1) && ($pitch->published == 1)):?>
            <a href="/pitches/upload/<?=$pitch->id?>" class="button" style="font-family:Arial,sans-serif;color:#ffffff;display:block;float:right;margin-right:20px;width:155px">предложить решение</a>
            <a class="print-link" target="_blank" href="/pitches/printpitch/<?=$pitch->id?>" style="float: right; margin-right: 250px;"><img alt="" src="/img/print_brief_button.png"></a>
            <?php elseif(($pitch->status == 1) && ($pitch->awarded == 0)):?>
            <img src="/img/status1.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Идет выбор победителя"/>
            <?php elseif(($pitch->status == 1) && ($pitch->awarded != 0)):?>
            <img src="/img/winner-selected.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Победитель выбран"/>
            <?php elseif($pitch->status == 2):?>
            <img src="/img/status2.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Питч завершен"/>
        <?php else:?>
            <a href="http://www.godesigner.ru/answers/view/78" class="button" style="font-family:Arial,sans-serif;color:#ffffff;display:block;float:right;margin-right:20px;width:178px">инструменты заказчика</a>
            <a class="print-link" href="/pitches/printpitch/<?=$pitch->id?>" style="float: right; margin-right: 250px;"><img alt="" src="/img/print_brief_button.png"></a>
        <?php endif;?>
    </nav>

            <div class="Center">
                <div class="details">
                    <h2 class="blueheading">Название</h2>
                    <p class="regular"><?=$pitch->title?></p>

                    <?php if(!empty($pitch->industry)):?>
                    <h2 class="blueheading">Вид деятельности</h2>
                    <p class="regular"><?=$pitch->industry?></p>
                    <?php endif;?>

                    <?php if(!empty($pitch->{'business-description'})):?>
                    <h2 class="blueheading">Описание бизнеса/деятельности</h2>
                    <p class="regular"><?=$this->brief->e($pitch->{'business-description'})?></p>
                    <?php endif;?>

                    <h2 class="blueheading">Описание питча</h2>
                    <p class="regular" style="word-break: break-word;"><?=$this->brief->e($pitch->description)?></p>

                    <div class="separator" style="width:620px"></div>

                    <?=$this->view()->render(array('element' => 'pitch-details/' . $pitch->category_id), array('pitch' => $pitch))?>

                    <div class="separator" style="width:620px"></div>

                    <?php if(($pitch->category_id != 7) && ($pitch->category_id != 1)):?>
                        <h2 class="blueheading">Можно ли дополнительно использовать материал из банков с изображениями или шрифтами?</h2>
                        <?php if($pitch->materials):?>
                        <p class="regular">да, допустимая стоимость одного изображения — <?=$pitch->{'materials-limit'}?> Р.-</p>
                        <?php else:?>
                        <p class="regular">нет</p>
                        <?php endif;?>
                    <?php elseif($pitch->category_id == 1):?>
                        <h2 class="blueheading">Можно ли дополнительно использовать платные шрифты?</h2>
                        <?php if($pitch->materials):?>
                        <p class="regular">да, допустимая стоимость — <?=$pitch->{'materials-limit'}?> Р.-</p>
                        <?php else:?>
                        <p class="regular">нет</p>
                        <?php endif;?>
                    <?php endif;?>

                    <h2 class="blueheading">Формат файла:</h2>
                    <?php if(!empty($pitch->fileFormats)):?>
                    <p class="regular">
                        <?= implode(', ', unserialize($pitch->fileFormats))?>
                    </p>
                    <?php endif;?>
                    <p class="regular"><?=$this->brief->e($pitch->fileFormatDesc)?></p>



                    <?php if((!empty($files)) && (count($files) > 0)):?>
                    <div class="separator" style="width:620px"></div>
                    <h2 class="blueheading">Прикрепленные документы:</h2>

                    <ul>
                        <?php foreach($files as $file):?>
                        <li class="regular">
                        <?php if (empty($file->originalbasename)):?>
                            <a style="font-size:15px;text-decoration: none;" href="<?=$file->weburl?>"><?=mb_basename($file->filename)?></a><br>
                        <?php else:?>
                            <a style="font-size:15px;text-decoration: none;" href="/pitchfiles/1<?=mb_basename($file->filename);?>"><?=$file->originalbasename;?></a><br>
                        <?php endif;?>
                        <?php if(!empty($file->{'file-description'})):?>
                        <p style="font-size:15px;text-decoration: none;"><?=$file->{'file-description'}?></p>
                        <?php endif;?>
                        </li>
                        <?php endforeach?>
                    </ul>
                    <?php endif?>

                </div>
            </div>


            <div class="SideBarRight">

                <div id="current_pitch" style="margin-top: 20px;">
                    <?php echo $this->stream->renderStream(3);?>
                    <!--script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
                    <script>
                    <script>
                        new TWTR.Widget({
                            version: 2,
                            type: 'search',
                            search: 'godesigner',
                            interval: 30000,
                            title: 'Твиттер лента',
                            subject: 'Go Designer',
                            width: 180,
                            height: 500,
                            theme: {
                                shell: {
                                    background: '#45464f',
                                    color: '#e1e1e1'
                                },
                                tweets: {
                                    background: '#f3f3f3',
                                    color: '#45464f',
                                    links: '#ff575d'
                                }
                            },
                            features: {
                                scrollbar: true,
                                loop: true,
                                live: true,
                                behavior: 'all'
                            }
                        }).render().start();
                    </script-->
                </div>

            </div>
            <?php if(($pitch->status == 0) && ($pitch->published == 1)):?>
            <!--div class="gogo">
                <p style="font: italic 17px georgia;margin-bottom: 0px; height: 30px;"><?=$this->html->link('Есть идеи?', array('controller' => 'pitches', 'action' => 'upload', 'id' => $pitch->id), array('class' => 'idea', 'style' => 'display: block; width: 424px; height: 30px;','escape' => false))?></p>

                <p><?=$this->html->link('УЧАСТВУЙ!', array('controller' => 'pitches', 'action' => 'upload', 'id' => $pitch->id), array('class' => 'go', 'style' => 'display:block;width:424px;', 'escape' => false))?></p>

            </div-->
            <?php endif;?>

    <?php if($pitch->private == 0):?>
    <div class="separator" style="width: 620px; margin-bottom: 15px; margin-top: 30px;"></div>
    <div style="">
        <div style="float:left;height:20px;margin-right:15px;">
            <div class="fb-like" data-href="http://www.godesigner.ru/pitches/details/<?=$pitch->id?>" data-send="false" data-layout="button_count" data-width="50" data-show-faces="false" data-font="arial"></div>
        </div>
        <div style="float:left;height:20px;width:95px;">
            <div id="vk_like"></div>
        </div>
        <div style="float:left;height:20px;width:90px;">
            <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.godesigner.ru/pitches/details/<?=$pitch->id?>" data-text="Интересная задача для дизайнеров на GoDesigner" data-lang="en" data-hashtags="Go_Deer">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
        <div style="float:left;height:20px;width:70px;">
            <div class="g-plusone" data-size="medium"></div>
        </div>
        <div style="float:left;height:20px;width:80px;">
            <a target="_blank" class="surfinbird__like_button" data-surf-config="{'layout': 'common', 'width': '120', 'height': '20'}" href="http://surfingbird.ru/share">Серф</a>
        </div>
        <div style="clear:both;width:300px;height:1px;"></div>
    </div>
    <?php endif?>


    <div class="separator" style="width: 620px; margin-bottom: 15px; margin-top: 30px;"></div>

    <a href="/pitches" style="margin-right:20px;" class="all-pitches-link"><img src="/img/all_pitches.png" alt=""></a>
    <a href="#" style="margin-right:20px;" class="fav-plus" data-pitchid="<?=$pitch->id?>" id="fav" data-type="add"><img src="/img/plusb.png" alt=""></a>
    <a href="/pitches/printpitch/<?=$pitch->id?>" style="margin-right:20px;" class="print-link"><img src="/img/print_brief_button.png" alt=""></a>
    <a href="/pitches/details/<?= $prevpitch->id?>" style="margin-right:20px;" class="next-pitch-link"><img src="/img/next.png" alt=""></a>


    <div style="margin-top:15px;">

    <section class="white" style="margin: 0 -34px">
        <div class="messages_gallery">
            <?php
            if(

                (($pitch->status > 0) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true) && (($this->session->read('user.id') == $pitch->user_id) || (in_array($this->session->read('user.id'), $expertsIds)) || (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))) || ($this->session->read('user.isAdmin')))) ||
                (($pitch->status == 0) && ($pitch->published == 1) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true))

                && ($this->session->read('user.id'))
            ):?>
                <section>
                    <div class="all_messages">
                        <div class="clr"></div>
                    </div>
                    <div class="separator" style="width: 810px; margin-left: 30px;"></div>

                    <div class="comment" id="comment-anchor"><h2 class="blueheading">Что непонятно? Спросите меня прямо здесь!</h2></div>
                    <input type="hidden" value="<?=$pitch->category_id?>" name="category_id" id="category_id">
                    <form id="createCommentForm" method="post" action="/comments/add">
                        <div style="display:none; background: url(/img/tooltip-bg-top-stripe.png) no-repeat scroll 0 0 transparent !important; padding: 4px 0 0 !important; height: auto; width: 205px; position: absolute; z-index: 2147483647;" id="tooltip-bubble">
                            <div style="background:url(/img/tooltip-bottom-bg2.png) no-repeat scroll 0 100% transparent; padding: 10px 10px 22px 16px;height:100px;">
                                <div style="" id="tooltipContent" class="supplement3">
                                    <p>Укажите номер комментируемого варианта, используя хештег #. Например:
                                        #2, нравится!<br>
                                        Обратитесь к автору решения, используя @. Например:<br>
                                        @username, спасибо!
                                    </p>
                                </div>
                            </div>
                        </div>
                        <textarea id="newComment" name="text">@<?=$this->nameInflector->renderName($pitch->user->first_name, $pitch->user->last_name)?>, </textarea>
                        <input type="hidden" value="" name="solution_id">
                        <input type="hidden" value="" name="comment_id">
                        <input type="hidden" value="<?=$pitch->id?>" name="pitch_id">
                        <input type="hidden" value="/pitches/details/<?= $pitch->id ?>" name="from">
                        <input type="submit" style="margin-left:16px; width: 200px;" id="createComment" class="button" value="Отправить" src="/img/message_button.png" />
                        <div class="clr"></div>
                    </form>
                    <?php   if(isset($comments) && count($comments) > 0):?>
                        <div class="separator" style="width: 810px; margin-left: 30px; margin-top: 25px;"></div>
                    <?php endif;?>
                </section>
                <?php else:?>
                    <?php   if(isset($comments) && count($comments) > 0):?>
                        <div style="width: 810px; margin-left: 30px; " id="t1" class="separator"></div>
                    <?php endif;?>
                <?php endif?>
            <?php
            if(isset($comments) && count($comments) > 0):?>
                <div class="comment" style="width:35%;float:left;"><h2 class="blueheading">Комментарии</h2></div>
                <div class="checkbox-input" style="margin-right:45px;"><input type="checkbox" id="client-only-toggle" style="font-size:14px;vertical-align: text-top;" /> <span class="supplement">показывать только комментарии заказчика</span></div>
                <div style="clear:both;"></div>

                <?php
                $totalComments = count($comments);
                $i = 0;
                foreach($comments as $comment):
                    if(($pitch->category_id == 7) || ($pitch->private)):
                        if(($pitch->user_id != $this->session->read('user.id')) && ($comment->user_id != $this->session->read('user.id')) && (!in_array($this->session->read('user.id'), $expertsIds))) {
                            echo '<!-- ' . $comment->id . '-->';
                            if((!in_array($comment->solution_id, $mySolutionList) && $comment->user_id == $pitch->user_id) && ($comment->solution_id != 0) && (!in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81)))) {
                                continue;
                            }
                            if(($comment->user_id != $pitch->user_id) && (!$comment->user->isAdmin) && ($comment->user_id != $this->session->read('user.id')) && (!in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81)))) {
                                continue;
                            }
                            if(($comment->reply_to != $this->session->read('user.id')) && ($comment->reply_to != 0) && (!in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81)))) {
                                continue;
                            }
                            $matches = array();
                            if((preg_match_all('@(#\d+)\D@', $comment->text, $matches, PREG_PATTERN_ORDER)) && (!in_array($this->session->read('user.id'), $expertsIds))) {
                                //echo '<!-- ex' . var_dump($expertsIds) . '-->';
                                $array = array();
                                foreach($matches[1] as $match):
                                    $array[] = $match;
                                endforeach;
                                //echo '<!-- ar' . var_dump($array) . '-->';

                                $noSolutions = true;
                                foreach($mySolutionNumList as $mySolutionNum):
                                    if(in_array($mySolutionNum, $array)) :
                                        $noSolutions = false;
                                        break;
                                    endif;
                                endforeach;
                                if(($noSolutions) && (!in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81)))):
                                    continue;
                                endif;

                            }else {
                                //echo '<!--' . var_dump($comment->text) . '-->';
                            }
                        }else {
                            echo '<!-- ' . $comment->id . '-->';
                        }
                    endif;

                    $i += 1;
                    if($pitch->user_id == $comment->user->id) {
                        $class = 'message_info2';
                    }elseif ($comment->user->isAdmin) {
                        $class = 'message_info4';
                    }elseif((in_array($comment->user->id, $expertsIds))) {
                        $class = 'message_info5';
                    }else {
                        $class = 'message_info1';
                    }
                    ?>
                    <section data-id="<?=$comment->id?>" <?php if($comment->user_id == $pitch->user_id) echo 'data-type="client"'?> <?php if($comment->user_id != $pitch->user_id) echo 'data-type="designer"'?>>
                        <div class="<?=$class?>" style="margin-top:20px;">
                            <?php if ($comment->user->isAdmin):?>
                            <?php //$this->avatar->show($comment->user->data());?>
                            <?php else:?>
                            <a href="/users/view/<?=$comment->user->id?>">
                                <?=$this->avatar->show($comment->user->data());?>
                            </a>
                            <?php endif;?>
                            <a href="#" data-comment-id="<?=$comment->id?>" data-comment-to="<?=$this->nameInflector->renderName($comment->user->first_name, $comment->user->last_name)?>" class="replyto">
                                <?php if(!$comment->user->isAdmin):?>
                                <span><?=$this->nameInflector->renderName($comment->user->first_name, $comment->user->last_name)?></span><br/>
                                <?php else:?>
                                <span>GoDesigner</span><br/>
                                <?php endif;?>
                                <span style="font-weight: normal;"><?=date('d.m.y H:i', strtotime($comment->created))?></span></a>
                            <div class="clr"></div>
                        </div>
                        <div data-id="<?=$comment->id?>" class="message_text" style="margin-top:15px;">

                            <span class="regular comment-container"><?php echo $this->brief->stripemail($comment->text)?></span>
                        </div>
                        <div style="width:810px;float:right;margin-top: 6px;margin-right: 95px;padding-bottom: 2px;height:18px;">
                            <div class="toolbar">
                                <?php
                                if($this->session->read('user.id') == $comment->user_id):?>

                                    <?= $this->html->link('Удалить', array('controller' => 'comments', 'action' => 'delete', 'id' => $comment->id), array('style' => "float:right;", "class" => "delete-link-in-comment"));?>
                                    <a href="#" style="float:right;" class="edit-link-in-comment" data-id="<?=$comment->id?>" data-text="<?=htmlentities($comment->originalText, ENT_COMPAT, 'utf-8')?>">Редактировать</a>
                                    <?php elseif(($this->session->read('user.id') > 0) && (($this->session->read('user.id') != $comment->user_id))):?>
                                    <?php if (($this->session->read('user.isAdmin') == 1) || \app\models\User::checkRole('admin')):?>
                                        <?= $this->html->link('Удалить', array('controller' => 'comments', 'action' => 'delete', 'id' => $comment->id), array('style' => "float:right;", "class" => "delete-link-in-comment"));?>
                                        <a href="#" style="float:right;" class="edit-link-in-comment" data-id="<?=$comment->id?>" data-text="<?=htmlentities($comment->originalText, ENT_COMPAT, 'utf-8')?>">Редактировать</a>

                                        <?php endif?>
                                    <?php
                                    if(

                                        (($pitch->status > 0) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true) && (($this->session->read('user.id') == $pitch->user_id) || (in_array($this->session->read('user.id'), $expertsIds)) || ($this->session->read('user.isAdmin')))) ||
                                        (($pitch->status == 0) && ($pitch->published == 1) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true))

                                        && ($this->session->read('user.id'))
                                    ):?>
                                        <a href="#" data-comment-id="<?=$comment->id?>" data-comment-to="<?=$this->nameInflector->renderName($comment->user->first_name, $comment->user->last_name)?>" class="replyto reply-link-in-comment" style="float:right;">Ответить</a>
                                        <?php if(!$comment->user->isAdmin):?>
                                            <?php endif?>
                                        <a href="#" data-comment-id="<?=$comment->id?>" data-url="/comments/warn.json" class="warning-comment warn-link-in-comment" style="float:right;">Пожаловаться</a>
                                        <?php endif;?>
                                    <?php endif;?>
                            </div></div>
                        <div class="clr"></div>
                        <div class="hiddenform" style="display:none">
                            <section><form style="margin-bottom: 25px;" action="/comments/edit/<?=$comment->id?>" method="post">
                                <!--div id="tooltip-bubble" style="background: url(&quot;/img/tooltip-bg-top-stripe.png&quot;) no-repeat scroll 0px 0px transparent ! important; padding: 4px 0px 0px ! important; height: auto; width: 205px; position: absolute; z-index: 2147483647; top: 799.6px; left: 220.5px; display: none;">
                                    <div style="background:url(/img/tooltip-bottom-bg2.png) no-repeat scroll 0 100% transparent; padding: 10px 10px 22px 16px;height:100px;">
                                        <div class="supplement3" id="tooltipContent" style="">
                                            <p>Укажите номер комментируемого варианта, используя хештег #. Например:
                                                #2, нравится!<br>
                                                Обратитесь к автору решения, используя @. Например:<br>
                                                @username, спасибо!
                                            </p>
                                        </div>
                                    </div>
                                </div-->
                                <textarea name="text" data-id="<?=$comment->id?>"></textarea>
                                <input type="button" src="/img/message_button.png" value="Отправить" class="button editcomment" style="margin-left:16px;margin-bottom:5px; width: 200px;"><br>
                                <span style="margin-left:25px;" class="supplement3">Нажмите Esс, чтобы отменить</span>
                                <div class="clr"></div>
                            </form>
                            </section>
                        </div>

                    </section>

                    <?php if($i != $totalComments): ?>
                    <div class="separator" style="width: 810px; margin-left: 30px;"></div>
                    <?php endif;?>
                    <?php endforeach;

            endif?>
        </div>
    </section>
</div>

        </div><!-- /solution -->

    </div>

</div><!-- /middle_inner -->


</div><!-- /middle -->

</div><!-- .wrapper -->
<div id="popup-warning-comment" class="popup-warn generic-window" style="display:none">
    <p style="margin-top:120px;">Вы можете пожаловаться, если обнаружены грубые высказывания, реклама, спам, контент для взрослых, ссылки на работы, сделки вне Go Designer, копирование чужой работы или плагиат. В последнем случае важно предоставить ссылку на оригинал. Важно однако учитывать, что в питче с одним брифом некоторая степень похожести работ допускается. Подробнее <a href="http://www.godesigner.ru/answers/view/38" target="_blank">тут</a></p>
    <p>Пожалуйста, прокомментируйте суть жалобы:</p>
    <textarea id="warn-comment" class="placeholder" placeholder="ВАША ЖАЛОБА"></textarea>
    <div class="final-step-nav wrapper" style="margin-top:20px;"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="sendWarnComment" value="Да, подтвердить"></div>
</div>
<!-- Start: Tooltips -->
<div style="display:none;">
<?php if((count($solutions) > 0) && ($pitch->published == 1)):?>
    <?php foreach($solutions as $solution):	?>
        <?php if($pitch->private != 1):
            if($pitch->category_id == 7):
                //
            ?>
            <?php else:?>
                <?php if(!isset($solution->images['solution_galleryLargeSize'][0])):?>
                    <input type="hidden" rel="#<?=$solution->num?>" data-src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>">
                <?php else:?>
                    <input type="hidden" rel="#<?=$solution->num?>" data-src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'][0])?>">
                <?php endif?>
            <?php endif?>
        <?php else:?>
            <?php if($pitch->category_id == 7):
                //
            ?>
            <?php else:?>
                <?php if(($pitch->user_id == $this->session->read('user.id')) || (in_array($this->session->read('user.id'), $expertsIds)) || (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))) || ($solution->user_id == $this->session->read('user.id'))):?>
                    <?php if(!isset($solution->images['solution_galleryLargeSize'][0])):?>
                        <input type="hidden" rel="#<?=$solution->num?>" data-src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'])?>">
                    <?php else:?>
                        <input type="hidden" rel="#<?=$solution->num?>" data-src="<?=$this->solution->renderImageUrl($solution->images['solution_galleryLargeSize'][0])?>">
                    <?php endif?>
                <?php else:?>
                    <input type="hidden" rel="#<?=$solution->num?>" data-src="/img/copy-inv.png">
                <?php endif?>
            <?php endif?>
        <?php endif?>
    <?php endforeach;?>
<?php endif;?>
</div>
<!-- End: Tooltips -->
<?=$this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), 'http://surfingbird.ru/share/share.min.js', 'http://assets.pinterest.com/js/pinit.js', 'jquery.hover.js', 'jquery-ui-1.8.17.custom.min.js', 'jcarousellite_1.0.1.js', 'jquery.timeago.js', 'jquery.scrollto.min.js', 'pitches/details.js'), array('inline' => false))?>
<?=$this->html->style(array('/view', '/messages12', '/pitches12', '/details', '/pitch_overview'), array('inline' => false))?>