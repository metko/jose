<?php
namespace Jose;

class App {

    /**
     * @var App
     */
    private static $instance;
    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config) {
        $this->config = $config;
    }


    public static function getInstance(): App
    {
        if(!self::$instance) {
            self::$instance = new App();
        }
        return self::$instance;
    }


    public static function create(): App
    {
        return self::getInstance();
    }

}