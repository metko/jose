<?php
declare(strict_types=1);

namespace Tests;

class ContainerTest extends BaseTestCase
{

    public function testType()
    {
        $this->assertInstanceOf('\Jose\Container', $this->container);
    }

    public function testCanReturnClass()
    {
        $this->assertInstanceOf('\Jose\Config', $this->container->get('config'));
    }

    public function testThrowExceptionIfClassDoesntExists()
    {
        $this->expectException('\Jose\Exception\NotFoundException');
        $this->assertInstanceOf('\Jose\Config', $this->container->get('doesntexistscontainer'));
    }

    public function testItCanSetNewProviders()
    {
        $this->container->set('task', '\Tests\Ressources\Task');
        $this->assertInstanceOf('\Tests\Ressources\Task', $this->container->get('task'));
    }

    public function testItCanSetMultipleProviderAsArray()
    {
        $this->container->set(['task' => '\Tests\Ressources\Task', 'task2' => '\Tests\Ressources\Task2']);
        $this->assertInstanceOf('\Tests\Ressources\Task', $this->container->get('task'));
        $this->assertInstanceOf('\Tests\Ressources\Task2', $this->container->get('task2'));
    }

    public function testItCanSetWithAFile()
    {
        $this->container->set(dirname(__FILE__).'/Ressources/providers.php');
        $this->assertInstanceOf('\Tests\Ressources\Task', $this->container->get('task'));
        $this->assertInstanceOf('\Tests\Ressources\Task2', $this->container->get('task2'));
    }

    public function testItThrowErrorIfFileDoesntExist()
    {        
        $this->expectException('\Jose\Exception\NotFoundException');
        $this->container->set(dirname(__FILE__).'/Ressources/providers99.php');
        $this->assertInstanceOf('\Tests\Ressources\Task', $this->container->get('task'));
    }

    public function testItThrowErrorIfFileProvidersAreNotArray()
    {        
        $this->expectException('\Jose\Exception\ExpectedTypeException');
        $this->container->set(dirname(__FILE__).'/Ressources/providers_fake.php');
        $this->assertInstanceOf('\Tests\Ressources\Task', $this->container->get('task'));
    }

    public function testItCanInstanciateWithParams()
    {
        $this->container->set('taskParams', '\Tests\Ressources\TaskParams');
        $task = $this->container->get('taskParams', ['Task 1', 'publised']);
        $this->assertEquals('Task 1', $task->name);
        $this->assertEquals('publised', $task->status);
    }
}