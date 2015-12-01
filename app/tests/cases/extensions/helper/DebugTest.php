<?php

namespace app\tests\cases\extensions\helper;

use app\extensions\helper\Debug;
use app\extensions\tests\AppUnit;
use app\extensions\helper\Session;

class DebugTest extends AppUnit {

    public $debug = null;
    public $queries = array(
        array(
            'type' => 'sql',
            'timestamp' => 120,
            'elapsed_time' => 0.1,
            'query' => 'SELECT * FROM `avatars` AS `Avatar` WHERE `model_id` = 32 AND `model` = \'\\app\\models\\User\''
        ),
        array(
            'type' => 'sql',
            'timestamp' => 150,
            'elapsed_time' => 0.5,
            'query' => 'SELECT Solution.pitch_id FROM `solutions` AS `Solution` WHERE user_id = 32 AND ((awarded = 1) OR (nominated = 1));'
        ),
        array(
            'type' => 'sql',
            'timestamp' => 150,
            'elapsed_time' => 0.3,
            'query' => 'SELECT Favourite.pitch_id FROM `favourites` AS `Favourite` WHERE user_id = 32;'
        ),
        array(
            'type' => 'sql',
            'timestamp' => 130,
            'elapsed_time' => 0.9,
            'query' => 'SELECT User.id FROM `users` AS `User` WHERE id = 32 GROUP BY User.id LIMIT 1;'
        )
    );

    public function setUp() {
        $this->debug = new Debug();
    }

    public function tearDown() {
        $this->debug = null;
        Session::clear();
    }

    public function testSortQueriesByTimestamp() {
        $result = $this->debug->sortQueriesByTimestamp($this->queries);
        $expected = array(
            array(
                'type' => 'sql',
                'timestamp' => 120,
                'elapsed_time' => 0.1,
                'query' => 'SELECT * FROM `avatars` AS `Avatar` WHERE `model_id` = 32 AND `model` = \'\\app\\models\\User\''
            ),
            array(
                'type' => 'sql',
                'timestamp' => 130,
                'elapsed_time' => 0.9,
                'query' => 'SELECT User.id FROM `users` AS `User` WHERE id = 32 GROUP BY User.id LIMIT 1;'
            ),
            array(
                'type' => 'sql',
                'timestamp' => 150,
                'elapsed_time' => 0.5,
                'query' => 'SELECT Solution.pitch_id FROM `solutions` AS `Solution` WHERE user_id = 32 AND ((awarded = 1) OR (nominated = 1));'
            ),
            array(
                'type' => 'sql',
                'timestamp' => 150,
                'elapsed_time' => 0.3,
                'query' => 'SELECT Favourite.pitch_id FROM `favourites` AS `Favourite` WHERE user_id = 32;'
            ),
        );
        $this->assertIdentical($expected, $result);

        $this->assertNull($this->debug->sortQueriesByTimestamp(null));
        $this->assertNull($this->debug->sortQueriesByTimestamp('string'));
        $this->assertNull($this->debug->sortQueriesByTimestamp(123));
        $this->assertNull($this->debug->sortQueriesByTimestamp(true));
    }

    public function testIsDebugInfoExists() {
        $this->assertFalse($this->debug->isDebugInfoExists());
        Session::write('debug.queries', 'string');
        $this->assertFalse($this->debug->isDebugInfoExists());
        Session::write('debug.queries', true);
        $this->assertFalse($this->debug->isDebugInfoExists());
        Session::write('debug.queries', new Debug);
        $this->assertFalse($this->debug->isDebugInfoExists());
        Session::write('debug.queries', array());
        $this->assertFalse($this->debug->isDebugInfoExists());
        Session::write('debug.queries', $this->queries);
        $this->assertTrue($this->debug->isDebugInfoExists());
    }

    public function testClearDebugInfo() {
        $this->assertFalse($this->debug->clearDebugInfo());
        Session::write('debug.queries', $this->queries);
        $this->assertTrue($this->debug->isDebugInfoExists());
        $this->assertTrue($this->debug->clearDebugInfo());
        $this->assertFalse($this->debug->isDebugInfoExists());
    }

    public function testDetectSpeedOfQuery() {
        $this->debug->speedBoundaries = array(
            'verySlow' => 0.4,
            'slow' => 0.2,
        );
        $this->assertNull($this->debug->detectSpeedOfQuery('string'));
        $this->assertNull($this->debug->detectSpeedOfQuery(true));
        $this->assertNull($this->debug->detectSpeedOfQuery(new Debug));
        $this->assertNull($this->debug->detectSpeedOfQuery(array()));
        $this->assertEqual('fast', $this->debug->detectSpeedOfQuery($this->queries[0]));
        $this->assertEqual('verySlow', $this->debug->detectSpeedOfQuery($this->queries[1]));
        $this->assertEqual('slow', $this->debug->detectSpeedOfQuery($this->queries[2]));
        $this->assertEqual('verySlow', $this->debug->detectSpeedOfQuery($this->queries[3]));
    }

