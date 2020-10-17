<?php

namespace Jose\Core;

use ErrorException;
use Jose\Utils\Config;
use Jose\Utils\Finder;
use Timber\Menu;

class Context {

    public $finder = null;

    public function __construct() {
        $this->finder = Finder::getInstance();
    }
       
    /**
     * Inti the global context, pass some global data
     *
     * @return void
     */
    public function init()
    {
        $user_context =  $this->get_context();
        add_filter( 'timber/context', function($context) {
            
            // TODO foreach menu in the config file, geneerate a menu, or an array
            $context['menu'] = new Menu('main_menu');
            
            $data['env'] = getenv('WP_ENV');

            // get data from context user
            return array_merge($context,) ;

        } );
        // if yoast ....
      
    }

    
    /**
     * Get the config key from config
     *
     * @return array
     */
    private function get_context(): array
     {
        $context_path = Config::getInstance()->get('context_path');
        if($context_path) {
            if($this->finder->file_exists(ROOT . $context_path)) {
                return $this->finder->require(ROOT . $context_path);
            }else {
                throw new ErrorException(ROOT . $context_path . " doesnt exists");
            }
        }
        return [];
    }
}