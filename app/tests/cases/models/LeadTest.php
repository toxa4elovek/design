<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Lead;

class LeadTest extends AppUnit
{

    public $models = ['Lead'];

    public function setUp()
    {
        $this->rollUp($this->models);
    }

    public function tearDown()
    {
        $this->rollDown($this->models);
    }

    public function testResetLeadForUser()
    {
        $result = Lead::resetLeadForUser(1);
        $this->assertFalse($result);
        $lead = Lead::create(['user_id' => 1, 'result' => 1, 'email_date' => '2015-01-01 14:30:30']);
        $lead->save();
        $result = Lead::resetLeadForUser('1');
        $this->assertTrue($result);
        $lead = Lead::first(['conditions' => ['user_id' => 1]]);
        $this->assertEqual(0, $lead->result);
    }
}
