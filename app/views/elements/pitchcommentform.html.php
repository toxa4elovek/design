<script>
var pitchNumber = <?php echo $pitch->id; ?>;
var currentUserId = <?php echo (int)$this->session->read('user.id'); ?>;
var currentUserName = '<?=$this->nameInflector->renderName($this->session->read('user.first_name'), $this->session->read('user.last_name'))?>';
var isCurrentAdmin = <?php echo ((int)$this->session->read('user.isAdmin') || \app\models\User::checkRole('admin')) ? 1 : 0 ?>;
var isCurrentExpert = <?php echo (in_array($this->session->read('user.id'), $expertsIds)) ? 1 : 0; ?>;
var isClient = <?php echo ((int)$this->session->read('user.id') == $pitch->user->id) ? 1 : 0; ?>;
</script>
<div class="messages_gallery">
    <?php
    if(

    (($pitch->status > 0) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true) && (($this->session->read('user.id') == $pitch->user_id) || (in_array($this->session->read('user.id'), $expertsIds)) || (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))) || ($this->session->read('user.isAdmin')))) ||
    (($pitch->status == 0) && ($pitch->published == 1) && ((strtotime($this->session->read('user.silenceUntil')) < time()) === true))

    && ($this->session->read('user.id'))
    ):?>
        <script>var allowComments = true;</script>
        <section>
            <div class="all_messages">
            <div class="clr"></div>
            </div>
            <div class="separator" style="width: 810px; margin-left: 30px;"></div>
            <div class="comment" id="comment-anchor">
            <?php if ((($this->session->read('user.id') == $pitch->user_id) || (in_array($this->session->read('user.id'), $expertsIds)) || (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81))) || ($this->session->read('user.isAdmin')))):
            $buttonText = 'Отправить';
                $publicComment = 1; ?>
                Оставьте комментарий всем участникам
            <?php else:
                $buttonText = 'Отправить вопрос';
                $publicComment = 0; ?>
                Задайте вопрос заказчику
            <?php endif; ?>
            </div>
            <input type="hidden" value="<?=$pitch->category_id?>" name="category_id" id="category_id">
            <form class="createCommentForm" method="post" action="/comments/add">
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
                <textarea id="newComment" name="text"></textarea>
                <input type="hidden" value="" name="solution_id">
                <input type="hidden" value="" name="comment_id">
                <input type="hidden" value="<?=$pitch->id?>" name="pitch_id">
                <input type="submit" style="margin-left:16; width: 200px;" data-is_public="<?php echo $publicComment?>" class="button createComment" value="<?php echo $buttonText; ?>" src="/img/message_button.png" />
                <div class="clr"></div>
            </form>
        </section>
    <?php else:?>
    <script>var allowComments = false;</script>
    <?php endif?>
    <!-- start: Pitch Comments -->
    <section class="pitch-comments isField">
        <div class="ajax-loader"></div>
    <!-- end: Pitch Comments -->
    </section>
</div>
<!-- Moderation Popups -->
<?php if ( (in_array($this->session->read('user.id'), array(32, 4, 5, 108, 81)) || ($this->session->read('user.isAdmin') == 1)) ):?>
    <?=$this->view()->render(array('element' => 'moderation'))?>
<?php endif; ?>