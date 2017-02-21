<?php

namespace app\controllers;

use app\extensions\mailers\UserMailer;
use app\extensions\smsfeedback\SmsUslugi;
use app\models\Addon;
use app\models\Pitch;
use app\models\TextMessage;
use app\models\Url;
use app\models\User;
use Exception;

class InvitesController extends AppController
{

    /**
     * Метод помечает доп опцию - инвайт, как успешно принятую
     *
     * @return object
     * @throws Exception
     */
    public function accept()
    {
        if ($project = Pitch::first((int) $this->request->params['id'])) {
            $addon = Addon::first(['conditions' => [
                'Addon.billed' => 1,
                'Addon.invite_id' => $this->userHelper->getId(),
                'Addon.invite_status' => 0,
                'Addon.pitch_id' => $project->id
            ]]);
            $addon->invite_status = 1;
            $addon->save();
            return $this->redirect("/pitches/details/$project->id");
        }
        throw new Exception('Public:Такого проекта не существует.', 404);
    }

    /**
     * Метод помечает доп опцию - инвайт, как успешно отвергнутую
     *
     * @return object
     * @throws Exception
     */
    public function decline()
    {
        if ($project = Pitch::first((int) $this->request->params['id'])) {
            $addon = Addon::first(['conditions' => [
                'Addon.billed' => 1,
                'Addon.invite_id' => $this->userHelper->getId(),
                'Addon.invite_status' => 0,
                'Addon.pitch_id' => $project->id
            ]]);
            $addon->invite_status = 2;
            $addon->save();
            $user = User::first($project->user_id);
            if (User::isSubscriptionActive($user->id, $user)) {
                User::fillBalance($user->id, (int) $addon->total);
            }
            return $this->redirect($this->request->env('HTTP_REFERER'));
        }
        throw new Exception('Public:Такого проекта не существует.', 404);
    }

    /**
     * Метод приглашения дизайнера в проект, проверяет баланс, создает доп-опцию инвайт, отсылает
     * почтовое сообщение и смс
     *
     * @throws Exception
     */
    public function invite()
    {
        if (User::isSubscriptionActive($this->userRecord->id, $this->userRecord)
            && (User::getBalance($this->userRecord->id) >= 100)
            && ($project = Pitch::first((int) $this->request->data['projectId']))
            && ($designer = User::first((int) $this->request->data['designerId']))
            ) {
            $data = ['pitch_id' => $project->id, 'invite_id' => $designer->id, 'billed' => 1, 'total' => 100];
            if (Addon::create($data)->save()) {
                User::reduceBalance($this->userRecord->id, (int) $data['total']);
                if (($designer->phone !== '') && ((int) $designer->phone_valid === 1)) {
                    $smsService = new SmsUslugi();
                    $shortUrl = 'https://godesigner.ru/urls/' . Url::getShortUrlFor("https://godesigner.ru/pitches/details/$project->id");
                    $message =  "Вам персональное приглашение в проект $shortUrl";
                    $params = [
                        'text' => $message
                    ];
                    $phones = [$designer->phone];
                    $respond = $smsService->send($params, $phones);
                    if (!isset($respond['smsid'])) {
                        $smsId = 0;
                    } else {
                        $smsId = $respond['smsid'];
                    }
                    $data = [
                        'user_id' => $designer->id,
                        'created' => date('Y-m-d H:i:s'),
                        'phone' => $designer->phone,
                        'text' => $message,
                        'status' => $respond['descr'],
                        'text_id' => $smsId
                    ];
                    TextMessage::create($data)->save();
                }
                $emailData = ['user' => $designer, 'pitch' => $project];
                UserMailer::newInvite($emailData);
            }
            return $this->request->data;
        }
        throw new Exception('Public:Такого пользователя не существует.', 404);
    }
}
