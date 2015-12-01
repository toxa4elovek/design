<?php
namespace app\extensions\helper;

/**
 * Class Faq
 *
 * Класс помощник для вывода списка вопросов
 *
 * @package app\extensions\helper
 */
class Faq extends \lithium\template\Helper {

    /**
     * Метод возвращяет html код списка вопросов
     *
     * @param $questions
     * @return string
     */
    public function show($questions) {
        $html = '';
        foreach($questions as $question):
            $html .= $this->__getHtmlForQuestion($question);
        endforeach;
        return '<ul>' . $html . '<li><a href="/answers" class="more" style="padding-left: 0;">Все вопросы</a></li></ul>';
    }

    /**
     * Метод возвращяет html код для одного вопроса
     *
     * @param $question
     * @return string
     */
    private function __getHtmlForQuestion($question) {
        return '<li style="text-shadow: -1px 0 0 #FFFFFF;"><a href="/answers/view/' . $question->id . '">' . $question->title . '</a></li>';
    }

}
