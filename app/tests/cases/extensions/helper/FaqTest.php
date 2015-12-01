<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Faq;
use app\extensions\tests\AppUnit;
use app\models\Question;

class FaqTest extends AppUnit {

    public $models = array('Question');
    public $helper = null;

    public function setUp() {
        $this->helper = new Faq();
        $this->rollUp($this->models);
    }

    public function tearDown() {
        $this->rollDown($this->models);
    }

    public function testShow() {
        $questions = Question::all();
        $result = $this->helper->show($questions);
        $expected = '<ul><li style="text-shadow: -1px 0 0 #FFFFFF;"><a href="/answers/view/1">Test question</a></li><li style="text-shadow: -1px 0 0 #FFFFFF;"><a href="/answers/view/2">Test question 2</a></li></ul>';
        $this->assertEqual($expected, $result);
    }
}