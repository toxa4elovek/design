<?php
namespace app\extensions\helper;

use app\extensions\helper\NameInflector;

class HtmlExtended extends \lithium\template\helper\Html {

    protected function _init() {
        parent::_init();
    }

    public function title($params, $vars, $notags = false) {
        extract($vars);
        $title = 'Логотип, сайт и дизайн от всего креативного интернет сообщества';
        if((isset($solution)) && (isset($solution->images)) && (isset($solution->images['solution_solutionView'])) && (is_object($solution->pitch)) && (is_object($solution->user))):
            $title = 'Go Designer; ' . $solution->pitch->title . '; Дизайнер: ' . NameInflector::renderName($solution->user->first_name, $solution->user->last_name);
        endif;
        if((isset($post)) && (isset($post->title))):
            $title = $post->title . '; Логотип, сайт и дизайн от всего креативного интернет сообщества';
        endif;
        if((strtolower($params['controller']) == 'pages') && ($params['action'] == 'view') && ($params['args'][0] == 'howitworks')):
            $title = 'Как это работает';
        endif;
        if(($params['controller'] == 'pages') && ($params['action'] == 'view') && ($params['args'][0] == 'to_designers')):
            $title = 'Дизайнерам';
        endif;
        if(($params['controller'] == 'pages') && ($params['action'] == 'contacts')):
            $title = 'Контакты';
        endif;
        if((($params['controller'] == 'posts')) && ($params['action'] == 'index')):
            $title = 'Блог';
        endif;
        if(($params['controller'] == 'Pitches') && ($params['action'] == 'index')):
            $title = 'Все проекты';
        endif;
        if(($params['controller'] == 'pitches') && ($params['action'] == 'create')):
            $title = 'Создание проекта';
        endif;
        if(($params['controller'] == 'pitches') && (($params['action'] == 'details') || ($params['action'] == 'viewsolution') || ($params['action'] == 'view'))):
            $title = $pitch->title;
        endif;
        if((($params['controller'] == 'answers')) && ($params['action'] == 'index')):
            $title = 'Помощь';
        endif;
        if((($params['controller'] == 'answers')) && ($params['action'] == 'view')):
            $title = $answer->title;
        endif;
        if((($params['controller'] == 'posts')) && ($params['action'] == 'view')):
            $title = $post->title;
        endif;
        if((($params['controller'] == 'users')) && ($params['action'] == 'registration')):
            $title = 'Зарегистрироваться';
        endif;
        if((($params['controller'] == 'users')) && ($params['action'] == 'login')):
            $title = 'Войти';
        endif;
        if((($params['controller'] == 'pages')) && ($params['action'] == 'view') && ($params['args'][0] == 'about')):
            $title = 'О проекте';
        endif;
        $title .= ' | GoDesigner';
        if(($params['controller'] == 'solutions') && ($params['action'] == 'logosale')):
            $title = 'Распродажа логотипов на GoDesigner.ru';
        endif;
        if($notags) {
            return $title;
        }else {
            return '<title>' . $title . '</title>';
        }
    }


}
