<?php

namespace app\extensions\command;

use \app\models\Solution;
use app\extensions\storage\Rcache;
use \tmhOAuth\tmhOAuth;
use \tmhOAuth\tmhUtilities;
use \app\models\Event;
use app\extensions\social\TwitterAPI;
use \app\extensions\helper\PitchTitleFormatter;
use \app\extensions\social\FacebookAPI;

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
            $params = '?utm_source=twitter&utm_medium=tweet&utm_content=best-solution-tweet&utm_campaign=sharing';
            $solutionUrl = 'http://www.godesigner.ru/pitches/viewsolution/' . $solution->id . $params;
            //Самое популярное решение за 24.09.2014 «Лого для сервиса Бригадир Онлайн» http://www.godesigner.ru/pitches/viewsolution/106167 #Go_Deer

            $nameInflector = new PitchTitleFormatter();
            $title = $nameInflector->renderTitle($solution->pitch->title, 30);

            $tweet = 'Самое популярное решение за ' . date('d.m.Y', $lastday) . ' «' . $title. '» ' . $solutionUrl . ' #Go_Deer';
            if ($solution->pitch->private == 0 && $solution->pitch->category_id != 7) {
                if (isset($solution->images['solution_solutionView'])) {
                    if (isset($solution->images['solution_solutionView'][0]['filename'])) {
                        $imageurl = $solution->images['solution_solutionView'][0]['filename'];
                    } else {
                        $imageurl = $solution->images['solution_solutionView']['filename'];
                    }
                }
            }
            $data = array(
                'message' => $tweet,
                'picture' => $imageurl
            );
            $facebookAPI = new FacebookAPI;
            $result = $facebookAPI->postMessageToPage($data);

            if ($id = TwitterAPI::sendTweet($tweet, $imageurl)) {
                Event::create(array(
                    'type' => 'RetweetAdded',
                    'tweet_id' => $id,
                    'created' => date('Y-m-d H:i:s', time() - HOUR)
                ))->save();
                $string = base64_encode('8r9SEMoXAacbpnpjJ5v64A:I1MP2x7guzDHG6NIB8m7FshhkoIuD6krZ6xpN4TSsk');
                $tmhOAuth = new tmhOAuth(array(
                    'consumer_key' => '8r9SEMoXAacbpnpjJ5v64A',
                    'consumer_secret' => 'I1MP2x7guzDHG6NIB8m7FshhkoIuD6krZ6xpN4TSsk',
                    'user_token' => '513074899-IvVlKCCD0kEBicxjrLGLjW2Pb7ZiJd1ZjQB9mkvN',
                    'user_secret' => 'ldmaK6qmlzA3QJPQemmVWJGUpfST3YuxrzIbhaArQ9M'
                ));
                $tmhOAuth->headers['Authorization'] = 'Basic ' . $string;
                $params = array('grant_type' => 'client_credentials');
                $response = $tmhOAuth->request('POST', 'https://api.twitter.com/oauth2/token', $params, false
                );
                $data = json_decode($tmhOAuth->response['response'], true);
                $bearerToken = $data['access_token'];
                $tmhOAuth->headers['Authorization'] = 'Bearer ' . $bearerToken;
                $params = array('rpp' => 1, 'id' => $id, 'include_entities' => false);
                $code = $tmhOAuth->request('GET', 'https://api.twitter.com/1.1/statuses/oembed.json', $params, false);
                if ($code == 200) {
                    $tweetsDump = Rcache::read('RetweetsFeed');
                    $this->out('Got the data, saving to cache');
                    $embeddata = json_decode($tmhOAuth->response['response'], true);
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