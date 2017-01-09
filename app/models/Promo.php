<?php

namespace app\models;

class Promo extends AppModel
{

    public $belongsTo = ['Solution'];

    public static function __init()
    {
        parent::__init();
        self::applyFilter('find', function ($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            if (is_object($result)) {
                $getWebUrl = function ($path) {
                    if (preg_match('#webroot(.*)#', $path, $matches)) {
                        return $matches[1];
                    } else {
                        return false;
                    }
                };
                $addWebUrl = function ($record) use ($getWebUrl) {
                    if (isset($record->filename)) {
                        $record->weburl = $getWebUrl($record->filename);
                    }
                    return $record;
                };
                if (get_class($result) == 'lithium\data\entity\Record') {
                    $result = $addWebUrl($foundItem);
                } else {
                    foreach ($result as $foundItem) {
                        $foundItem = $addWebUrl($foundItem);
                    }
                }
            }
            return $result;
        });
    }
}
