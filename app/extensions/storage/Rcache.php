<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\extensions\storage;

class Rcache {

    /**
     * @var Свойство для хранения объекта класса Redis
     */
    public static $client;
    /**
     * @var array Свойство для хранения конфига, переданного в init
     */
    protected static $_config = array();
    /**
     * @var string Название ключа для регистра тегов
     */
    protected static $_tagRegistryKey = '__tags__';

    /**
     * Иницилазция класса, создает подключение к редису и сохраняет его
     *
     * @param array $config
     */
    public static function init(array $config = array()) {
        self::$client = new \Redis;
        $defaults = array(
            'host' => '127.0.0.1:6379',
            'persistent' => false
        );
        self::$_config = $config + $defaults;
    }

    /**
     * Метод создаёт запись в редис
     *
     * @param $key - ключ, строка
     * @param $data - данные для сохранения, сериализуются
     * @param array $tags - Массив тегов ИЛИ strtotime-совместима строка $expiry
     * @param null $expiry - strtotime-совместима строка $expiry
     * @return mixed
     */
    public static function write($key, $data, $tags = array(), $expiry = null) {
        if(func_num_args() == 3) {
            if(!is_array($tags)) {
                $expiry = $tags;
                $tags = array();
            }
        }
        if(!empty($tags)) {
            foreach($tags as $tag) {
                if(!self::__isInList($key, $tag)) {
                    self::$client->rPush($tag, $key);
                }
                if(!self::__isInList($tag, self::$_tagRegistryKey)) {
                    self::$client->rpush(self::$_tagRegistryKey, $tag);
                }
            }
        }
        return self::$client->set($key, serialize($data), strtotime($expiry));
    }

    /**
     * Метод пытается вернуть сохраненное ранее значение по ключи $key
     *
     * @param $key
     * @return bool|mixed если ключа не существует - false, в остальных случаях - значение
     */
    public static function read($key) {
        if(!self::$client->exists($key)) return false;
        $type = self::$client->type($key);
        if($type == 1) {
            $result = self::$client->get($key);
            return unserialize($result);
        }elseif($type == 3) {
            $result = self::$client->lRange($key, 0, -1);;
            return $result;
        }
    }

    /**
     * Метод удаляет запись по ключу $key
     *
     * @param $key
     * @return bool
     */
    public static function delete($key) {
        $result = self::$client->del($key);
        return (bool) $result;
    }

    /**
     * Метод удаляет все записи с тегом $tag. Теги без записей тоже удаляются
     *
     * @param $tag
     * @return bool
     */
    public static function deleteByTag($tag) {
        if(!self::exists($tag)) {
            return false;
        }
        $listOfKeysForTag = self::read($tag);
        foreach($listOfKeysForTag as $key) {
            self::delete($key);
            self::$client->lrem($tag, $key, 1);
        }
        $tagList = self::read(self::$_tagRegistryKey);
        foreach($tagList as $tag) {
            $keyList = self::read($tag);
            if($keyList) {
                $intersected = array_intersect($listOfKeysForTag, $keyList);
                foreach($intersected as $key) {
                    self::$client->lrem($tag, $key, 1);
                }
            }
        }
        return true;
    }

    /**
     * Метод возвращает дату окончания срока действия ключа
     *
     * @param $key
     * @return bool|null - число, если дата есть, null - если срок вечный, false - если запись не найдена
     *
     */
    public static function ttl($key) {
        $ttl = self::$client->ttl($key);
        if($ttl == -1) {
            return null;
        }elseif($ttl == -2) {
            return false;
        }
        return $ttl;
    }

    /**
     * Метод определяет, существует ли запись с ключём $key
     *
     * @param $key
     * @return mixed
     */
    public static function exists($key) {
        return self::$client->exists($key);
    }

    /**
     * Метод определяет, подключено ли расширение redis
     *
     * @see https://github.com/nicolasff/phpredis
     * @return bool
     */
    public static function enabled() {
        return extension_loaded('redis');
    }

    /**
     * Метод создает соединение к редису
     *
     * @return mixed
     */
    public static function connect() {
        list($ip, $port) = explode(':', self::$_config['host']);
        return self::$client->connect($ip, $port);
    }

    /**
     * Метод записи в базе данных
     *
     * @return mixed
     */
    public static function flushDB() {
        return self::$client->flushDB();
    }

    /**
     * Метод удаляет неиспользуемые более ключи в тегах, для cron
     *
     * @return int - количество удаленных ключей
     */
    public static function flushUnusedTags() {
        $count = 0;
        $tags = self::read(self::$_tagRegistryKey);
        foreach($tags as $tag) {
            $keyListOfTag = self::read($tag);
            foreach($keyListOfTag as $key) {
                if(!self::exists($key)) {
                    self::$client->lrem($tag, $key, 1);
                    $count++;
                }
            }
            if(!$length = self::$client->llen($tag)) {
                self::$client->lrem(self::$_tagRegistryKey, $tag, 1);
            }
        }
        return $count;
    }

    /**
     * Метод определяет, если ли ключ $value в списке $key
     *
     * @param $value
     * @param $key
     * @return bool
     */
    private static function __isInList($value, $key) {
        if($list = self::read($key)) {
            return in_array($value, $list);
        }
        return false;
    }
}