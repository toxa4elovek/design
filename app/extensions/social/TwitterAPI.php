<?php
namespace app\extensions\social;

use \tmhOAuth\tmhOAuth;

class TwitterAPI extends AbstractAPI
{

    /**
     * Свойство для хранения внешнего апи объекта
     *
     * @var null|tmhOAuth
     */
    public $apiObject = null;

    /**
     * Конструктор, где инициализируется внешний объект
     */
    public function __construct($keys = [])
    {
        require_once LITHIUM_APP_PATH . '/libraries/tmhOAuth/tmhOAuth.php';
        if (empty($keys)) {
            $keys = [
                'consumer_key' => '8KowPOOLHqbLQPKt8JpwnLpTn',
                'consumer_secret' => 'Guna29r1BY8gEofz2amAIfPo1XcHJWNGI8Nzn6wiEwNlykAHhy',
                'user_token' => '513074899-XF4hfFeVZNBQgX7QQU0brLzbd3AxIOk1HcEQFsGl',
                'user_secret' => 'qJUuvweF3ennscQKvWPpHdxhhiDo4VRCvunpVm51SziQV'
            ];
        }
        $this->apiObject = new tmhOAuth($keys);
    }

    /**
     *  Пустышка
     */
    public function getAccessToken()
    {
    }

    /**
     * Метод для публикации твита
     *
     * @param array $data
     * @return bool
     */
    public function postMessageToPage(array $data)
    {
        $tweet = $data['message'];

        if (!empty($data['picture'])) {
            $img = $data['picture'];
            $name = basename($img);
            $extension = image_type_to_mime_type(exif_imagetype($img));
            if (class_exists('CurlFile', false)) {
                $media = new \CURLFile($img);
            } else {
                $media = "@{$img};type={$extension};filename={$name}";
            }
            $this->apiObject->user_request(array(
                'method' => 'POST',
                'url'    => 'https://upload.twitter.com/1.1/media/upload.json',
                'params' => array(
                    'media' => $media,
                ),
                'multipart' => true,
            ));
            $data = json_decode($this->apiObject->response['response'], true);
            $code = $this->apiObject->request('POST', $this->apiObject->url('1.1/statuses/update'), [
                'status' => $tweet,
                'media_ids' => $data['media_id_string']
            ]);
        } else {
            $code = $this->apiObject->request('POST', $this->apiObject->url('1.1/statuses/update'), [
                'status' => $tweet
            ]);
        }
        if ($code == 200) {
            $data = json_decode($this->apiObject->response['response'], true);
            //var_dump($this->apiObject);
            //var_dump($data['id_str']);
            return $data['id_str'];
        } else {
            echo '<pre>';
            var_dump($this->apiObject);
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
    public function search($text = 'Какой ты дизайнер на самом деле', $function)
    {
        $params = ['rpp' => 20, 'q' => urlencode($text), 'include_entities' => true];
        $this->apiObject->user_request([
            'method' => 'GET',
            'url' => $this->apiObject->url("1.1/search/tweets.json"),
            'params' => $params,
        ]);
        return $function($this->apiObject, $this);
    }

    /**
     * Метод ретвитиь твит с $id
     * @param $id
     */
    public function retweet($id)
    {
        $params = ['id' => $id];
        $this->apiObject->user_request([
            'method' => 'POST',
            'url' => $this->apiObject->url('1.1/statuses/retweet/' . $id . '.json'),
            'params' => $params,
        ]);
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

    public function access_token()
    {
        $result = $this->apiObject->apponly_request([
            'method' => 'POST',
            'url' => 'https://api.twitter.com/oauth/access_token',
            'params' => [
                'oauth_verifier' => '5WWaxQtyWl8q0Rdbh8zzzRhPAjXMGXUK',
                'oauth_token' => 'XJOlGgAAAAAAHfhHAAABTyjfe8o'
            ]
        ]);
        echo '<pre>';
        var_dump($result);
        var_dump($this->apiObject);
        var_dump($this->apiObject->response['response']);
        die();
    }

    public function request_token()
    {
        $result = $this->apiObject->user_request([
            'method' => 'POST',
            'url' => 'https://api.twitter.com/oauth/request_token',
            'params' => [
                'oauth_callback' => 'https://godesigner.ru/twitter',
            ]
        ]);
        echo '<pre>';
        var_dump($result);
        var_dump($this->apiObject);
        var_dump($this->apiObject->response['response']);
        die();
    }
}
