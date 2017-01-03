<?php

namespace app\extensions\command;

use app\models\User;
use DrewM\MailChimp\MailChimp;

class SyncUnsubscribes extends CronJob
{

    public function run()
    {
        $this->header('Welcome to the Sync Unsubscribes command!');
        $mailChimp = new MailChimp('02887cbd428f1ae0b9849eb586382ea7-us13');
        $result = $mailChimp->get('lists/055991fc8a/members', ['status' => 'unsubscribed', 'count' => 500]);
        $unsubscribes = $result['members'];
        $count = 0;
        foreach ($unsubscribes as $member) {
            if (($user = User::first(['conditions' => ['User.email' => $member['email_address']]])) && ((int) $user->email_digest === 1)) {
                $user->email_digest = 0;
                $user->save(null, ['validate' => false]);
                $count++;
                $this->out(sprintf('%s address was unsubscribed', $user->email));
            }
        }
        $this->out($count . ' has been unsubscribed and synced.');
    }
}
