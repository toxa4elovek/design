<?php

namespace app\extensions\command;

use app\models\Pitch;
use app\models\User;

class OpenLetter extends CronJob
{

    public function run()
    {
        $this->header('Welcome to the OpenLetter command!');
        $pitches = Pitch::all([
            'conditions' => [
                'published' => 1,
                'blank' => 0,
                'started' => [
                    '>=' => date('Y-m-d H:i:s', time() - DAY - HOUR),
                    '<=' => date('Y-m-d H:i:s', time() - DAY),
                ],
            ],
            'with' => ['User'],
        ]);
        $result = [
            'all' => count($pitches),
            'sent' => 0,
        ];
        if ($result['all'] > 0) {
            foreach ($pitches as $pitch) {
                if (User::sendOpenLetter($pitch)) {
                    $result['sent'] ++;
                }
            }
        }
        $messages = ($result['sent'] == 1) ? ' message ' : ' messages ';
        $have = ($result['sent'] == 1) ? ' has ' : ' have ';
        $this->out($result['sent'] . $messages . 'of ' . $result['all'] . $have . 'been sent.');
    }
}
