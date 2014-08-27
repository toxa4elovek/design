<?php

namespace app\tests\mocks\storage;

Class MockDummy extends \lithium\core\Object {

    public $a = 1;
    public $b = 2;

    public function calc() {
        return $this->a + $this->b;
    }

}