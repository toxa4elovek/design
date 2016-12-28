<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Tag;
use app\models\Solutiontag;

class TagTest extends AppUnit {

    public function setUp()
    {
        $this->rollUp(array('Tag', 'Solutiontag', 'Solution'));
    }

    public function tearDown()
    {
        $this->rollDown(array('Tag', 'Solutiontag', 'Solution'));
    }

    public function testIsTagExists() {
        // точное совпадение
        $this->assertTrue(Tag::isTagExists('Тег'));

        // совпадение с неверным регистром первой буквы
        $this->assertTrue(Tag::isTagExists('тег'));

        // совпадение с неверным регистром первой буквы и пробелом
        $this->assertTrue(Tag::isTagExists(' тег '));

        // нет в базе
        $this->assertFalse(Tag::isTagExists('абракадбра'));
    }

    public function testSaveTag() {
        // Новый тег
        $tag = Tag::saveTag('Новый');
        $latestTag = Tag::first(array('order' => array('id' => 'desc')));
        $this->assertEqual($tag->id, $latestTag->id);

        // Уже существующий тег
        $tag = Tag::saveTag("godesigner.ru");
        $existingTag = Tag::first(array('conditions' => array('name' => "godesigner.ru")));
        $this->assertEqual($tag->id, $existingTag->id);

        // Уже существующий тег, но неточное написание
        $tag = Tag::saveTag(" тег ");
        $existingTag = Tag::first(array('conditions' => array('name' => "Тег")));
        $this->assertEqual($tag->id, $existingTag->id);
    }

    public function testGetTagId() {
        // тег существует
        $id = Tag::getTagId('Тег');
        $existingTag = Tag::first(array('conditions' => array('name' => "Тег")));
        $this->assertEqual($existingTag->id, $id);

        // тег существует, не точное написание
        $id = Tag::getTagId(" тег ");
        $existingTag = Tag::first(array('conditions' => array('name' => "Тег")));
        $this->assertEqual($existingTag->id, $id);

        // тег не существует
        $id = Tag::getTagId("абракадбра");
        $this->assertFalse($id);
    }

    public function testSaveSolutionTag() {
        // не существует
        $solutionId = 100;
        $result = Tag::saveSolutionTag('Проверка', $solutionId);
        $idOfSampleTag = Tag::getTagId('Проверка');

        $solutionTag = Solutiontag::first(array('order' => array('id' => 'desc')));
        $this->assertTrue(Tag::isTagExists('Проверка'));
        $this->assertEqual($result->id, $solutionTag->id);
        $this->assertEqual($idOfSampleTag, $solutionTag->tag_id);
        $this->assertEqual($solutionId, $solutionTag->solution_id);

        // тег уже существует
        $solutionId = 1000;
        $result = Tag::saveSolutionTag('Проверка', $solutionId);
        $solutionTag2 = Solutiontag::first(array('order' => array('id' => 'desc')));
        $this->assertTrue(Tag::isTagExists('Проверка'));
        $this->assertNotEqual($solutionTag->id, $solutionTag2->id);
        $this->assertEqual($result->id, $solutionTag2->id);
        $this->assertEqual($idOfSampleTag, $solutionTag2->tag_id);
        $this->assertEqual($solutionId, $solutionTag2->solution_id);
    }

    public function testGetags() {
        $result = Tag::getSuggest('Тег', true);
        $data = $result;
        $this->assertEqual(2, count($data));
        $this->assertEqual('Тег', $data[1]['name']);
        $this->assertEqual('Тегирование', $data[4]['name']);
        Tag::saveTag('Теги');
        $result = Tag::getSuggest('Тег', true);
        $data = $result;
        $this->assertEqual(3, count($data));
        $this->assertEqual('Тег', $data[1]['name']);
        $this->assertEqual('Тегирование', $data[4]['name']);
        $this->assertEqual('Теги', $data[5]['name']);
        $result = Tag::getSuggest('Тег', false);
        $data = $result;
        $this->assertEqual(3, count($data));
        $this->assertEqual('Тег', $data[1]['name']);
        $this->assertEqual('Тегирование', $data[4]['name']);
        $this->assertEqual('Теги', $data[5]['name']);
    }

    public function testRemoveTag() {
        $solutionId = 100;
        Tag::saveSolutionTag('Проверка', $solutionId);
        $this->assertTrue(Tag::isTagExists('Проверка'));
        $solutionTag = Solutiontag::first(array('conditions' => array('tag_id' => 5, 'solution_id' => $solutionId)));
        $this->assertTrue(is_object($solutionTag));
        $removeResult = Tag::removeTag('Проверка', $solutionId);
        $solutionTag = Solutiontag::first(array('conditions' => array('tag_id' => 5, 'solution_id' => $solutionId)));
        $this->assertFalse($solutionTag);
        $all = Solutiontag::all();
        $this->assertTrue(count($all->data()) > 0);
        $this->assertTrue(Tag::isTagExists('Проверка'));
        $this->assertTrue($removeResult);
        // Новый тег должен быть сохранен, даже если его еще нет
        $solutionId = 100;
        $result = Tag::removeTag('Новая проверка', $solutionId);
        $this->assertTrue($result);
        $this->assertTrue(Tag::isTagExists('Новая проверка'));
    }

    public function testGetPopularTags() {
        Tag::saveSolutionTag('Проверка', 1);
        Tag::saveSolutionTag('Проверка', 2);
        Tag::saveSolutionTag('Проверка', 3);
        Tag::saveSolutionTag('Проверка', 4);
        Tag::saveSolutionTag('Проверка', 5);
        Tag::saveSolutionTag('Проверка', 6);
        Tag::saveSolutionTag('Проверка2', 1);
        Tag::saveSolutionTag('Проверка2', 2);
        Tag::saveSolutionTag('Проверка2', 3);
        Tag::saveSolutionTag('Проверка2', 4);
        Tag::saveSolutionTag('Проверка3', 1);
        Tag::saveSolutionTag('Проверка3', 2);
        $result = Tag::getPopularTags(2);
        $expected = array(
            'Проверка' => 6,
            'Проверка2' => 4
        );
        $this->assertEqual($expected, $result);
    }

    public function testAdd() {
        $this->assertFalse(Tag::isTagExists('Проверка2'));
        $array = array('tags' => array('Проверка2'));
        Tag::add($array, 100);
        $this->assertTrue(Tag::isTagExists('Проверка2'));
        $id = Tag::getTagId('Проверка2');
        $count = Solutiontag::count(array('conditions' => array('tag_id' => $id, 'solution_id' => 100)));
        $this->assertEqual(1, $count);

        $array = array('job-type' => array('finances'));
        Tag::add($array, 101);
        $id = Tag::getTagId('Финансы');
        $id2 = Tag::getTagId('Бизнес');
        $count = Solutiontag::count(array('conditions' => array('tag_id' => $id, 'solution_id' => 101)));
        $this->assertEqual(1, $count);
        $count = Solutiontag::count(array('conditions' => array('tag_id' => $id2, 'solution_id' => 101)));
        $this->assertEqual(1, $count);

        $array = array('job-type' => array('sport'));
        Tag::add($array, 102);
        $id = Tag::getTagId('Спорт');
        $count = Solutiontag::count(array('conditions' => array('tag_id' => $id, 'solution_id' => 102)));
        $this->assertEqual(1, $count);
    }

}