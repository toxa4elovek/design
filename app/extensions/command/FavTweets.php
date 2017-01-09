<?php

namespace app\extensions\command;

use app\extensions\social\TwitterAPI;

class FavTweets extends CronJob
{

    public function run()
    {
        $twitterApi = new TwitterAPI;
        $twitterApi->search('Какой ты дизайнер на самом деле', function ($object) {
            var_dump($object);
        });

        /*$objects = array('логотип', 'сайт', 'упаковку', 'название', 'слоган');
        $actions = array('помогите', 'где логотип', 'заказать', 'кого');
        $queries = array();
        foreach($actions as $verb) {
            foreach($objects as $object) {
                $queries[] = $verb . ' ' . $object;
            }
        }
        $result = 0;
        foreach($queries as $query) {
            $result += $twitterApi->search($query, function($object, $apiObject) {
                $decoded = json_decode($object->response['response'], true);
                $retweetsIds = array();
                $toRetweetIds = array();

                foreach ($decoded['statuses'] as $tweet) {
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
                    $apiObject->favorite($idStr);
                }
                return count($toRetweetIds);
            });
        }
        $this->out($result . ' твитов было отретвичено');*/
    }
}
