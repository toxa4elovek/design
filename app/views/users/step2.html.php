<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('header' => 'header2'))?>

    <script>var currentUserId = <?= (int) $this->user->getId(); ?>;</script>

    <div class="conteiner">
        <section>
            <div class="menu">
                <?=$this->view()->render(array('element' => 'step-menu'))?>
            </div>
        </section>
        <section>
            <div style="margin-left: 50px;">
                <?=$this->view()->render(array('element' => 'complete-process/filtersmenu'), array('link' => ($solution->step == 4) ? 2 : 3))?>
            </div>
            <?=$this->view()->render(array('element' => '/complete-process/stepmenu-designer'), array('solution' => $solution, 'step' => $step, 'type' => $type))?>
        </section>
        <section>
            <div class="center_block messages_gallery"  style="margin:35px 0 0 63px !important">
                <?php if ($solution->pitch->category_id == 7):?>
                    <?php if($type == 'designer'):?>
                    <span class="regular">Со дня определения победителя у заказчика есть 10 дней для получения полного объема работ, запрошенного в брифе.</span>
                    <?php elseif($type == 'client') :?>
                    <span class="regular">Со дня определения победителя у вас есть 10 дней для получения полного объема работ, запрошенного в брифе. Если вас все устраивает, пожалуйста, завершите питч.</span>
                    <?php endif;?>
                <?php else: ?>
                    <?php if($type == 'designer'):?>
                    <span class="regular">Пожалуйста, загрузите эскизы в экранном разрешении (RGB, 72 dpi, JPG, GIF, PDF). Если у вас несколько документов, заархивируйте их в один ZIP файл. У заказчика есть право на внесение 3 поправок до запроса исходных файлов. Если для этого вам потребуется более 24 часов, пожалуйста, сообщите об этом в комментариях. Успехов!</span>
                    <?php elseif($type == 'client') :?>
                    <span class="regular">У вас есть право на внесение 3 поправок в течение <?=$solution->pitch->category->default_timelimit?> дней до запроса исходных файлов. Если вы удовлетворены макетами, пожалуйста, нажмите кнопку &laquo;Одобрить макеты&raquo; внизу страницы.</span>
                    <?php endif;?>
                <?php endif; ?>

                <div class="comment" style="margin-left:0px;">
                    <h4>Комментарии</h4>
                    <?php if(($solution->step < 3) && ($solution->pitch->status < 2)):?>
                    <form id="wincomment" method="post" action="/users/step2/<?=$solution->id?>.json" enctype="multipart/form-data">
                        <textarea id="newComment" name="text" style="margin:10px 0 0 0;">@<?=$this->user->getFormattedName($messageTo->first_name, $messageTo->last_name); ?>,</textarea>
                        <div style="position: relative;">
                            <input type="file" name="file[]" multiple="multiple" class="wincommentfileupload" />
                            <input id="fakebutton" type="button" style="position: absolute; z-index: 4; top: 0; left: 0; width: 185px; height: 23px; font-size: 12px;" value="Выбрать файлы">
                            <input type="submit" class="button" value="Отправить" style="width:185px;height:49px;margin:10px 0 0 0;padding:0;float:right">
                            <ul id="filelist" style="margin-top: 40px;"></ul>
                        </div>
                    </form>
                    <?php endif;?>
                </div>

                <div class="clr"></div>
                <div class="separator" style="margin-top: 10px;"></div>
                <?php foreach($comments as $comment):
                if($comment->type == 'designer'):
                    $class ="message_info1";
                elseif ($comment->user->isAdmin) :
                    $class = 'message_info4';
                else :
                    $class = 'message_info2';
                endif;
                ?>
                <section>
                    <div class="message_inf">
                        <div class="<?=$class?>" style="margin-top:20px;margin-left:0;">
                            <?php if ($comment->user->isAdmin):?>
                            <?php //$this->avatar->show($comment->user->data());?>
                            <?php else:?>
                            <a href="/users/view/<?=$comment->user->id?>">
                                <?=$this->avatar->show($comment->user->data());?>
                            </a>
                            <?php endif;?>
                            <a href="#" data-comment-id="<?=$comment->id?>" data-comment-to="<?=$this->user->getFormattedName($comment->user->first_name, $comment->user->last_name)?>" class="replyto">
                                <?php if(!$comment->user->isAdmin):?>
                                <span><?=$this->user->getFormattedName($comment->user->first_name, $comment->user->last_name)?></span><br/>
                                <?php else:?>
                                <span>GoDesigner</span><br/>
                                <?php endif;?>
                                <span style="font-weight: normal;"><?=date('d.m.y H:i', strtotime($comment->created))?></span></a>
                            <div class="clr"></div>
                        </div>
                    </div>
                    <div class="message_inf2" style="margin-bottom: 10px;">
                        <div class="message_text2">
                            <span class="regular comment-container"><?php echo $this->brief->linkemail($this->brief->eee($comment->originalText)); ?></span>
                        </div>
                    </div>

                    <?php
                    if(!empty($comment->images)):
                        if(isset($comment->images['file'][0])) :
                            foreach($comment->images['file'] as $file):?>
                                <div class="file_link">
                                <?php if (empty($file['originalbasename'])):?>
                                    <a class="file-link-attach" href="<?=$file['weburl']?>"><?=$file['basename']?></a>
                                <?php else:?>
                                    <a class="file-link-attach" href="<?= str_replace('files/', 'files/1', $file['weburl']);?>"><?=$file['basename']?></a>
                                <?php endif;?>
                                    <!--a href="" class="marker"><img src="/img/marker10.png" /></a-->
                                </div>
                                <?php endforeach;
                        else :?>
                            <div class="file_link">
                            <?php if (empty($comment->images['file']['originalbasename'])):?>
                                <a class="file-link-attach" href="<?=$comment->images['file']['weburl']?>"><?=$comment->images['file']['basename']?></a>
                            <?php else:?>
                                <a class="file-link-attach" href="<?= str_replace('files/', 'files/1', $comment->images['file']['weburl']);?>"><?=$comment->images['file']['basename']?></a>
                            <?php endif;?>
                                <!--a href="" class="marker"><img src="/img/marker10.png" /></a-->
                            </div>
                            <?php endif;

                        /*foreach($comment->images['file'] as $file):?>
                       <div class="file_link">
                           <a href="<?=$file['weburl']?>"><?=basename($file['filename'])?></a>
                           <!--a href="" class="marker"><img src="/img/marker10.png" /></a-->
                       </div>
                       <?php endforeach;*/
                    endif;
                    ?>

                    <div style="width:810px;float:right;margin-top:6px;margin-right:5px;padding-bottom:2px;height:18px;">
                        <?php if (($comment->touch != '0000-00-00 00:00:00') && ($this->user->isAdmin() || ($this->user->isCommentAuthor($comment->user_id)))): ?>
                        <div class="comment-touch">Просмотрено <?=date('H:i, d.m.y', strtotime($comment->touch));?></div>
                        <?php endif; ?>
                        <div class="toolbar">
                        <?php
                        if(($this->user->isCommentAuthor($comment->user_id)) && (($solution->step <= 2) && ($solution->pitch->status < 2))):?>
                            <a class="delete-link-in-comment" style="float:right;" href="/wincomments/delete/<?=$comment->id?>?step=2">Удалить</a>
                        <?php elseif(($this->user->isLoggedIn()) && ((!$this->user->isCommentAuthor($comment->user_id)))):?>
                            <?php if ($this->user->isAdmin()):?>
                            <a class="delete-link-in-comment" style="float:right;" href="/wincomments/delete/<?=$comment->id?>?step=2">Удалить</a>
                            <?php endif?>
                            <a href="#" data-comment-id="<?=$comment->id?>" data-comment-to="<?=$this->user->getFormattedName($comment->user->first_name, $comment->user->last_name)?>" class="replyto reply-link-in-comment" style="float:right;">Ответить</a>
                        <?php endif;?>
                        <?php if ($this->user->isAdmin()):?>
                            <a href="#" style="float:right;" class="edit-link-in-comment" data-id="<?=$comment->id?>" data-text="<?=htmlentities($comment->originalText, ENT_COMPAT, 'utf-8')?>">Редактировать</a>
                        <?php endif?>
                        </div>
                    </div>

                    <div class="clr"></div>

                    <div class="hiddenform" style="display:none">
                        <section><form style="margin-bottom: 25px;" action="/comments/edit/<?=$comment->id?>" method="post">
                            <textarea name="text" data-id="<?=$comment->id?>"></textarea>
                            <input type="button" src="/img/message_button.png" value="Отправить" class="button editcomment" style="margin:15px 15px 5px 16px; width: 200px;"><br>
                            <span style="margin-left:25px;" class="supplement3">Нажмите Esс, чтобы отменить</span>
                            <div class="clr"></div>
                        </form>
                        </section>
                    </div>

                    <div class="separator" style="margin-top: 0px;"></div>
                </section>
                <?php endforeach;?>
                <?php if($type == 'designer'):?>
                <div class="buttons">
                    <div class="back spanned" style="margin-bottom:10px;">
                        <?=$this->html->link('<img src="/img/back.png" /><br />
                            <span>Назад</span>', array('controller' => 'users', 'action' => 'step1', 'id' => $solution->id), array('escape' => false))?>
                    </div>
                    <div class="continue spanned" style="margin-bottom:10px;">
                    <?php if ($solution->pitch->category_id == 7): ?>
                        <?php if($solution->step < 4):?>
                        <p><img src="/img/continue.png" /><br />
                            <span style="font: normal 18px/21px 'RodeoC',sans-serif; margin: 10px 0 0; display: inline-block; color: #BABABA; text-transform: uppercase;">Продолжить</span></p>
                        <?php else:?>
                        <?=$this->html->link('<img src="/img/proceed.png" /><br />
                            <span>Продолжить</span>', array('controller' => 'users', 'action' => 'step4', 'id' => $solution->id), array('escape' => false))?>
                        <?php endif;?>
                    <?php else: ?>
                        <?php if($solution->step < 3):?>
                        <p><img src="/img/continue.png" /><br />
                            <span style="font: normal 18px/21px 'RodeoC',sans-serif; margin: 10px 0 0; display: inline-block; color: #BABABA; text-transform: uppercase;">Продолжить</span></p>
                        <?php else:?>
                        <?=$this->html->link('<img src="/img/proceed.png" /><br />
                            <span>Продолжить</span>', array('controller' => 'users', 'action' => 'step3', 'id' => $solution->id), array('escape' => false))?>
                        <?php endif;?>
                    <?php endif; ?>
                    </div>
                </div>
                <?php elseif((($type == 'client') || ($this->user->isAdmin())) &&  ($solution->step < 3)):?>
                <div class="buttons">
                    <div class="verify spanned" style="margin-right: 0px;">
                    <?php if ($solution->pitch->category_id == 7): ?>
                        <?=$this->html->link('<img src="/img/proceed.png" /><br />
                            <span style="">Одобрить работу</span>', array('controller' => 'users', 'action' => 'step4', 'id' => $solution->id, 'confirm' => 'confirm'), array('escape' => false))?>
                    <?php else: ?>
                        <?php if(($nofiles == false) || ($this->user->isAdmin())):?>
                        <?=$this->html->link('<img src="/img/proceed.png" /><br />
                            <span style="">Одобрить макеты</span>', array('controller' => 'users', 'action' => 'step3', 'id' => $solution->id, 'confirm' => 'confirm'), array('escape' => false, 'id' => 'confirm'))?>
                        <?php else:?>
                            <a href="#" id="nofile"><img src="/img/proceed.png"><br>
                            <span>Одобрить макеты</span></a>
                        <?php endif;?>
                    <?php endif; ?>
                    </div>
                </div>
                <?php endif;?>
            </div>
            <?=$this->view()->render(array('element' => '/complete-process/rightblock'), array('solution' => $solution, 'type' => $type))?>
            <div class="clr"></div>
        </section>
    </div>
    <div class="conteiner-bottom"></div>
</div><!-- .wrapper -->
<div id="important-confirm" class="popup-final-step" style="display:none">
    <h3>Вы уверены, что одобряете макеты?</h3>
    <p>Эта процедура является окончательной, и в дальнейшем вы не сможете изменить своё решение. Нажав «Да, одобряю» вы подтверждаете, что автор решения выполнил все необходимые доработки, и готовы приступить к получению рабочих файлов, указанных в брифе. На следующей стадии вы не сможете вносить исправления в файлы. За справкой обратитесь <a href="/answers/view/63" target="_blank">к разделу помощи</a> или <a href="/pages/contacts" target="_blank">напишите нам.</a></p>
    <div style="margin-top: 40px;" class="final-step-nav wrapper">
        <input style="width:167px" type="submit" class="button second popup-close" value="Нет, отменить">
        <input style="width:167px" type="submit" class="button" id="confirmWinner" value="Да, одобряю">
    </div>
</div>

<div id="nofiles-warning" class="popup-final-step" style="display:none">
    <h3>Дизайнер не прикрепил макеты!</h3>
    <p>В этом разделе вы должны утвердить макеты в jpg и убедиться, что больше не нужно вносить поправок. Пока автор решения не загрузил файлы через этот раздел, вы не сможете перейти в следующий и запросить рабочие документы, указанные в брифе.<br/>
        За справкой обратитесь <a href="/answers/view/63" target="_blank">к разделу помощи</a> или <a href="/pages/contacts" target="_blank">напишите нам.</p>
    <div style="margin-top: 40px;" class="final-step-nav wrapper">
        <input style="width:167px" type="submit" class="button popup-close" value="OK!">
    </div>
</div>

<div id="loading-overlay" class="popup-final-step" style="display:none;width:353px;text-align:center;text-shadow:none;">
    <div style="margin-top: 15px; margin-bottom:20px; color:#afafaf;font-size:14px"><span id="progressbar">0%</span></div>
    <div id="progressbarimage" style="text-align: left; padding-left: 6px; padding-top: 1px; padding-right: 6px; height: 23px; background: url('/img/indicator_empty.png') repeat scroll 0px 0px transparent; width: 341px;">
        <img id="filler" src="/img/progressfilled.png" style="width:1px" height="22">
    </div>
    <div style="color: rgb(202, 202, 202); font-size: 14px; margin-top: 20px;">Пожалуйста, используйте эту паузу<br> с пользой для здоровья!</div>
</div>

<?=$this->html->script(array('jquery-ui-1.8.17.custom.min.js', 'jquery.iframe-transport.js', 'jquery.fileupload.js', 'users/step2'), array('inline' => false))?>
<?=$this->html->style(array('/view', '/messages12', '/pitches12', '/pitches2', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css',), array('inline' => false))?>