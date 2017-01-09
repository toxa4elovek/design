<?php

namespace app\extensions\command;

use app\models\Event;
use app\extensions\storage\Rcache;

class SaveReposts extends \app\extensions\command\CronJob
{

    public function run()
    {
        Rcache::init();
        set_time_limit(10);
        $url = 'https://api.vk.com/method/wall.get?owner_id=-87581422&v=5.28';
        //Initialize the Curl Session.
        $ch = curl_init();
        //Set the Curl url.
        curl_setopt($ch, CURLOPT_URL, $url);
        //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); //timeout in seconds
        //Execute the  cURL session.
        $curlResponse = curl_exec($ch);
        //Get the Error Code returned by Curl.
        $curlErrno = curl_errno($ch);
        if ($curlErrno) {
            $curlError = curl_error($ch);
            throw new Exception($curlError);
        }
        //Close a cURL session.
        curl_close($ch);
        $response = json_decode($curlResponse, true);
        foreach ($response['response']['items'] as $post) {
            var_dump($post);
        }
        die();
    }

    private function in_array_r($needle, $haystack, $strict = false)
    {
        foreach ($haystack as $k => $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && self::in_array_r($needle, $item, $strict))) {
                return $k;
            }
        }
        return false;
    }
}
