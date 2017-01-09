<?php

namespace app\extensions\command;

use app\extensions\social\TwitterAPI;

class RetweetTestResults extends CronJob
{

    public function run()
    {
        $twitterApi = new TwitterAPI;
        $result = $twitterApi->search('Какой ты дизайнер на самом деле', function ($object) {
            $decoded = json_decode($object->response['response'], true);
            $retweetsIds = [];
            $toRetweetIds = [];
            foreach ($decoded['statuses'] as $tweet) {
                if (($tweet['user']['id_str'] == '513074899') and (isset($tweet['retweeted_status']))) {
                    $retweetsIds[] = $tweet['retweeted_status']['id_str'];
                    continue;
                }
                if (in_array($tweet['id_str'], $retweetsIds)) {
                    continue;
                }
                $toRetweetIds[] = $tweet['id_str'];
            }
            foreach ($toRetweetIds as $idStr) {
                $params = ['id' => $idStr];
                $object->user_request([
                    'method' => 'POST',
                    'url' => $object->url('1.1/statuses/retweet/' . $idStr . '.json'),
                    'params' => $params,
                ]);
            }
            return count($toRetweetIds);
        });
        $this->out($result . ' твитов было отретвичено');
    }
}
