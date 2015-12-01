<?php

namespace app\extensions\storage;

use lithium\core\StaticObject;

class Rcache extends StaticObject {

    /**
     * @var Object свойство для хранения объекта класса Redis
     */
    protected static $client = null;
    /**
     * @var array свойство для хранения конфига, переданного в init
     */
    protected static $_config = [];
    /**
     * @var string Название ключа для регистра тегов
     */
    protected static $_tagRegistryKey = '__tags__';

    public static $connected = false;

    /**
     * Иницилазция класса, создает подключение к редису и сохраняет его
     *
     * @param array $config
     */
    public static function init(array $config = []) {
        self::$client = new \Redis;
        $defaults = [
            'host' => '127.0.0.1:6379',
            'persistent' => false
        ];
        self::$_config = $config + $defaults;
        self::connect();

        $methodsThatMustHaveConnections = [
            'read',
            'write',
            'delete',
            'flushDB',
            'ttl',
            'exists',
            'flushUnusedTags'
        ];
        self::applyFilter($methodsThatMustHaveConnections, function($self, $params, $chain) {
            if(!self::connected()) {
                return false;
            }
            return $chain->next($self, $params, $chain);
        });
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
    public static function write($key, $data, $tags = [], $expiry = null) {
        if(func_num_args() == 3) {
            if(!is_array($tags)) {
                $expiry = $tags;
                $tags = [];
            }
        }
        $params = ['key' => $key, 'data' => $data, 'tags' => $tags, 'expiry' => $expiry, 'operation' => __FUNCTION__];
        return static::_filter(__FUNCTION__, $params, function($self, $params) {
            $key = $params['key'];
            $data = $params['data'];
            $tags = $params['tags'];
            $expiry = $params['expiry'];
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    if (!self::__isInList($key, $tag)) {
                        self::$client->rPush($tag, $key);
                    }
                    if (!self::__isInList($tag, self::$_tagRegistryKey)) {
                        self::$client->rPush(self::$_tagRegistryKey, $tag);
                    }
                }
            }
            if (!$expiry) {
                return self::$client->set($key, serialize($data));
            } else {
                return self::$client->set($key, serialize($data), (strtotime($expiry) - time()));
            }
        });
    }

    /**
     * Метод пытается вернуть сохраненное ранее значение по ключи $key
     *
     * @param $key
     * @return bool|mixed если ключа не существует - false, в остальных случаях - значение
     */
    public static function read($key) {
        $params = ['key' => $key, 'operation' => __FUNCTION__];
        return static::_filter(__FUNCTION__, $params, function($self, $params) {
            $key = $params['key'];
            if (!$exists = self::$client->exists($key)) {
                return false;
            }
            $type = self::$client->type($key);
            if ($type == 1) {
                $result = self::$client->get($key);
                return unserialize($result);
            } elseif ($type == 3) {
                $result = self::$client->lRange($key, 0, -1);;
                return $result;
            }
            return false;
        });
    }

    /**
     * Метод удаляет запись по ключу $key
     *
     * @param $key
     * @return bool
     */
    public static function delete($key) {
        $operation = __FUNCTION__;
        $params = compact('key', 'operation');
        return static::_filter(__FUNCTION__, $params, function($self, $params) {
            $result = self::$client->del($params['key']);
            return (bool) $result;
        });

    }

    /**
     * Метод удаляет все записи с тегом $tag. Теги без записей тоже удаляются
     *
     * @param $tag
     * @return bool
     */
    public static function deleteByTag($tag) {
        //$operation = __FUNCTION__;
        //$params = compact('tag', 'operation');
        //$tag = $params['tag'];
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
        $operation = __FUNCTION__;
        $params = compact('key', 'operation');
        return static::_filter(__FUNCTION__, $params, function($self, $params) {
            $ttl = self::$client->ttl($params['key']);
            if ($ttl == -1) {
                return null;
            } elseif ($ttl == -2) {
                return false;
            }
            return $ttl;
        });
    }

    /**
     * Метод определяет, существует ли запись с ключём $key
     *
     * @param $key
     * @return mixed
     */
    public static function exists($key) {
        $operation = __FUNCTION__;
        $params = compact('key', 'operation');
        return static::_filter(__FUNCTION__, $params, function($self, $params) {
            return self::$client->exists($params['key']);
        });
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
     * Метод возвращяет, подключен ли сейчас клиент к редису
     *
     * @return bool
     */
    public static function connected() {
        return self::$connected;
    }

    /**
     * Метод создает соединение к редису
     *
     * @return mixed
     */
    public static function connect() {
        list($ip, $port) = explode(':', self::$_config['host']);
        if(self::$client->connect($ip, $port)) {
            self::$connected = true;
        }
        return self::$connected;
    }

    /**
     * Метод разрывает соединение к редису
     *
     * @return mixed
     */
    public static function disconnect() {
        if($result = self::$client->close()) {
            self::$connected = false;
        }
        return $result;
    }

    /**
     * Метод записи в базе данных
     *
     * @return mixed
     */
    public static function flushDB() {
        return static::_filter(__FUNCTION__, [], function($self, $params) {
            return self::$client->flushDB();
        });
    }

    /**
     * Метод удаляет неиспользуемые более ключи в тегах, для cron
     *
     * @return int - количество удаленных ключей
     */
    public static function flushUnusedTags() {
        return static::_filter(__FUNCTION__, [], function($self, $params) {
            $count = 0;
            $tags = self::read(self::$_tagRegistryKey);
            foreach ($tags as $tag) {
                $keyListOfTag = self::read($tag);
                foreach ($keyListOfTag as $key) {
                    if (!self::exists($key)) {
                        self::$client->lrem($tag, $key, 1);
                        $count++;
                    }
                }
                if (!$length = self::$client->llen($tag)) {
                    self::$client->lrem(self::$_tagRegistryKey, $tag, 1);
                }
            }
            return $count;
        });
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