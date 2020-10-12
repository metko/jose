<?php

namespace Jose;

use ErrorException;

class Jose {

    private static $instance = null;
    
    /**
     * If the app has already been inited
     *
     * @var bool
     */
    private $site = false;
    
    
    
    /**
     * Init the global package and register all the dependencies
     *
     * @return Timber object
     */
    public function init() {

        // use a config object
        if( $this->site) {
            throw new ErrorException('App jose already inited!');
        }

        $this->site = new \Jose\Core\App( null );
        // return $this;

    }
    
    /**
     * getSite
     *
     * @return void
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
     * @return void
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
                throw new ErrorException('You mist use Timber');
            }
            
            self::$instance = new Jose();

        }
        
        return self::$instance;
    }


}