<?php

namespace app\tests\cases\extensions\social;

use app\extensions\social\SocialMediaManager;
use app\extensions\tests\AppUnit;
use app\models\Pitch;
use app\models\Solution;
use app\models\User;

class SocialMediaManagerTest extends AppUnit {

    public $manager = null;

    public function setUp() {
        $this->rollUp(array('Pitch', 'Solution', 'User'));
        $this->manager = new SocialMediaManager();
    }

    public function tearDown() {
        $this->rollDown(array('Pitch', 'Solution', 'User'));
    }

    public function testGetProjectTitleForSocialNetwork() {
        $project = Pitch::first(1);

        $project->title = 'Короткое название "проекта"';
        $result = $this->manager->getProjectTitleForSocialNetwork($project, 'twitter');
        $this->assertEqual('Короткое название «Проекта»', $result);

        $project->title = 'Очень больше и длинное название "проекта"';
        $result = $this->manager->getProjectTitleForSocialNetwork($project, 'twitter');
        $this->assertEqual('Очень больше и длинное назван…', $result);

        $project->title = 'Очень больше и длинное название "проекта"';
        $result = $this->manager->getProjectTitleForSocialNetwork($project, 'vk');
        $this->assertEqual('Очень больше и длинное название «Проекта»', $result);

        $project->title = 'Очень больше и длинное название "проекта"';
        $result = $this->manager->getProjectTitleForSocialNetwork($project, 'facebook');
        $this->assertEqual('Очень больше и длинное название «Проекта»', $result);
    }

    public function testGetBestSolutionAnalyticsStringForSocialNetwork() {
        $string = '?utm_source=twitter&utm_medium=tweet&utm_content=best-solution-tweet&utm_campaign=sharing';
        $this->assertEqual($string, $this->manager->getBestSolutionAnalyticsStringForSocialNetwork('twitter'));

        $string = '?utm_source=facebook&utm_medium=post&utm_content=best-solution-post&utm_campaign=sharing';
        $this->assertEqual($string, $this->manager->getBestSolutionAnalyticsStringForSocialNetwork('facebook'));

        $string = '?utm_source=vk&utm_medium=post&utm_content=best-solution-post&utm_campaign=sharing';
        $this->assertEqual($string, $this->manager->getBestSolutionAnalyticsStringForSocialNetwork('vk'));
    }

    public function testGetBestSolutionMessageForSocialNetwork() {
        $solution = Solution::first(array('conditions' => array('Solution.id' => 2), 'with' => array('Pitch')));
        $solution->pitch->title = 'Очень больше и длинное название "проекта"';

        $string = 'Самое популярное решение за ' . date('d.m.Y', time()) . ' «Очень больше и длинное назван…» ' . 'http://www.godesigner.ru/pitches/viewsolution/' . $solution->id . $this->manager->getBestSolutionAnalyticsStringForSocialNetwork('twitter') . ' #Go_Deer';
        $this->assertEqual($string, $this->manager->getBestSolutionMessageForSocialNetwork($solution, time(), 'twitter'));

        $string = 'Самое популярное решение за ' . date('d.m.Y', time()) . ' «Очень больше и длинное название «Проекта»» ' . 'http://www.godesigner.ru/pitches/viewsolution/' . $solution->id . $this->manager->getBestSolutionAnalyticsStringForSocialNetwork('vk') . ' #Go_Deer';
        $this->assertEqual($string, $this->manager->getBestSolutionMessageForSocialNetwork($solution, time(), 'vk'));

        $string = 'Самое популярное решение за ' . date('d.m.Y', time()) . ' «Очень больше и длинное название «Проекта»» ' . 'http://www.godesigner.ru/pitches/viewsolution/' . $solution->id . $this->manager->getBestSolutionAnalyticsStringForSocialNetwork('facebook') . ' #Go_Deer';
        $this->assertEqual($string, $this->manager->getBestSolutionMessageForSocialNetwork($solution, time(), 'facebook'));
    }

