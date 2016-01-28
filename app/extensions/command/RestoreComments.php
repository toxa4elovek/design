<?php

namespace app\extensions\command;

use app\extensions\storage\Rcache;
use app\models\Comment;
use app\models\Historycomment;
use app\models\Sendemail;
use app\models\Solution;

class RestoreComments extends CronJob {

    public function run() {
        $this->header('Welcome to the ReferalPayments command!');
        $count = 0;
        /*$emails = Sendemail::all(['conditions' => [
            'email' => ['!=' => 'team@godesigner.ru'],
            'subject' => ['!=' => 'Go Designer оставил комментарий'],
            'text' => ['LIKE' => '%Название для тарифа на GoDesigner%']
        ]]);

        foreach($emails as $email) {
            if(preg_match('@<span style="color: #444444; line-height: 17px; font-size: 12px; font-family: Arial, sans-serif;">(.*)<\/span>@', $email->text, $matches)) {
                $text = $matches[1];
            }
            if(!$comment = Comment::first(['conditions' => ['user_id' => 108, 'pitch_id' => 108764, 'text' => ['LIKE' => "%$text%"]]])) {
                preg_match('/#(\d+)\D/', $text, $matches);
                $solutionNum = $matches[1];
                $solution = Solution::first(['conditions' => ['pitch_id' => 108764, 'num' => $solutionNum]]);
                if($solution) {
                    $solutionId = $solution->id;
                }else {
                    $solutionId = 0;
                }
                $this->out('Comment do not exists: ' . $text);
                $data = [
                    'user_id' => 108,
                    'pitch_id' => 108764,
                    'solution_id' => $solutionId,
                    'text' => $text,
                    'created' => $email->created,
                    'public' => 0
                ];
                var_dump($data);
                //$comment = Comment::create($data);
                //$comment->save();
                $count++;
            }
        }*/
        /*
        $historyComments = Historycomment::all(['conditions' => ['pitch_id' => 108764, 'user_id' => 108]]);
        foreach($historyComments as $historyComment) {
            $text = $historyComment->text;
            if(!$comment = Comment::first(['conditions' => ['user_id' => 108, 'pitch_id' => 108764, 'text' => $text]])) {
                if($historyComment->solution_id) {
                    $public = 0;
                }else {
                    $public = 1;
                }
                $this->out('Comment do not exists: ' . $text);
                $data = [
                    'user_id' => 108,
                    'pitch_id' => 108764,
                    'solution_id' => $historyComment->solution_id,
                    'text' => $text,
                    'created' => $historyComment->created,
                    'public' => $public
                ];
                var_dump($data);
                $comment = Comment::create($data);
                $comment->save();
                $count++;
            }
        }
        */
        $cacheKey = 'commentsraw_108764';
        Rcache::delete($cacheKey);
        $this->out($count . ' emails');
    }
}