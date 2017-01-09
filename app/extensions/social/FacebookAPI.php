<?php
namespace app\extensions\social;

use \lithium\net\http\Service;

class FacebookAPI extends AbstractAPI
{

    /**
     * @var string переменная для хранения токена доступа к странице
     */
    public $accessToken = 'CAAC4agxXZBsMBAGHKxbBpuj5VxUTqIfYC1UJEie7Krc6ZCNqZCZC8RU3nuGShZCT3jt0joJQitVp8h9aTuVotxzhiFONZC6ST8cAKmomDwEVTWwre6qWJXUh0ZCzCPDkRm7dRXZB3H6ZAkihieQif0oIpAMWHURDgpHAu3zmfCZC7qUb9jwZCpI7hnVEipXFL1fPC0KDAVi7eEDvxD81b7OlVnl';
    public $tutAccessToken = 'CAAC5FlJobhYBALWCxwZA0ulrgmNf9BNpMq0wjdwasrPXfzWBTZBwT8xxI7ww7rRJGYusqq51019Cbk7nQ0cBBsh2HhsH4LXHSkcw9l0eRSWN68sbVVozo8vyWhpI7S0ZBlXpsqaNOCoTj8g3cnMNxIFa8pwjORmya0yu45IF3i89QZBzo6rVMTZCnZCujSJVYZD';
    /**
     * Метод для получения токена доступа
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Метод для публикации сообщения в ленту страницы фейсбука
     *
     * @param array $data
     * @return mixed
     */
    public function postMessageToPage(array $data)
    {
        $config = [
            'persistent' => false,
            'scheme'     => 'https',
            'host'       => 'graph.facebook.com',
            'encoding'   => 'UTF-8',
        ];
        $service = new Service($config);

        $data['access_token'] = $this->tutAccessToken;
        if (!isset($data['page_id'])) {
            $data['page_id'] = '160482360714084';
            $data['access_token'] = $this->getAccessToken();
        }
        $result = $service->post('v2.3/' . $data['page_id'] . '/feed', $data);
        return json_decode($result, true);
    }
}
