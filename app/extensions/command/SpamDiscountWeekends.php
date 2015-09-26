<?php

namespace app\extensions\command;

use \app\models\Pitch;
use app\extensions\storage\Rcache;
use \app\extensions\mailers\SpamMailer;

class SpamDiscountWeekends extends \app\extensions\command\CronJob {

    public function run() {
        $this->header('Start SpamDiscountWeekends command');
        Rcache::init();
        $date = date('Y-m-d');
        $pitches = Pitch::all(array('conditions' => array('billed' => 0, 'published' => 0, 'category_id' => 1, 'started' => array(
                            '>=' => date('Y-m-d', strtotime($date . ' -7 days')),
                            '<' => $date)), 'with' => 'User'));
        $cache = array();
        $count = 0;
        foreach ($pitches as $pitch) {
            $hash = sha1($pitch->user->id . 'spmWeek');
            $complete_hash = sha1($hash . $pitch->id . $pitch->user->email);
            $data = array(
                'email' => $pitch->user->email,
                'subject' => 'Скидка на выходных!',
                'pitch' => $pitch,
                'sale' => $complete_hash
            );
            $cache[$complete_hash] = array('user_id' => $pitch->user->id, 'pitch_id' => $pitch->id, 'email' => $pitch->user->email);
            SpamMailer::discountWeekends($data);
            $count++;
        }
        Rcache::write('SpamDsicountWeek', $cache);
        $this->out($count . ' users emailed');
    }

}
?>