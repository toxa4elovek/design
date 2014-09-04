<?php

namespace app\tests\cases\models;

use \app\extensions\tests\AppUnit;
use \app\models\Pitchrating;
use \app\models\Pitch;
use \app\models\User;

class PitchratingTest extends AppUnit {

    public function setUp() {
        $this->rollUp(array('Pitch', 'User', 'Pitchrating'));
    }

    public function tearDown() {
        $this->rollDown(array('Pitch', 'User', 'Pitchrating'));
    }

    public function testSetRating() {
        $pitchRating = Pitchrating::first();
        $this->assertTrue(Pitchrating::setRating($pitchRating->user_id, $pitchRating->pitch_id, 3));

        // True при одинаковом рейтинге
        $this->assertTrue(Pitchrating::setRating($pitchRating->user_id, $pitchRating->pitch_id, 3));

        //Пользователь не существует
        $this->assertFalse(Pitchrating::setRating(0, $pitchRating->pitch_id, 2));
        //Питч не существует
        $this->assertFalse(Pitchrating::setRating($pitchRating->user_id, 0, 2));

        // Значение больше 5
        Pitchrating::setRating($pitchRating->user_id, $pitchRating->pitch_id, 99);
        $pitchRating2 = Pitchrating::first(array('conditions' => array('user_id' => $pitchRating->user_id, 'pitch_id' => $pitchRating->pitch_id)));
        $this->assertEqual(5, $pitchRating2->rating);

        // Значение меньше 1
        Pitchrating::setRating($pitchRating->user_id, $pitchRating->pitch_id, 0);
        $pitchRating2 = Pitchrating::first(array('conditions' => array('user_id' => $pitchRating->user_id, 'pitch_id' => $pitchRating->pitch_id)));
        $this->assertEqual(1, $pitchRating2->rating);
    }

    public function testGetRating() {
        $this->assertEqual(1, Pitchrating::getRating(User::first()->id, Pitch::first()->id));
        $this->assertEqual(0, Pitchrating::getRating(0, Pitch::first()->id));
        $this->assertEqual(0, Pitchrating::getRating(User::first()->id, 0));
    }

}