    public function testGetImageReadyForSocialNetwork() {
        $solution = Solution::first(array('conditions' => array('Solution.id' => 2), 'with' => array('Pitch')));
        $this->assertEqual($solution->images['solution_solutionView']['filename'], $this->manager->getImageReadyForSocialNetwork($solution, 'twitter'));
        $this->assertEqual('http://www.godesigner.ru/solutions/2_solutionView.jpg', $this->manager->getImageReadyForSocialNetwork($solution, 'vk'));
        $this->assertEqual('http://www.godesigner.ru/solutions/2_solutionView.jpg', $this->manager->getImageReadyForSocialNetwork($solution, 'facebook'));
        $solution->pitch->private = 1;
        $this->assertIdentical('', $this->manager->getImageReadyForSocialNetwork($solution, 'facebook'));
        /*$solution = Solution::first(array('conditions' => array('Solution.id' => 2)));
        $solution->pitch = null;
        $this->assertEqual($solution->images['solution_solutionView']['filename'], $this->manager->getImageReadyForSocialNetwork($solution, 'twitter'));*/
        $solution->images = null;
        $this->assertIdentical('', $this->manager->getImageReadyForSocialNetwork($solution, 'twitter'));
    }

    public function testGetWinnerSolutionAnalyticsStringForSocialNetwork() {
        $string = '?utm_source=twitter&utm_medium=tweet&utm_content=winner-solution-tweet&utm_campaign=sharing';
        $this->assertEqual($string, $this->manager->getWinnerSolutionAnalyticsStringForSocialNetwork('twitter'));

        $string = '?utm_source=facebook&utm_medium=post&utm_content=winner-solution-post&utm_campaign=sharing';
        $this->assertEqual($string, $this->manager->getWinnerSolutionAnalyticsStringForSocialNetwork('facebook'));

        $string = '?utm_source=vk&utm_medium=post&utm_content=winner-solution-post&utm_campaign=sharing';
        $this->assertEqual($string, $this->manager->getWinnerSolutionAnalyticsStringForSocialNetwork('vk'));
    }

    public function testGetWinnerSolutionMessageForSocialNetwork() {
        $solution = Solution::first(array('conditions' => array('Solution.id' => 2)));
        $solution->winner = User::first($solution->user_id);
        $solution->pitch = Pitch::first($solution->pitch_id);
        $solution->pitch->title = 'Очень больше и длинное название "проекта"';
        $string = 'Дмитрий Н. заработал 300 РУБ.- за проект «Очень больше и длинное назван…» http://www.godesigner.ru/pitches/viewsolution/2?utm_source=twitter&utm_medium=tweet&utm_content=winner-solution-tweet&utm_campaign=sharing #Go_Deer';
        $this->assertEqual($string, $this->manager->getWinnerSolutionMessageForSocialNetwork($solution, 0, 'twitter'));
        $string = 'Дмитрий Н. заработал 300 РУБ.- за проект «Очень больше и длинное название «Проекта»» http://www.godesigner.ru/pitches/viewsolution/2?utm_source=facebook&utm_medium=post&utm_content=winner-solution-post&utm_campaign=sharing #Go_Deer';
        $this->assertEqual($string, $this->manager->getWinnerSolutionMessageForSocialNetwork($solution, 0, 'facebook'));
        $string = 'Дмитрий Н. заработал 300 РУБ.- за проект «Очень больше и длинное название «Проекта»» http://www.godesigner.ru/pitches/viewsolution/2?utm_source=vk&utm_medium=post&utm_content=winner-solution-post&utm_campaign=sharing #Go_Deer';
        $this->assertEqual($string, $this->manager->getWinnerSolutionMessageForSocialNetwork($solution, 0, 'vk'));

        $string = 'Дмитрий Н. победил в проекте «Очень больше и длинное назван…», награда 300 РУБ.- http://www.godesigner.ru/pitches/viewsolution/2?utm_source=twitter&utm_medium=tweet&utm_content=winner-solution-tweet&utm_campaign=sharing #Go_Deer';
        $this->assertEqual($string, $this->manager->getWinnerSolutionMessageForSocialNetwork($solution, 1, 'twitter'));
        $string = 'Дмитрий Н. победил в проекте «Очень больше и длинное название «Проекта»», награда 300 РУБ.- http://www.godesigner.ru/pitches/viewsolution/2?utm_source=facebook&utm_medium=post&utm_content=winner-solution-post&utm_campaign=sharing #Go_Deer';
        $this->assertEqual($string, $this->manager->getWinnerSolutionMessageForSocialNetwork($solution, 1, 'facebook'));
        $string = 'Дмитрий Н. победил в проекте «Очень больше и длинное название «Проекта»», награда 300 РУБ.- http://www.godesigner.ru/pitches/viewsolution/2?utm_source=vk&utm_medium=post&utm_content=winner-solution-post&utm_campaign=sharing #Go_Deer';
        $this->assertEqual($string, $this->manager->getWinnerSolutionMessageForSocialNetwork($solution, 1, 'vk'));
    }

}