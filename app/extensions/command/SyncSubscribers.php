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
        $maxLimit = 1000;
        $currentTotal = $this->getCurrentSubscribers($mailChimp);
        $diff = $maxLimit - $currentTotal;
        $this->out(sprintf('Current total is %s, need to add %s', $currentTotal, $diff));
        $i = 1;
        $limit = $diff;
        $count = $projects = Pitch::count([
            'conditions' => [
                'Pitch.billed' => 1,
                'Pitch.published' => 1,
                'User.email_digest' => 1,
                'User.active' => 1,
                'User.confirmed_email' => 1,
            ],
            'with' => ['User']
        ]);
        $this->out(sprintf('Number of pages for current limit is %d', ceil($count / $limit)));
        while ($currentTotal < $maxLimit) {
            $this->out(sprintf('Iteration start, current I = %d, and current Limit = %d', $i, $limit));
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
            if (!$projects) {
                $this->out('No projects, exiting...');
                break;
            }
            foreach ($projects as $project) {
                $role = 'Клиент';
                if ($project->user->subscription_status > 0) {
                    $role = 'Абонент';
                }
                $result = $mailChimp->post('lists/055991fc8a/members', [
                    'email_address' => $project->user->email,
                    'status'        => 'subscribed',
                    'merge_fields' => [
                        'FNAME' => $project->user->first_name,
                        'LNAME' => $project->user->last_name,
                        'MMERGE3' => $role
                    ],
                ]);
                $this->out(sprintf('Result: %s, full text: %s', $result['title'], $result['detail']));
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
        $unSubscribed = $mailChimp->get('lists/055991fc8a/members', ['status' => 'unsubscribed']);
        $cleaned = $mailChimp->get('lists/055991fc8a/members', ['status' => 'cleaned']);
        return (int) $result['total_items'] - (int) $unSubscribed['total_items'] - (int) $cleaned['total_items'];
    }
}
