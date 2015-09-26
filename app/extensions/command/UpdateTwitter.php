<?php

namespace app\extensions\command;

use app\extensions\social\TwitterAPI;
use app\extensions\storage\Rcache;
use app\models\Wp_post;

class UpdateTwitter extends CronJob {

    public function run() {
        Rcache::init();
        $api = new TwitterAPI();
        $api->search('godesigner.ru', function($object) {
            $data = json_decode($object->response['response'], true);
            $censoredTweets = array();
            $censoredTweets['statuses'] = array();
            $minTimestamp = 1893355200;
            foreach ($data['statuses'] as $key => &$tweet) {
                echo '<pre>';
                $delete = false;
                if (isset($tweet['entities']) and isset($tweet['entities']['urls'])) {
                    foreach ($tweet['entities']['urls'] as $url) {
                        if ($matches = preg_match('*godesigners.ru/\?ref\=*', $url['expanded_url'])) {
                            $delete = true;
                        }
                    }
                }
                if ($delete == false) {
                    $tweet['timestamp'] = strtotime($tweet['created_at']);
                    $minTimestamp = ($tweet['timestamp'] < $minTimestamp) ? $tweet['timestamp'] : $minTimestamp;
                    if (isset($tweet['entities']) and isset($tweet['entities']['media'])) {
                        $tweet['thumbnail'] = $tweet['entities']['media'][0]['media_url_https'];
                    }
                    $censoredTweets['statuses'][$key] = $tweet;
                }
            }

            if (($tutPosts = Wp_post::getPostsForStream($minTimestamp)) && (count($tutPosts) > 0)) {
                foreach ($tutPosts as $post) {
                    $censoredTweets['statuses'][] = array(
                        'type' => 'tutdesign',
                        'text' => $post->post_title,
                        'timestamp' => strtotime($post->post_modified),
                        'created_at' => $post->post_modified,
                        'slug' => $post->post_name,
                        'category' => $post->category,
                        'id' => $post->ID,
                        'thumbnail' => $post->thumbnail,
                    );
                }
            }

            uasort($censoredTweets['statuses'], function($a, $b) {
                return ($a['timestamp'] > $b['timestamp']) ? -1 : 1;
            });

            $res = Rcache::write('twitterstream', $censoredTweets);
            echo '<pre>';
            var_dump($censoredTweets['statuses']);
            die();
        });
    }
}