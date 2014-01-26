<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\extensions\storage\cache\adapter;

class Rcache {

    public static $client;
    protected static $_config = array();
    protected static $_tagRegistryKey = '__tags__';

    public static function init(array $config = array()) {
        self::$client = new \Redis;
        $defaults = array(
            'host' => '127.0.0.1:6379',
            'expiry' => '+1 hour',
            'persistent' => false
        );
        self::$_config = $config + $defaults;
    }

    public static function write($key, $data, $expiry = null, $tags = array(), $options = array()) {
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

    public static function read($key, $options = array()) {
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

    public static function delete($key) {
        $result = self::$client->del($key);
        return (bool) $result;
    }

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

    public static function ttl($key) {
        $ttl = self::$client->ttl($key);
        if($ttl == -1) {
            return null;
        }elseif($ttl == -2) {
            return false;
        }
        return $ttl;
    }

    public static function exists($key) {
        return self::$client->exists($key);
    }

    public static function enabled() {
        return extension_loaded('redis');
    }

    public static function connect() {
        list($ip, $port) = explode(':', self::$_config['host']);
        return self::$client->connect($ip, $port);
    }

    public static function flushDB() {
        return self::$client->flushDB();
    }

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

    private static function __isInList($value, $key) {
        if($list = self::read($key)) {
            return in_array($value, $list);
        }
        return false;
    }
}