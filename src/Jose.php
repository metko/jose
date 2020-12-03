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
    private static $theme_inited = null;

    /**
     * Get  the instance of the global app
     *
     */
    public static function app(): \Jose\Core\App
    {
       
        if( ! self::$instance) {
           throw new ErrorException('You need to init jose first in functions.php');
        }

        if( ! self::$theme_inited) {
            // dump('::app()');
            self::$instance->init_theme();
            self::$theme_inited = true;
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
                self::$instance = new \Jose\Core\App();
                if($config) {
                    self::config($config);
                }
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