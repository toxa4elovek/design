<?php

namespace app\extensions\command;

use app\models\Event;
use app\models\News;

/**
 * Class UpdateNewsWithCompanyInstagram
 *
 * Команда для сохранения фоток из инстаграма в ленту
 * @package app\extensions\command
 */
class UpdateNewsWithCompanyInstagram extends CronJob
{

    /**
     * Получаем и сохраняем фотки из инстаграмма за последний час
     */
    public function run()
    {
        $this->_renderHeader();
        $jsonString = file_get_contents('https://www.instagram.com/godesigner.ru/media/');
        $json = json_decode($jsonString, true);
        $json['items'] = array_filter($json['items'], function ($item) {
            if ($item['created_time'] < time() - 4 * DAY) {
                return false;
            }
            return true;
        });
        $count = count($json['items']);
        array_walk($json['items'], function ($item) {
            $embedData = json_decode(file_get_contents('https://api.instagram.com/oembed/?url=http://instagr.am/p/' . $item['code']), true);
            $newsData = [
                'short' => $embedData['html'],
                'created' => date(MYSQL_DATETIME_FORMAT),
                'admin' => 1
            ];
            $record = News::create($newsData);
            $record->save();
            $eventData = [
                'type' => 'newsAdded',
                'created' => date(MYSQL_DATETIME_FORMAT),
                'news_id' => $record->id
            ];
            Event::create($eventData)->save();
        });
        $this->_renderFooter("$count photo saved");
    }
}
