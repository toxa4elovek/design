<?php

namespace app\extensions\command;

use \app\models\Pitch;

class AddonLetter extends \lithium\console\Command {

    public function run() {
        $this->header('Welcome to the AddonLetter command!');
        $res = 0;

        /* Time in days.
         *
         * From 0 to 1 - Part of pitch duration. E.g.: 0.5 - middle of pitch period.
         * Positive - days after pitches.started.
         * Negative - days before pitches.finishDate.
         */
        $briefTime = 2;
        $prolongTime = 0.5;
        $expertTime = -2;
        $res += Pitch::addonBriefLetter($briefTime);
        $res += Pitch::addonProlongLetter($prolongTime);
        $res += Pitch::addonExpertLetter($expertTime);
        $messages = ($res == 1) ? ' message' : ' messages';
        $have = ($res == 1) ? ' has' : ' have';
        $this->out($res . $messages . $have . ' been sent.');
    }
}