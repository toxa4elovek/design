<div style="padding: 5px 0;">
    <?php switch ($moderation->reason) {
        case 'plagiat':
            $reason = 'плагиат';
            break;
        case 'template':
            $reason = 'использование шаблонов';
            break;
        case 'other':
            $reason = 'другое';
            break;
        case 'critique':
            $reason = 'публичную критику';
            break;
        case 'link':
            $reason = 'ссылку';
            break;
        default:
            $reason = 'просто так';
            break;
    }
    switch ($moderation->penalty) {
        case 0:
            $penalty = 'без штрафа';
            break;
        case 1:
            $penalty = 'заблокирован';
            break;
        case 2:
            $penalty = 'заблокирован на 30 дней';
            break;
        case 3:
            $penalty = "заблокирован в проекте <a href=\"/pitches/view/$moderation->pitch_id\" target='_blank'>$moderation->pitch_id</a>";
            break;
        default:
            $penalty = 'бан ' . (int) $moderation->penalty . ' дней';
            break;
    }
    $modelData = unserialize($moderation->model_data);
    if ($moderation->model == '\app\models\Comment') {
        $model = 'Удаление комментария';
        $postDate = date('d.m.y H:i', strtotime($modelData['created']));
        $messageInfo = 'message_info1';
        $user = \app\models\User::first($moderation->model_user);
        $commentAuthor = $this->user->getFormattedName($user->first_name, $user->last_name);
        $panel = '<div class="' . $messageInfo . '" style="margin: 20px 40px 20px 0;">
                    <a href="/users/view/' . $moderation->model_user . '">' .
                    $this->avatar->show(['id' => $moderation->model_user]) .
                    '</a>
                    <a href="/users/view/' . $moderation->model_user . '" data-comment-to="' . $commentAuthor . '" class="replyto">
                        <span>' . $commentAuthor . '</span><br />
                        <span style="font-weight: normal;">' . $postDate . '</span>
                    </a>
                </div>';
        $text = '<p class="regular">' . $modelData['text'] . '</p>';
    } else {
        $model = 'Удаление решения';
        $file = '/img/copy-inv.png';
        if (file_exists($modelData['image'])) {
            $fileName = pathinfo($modelData['image'], PATHINFO_BASENAME);
            $file = '/solutions/deleted/' . $fileName;
        }
        $panel = '<div class="portfolio" style="float:left; width: 250px;"><div class="photo_block"><img src="' . $file . '"></div></div>';
        $text = '';
    } ?>
    <?php echo $panel;?>
    <div style="float: right; width: 360px;">
        <h2 class="regular" style="font-size: 15px; font-weight: bold;"><?php echo $model . ' за ' . $reason . ', ' . $penalty;?></h2>
        <p class="regular"><?=date('d.m.y', strtotime($moderation->created));?></p><br>
        <?php echo $text; ?>
        <?php if (!empty($moderation->explanation)): ?>
            <?php echo '<br><p class="regular" style="font-style:italic; word-wrap: break-word;">Примечание: ' . $this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($moderation->explanation) . '</p>';?>
        <?php endif; ?>
    </div>
    <div class="clr">&nbsp;</div>
<hr class="tiny-hr" style="clear: both;">
</div>
