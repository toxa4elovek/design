<?php
namespace app\extensions\helper;

class Brief extends \lithium\template\Helper {

    public $emailPattern = "\b[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}\b";

    function e($string) {
        $string = strip_tags(nl2br($string), '<br>');
        $regex = '^[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/*[-a-zA-Z0-9\(\)@:;|%_\+.~#?&//=]*)?^';

        $regex2 = '!(^|\s|\()([-a-zA-Z0-9:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/*[-a-zA-Z0-9\(\)@:;|%_\+.~#?&//=]*)?)!';
        if(preg_match($regex, $string)) {
            $string = preg_replace($regex2, '$1<a href="$2" target="_blank">$2</a>', $string);
        }
        while(preg_match('#href="(?!(http|https)://)(.*)"#', $string, $match)) {
            $string = preg_replace('#href="(?!(http|https)://)(.*)"#', 'href="http://$2"', $string, -1);
        }
        return $this->stripemail($string);
    }

    function ee($string) {
        $string = strip_tags(nl2br($string), '<br>');
        $regex = '^[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/*[-a-zA-Z0-9\(\)@:;|%_\+.~#?&//=]*)?^';

        $regex2 = '!(^|\s|\()([-a-zA-Z0-9:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/*[-a-zA-Z0-9\(\)@:;|%_\+.~#?&//=]*)?)!';
        if(preg_match($regex, $string)) {
            $string = preg_replace($regex2, '$1<a href="$2" target="_blank">$2</a>', $string);
        }
        while(preg_match('#href="(?!(http|https)://)(.*)"#', $string, $match)) {
            $string = preg_replace('#href="(?!(http|https)://)(.*)"#', 'href="http://$2"', $string, -1);
        }
        return $string;
    }

    /**
     * Convert links in text to real links. Preserve mentions.
     *
     * @param string $string Original text
     * @return string
     */
    function eee($string) {
        $string = strip_tags(nl2br($string), '<br>');
        $regex = '^[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/*[-a-zA-Z0-9\(\)@:;|%_\+.~#?&//=]*)?^';

        $regex2 = '!(^|\s|\()([-a-zA-Z0-9:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/*[-a-zA-Z0-9\(\)@:;|%_\+.~#?&//=]*)?)!';
        if(preg_match($regex, $string)) {
            $string = preg_replace($regex2, '$1<a href="$2" target="_blank">$2</a>', $string);
        }
        while(preg_match('#href="(?!(http|https)://)(.*)"#', $string, $match)) {
            $string = preg_replace('#href="(?!(http|https)://)(.*)"#', 'href="http://$2"', $string, -1);
        }
        // Mentions
        $string = preg_replace('/@([^@]*? [^@]\.)(,?)/u', '<a href="#" class="mention-link" data-comment-to="$1">@$1$2</a>', $string);
        return $string;
    }

    function stripemail($string){
        return preg_replace('#' . $this->emailPattern . '#',
            '<a target="_blank" href="http://www.godesigner.ru/answers/view/47">[Адрес скрыт]</a>', $string);
    }

    function linkemail($string) {
        return preg_replace('#(' . $this->emailPattern . ')#',
            '<a href="mailto://$1">$1</a>', $string);
    }

    function trim_all($str, $what = NULL, $with = ' ') {
        if ($what === NULL) {
            //  Character      Decimal      Use
            //  "\0"            0           Null Character
            //  "\t"            9           Tab
            //  "\n"           10           New line
            //  "\x0B"         11           Vertical Tab
            //  "\r"           13           New Line in Mac
            //  " "            32           Space

            $what   = "\\x00-\\x20";    //all white-spaces and control chars
        }

        return trim(preg_replace("/[".$what."]+/", $with, $str), $what);
    }
}
