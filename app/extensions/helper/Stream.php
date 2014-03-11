<?php

namespace app\extensions\helper;

use app\extensions\storage\Rcache;

class Stream extends \lithium\template\Helper {

    /**
     * Метод возвращает html для формирования твиттер ленты. Твиты берутся из кеша.
     *
     * @param int $num - количество твитов для показа
     * @return string
    */
    public function renderStream($num = 10, $header = true) {
        if ($header) {
            $header = '<h2 style="font:20px \'RodeoC\',serif; text-shadow: 1px 0 1px #FFFFFF;color:#999;text-transform: uppercase; text-align: center;margin-bottom:10px">Твиттер лента</h2><ul id="sidebar-content">';
        } else {
            $header = '<ul id="sidebar-content">';
        }
        if($data = Rcache::read('twitterstream')):
            $tweets = $data['statuses'];
            $content = '';
            $count = 1;
            foreach($tweets as $tweet):
                $text = $tweet['text'];
                if (isset($tweet['type']) && $tweet['type'] == 'tutdesign') {
                    $image = '<a href="http://tutdesign.ru/cats/' . $tweet['category'] . '/' . $tweet['id'] . '-' . $tweet['slug'] . '.html" target="_blank"><img src="http://tutdesign.ru/wp-content/uploads/' . $tweet['thumbnail'] . '" width="171" height="114"></a>';
                    $link = '<a style="display:inline;color:#ff585d" target="_blank" href="http://tutdesign.ru/cats/' . $tweet['category'] . '">http://tutdesign.ru/cats/' . $tweet['category'] . '</a>';
                    $content .= '<p class="regular" style="line-height:20px;">' . $image . '<br>' . $text . '<br>' . $link . '</p>';
                } else {
                    foreach($tweet['entities']['hashtags'] as $hashtag) {
                        $text = str_replace('#' . $hashtag['text'], '<a style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/search/%23' . $hashtag['text'] . '">' . '#' . $hashtag['text']  . '</a>', $text);
                    }
                    foreach($tweet['entities']['urls'] as $url) {

                        $text = str_replace($url['url'], '<a style="display:inline;color:#ff585d" target="_blank" href="'. $url['url'] . '">' . $url['display_url']  . '</a>', $text);
                    }
                    foreach($tweet['entities']['user_mentions'] as $user) {

                         $text = str_replace('@' . $user['screen_name'], '<a style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/' . $user['screen_name'] . '">' . '@' . $user['screen_name']  . '</a>', $text);
                    }
                    $user = '<a style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/'. $tweet['user']['screen_name'] . '">@' . $tweet['user']['screen_name']  . '</a>';
                    if($count == 1):
                        $content .= '<li style="padding-left:5px;padding-right:5px;padding-top:10px;">';
                    else:
                        $content .= '<li style="padding-left:5px;">';
                    endif;
                    $content .= '<p class="regular" style="line-height:20px;">' . $user . ' ' . $text . '</p>';
                }
                $content .= '<p class="time" title="' . date('Y-m-d H:i:s', strtotime($tweet['created_at'])) . '">' . date('H:i:s d.m.Y', strtotime($tweet['created_at'])) . '</p><div style="height:3px; background: url(/img/sep.png) repeat-x scroll 0 0 transparent;width:188px;margin-top:7px;margin-bottom:15px;"></div></li>';
                $count += 1;
                if($count > $num) {
                    break;
                }
            endforeach;
        endif;
        $ending = '</ul><h2 style="font:20px \'RodeoC\',serif; text-shadow: 1px 0 1px #FFFFFF;color:#999;text-transform: uppercase; text-align: center;margin-top:10px;">СЛЕДИ ЗА <a class="follow-link" style="font-size:20px;" target="_blank" href="http://www.twitter.com/#!/Go_Deer">@GO_DEER</a><br> В ТВИТТЕРЕ</h2>';

        return $header . $content . $ending;
    }

}
