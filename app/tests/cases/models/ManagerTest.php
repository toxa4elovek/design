<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Manager;

class ManagerTest extends AppUnit
{
    public $models = ['User', 'Manager', 'Pitch'] ;

    public function setUp()
    {
        Manager::config(['connection' => 'test']);
        $this->rollUp($this->models);
    }

    public function tearDown()
    {
        $this->rollDown($this->models);
    }

    /**
     * Проверки метода добавления члена в команду
     */
    public function testAddManagerForSubscriber()
    {
        // Просто добавление
        $managerId = 1;
        $subscriberId = 2;
        $result = Manager::addManagerForSubscriber($managerId, $subscriberId);
        $this->assertTrue($result);
        $count = Manager::count(['conditions' => ['manager_id' => $managerId, 'subscriber_id' => $subscriberId]]);
        $this->assertEqual(1, $count);

        // Нельзя добавить повторно уже существующего
        $result = Manager::addManagerForSubscriber($managerId, $subscriberId);
        $this->assertFalse($result);
        $count = Manager::count(['conditions' => ['manager_id' => $managerId, 'subscriber_id' => $subscriberId]]);
        $this->assertEqual(1, $count);

        // Несуществующий менеджер
        $managerId = 10;
        $subscriberId = 2;
        $result = Manager::addManagerForSubscriber($managerId, $subscriberId);
        $this->assertFalse($result);
        $count = Manager::count(['conditions' => ['manager_id' => $managerId, 'subscriber_id' => $subscriberId]]);
        $this->assertEqual(0, $count);

        // Несуществуюзий абонент
        $managerId = 1;
        $subscriberId = 20;
        $result = Manager::addManagerForSubscriber($managerId, $subscriberId);
        $this->assertFalse($result);
        $count = Manager::count(['conditions' => ['manager_id' => $managerId, 'subscriber_id' => $subscriberId]]);
        $this->assertEqual(0, $count);
    }

    /**
     * Методы проверки удаления члена из команды
     */
    public function testRemoveManagerForSubscriber()
    {
        $managerId = 1;
        $subscriberId = 2;
        Manager::addManagerForSubscriber($managerId, $subscriberId);

        $result = Manager::removeManagerForSubscriber($managerId, $subscriberId);
        $this->assertTrue($result);
        $count = Manager::count(['conditions' => ['manager_id' => $managerId, 'subscriber_id' => $subscriberId]]);
        $this->assertEqual(0, $count);

        Manager::addManagerForSubscriber($managerId, $subscriberId);
        // Несуществующий менеджер
        $managerId = 10;
        $subscriberId = 2;
        $result = Manager::removeManagerForSubscriber($managerId, $subscriberId);
        $this->assertFalse($result);
        $count = Manager::count(['conditions' => ['manager_id' => 1, 'subscriber_id' => 2]]);
        $this->assertEqual(1, $count);

        // Несуществуюзий абонент
        $managerId = 1;
        $subscriberId = 20;
        $result = Manager::removeManagerForSubscriber($managerId, $subscriberId);
        $this->assertFalse($result);
        $count = Manager::count(['conditions' => ['manager_id' => 1, 'subscriber_id' => 2]]);
        $this->assertEqual(1, $count);
    }

    /**
     * Тестируем метод определения членства в какой-либо команде
     */
    public function testIsUserManager()
    {
        Manager::addManagerForSubscriber(1, 2);
        $result = Manager::isUserManager(1);
        $this->assertTrue($result);

        Manager::removeManagerForSubscriber(1, 2);
        $result = Manager::isUserManager(1);
        $this->assertFalse($result);

        $result = Manager::isUserManager(2);
        $this->assertFalse($result);
    }

    /**
     * Метод возвращяет номер абонента для определенного менеджера, если есть
     */
    public function testGetTeamLeaderIdOfManager()
    {
        $result = Manager::getTeamLeaderOfManager(1);
        $this->assertIdentical(0, $result);

        Manager::addManagerForSubscriber(1, 2);
        $result = Manager::getTeamLeaderOfManager(1);
        $this->assertIdentical(2, $result);

        Manager::removeManagerForSubscriber(1, 2);
        $result = Manager::getTeamLeaderOfManager(1);
        $this->assertIdentical(0, $result);
    }

    /**
     * Тесты на проверку присвоения менеджеру проекта
     * @TODO добавить проверку на владение абонентами проектами
     */
    public function testAssignProjectToManager()
    {
        $managerId = 1;
        $subscriberId = 2;
        Manager::addManagerForSubscriber($managerId, $subscriberId);
        $result = Manager::assignManagerToProject(1, 1);
        $this->assertTrue($result);
        $count = Manager::count(['conditions' => ['manager_id' => 1, 'project_id' => 1]]);
        $this->assertEqual(1, $count);

        // Номер проекта не существующий
        $result = Manager::assignManagerToProject(1, 100000);
        $this->assertFalse($result);
        $count = Manager::count(['conditions' => ['manager_id' => 1, 'project_id' => 100000]]);
        $this->assertEqual(0, $count);

        // Менеджер проекта не существующий
        $result = Manager::assignManagerToProject(10, 100000);
        $this->assertFalse($result);
        $count = Manager::count(['conditions' => ['manager_id' => 10000, 'project_id' => 1]]);
        $this->assertEqual(0, $count);
    }

    public function testRemoveManagerFromProject()
    {
        $managerId = 1;
        $subscriberId = 2;
        $projectId = 1;
        Manager::addManagerForSubscriber($managerId, $subscriberId);

        $result = Manager::removeManagerFromProject($managerId, $projectId);
        $this->assertFalse($result);
        $count = Manager::count(['conditions' => ['manager_id' => $managerId, 'project_id' => $projectId]]);
        $this->assertEqual(0, $count);

        $result = Manager::assignManagerToProject($managerId, $projectId);
        $this->assertTrue($result);
        $count = Manager::count(['conditions' => ['manager_id' => $managerId, 'project_id' => $projectId]]);
        $this->assertEqual(1, $count);

        $result = Manager::removeManagerFromProject($managerId, $projectId);
        $this->assertTrue($result);
        $count = Manager::count(['conditions' => ['manager_id' => $managerId, 'project_id' => $projectId]]);
        $this->assertEqual(0, $count);
    }
}
