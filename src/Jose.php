<?php

namespace Jose;

use ErrorException;
use Jose\Core\Context;
use Jose\Core\Exceptions\DependencyMissingException;
use Jose\Utils\Config;
use Timber\Timber;

class Jose {
    
    /**
     * If the app has already been inited
     *
     */
    private static $instance = null;

    /**
     * Get  the instance of the global app
     *
     */
    public static function app($config = null): \Jose\Core\App
    {
        if( ! self::$instance) {
            // need to check
            if(self::checkRequirments() ) {
                if($config) {
                    self::config($config);
                }
                self::$instance = new \Jose\Core\App();
            }  
        }
        return self::$instance;
    }


    /**
     * config Set custom config file
     *
     * @param  mixed $config
     * @return void
     */
    public static function config($config)
    {
        return Config::getInstance()->define($config);
    }

    
    /**
     * init App init
     *
     * @param  mixed $config
     * @return void
     */
    public static function init($config = null): void
    {
        // dump('::init()');
        if( ! self::$instance) {
            // need to check
            if(self::checkRequirments() ) {
                if($config) {
                    self::config($config);
                }
                self::$instance = new \Jose\Core\App();
                // self::$instance->init_theme();
            }  
        }
    }
    
    /**
     * get_config return current config
     *
     * @param  mixed $key
     * @return void
     */
    public static function get_config($key = null)
    {
        return Config::getInstance()->get($key);
    }
    
    /**
     * Check all the necessary php version, lib etc here....
     *
     * @return bool
     */    
    /**
     * checkRequirments
     *
     * @return bool
     */
    private static function checkRequirments(): ?bool
    {
        if ( ! class_exists('\WP') ) {
            throw new ErrorException('You need wordpress to use Jose. Sorry...');
        }
        return true;
    }

}