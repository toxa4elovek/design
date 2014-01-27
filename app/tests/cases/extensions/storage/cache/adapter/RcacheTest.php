<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\tests\cases\extensions\storage\cache\adapter;

use Exception;
use app\extensions\storage\Rcache;
use app\tests\mocks\storage\MockDummy;

class RcacheTest extends \lithium\test\Unit {

    public function __construct(array $config = array()) {
        $defaults = array(
            'host' => '127.0.0.1',
            'port' => 6379
        );
        parent::__construct($config + $defaults);
    }

    public function skip() {
        $this->skipIf(!Rcache::enabled(), 'The redis extension is not installed.');
    }

    public function setUp() {
        Rcache::init();
    }

    public function tearDown() {
        Rcache::flushdb();
    }

    public function testWriteString() {
        $data = 'value';
        $key = 'key';
        $result = Rcache::write($key, $data);
        $this->assertTrue($result);
        $result = Rcache::read($key);
        $this->assertEqual($data, $result);
    }

    public function testWriteArray() {
        $data = array('first' => 'first_test', 'second' => 'second_test');
        $key = 'key';
        $result = Rcache::write($key, $data);
        $this->assertTrue($result);
        $result = Rcache::read($key);
        $this->assertIdentical($data, $result);
    }

    public function testWriteObject() {
        $data = new MockDummy;
        $key = 'key';
        $result = Rcache::write($key, $data);
        $this->assertTrue($result);
        $result = Rcache::read($key);
        $this->assertEqual($data, $result);
        $this->assertTrue(is_object($result));
        $this->assertTrue(method_exists($result, 'calc'));
        $this->assertEqual(3, $result->calc());
    }

    public function testWriteWithExpiry() {
        $data = 'value';
        $key = 'key';
        // Проверка существующего ключа
        $result = Rcache::write($key, $data, '+1 minute');
        $this->assertTrue($result);
        $ttl = Rcache::ttl($key);
        $this->assertTrue(is_numeric($ttl));
        $this->assertTrue($ttl > 0);
        $this->assertEqual(60, $ttl);
        
        // проверка ключа без срока годности
        $result = Rcache::write($key, $data);
        $this->assertTrue($result);
        $ttl = Rcache::ttl($key);
        $this->assertIdentical(null, $ttl);

        // проверка не существующего ключа
        $ttl = Rcache::ttl('non-existing key');
        $this->assertIdentical(false, $ttl);
    }

    public function testDelete() {
        $result = Rcache::delete('non-existing key');
        $this->assertFalse($result);
        $data = 'value';
        $key = 'key';
        $result = Rcache::write($key, $data);
        $this->assertTrue($result);
        $result = Rcache::delete($key);
        $this->assertTrue($result);
        $result = Rcache::read($key);
        $this->assertFalse($result);
    }

    public function testWriteWithTags() {
        $data = 'value';
        $data2 = 'value2';
        $key = 'key';
        $key2 = 'key2';
        // добавляем новую запись с тегами
        $result = Rcache::write($key, $data, array('tag1', 'tag2'));
        $this->assertTrue($result);
        $result = Rcache::read($key);
        $this->assertEqual($data, $result);
        $tag1 = Rcache::read('tag1');
        $this->assertEqual(array('key'), $tag1);
        $tag2 = Rcache::read('tag2');
        $this->assertEqual(array('key'), $tag2);
        // добавляем вторую запись с пересекающимся тегом
        $result = Rcache::write($key2, $data, array('tag1'));
        $this->assertTrue($result);
        $tag1 = Rcache::read('tag1');
        $this->assertEqual(array('key', 'key2'), $tag1);
        $tag2 = Rcache::read('tag2');
        $this->assertEqual(array('key'), $tag2);
        // убедимся в том, что в списке ключей для тега не происходит дублирования
        $result = Rcache::write($key, $data2, array('tag1', 'tag2'));
        $this->assertTrue($result);
        $tag1 = Rcache::read('tag1');
        $this->assertEqual(array('key', 'key2'), $tag1);
        $tag2 = Rcache::read('tag2');
        $this->assertEqual(array('key'), $tag2);
        // проверим, что отредактированный ключ возвращает новое значение
        $result = Rcache::read('key');
        $this->assertEqual($data2, $result);
        // проверим ттл ключей-тегов
        $ttl = Rcache::ttl('tag1');
        $this->assertIdentical(null, $ttl);
        $ttl = Rcache::ttl('tag2');
        $this->assertIdentical(null, $ttl);
        // проверим регистр тегов
        $results = Rcache::read('__tags__');
        $this->assertEqual(array('tag1', 'tag2'), $results);

    }

