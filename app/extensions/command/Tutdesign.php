<?php

namespace app\extensions\command;

use app\extensions\social\TwitterAPI;
use app\models\Wp_post;

class Tutdesign extends CronJob
{

    public function run()
    {
        $this->_renderHeader();
        $postsToPost = Wp_post::getPostsForStream(time() - (30 * MINUTE));
        $twitter = new TwitterAPI([
            'consumer_key' => '8KowPOOLHqbLQPKt8JpwnLpTn',
            'consumer_secret' => 'Guna29r1BY8gEofz2amAIfPo1XcHJWNGI8Nzn6wiEwNlykAHhy',
            'user_token' => '76610418-JxUuuxQdUOaxc3uwxRjBUG4rXUdIABjNYAuhKP7uh',
            'user_secret' => '8qoejI0OTXHq56wp2QKPz16KoiB9w1sQQUncl6ilL20eh'
        ]);
        foreach ($postsToPost as $post) {
            $url = 'http://www.tutdesign.ru/cats/' . $post->category . '/' . $post->ID .'-' . $post->post_name . '.html';
            $data = [
                'message' => "$post->post_title $url",
                'picture' => '/var/new/wp-content/uploads/' . $post->thumbnail
            ];
            $twitter->postMessageToPage($data);
        }
        $this->_renderFooter(count($postsToPost) ." posts shared.");
    }
}
