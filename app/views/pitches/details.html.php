<?php
function mb_basename($file)
{
    return end(explode('/',$file));
}?>

<div class="wrapper pitchpanel login">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>

    <div class="middle">
        <div class="middle_inner_gallery" style="padding-top:25px">
            <?=$this->view()->render(array('element' => 'pitch-info/infotable'), array('pitch' => $pitch))?>

            <ul class="tabs-curve group">
                <li style="z-index: 3;">
                    <?=$this->html->link('Решения', array('controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id), array('class' => 'menu-toggle ajaxgallery', 'data-page' => 'gallery'))?>
                </li>
                <li class="active" style="z-index: 2;">
                    <?=$this->html->link('Бриф', array('controller' => 'pitches', 'action' => 'details', 'id' => $pitch->id), array('class' => 'menu-toggle ajaxgallery', 'data-page' => 'brief'))?>
                </li>
                <li style="z-index: 1;">
                    <?=$this->html->link('Участники', array('controller' => 'pitches', 'action' => 'designers', 'id' => $pitch->id), array('class' => 'menu-toggle ajaxgallery', 'data-page' => 'designers'))?>
                </li>
            </ul>
            <div class="gallery_container">
                <nav class="other_nav_gallery clear">
                    <?php
                    if(
                        (((int) $pitch->premium === 0) && (!$this->user->isPitchOwner($pitch->user_id)) && ($pitch->status < 1) && ($pitch->published == 1) && $disableUpload === false)
                        ||
                        (((int) $pitch->premium === 1) &&
                            ($pitch->status < 1) &&
                            ($pitch->published == 1) &&
                            (($this->user->isPitchOwner($pitch->user_id) === true) ||
                            ($this->user->isExpert() === true) ||
                            ($this->user->isAdmin() === true) ||
                            ($this->user->getAwardedSolutionNum() > 0)))
                    ):?>
                        <a href="/pitches/upload/<?=$pitch->id?>" class="button add_solution <?php if($this->session->read('user.confirmed_email') == '0') {echo 'needConfirm';}?> <?php echo ($this->user->designerTimeRemain($pitch)) ? ' needWait' : '';?>">предложить решение</a>
                        <?php elseif(($pitch->status == 1) && ($pitch->awarded == 0)):?>
                        <!-- <img src="/img/status1.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Идет выбор победителя"/> -->
                        <?php elseif(($pitch->status == 1) && ($pitch->awarded != 0)):?>
                        <!-- <img src="/img/winner-selected.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Победитель выбран"/> -->
                        <?php elseif($pitch->status == 2):?>
                        <!-- <img src="/img/status2.jpg" class="other-nav-right active" style="position:relative;top:-40px;margin-right: 40px;" alt="Питч завершен"/> -->
                    <?php endif;?>
                </nav>

                <div class="Center clr">
                    <div class="details">
                        <?php
                        if(
                            ((int) $pitch->premium === 1) &&
                            ((int) $pitch->status < 1) &&
                            ((int) $pitch->published === 1) &&
                            (($this->user->isPitchOwner($pitch->user_id) === true) ||
                            ($this->user->isExpert() === true) ||
                            ($this->user->isAdmin() === true) ||
                            ($this->user->getAwardedSolutionNum() > 0))
                        === false):
                        ?>
                        <div class="bigfont">
                            <h2 class="title">
                                ЭТО <a href="/answers/view/112">ПРЕМИУМ ПРОЕКТ</a>,<br>
                                ПРИНЯТЬ УЧАСТИЕ В НЁМ МОГУТ ТОЛЬКО<br>
                                ПОБЕДИТЕЛИ ПРОЕКТОВ.
                            </h2>
                        </div>
                        <p>В премиум-проекте могут предлагать решения только опытные участники, которые уже ранее одерживали победы в проектах. Подробнее об этой опции вы можете прочитать по&nbsp;<a href="/answers/view/112">ссылке</a>.</p>
                        <?php else:?>
                        <h2 class="blueheading">Название</h2>
                        <p class="regular"><?=$pitch->title?></p>

                        <?php if((!empty($pitch->industry)) && ($pitch->industry != 'N;')):
                            if($unserialized = unserialize($pitch->industry)):
                                $job_types = array(
                                    'realty' => 'Недвижимость / Строительство',
                                    'auto' => 'Автомобили / Транспорт',
                                    'finances' => 'Финансы / Бизнес',
                                    'food' => 'Еда / Напитки',
                                    'adv' => 'Реклама / Коммуникации',
                                    'tourism' => 'Туризм / Путешествие',
                                    'sport' => 'Спорт',
                                    'sci' => 'Образование / Наука',
                                    'fashion' => 'Красота / Мода',
                                    'music' => 'Развлечение / Музыка',
                                    'culture' => 'Искусство / Культура',
                                    'animals' => 'Животные',
                                    'children' => 'Дети',
                                    'security' => 'Охрана / Безопасность',
                                    'health' => 'Медицина / Здоровье',
                                    'it' => 'Компьютеры / IT');
                                $selected = array();
                                foreach ($job_types as $k => $v):
                                    if(in_array($k, $unserialized)):
                                        $selected[] = $v;
                                    endif;
                                endforeach;
                                $pitch->industry = implode($selected, '<br>');
                            endif;
                            ?>
                        <h2 class="blueheading">Вид деятельности</h2>
                        <p class="regular"><?php echo $pitch->industry?></p>
                        <?php endif;?>

                        <?php if(!empty($pitch->{'business-description'})):?>
                        <h2 class="blueheading">Описание бизнеса/деятельности</h2>
                        <div class="editor_content"><?=$this->brief->briefDetails($pitch, 'business-description')?></div>
                        <?php endif;?>

                        <h2 class="blueheading">Описание проекта</h2>
                        <div class="editor_content"><?=$this->brief->briefDetails($pitch)?></div>

                        <div class="separator" style="width:620px; margin:0;"></div>

                        <?=$this->view()->render(array('element' => 'pitch-details/' . $pitch->category_id), array('pitch' => $pitch))?>

                        <div class="separator" style="width:620px; margin:0;"></div>

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
                        <p class="regular"><?=$this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($pitch->fileFormatDesc)?></p>
                        <?php if((!empty($files)) && (count($files) > 0)):?>
                        <div class="separator" style="width:620px; margin:0;"></div>
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
                        <?php endif?>
                      <?php if($this->user->isLoggedIn()):?>
                      <div class="separator" style="width: 620px; margin:30px 0 15px;"></div>
                      <div style="height:20px;margin-top:22px;"><span id="rating_brief">Оцените бриф:</span>
                        <div style="position:relative;left:100px;bottom:20px;" id="pitch_rating" data-pitchid="<?=$pitch->id?>" data-read="<?php echo $this->session->read('user.id') ? 'false' : 'true' ?>" data-rating="<?=$rating?>"></div>
                        <div id="take-part">
                            <button class="btn btn-success" data-pitchid="<?=$pitch->id?>"><span class="glyphicon glyphicon-plus-sign"></span> Приму участие</button>
                        </div>
                    </div>
                    <?php endif?>
                    </div>
                </div>
                <div class="SideBarRight">
                    <div id="current_pitch" style="margin-top: 20px;">
                        <?php echo $this->stream->renderStream(3);?>
                    </div>
                </div>
                <?php if(($pitch->status == 0) && ($pitch->published == 1)):?>
                <!--div class="gogo">
                    <p style="font: italic 17px georgia;margin-bottom: 0px; height: 30px;"><?=$this->html->link('Есть идеи?', array('controller' => 'pitches', 'action' => 'upload', 'id' => $pitch->id), array('class' => 'idea', 'style' => 'display: block; width: 424px; height: 30px;','escape' => false))?></p>

                    <p><?=$this->html->link('УЧАСТВУЙ!', array('controller' => 'pitches', 'action' => 'upload', 'id' => $pitch->id), array('class' => 'go', 'style' => 'display:block;width:424px;', 'escape' => false))?></p>

                </div-->
                <?php endif;?>

                <?php if($pitch->private == 0):?>
                <div class="separator" style="width: 620px; margin: 30px 0 15px 0;"></div>
                <div style="">
                    <div class="social-likes" data-counters="no" data-url="https://www.godesigner.ru/pitches/details/<?=$pitch->id?>">
                        <div style="margin: 7px 0 0 9px;" class="facebook" title="Поделиться ссылкой на Фейсбуке">SHARE</div>
                        <div style="margin: 7px 0 0 7px;" class="twitter" data-via="Go_Deer">TWITT</div>
                        <div style="margin: 7px 0 0 7px;" class="vkontakte" title="Поделиться ссылкой во Вконтакте">SHARE</div>
                        <div style="margin: 7px 0 0 7px;" class="pinterest" title="Поделиться картинкой на Пинтересте">PIN</div>
                    </div>
                    <div style="clear:both;width:300px;height:1px;"></div>
                </div>
                <?php endif?>
                <div class="separator" style="width: 620px; margin: 30px 0 15px 0;"></div>

                <a data-fav="<?= $this->user->hasFavouritePitches() ?>" href="/pitches" class="all-pitches-link"></a>
                <?php if(($this->user->isLoggedIn()) && ($this->user->hasFavouritePitches()) && ($this->user->isPitchFavourite($pitch->id))):?>
                    <a href="#" class="fav-minus" data-pitchid="<?=$pitch->id?>" id="fav" data-type="remove"></a>
                <?php elseif($this->user->isLoggedIn()):?>
                    <a href="#" class="fav-plus" data-pitchid="<?=$pitch->id?>" id="fav" data-type="add"></a>
                <?php endif?>
                <?php
                if(
                    ((int) $pitch->premium === 0) ||
                    (((int) $pitch->premium === 1) &&
                        (($this->user->isPitchOwner($pitch->id) === true) ||
                    ($this->user->isAdmin() === true) ||
                    ($this->user->getAwardedSolutionNum() > 0)))
                ):
                ?>
                    <a href="/pitches/printpitch/<?=$pitch->id?>" class="print-link"></a>
                <?php endif ?>
                <a href="/pitches/details/<?= $prevpitch->id?>" class="next-pitch-link"></a>

                <div style="margin-top:15px;">
                    <section class="white" style="margin: 0 -34px">
                        <?=$this->view()->render(array('element' => 'pitchcommentform'), array('pitch' => $pitch))?>
                    </section>
                </div>
            </div><!-- /gallery_container -->
        </div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->
</div><!-- .wrapper -->

<script>
    var autosuggestUsers = <?php echo json_encode($autosuggestUsers)?>;
</script>

<?=$this->view()->render(array('element' => 'popups/warning'), array('freePitch' => $freePitch, 'pitchesCount' => $pitchesCount, 'pitch' => $pitch))?>

<?=$this->html->script(array(
    'flux/flux.min.js',
    '/js/enjoyhint.js',
    'http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999),
    '//assets.pinterest.com/js/pinit.js',
    'jquery.simplemodal-1.4.2.js',
    'jquery-plugins/jquery.scrollto.min.js',
    'socialite.js',
    'jquery.hover.js',
    'jquery.raty.min.js',
    'jquery-ui-1.11.4.min.js',
    'jquery.timeago.js',
    'social-likes.min.js',
    'konva.0.9.5.min.js',
    '/js/common/comments/UserAutosuggest.js',
    '/js/common/comments/actions/CommentsActions.js',
    'pitches/plot.js',
    'pitches/view.js',
    'pitches/gallery.js',
    'pitches/details.js'
), array('inline' => false))?>
<?=$this->html->style(array('/css/enjoyhint.css', '/messages12', '/pitches12', '/view', '/pitch_overview', '/css/social-likes_flat'), array('inline' => false))?>