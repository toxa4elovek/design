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
                    if((!$this->user->isPitchOwner($pitch->user_id)) && ($pitch->status < 1) && ($pitch->published == 1)):?>
                        <a href="/pitches/upload/<?=$pitch->id?>" class="button add_solution<?php echo ($this->user->designerTimeRemain()) ? ' needWait' : '';?>">предложить решение</a>
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
                        <p class="regular"><?=$this->brief->e($pitch->description)?></p>

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
                        <p class="regular"><?=$this->brief->e($pitch->fileFormatDesc)?></p>



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
                    <div style="float:left;height:20px;margin-right:15px;">
                        <div class="fb-like" data-href="http://www.godesigner.ru/pitches/details/<?=$pitch->id?>" data-send="false" data-layout="button_count" data-width="50" data-show-faces="false" data-font="arial"></div>
                    </div>
                    <div style="float:left;height:20px;width:95px;">
                        <div id="vk_like"></div>
                    </div>
                    <div style="float:left;height:20px;width:90px;">
                        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.godesigner.ru/pitches/details/<?=$pitch->id?>" data-text="Интересная задача для дизайнеров на GoDesigner" data-lang="en" data-hashtags="Go_Deer">Tweet</a>
                    </div>
                    <div style="float:left;height:20px;width:70px;">
                        <div class="g-plusone" data-size="medium"></div>
                    </div>
                    <div style="clear:both;width:300px;height:1px;"></div>
                </div>
                <?php endif?>


                <div class="separator" style="width: 620px; margin: 30px 0 15px 0;"></div>

                <a href="/pitches" class="all-pitches-link"></a>
                <a href="#" class="fav-plus" data-pitchid="<?=$pitch->id?>" id="fav" data-type="add"></a>
                <a href="/pitches/printpitch/<?=$pitch->id?>" class="print-link"></a>
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
<?=$this->view()->render(array('element' => 'popups/warning'))?>

<?=$this->html->script(array('http://userapi.com/js/api/openapi.js?' . mt_rand(100, 999), '//assets.pinterest.com/js/pinit.js', 'jquery.simplemodal-1.4.2.js', 'jquery.scrollto.min.js', 'socialite.js', 'jquery.hover.js', 'jquery.raty.min.js', 'jquery-ui-1.8.23.custom.min.js', 'jquery.timeago.js', 'kinetic-v4.5.4.min.js', 'pitches/plot.js', 'pitches/view.js', 'pitches/gallery.js'), array('inline' => false))?>
<?=$this->html->style(array('/messages12', '/pitches12', '/view', '/pitch_overview'), array('inline' => false))?>
