<?php
declare(strict_types=1);

namespace Tests;

class ConfigTest extends BaseTestCase
{

    public function testType()
    {
        $this->assertInstanceOf('\Jose\Config', $this->container->get('config'));
    }

    public function testCanSetConfigAsArray() {
        $config = $this->container->get('config');
        $config->set(['foo' => 'bar']);
        $this->assertEquals('bar', $config->get('foo'));
    }

    public function testCanSetConfigAsFile() {
        $config = $this->container->get('config');
        $config->set(dirname(__FILE__).'/Ressources/config.php');
        $this->assertEquals('bar', $config->get('foo'));
    }

    public function testCanGetNestedConfig() {
        $config = $this->container->get('config');
        $config->set(['foo' => ['foo' => ['foo' => 'bar']]]);
        $this->assertEquals('bar', $config->get('foo.foo.foo'));
    }

    public function testCantIncludeSameConfigFile() {
        $this->expectException('\Jose\Exception\FileAlreadyLoadedException');
        $config = $this->container->get('config');
        $config->set(dirname(__FILE__).'/Ressources/config.php');
        $config->set(dirname(__FILE__).'/Ressources/config.php');
    }

    public function testThrowExceptionIfConfigFileDoesntExisxt() {
        $this->expectException('\Jose\Exception\NotFoundException');
        $config = $this->container->get('config');
        $config->set(dirname(__FILE__).'/config99.php');
    }

}