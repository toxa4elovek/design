<?php
namespace app\extensions\helper;

/**
 * Class Brief
 *
 * Класс помощник для работы с текстами брифов и комментариев
 *
 * @package app\extensions\helper
 */
class Brief extends \lithium\template\Helper {

    /**
     * @var string паттерн для матчинга адреса имейла
     */
    public $emailPattern = "\b[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}\b";

    /**
     * @var string паттенр лоя матчинга адреса url
     */
    public $urlPattern = '/\b(https?):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i';

    /**
     * Метод определяет, нудно ли отображать текст брифа как текст, если проект старый
     *
     * @param $projectRecord
     * @return bool
     */
    public function isUsingPlainText($projectRecord) {
        return strtotime(($projectRecord->started) < strtotime('2013-07-25 16:30:00') && ($projectRecord->published == 1));
    }

    /**
     * Мето обрабатывает текст и создает hmtl ссылки для адресов в тексте
     *
     * @param $pitch
     * @param string $textField
     * @return string
     */
    public function briefDetails($pitch, $textField = 'description') {
        if($this->isUsingPlainText($pitch)){
            return $this->deleteHtmlTagsAndInsertHtmlLinkInText($pitch->{$textField});
        }else {
            $string = strip_tags($pitch->{$textField}, '<p><ul><ol><li><a><br><span>');
            return $this->insertHtmlLinkInText($string);
        }
    }

    /**
     * Метод заменяет простые адреса на html ссылки
     *
     * @param $text
     * @return string
     */
    public function insertHtmlLinkInText($text) {
        $text= $this->__autoReplaceHtmlLinks($text);
        return $this->stripEmail($text);
    }

    /**
     * Метод заменяет простые адреса на html ссылки и удаляет html код
     *
     * @param $text
     * @return string
     */
    public function deleteHtmlTagsAndInsertHtmlLinkInText($text) {
        $text = strip_tags(nl2br($text), '<br/><br>');
        $text = $this->__autoReplaceHtmlLinks($text);
        return $this->stripEmail($text);
    }

    /**
     * Метод удаляет теги, вставляет html ссылки и создает ссылки для упоминаний людей
     *
     * @param string $text Original text
     * @return string
     */
    public function deleteHtmlTagsAndInsertHtmlLinkInTextAndMentions($text) {
        $text = $this->__autoReplaceHtmlLinks($text);
        //@([a-zA-Zа-яА-Я]*)(\s[a-zA-Zа-яА-Я])?(\.\,)?
        //'/@([^@]*? [^@]\.)(,?)/u'
        $text = preg_replace('/@([a-zA-Zа-яА-Я]*)(\s[a-zA-Zа-яА-Я])?(\.\,)?/u', '<a href="#" class="mention-link" data-comment-to="$1">@$1$2$3</a>', $text);
        $text = str_replace('<br /><br />', '<br />', $text);
        $text = str_replace('<br><br>', '<br>', $text);
        return $text;
    }

    public function showRawComment($text) {
        return $text;
    }

    /**
     * Метод ищет адреса в строке и пытается вставить html ссылку
     *
     * @param $text
     * @return mixed
     */
    private function __autoReplaceHtmlLinks($text) {
        $checkRegExp = '^[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/*[-a-zA-Z0-9\(\)@:;|%_\+.~#?&//=]*)?^';
        $replacementRegExp = '!(^|\s|\(|>)([-a-zA-Z0-9:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/*[-a-zA-Z0-9\(\)@:;|%_\+.~#?&//=]*)?)!';
        if(preg_match($checkRegExp, $text)) {
            $text = preg_replace($replacementRegExp, '$1<a href="$2" target="_blank">$2</a>', $text);
        }
        while(preg_match('#href="(?!(http|https)://)(.*)"#', $text, $match)) {
            $text = preg_replace('#href="(?!(http|https)://)(.*)"#', 'href="http://$2"', $text, -1);
        }
        return $text;
    }
	
	/**
     * Метод производит замену email на ссылку с вопросом помощи
     *
     * @param $string
     * @return string
     */
    public function stripEmail($string){
        return preg_replace('#' . $this->emailPattern . '#',
            '<a target="_blank" href="https://godesigner.ru/answers/view/47">[Адрес скрыт]</a>', $string);
    }

	/**
     * Метод удаляет email адрес из строки
     *
     * @param $string
     * @return string
     */
    public function removeEmailClean($string) {
        return preg_replace('#' . $this->emailPattern . '#', '', $string);
    }

	/**
     * Метод удаляет url из строки
     *
     * @param $string
     * @return string
     */
    public function stripUrl($string) {
        return preg_replace($this->urlPattern, '', $string);
    }

	/**
     * Метод создает mailto ссылку из email адреса
     *
     * @param $string
     * @return string
     */
    public function linkEmail($string) {
        return preg_replace('#(' . $this->emailPattern . ')#',
            '<a href="mailto://$1">$1</a>', $string);
    }

    /**
     * Метод заменяет все невидимые переносы и пробелы
     *
     * @param $string
     * @param null $specialChars
     * @param string $replacement
     * @return string
     */
    public function trimAllInvisibleCharacter($string, $specialChars = null, $replacement = ' ') {
        if ($specialChars === null) {
            $specialChars   = "\\x00-\\x20";
        }
        return trim(preg_replace("/[".$specialChars."]+/", $replacement, $string), $specialChars);
    }

}