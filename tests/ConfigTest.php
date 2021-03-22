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
        $config->set(dirname(__FILE__).'/Ressources/config2.php');
        $this->assertEquals('awesome', $config->get('nested.foo.bar'));
    }

    public function testCanIncludeSameConfigFileOnlyOnce() {
        $this->expectException('\Jose\Exception\FileAlreadyLoadedException');
        $config = $this->container->get('config');
        $config->set(dirname(__FILE__).'/Ressources/config.php');
    }

//    public function testTrhrowExceptionIfConfigFileDoesntExisxt() {
//        $this->expectException('\Jose\Exception\FileAlreadyLoadedException');
//        $config = $this->container->get('config');
//        $config->set(dirname(__FILE__).'/config.php');
//    }



}