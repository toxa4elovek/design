<?php

namespace app\extensions\command;

use app\models\Pitch;
use DrewM\MailChimp\MailChimp;

class SyncSubscribers extends CronJob
{

    public function run()
    {
        $this->header('Welcome to the Sync Unsubscribes command!');
        $mailChimp = new MailChimp('02887cbd428f1ae0b9849eb586382ea7-us13');
        $maxLimit = 500;
        $currentTotal = $this->getCurrentSubscribers($mailChimp);
        $diff = $maxLimit - $currentTotal;
        $this->out(sprintf('Current total is %s, need to add %s', $currentTotal, $diff));
        $i = 1;
        $limit = $diff;
        while ($currentTotal < $maxLimit) {
            $projects = Pitch::all([
                'conditions' => [
                    'Pitch.billed' => 1,
                    'Pitch.published' => 1,
                    'User.email_digest' => 1,
                    'User.active' => 1,
                    'User.confirmed_email' => 1,
                ],
                'limit' => $limit,
                'page' => $i,
                'order' => ['Pitch.billed_date' => 'desc'],
                'with' => ['User']
            ]);
            foreach ($projects as $project) {
                if ($project->user->subscription_status > 0) {
                    $role = 'Абонент';
                } else {
                    $role = 'Клиент';
                }
                $mailChimp->post("lists/055991fc8a/members", [
                    'email_address' => $project->user->email,
                    'status'        => 'subscribed',
                    'merge_fields' => [
                        'FNAME' => $project->user->first_name,
                        'LNAME' => $project->user->last_name,
                        'MMERGE3' => $role
                    ],
                ]);
            }
            $currentTotal = $this->getCurrentSubscribers($mailChimp);
            $diff = $maxLimit - $currentTotal;
            $this->out(sprintf('Current total is %s, need to add %s', $currentTotal, $diff));
            $i++;
            $limit = $diff;
        }
        $this->out('List has been updated.');
    }

    public function getCurrentSubscribers($mailChimp)
    {
        $result = $mailChimp->get('lists/055991fc8a/members');
        $unsubscribed = $mailChimp->get('lists/055991fc8a/members', ['status' => 'unsubscribed']);
        $cleaned = $mailChimp->get('lists/055991fc8a/members', ['status' => 'cleaned']);
        return (int) $result['total_items'] - (int) $unsubscribed['total_items'] - (int) $cleaned['total_items'];
    }
}
