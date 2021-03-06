<?php

namespace app\extensions\command;

use \app\models\Pitch;
use \app\models\Solution;

class IndexSolutions extends \app\extensions\command\CronJob
{


    public function run()
    {
        $id = 101805;
        $pitches = Pitch::all(['conditions' => ['id' => $id]]);
        foreach ($pitches as $pitch) {
            $solutions = Solution::all(['conditions' => ['pitch_id' => $pitch->id]]);
            foreach ($solutions as $solution) {
                $this->out('Solution id#' . $solution->id);
                $solution->pitchtitle = $pitch->title;
                $solution->pitchdescription = $pitch->description;
                $solution->save();
            }
        }
    }
}
