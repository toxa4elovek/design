<?php

namespace app\extensions\command;

use app\extensions\smsfeedback\SmsFeedback;
use app\models\TextMessage;

/**
 * Class UpdateSmsStatus
 *
 * Команда для обновления статусов смс
 * @package app\extensions\command
 */
class UpdateSmsStatus extends CronJob
{

    /**
     * Обновляем статус смс-сообщениям со статусом ожидания
     */
    public function run()
    {
        $this->_renderHeader();
        $waitingForUpdateStatuses = ['queued', 'accepted'];
        $messages = TextMessage::all(['conditions' => ['status' => $waitingForUpdateStatuses]]);
        foreach($messages as $message) {
            $response = SmsFeedback::status($message->text_id);
            list($smsId, $smsStatus) = explode(';', $response);
            $message->status = $smsStatus;
            $message->save();
        }
        $count = count($messages);
        $this->_renderFooter("$count messages updated.");
    }
}
