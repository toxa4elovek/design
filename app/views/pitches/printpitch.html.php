<?php
function mb_basename($file)
{
    return end(explode('/',$file));
};
$details = unserialize($pitch->specifics);
?>

<div class="wrapper pitchpanel login" style="background-color: white">
    <div class="middle">
        <div class="middle_inner_gallery"  style="background: white; padding-top:25px">

            <div class="details-cont">

                <div class="Center">
                    <img style="margin-top:60px" src="/img/printlogo.png" alt="godesigner" width="250">
                    <div class="details" style="margin-top:60px">
                        <h2 class="blueheading">Название</h2>
                        <p class="regular"><?=$pitch->title?></p>

                        <?php if(!empty($pitch->industry)):
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
                        <p class="regular"><?=$this->brief->e($pitch->{'business-description'})?></p>
                        <?php endif;?>

                        <h2 class="blueheading">Описание проекта</h2>
                        <p class="regular"><?=$this->brief->e($pitch->description)?></p>

                        <?=$this->view()->render(array('element' => 'print/' . $pitch->category_id), array('pitch' => $pitch))?>

                        <?php if($pitch->category_id != 7):?>
                        <h2 class="blueheading">Можно ли использовать материал из банков с изображениями</h2>
                        <?php if($pitch->materials):?>
                            <p class="regular">да, допустимая стоимость одного изображения — <?=$pitch->{'materials-limit'}?> Р.-</p>
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
                        <h2 class="blueheading">Прикрепленные документы:</h2>

                        <ul>
                            <?php foreach($files as $file):?>
                            <li class="regular">
                                <?php if (empty($file->originalbasename)):?>
                                    <a style="font:10pt/16pt 'Arial',sans-serif" href="<?=$file->weburl?>"><?=mb_basename($file->filename)?></a><br>
                                <?php else:?>
                                    <a style="font:10pt/16pt 'Arial',sans-serif" href="/pitchfiles/1<?=mb_basename($file->filename);?>"><?=$file->originalbasename;?></a><br>
                                <?php endif;?>
                                <?php if(!empty($file->{'file-description'})):?>
                                <p style="font:10pt/16pt 'Arial',sans-serif"><?=$file->{'file-description'}?></p>
                                <?php endif;?>
                            </li>
                            <?php endforeach?>
                        </ul>
                        <?php endif?>

                    </div>
                </div>

            </div>

        </div><!-- /solution -->
    </div>

</div><!-- /middle_inner -->


</div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/view', '/messages12', '/pitches12', '/pitch_overview', '/print'), array('inline' => false))?>