<?php

namespace app\controllers;

use \app\models\Post;
use \lithium\storage\Session;
use app\models\User;
use lithium\analysis\Logger;

class PostsController extends \app\controllers\AppController {

    /**
     * @var array Массив экшенов, доступных не залогинненым пользователям
     */
    public $publicActions = array('index', 'view', 'search');

    /**
     * Метод показа индексной страницы, используется в html и json форматах
     *
     * @return array
     */
    public function index() {
        $limit = 7;
        $page = 1;
        $tag = false;
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

        $total = Post::count(array('conditions' => $conditions));
        $total = ceil($total / $limit);
        $currenttag = $tag;

        return compact('posts', 'total', 'page', 'currenttag', 'editor');
    }

    public function search() {
        if ($this->request->is('json') && isset($this->request->query['search'])) {
            $limit = 7;
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
                $posts = $posts->data();
            } else {
                $posts = array();
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
                    $posts += $result->data();
                }
            }
            $search = implode(' ', $words);

            return compact('posts', 'search');
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
            if((isset($this->request->data['published'])) && ($this->request->data['published'] == 'on')) {
                $published = '1';
            }else {
                $published = '0';
            }

            $post->tags = $tagsString;
            $post->published = $published;

            $post->save();
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

}