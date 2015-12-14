<?php
namespace app\extensions\helper;

use \app\models\Pitch as PitchModel;
use \app\models\Expert;
use \app\models\Comment;
use app\models\SubscriptionPlan;

/**
 * Class Pitch
 * Хэлпер для работы с данными проектов
 * @package app\extensions\helper
 */
class Pitch extends \lithium\template\Helper {

    protected $expert;
    protected $dates = array();

    /**
     * Метод возвращяет дату публикации экспертного мнения или дату завершения проекта
     *
     * @param $pitch_id
     * @return int
     */
    public function expertOpinion($pitch_id) {
        $pitch = PitchModel::first($pitch_id);
        $experts = unserialize($pitch->{'expert-ids'});
        $expertUserIds = Expert::getExpertUserIds($experts);
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

    /**
     * Метод проверяет, является ли комментарий комментарием эксперта
     *
     * @param $comment
     * @return bool
     */
    public function wasExpertComment($comment) {
        $this->dates[] = strtotime($comment['created']);
        return (bool) $comment['user_id'] == $this->expert;
    }

    /**
     * Метод определяет, готов ли переданный питч быть к тому, чтобы
     * прдаваться на распродаже
     *
     * @param $pitch
     * @return bool
     */
    public function isReadyForLogosale($pitch) {
        return PitchModel::isReadyForLogosale($pitch);
    }

    /**
     * Метод показывает среднее количество решений для категории $categoryId и качества награды $type
     *
     * @param $categoryId
     * @param $type
     * @return bool|float|int|mixed
     */
    public function getStatisticalAverages($categoryId, $type) {
        return PitchModel::getStatisticalAverages($categoryId, $type);
    }

    /**
     * Метод возвращяет метку времени, когда заканчивается этап выбора победителя
     *
     * @param $projectRecord
     * @return int|null
     */
    public function getChooseWinnerTime($projectRecord) {
        if(($projectRecord->status != 1) || ($projectRecord->awarded != 0)) {
            return null;
        }
        $startTime = $projectRecord->finishDate;
        $time = strtotime($startTime) + DAY * 4;
        if($projectRecord->expert == 1) {
            return $this->expertOpinion($projectRecord->id) + (3 * DAY);
        }
        if($projectRecord->chooseWinnerFinishDate != '0000-00-00 00:00:00') {
            $time = strtotime($projectRecord->chooseWinnerFinishDate);
        }
        return $time;
    }

    public function getPenalty($projectRecord) {
        //http://www.godesigner.ru/answers/view/70
        $diff = time() - $this->getChooseWinnerTime($projectRecord);
        return ceil($diff / 60 / 60) * 25;
    }

    /**
     * Метод-обертка для получения номера тарифного плана из уже существующей записи
     *
     * @param $recordId
     * @return int|null
     */
    public function getPlanForPayment($recordId) {
        return SubscriptionPlan::getPlanForPayment($recordId);
    }

}