<?php

namespace app\controllers;

use app\models\News;

/**
 * Class NewsController
 *
 * Контроллер с пользовательскими действиями в отношении записей новостей
 *
 * @package app\controllers
 */
class NewsController extends AppController
{

    /**
     * Метод добавляет лайк для новости от имени пользователя
     * 
     * @return array
     */
    public function like()
    {
        $likes = News::increaseLike((int) $this->request->id, (int) $this->userHelper->getId());
        $result = $likes['result'];
        $likes = $likes['likes'];
        return compact('likes', 'result');
    }

    /**
     * Метод убирает лайк для новости от имени пользователя
     *
     * @return array
     */
    public function unlike()
    {
        $likes = News::decreaseLike((int) $this->request->id, (int) $this->userHelper->getId());
        $result = $likes['result'];
        $likes = $likes['likes'];
        return compact('likes', 'result');
    }

    /**
     * Метод скрывает новость ("удаляет), только для админов
     *
     * @return array
     */
    public function hide()
    {
        $result = false;
        if ($this->userHelper->isAdmin()) {
            $result = News::hideNews((int) $this->request->id);
        }
        return compact('result');
    }
}
