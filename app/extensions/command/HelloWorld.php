<?php

namespace app\extensions\command;

class HelloWorld extends \lithium\console\Command {

    public function run() {
        $this->header('Welcome to the Hello World command!');
        $this->out('Hello, World!');
    }
}

?>