<?php

namespace app\extensions\command;

use app\extensions\smsfeedback\SmsUslugi;
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
        $waitingForUpdateStatuses = ['Успешно обработано'];
        $messages = TextMessage::all(['conditions' => ['status' => $waitingForUpdateStatuses]]);
        $smsService = new SmsUslugi();
        //$reports = $smsService->reports(date('Y-m-d', time() - 10 * MINUTE), date('Y-m-d'));
        foreach ($messages as $message) {
            $details = $smsService->detailReport($message->text_id);
            $message->status = $details['descr'];
            $message->save();
            sleep(1);
        }
        $count = count($messages);
        $this->_renderFooter("$count messages updated.");
    }
}
