<?php

namespace app\controllers;

use app\models\BlogAd;
use \app\models\Post;
use \lithium\storage\Session;
use app\models\User;
use PHPHtmlParser\Dom;

class PostsController extends AppController
{

    /**
     * @var array Массив экшенов, доступных не залогинненым пользователям
     */
    public $publicActions = ['index', 'view', 'search'];

    /**
     * @var int переменная отвечает за количество выводимых постов на страницах-списков (оглавление и поиск)
     */
    public $postsOnIndexPage = 12;

    /**
     * Метод показа индексной страницы, используется в html и json форматах
     *
     * @return array
     */
    public function index()
    {
        $limit = $this->postsOnIndexPage;
        $page = 1;
        $conditions = [];
        if (isset($this->request->query['page'])) {
            $page = abs(intval($this->request->query['page']));
        }
        if (isset($this->request->query['tag'])) {
            $tag = $this->request->query['tag'];
            $conditions += ['tags' => ['LIKE' => '%' . $tag . '%']];
        }
        if (isset($this->request->query['author'])) {
            $conditions += ['user_id' => (int) $this->request->query['author']];
        }
        if ((Session::write('user.id' > 0)) && (Session::read('user.blogpost') != null)) {
            setcookie('counterdata', "", time() - 3600, '/');
            Session::delete('user.blogpost');
        }

        if (User::checkRole('editor') || User::checkRole('author')) {
            $posts = Post::all(['conditions' => $conditions, 'page' => $page, 'limit' => $limit, 'order' => ['created' => 'desc'], 'with' => ['User']]);
            $editor = 1;
        } else {
            $posts = Post::all(['conditions' => ['published' => 1, 'Post.created' => ['<=' => date('Y-m-d H:i:s')]] + $conditions, 'page' => $page, 'limit' => $limit, 'order' => ['created' => 'desc'], 'with' => ['User']]);
        }
        $postsList = [];
        foreach ($posts as $post) {
            $post->timezonedCreated = date('c', strtotime($post->created));
            $postsList[] = $post->data();
        }
        $search = (isset($this->request->query['search'])) ? urldecode(filter_var($this->request->query['search'], FILTER_SANITIZE_STRING)) : '';

        return compact('posts', 'postsList', 'editor', 'search', 'conditions');
    }

    /**
     * Метод поиска по запросу
     *
     * @return array|object|void
     */
    public function search()
    {
        if (isset($this->request->query['search'])) {
            require_once LITHIUM_APP_PATH . '/libraries/sphinxapi.php';
            $client = new \SphinxClient();
            $client->open();
            error_reporting(0);
            $client->SetMatchMode(SPH_MATCH_ANY);
            $limit = $this->postsOnIndexPage;
            $page = 1;
            if (isset($this->request->query['page'])) {
                $page = abs(intval($this->request->query['page']));
            }
            $client->SetLimits($page - 1, $limit);
            $client->SetFilter('published', [1]);
            $searchCondition = urldecode(filter_var($this->request->query['search'], FILTER_SANITIZE_STRING));
            $tempWords = explode(' ', $searchCondition);
            foreach ($tempWords as $index => &$searchWord) {
                if ($searchWord == '') {
                    unset($tempWords[$index]);
                    continue;
                }
                $searchWord = mb_eregi_replace('[^A-Za-z0-9а-яА-Я]', '', $searchWord);
                $searchWord = trim($searchWord);
            }
            $words = [$searchCondition];
            foreach ($tempWords as $subwords) {
                $words[] = $subwords;
            }
            $posts = new \lithium\util\Collection();
            foreach ($words as $word) {
                /*$result = Post::all(array('conditions' => array(
                    'OR' => array(
                        'title' => array('LIKE' => '%' . $word . '%'),
                        'short' => array('LIKE' => '%' . $word . '%'),
                        'full' => array('LIKE' => '%' . $word . '%'),
                    ),
                    'published' => 1,
                    'Post.created' => array('<=' => date('Y-m-d H:i:s')),
                ),
                    'page' => $page,
                    'limit' => $limit,
                    'order' => array('created' => 'desc'),
                    'with' => array('User'),
                ));*/
                $searchQuery = $client->Query($word, 'blog');
                //var_dump(array_keys($searchQuery['matches']));
                $postIds = array_keys($searchQuery['matches']);
                foreach ($postIds as $postId) {
                    $post = Post::first(['conditions' => ['Post.id' => $postId], 'with' => ['User']]);
                    $posts[$post->id] = $post;
                }
                continue;
            }
            $search = implode(' ', $words);

            $editor = (User::checkRole('editor') || User::checkRole('author')) ? 1 : 0;
            $postsList = [];
            foreach ($posts as $post) {
                $post->timezonedCreated = date('c', strtotime($post->created));
                $postsList[] = $post->data();
            }
            if ($this->request->is('json')) {
                return compact('postsList', 'posts', 'search', 'editor');
            }
            $search = (isset($this->request->query['search'])) ? urldecode(filter_var($this->request->query['search'], FILTER_SANITIZE_STRING)) : '';
            return $this->render(['template' => 'index', 'data' => compact('postsList', 'posts', 'search', 'editor')]);
        }
        return $this->redirect('/posts');
    }

