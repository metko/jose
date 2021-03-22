<?php
namespace Tests;

use Jose\Container;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{

    protected $container;

    public function setUp()
    {
//        require(dirname(__DIR__).'/src/constants.php');
//        require(dirname(__DIR__).'/src/helpers.php');
        $this->container = Container::getInstance();
        parent::setUp();
    }


}