<?php
namespace app\extensions\helper;

use app\extensions\helper\NameInflector;

class HtmlExtended extends \lithium\template\helper\Html
{

    protected function _init()
    {
        parent::_init();
    }

    public function description($vars) {
        extract($vars);
        $description = 'Биржа дизайнеров-фрилансеров Go Designer';
        if (preg_match('@^/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Биржа профессиональных дизайнеров-фрилансеров со всего мира. Закажите дизайн и получите множество решений вашей задачи.';
        }
        if (preg_match('@^/answers/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Все о работе с Go Designer: ответы на общие вопросы, помощь заказчикам, дизайнерам, финансовые вопросы, информация для юридических лиц.';
        }
        if (preg_match('@^/answers/view/\d+/$@', $_SERVER['REQUEST_URI'])) {
            $description = $this->__shortenTextIfNeeded($answer->text);
        }
        if (preg_match('@^/experts/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Нужно мнение профессионалов? Наши эксперты расскажут, какое креативное решение наиболее выигрышно, исходя из вашего брифа.';
        }
        if (preg_match('@^/experts/view/\d\d?/$@', $_SERVER['REQUEST_URI'])) {
            $description = $this->__shortenTextIfNeeded($expert->text);
        }
        if (preg_match('@^/fastpitch/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Создайте проект на лучший логотип, остальное мы сделаем за вас: заполним бриф, предоставим эксперта и сэкономим вам 4330 руб.';
        }
        if (preg_match('@^/golden-fish/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Решайте задачи клиентов силами дизайнеров и копирайтеров сервиса. Зарабатывайте на разнице гонораров. Мы вернем деньги, если идеи не понравятся.';
        }
        if (preg_match('@^/golden-fish/how-it-works/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Узнайте, как работать с бизнес-планом «Золотая рыбка» и зарабатывать на разнице гонораров.';
        }
        if (preg_match('@^/pages/about/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Go Designer – это виртуальная площадка, объединяющая дизайнеров и тех, кому нужны творческие решения.';
        }
        if (preg_match('@^/pages/brief/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Мы упростим работу и сэкономим ваше время, заполнив бриф за вас. Мы поможем сформулировать ваши ожидания и добиться желаемых результатов!';
        }
        if (preg_match('@^/pages/contacts/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Контакты Go Designer: телефон, e-mail, адрес, форма обратной связи.';
        }
        if (preg_match('@^/pages/howitworks/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Опишите, что вам нужно, дизайнеры предложат идеи, а вы выберете лучшее креативное решение.';
        }
        if (preg_match('@^/pages/referal/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Вы получите 500 рублей на телефон, когда ваши друзья создадут проект на Go Designer.';
        }
        if (preg_match('@^/pages/special/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Мы вернем вам деньги, если по истечении срока предложенные дизайнерами идеи не понравятся.';
        }
        if (preg_match('@^/pages/subscribe/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Станьте нашим абонентом, и вы сможете создавать проекты от 500 р. Мы обезопасим вас от рисков работы с фрилансерами и гарантируем выбор решений даже для небольших задач!';
        }
        if (preg_match('@^/pages/terms-and-privacy/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Пожалуйста, внимательно прочтите настоящее соглашение, прежде чем начать пользоваться сайтом godesigner.ru.';
        }
        if (preg_match('@^/pages/to_designers/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Выберите проект, предложите идею, победите и заработайте гонорар. У всех одинаковые шансы на победу, гарантия оплаты.';
        }
        if (preg_match('@^/pitches/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Все дизайн-проекты Go Designer: логотипы, сайты, упаковка и многое другое от профессиональных дизайнеров-фрилансеров со всего мира.';
        }
        if (preg_match('@^/pitches/designers/\d+/$@', $_SERVER['REQUEST_URI'])) {
            $description = "Участники конкурса на $pitch->title на godesigner.ru.";
        }
        if (preg_match('@^/pitches/details/\d+/$@', $_SERVER['REQUEST_URI'])) {
            $description = "Описание конкурса на $pitch->title на godesigner.ru.";
        }
        if (preg_match('@^/pitches/view/\d+/$@', $_SERVER['REQUEST_URI'])) {
            $description = "$pitch->title. Креативные решения от дизайнеров-профессионалов со всего мира.";
        }
        if (preg_match('@^/posts/view/\d+/$@', $_SERVER['REQUEST_URI'])) {
            $description = $this->__shortenTextIfNeeded($post->full);
        }
        if (preg_match('@^/posts/$@', $_SERVER['REQUEST_URI'])) {
            $description = 'Самое интересное и актуальное из мира дизайна, подборки лучших логотипов по тематикам и многое другое.';
        }
        if (preg_match('@^/posts/\?tag=.+$@', $_SERVER['REQUEST_URI'])) {
            $description = "Читайте самые интересные и актуальные статьи из мира дизайна по теме $request->query['tag'].";
        }
        if (preg_match('@^/questions/$@', $_SERVER['REQUEST_URI'])) {
            $description = '15 вопросов, 3 минуты на прохождение. Бонус для тех, кто с первого раза сдаст на «хорошо» и «отлично» и поделится результатами в соцсетях.';
        }
        return sprintf('<meta name="description" content="%s">', $description);
    }

    private function __shortenTextIfNeeded($text, $limit = 150) {
        $text = html_entity_decode(strip_tags($text));
        if (mb_strlen($text, 'UTF-8') > $limit) {
            $pos = mb_strpos($text, ' ', $limit);
            $text = mb_substr($text, 0, $pos) . '...';
        }
        return $text;
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
