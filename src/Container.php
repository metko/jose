<?php

namespace Jose;


use Jose\Exception\ExpectedTypeException;
use Jose\Exception\NotFoundException;

class Container
{
    private  $instances = [];
    private static $instance;

    /**
     * Store all entries in the container
     */
    public $facades = null;
    public $post_class_map = null;

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

    public function __construct()
    {
        // Setup error catcher
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();

        $providers = require_once(ROOT_JOSE. '/config/providers.php');
        // TODO: if file exists
        $this->user_providers = require_once(ROOT_APP . 'config/providers.php');
        $this->facades = $providers['facades'];
        $this->post_class_map = $providers['post_class_map'];
        // TODO add custom registrer providers from user config

    }

    public function loadUserFacades ()
    {
        if (jose('file')->exists(ROOT_APP . 'config/providers.php')) {
            if (array_key_exists('facades', $this->user_providers)) {
                $this->setFacades($this->user_providers['facades']);
            }
            // dd($container->defaultProviders);
        } else {
            throw new NotFoundException(ROOT_APP . 'config/providers.php not found'); 
        }
    }
    public function loadUserClassMap ()
    {
        if (jose('file')->exists(ROOT_APP . 'config/providers.php')) {
            if (array_key_exists('post_class_map', $this->user_providers)) {
                $this->setClassMap($this->user_providers['post_class_map']);
            }
            // dd($container->defaultProviders);
        } else {
            throw new NotFoundException(ROOT_APP . 'config/providers.php not found'); 
        }
    }

    public function setFacades(Array $facades)
    {
        $this->facades = array_merge($this->facades, $facades);
    }

    public function setClassMap(Array $post_class_map)
    {
        $this->post_class_map = array_merge($this->post_class_map, $post_class_map);
    }

    /**
     * @param $key
     * @param $params
     * @return mixed|object
     * @throws \ReflectionException
     */
    public function getClassInstance($key, $params): object
    {
        if ($params) {
            $reflect  = new \ReflectionClass($this->facades[$key]);
            $class = $reflect->newInstanceArgs($params);
        } else {
            $class  = new $this->facades[$key]();
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
            if (array_key_exists($key, $this->facades)) {
             
                // if we want a sinle instance
                if ($newInstance) {
                    return $this->getClassInstance($key, $params);

                } else if(
                    is_string( $this->facades[$key])
                    && class_exists($this->facades[$key] )
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
     * @param String|Array $key
     * @param String|null $class
     * @return $this
     */
    public function set($key, $class = null) :Container
    {
        if (is_array($key)) {
            foreach($key as $k =>$class) {
                $this->facades[$k] = $class;
            }
        } else if ($class) {
            $this->facades[$key] = $class;
        } else {
            if ( jose('file')->exists($key)) {
                $providers = require_once($key);
                if (!is_array($providers)) {
                    throw new ExpectedTypeException('Providers file must be an array');
                }
                foreach($providers as $k =>$class) {
                    $this->facades[$k] = $class;
                }
            } else {
                throw new NotFoundException('Providers file '.$key .' not found');
            }
        }
        return $this;
    }


}