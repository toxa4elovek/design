<?php
namespace app\extensions\helper;

class Faq extends \lithium\template\Helper {


    function show($questions) {
        $html = '';
        foreach($questions as $question):
            $html .= '<li style="text-shadow: -1px 0 0 #FFFFFF;"><a href="/answers/view/' . $question->id . '">' . $question->title . '</a></li>';
        endforeach;
        return '<ul>' . $html . '</ul>';
    }


}
