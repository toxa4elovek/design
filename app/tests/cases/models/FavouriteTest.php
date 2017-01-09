<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Favourite;
use app\models\Event;

class FavouriteTest extends AppUnit
{

    public function setUp()
    {
        $this->rollUp(['Favourite', 'Pitch', 'User', 'Event']);
    }

    public function tearDown()
    {
        $this->rollDown(['Favourite', 'Pitch', 'User', 'Event']);
    }

    public function testAdd()
    {
        $this->assertTrue(Favourite::add(2, 2));
        $record = Favourite::first(['conditions' => ['user_id' => 2, 'pitch_id' => 2]]);
        $this->assertTrue(gettype($record), 'object');
        $this->assertTrue(get_class($record), 'lithium\data\entity\Record');
        // уже добавили
        $this->assertFalse(Favourite::add(2, 2));
    }

    public function testUnfav()
    {
        $this->assertTrue(Favourite::add(2, 2));
        $this->assertTrue(Favourite::unfav(2, 2));
        $this->assertFalse(Favourite::unfav(2, 2));
    }

    public function testAddUser()
    {
        $this->assertTrue(Favourite::addUser(1, 2));
        $record = Favourite::first(['conditions' => ['user_id' => 1, 'pitch_id' => 0, 'fav_user_id' => 2]]);
        $this->assertTrue(gettype($record), 'object');
        $this->assertTrue(get_class($record), 'lithium\data\entity\Record');
        $event = Event::first(['conditions' => [
            'type' => 'FavUserAdded',
            'user_id' => 1,
            'fav_user_id' => 2]]);
        $this->assertTrue(gettype($event), 'object');
        $this->assertTrue(get_class($event), 'lithium\data\entity\Record');

        // уже добавили
        $this->assertFalse(Favourite::addUser(1, 2));
    }

    public function testUnfavUser()
    {
        $this->assertTrue(Favourite::addUser(1, 2));
        $this->assertTrue(Favourite::unfavUser(1, 2));
        $event = Event::first(['conditions' => [
            'type' => 'FavUserAdded',
            'user_id' => 1,
            'fav_user_id' => 2]]);
        $this->assertNull($event);
        $this->assertFalse(Favourite::unfavUser(1, 2));
    }

    public function testGetCountFavoriteMe()
    {
        $result = Favourite::getNumberOfTimesAddedToFavourite(1);
        $this->assertTrue(is_int($result));
        $this->assertIdentical(0, $result);

        $this->assertTrue(Favourite::addUser(2, 1));
        $result = Favourite::getNumberOfTimesAddedToFavourite(1);
        $this->assertIdentical(1, $result);

        $this->assertTrue(Favourite::addUser(3, 1));
        $result = Favourite::getNumberOfTimesAddedToFavourite(1);
        $this->assertIdentical(2, $result);
    }

    public function testGetCountFavoriteUsersForUser()
    {
        $result = Favourite::getCountFavoriteUsersForUser(1);
        $this->assertTrue(is_int($result));
        $this->assertIdentical(0, $result);

        $this->assertTrue(Favourite::addUser(1, 2));
        $result = Favourite::getCountFavoriteUsersForUser(1);
        $this->assertIdentical(1, $result);

        $this->assertTrue(Favourite::addUser(1, 3));
        $result = Favourite::getCountFavoriteUsersForUser(1);
        $this->assertIdentical(2, $result);
    }

    public function testGetFavouriteProjectsIdsForUser()
    {
        $expected = null;
        $this->assertEqual($expected, Favourite::getFavouriteProjectsIdsForUser(3));

        $expected = [1];
        $this->assertEqual($expected, Favourite::getFavouriteProjectsIdsForUser(2));

        $expected = [2, 5, 7];
        $this->assertEqual($expected, Favourite::getFavouriteProjectsIdsForUser(1));
    }
}
