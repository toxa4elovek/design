<?php

namespace app\extensions\command;

use app\extensions\social\TwitterAPI;
use app\extensions\storage\Rcache;

class UpdateTwitterFeed extends CronJob
{

    public function run()
    {
        Rcache::init();
        $api = new TwitterAPI();
        $hashTags = ['работадлядизайнеров'];
        $x = 0;
        $url = '';
        $countTags = count($hashTags);
        foreach ($hashTags as $tag) {
            ++$x;
            $url .= $countTags > $x ? '%23' . $tag . '+' : '%23' . $tag;
        }
        $api->search('работадлядизайнеров', function ($object) {
            $data = json_decode($object->response['response'], true);
            $censoredTweets = [];
            $censoredTweets['statuses'] = [];
            $minTimestamp = 1893355200;
            $listOfUsedIds = [];
            foreach ($data['statuses'] as $key => &$tweet) {
                $delete = false;
                if (isset($tweet['entities']) and isset($tweet['entities']['urls'])) {
                    foreach ($tweet['entities']['urls'] as $url) {
                        if ($matches = preg_match('*godesigners.ru/\?ref\=*', $url['expanded_url'])) {
                            $delete = true;
                        }
                    }
                }
                if (in_array($tweet['id_str'], $listOfUsedIds)) {
                    $delete = true;
                }
                if ($delete == false) {
                    $content = '';
                    $listOfUsedIds[] = $tweet['id_str'];
                    $tweet['timestamp'] = strtotime($tweet['created_at']);
                    $minTimestamp = ($tweet['timestamp'] < $minTimestamp) ? $tweet['timestamp'] : $minTimestamp;
                    $censoredTweets['statuses'][$key] = $tweet;
                    $text = $tweet['text'];
                    if (!isset($tweet['type']) && $tweet['type'] !== 'tutdesign') {
                        foreach ($tweet['entities']['hashtags'] as $hashtag) {
                            $text = str_replace('#' . $hashtag['text'], '<a style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/search/%23' . $hashtag['text'] . '">' . '#' . $hashtag['text'] . '</a>', $text);
                        }
                        foreach ($tweet['entities']['urls'] as $url) {
                            $text = str_replace($url['url'], '<a class="url-twitter" style="display:inline;color:#ff585d" target="_blank" href="' . $url['url'] . '">' . $url['display_url'] . '</a>', $text);
                        }
                        foreach ($tweet['entities']['user_mentions'] as $user) {
                            $text = str_replace('@' . $user['screen_name'], '<a style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/' . $user['screen_name'] . '">' . '@' . $user['screen_name'] . '</a>', $text);
                        }
                        $user = '<a style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/' . $tweet['user']['screen_name'] . '">@' . $tweet['user']['screen_name'] . '</a>';
                        $content .= $user . ' ' . $text;
                        $content = preg_replace("/<img[^>]+\>/i", '', $content);
                    }
                }
            }

            uasort($censoredTweets['statuses'], function ($a, $b) {
                return ($a['timestamp'] > $b['timestamp']) ? -1 : 1;
            });
            $res = Rcache::write('twitterstreamFeed', $censoredTweets);
            echo '<pre>';
            var_dump($censoredTweets['statuses']);
        });
    }
}
