<?php
namespace app\extensions\social;

abstract class AbstractAPI
{

    /**
     * Метод для получения токена доступа для постинга в соц сети
     *
     * @return mixed
     */
    abstract public function getAccessToken();

    /**
     * Метод для отправки сообщения на страницу в соц сети
     *
     * @param array $data
     * @return mixed
     */
    abstract public function postMessageToPage(array $data);
}
