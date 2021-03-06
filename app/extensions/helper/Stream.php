<?php

namespace app\extensions\helper;

use app\extensions\storage\Rcache;

class Stream extends \lithium\template\Helper
{

    /**
     * Метод возвращает html для формирования твиттер ленты. Твиты берутся из кеша.
     *
     * @param int $num - количество твитов для показа
     * @return string
     */
    public function renderStream($num = 10, $header = true)
    {
        if ($header) {
            $header = '<h2 style="font:20px \'RodeoC\',serif; text-shadow: 1px 0 1px #FFFFFF;color:#999;text-transform: uppercase; text-align: center;margin-bottom:10px">Твиттер лента</h2><ul id="sidebar-content">';
        } else {
            $header = '<ul id="sidebar-content">';
        }
        if ($data = Rcache::read('twitterstream')):
            $tweets = $data['statuses'];
        $content = '';
        $count = 1;
        foreach ($tweets as $tweet):
                $text = $tweet['text'];
        if (isset($tweet['type']) && $tweet['type'] === 'tutdesign') {
            $image = '<!--noindex--><a rel="nofollow" href="http://tutdesign.ru/cats/' . $tweet['category'] . '/' . $tweet['id'] . '-' . $tweet['slug'] . '.html" target="_blank"><img style="position: relative; margin: 0 0 10px 6px;" alt="' . $text . '" src="http://tutdesign.ru/wp-content/uploads/' . $tweet['thumbnail'] . '" width="171" height="114"></a><!--/noindex-->';
            $link = '<!--noindex--><a rel="nofollow" style="display:inline;color:#ff585d" target="_blank" href="http://tutdesign.ru/cats/' . $tweet['category'] . '/' . $tweet['id'] . '-' . $tweet['slug'] . '.html">http://tutdesign.ru/cats/' . $tweet['category'] . '</a><!--/noindex-->';
            $content .= '<li style="padding-left:5px;"><p class="regular" style="line-height:20px;">' . $image . '<br>' . $text . '<br>' . $link . '</p>';
        } else {
            foreach ($tweet['entities']['hashtags'] as $hashtag) {
                $text = str_replace('#' . $hashtag['text'], '<!--noindex--><a rel="nofollow" style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/search/%23' . $hashtag['text'] . '">' . '#' . $hashtag['text'] . '</a><!--/noindex-->', $text);
            }
            foreach ($tweet['entities']['urls'] as $url) {
                $text = str_replace($url['url'], '<!--noindex--><a rel="nofollow" style="display:inline;color:#ff585d" target="_blank" href="' . $url['url'] . '">' . $url['display_url'] . '</a><!--/noindex-->', $text);
            }
            foreach ($tweet['entities']['user_mentions'] as $user) {
                $text = str_replace('@' . $user['screen_name'], '<!--noindex--><a rel="nofollow" style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/' . $user['screen_name'] . '">' . '@' . $user['screen_name'] . '</a><!--/noindex-->', $text);
            }
            $user = '<!--noindex--><a rel="nofollow" style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/' . $tweet['user']['screen_name'] . '">@' . $tweet['user']['screen_name'] . '</a><!--/noindex-->';
            if (($tweet['user']['screen_name'] === 'tutdesign') && (preg_match('/news\?event/', $text))) {
                continue;
            }
            $image = '';
            if (isset($tweet['thumbnail'])) {
                if ((isset($tweet['entities']['urls'])) && (count($tweet['entities']['urls']) > 0)) {
                    $image = '<!--noindex--><a rel="nofollow" href="' . $tweet['entities']['urls'][0]['url'] . '"><img style="position: relative; margin: 0 0 10px 6px;" src="' . $tweet['thumbnail'] . '" width="171" alt=""></a><!--/noindex--><br>';
                } else {
                    $image = '<img style="position: relative; margin: 0 0 10px 6px;" src="' . $tweet['thumbnail'] . '" width="171" alt=""><br>';
                }
            }
            if ($count == 1):
                        $content .= '<li style="padding-left:5px;padding-right:5px;padding-top:10px;">'; else:
                        $content .= '<li style="padding-left:5px;">';
            endif;
            $content .= '<p class="regular" style="line-height:20px;">' . $image . $user . ' ' . $text . '</p>';
        }
        $content .= '<p class="time" title="' . date('Y-m-d H:i:s', strtotime($tweet['created_at'])) . '">' . date('H:i:s d.m.Y', strtotime($tweet['created_at'])) . '</p><div style="height:3px; background: url(/img/sep.png) repeat-x scroll 0 0 transparent;width:188px;margin-top:7px;margin-bottom:15px;"></div></li>';
        $count += 1;
        if ($count > $num) {
            break;
        }
        endforeach;
        endif;
        $ending = '</ul><h2 style="font:20px \'RodeoC\',serif; text-shadow: 1px 0 1px #FFFFFF;color:#999;text-transform: uppercase; text-align: center;margin-top:10px;">СЛЕДИ ЗА <!--noindex--><a rel="nofollow" class="follow-link" style="font-size:20px;" target="_blank" href="http://www.twitter.com/#!/Go_Deer">@GO_DEER</a><!--/noindex--><br> В ТВИТТЕРЕ</h2>';

        return $header . $content . $ending;
    }

    public function renderStreamFeed($num = 10, $created = null)
    {
        $content = '';
        if ($data = Rcache::read('twitterstreamFeed')) {
            $tweets = $data['statuses'];
            $count = 1;
            foreach ($tweets as $tweet) {
                if (strtotime($tweet['created_at']) > strtotime($created)) {
                    $text = $tweet['text'];
                    if (!isset($tweet['type']) && $tweet['type'] !== 'tutdesign') {
                        foreach ($tweet['entities']['hashtags'] as $hashtag) {
                            $text = str_replace('#' . $hashtag['text'], '<!--noindex--><a rel="nofollow" style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/search/%23' . $hashtag['text'] . '">' . '#' . $hashtag['text'] . '</a><!--/noindex-->', $text);
                        }
                        foreach ($tweet['entities']['urls'] as $url) {
                            $text = str_replace($url['url'], '<!--noindex--><a rel="nofollow" class="url-twitter" style="display:inline;color:#ff585d" target="_blank" href="' . $url['url'] . '">' . $url['display_url'] . '</a><!--/noindex-->', $text);
                        }
                        foreach ($tweet['entities']['user_mentions'] as $user) {
                            $text = str_replace('@' . $user['screen_name'], '<!--noindex--><a rel="nofollow" style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/' . $user['screen_name'] . '">' . '@' . $user['screen_name'] . '</a><!--/noindex-->', $text);
                        }
                        $user = '<!--noindex--><a rel="nofollow" style="display:inline;color:#ff585d" target="_blank" href="https://twitter.com/#!/' . $tweet['user']['screen_name'] . '">@' . $tweet['user']['screen_name'] . '</a><!--/noindex-->';
                        if ($count == 1) {
                            $content .= '<div id="twitterDate" data-date="' . date('Y-m-d H:i:s', strtotime($tweet['created_at'])) . '" class="job">';
                        } else {
                            $content .= '<div class="job">';
                        }
                        $content .= $user . ' ' . $text . '</div><div class="sp"></div>';
                        $content = preg_replace("/<img[^>]+\>/i", '', $content);
                    }
                    $count++;
                    if ($count > $num) {
                        break;
                    }
                }
            }
        }

        return $content;
    }
}
