<?php

namespace app\tests\cases\models;

use \app\extensions\tests\AppUnit;
use \app\models\Solution;
use app\extensions\storage\Rcache;

class SolutionTest extends AppUnit {

    public function setUp() {
        Rcache::init();
        $this->rollUp(array('Pitch', 'Solution'));
    }

    public function tearDown() {
        Rcache::flushdb();
        $this->rollDown(array('Pitch', 'Solution', 'Solutionfile'));
    }

    public function testSave() {
        $this->assertFalse(Solution::copy(0, 0));
        $this->assertFalse(Solution::copy(100076, ''));
        Solution::copy(100076, 2);
        $new = Solution::first(array('order' => array('id' => 'DESC')));
        $old = Solution::first(2);
        $this->assertNotEqual($new->data(), $old->data());
    }

    public function testGetCreatedDate() {
        $this->assertEqual('14 Августа 2014, 21:27', Solution::getCreatedDate(1));
        $this->assertEqual('4 Августа 2014, 21:27', Solution::getCreatedDate(2));
        $this->assertFalse(Solution::getCreatedDate(false));
        $this->assertFalse(Solution::getCreatedDate(50));
    }

    public function testGetBestSolution() {
        $solution = Solution::getBestSolution(6);
        $this->assertEqual(8, $solution->id);
        $solution2 = Solution::getBestSolution(7);
        $this->assertEqual(7, $solution2->id);
        $solution3 = Solution::getBestSolution(6);
        $this->assertEqual(8, $solution3->id);
        $solution4 = Solution::getBestSolution(4);
        $this->assertEqual(11, $solution4->id);
    }

