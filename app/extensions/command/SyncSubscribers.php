<?php

namespace app\extensions\command;

use app\models\Pitch;
use app\models\User;
use DrewM\MailChimp\MailChimp;

class SyncSubscribers extends CronJob
{

    /**
     * @var MailChimp Клиент мейлчимпа
     */
    public $mailChimp;

    /**
     * @var int Лимит пользователей в мейлчимпе
     */
    public $maxLimit = 2000;

    /**
     * @var array Список обработанных адресов
     */
    public $processedEmails = [];

    public function run()
    {
        $this->header('Welcome to the Sync Subscribers command!');
        $this->mailChimp = new MailChimp('02887cbd428f1ae0b9849eb586382ea7-us13');
        $subscribers = [
            'Pitch.billed' => 1,
            'Pitch.published' => 1,
            'User.email_digest' => 1,
            'User.active' => 1,
            'User.confirmed_email' => 1,
            'User.subscription_status' => ['>' => 0]
        ];
        $this->iterateOverProjectsAndUpdateList($subscribers, 'Абонент');
        $simpleClients = [
            'Pitch.billed' => 1,
            'Pitch.published' => 1,
            'User.email_digest' => 1,
            'User.active' => 1,
            'User.confirmed_email' => 1,
            'User.subscription_status' => 0
        ];
        $this->iterateOverProjectsAndUpdateList($simpleClients, 'Реальный клиент');
        $draftsOnly = [
            'Pitch.billed' => 0,
            'Pitch.blank' => 0,
            'User.email_digest' => 1,
            'User.active' => 1,
            'User.confirmed_email' => 1,
        ];
        $this->iterateOverProjectsAndUpdateList($draftsOnly, 'Клиент c черновиком');
        $potentialClients = [
            'User.email_digest' => 1,
            'User.active' => 1,
            'User.confirmed_email' => 1,
            'OR' => [
                ['`User`.`isClient` = 1'],
                ['`User`.`is_company` = 1']
            ],
        ];
        $this->iterateOverUsersAndUpdateList($potentialClients, 'Потенциальный клиент');
        $this->out('List has been updated.');
    }

    /**
     * Метод проходит по списку пользователей по условиям и присваивает им роль в мейлчимпе
     *
     * @param $conditions
     * @param $role
     */
    public function iterateOverUsersAndUpdateList($conditions, $role) {
        $currentTotal = $this->getCurrentSubscribers();
        $diff = $this->maxLimit - $currentTotal;
        $this->out(sprintf('Current total is %s, need to add %s', $currentTotal, $diff));
        $i = 1;
        $limit = $diff;
        $finalConditions = $conditions;
        if(count($this->processedEmails) > 0) {
            $finalConditions = $conditions + ['User.email' => ['!=' => $this->processedEmails]];
        }
        $count = $projects = User::count([
            'conditions' => $finalConditions,
        ]);
        $this->out(sprintf('%d users found, number of pages for current limit is %d', $count, ceil($count / $limit)));
        while ($currentTotal < $this->maxLimit) {
            $this->out(sprintf('Iteration start, current I = %d, and current Limit = %d', $i, $limit));
            $users = User::all([
                'conditions' => $finalConditions,
                'limit' => $limit,
                'page' => $i,
                'order' => ['User.id' => 'desc'],
            ]);
            if (!$users) {
                $this->out('No users, exiting...');
                break;
            }
            foreach ($users as $user) {
                $projectCount = Pitch::count([
                    'conditions' => ['Pitch.blank' => 0, 'Pitch.user_id' => $user->id]
                ]);
                if($projectCount || in_array($user->email, $this->processedEmails, true)) {
                    $this->out(sprintf('User %s already processed, continuing', $user->email));
                    continue;
                }
                $this->processedEmails[] = $user->email;
                $userData = [
                    'role' => $role,
                    'email_address' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name
                ];
                $this->addOrUpdateUser($userData);
            }
            $currentTotal = $this->getCurrentSubscribers();
            $diff = $this->maxLimit - $currentTotal;
            $this->out(sprintf('Current total is %s, need to add %s', $currentTotal, $diff));
            $i++;
            $limit = $diff;
        }
        $this->out(sprintf('Total unique emails processed so far: %d', count($this->processedEmails)));
    }

    /**
     * Метод проходит по списку проектов по условиям и присваивает их создателям роль в мейлчимпе
     *
     * @param $conditions
     * @param $role
     */
    public function iterateOverProjectsAndUpdateList($conditions, $role) {
        $currentTotal = $this->getCurrentSubscribers();
        $diff = $this->maxLimit - $currentTotal;
        $this->out(sprintf('Current total is %s, need to add %s', $currentTotal, $diff));
        $i = 1;
        $limit = $diff;
        $finalConditions = $conditions;
        if(count($this->processedEmails) > 0) {
            $finalConditions = $conditions + ['User.email' => ['!=' => $this->processedEmails]];
        }
        $count = $projects = Pitch::count([
            'conditions' => $finalConditions,
            'with' => ['User']
        ]);
        $this->out(sprintf('%d projects found, number of pages for current limit is %d', $count, ceil($count / $limit)));
        while ($currentTotal < $this->maxLimit) {
            $this->out(sprintf('Iteration start, current I = %d, and current Limit = %d', $i, $limit));
            $projects = Pitch::all([
                'conditions' => $finalConditions,
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
                if(in_array($project->user->email, $this->processedEmails, true)) {
                    $this->out(sprintf('User %s already processed, skipping', $project->user->email));
                    continue;
                }
                $this->processedEmails[] = $project->user->email;
                $userData = [
                    'role' => $role,
                    'email_address' => $project->user->email,
                    'first_name' => $project->user->first_name,
                    'last_name' => $project->user->last_name
                ];
                $this->addOrUpdateUser($userData);
            }
            $currentTotal = $this->getCurrentSubscribers();
            $diff = $this->maxLimit - $currentTotal;
            $this->out(sprintf('Current total is %s, need to add %s', $currentTotal, $diff));
            $i++;
            $limit = $diff;
        }
        $this->out(sprintf('Total unique emails processed so far: %d', count($this->processedEmails)));
    }

    /**
     * Метод добавляет или обновляет информацию о пользователей
     *
     * @param $userData
     */
    public function addOrUpdateUser($userData) {
        $parameters = [
            'email_address' => $userData['email_address'],
            'status'        => 'subscribed',
            'status_if_new' => 'subscribed',
            'merge_fields' => [
                'FNAME' => $userData['first_name'],
                'LNAME' => $userData['last_name'],
                'MMERGE3' => $userData['role']
            ],
        ];
        $hash = md5($userData['email_address']);
        $result = $this->mailChimp->put("lists/055991fc8a/members/$hash", $parameters);
        $this->out(sprintf('User %s updated, current role is: %s', $userData['email_address'], $result['merge_fields']['MMERGE3']));
    }

    /**
     * Метод получает текущее количество подписчиков в списке
     *
     * @return int
     */
    public function getCurrentSubscribers()
    {
        $result = $this->mailChimp->get('lists/055991fc8a/members');
        $unSubscribed = $this->mailChimp->get('lists/055991fc8a/members', ['status' => 'unsubscribed']);
        $cleaned = $this->mailChimp->get('lists/055991fc8a/members', ['status' => 'cleaned']);
        return (int) $result['total_items'] - (int) $unSubscribed['total_items'] - (int) $cleaned['total_items'];
    }
}
