<?php

namespace app\tests\cases\models;

use app\extensions\storage\Rcache;
use app\extensions\tests\AppUnit;
use app\models\Pitch;
use app\models\Solution;
use app\models\Solutionfile;

class SolutionTest extends AppUnit
{

    public $models = ['Pitch', 'Solution', 'Solutionfile'];

    public function setUp()
    {
        Rcache::init();
        copy(
            '/Users/dima/www/godesigner/app/resources/tmp/tests/avatar.png',
            '/Users/dima/www/godesigner/app/resources/tmp/solution.png'
        );
        copy(
            '/Users/dima/www/godesigner/app/resources/tmp/tests/avatar.png',
            '/Users/dima/www/godesigner/app/resources/tmp/solution_normal.png'
        );
        copy(
            '/Users/dima/www/godesigner/app/resources/tmp/tests/avatar.png',
            '/Users/dima/www/godesigner/app/resources/tmp/solution_view.png'
        );
        copy(
            '/Users/dima/www/godesigner/app/resources/tmp/tests/avatar.png',
            '/Users/dima/www/godesigner/app/resources/tmp/solution_largest.png'
        );
        $this->rollUp($this->models);
    }

    public function tearDown()
    {
        Rcache::flushdb();
        $this->rollDown($this->models);
        if (file_exists('/Users/dima/www/godesigner/app/resources/tmp/solution.png')) {
            unlink('/Users/dima/www/godesigner/app/resources/tmp/solution.png');
        }
        if (file_exists('/Users/dima/www/godesigner/app/resources/tmp/solution_normal.png')) {
            unlink('/Users/dima/www/godesigner/app/resources/tmp/solution_normal.png');
        }
        if (file_exists('/Users/dima/www/godesigner/app/resources/tmp/solution_view.png')) {
            unlink('/Users/dima/www/godesigner/app/resources/tmp/solution_view.png');
        }
        if (file_exists('/Users/dima/www/godesigner/app/resources/tmp/solution_largest.png')) {
            unlink('/Users/dima/www/godesigner/app/resources/tmp/solution_largest.png');
        }
    }

    public function testSave()
    {
        $this->assertFalse(Solution::copy(0, 0));
        $this->assertFalse(Solution::copy(100076, ''));
        Solution::copy(100076, 2);
        $new = Solution::first(['order' => ['id' => 'DESC']]);
        $old = Solution::first(2);
        $this->assertNotEqual($new->data(), $old->data());
    }

    public function testGetCreatedDate()
    {
        $this->assertEqual('14 Августа 2014, 21:27', Solution::getCreatedDate(1));
        $this->assertEqual('4 Августа 2014, 21:27', Solution::getCreatedDate(2));
        $this->assertFalse(Solution::getCreatedDate(false));
        $this->assertFalse(Solution::getCreatedDate(50));
    }

    public function testGetBestSolution()
    {
        $solution = Solution::getBestSolution(6);
        $this->assertEqual(8, $solution->id);
        $solution2 = Solution::getBestSolution(7);
        $this->assertEqual(7, $solution2->id);
        $solution3 = Solution::getBestSolution(6);
        $this->assertEqual(8, $solution3->id);
        $solution4 = Solution::getBestSolution(4);
        $this->assertEqual(11, $solution4->id);
    }

