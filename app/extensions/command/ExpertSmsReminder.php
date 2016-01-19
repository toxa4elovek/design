<?php

namespace app\extensions\command;

use app\extensions\smsfeedback\SmsFeedback;
use app\models\Expert;
use app\models\Pitch;
use app\models\TextMessage;

class ExpertSmsReminder extends CronJob
{

    public function run()
    {
        $this->header('Welcome to the ExpertReminder command!');
        $projects = Pitch::all([
            'conditions' => [
                'expert' => 1,
                'published' => 1,
                'status' => 1,
                'awarded' => 0,
            ]
        ]);
        $projectCount = count($projects);
        $smsCount = 0;
        $this->out("Found $projectCount project(s)...");
        foreach ($projects as $project) {
            $this->out("Project #$project->id");
            $chosenExperts = unserialize($project->{'expert-ids'});
            if (time() < strtotime($project->finishDate) + (2 * DAY)) {
                foreach ($chosenExperts as $expert) {
                    if (Expert::isExpertNeedToWriteComment($project, $expert)) {
                        $message = "Здравствуйте! Требуется ваше экспертное мнение для проекта «" . $project->title . "» (#$project->id)";
                        $this->out($message);
                        $this->out("Need to send sms to expert #$expert for project $project->id");
                        $expertRecord = Expert::first(['conditions' => ['Expert.id' => $expert], 'with' => ['User']]);
                        if (($expertRecord->user->phone != '') && ($expertRecord->user->phone_valid)) {
                            $response = SmsFeedback::send($expertRecord->user->phone, $message);
                            list($smsStatus, $smsId) = explode(';', $response);
                            $data = [
                                'user_id' => $expertRecord->user->id,
                                'created' => date('Y-m-d H:i:s'),
                                'phone' => $expertRecord->user->phone,
                                'text' => $message,
                                'status' => $smsStatus,
                                'text_id' => $smsId
                            ];
                            TextMessage::create($data)->save();
                            $smsCount++;
                        }
                        break;
                    }
                }
            }
        }
        $this->out("$smsCount sms has been sent");
    }
}
