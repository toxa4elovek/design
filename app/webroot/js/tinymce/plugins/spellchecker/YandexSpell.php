<?php

class YandexSpell extends SpellChecker {

    public function __construct() {

    }

    /**
     * Spellchecks an array of words.
     *
     * @param {String} $lang Language code like sv or en.
     * @param {Array} $words Array of words to spellcheck.
     * @return {Array} Array of misspelled words.
     */
    function &checkWords($lang, $words) {
        $wordstr = implode(' ', $words);
        $words = $this->_getMatches($lang, $wordstr);
        return $words;
    }

    protected function &_getMatches($lang, $str) {
        $str = urlencode($str);
        $response = file_get_contents('http://speller.yandex.net/services/spellservice.json/checkText?text=' . $str);
        $decoded = json_decode($response, true);
        $result = array();
        foreach($decoded as $wrongWord) {
            $result['words'][$wrongWord['word']] = $wrongWord['s'];
        }
        return $result;
    }

}