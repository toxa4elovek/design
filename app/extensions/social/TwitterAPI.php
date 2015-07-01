<?php
namespace app\extensions\social;

use \tmhOAuth\tmhOAuth;
use \lithium\net\http\Service;

class TwitterAPI extends AbstractAPI {

    public function getAccessToken() {}

    public function postMessageToPage(Array $data) {
        $tweet = $data['message'];
        require_once LITHIUM_APP_PATH . '/libraries/tmhOAuth/tmhOAuth.php';
        require_once LITHIUM_APP_PATH . '/libraries/tmhOAuth/tmhUtilities.php';

        $tmhOAuth = new tmhOAuth(array(
            'consumer_key' => '8r9SEMoXAacbpnpjJ5v64A',
            'consumer_secret' => 'I1MP2x7guzDHG6NIB8m7FshhkoIuD6krZ6xpN4TSsk',
            'user_token' => '513074899-IvVlKCCD0kEBicxjrLGLjW2Pb7ZiJd1ZjQB9mkvN',
            'user_secret' => 'ldmaK6qmlzA3QJPQemmVWJGUpfST3YuxrzIbhaArQ9M'
        ));
        if (!empty($data['picture'])) {
            $img = $data['picture'];
            $name = basename($img);
            $extension = image_type_to_mime_type(exif_imagetype($img));
            $tmhOAuth->request('POST', 'https://upload.twitter.com/1.1/media/upload.json', array(
                'media' => "@{$img};type={$extension};filename={$name}"
            ), true, true);
            $data = json_decode($tmhOAuth->response['response'], true);
            $code = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array(
                'status' => $tweet,
                'media_ids' => $data['media_id_string']
            ));
        } else {
            $code = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array(
                'status' => $tweet
            ));
        }
        if ($code == 200) {
            $data = json_decode($tmhOAuth->response['response'], true);
            return $data['id_str'];
        } else {
            return false;
        }
    }

    public function search($text = 'Какой ты дизайнер на самом деле') {
        require_once LITHIUM_APP_PATH . '/libraries/tmhOAuth/tmhOAuth.php';
        require_once LITHIUM_APP_PATH . '/libraries/tmhOAuth/tmhUtilities.php';
        var_dump('<pre>');
        $string = base64_encode('8r9SEMoXAacbpnpjJ5v64A:I1MP2x7guzDHG6NIB8m7FshhkoIuD6krZ6xpN4TSsk');
        $tmhOAuth = new tmhOAuth(array(
            'consumer_key' => '8r9SEMoXAacbpnpjJ5v64A',
            'consumer_secret' => 'I1MP2x7guzDHG6NIB8m7FshhkoIuD6krZ6xpN4TSsk',
            'user_token' => '513074899-IvVlKCCD0kEBicxjrLGLjW2Pb7ZiJd1ZjQB9mkvN',
            'user_secret' => 'ldmaK6qmlzA3QJPQemmVWJGUpfST3YuxrzIbhaArQ9M'
        ));
        $tmhOAuth->headers['Authorization'] = 'Basic ' . $string;
        $tmhOAuth->request('POST', 'https://api.twitter.com/oauth/request_token', array(), false);
        $data = json_decode($tmhOAuth->response['response'], true);
        var_dump($data);

        /*
        $params = array('grant_type' => 'client_credentials');
        $tmhOAuth->request('POST', 'https://api.twitter.com/oauth2/token', $params, false);
        $data = json_decode($tmhOAuth->response['response'], true);

        $bearerToken = $data['access_token'];
        var_dump($tmhOAuth->response['response']);
        /*$tmhOAuth->headers['Authorization'] = 'Bearer ' . $bearerToken;
        $params = array('rpp' => 100, 'q' => urlencode($text), 'include_entities' => true);
        $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/search/tweets.json', $params, false);
        $dataTag = json_decode($tmhOAuth->response['response'], true);
        foreach ($dataTag['statuses'] as $tweet) {
            $tweets[] = $tweet;
        }*/
        $retweetsIds = array();
        $toRetweetIds = array('615556391479214080');
        /*foreach ($tweets as $tweet) {
            if(($tweet['user']['id_str'] == '513074899') and (isset($tweet['retweeted_status']))) {
                $retweetsIds[] = $tweet['retweeted_status']['id_str'];
                continue;
            }
            if(in_array($tweet['id_str'], $retweetsIds)) {
                continue;
            }
            $toRetweetIds[] = $tweet['id_str'];
        }
        foreach($toRetweetIds as $idStr) {
            $tmhOAuth->headers['Authorization'] = 'Bearer ' . $bearerToken;
            $params = array();
            $tmhOAuth->request('POST', 'https://api.twitter.com/1.1/statuses/retweet/' . $idStr . '.json', $params, false);
            var_dump($tmhOAuth->response['response']);
        }
*/
        //var_dump($retweetsIds);
        //var_dump($toRetweetIds);
        die();
    }

}