    public function testEscapeQuery() {
        $query = "SELECT * FROM `avatars` AS `Avatar` WHERE `model_id` = 32 AND `model` = '\\app\\models\\User'";
        $this->assertEqual(addslashes($query), $this->debug->escapeQuery($query));
    }

    public function testRoundTime() {
        $time = 0.2043313;
        $this->assertEqual(0.20433, $this->debug->roundTime($time));
    }

    public function testGetDebugQueries () {
        $this->assertNull($this->debug->getDebugQueries());
        Session::write('debug.queries', $this->queries);
        $this->assertEqual($this->queries, $this->debug->getDebugQueries());
    }

    public function testGetVisualStyle() {
        $this->debug->styles = array(
            'slow' => 'color: orange',
        );
        $this->assertNull($this->debug->getVisualStyle('string'));
        $this->assertNull($this->debug->getVisualStyle(false));
        $this->assertNull($this->debug->getVisualStyle(array()));
        $this->assertNull($this->debug->getVisualStyle('verySlow'));
        $this->assertEqual('color: orange', $this->debug->getVisualStyle('slow'));
    }

    public function testGetHtmlForQuery() {
        $this->assertNull($this->debug->getHtmlForQuery('string'));
        $this->assertNull($this->debug->getHtmlForQuery(true));
        $this->assertNull($this->debug->getHtmlForQuery(new Debug));
        $this->assertNull($this->debug->getHtmlForQuery(array()));
        $expected = "console.log('%c0.9 SELECT User.id FROM `users` AS `User` WHERE id = 32 GROUP BY User.id LIMIT 1;', 'font-weight: bold, color: red');\r\n";
        $this->assertEqual($expected, $this->debug->getHtmlForQuery($this->queries[3]));
    }

    public function testGetQueryArray() {
        $this->assertNull($this->debug->getQueryArray('string'));
        $this->assertNull($this->debug->getQueryArray(true));
        $this->assertNull($this->debug->getQueryArray(new Debug));
        $this->assertNull($this->debug->getQueryArray(array()));
        $expected = array(
            'type' => 'sql',
            'timestamp' => 150,
            'elapsed_time' => 0.5,
            'query' => 'SELECT Solution.pitch_id FROM `solutions` AS `Solution` WHERE user_id = 32 AND ((awarded = 1) OR (nominated = 1));',
            'style' => 'font-weight: bold, color: red'
        );
        $this->assertEqual($expected, $this->debug->getQueryArray($this->queries[1]));
    }

    public function testIsDebugQuery() {
        $this->assertFalse($this->debug->isDebugQuery('string'));
        $this->assertFalse($this->debug->isDebugQuery(true));
        $this->assertFalse($this->debug->isDebugQuery(new Debug));
        $this->assertFalse($this->debug->isDebugQuery(array()));
        $this->assertFalse($this->debug->isDebugQuery(array('timestamp' => 1)));
        $this->assertFalse($this->debug->isDebugQuery(array('timestamp' => 1, 'query' => '12')));
        $this->assertFalse($this->debug->isDebugQuery(array('timestamp' => 1, 'query' => '12', 'elapsed_time' => 0.2)));
        $this->assertTrue($this->debug->isDebugQuery(array('timestamp' => 1, 'query' => '12', 'elapsed_time' => 0.2, 'type' => 'redis')));
    }

    public function testDumpDebugInfo() {
        $this->assertNull($this->debug->dumpDebugInfo());
        $expected = array(
            array(
                'type' => 'sql',
                'timestamp' => 120,
                'elapsed_time' => 0.1,
                'query' => 'SELECT * FROM `avatars` AS `Avatar` WHERE `model_id` = 32 AND `model` = \'\\app\\models\\User\'',
                'style' => 'font-style: italic'
            ),
            array(
                'type' => 'sql',
                'timestamp' => 130,
                'elapsed_time' => 0.9,
                'query' => 'SELECT User.id FROM `users` AS `User` WHERE id = 32 GROUP BY User.id LIMIT 1;',
                'style' => 'font-weight: bold, color: red'
            ),
            array(
                'type' => 'sql',
                'timestamp' => 150,
                'elapsed_time' => 0.5,
                'query' => 'SELECT Solution.pitch_id FROM `solutions` AS `Solution` WHERE user_id = 32 AND ((awarded = 1) OR (nominated = 1));',
                'style' => 'font-weight: bold, color: red'
            ),
            array(
                'type' => 'sql',
                'timestamp' => 150,
                'elapsed_time' => 0.3,
                'query' => 'SELECT Favourite.pitch_id FROM `favourites` AS `Favourite` WHERE user_id = 32;',
                'style' => 'color: orange'
            ),
        );
        Session::write('debug.queries', $this->queries);
        $this->assertEqual($expected, $this->debug->dumpDebugInfo());
    }

}