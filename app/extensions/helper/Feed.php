<?php
namespace app\extensions\helper;

class Feed extends \lithium\template\Helper {

    /**
     * Метод определяет, является ли $url ссылкой на видео с ютуба или вимео
     *
     * @param $url
     * @return bool
     */
    public function isEmbeddedLink($url) {
        if(preg_match('@(vimeo.com/\d+|youtube.com\/watch\?v=)@', $url)) {
            return true;
        }
        return false;
    }

    /**
     * Метод генерирует код для вставки видео через айфрейм на видео по ссылке $url
     *
     * @param $url
     * @return string
     */
    public function generateEmbeddedIframe($url) {
        if($this->__isYoutubeLink($url)) {
            $videoId = $this->__getYoutubeVideoId($url);
            return '<iframe width="600" height="337" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allowfullscreen></iframe>';
        }elseif($this->__isVimeoLink($url)) {
            $videoId = $this->__getVimeoVideoId($url);
            return '<iframe src="https://player.vimeo.com/video/' . $videoId . '" width="600" height="337" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        }
    }

    /**
     * Метод вытаскивает номер видео из ютюб ссылки
     *
     * @param $url
     * @return mixed
     */
    private function __getYoutubeVideoId($url) {
        preg_match('@watch\?v=(.*)$@', $url, $matches);
        return $matches[1];
    }

    /**
     * Метод вытаскивает номер видео из вимео ссылки
     *
     * @param $url
     * @return mixed
     */
    private function __getVimeoVideoId($url) {
        preg_match('@vimeo.com\/(\d+)$@', $url, $matches);
        return $matches[1];
    }

    /**
     * Метод определяет, является ли ссылка на видео с ютюба
     *
     * @param $url
     * @return mixed
     */
    private function __isYoutubeLink($url) {
        if(preg_match('@youtube.com\/watch\?v=@', $url)) {
            return true;
        }
        return false;
    }

    /**
     * Метод определяет, является ли ссылка на видео с вимео
     *
     * @param $url
     * @return mixed
     */
    private function __isVimeoLink($url) {
        if(preg_match('@vimeo.com/\d+@', $url)) {
            return true;
        }
        return false;
    }

}