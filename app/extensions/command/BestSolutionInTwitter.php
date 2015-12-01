<?php

namespace app\extensions\command;

use app\models\Event;
use app\models\Solution;
use app\extensions\social\SocialMediaManager;
use app\extensions\social\TwitterAPI;
use app\extensions\storage\Rcache;

class BestSolutionInTwitter extends CronJob {

    public function run() {
        Rcache::init();
        $lastDay = time() - (DAY);
        $day = date('Y-m-d', $lastDay);
        $start = strtotime($day);
        $end = $start + DAY;
        $solution = Solution::first(array(
                    'conditions' => array(
                        'Pitch.status' => 0,
                        'Pitch.published' => 1,
                        'Pitch.private' => 0,
                        'Pitch.category_id' => array('!=' => 20),
                        'created' => array('>=' => date('Y-m-d H:i:s', $start), '<=' => date('Y-m-d H:i:s', $end))
                    ),
                    'order' => array('Solution.likes' => 'desc', 'Solution.views' => 'desc'),
                    'with' => array('Pitch')
        ));
        if($solution) {
            $mediaManager = new SocialMediaManager;
            $id = $mediaManager->postBestSolutionMessage($solution, $lastDay);
            if ($id) {
                Event::create(array(
                    'type' => 'RetweetAdded',
                    'tweet_id' => $id,
                    'created' => date('Y-m-d H:i:s', time() - HOUR)
                ))->save();
                $twitterAPI = new TwitterAPI();
                $params = array('rpp' => 1, 'id' => $id, 'maxwidth' => '550', 'include_entities' => false);
                $code = $twitterAPI->apiObject->user_request(array(
                    'method' => 'GET',
                    'url' => $twitterAPI->apiObject->url('1.1/statuses/oembed.json'),
                    'params' => $params
                ));
                if ($code == 200) {
                    $tweetsDump = Rcache::read('RetweetsFeed');
                    $this->out('Got the data, saving to cache');
                    $embeddedData = json_decode($twitterAPI->apiObject->response['response'], true);
                    $tweetsDump[$id] = $embeddedData['html'];
                    Rcache::write('RetweetsFeed', $tweetsDump);
                }
                $this->out('Event saved');
                $this->out('The best solution for ' . $day . ' sent');
            } else {
                $this->out('Error! The best solution for ' . $day . ' was not sent');
            }
        }
    }

}