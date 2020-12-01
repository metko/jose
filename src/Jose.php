<?php

namespace Jose;

use ErrorException;
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
    public static function app(): \Jose\Core\App
    {
        if( ! self::$instance) {
            // need to check
            if(self::checkRequirments() ) {
                self::$instance = new \Jose\Core\App();
            }
            
        }
        return self::$instance;
    }

    public static function config($config)
    {
        return Config::getInstance()->define($config);
    }

    public static function get_config($key = null)
    {
        return Config::getInstance()->get($key);
    }
    
    /**
     * Check all the necessary php version, lib etc here....
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