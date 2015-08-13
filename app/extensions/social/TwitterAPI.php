<?php
namespace app\extensions\social;

use \tmhOAuth\tmhOAuth;

class TwitterAPI extends AbstractAPI {

    /**
     * Свойство для хранения внешнего апи объекта
     *
     * @var null|tmhOAuth
     */
    public $apiObject = null;

    /**
     * Конструктор, где инициализируется внешний объект
     */
    public function __construct() {
        require_once LITHIUM_APP_PATH . '/libraries/tmhOAuth/tmhOAuth.php';
        $this->apiObject = new tmhOAuth(array(
            'consumer_key' => '7ynjxKFuCuK4a7KE1ay1DwQbU',
            'consumer_secret' => 'aKNZum1E2wMq3BE5IUwfGP4eNVxO2ulF5OdwWqmTAUYhYLDmTH',
            'user_token' => '513074899-qh6ee3WgG0tbzLyKli1M2OhJ6S30ev0NLWQWoQNT',
            'user_secret' => '2YBrHFlu2gpyyDV9NE2uEk8xeoKzUguYVYW4BlMSE72yB'
        ));
    }

    /**
     *  Пустышка
     */
    public function getAccessToken() {}

    /**
     * Метод для публикации твита
     *
     * @param array $data
     * @return bool
     */
    public function postMessageToPage(Array $data) {
        $tweet = $data['message'];

        if (!empty($data['picture'])) {
            $img = $data['picture'];
            $name = basename($img);
            $extension = image_type_to_mime_type(exif_imagetype($img));
            $this->apiObject->request('POST', 'https://upload.twitter.com/1.1/media/upload.json', array(
                'media' => "@{$img};type={$extension};filename={$name}"
            ), true, true);
            $data = json_decode($this->apiObject->response['response'], true);
            $code = $this->apiObject->request('POST', $this->apiObject->url('1.1/statuses/update'), array(
                'status' => $tweet,
                'media_ids' => $data['media_id_string']
            ));
        } else {
            $code = $this->apiObject->request('POST', $this->apiObject->url('1.1/statuses/update'), array(
                'status' => $tweet
            ));
        }
        if ($code == 200) {
            $data = json_decode($this->apiObject->response['response'], true);
            return $data['id_str'];
        } else {
            return false;
        }
    }

    /**
     * Метод ищет твит, а потом вызываем $function для использования результатов поиска
     *
     * @param string $text
     * @param $function
     * @return mixed
     */
    public function search($text = 'Какой ты дизайнер на самом деле', $function) {
        $params = array('rpp' => 20, 'q' => urlencode($text), 'include_entities' => true);
        $this->apiObject->user_request(array(
            'method' => 'GET',
            'url' => $this->apiObject->url("1.1/search/tweets.json"),
            'params' => $params,
        ));
        return $function($this->apiObject, $this);
    }

    /**
     * Метод ретвитиь твит с $id
     * @param $id
     */
    public function retweet($id) {
        $params = array('id' => $id);
        $this->apiObject->user_request(array(
            'method' => 'POST',
            'url' => $this->apiObject->url('1.1/statuses/retweet/' . $id . '.json'),
            'params' => $params,
        ));
    }

    /**
     * Метод добавляет твит с $id в избранное
     * @param $id
     */
    /*public function favorite($id) {
        $params = array('id' => $id);
        $this->apiObject->user_request(array(
            'method' => 'POST',
            'url' => $this->apiObject->url('1.1/favorites/create.json'),
            'params' => $params,
        ));
    }*/

}