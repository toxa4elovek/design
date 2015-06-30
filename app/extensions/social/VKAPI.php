<?php
namespace app\extensions\social;

use \lithium\net\http\Service;

class VKAPI extends AbstractAPI {

    /**
     * @var string переменная для хранения токена доступа к странице
     */
    public $accessToken = '8f505b137c636ee0b8d9220ec2f3ef2a7a8f62c066dc55c7774480266946edffa9dcaafbb2acc69bf3d44';

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
        $data['attachments'] = $data['picture'];
        $result = $service->post('method/wall.post', $data);
        $decoded =  json_decode($result, true);
        if(isset($decoded['response']['post_id'])) {
            return $decoded['response']['post_id'];
        }else {
            return false;
        }
    }

}