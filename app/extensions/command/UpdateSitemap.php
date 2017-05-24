<?php

namespace app\extensions\command;

use app\models\Answer;
use app\models\Comment;
use app\models\Expert;
use app\models\Pitch;
use app\models\Post;
use app\models\Solution;
use Thepixeldeveloper\Sitemap\Output;
use Thepixeldeveloper\Sitemap\Url;
use Thepixeldeveloper\Sitemap\Urlset;

/**
 * Class UpdateSmsStatus
 *
 * Команда для обновления карты сайта
 * @package app\extensions\command
 */
class UpdateSitemap extends CronJob
{

    /**
     * Обновляем карту сайта
     */
    public function run()
    {
        $this->_renderHeader();
        $urlSet = new Urlset();
        $urls = [
            '/',
            '/answers/',
            '/experts/',
            '/fastpitch/',
            '/golden-fish/',
            '/golden-fish/how-it-works/',
            '/pages/about/',
            '/pages/brief/',
            '/pages/contacts/',
            '/pages/howitworks/',
            '/pages/referal/',
            '/pages/special/',
            '/pages/subscribe/',
            '/pages/terms-and-privacy/',
            '/pages/to_designers/',
            '/pitches/',
            '/posts/',
            '/questions/',
        ];
        $layoutTime = $this->_getLastUpdateTimeOfLayout();
        foreach ($urls as $url) {
            $urlObject = (new Url('https://godesigner.ru' . $url))->setLastMod(date('Y-m-d', $layoutTime));
            $urlSet->addUrl($urlObject);
        }
        $items = Answer::all(['order' => ['Answer.id' => 'asc']]);
        foreach ($items as $item) {
            $urlObject = (new Url('https://godesigner.ru/answers/view/' . $item->id))->setLastMod(date('Y-m-d', $layoutTime));
            $urlSet->addUrl($urlObject);
        }
        $items = Expert::all(['conditions' => ['Expert.enabled' => 1], 'order' => ['Expert.id' => 'asc']]);
        foreach ($items as $item) {
            $urlObject = (new Url('https://godesigner.ru/experts/view/' . $item->id))->setLastMod(date('Y-m-d', $layoutTime));
            $urlSet->addUrl($urlObject);
        }
        $items = Post::all(['conditions' => ['Post.published' => 1], 'order' => ['Post.id' => 'asc']]);
        foreach ($items as $item) {
            $lastEditTime = $this->_getPostLastEditTime($item);
            $maxTime = $this->_getMaxTimeOutOfList([$lastEditTime, $layoutTime]);
            $urlObject = (new Url('https://godesigner.ru/posts/view/' . $item->id))->setLastMod(date('Y-m-d', $maxTime));
            $urlSet->addUrl($urlObject);
        }
        $items = Pitch::all(['conditions' => [
            'Pitch.published' => 1,
            'Pitch.type' => ['', 'company_project'],
            'Pitch.private' => 0
        ], 'order' => ['Pitch.started' => 'asc']]);
        foreach ($items as $item) {
            $startedTime = strtotime($item->started);
            $projectLastCommentTime = $this->_getProjectLastCommentTime($item);
            $projectLastSolutionTime = $this->_getProjectLastSolutionTime($item);
            $maxTime = $this->_getMaxTimeOutOfList([$startedTime, $projectLastCommentTime, $projectLastSolutionTime, $layoutTime]);
            $urlObject = (new Url('https://godesigner.ru/pitches/view/' . $item->id))->setLastMod(date('Y-m-d', $maxTime));
            $urlSet->addUrl($urlObject);
            $urlObject = (new Url('https://godesigner.ru/pitches/details/' . $item->id))->setLastMod(date('Y-m-d', $maxTime));
            $urlSet->addUrl($urlObject);
            $urlObject = (new Url('https://godesigner.ru/pitches/designers/' . $item->id))->setLastMod(date('Y-m-d', $maxTime));
            $urlSet->addUrl($urlObject);
        }
        $output = (new Output())->getOutput($urlSet);
        $sitemapFilePath = LITHIUM_APP_PATH . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'sitemap.xml';
        file_put_contents($sitemapFilePath, $output);
        $this->_renderFooter("Sitemap.xml updated");
    }

    private function _getMaxTimeOutOfList($listIfTimestamps)
    {
        return max($listIfTimestamps);
    }

    private function _getLastUpdateTimeOfLayout()
    {
        $layoutFile = LITHIUM_APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'default.html.php';
        return filemtime($layoutFile);
    }

    private function _getPostLastEditTime($record)
    {
        return strtotime($record->lastEditTime);
    }

    private function _getProjectLastCommentTime($record)
    {
        $item = Comment::first([
            'conditions' => ['Comment.pitch_id' => $record->id],
            'order' => ['Comment.created' => 'desc']
        ]);
        return strtotime($item->created);
    }

    private function _getProjectLastSolutionTime($record)
    {
        $item = Solution::first([
            'conditions' => ['Solution.pitch_id' => $record->id],
            'order' => ['Solution.created' => 'desc']
        ]);
        return strtotime($item->created);
    }
}
