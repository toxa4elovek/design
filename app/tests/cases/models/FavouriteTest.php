<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Favourite;

class FavouriteTest extends AppUnit {

    public function setUp()
    {
        $this->rollUp('Favourite');
    }

    public function tearDown()
    {
        $this->rollDown('Favourite');
    }

    public function testGetFavouriteProjectsIdsForUser() {
        $expected = null;
        $this->assertEqual($expected, Favourite::getFavouriteProjectsIdsForUser(3));

        $expected = array(1);
        $this->assertEqual($expected, Favourite::getFavouriteProjectsIdsForUser(2));

        $expected = array(2, 5, 7);
        $this->assertEqual($expected, Favourite::getFavouriteProjectsIdsForUser(1));
    }


}