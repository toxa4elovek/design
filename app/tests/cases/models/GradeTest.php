<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Grade;

class GradeTest extends AppUnit
{

    public function setUp()
    {
        $this->rollUp('Grade');
    }

    public function tearDown()
    {
        $this->rollDown('Grade');
    }

    public function testGetFavouriteProjectsIdsForUser()
    {
        $this->assertFalse(Grade::isDesignerRatingExistsForProject(1));
        $this->assertTrue(Grade::isDesignerRatingExistsForProject(7));
    }
}
