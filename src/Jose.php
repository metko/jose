<?php

namespace Jose;

use ErrorException;
use Jose\Core\Exceptions\DependencyMissingException;
use Timber\Timber;

class Jose {

    /**
     * If the app has already been inited
     *
     */
    private static $instance = null;

    /**
     * Get  the instance og the site
     *
     */
    public static function app()
    {
        if( ! self::$instance) {
            // need to check
            self::$instance = new \Jose\Core\App();
        }
        return self::$instance;
    }

}