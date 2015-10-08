<?php

namespace app\controllers;

use \app\models\Post;
use \lithium\storage\Session;
use app\models\User;

class PostsController extends AppController {

    /**
     * @var array Массив экшенов, доступных не залогинненым пользователям
     */
    public $publicActions = array('index', 'view', 'search');

    /**
     * @var int переменная отвечает за количество выводимых постов на страницах-списков (оглавление и поиск)
     */
    public $postsOnIndexPage = 12;

    /**
     * Метод показа индексной страницы, используется в html и json форматах
     *
     * @return array
     */
    public function index() {
        $limit = $this->postsOnIndexPage;
        $page = 1;
        $conditions = array();
        if(isset($this->request->query['page'])) {
            $page = abs(intval($this->request->query['page']));
        }
        if(isset($this->request->query['tag'])) {
            $tag = $this->request->query['tag'];
            $conditions += array('tags' => array('LIKE' => '%' . $tag . '%'));
        }
        if((Session::write('user.id' > 0)) && (Session::read('user.blogpost') != null)) {
            setcookie('counterdata', "", time() - 3600, '/');
            Session::delete('user.blogpost');
        }

        if(User::checkRole('editor') || User::checkRole('author')) {
            $posts = Post::all(array('conditions' => $conditions, 'page' => $page, 'limit' => $limit,'order' => array('created' => 'desc'), 'with' => array('User')));
            $editor = 1;
        }else {
            $posts = Post::all(array('conditions' => array('published' => 1, 'Post.created' => array('<=' => date('Y-m-d H:i:s'))) + $conditions, 'page' => $page, 'limit' => $limit, 'order' => array('created' => 'desc'), 'with' => array('User')));
        }
        $postsList = array();
        foreach($posts as $post) {
            $post->timezonedCreated = date('c', strtotime($post->created));
            $postsList[] = $post->data();
        }
        $search = (isset($this->request->query['search'])) ? urldecode(filter_var($this->request->query['search'], FILTER_SANITIZE_STRING)) : '';

        return compact('posts', 'postsList', 'editor', 'search');
    }

    /**
     * Метод поиска по запросу
     *
     * @return array|object|void
     */
    public function search() {
        if (isset($this->request->query['search'])) {
            $limit = $this->postsOnIndexPage;
            $page = 1;
            if (isset($this->request->query['page'])) {
                $page = abs(intval($this->request->query['page']));
            }
            $searchCondition = urldecode(filter_var($this->request->query['search'], FILTER_SANITIZE_STRING));
            $words = explode(' ', $searchCondition);
            foreach ($words as $index => &$searchWord) {
                if ($searchWord == '') {
                    unset($words[$index]);
                    continue;
                }
                $searchWord = mb_eregi_replace('[^A-Za-z0-9а-яА-Я]', '', $searchWord);
                $searchWord = trim($searchWord);
            }
            if (count($words) == 1) {
                $posts = Post::all(array('conditions' => array(
                    'OR' => array(
                        'title' => array('LIKE' => '%' . $words[0] . '%'),
                        'full' => array('LIKE' => '%' . $words[0] . '%'),
                    ),
                    'published' => 1,
                    'Post.created' => array('<=' => date('Y-m-d H:i:s')),
                    ),
                    'page' => $page,
                    'limit' => $limit,
                    'order' => array('created' => 'desc'),
                    'with' => array('User'),
                ));
            } else {
                $posts = new \lithium\util\Collection();
                foreach ($words as $word) {
                    $result = Post::all(array('conditions' => array(
                        'OR' => array(
                            'title' => array('LIKE' => '%' . $word . '%'),
                            'full' => array('LIKE' => '%' . $word . '%'),
                        ),
                        'published' => 1,
                        'Post.created' => array('<=' => date('Y-m-d H:i:s')),
                        ),
                        'page' => $page,
                        'limit' => $limit,
                        'order' => array('created' => 'desc'),
                        'with' => array('User'),
                    ));
                    foreach ($result as $post) {
                        $posts[$post->id] = $post;
                    }
                }
            }
            $search = implode(' ', $words);

            $editor = (User::checkRole('editor') || User::checkRole('author')) ? 1 : 0;
            $postsList = array();
            foreach($posts as $post) {
                $postsList[] = $post->data();
            }
            if ($this->request->is('json')) {
                return compact('postsList', 'posts', 'search', 'editor');
            }
            $search = (isset($this->request->query['search'])) ? urldecode(filter_var($this->request->query['search'], FILTER_SANITIZE_STRING)) : '';
            return $this->render(array('template' => 'index', 'data' => compact('postsList', 'posts', 'search', 'editor')));
        }
        return $this->redirect('/posts');
    }

