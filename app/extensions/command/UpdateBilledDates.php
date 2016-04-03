<?php

namespace app\extensions\command;

use app\models\Pitch;

class UpdateBilledDates extends CronJob {

    public function run()
    {
        $this->_renderHeader();
        $projects = Pitch::all([
            'conditions' => ['billed' => 1, 'billed_date' == ''],
            'order' => ['id' => 'desc']]);
        foreach($projects as $project) {
            var_dump($project->id);
            if($project->billed_date == '') {
                $project->billed_date = $project->started;
            }
            var_dump($project->save());
        }
        $this->_renderFooter(count($projects) ." projects shared.");
    }

}