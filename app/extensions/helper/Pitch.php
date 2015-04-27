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
                if (count($res) == 0) {
                    return strtotime($pitch->finishDate);
                }
            }
            rsort($this->dates);
            return $this->dates[0];
        }
        return strtotime($pitch->finishDate);
    }

    /**
     * Метод возвращает временной интервал, оставшийся до момента подтверждения
     *
     * @return boolean|DateInterval
     */
    public function confirmationTimeRemain($pitch = false) {
        $timeWait = 3;
        $datetime1 = new \DateTime();
        $datetime2 = new \DateTime(date('Y-m-d H:i:s', (strtotime($pitch->started) + $timeWait * DAY)));
        $interval = $datetime2->diff($datetime1);
        if ($interval->invert == 0) {
            return false;
        }
        return $interval;
    }

    protected function wasExpertComment($comment) {
        $this->dates[] = strtotime($comment['created']);
        $res = ($comment['user_id'] == $this->expert);
        return $res;
    }

    /**
     * Метод определяет, готов ли переданный питч быть к тому, чтобы
     * прдаваться на распродаже
     *
     * @param $pitch
     * @return bool
     */
    public function isReadyForLogosale($pitch) {
        if(is_object($pitch) && method_exists($pitch, 'data')) {
            $pitch = $pitch->data();
        }
        if(is_array($pitch)) {
            if(($pitch['status'] == 2) && ($pitch['category_id'] == 1) &&
                ($pitch['private'] == 0) && ($pitch['totalFinishDate'] < date('Y-m-d H:i:s', time() - 30 * DAY)))
            {
                return true;
            }else {
                return false;
            }
        }
        return false;
    }

}