    public function testWriteWithTagsAndExpiry() {
        $key = 'key';
        $data = 'value';
        // добавляем новую запись с тегами
        $result = Rcache::write($key, $data, array('tag1', 'tag2'), '+1 hour');
        $this->assertTrue($result);
        $ttl = Rcache::ttl($key);
        $this->assertTrue($ttl);
    }

    public function testDeleteByTags() {
        // Добавим новые записи с тегами
        $result = Rcache::write('key1', 'data1', array('tag1', 'tag2'));
        $this->assertTrue($result);
        $result = Rcache::write('key2', 'data2', array('tag2', 'tag3'));
        $this->assertTrue($result);
        $result = Rcache::write('key2', 'data2', array('tag3'));
        $this->assertTrue($result);
        // Удалим первую запись по общему для двух записей тегу
        $result = Rcache::deleteByTag('tag2');
        $this->assertTrue($result);
        // Убедимся, что ключей не больше не существует
        $this->assertFalse(Rcache::exists('key1'));
        $this->assertFalse(Rcache::exists('key2'));
        // Убедимся, что для тегов, в которых нет ключей, нет ключей
        $this->assertFalse(Rcache::exists('tag1'));
        $this->assertFalse(Rcache::exists('tag2'));
        // А для тех, где есть, ключ остается
        $this->assertFalse(Rcache::exists('tag3'));
    }

    public function testFlushUnusedTagsKeysFlush() {
        $redis = new \Redis;
        $redis->connect('127.0.0.1', '6379');
        $redis->rpush('__tags__', 'tagWithExpiredKey');
        $redis->rpush('tagWithExpiredKey', 'expiredKey');
        $result = Rcache::write('key1', 'data1', array('tagWithExpiredKey', 'tag1'));
        $this->assertTrue($result);
        // проверим текущие теги
        $result = Rcache::read('__tags__');
        $this->assertEqual(array('tagWithExpiredKey', 'tag1'), $result);
        // проверим список ключей тега tagWithExpiredKey
        $result = Rcache::read('tagWithExpiredKey');
        $this->assertEqual(array('expiredKey', 'key1'), $result);
        // очистим устаревшие теги и ключи
        $count = Rcache::flushUnusedTags();
        $this->assertEqual(1, $count);
        $result = Rcache::read('tagWithExpiredKey');
        $this->assertEqual(array('key1'), $result);
    }

    public function testFlushUnusedTagsKeysAndTagsFlush() {
        $redis = new \Redis;
        $redis->connect('127.0.0.1', '6379');
        $redis->rpush('__tags__', 'tagWithExpiredKey');
        $redis->rpush('tagWithExpiredKey', 'expiredKey');
        $result = Rcache::write('key1', 'data1', array('tag1'));
        $this->assertTrue($result);
        // проверим текущие теги
        $result = Rcache::read('__tags__');
        $this->assertEqual(array('tagWithExpiredKey', 'tag1'), $result);
        // проверим список ключей тега tagWithExpiredKey
        $result = Rcache::read('tagWithExpiredKey');
        $this->assertEqual(array('expiredKey'), $result);
        // очистим устаревшие теги и ключи
        $count = Rcache::flushUnusedTags();
        $this->assertEqual(1, $count);
        $result = Rcache::read('tagWithExpiredKey');
        $this->assertFalse($result);
        $result = Rcache::read('__tags__');
        $this->assertEqual(array('tag1'), $result);
    }
}