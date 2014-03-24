<?php
function mb_basename($file)
{
    return end(explode('/',$file));
}?>

<div class="wrapper pitchpanel login">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>

    <div class="middle">
        <div class="middle_inner_gallery" style="padding-top:25px">
        <div id="pitch-title" style="height:36px;margin-bottom:5px;">
            <div class="breadcrumbs-view" style="width: 840px; margin: 30px 0 20px 0; float:left;">
                <a href="/pitches">Все питчи /</a> <a href="/pitches/view/<?=$pitch->id?>"><?=$pitch->title?></a>
            </div>
        </div>

        <div class="clr" style="width: 840px; margin-bottom: 30px;">
            <?=$this->view()->render(array('element' => 'pitch-info/infotable'), array('pitch' => $pitch))?>
        </div>


<div class="details-cont">

    <ul class="tabs-curve group">
        <li style="z-index: 3;">
            <?=$this->html->link('Решения', array('controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id), array('class' => 'selected menu-toggle', 'data-page' => 'gallery'))?>
        </li>
        <li class="active" style="z-index: 2;">
            <?=$this->html->link('Бриф', array('controller' => 'pitches', 'action' => 'details', 'id' => $pitch->id), array('class' => 'menu-toggle', 'data-page' => 'brief'))?>
        </li>
        <!-- <li style="z-index: 1;"><a href="#three">Дизайнеры</a></li> -->
    </ul>

    <nav class="other_nav_gallery clear">
        <?php
        if((!$this->user->isPitchOwner($pitch->user_id)) && ($pitch->status < 1) && ($pitch->published == 1)):?>
            <a href="/pitches/upload/<?=$pitch->id?>" class="button" style="font-family:Arial,sans-serif;color:#ffffff;display:block;float:right;margin-right:20px; margin-top: -45px; width:155px">предложить решение</a>
            <?php elseif(($pitch->status == 1) && ($pitch->awarded == 0)):?>
            <img src="/img/status1.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Идет выбор победителя"/>
            <?php elseif(($pitch->status == 1) && ($pitch->awarded != 0)):?>
            <img src="/img/winner-selected.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Победитель выбран"/>
            <?php elseif($pitch->status == 2):?>
            <img src="/img/status2.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Питч завершен"/>
        <?php endif;?>
    </nav>

            <div class="Center clr" style="margin-top: 20px;">
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
        <?=$this->view()->render(array('element' => 'pitchcommentform'), array('pitch' => $pitch))?>
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
<?=$this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), 'http://surfingbird.ru/share/share.min.js', 'http://assets.pinterest.com/js/pinit.js', 'jquery.hover.js', 'jquery-ui-1.8.17.custom.min.js', 'jcarousellite_1.0.1.js', 'jquery.timeago.js', 'jquery.scrollto.min.js', 'pitches/details.js'), array('inline' => false))?>
<?=$this->html->style(array('/view', '/messages12', '/pitches12', '/details', '/pitch_overview'), array('inline' => false))?>