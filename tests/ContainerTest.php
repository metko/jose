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

    public function testItCanInstanciateWithParams()
    {
        $this->container->set('taskParams', '\Tests\Ressources\TaskParams');
        $task = $this->container->get('taskParams', ['Task 1', 'publised']);
        $this->assertEquals('Task 1', $task->name);
        $this->assertEquals('publised', $task->status);
    }
}