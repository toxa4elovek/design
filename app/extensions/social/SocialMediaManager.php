<?php

namespace app\extensions\social;

use \lithium\data\entity\Record;
use app\extensions\helper\PitchTitleFormatter;
use app\extensions\helper\nameInflector;
use app\extensions\helper\MoneyFormatter;

class SocialMediaManager {

    /**
     * Метод возвращяет отформатированное название проекта
     *
     * @param Record $projectObject проект
     * @param string $social название соц сети
     * @return mixed|string
     */
    public function getProjectTitleForSocialNetwork(Record $projectObject, $social = 'twitter') {
        $nameInflector = new PitchTitleFormatter();
        if($social == 'twitter') {
            $charLimit = 30;
        }else {
            $charLimit = 0;
        }
        return $nameInflector->renderTitle($projectObject->title, $charLimit);
    }

    /**
     * Метод возвращяет строчку для аналитики для сообщения о лучшем решении
     *
     * @param string $social
     * @return string
     */
    public function getBestSolutionAnalyticsStringForSocialNetwork($social = 'twitter') {
        switch ($social):
            case 'twitter': return $this->__buildAnalyticsParams('sharing', 'twitter', 'tweet', 'best-solution-tweet');
            case 'facebook': return $this->__buildAnalyticsParams('sharing', 'facebook', 'post', 'best-solution-post');
            case 'vk': return $this->__buildAnalyticsParams('sharing', 'vk', 'post', 'best-solution-post');
        endswitch;
    }

    /**
     * Метод возвращяет строчку для аналитики для сообщения о решении победителе
     *
     * @param string $social
     * @return string
     */
    public function getWinnerSolutionAnalyticsStringForSocialNetwork($social = 'twitter') {
        switch ($social):
            case 'twitter': return $this->__buildAnalyticsParams('sharing', 'twitter', 'tweet', 'winner-solution-tweet');
            case 'facebook': return $this->__buildAnalyticsParams('sharing', 'facebook', 'post', 'winner-solution-post');
            case 'vk': return $this->__buildAnalyticsParams('sharing', 'vk', 'post', 'winner-solution-post');
        endswitch;
    }

    /**
     * Метод возвращяет строчку для аналитики для сообщение о новом проекте
     *
     * @param string $social
     * @return string
     */
    public function getNewProjectAnalyticsStringForSocialNetwork($social = 'twitter') {
        switch ($social):
            case 'twitter': return $this->__buildAnalyticsParams('sharing', 'twitter', 'tweet', 'new-project-tweet');
            case 'facebook': return $this->__buildAnalyticsParams('sharing', 'facebook', 'post', 'new-project-post');
            case 'vk': return $this->__buildAnalyticsParams('sharing', 'vk', 'post', 'new-project-post');
        endswitch;
    }

    /**
     * Метод помощник для составления строчки параметров для аналитики
     *
     * @param $campaign
     * @param $source
     * @param $medium
     * @param $content
     * @return string
     */
    private function __buildAnalyticsParams($campaign, $source, $medium, $content) {
        return '?utm_source=' . $source . '&utm_medium=' . $medium . '&utm_content=' . $content . '&utm_campaign=' . $campaign;
    }

    /**
     * Метод возвращяет сообщение о самом популярном решении для соц сети
     *
     * @param Record $solutionObject
     * @param $time
     * @param string $social
     * @return string
     */
    public function getBestSolutionMessageForSocialNetwork(Record $solutionObject, $time, $social = 'twitter') {
        return 'Самое популярное решение за ' . date('d.m.Y', $time) . ' «' . $this->getProjectTitleForSocialNetwork($solutionObject->pitch, $social) . '» ' . 'http://www.godesigner.ru/pitches/viewsolution/' . $solutionObject->id . $this->getBestSolutionAnalyticsStringForSocialNetwork($social) . ' #Go_Deer';
    }

    /**
     * Метод публикает в соц сеть сообщение о победе в питче
     *
     * @param Record $solutionObject
     * @param $index
     * @param string $social
     * @return string
     */
    public function getWinnerSolutionMessageForSocialNetwork(Record $solutionObject, $index, $social = 'twitter') {
        $templates = array(
            '%s заработал %s за проект «%s» %s #Go_Deer',
            '%s победил в проекте «%s», награда %s %s #Go_Deer'
        );
        $nameInflector = new nameInflector();
        $moneyFormatter = new MoneyFormatter();
        $winnerPrice = $moneyFormatter->formatMoney($solutionObject->pitch->price, array('suffix' => ' РУБ.-'));
        $winnerName = $nameInflector->renderName($solutionObject->winner->first_name, $solutionObject->winner->last_name);
        switch($index):
            case 0: return sprintf($templates[$index], $winnerName, $winnerPrice, $this->getProjectTitleForSocialNetwork($solutionObject->pitch, $social), 'http://www.godesigner.ru/pitches/viewsolution/' . $solutionObject->id . $this->getWinnerSolutionAnalyticsStringForSocialNetwork($social));
            case 1: return sprintf($templates[$index], $winnerName, $this->getProjectTitleForSocialNetwork($solutionObject->pitch, $social), $winnerPrice, 'http://www.godesigner.ru/pitches/viewsolution/' . $solutionObject->id . $this->getWinnerSolutionAnalyticsStringForSocialNetwork($social));
        endswitch;
    }

    /**
     * Метод постит в соц сеть сообщение о новом проекте
     *
     * @param Record $projectObject
     * @param $index
     * @param string $social
     * @return string
     */
    public function getNewProjectMessageForSocialNetwork(Record $projectObject, $index, $social = 'twitter') {
        $templates = array(
            'Нужен «%s», вознаграждение %s %s #Go_Deer #работадлядизайнеров',
            'За %s нужен «%s», %s #Go_Deer #работадлядизайнеров'
        );
        $moneyFormatter = new MoneyFormatter();
        $winnerPrice = $moneyFormatter->formatMoney($projectObject->price, array('suffix' => ' р.-'));
        switch($index):
            case 0: return sprintf($templates[$index], $this->getProjectTitleForSocialNetwork($projectObject, $social), $winnerPrice, 'http://www.godesigner.ru/pitches/details/' . $projectObject->id . $this->getNewProjectAnalyticsStringForSocialNetwork($social));
            case 1: return sprintf($templates[$index], $winnerPrice, $this->getProjectTitleForSocialNetwork($projectObject, $social), 'http://www.godesigner.ru/pitches/details/' . $projectObject->id . $this->getNewProjectAnalyticsStringForSocialNetwork($social));
        endswitch;
    }

    /**
     * Метод подготоваливает адрес картинки для АПИ соц сети
     *
     * @param Record $solutionObject
     * @param string $social
     * @return string
     */
    public function getImageReadyForSocialNetwork(Record $solutionObject, $social = 'twitter') {
        if (($solutionObject->pitch->private == 0 && $solutionObject->pitch->category_id != 7) &&
            (isset($solutionObject->images['solution_solutionView']))) {
            if (isset($solutionObject->images['solution_solutionView'][0]['filename'])) {
                return $this->__returnImageReady($solutionObject->images['solution_solutionView'][0], $social);
            } else {
                return $this->__returnImageReady($solutionObject->images['solution_solutionView'], $social);
            }
        }
        return '';
    }

    /**
     * Вспомогательный метод, возвращает нужный адрес картинки для соц сети
     *
     * @param $solutionView
     * @param $social
     * @return string
     */
    private function __returnImageReady($solutionView, $social) {
        if($social == 'twitter') {
            return $solutionView['filename'];
        }else {
            return 'http://www.godesigner.ru' . $solutionView['weburl'];
        }
    }

}