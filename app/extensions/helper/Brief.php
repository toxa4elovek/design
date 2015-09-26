<?php
namespace app\extensions\helper;

class Brief extends \lithium\template\Helper {

    public $emailPattern = "\b[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}\b";
    public $urlPattern = '/\b(https?):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i';

    function isUsingPlainText($pitch) {
        if((strtotime($pitch->started) < strtotime('2013-07-25 16:30:00')) or ($pitch->id == '102442')) {
            return true;
        }
        return false;
    }

    function briefDetails($string, $pitch) {
        if($this->isUsingPlainText($pitch)){
            return $this->e($string);
        }else {
            $string = strip_tags($string, '<p><ul><ol><li><a><br><span>');
            $string = preg_replace('@(<a>)(.*?)(</a>)@', '<a class="check_url" href="$2">$2$3', $string);

            $num = preg_match_all('@<a class="check_url" href="(.*?)">.*?</a>@Ui', $string, $matches, PREG_SET_ORDER);
            if ($num > 0) {
                foreach ($matches as $match) {
                    if (!preg_match("~^(?:f|ht)tps?://~i", $match[1])) {
                        $linkToFixRegExp = '@(<a class="check_url" href=")(' . $match[1] . ')(">' . $match[1] . '</a>)@';
                        $url = "http://" . $match[1];
                        $string = preg_replace($linkToFixRegExp, '<a href="' . $url . '$3', $string);

                    }
                }

            }
            return $this->softE($string);
        }
    }

    function softE($string) {
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
        $string = nl2br(strip_tags($string));
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
        $string = str_replace('<br /><br />', '<br />', $string);
        $string = str_replace('<br><br>', '<br>', $string);
        return $string;
    }
	
	/**
     * Метод производит замену email на ссылку с вопросом помощи
     *
     * @param $string
     * @return string
     */
    function stripemail($string){
        return preg_replace('#' . $this->emailPattern . '#',
            '<a target="_blank" href="http://www.godesigner.ru/answers/view/47">[Адрес скрыт]</a>', $string);
    }

	/**
     * Метод удаляет email адрес из строки
     *
     * @param $string
     * @return string
     */
    function removeEmailClean($string) {
        return preg_replace('#' . $this->emailPattern . '#', '', $string);
    }

	/**
     * Метод удаляет url из строки
     *
     * @param $string
     * @return string
     */
    function stripurl($string) {
        return preg_replace($this->urlPattern, '', $string);
    }

	/**
     * Метод создает mailto ссылку из email адреса
     *
     * @param $string
     * @return string
     */
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
