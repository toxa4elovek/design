<?php

namespace app\tests\cases\extensions\storage;

use Exception;
use app\extensions\storage\Rcache;
use app\tests\mocks\storage\MockDummy;
use lithium\core\Environment;

class RcacheTest extends \lithium\test\Unit
{

    public function __construct(array $config = [])
    {
        Environment::set('test');
        $defaults = [
            'host' => '127.0.0.1',
            'port' => 6379
        ];
        parent::__construct($config + $defaults);
    }

    public function skip()
    {
        $this->skipIf(
            !Rcache::enabled(),
            'The redis extension is not installed, please install from https://github.com/phpredis/phpredis'
        );
    }

    public function setUp()
    {
        Rcache::init();
    }

    public function tearDown()
    {
        Rcache::flushdb();
    }

    public function testInitAndConnected()
    {
        Rcache::init();
        $this->assertTrue(Rcache::connected());
        $this->assertTrue(Rcache::disconnect());
        $this->assertFalse(Rcache::connected());
        Rcache::init();
        $this->assertTrue(Rcache::connect());
    }

    public function testConnect()
    {
        $this->assertTrue(Rcache::enabled());
    }

    public function testWriteString()
    {
        $this->skipIf(!Rcache::connected(), 'The redis is not connected.');
        $data = 'value';
        $key = 'key';
        $result = Rcache::write($key, $data);
        $this->assertTrue($result);
        $result = Rcache::read($key);
        $this->assertEqual($data, $result);
    }

    public function testReadWithFunction()
    {
        $this->assertFalse(Rcache::exists('robot'));
        $this->assertFalse(Rcache::exists('key'));
        $result = Rcache::read('key');
        $this->assertFalse($result);
        $result = Rcache::read('key', function () {
            return 'data';
        }, '+1 minute', ['robot']);
        $this->assertEqual('data', $result);
        $this->assertTrue(Rcache::exists('key'));
        $result = Rcache::read('key');
        $this->assertEqual('data', $result);
        $ttl = Rcache::ttl('key');
        $this->assertTrue(is_numeric($ttl));
        $this->assertTrue($ttl > 0);
        $this->assertEqual(60, $ttl);
        $this->assertTrue(Rcache::exists('robot'));
    }

    public function testWriteArray()
    {
        $this->skipIf(!Rcache::connected(), 'The redis is not connected.');
        $data = ['first' => 'first_test', 'second' => 'second_test'];
        $key = 'key';
        $result = Rcache::write($key, $data);
        $this->assertTrue($result);
        $result = Rcache::read($key);
        $this->assertIdentical($data, $result);
    }

