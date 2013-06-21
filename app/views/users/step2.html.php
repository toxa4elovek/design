<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('header' => 'header2'))?>


    <div class="conteiner" style="width:958px !important">
        <section>
            <div class="menu">
                <?=$this->view()->render(array('element' => 'step-menu'))?>
            </div>
        </section>
        <section>
            <div class="block-toggler" style="margin-left: 65px;">
                <a href="/users/solutions">все решения</a> /
                <?php if($solution->step == 4):?>
                <a href="/users/awarded" class="link">награжденные</a> /
                <?php else:?>
                <a href="/users/awarded">награжденные</a> /
                <?php endif?>
                <?php if($solution->step < 4):?>
                <a href="/users/nominated" class="link">в процессе завершения</a>
                <?php else:?>
                <a href="/users/nominated">в процессе завершения</a>
                <?php endif?>
            </div>
            <?=$this->view()->render(array('element' => '/complete-process/stepmenu-designer'), array('solution' => $solution, 'step' => $step, 'type' => $type))?>
        </section>
        <section>
            <div class="center_block"  style="margin:35px 0 0 63px !important">
                <?php if($type == 'designer'):?>
                <span class="regular">Пожалуйста, загрузите эскизы в экранном разрешении (RGB, 72 dpi, JPG, GIF, PDF). Если у вас несколько документов, заархивируйте их в один ZIP файл. У заказчика есть право на внесение 3 поправок до запроса исходных файлов. Если для этого вам потребуется более 24 часов, пожалуйста, сообщите об в комментариях. Успехов!</span>
                <?php elseif($type == 'client') :?>
                <span class="regular">У вас есть право на внесение 3 поправок в течение <?=$solution->pitch->category->default_timelimit?> дней до запроса исходных файлов. Если вы удовлетворены макетами, пожалуйста, нажмите кнопку &laquo;Одобрить макеты&raquo; внизу страницы.</span>
                <?php endif;?>

                <div class="comment" style="margin-left:0px;">
                    <h4>Комментарии</h4>
                    <?php if(($solution->step < 3) && ($solution->pitch->status < 2)):?>
                    <form method="post" action="/users/step2/<?=$solution->id?>" enctype="multipart/form-data">
                        <textarea name="text" style="margin:10px 0 0 0;"></textarea>
                        <div>
                            <input type="file" name="file[]" class="wincommentfileupload"/>
                            <input type="submit" class="button" value="Отправить" style="width:185px;height:49px;margin:0;padding:0;float:right">
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
                            <a href="#" data-comment-id="<?=$comment->id?>" data-comment-to="<?=$this->nameInflector->renderName($comment->user->first_name, $comment->user->last_name)?>" class="replyto">
                                <?php if(!$comment->user->isAdmin):?>
                                <span><?=$this->nameInflector->renderName($comment->user->first_name, $comment->user->last_name)?></span><br/>
                                <?php else:?>
                                <span>GoDesigner</span><br/>
                                <?php endif;?>
                                <span style="font-weight: normal;"><?=date('d.m.y H:i', strtotime($comment->created))?></span></a>
                            <div class="clr"></div>
                        </div>
                    </div>
                    <div class="message_inf2" style="margin-bottom: 10px;">
                        <div class="message_text2">
                            <span class="regular"><?=$this->brief->ee($comment->text)?></span>
                        </div>
                    </div>

                    <?php
                    if(!empty($comment->images)):
                        if(isset($comment->images['file'][0])) :
                            foreach($comment->images['file'] as $file):?>
                                <div class="file_link">
                                    <a class="file-link-attach" href="<?=$file['weburl']?>"><?=$file['basename']?></a>
                                    <!--a href="" class="marker"><img src="/img/marker10.png" /></a-->
                                </div>
                                <?php endforeach;
                        else :?>
                            <div class="file_link">
                                <a class="file-link-attach" href="<?=$comment->images['file']['weburl']?>"><?=$comment->images['file']['basename']?></a>
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

                    <div style="width:810px;float:right;margin-top: 6px;margin-right: 5px;padding-bottom: 2px;">
                        <?php
                        if(($this->session->read('user.id') == $comment->user_id) && (($solution->step <= 2) && ($solution->pitch->status < 2))):?>
                            <a class="delete-link-in-comment" style="float:right;" href="/wincomments/delete/<?=$comment->id?>?step=2">удалить</a>
                            <?php elseif(($this->session->read('user.id') > 0) && (($this->session->read('user.id') != $comment->user_id))):?>
                            <?php if ($this->session->read('user.isAdmin') == 1):?>
                                <a class="delete-link-in-comment" style="float:right;" href="/wincomments/delete/<?=$comment->id?>?step=2">удалить</a>
                                <?php endif?>
                            <?php endif;?>
                    </div>

                    <div class="clr"></div>
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
                        <?php if($solution->step < 3):?>
                        <p><img src="/img/continue.png" /><br />
                            <span style="font: normal 18px/21px 'RodeoC',sans-serif;
    margin: 10px 0 0;
    display: inline-block;color:#BABABA;text-transform:uppercase;">Продолжить</span></p>
                        <?php else:?>
                        <?=$this->html->link('<img src="/img/proceed.png" /><br />
                            <span>Продолжить</span>', array('controller' => 'users', 'action' => 'step3', 'id' => $solution->id), array('escape' => false))?>
                        <?php endif;?>
                    </div>
                </div>
                <?php elseif(($type == 'client') &&  ($solution->step < 3)):?>
                <div class="buttons">
                    <div class="verify spanned" style="margin-right: 0px;">
                        <?php if($nofiles == false):?>
                        <?=$this->html->link('<img src="/img/proceed.png" /><br />
                            <span style="">Одобрить макеты</span>', array('controller' => 'users', 'action' => 'step3', 'id' => $solution->id, 'confirm' => 'confirm'), array('escape' => false, 'id' => 'confirm'))?>
                        <?php else:?>
                            <a href="#" id="nofile"><img src="/img/proceed.png"><br>
                            <span>Одобрить макеты</span></a>
                        <?php endif;?>

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
<?=$this->html->script(array('jquery.simplemodal-1.4.2.js', 'users/step2'), array('inline' => false))?>
<?=$this->html->style(array('/view', '/messages12', '/pitches12', '/pitches2', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css',), array('inline' => false))?>