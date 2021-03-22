<?php

namespace Jose;


use Jose\Exception\NotFoundException;

class Container
{
    private  $instances = [];
    private static $instance;

    /**
     * Store all entries in the container
     */
    protected $defaultProviders = [

    ];


    public function __construct() {
        require_once(ROOT_JOSE. 'helpers.php');
        $this->defaultProviders = require_once(ROOT_JOSE. 'providers.php');

        // TODO add custom registrer providers from user config

        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }

    /**
     * Main container
     * @return Container
     */
    public static function getInstance(): Container
    {
        if(!self::$instance) {
            self::$instance = new Container();
        }
        return self::$instance;
    }

    /**
     * @param $key
     * @param $params
     * @return mixed|object
     * @throws \ReflectionException
     */
    public function getClassInstance($key, $params): object
    {
        if($params) {
            $reflect  = new \ReflectionClass($this->defaultProviders[$key]);
            $class = $reflect->newInstanceArgs($params);
        } else {
            $class  = new $this->defaultProviders[$key]();
        }
        return $class;
    }

    /**
     * @param $key
     * @param null $params
     * @param bool $newInstance
     * @return mixed
     * @throws NotFoundException|\ReflectionException
     */
    public function get($key, $params = null, $newInstance = false): object
    {

        if( ! array_key_exists($key, $this->instances)) {
            if (array_key_exists($key, $this->defaultProviders)) {

                // if we want a sinle instance
                if ($newInstance) {
                    return $this->getClassInstance($key, $params);

                } else if(
                    is_string( $this->defaultProviders[$key])
                    && class_exists($this->defaultProviders[$key] )
                ) {

                    // register the instance in the container
                    $this->instances[$key] = $this->getClassInstance($key, $params);
                }

            } else {
               throw new NotFoundException('Class ' . $key . ' not found in container. Did you registered your class in the providers ?');
            }

        }
        return $this->instances[$key];

    }

    /**
     * Add a custom providers
     * @param $key
     * @param $class
     * @return $this
     */
    public function set($key, $class) :Container
    {
        $this->defaultProviders[$key] = $class;
        return $this;
    }


}