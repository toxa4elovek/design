<?php

namespace app\models;

/**
 * Class Url
 *
 * Класс для работы с краткими адресами
 *
 * @package app\models
 */
class Url extends AppModel
{

    /**
     * Метод проверяет есть ли в базе указанный урл
     *
     * @param $url string
     * @return bool
     */
    public static function check($url)
    {
        return (bool) self::first(['conditions' => ['full' => $url]]);
    }

    /**
     * Метод возвращяет полный адрес по коротку коду
     *
     * @param $short string
     * @return null|string
     */
    public static function get($short)
    {
        if ($url = self::first(['conditions' => ['short' => $short]])) {
            return $url->full;
        }
        return null;
    }

    /**
     * @param $full string
     * @return string
     */
    private static function fetchOrCreateNew($full)
    {
        if (!$record = self::first(['conditions' => ['full' => $full]])) {
            $data = ['full' => $full, 'short' => self::generateUrl()];
            $record = self::create($data);
            $record->save();
        }
        return $record->short;
    }

    /**
     * Метод создает случайную шестисимвольную строчки для использования в адресе
     *
     * @return string
     */
    private static function generateUrl()
    {
        return substr(md5(rand().rand()), 0, 6);
    }

    /**
     * Метод возвращяет короткий адрес для полного адреса $full
     * если адрес $full уже есть в базе, просто возвращяет его короткий адрес
     * если адрес $full не существует, создается запись
     *
     * @param $full string
     * @return string
     */
    public static function getShortUrlFor($full)
    {
        $shortUrlCode = self::fetchOrCreateNew($full);
        return $shortUrlCode;
    }
}
