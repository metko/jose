<?php

namespace Jose\Core;

use ErrorException;
use Jose\Core\Exceptions\FileNotException;
use Jose\Utils\Config;
use Jose\Utils\Finder;
use Timber\Menu;
use Timber\Timber;

class Context {

    public static $instance = null;

    public $context = [];

    /**
     * getInstance
     */
    public static function getInstance(): Context
    {
        if( ! self::$instance ) {
            self::$instance = new Context();
        }
        return self::$instance;
    }




    public  function setContext() {
        $newContext = $this->get_user_context();
        add_filter( 'timber/context', function( $context ) use($newContext){
            return array_merge($context, $newContext);
        } );
        $this->context['http_host'] = \Timber\URLHelper::get_scheme().'://'.\Timber\URLHelper::get_host();
       // $this->context['wp_title'] = \Timber\Helper::get_wp_title();
        $this->context['body_class'] = implode(' ', get_body_class());
        $this->context['request'] = new \Timber\Request();
        $user = new \Timber\User();
        $this->context['user'] = ($user->ID) ? $user : false;
        $this->context['posts'] = new \Timber\PostQuery();

        /** 
         * @deprecated as of Timber 1.3.0
         * @todo remove in Timber 1.4.*
         */
        $this->context['wp_head'] = new \Timber\FunctionWrapper( 'wp_head' );
        $this->context['wp_footer'] = new \Timber\FunctionWrapper( 'wp_footer' );

        if (array_key_exists('site', $newContext) ) {
            $this->context = array_merge($this->context, $newContext);
        }else {
            $this->context['site'] = new \Timber\Site();
        }
        $this->context['theme'] = $this->context['site']->theme;

        //  dd($this->context);
        \Timber\Timber::$context_cache = $this->context;
        \Timber\Timber::$context_cache = apply_filters('timber_context', Timber::$context_cache);
        return $this;
    }


    /**
     * Get the timber context merged to user context
     * @param $key
     * @return array|string
     */
    public function get($key = null)
    {
        $keys = explode('.', $key);

        if (count($keys)) {

            if(count($keys) == 1) {
                if (array_key_exists($key, $this->context)) {
                    return $this->context[$key];
                }
            }

            if (! accessibleArray($this->context)) {
                return $this->context;
            }

            if (is_null($key)) {
                return $this->context;
            }

            if (existKeyInArray($this->context, $key)) {
                return $this->context[$key];
            }

            if (strpos($key, '.') === false) {
                return $this->context[$key] ?? $this->context;
            }

            foreach (explode('.', $key) as $segment) {
                if (accessibleArray($this->context) && existKeyInArray($this->context, $segment)) {
                    $this->context = $this->context[$segment];
                } else {
                    return $this->context;
                }
            }

            return $this->context;

        } else {
            return $this->context;
        }
        return $this->context;

    }

    /**
     * Pass varibale into the context
     *
     * @param  mixed $param1 
     * @param  mixed $param2
     * @return self
     */
    public function pass($param1 = null, $param2 = null) 
    {
     
        if(!$param1) {
            return $this;
        }
        if(is_array($param1)) {
            $this->addArrayToContext($param1);
        }else if( isset($param2)) {
            $this->context[$param1] = $param2;
        }
        return $this;
        
    }
    
    /**
     * Add an array to the context
     *
     * @param  mixed $array
     * @return null
     */
    public function addArrayToContext(Array $array) 
    {
        if( count($array)) {
            foreach($array as $key => $value) {
                $context[$key] = $value;
            }
        }
        $this->context = array_merge($this->context, $context);
    }


    


    /**
     * Get the config key from config
     *
     * @return array
     * @throws Exceptions\ConfigIsNotArrayException
     * @throws FileNotException
     */
    protected function get_user_context(): array
     {
        $finder = Finder::getInstance();
        $context_path = Config::getInstance()->get('context_path');
        if($context_path) {
            if($finder->file_exists(ROOT . $context_path)) {
                return $finder->require(ROOT . $context_path);
            }else {
                return [];
                throw new FileNotException(ROOT . $context_path . " doesnt exists");
            }
        }
        return [];
    }
}