    public function testStringToWordsForSearchQuery()
    {
        $input = 'мясокомбинат';
        $result = ['мясокомбинат'];
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($input));
        $input = 'мясокомбинат транспорт';
        $result = ['мясокомбинат', 'транспорт'];
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($input));
        $input = urlencode('мясокомбинат транспорт');
        $result = ['мясокомбинат', 'транспорт'];
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($input));
        $array = ['мясокомбинат', 'транспорт'];
        $result = ['мясокомбинат', 'транспорт'];
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($array));
        $array = [urlencode('мясокомбинат'), urlencode('транспорт')];
        $result = ['мясокомбинат', 'транспорт'];
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($array));
        $input = '';
        $result = [];
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($input));
        $input = true;
        $result = [];
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($input));
        $input = false;
        $result = [];
        $this->assertEqual($result, Solution::stringToWordsForSearchQuery($input));
    }

    public function testFlipIndustryDictionary()
    {
        Solution::$industryDictionary = [
                'realty' => 'Недвижимость / Строительство',
                'auto' => 'Автомобили / Транспорт',
                'finances' => 'Финансы / Бизнес'];
        $result = ['Недвижимость / Строительство' => 'realty',
                'Автомобили / Транспорт' => 'auto',
                'Финансы / Бизнес' => 'finances'];
        $this->assertEqual($result, Solution::flipIndustryDictionary());
        Solution::$industryDictionary = 'string';
        $this->assertEqual(false, Solution::flipIndustryDictionary());
        Solution::$industryDictionary = null;
        $this->assertEqual(false, Solution::flipIndustryDictionary());
    }

    public function testCleanWordForSearchQuery()
    {
        $word = ' транспорт ';
        $result = 'транспорт';
        $this->assertEqual($result, Solution::cleanWordForSearchQuery($word));
        $word = '  Транспорт ';
        $result = 'транспорт';
        $this->assertEqual($result, Solution::cleanWordForSearchQuery($word));
    }

    public function testInjectIndustryWords()
    {
        Solution::$industryDictionary = [
                'realty' => 'Недвижимость / Строительство',
                'auto' => 'Автомобили / Транспорт',
                'finances' => 'Финансы / Бизнес'];
        $words = ['проверка', 'Финансы / Бизнес', 'Автомобили / Транспорт'];
        $result = ['проверка', 'финансы', 'бизнес', 'автомобили', 'транспорт'];
        $this->assertEqual($result, Solution::injectIndustryWords($words));
        $words = ['проверка', 'финансы', 'бизнес'];
        $result = ['проверка', 'финансы', 'бизнес'];
        $this->assertEqual($result, Solution::injectIndustryWords($words));
    }

    public function testGetListOfIndustryKeys()
    {
        Solution::$industryDictionary = [
                'realty' => 'Недвижимость / Строительство',
                'auto' => 'Автомобили / Транспорт',
                'finances' => 'Финансы / Бизнес'];
        $words = ['проверка', 'Финансы / Бизнес', 'Автомобили / Транспорт'];
        $result = ['finances', 'auto'];
        $this->assertEqual($result, Solution::getListOfIndustryKeys($words));
        $words = ['проверка', 'финансы', 'бизнес'];
        $result = [];
        $this->assertEqual($result, Solution::getListOfIndustryKeys($words));
    }

    public function testBuildSearchQuery()
    {
        $string = ['мясокомбинат'];
        $industries = ['finances'];
        $tags_id = [12, 13];
        $page = 2;
        $limit = 16;

        $expected = ['conditions' => [
                ['OR' => [
                    ["Pitch.title REGEXP '" . 'мясокомбинат' . "'"],
                    ["Pitch.description LIKE '%мясокомбинат%'"],
                    ["'Pitch.business-description' LIKE '%мясокомбинат%'"],
                    ["Pitch.industry LIKE '%" . 'finances' . "%'"],
                    ["Solutiontag.tag_id IN(12, 13)"]
                ]],
                'Solution.multiwinner' => 0,
                'Solution.awarded' => 0,
                'Solution.selected' => 1,
                'Pitch.awardedDate' => ['<' => date('Y-m-d H:i:s', time() - MONTH)],
                'Pitch.status' => ['>' => 1],
                'Pitch.private' => 0,
                'Pitch.category_id' => 1,
                'Solution.rating' => ['>=' => 3]
            ],
                'order' => ['Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc'],
                'with' => ['Pitch', 'Solutiontag'],
                'limit' => 16,
                'page' => 2];

        $this->assertEqual($expected, Solution::buildSearchQuery($string, $industries, $tags_id, $page, $limit));

        $string = ['мясокомбинат', 'транспорт'];
        $industries = [];
        $tags_id = 0;

        $result = ['conditions' => [
                ['OR' => [
                    ["Pitch.title REGEXP '" . 'мясокомбинат|транспорт' . "'"],
                    ["Pitch.description LIKE '%мясокомбинат транспорт%'"],
                    ["'Pitch.business-description' LIKE '%мясокомбинат транспорт%'"],
                ]],
                'Solution.multiwinner' => 0,
                'Solution.awarded' => 0,
                'Solution.selected' => 1,
                'Pitch.awardedDate' => ['<' => date('Y-m-d H:i:s', time() - MONTH)],
                'Pitch.status' => ['>' => 1],
                'Pitch.private' => 0,
                'Pitch.category_id' => 1,
                'Solution.rating' => ['>=' => 3]
            ],
                'order' => ['Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc'],
                'with' => ['Pitch', 'Solutiontag'],
                'limit' => 28,
                'page' => 1];
        $this->assertEqual($result, Solution::buildSearchQuery($string, $industries, $tags_id));

        $string = ['мясокомбинат', 'транспорт'];
        $industries = [];
        $tags_id = 0;

        $expected = ['conditions' => [
                ['OR' => [
                    ["Pitch.title REGEXP '" . 'мясокомбинат|транспорт' . "'"],
                    ["Pitch.description LIKE '%мясокомбинат транспорт%'"],
                    ["'Pitch.business-description' LIKE '%мясокомбинат транспорт%'"],
                ]],
                'Solution.multiwinner' => 0,
                'Solution.awarded' => 0,
                'Solution.selected' => 1,
                'Pitch.awardedDate' => ['<' => date('Y-m-d H:i:s', time() - MONTH)],
                'Pitch.status' => ['>' => 1],
                'Pitch.private' => 0,
                'Pitch.category_id' => 1,
                'Solution.rating' => ['>=' => 3]
            ],
                'order' => ['Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc'],
                'with' => ['Pitch', 'Solutiontag']];
        $this->assertEqual($expected, Solution::buildSearchQuery($string, $industries, $tags_id, false, false));
            //echo '<pre>';
            //var_dump($expected);
            //var_dump(Solution::buildSearchQuery($string, $industries, $tags_id, $page, $limit));

            // Проверяем исключения
            $string = ['IT'];
        $industries = [];
        $tags_id = 0;

        $result = ['conditions' => [
                ['OR' => [
                    ["Pitch.title REGEXP '[[:<:]]IT[[:>:]]'"],
                    ["Pitch.description REGEXP '[[:<:]]IT[[:>:]]'"],
                    ["'Pitch.business-description' REGEXP '[[:<:]]IT[[:>:]]'"],
                ]],
                'Solution.multiwinner' => 0,
                'Solution.awarded' => 0,
                'Solution.selected' => 1,
                'Pitch.awardedDate' => ['<' => date('Y-m-d H:i:s', time() - MONTH)],
                'Pitch.status' => ['>' => 1],
                'Pitch.private' => 0,
                'Pitch.category_id' => 1,
                'Solution.rating' => ['>=' => 3]
            ],
                'order' => ['Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc'],
                'with' => ['Pitch', 'Solutiontag']];
        $this->assertEqual($result, Solution::buildSearchQuery($string, $industries, $tags_id, false, false));

        $string = ['IT'];
        $industries = [];
        $tags_id = 0;

        $result = ['conditions' => [
                ['OR' => [
                    ["Pitch.title REGEXP '[[:<:]]IT[[:>:]]'"],
                    ["Pitch.description REGEXP '[[:<:]]IT[[:>:]]'"],
                    ["'Pitch.business-description' REGEXP '[[:<:]]IT[[:>:]]'"],
                ]],
                'Solution.multiwinner' => 0,
                'Solution.awarded' => 0,
                'Solution.selected' => 1,
                'Pitch.awardedDate' => ['<' => date('Y-m-d H:i:s', time() - MONTH)],
                'Pitch.status' => ['>' => 1],
                'Pitch.private' => 0,
                'Pitch.category_id' => 1,
                'Solution.rating' => ['>=' => 3]
            ],
                'order' => ['Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc'],
                'with' => ['Pitch', 'Solutiontag']];
        $this->assertEqual($result, Solution::buildSearchQuery($string, $industries, $tags_id, false, false, ['Solution.rating' => 'desc']));

        $string = ['IT'];
        $industries = [];
        $tags_id = 0;

        $expected = ['conditions' => [
                ['OR' => [
                    ["Pitch.title REGEXP '[[:<:]]IT[[:>:]]'"],
                    ["Pitch.description REGEXP '[[:<:]]IT[[:>:]]'"],
                    ["'Pitch.business-description' REGEXP '[[:<:]]IT[[:>:]]'"],
                ]],
                'Solution.multiwinner' => 0,
                'Solution.awarded' => 0,
                'Solution.selected' => 1,
                'Pitch.awardedDate' => ['<' => date('Y-m-d H:i:s', time() - MONTH)],
                'Pitch.status' => ['>' => 1],
                'Pitch.private' => 0,
                'Pitch.category_id' => 1,
                'Solution.rating' => ['>=' => 3]
            ],
                'order' => ['Solution.likes' => 'desc', 'Solution.views' => 'desc', 'Solution.rating' => 'desc'],
                'with' => ['Pitch', 'Solutiontag']];
        $result = Solution::buildSearchQuery($string, $industries, $tags_id, false, false, ['likes', 'views', 'rating']);
        $result = $expected['order'] === $result['order'];
        $this->assertTrue($result);
    }

    public function testBuildStreamQuery()
    {
        $result = [
                'conditions' =>
                    [
                        'Solution.multiwinner' => 0,
                        'Solution.awarded' => 0,
                        'Solution.selected' => 1,
                        'Pitch.awardedDate' => ['<' => date('Y-m-d H:i:s', time() - MONTH)],
                        'Pitch.status' => ['>' => 1],
                        'private' => 0,
                        'category_id' => 1,
                        'rating' => ['>=' => 3]
                    ],
                'order' => ['Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc'],
                'with' => ['Pitch'],
                'page' => 3,
                'limit' => 16];
        $this->assertEqual($result, Solution::buildStreamQuery(3, 16));
        $result = [
                'conditions' =>
                    [
                        'Solution.multiwinner' => 0,
                        'Solution.awarded' => 0,
                        'Solution.selected' => 1,
                        'Pitch.awardedDate' => ['<' => date('Y-m-d H:i:s', time() - MONTH)],
                        'Pitch.status' => ['>' => 1],
                        'private' => 0,
                        'category_id' => 1,
                        'rating' => ['>=' => 3]
                    ],
                'order' => ['Solution.rating' => 'desc', 'Solution.likes' => 'desc', 'Solution.views' => 'desc'],
                'with' => ['Pitch'],
                'page' => 1,
                'limit' => 28];
        $this->assertEqual($result, Solution::buildStreamQuery());
        $expected = [
                'conditions' =>
                    [
                        'Solution.multiwinner' => 0,
                        'Solution.awarded' => 0,
                        'Solution.selected' => 1,
                        'Pitch.awardedDate' => ['<' => date('Y-m-d H:i:s', time() - MONTH)],
                        'Pitch.status' => ['>' => 1],
                        'private' => 0,
                        'category_id' => 1,
                        'rating' => ['>=' => 3]
                    ],
                'order' => ['Solution.likes' => 'desc', 'Solution.views' => 'desc', 'Solution.rating' => 'desc'],
                'with' => ['Pitch'],
                'page' => 1,
                'limit' => 28];
        $result = Solution::buildStreamQuery(1, 28, ['likes', 'views', 'rating']);
        $result = ($result['order'] === $expected['order']);
        $this->assertTrue($result);
    }

    public function testRandomizeStreamOrder()
    {
        $result = Solution::randomizeStreamOrder();
        $this->assertTrue(is_array($result));
        $this->assertTrue(count($result) == 3);
        $this->assertTrue(in_array('likes', $result));
        $this->assertTrue(in_array('views', $result));
        $this->assertTrue(in_array('rating', $result));
    }

    public function testSolutionsForSaleCount()
    {
        $expected = 2;
        $this->assertEqual($expected, Solution::solutionsForSaleCount());
        $ttl = Rcache::ttl('logosale_totalcount');
        $this->assertTrue(is_numeric($ttl));
        $this->assertTrue($ttl > 0);
        $this->assertEqual(DAY, $ttl);
    }

    public function testGetUsersSolutions()
    {
        $ids = [4, 7, 6, 5, 2];
        $result = [];
        $solutions = Solution::getUsersSolutions(2);
        foreach ($solutions as $solution) {
            $result[] = (int) $solution->id;
        }
        $this->assertEqual($ids, $result);

        $ids = [4, 6, 2];
        $result = [];
        $solutions = Solution::getUsersSolutions(2, true);
        foreach ($solutions as $solution) {
            $result[] = (int) $solution->id;
        }
        $this->assertEqual($ids, $result);
    }

    public function testIsReadyForLogosale()
    {
        $pitch = Pitch::first(1);
        $pitch->status = 2;
        $pitch->category_id = 1;
        $pitch->private = 0;
        $pitch->totalFinishDate = date('Y-m-d H:i:s', time() - 31 * DAY);
        $result = Pitch::isReadyForLogosale($pitch);
        $this->assertTrue($result);

        $solution = Solution::first(1);
        $solution->rating = 4;
        $result = Solution::isReadyForLogosale($solution, $pitch);
        $this->assertTrue($result);

        $solution->rating = 2;
        $result = Solution::isReadyForLogosale($solution, $pitch);
        $this->assertFalse($result);

        $solution->rating = 4;
        $pitch->awarded = 1;
        $result = Solution::isReadyForLogosale($solution, $pitch);
        $this->assertFalse($result);
    }

    public function testFindAndSolutionFileCaching()
    {
        $solutionId = 2;
        $cacheKey = "solutionfiles_$solutionId";
        $this->assertFalse(Rcache::exists($cacheKey));
        $solution = Solution::first($solutionId);
        $images = $solution->data()['images'];
        $this->assertTrue(is_array($images));
        $this->assertEqual(4, count($images));
        $this->assertTrue(Rcache::exists($cacheKey));
        $solution = Solution::first($solutionId);
        $images = $solution->data()['images'];
        $this->assertTrue(is_array($images));
        $this->assertEqual(4, count($images));
        $this->assertTrue(Rcache::exists($cacheKey));
        $this->assertTrue(file_exists('/Users/dima/www/godesigner/app/resources/tmp/solution.png'));
        $this->assertTrue(file_exists('/Users/dima/www/godesigner/app/resources/tmp/solution_normal.png'));
        $this->assertTrue(file_exists('/Users/dima/www/godesigner/app/resources/tmp/solution_view.png'));
        $this->assertTrue(file_exists('/Users/dima/www/godesigner/app/resources/tmp/solution_largest.png'));
        $solution->delete();
        $this->assertFalse(Rcache::exists($cacheKey));
        $this->assertFalse(file_exists('/Users/dima/www/godesigner/app/resources/tmp/solution.png'));
        $this->assertFalse(file_exists('/Users/dima/www/godesigner/app/resources/tmp/solution_normal.png'));
        $this->assertFalse(file_exists('/Users/dima/www/godesigner/app/resources/tmp/solution_view.png'));
        $this->assertFalse(file_exists('/Users/dima/www/godesigner/app/resources/tmp/solution_largest.png'));
    }
}