    public function testWriteObject()
    {
        $this->skipIf(!Rcache::connected(), 'The redis is not connected.');
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

    public function testWriteWithExpiry()
    {
        $this->skipIf(!Rcache::connected(), 'The redis is not connected.');
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

    public function testDelete()
    {
        $this->skipIf(!Rcache::connected(), 'The redis is not connected.');
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

    public function testWriteWithTags()
    {
        $this->skipIf(!Rcache::connected(), 'The redis is not connected.');
        $data = 'value';
        $data2 = 'value2';
        $key = 'key';
        $key2 = 'key2';
        // добавляем новую запись с тегами
        $result = Rcache::write($key, $data, ['tag1', 'tag2']);
        $this->assertTrue($result);
        $result = Rcache::read($key);
        $this->assertEqual($data, $result);
        $tag1 = Rcache::read('tag1');
        $this->assertEqual(['key'], $tag1);
        $tag2 = Rcache::read('tag2');
        $this->assertEqual(['key'], $tag2);
        // добавляем вторую запись с пересекающимся тегом
        $result = Rcache::write($key2, $data, ['tag1']);
        $this->assertTrue($result);
        $tag1 = Rcache::read('tag1');
        $this->assertEqual(['key', 'key2'], $tag1);
        $tag2 = Rcache::read('tag2');
        $this->assertEqual(['key'], $tag2);
        // убедимся в том, что в списке ключей для тега не происходит дублирования
        $result = Rcache::write($key, $data2, ['tag1', 'tag2']);
        $this->assertTrue($result);
        $tag1 = Rcache::read('tag1');
        $this->assertEqual(['key', 'key2'], $tag1);
        $tag2 = Rcache::read('tag2');
        $this->assertEqual(['key'], $tag2);
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
        $this->assertEqual(['tag1', 'tag2'], $results);
    }

    public function testWriteWithTagsAndExpiry()
    {
        $this->skipIf(!Rcache::connected(), 'The redis is not connected.');
        $key = 'key';
        $data = 'value';
        // добавляем новую запись с тегами
        $result = Rcache::write($key, $data, ['tag1', 'tag2'], '+1 hour');
        $this->assertTrue($result);
        $ttl = Rcache::ttl($key);
        $this->assertTrue($ttl);
    }

    public function testDeleteByTags()
    {
        $this->skipIf(!Rcache::connected(), 'The redis is not connected.');
        // Добавим новые записи с тегами
        $result = Rcache::write('key1', 'data1', ['tag1', 'tag2']);
        $this->assertTrue($result);
        $result = Rcache::write('key2', 'data2', ['tag2', 'tag3']);
        $this->assertTrue($result);
        $result = Rcache::write('key2', 'data2', ['tag3']);
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

    public function testFlushUnusedTagsKeysFlush()
    {
        $this->skipIf(!Rcache::connected(), 'The redis is not connected.');
        $redis = new \Redis;
        $redis->connect('127.0.0.1', '6379');
        $redis->rpush('__tags__', 'tagWithExpiredKey');
        $redis->rpush('tagWithExpiredKey', 'expiredKey');
        $result = Rcache::write('key1', 'data1', ['tagWithExpiredKey', 'tag1']);
        $this->assertTrue($result);
        // проверим текущие теги
        $result = Rcache::read('__tags__');
        $this->assertEqual(['tagWithExpiredKey', 'tag1'], $result);
        // проверим список ключей тега tagWithExpiredKey
        $result = Rcache::read('tagWithExpiredKey');
        $this->assertEqual(['expiredKey', 'key1'], $result);
        // очистим устаревшие теги и ключи
        $count = Rcache::flushUnusedTags();
        $this->assertEqual(1, $count);
        $result = Rcache::read('tagWithExpiredKey');
        $this->assertEqual(['key1'], $result);
    }

    public function testFlushUnusedTagsKeysAndTagsFlush()
    {
        $this->skipIf(!Rcache::connected(), 'The redis is not connected.');
        $redis = new \Redis;
        $redis->connect('127.0.0.1', '6379');
        $redis->rpush('__tags__', 'tagWithExpiredKey');
        $redis->rpush('tagWithExpiredKey', 'expiredKey');
        $result = Rcache::write('key1', 'data1', ['tag1']);
        $this->assertTrue($result);
        // проверим текущие теги
        $result = Rcache::read('__tags__');
        $this->assertEqual(['tagWithExpiredKey', 'tag1'], $result);
        // проверим список ключей тега tagWithExpiredKey
        $result = Rcache::read('tagWithExpiredKey');
        $this->assertEqual(['expiredKey'], $result);
        // очистим устаревшие теги и ключи
        $count = Rcache::flushUnusedTags();
        $this->assertEqual(1, $count);
        $result = Rcache::read('tagWithExpiredKey');
        $this->assertFalse($result);
        $result = Rcache::read('__tags__');
        $this->assertEqual(['tag1'], $result);
    }

    public function testWriteOnSameKey()
    {
        $this->skipIf(!Rcache::connected(), 'The redis is not connected.');
        $data = 'value';
        $data2 = 'value2';
        $key = 'key';
        $result = Rcache::write($key, $data);
        $this->assertTrue($result);
        $result = Rcache::read($key);
        $this->assertEqual($data, $result);
        $result = Rcache::write($key, $data2);
        $this->assertTrue($result);
        $result = Rcache::read($key);
        $this->assertEqual($data2, $result);
    }

    public function testFlushDB()
    {
        $this->skipIf(!Rcache::connected(), 'The redis is not connected.');
        $key = 'key';
        $data = 'value';
        $result = Rcache::write($key, $data);
        $this->assertTrue($result);
        $result = Rcache::read($key);
        $this->assertEqual($data, $result);
        Rcache::flushDB();
        $result = Rcache::read($key);
        $this->assertFalse($result);
    }

    public function testPublicsMustReturnFalseIfRedisNotConnected()
    {
        if (Rcache::connected()) {
            Rcache::disconnect();
        }
        $data = 'value';
        $key = 'key';
        $result = Rcache::write($key, $data);
        $this->assertFalse($result);
        $result = Rcache::read($key);
        $this->assertFalse($result);
        $result = Rcache::delete('non-existing key');
        $this->assertFalse($result);
    }

    public function testReadFilter()
    {
        Rcache::applyFilter('read', function ($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            return str_repeat($result, 2);
        });
        $data = 'value';
        $key = 'key';
        Rcache::write($key, $data);
        $result = Rcache::read($key);
        $this->assertEqual($data.$data, $result);
    }
}
