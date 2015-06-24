<?php
namespace app\extensions\social;

use \tmhOAuth\tmhOAuth;

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

}