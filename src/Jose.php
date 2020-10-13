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
     * If the app has already been inited
     *
     */
    private $site = null;
    

    /**
     * Init the global package and register all the dependencies
     *
     */
    public function init() {

        // use a config object
        if( $this->site) {
            throw new ErrorException('App jose already inited!');
        }

        $this->site = new \Jose\Core\App();
        // return $this;

    }
    
    /**
     * getSite
     *
     */
    public function getSite() {
        if( ! $this->site) {
            throw new ErrorException('You must init the Jose app first!');
        }
        return $this->site;
    }
    
    /**
     * Static site
     * Return a instance of the global Jose package
     * 
     * @return $instance
     */
    public static function app() {
        return self::getInstance();
    }
    
    /**
     * Get  the instance og the site
     *
     */
    public static function site() {
        return self::getInstance()->getSite();
    }
   
    /**
     * Get instance Jose
     *
     * @return \Jose\Jose
     */
    public static function getInstance() {
        if( ! self::$instance ) {
            if(! class_exists('\Timber\Timber') ) {
                throw new DependencyMissingException("Timber\Timber");
            }

            self::$instance = new Jose();

        }
        
        return self::$instance;
    }


}