<?php
namespace app\extensions\social;

use \lithium\net\http\Service;

class FacebookAPI extends AbstractAPI {

    /**
     * @var string переменная для хранения токена доступа к странице
     */
    public $accessToken = 'CAAC4agxXZBsMBAGHKxbBpuj5VxUTqIfYC1UJEie7Krc6ZCNqZCZC8RU3nuGShZCT3jt0joJQitVp8h9aTuVotxzhiFONZC6ST8cAKmomDwEVTWwre6qWJXUh0ZCzCPDkRm7dRXZB3H6ZAkihieQif0oIpAMWHURDgpHAu3zmfCZC7qUb9jwZCpI7hnVEipXFL1fPC0KDAVi7eEDvxD81b7OlVnl';

    /**
     * Метод для получения токена доступа
     *
     * @return string
     */
    public function getAccessToken() {
        return $this->accessToken;
    }

    /**
     * Метод для публикации сообщения в ленту страницы фейсбука
     *
     * @param array $data
     * @return mixed
     */
    public function postMessageToPage(Array $data) {
        $config = array(
            'persistent' => false,
            'scheme'     => 'https',
            'host'       => 'graph.facebook.com',
            'encoding'   => 'UTF-8',
        );
        $service = new Service($config);
        $data['access_token'] = $this->getAccessToken();
        $result = $service->post('v2.3/160482360714084/feed', $data);
        return json_decode($result, true);
    }

}