    /**
     * Метод сохранения поста (нового или существующего)
     *
     * @return object
     */
    public function save()
    {
        if ((User::checkRole('editor')) || (User::checkRole('author'))) {
            if ((!empty($this->request->data['id'])) && ($this->request->data['id'])) {
                $post = Post::first($this->request->data['id']);
            } else {
                unset($this->request->data['id']);
                $post = Post::create();
                $post->user_id = $this->userHelper->getId();
            }
            $post->set($this->request->data);
            $tagsArray = [];
            foreach (explode(',', preg_replace('/[\[\]@\"]/', '', $this->request->data['tags'])) as $tag) {
                $tagsArray[] = trim($tag);
            }
            $tagsString = implode('|', $tagsArray);
            if ((isset($this->request->data['published'])) && (($this->request->data['published'] == 'on') || ($this->request->data['published'] == 1))) {
                $published = '1';
            } else {
                if (!isset($this->request->data['id'])) {
                    $published = '0';
                } else {
                    $published = $post->published;
                }
            }
            $post->tags = $tagsString;
            $post->published = $published;
            $post->save();
            Post::lock($post->id, $this->userHelper->getId());
            Post::updateLastEditTime($post->id);
            return $post->data();
        } else {
            return $this->redirect('/posts');
        }
    }

    /**
     * Метод просмотра поста из блога
     *
     * @return array|object
     */
    public function view()
    {
        if (!empty($this->request->query['search'])) {
            return $this->redirect('/posts/search?search=' . $this->request->query['search']);
        }

        if (($post = Post::first(['conditions' => ['Post.id' => $this->request->id], 'with' => ['User']])) && ($post->published == 1 || (User::checkRole('author') || User::checkRole('editor')))) {
            if ((Session::write('user.id' > 0)) && (Session::read('user.blogpost') != null)) {
                Session::delete('user.blogpost');
                setcookie('counterdata', '', time() - 3600, '/');
            }
            $tags = explode('|', $post->tags);
            Post::increaseCounter($this->request->id);
            $post->views += 1;
            $post->save();
            $searchIds = [];
            foreach ($tags as $tag) {
                $related = Post::all(['conditions' => [
                    'tags' => ['LIKE' => '%' . $tag . '%'],
                    'id' => ['!=' => $post->id],
                    'published' => 1,
                    'Post.created' => ['<=' => date('Y-m-d H:i:s')]
                ],                    'order' => ['RAND()']]);
                foreach ($related as $relatedPost) {
                    if (isset($searchIds[$relatedPost->id])) {
                        $searchIds[$relatedPost->id] += 1;
                    } else {
                        $searchIds[$relatedPost->id] = 1;
                    }
                }
            }
            $top = array_keys(array_slice($searchIds, 0, 3, true));
            if ($top) {
                $related = Post::all(['conditions' => ['id' => $top]]);
            }
            if ($post->blog_ad_id != 0) {
                function getFirstParagraph($string)
                {
                    $string = substr($string, 0, strpos($string, "</p>")+4);
                    return $string;
                }
                $snippet = BlogAd::first($post->blog_ad_id);
                $paragraph = getFirstParagraph($post->full);
                $post->full = str_replace($paragraph, $paragraph . $snippet->text, $post->full);
            }
            $postNonBreakList = ['и', 'в', 'для', 'не', 'на', 'с', '&mdash;', 'по'];
            foreach ($postNonBreakList as $word) {
                $postNonBreakList[] = Post::mb_ucfirst($word);
            }
            foreach ($postNonBreakList as $word) {
                $pattern = "(\s)($word)\s";
                $post->full = preg_replace("/$pattern/im", '$1$2&nbsp;', $post->full);
                $post->short = preg_replace("/$pattern/im", '$1$2&nbsp;', $post->short);
            }
            return compact('post', 'related');
        } else {
            throw new \Exception('Public:Запись в блоге не найдена.', 404);
        }
    }

    /**
     * Метод показа страницы нового поста
     *
     * @return array|object
     */
    public function add()
    {
        if (false === (User::checkRole('author') or User::checkRole('editor'))) {
            return $this->redirect('/posts');
        }
        $commonTags = Post::getCommonTags();
        $snippets = BlogAd::all();
        return compact('commonTags', 'snippets');
    }

    /**
     * Метод показа страницы редактирования поста
     *
     * @return array|object
     */
    public function edit()
    {
        if ($this->request->id && (User::checkRole('editor') || User::checkRole('author'))) {
            if ($post = Post::first($this->request->id)) {
                Post::lock($this->request->id, $this->userHelper->getId());
                $snippets = BlogAd::all();
                return compact('post', 'snippets');
            } else {
                return $this->redirect('/posts/index');
            }
        } else {
            return $this->redirect('/posts');
        }
    }

    /**
     * Метод удаления поста.
     *
     * @return object
     */
    public function delete()
    {
        if (User::checkRole('editor') or User::checkRole('author')) {
            if ($post = Post::first($this->request->id)) {
                $post->delete();
            }
        }
        return $this->redirect('/posts');
    }

    /**
     * Метод для обновления активности редактирования
     *
     * @return array
     */
    public function updateEditTime()
    {
        $result = false;
        if (Post::isLockedByMe($this->request->id, Session::read('user.id'))) {
            $result = Post::updateLastEditTime($this->request->id);
        }
        return compact('result');
    }
}
