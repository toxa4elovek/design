<?php

namespace app\extensions\command;

use app\extensions\smsfeedback\SmsUslugi;
use app\models\Pitch;
use app\models\TextMessage;
use app\models\User;

class NormalSmsSelectWinnerWarning extends CronJob
{

    /**
     * Комнада отправляет смс клиентам, которые согласились получать смс и у которых заканчивается приём
     * работ в течение ближайших 4-5 часов
     */
    public function run()
    {
        $this->_renderHeader();
        $arrayOfProjects = $projectsReadyForSms = [];
        $finishDateDeltaStart = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() + 4 * HOUR));
        $finishDateDeltaEnd = new \DateTime(date(MYSQL_DATETIME_FORMAT, time() + 5 * HOUR));
        $projects = Pitch::all([
            'conditions' => [
                'status' => 1,
                'awarded' => 0,
                'published' => 1,
                'category_id' => ['!=' => 20],
                'AND' => [
                    [sprintf("finishDate >=  '%s'", $finishDateDeltaStart->format(MYSQL_DATETIME_FORMAT))],
                    [sprintf("finishDate <=  '%s'", $finishDateDeltaEnd->format(MYSQL_DATETIME_FORMAT))],
                ],
            ]
        ]);
        foreach ($projects as $project) {
            $arrayOfProjects[] = $project;
        }
        $projectsReadyForSms = array_filter($arrayOfProjects, function ($project) {
            if ($project->isOkToSendSmsForFinishWinnerSelectionWarning()) {
                return true;
            }
            return false;
        });

        array_walk($projectsReadyForSms, function ($project) {
            $monthNames = [
                1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
                5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
                9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
            ];
            $monthIndex = $project->getEndOfWinnerSelectionDateTime()->format('n');
            $timeAndDateString = $project->getEndOfWinnerSelectionDateTime()->format('H:i d');
            $message = sprintf(
                '%s %s истекает время выбора победителя. GoDesigner.ru',
                $timeAndDateString,
                $monthNames[$monthIndex]
            );
            $user = User::first($project->user_id);
            $userData = unserialize($user->userdata);
            if (($userData) && (isset($userData['accept_sms'])) && ($userData['accept_sms'])) {
                $user->phone = '79817340510';
                $params = [
                'text' => $message
            ];
                $phones = [$user->phone];
                $smsService = new SmsUslugi();
                $respond = $smsService->send($params, $phones);
                if (!isset($respond['smsid'])) {
                    $smsId = 0;
                } else {
                    $smsId = $respond['smsid'];
                }
                $data = [
                'user_id' => $user->id,
                'created' => date('Y-m-d H:i:s'),
                'phone' => $user->phone,
                'text' => $params['text'],
                'status' => $respond['descr'],
                'text_id' => $smsId
            ];
                TextMessage::create($data)->save();
            }
        });

        $this->_renderFooter(sprintf('%d sms sent', count($projectsReadyForSms)));
    }
}
