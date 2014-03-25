<?php
namespace app\extensions\helper;

use \app\models\Pitch as PitchModel;
use \app\models\Expert;
use \app\models\Comment;

class Pitch extends \lithium\template\Helper {

    protected $expert;
    protected $dates = array();

    function expertOpinion($pitch_id) {
        $pitch = PitchModel::first($pitch_id);
        $experts = unserialize($pitch->{'expert-ids'});
        $expertUserIds = Expert::getPitchExpertUserIds($experts);
        if ($comments = Comment::all(array('conditions' => array('pitch_id' => $pitch_id, 'user_id' => $expertUserIds)))) {
            $comments = $comments->data();
            foreach ($expertUserIds as $expert) {
                $this->expert = $expert;
                $res = array_filter($comments, array($this, 'wasExpertComment'));
                \lithium\analysis\Logger::write('debug', var_export(count($res), true));
                if (count($res) == 0) {
                    return strtotime($pitch->finishDate) + DAY * 2;
                }
            }
            arsort($this->dates);
            return $this->dates[0] + DAY * 2;
        }
        return strtotime($pitch->finishDate) + DAY * 2;
    }

    protected function wasExpertComment($comment) {
        $this->dates[] = strtotime($comment['created']);
        $res = ($comment['user_id'] == $this->expert);
        return $res;
    }
}
