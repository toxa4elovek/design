<?php
namespace app\extensions\social;

use \lithium\net\http\Service;

class VKAPI extends AbstractAPI {

    /**
     * @var string переменная для хранения токена доступа к странице
     */
    public $accessToken = 'f7cf9c75292ca989950d58be5f7fcc727f2ac74911c45c1f2e2fadb62422d5670114177b8dbc0c8b8182a';

    public function getAccessToken() {
        return $this->accessToken;
    }

    /**
     * Метод для публикации сообщения в ленту страницы vk
     *
     * @param array $data
     * @return mixed
     */
    public function postMessageToPage(Array $data) {
        $config = array(
            'persistent' => false,
            'scheme'     => 'https',
            'host'       => 'api.vk.com',
            'encoding'   => 'UTF-8',
        );
        $service = new Service($config);
        $data['access_token'] = $this->getAccessToken();
        $data['v'] = '5.34';
        $data['owner_id'] = '-36153921';
        $data['from_group'] = '1';
        if(isset($data['picture'])) {
            $data['attachments'] = $data['picture'];
        }
        $result = $service->post('method/wall.post', $data);
        $decoded =  json_decode($result, true);
        if(isset($decoded['response']['post_id'])) {
            return $decoded['response']['post_id'];
        }else {

            var_dump($result);
            return false;
        }
    }

}