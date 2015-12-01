<?php

namespace app\controllers;

use \app\models\Pitch;
use \app\models\Solution;
use \app\models\Request;

/**
 * Класс, который отвечает за проверку и создание записей дизайнеров в закрытые проекты
 *
 * Class RequestsController
 * @package app\controllers
 */
class RequestsController extends AppController {

    /**
     * Выводим страничку просмотра соглашения
     *
     * @return array|object
     */
    public function sign() {
        if($pitch = Pitch::first(array('conditions' => array('Pitch.id' => $this->request->id), 'with' => array('User')))) {
            if(($pitch->private == 1) && ($this->userHelper->getId() != $pitch->user_id)) {
                $errors = false;
                if(isset($this->request->query['errors'])) {
                    $errors = true;
                }
                $pitch->applicantsCount = Solution::find('count', array('conditions' => array('pitch_id' => $this->request->id), 'fields' => array('distinct(user_id)')));
                return compact('pitch', 'errors');
            }else {
                return $this->redirect('/pitches/view/' . $this->request->id);
            }
        }
    }

    /**
     * Проверяем, сохраняем и перенаправляем
     *
     * @return object
     */
    public function create() {
        if(($this->request->data['first_name'] == '') || ($this->request->data['last_name'] == '') || (!isset($this->request->data['tos']))) {
            return $this->redirect('/requests/sign/' . $this->request->data['pitch_id']);
        }
        $trimmedFirstName = trim($this->request->data['first_name']);
        $trimmedLastName = trim($this->request->data['first_name']);
        if(empty($trimmedFirstName) || empty($trimmedLastName)) {
            return $this->redirect('/requests/sign/' . $this->request->data['pitch_id']);
        }
        $personalData = array(
            'first_name' => $trimmedFirstName,
            'last_name' => $trimmedLastName
        );
        $data = array(
            'pitch_id' => $this->request->data['pitch_id'],
            'user_id' => $this->userHelper->getId(),
            'data' => serialize($personalData),
            'created' => date('Y-m-d H:i:s'),
            'active' => 1
        );
        $request = Request::create();
        $request->set($data);

        if($request->save()) {
            return $this->redirect('/pitches/details/' . $this->request->data['pitch_id']);
        }else{
            return $this->redirect('/requests/sign/' . $this->request->data['pitch_id'] . '?errors=true');
        }
    }

}