<?php

namespace app\extensions\command;

use \app\models\User;

class Backup extends \lithium\console\Command {

    public function run() {
        $this->header('Welcome to the Backup command!');
        $handle = fopen('/var/godesigner/resources/tmp/'. date('YmdHis'). '.txt', "w");
        var_dump($handle);
        fwrite($handle,'test');
        fclose($handle);
        $output = shell_exec('/root/tarsh');
        echo "<pre>$output</pre>";
        $handle = fopen('/var/godesigner/resources/tmp/'. date('YmdHis'). '.txt', "w");
        var_dump($handle);
        fwrite($handle,'test');
        fclose($handle);
        $this->out('Finish');
    }
}

?>