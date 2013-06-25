<div class="wrapper pitchpanel login">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header2'))?>

    <div class="middle">
        <div class="middle_inner_gallery" style="padding-top:25px">

            <div style="margin-left:280px;width: 560px; height:70px;margin-bottom:40px;">
                <?php if($pitch->user_id != $this->session->read('user.id') || $pitch->status > 0): ?>
                <table class="pitch-info-table" border="1">
                    <tr><td width="255" height="25" style="padding-left:5px;padding-top:5px;border-top:1px solid #c1c1c1"><span class="regular">Гонорар:</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="pitch-info-text"><?=$this->moneyFormatter->formatMoney($pitch->price, array('suffix' => 'р.-'))?></span></td>
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

            <div id="pitch-title" style="height:36px;margin-bottom:5px;">
                <div class="breadcrumbs-view" style="width:770px;float:left;">
                    <a href="/pitches">Все питчи /</a> <a href="/pitches/view/<?=$pitch->id?>"><?=$pitch->title?></a>

                </div>
                <?php if($pitch->status == 0):?>
                <?php if(!in_array($pitch->id, $this->session->read('user.faves'))):?>
                    <div style="width:36px;height:36px;float:right;margin-right: 20px;">
                        <a data-pitchid="<?=$pitch->id?>" id="fav" data-type="add" href="#" title="Добавить в избранное"><img class="fav-plus" alt="добавить в избранное" src="/img/plus 2.png"></a>
                    </div>
                    <?php else:?>
                    <div style="width:36px;height:36px;float:right;margin-right: 20px;">
                        <a data-pitchid="<?=$pitch->id?>" id="fav" data-type="remove" href="#" title="Удалить из избранного"><img class="fav-minus" alt="Удалить из избранного" src="/img/minus.png"></a>
                    </div>
                    <?php endif?><?php endif?>
            </div>

            <h1 class="largeHeading">Загрузить решение</h1>

            <form action="/pitches/uploadcopy/<?=$pitch->id?>.json" method="post" id="solution" class="add-pitch upload-form" enctype="multipart/form-data">
                <div style="float:left; width: 627px;">

                    <div class="group" style="background:none;margin-bottom:0;">
                        <p>
                            <label class="greyboldheader">Название, хедлайн или слоган</label> <a class="second" href="">(?)</a>
                            <span id="charcounter" class="greyboldheader" style="display: block; float: right; margin-right: 40px;">375</span>
                            <textarea id="charzone" class="upload-textarea" style="height:130px; width: 557px;margin-top: 8px;" name="description"></textarea>
                        </p>
                    </div>

                    <div class="uploadblock" style="float:left;width: 600px;margin-top: 10px;position:relative">
                        <input type="hidden" value="copy" name="type" id="uploadtype" />


                        <input type="file" multiple="multiple" name="solution[]" class="fileinput-button" style="display:block; opacity:0; position:relative;z-index:5; width: 190px; height: 50px;" />
                        <input type="button" class="button" style="position:absolute;z-index: 4;top:0;left:0;" value="Загрузить файлы">
                    </div>
                    <div style="clear:both;margin-bottom:10;"></div>
                    <ul id="filelist" style="margin-top: 10px;margin-bottom: 10px;">
                        <li class="fakeinput" style=" padding-top: 1px; margin-left:10px;">Файлы не выбран</li>
                    </ul>

                </div>
                <div style="float:left; width: 180px;margin-right:20px; margin-bottom: 20px;"><p class="supplement">
                    Для копирайтинга достаточно написать идею в поле, или прикрепить документ в формате DOC, PDF, RTF не больше 5 Мбт. Если ваше решение победит в питче, вы загрузите запрашиваемые заказчиком рабочие документы.
                </div>
                <div style="height:2px;clear:both;width:807px;background: url('/img/obnovleniya_line.jpg') repeat-x scroll 0 100% transparent; margin-bottom: 15px;"></div>

                <div class="tos-container supplement">
                    <label><input type="checkbox" name="tos" style="margin-right:5px;"/>Я прочитал(а) и выражаю безусловное согласие с условиями настоящего <a href="/docs/dogovor.pdf" style="text-decoration: none;">конкурсного соглашения</a>.</label>
                </div>



                <div class="group" style="background:none;">
                    <?=$this->html->link('Отмена', array('controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id), array('class' => 'button second', 'style' => 'width:80px;margin-right:40px;')); ?>
                    <input id="uploadSolution" type="submit" class="button" value="Отправить"/>
                </div>



                <a href="#uploading" style="display:none;">.</a>
                <div style="display: none;">
                    <div id="uploading" style="width:200px;height:100px;overflow:auto;">
                        Файл загружается
                    </div>
                </div>
                <a href="#invalid" style="display:none;">.</a>
                <div style="display: none;">
                    <div id="invalid" style="width:200px;height:100px;overflow:auto;">
                        Вы не заполнили все обязательные поля.
                    </div>
                </div>
            </form>
            <input type="hidden" id="redirect-value" value="/pitches/view/<?=$pitch->id?>">
        </div><!-- /solution -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div>

</div><!-- /middle_inner -->


</div><!-- /middle -->

</div><!-- .wrapper -->

<div id="loading-overlay" class="popup-final-step" style="display:none">
    <img src="/img/ajax-loader-4.gif" alt="Загрузка">
</div>


<?=$this->html->script(array('jquery-ui-1.8.17.custom.min.js', 'jquery.iframe-transport.js', 'jquery.fileupload.js', 'fancybox/jquery.mousewheel-3.0.4.pack.js', 'fancybox/jquery.fancybox-1.3.4.pack.js', 'jquery.simplemodal-1.4.2.js', 'jquery.charcount', 'pitches/upload-copy.js'), array('inline' => false))?>
<?=$this->html->style(array('/view', '/messages12', '/pitches12', '/pitch_overview', '/upload','/jquery.fancybox-1.3.4.css'), array('inline' => false))?>