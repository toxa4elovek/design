<?php

namespace app\extensions\command;

use \app\models\Solution;
use \app\models\Pitch;
use \app\models\User;

class BestSolutionInTwitter extends \app\extensions\command\CronJob {

    public function run() {
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
        $params = '?utm_source=twitter&utm_medium=tweet&utm_content=winner-tweet&utm_campaign=sharing';
        $solutionUrl = 'http://www.godesigner.ru/pitches/viewsolution/' . $solution->id . $params;
        //Самое популярное решение за 24.09.2014 «Лого для сервиса Бригадир Онлайн» http://www.godesigner.ru/pitches/viewsolution/106167 #Go_Deer
        $tweet = 'Самое популярное решение за ' . date('d.m.Y', $lastday) . ' «' . $solution->pitch->title . '» ' . $solutionUrl . ' #Go_Deer';
        if ($solution->pitch->private == 0 && $solution->pitch->category_id != 7) {
            if (isset($solution->images['solution_solutionView'])) {
                if (isset($solution->images['solution_solutionView'][0]['filename'])) {
                    $imageurl = $solution->images['solution_solutionView'][0]['filename'];
                } else {
                    $imageurl = $solution->images['solution_solutionView']['filename'];
                }
            }
        }

        if (User::sendTweet($tweet, $imageurl)) {
            $this->out('The best solution for ' . $day . ' sent');
        } else {
            $this->out('Error! The best solution for ' . $day . ' was not sent');
        }
    }

}

?>