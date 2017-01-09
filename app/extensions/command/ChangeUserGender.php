<?php

namespace app\extensions\command;

use \app\models\User;

class ChangeUserGender extends \app\extensions\command\CronJob
{

    public function run()
    {
        $users = User::all(['conditions' => ['facebook_uid' => ['!=' => ''], 'gender' => 0], 'order' => ['lastTimeOnline' => 'desc'], 'limit' => 100]);
        $count = count($users);
        $url = 'http://graph.facebook.com/?ids=';
        $x = 0;
        foreach ($users as $user) {
            ++$x;
            $url .= $count > $x ? $user->facebook_uid . ',' : $user->facebook_uid;
        }
        $json = file_get_contents($url);
        $fusers = json_decode($json);
        foreach ($fusers as $f) {
            $gender = 0;
            if ($f->gender === 'male') {
                $gender = 1;
            } elseif ($f->gender === 'female') {
                $gender = 2;
            }
            foreach ($users as $user) {
                if ((int) $f->id === (int) $user->facebook_uid) {
                    $user->gender = $gender;
                    break;
                }
            }
        }
        $users->save(null, ['validate' => false]);
    }
}