    /**
     * Метод сохранения поста (нового или существующего)
     *
     * @return object
     */
    public function save() {
        if((User::checkRole('editor')) || (User::checkRole('author'))) {
            if((!empty($this->request->data['id'])) && ($this->request->data['id'])) {
                $post = Post::first($this->request->data['id']);
            }else {
                unset($this->request->data['id']);
                $post = Post::create();
                $post->user_id = Session::read('user.id');
            }
            $post->set($this->request->data);
            $tagsArray = array();
            foreach(explode(',', preg_replace('/[\[\]@\"]/', '', $this->request->data['tags'])) as $tag) {
                $tagsArray[] = trim($tag);
            }
            $tagsString = implode('|', $tagsArray);
            if((isset($this->request->data['published'])) && (($this->request->data['published'] == 'on') || ($this->request->data['published'] == 1))) {
                $published = '1';
            }else {
                $published = '0';
            }

            $post->tags = $tagsString;
            $post->published = $published;

            $post->save();
            Post::lock($post->id, Session::read('user.id'));
            Post::updateLastEditTime($post->id);
            return $post->data();
        }else {
            return $this->redirect('/posts');
        }
    }

    /**
     * Метод просмотра поста из блога
     *
     * @return array|object
     */
    public function view() {
        if (!empty($this->request->query['search'])) {
            return $this->redirect('/posts/search?search=' . $this->request->query['search']);
        }

        if(($post = Post::first(array('conditions' => array('Post.id' => $this->request->id), 'with' => array('User')))) && ($post->published == 1 || (User::checkRole('author') || User::checkRole('editor')))) {
            if((Session::write('user.id' > 0)) && (Session::read('user.blogpost') != null)) {
                Session::delete('user.blogpost');
                setcookie('counterdata', '', time() - 3600, '/');
            }
            $tags = explode('|', $post->tags);
            Post::increaseCounter($this->request->id);
            $post->views += 1;
            $post->save();
            $searchIds = array();
            foreach($tags as $tag) {
                $related = Post::all(array('conditions' => array(
                    'tags' => array('LIKE' => '%' . $tag . '%'),
                    'id' => array('!=' => $post->id),
                    'published' => 1,
                    'Post.created' => array('<=' => date('Y-m-d H:i:s'))
                ),                    'order' => array('RAND()')));
                foreach($related as $relatedPost) {
                    if(isset($searchIds[$relatedPost->id])) {
                        $searchIds[$relatedPost->id] += 1;
                    }else {
                        $searchIds[$relatedPost->id] = 1;
                    }
                }

            }
            $top = array_keys(array_slice($searchIds, 0, 3, true));
            if($top) {
                $related = Post::all(array('conditions' => array('id' => $top)));
            }
            return compact('post', 'related');
        }else {
            return $this->redirect('/posts');
        }
    }

    /**
     * Метод показа страницы нового поста
     *
     * @return array|object
     */
    public function add() {
        if(false === (User::checkRole('author') or User::checkRole('editor'))) {
            return $this->redirect('/posts');
        }
        $commonTags = Post::getCommonTags();
        return compact('commonTags');
    }

    /**
     * Метод показа страницы редактирования поста
     *
     * @return array|object
     */
    public function edit() {
        if(User::checkRole('editor') or User::checkRole('author')) {
            if($post = Post::first($this->request->id)) {
                Post::lock($this->request->id, Session::read('user.id'));
                return compact('post');
            }else {
                return $this->redirect('/posts/index');
            }
        }else {
            return $this->redirect('/posts');
        }
    }

    /**
     * Метод удаления поста.
     *
     * @return object
     */
    public function delete() {
        if(User::checkRole('editor') or User::checkRole('author')) {
            if($post = Post::first($this->request->id)) {
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
    public function updateEditTime() {
        $result = false;
        if(Post::isLockedByMe($this->request->id, Session::read('user.id'))) {
            $result = Post::updateLastEditTime($this->request->id);
        }
        return compact('result');
    }

}
