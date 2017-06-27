<?php

namespace app\extensions\seo;

use app\extensions\storage\Rcache;

class SitemapReader {

    public static function getLastModifiedUnixForUrl($needle) {
        $fullUrl = 'https://godesigner.ru' . $needle;
        if(!$array = Rcache::read('sitemap')) {
            $sitemapFilePath = LITHIUM_APP_PATH . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'sitemap.xml';
            $string = file_get_contents($sitemapFilePath);
            $xml = simplexml_load_string($string);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);
            Rcache::write('sitemap', $array, [], '+12 hours');
        }
        $lastModifiedUnix = time() - 10 * DAY;
        foreach($array['url'] as $url) {
            if($url['loc'] === $fullUrl) {
                $lastModifiedUnix = strtotime($url['lastmod']);
                break;
            }
        }
        return $lastModifiedUnix;
    }

    public static function getLastModifiedForUrl($needle) {
        return gmdate("D, d M Y H:i:s \G\M\T", self::getLastModifiedUnixForUrl($needle));
    }

    private static function _xml2array($xmlObject, $out =  [])
    {
        foreach ((array) $xmlObject as $index => $node) {
            $out[$index] = is_object($node) ? self::_xml2array($node) : $node;
        }

        return $out;
    }

}