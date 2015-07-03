<?php

namespace app\extensions\command;

use \app\models\Solution;
use app\extensions\storage\Rcache;
use \tmhOAuth\tmhOAuth;
use \app\models\Event;
use app\extensions\social\SocialMediaManager;

class BestSolutionInTwitter extends \app\extensions\command\CronJob {

    public function run() {
        Rcache::init();
        $lastday = time() - (DAY);
        $day = date('Y-m-d', $lastday);
        $start = strtotime($day);
        $end = $start + DAY;
        $solution = Solution::first(array(
                    'conditions' => array(
                        'Pitch.status' => 0,
                        'Pitch.published' => 1,
                        'Pitch.private' => 0,
                        'created' => array('>=' => date('Y-m-d H:i:s', $start), '<=' => date('Y-m-d H:i:s', $end))
                    ),
                    'order' => array('Solution.likes' => 'desc', 'Solution.views' => 'desc'),
                    'with' => array('Pitch')
        ));
        if($solution) {
            $mediaManager = new SocialMediaManager;
            $id = $mediaManager->postBestSolutionMessage($solution, $lastday);
            if ($id) {
                Event::create(array(
                    'type' => 'RetweetAdded',
                    'tweet_id' => $id,
                    'created' => date('Y-m-d H:i:s', time() - HOUR)
                ))->save();
                $api = new tmhOAuth(array(
                    'consumer_key' => '8r9SEMoXAacbpnpjJ5v64A',
                    'consumer_secret' => 'I1MP2x7guzDHG6NIB8m7FshhkoIuD6krZ6xpN4TSsk',
                    'user_token' => '513074899-IvVlKCCD0kEBicxjrLGLjW2Pb7ZiJd1ZjQB9mkvN',
                    'user_secret' => 'ldmaK6qmlzA3QJPQemmVWJGUpfST3YuxrzIbhaArQ9M'
                ));
                $params = array('rpp' => 1, 'id' => $id, 'maxwidth' => '550', 'include_entities' => false);
                $code = $api->user_request(array(
                    'method' => 'GET',
                    'url' => $api->url('1.1/statuses/oembed.json'),
                    'params' => $params
                ));
                if ($code == 200) {
                    $tweetsDump = Rcache::read('RetweetsFeed');
                    $this->out('Got the data, saving to cache');
                    $embeddata = json_decode($api->response['response'], true);
                    $tweetsDump[$id] = $embeddata['html'];
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

?>