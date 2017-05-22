<?php
namespace app\extensions\helper;

use app\extensions\helper\NameInflector;

class HtmlExtended extends \lithium\template\helper\Html
{

    protected function _init()
    {
        parent::_init();
    }

    public function title($params, $vars, $notags = false)
    {
        extract($vars);
        $title = 'Биржа дизайнеров-фрилансеров Go Designer';
        if (preg_match('@^/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Биржа дизайнеров-фрилансеров Go Designer';
        }
        if (($params['controller'] === 'answers') && ($params['action'] === 'index')):
            $title = 'Помощь';
        endif;
        if (($params['controller'] === 'answers') && ($params['action'] === 'view')):
            $title = $answer->title;
        endif;
        if (preg_match('@^/experts/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Эксперты Go Designer';
        }
        if (preg_match('@^/experts/view/\d\d?/$@', $_SERVER['REQUEST_URI'])) {
            $title = "$expert->name – эксперт Go Designer";
        }
        if (preg_match('@^/fastpitch/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Логотип в 1 клик: быстрый заказ лого без заполнения брифа';
        }
        if (preg_match('@^/golden-fish/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Дизайн на аутсорс: бизнес-план «Золотая рыбка» от Go Designer';
        }
        if (preg_match('@^/golden-fish/how-it-works/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Бизнес-план «Золотая рыбка»: как это работает';
        }
        if (preg_match('@^/pages/about/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'О проекте Go Designer';
        }
        if (preg_match('@^/pages/brief/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Заполним бриф за вас';
        }
        if (preg_match('@^/pages/contacts/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Контакты Go Designer';
        }
        if (preg_match('@^/pages/howitworks/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Как работает Go Designer';
        }
        if (preg_match('@^/pages/referal/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Пригласите друзей на Go Designer';
        }
        if (preg_match('@^/pages/special/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Вернем деньги';
        }
        if (preg_match('@^/pages/subscribe/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Тарифы на постоянное сотрудничество с дизайнерами Go Designer';
        }
        if (preg_match('@^/pages/terms-and-privacy/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Правила и лицензионное соглашение';
        }
        if (preg_match('@^/pages/to_designers/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Дизайнерам';
        }
        if (preg_match('@^/pitches/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Все дизайн-проекты Go Designer';
        }
        if (preg_match('@^/pitches/designers/\d+/$@', $_SERVER['REQUEST_URI'])) {
            $title = "Участники проекта $pitch->title";
        }
        if (preg_match('@^/pitches/details/\d+/$@', $_SERVER['REQUEST_URI'])) {
            $title = "Бриф на $pitch->title";
        }
        if (preg_match('@^/pitches/view/\d+/$@', $_SERVER['REQUEST_URI'])) {
            $title = $pitch->title;
        }
        if (preg_match('@^/posts/view/\d+/$@', $_SERVER['REQUEST_URI'])) {
            $title = $post->title;
        }
        if (preg_match('@^/posts/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Блог о дизайне';
        }
        if (preg_match('@^/posts/\?tag=.+$@', $_SERVER['REQUEST_URI'])) {
            $title = "Статьи по теме $request->query['tag']";
        }
        if (preg_match('@^/questions/$@', $_SERVER['REQUEST_URI'])) {
            $title = 'Тест на профпригодность дизайнера';
        }
        /*
        if (preg_match('@^//$@', $_SERVER['REQUEST_URI'])) {
            $title = '';
        }
        */
        /*
        if ((isset($solution)) && (isset($solution->images)) && (isset($solution->images['solution_solutionView'])) && (is_object($solution->pitch)) && (is_object($solution->user))):
            $title = 'Go Designer; ' . $solution->pitch->title . '; Дизайнер: ' . NameInflector::renderName($solution->user->first_name, $solution->user->last_name);
        endif;
        if ((isset($post)) && (isset($post->title))):
            $title = $post->title . '; Логотип, сайт и дизайн от всего креативного интернет сообщества';
        endif;
        if ((strtolower($params['controller']) == 'pages') && ($params['action'] == 'view') && ($params['args'][0] == 'howitworks')):
            $title = 'Как это работает';
        endif;
        if (($params['controller'] == 'pages') && ($params['action'] == 'view') && ($params['args'][0] == 'to_designers')):
            $title = 'Дизайнерам';
        endif;
        if (($params['controller'] == 'pages') && ($params['action'] == 'contacts')):
            $title = 'Контакты';
        endif;
        if ((($params['controller'] == 'posts')) && ($params['action'] == 'index')):
            $title = 'Блог';
        endif;
        if (($params['controller'] == 'Pitches') && ($params['action'] == 'index')):
            $title = 'Все проекты';
        endif;
        if (($params['controller'] == 'pitches') && ($params['action'] == 'create')):
            $title = 'Создание проекта';
        endif;
        if ((isset($pitch)) && (($params['controller'] == 'pitches') && (($params['action'] == 'details') || ($params['action'] == 'viewsolution') || ($params['action'] == 'view')))):
            $title = $pitch->title;
        endif;
        */
        /*if ((($params['controller'] == 'posts')) && ($params['action'] == 'view')):
            $title = $post->title;
        endif;
        if ((($params['controller'] == 'users')) && ($params['action'] == 'registration')):
            $title = 'Зарегистрироваться';
        endif;
        if ((($params['controller'] == 'users')) && ($params['action'] == 'login')):
            $title = 'Войти';
        endif;
        if ((($params['controller'] == 'pages')) && ($params['action'] == 'view') && ($params['args'][0] == 'about')):
            $title = 'О проекте';
        endif;
        $title .= ' | GoDesigner';
        if (($params['controller'] == 'solutions') && ($params['action'] == 'logosale')):
            $title = 'Распродажа логотипов на GoDesigner.ru';
        endif;
        */
        if ($notags) {
            return $title;
        } else {
            return '<title>' . $title . '</title>';
        }
    }
}
