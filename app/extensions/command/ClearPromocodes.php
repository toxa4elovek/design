<?php

namespace app\extensions\command;

use \app\models\Promocode;

class ClearPromocodes extends \app\extensions\command\CronJob
{

    public function run()
    {
        $this->header('Welcome to the ClearPromocodes command!');
        $promocodes = Promocode::getOldPromocodes();
        $this->out('Total promocodes fetched for deletion - ' . count($promocodes));
        foreach ($promocodes as $promocode) {
            $promocode->delete();
        }
        $this->out('Task completed');
    }
}