    public function testStringToWordsForSearchQuery() {
        $input = 'мясокомбинат';
        $result = array('мясокомбинат');
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($input));
        $input = 'мясокомбинат транспорт';
        $result = array('мясокомбинат', 'транспорт');
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($input));
        $input = urlencode('мясокомбинат транспорт');
        $result = array('мясокомбинат', 'транспорт');
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($input));
        $array = array('мясокомбинат', 'транспорт');
        $result = array('мясокомбинат', 'транспорт');
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($array));
        $array = array(urlencode('мясокомбинат'), urlencode('транспорт'));
        $result = array('мясокомбинат', 'транспорт');
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($array));
        $input = '';
        $result = array();
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($input));
        $input = true;
        $result = array();
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($input));
        $input = false;
        $result = array();
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($input));
    }

    public function testFlipIndustryDictionary() {
        Solution::$industryDictionary = array(
            'realty' => 'Недвижимость / Строительство',
            'auto' => 'Автомобили / Транспорт',
            'finances' => 'Финансы / Бизнес');
        $result = array('Недвижимость / Строительство' => 'realty',
            'Автомобили / Транспорт' => 'auto',
            'Финансы / Бизнес' => 'finances');
        $this->assertEqual($result, Solution::flipIndustryDictionary());
        Solution::$industryDictionary = 'string';
        $this->assertEqual(false, Solution::flipIndustryDictionary());
        Solution::$industryDictionary = null;
        $this->assertEqual(false, Solution::flipIndustryDictionary());
    }

    public function testCleanWordForSearchQuery() {
        $word = ' транспорт ';
        $result = 'транспорт';
        $this->assertEqual($result, Solution::cleanWordForSearchQuery($word));
        $word = '  Транспорт ';
        $result = 'транспорт';
        $this->assertEqual($result, Solution::cleanWordForSearchQuery($word));
    }

    public function testInjectIndustryWords() {
        Solution::$industryDictionary = array(
            'realty' => 'Недвижимость / Строительство',
            'auto' => 'Автомобили / Транспорт',
            'finances' => 'Финансы / Бизнес');
        $words = array('проверка', 'Финансы / Бизнес', 'Автомобили / Транспорт');
        $result = array('проверка', 'финансы', 'бизнес', 'автомобили', 'транспорт');
        $this->assertEqual($result, Solution::injectIndustryWords($words));
        $words = array('проверка', 'финансы', 'бизнес');
        $result = array('проверка', 'финансы', 'бизнес');
        $this->assertEqual($result, Solution::injectIndustryWords($words));
    }

    public function testGetListOfIndustryKeys() {
        Solution::$industryDictionary = array(
            'realty' => 'Недвижимость / Строительство',
            'auto' => 'Автомобили / Транспорт',
            'finances' => 'Финансы / Бизнес');
        $words = array('проверка', 'Финансы / Бизнес', 'Автомобили / Транспорт');
        $result = array('finances', 'auto');
        $this->assertEqual($result, Solution::getListOfIndustryKeys($words));
        $words = array('проверка', 'финансы', 'бизнес');
        $result = array();
        $this->assertEqual($result, Solution::getListOfIndustryKeys($words));
    }

    public function testBuildSearchQuery() {
        $string = array('мясокомбинат');
        $industries = array('finances');
        $tags_id = array(12, 13);
        $page = 2;
        $limit = 16;

        $expected = array('conditions' => array(
            array('OR' => array(
                array("Pitch.title REGEXP '" . 'мясокомбинат' . "'"),
                array("Pitch.description LIKE '%мясокомбинат%'"),
                array("'Pitch.business-description' LIKE '%мясокомбинат%'"),
                array("Pitch.industry LIKE '%" . 'finances' . "%'"),
                array("Solutiontag.tag_id IN(12, 13)")
            )),
            'Solution.multiwinner' => 0,
            'Solution.awarded' => 0,
            'Solution.selected' => 1,
            'Pitch.awardedDate' => array('<' => date('Y-m-d H:i:s', time() - MONTH)),
            'Pitch.status' => array('>' => 0),
            'Pitch.private' => 0,
            'Pitch.category_id' => 1,
            'Solution.rating' => array('>=' => 3)
        ),
            'order' => array('Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc'),
            'with' => array('Pitch', 'Solutiontag'),
            'limit' => 16,
            'page' => 2);

        $this->assertEqual($expected, Solution::buildSearchQuery($string, $industries, $tags_id, $page, $limit));

        $string = array('мясокомбинат', 'транспорт');
        $industries = array();
        $tags_id = 0;

        $result = array('conditions' => array(
            array('OR' => array(
                array("Pitch.title REGEXP '" . 'мясокомбинат|транспорт' . "'"),
                array("Pitch.description LIKE '%мясокомбинат транспорт%'"),
                array("'Pitch.business-description' LIKE '%мясокомбинат транспорт%'"),
            )),
            'Solution.multiwinner' => 0,
            'Solution.awarded' => 0,
            'Solution.selected' => 1,
            'Pitch.awardedDate' => array('<' => date('Y-m-d H:i:s', time() - MONTH)),
            'Pitch.status' => array('>' => 0),
            'Pitch.private' => 0,
            'Pitch.category_id' => 1,
            'Solution.rating' => array('>=' => 3)
        ),
            'order' => array('Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc'),
            'with' => array('Pitch', 'Solutiontag'),
            'limit' => 28,
            'page' => 1);
        $this->assertEqual($result, Solution::buildSearchQuery($string, $industries, $tags_id));

        $string = array('мясокомбинат', 'транспорт');
        $industries = array();
        $tags_id = 0;

        $expected = array('conditions' => array(
            array('OR' => array(
                array("Pitch.title REGEXP '" . 'мясокомбинат|транспорт' . "'"),
                array("Pitch.description LIKE '%мясокомбинат транспорт%'"),
                array("'Pitch.business-description' LIKE '%мясокомбинат транспорт%'"),
            )),
            'Solution.multiwinner' => 0,
            'Solution.awarded' => 0,
            'Solution.selected' => 1,
            'Pitch.awardedDate' => array('<' => date('Y-m-d H:i:s', time() - MONTH)),
            'Pitch.status' => array('>' => 0),
            'Pitch.private' => 0,
            'Pitch.category_id' => 1,
            'Solution.rating' => array('>=' => 3)
        ),
            'order' => array('Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc'),
            'with' => array('Pitch', 'Solutiontag'));
        $this->assertEqual($expected, Solution::buildSearchQuery($string, $industries, $tags_id, false, false));
        //echo '<pre>';
        //var_dump($expected);
        //var_dump(Solution::buildSearchQuery($string, $industries, $tags_id, $page, $limit));

        // Проверяем исключения
        $string = array('IT');
        $industries = array();
        $tags_id = 0;

        $result = array('conditions' => array(
            array('OR' => array(
                array("Pitch.title REGEXP '[[:<:]]IT[[:>:]]'"),
                array("Pitch.description REGEXP '[[:<:]]IT[[:>:]]'"),
                array("'Pitch.business-description' REGEXP '[[:<:]]IT[[:>:]]'"),
            )),
            'Solution.multiwinner' => 0,
            'Solution.awarded' => 0,
            'Solution.selected' => 1,
            'Pitch.awardedDate' => array('<' => date('Y-m-d H:i:s', time() - MONTH)),
            'Pitch.status' => array('>' => 0),
            'Pitch.private' => 0,
            'Pitch.category_id' => 1,
            'Solution.rating' => array('>=' => 3)
        ),
            'order' => array('Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc'),
            'with' => array('Pitch', 'Solutiontag'));
        $this->assertEqual($result, Solution::buildSearchQuery($string, $industries, $tags_id, false, false));

    }

    public function testBuildStreamQuery() {
        $result = array(
            'conditions' =>
                array(
                    'Solution.multiwinner' => 0,
                    'Solution.awarded' => 0,
                    'Solution.selected' => 1,
                    'Pitch.awardedDate' => array('<' => date('Y-m-d H:i:s', time() - MONTH)),
                    'Pitch.status' => array('>' => 0),
                    'private' => 0,
                    'category_id' => 1,
                    'rating' => array('>=' => 3)
                ),
            'order' => array('Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc'),
            'with' => array('Pitch'),
            'page' => 3,
            'limit' => 16);
        $this->assertEqual($result, Solution::buildStreamQuery(3, 16));
        $result = array(
            'conditions' =>
                array(
                    'Solution.multiwinner' => 0,
                    'Solution.awarded' => 0,
                    'Solution.selected' => 1,
                    'Pitch.awardedDate' => array('<' => date('Y-m-d H:i:s', time() - MONTH)),
                    'Pitch.status' => array('>' => 0),
                    'private' => 0,
                    'category_id' => 1,
                    'rating' => array('>=' => 3)
                ),
            'order' => array('Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc'),
            'with' => array('Pitch'),
            'page' => 1,
            'limit' => 28);
        $this->assertEqual($result, Solution::buildStreamQuery());
    }

    public function testSolutionsForSaleCount() {
        $expected = 2;
        $this->assertEqual($expected, Solution::solutionsForSaleCount());
        $ttl = Rcache::ttl('logosale_totalcount');
        $this->assertTrue(is_numeric($ttl));
        $this->assertTrue($ttl > 0);
        $this->assertEqual(DAY, $ttl);
    }

}