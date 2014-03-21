<?=$this->view()->render(array('element' => 'scripts/viewsolution_init'), array('pitch' => $pitch))?>
<div class="messages_gallery">
    <?php
    if(

    (($pitch->status > 0) && ($this->user->isAllowedToComment()) && (($this->user->isPitchOwner($pitch->user_id)) || ($this->user->isExpert()) || ($this->user->isAdmin()))) ||
    (($pitch->status == 0) && ($pitch->published == 1) && ($this->user->isAllowedToComment()))

    && ($this->user->getId())
    ):?>
        <script>var allowComments = true;</script>
        <section>
            <div class="all_messages">
            <div class="clr"></div>
            </div>
            <div class="separator pre-comment-separator" style="display: none; width: 810px; margin-left: 30px;"></div>
            <div class="comment" id="comment-anchor">
            <?php if (($this->user->isPitchOwner($pitch->user_id)) || ($this->user->isExpert()) || ($this->user->isAdmin())):
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
                <textarea id="newComment" name="text"></textarea>
                <input type="hidden" value="" name="solution_id">
                <input type="hidden" value="" name="comment_id">
                <input type="hidden" value="<?=$pitch->id?>" name="pitch_id">
                <input type="submit" style="margin-left:16px; width: 200px;" data-is_public="<?php echo $publicComment?>" class="button createComment" value="<?php echo $buttonText; ?>" src="/img/message_button.png" />
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
<?php if ($this->user->isAdmin()):?>
    <?=$this->view()->render(array('element' => 'moderation'))?>
<?php endif; ?>