<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Tag;
use app\models\Solutiontag;

class TagTest extends AppUnit {

    public function setUp()
    {
        $this->rollUp('Tag', 'Solutiontag');
    }

    public function tearDown()
    {
        $this->rollDown('Tag', 'Solutiontag');
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
        $tag = Tag::saveTag("www.godesigner.ru");
        $existingTag = Tag::first(array('conditions' => array('name' => "www.godesigner.ru")));
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

}