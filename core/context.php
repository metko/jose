<?php

namespace Jose\Core;

use Jose\Utils\Config;
use Timber\Menu;

class Context {
     // set the default context
    // it will be merged with the context in app/context.php
    public function init() {

        add_filter( 'timber/context', function($context) {
           
            $context['menu'] = new Menu('main_menu');
            
            $data['env'] = getenv('WP_ENV');

            // if breadcrumb
            ob_start();
            yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
            $context['breadcrumb'] = ob_get_clean();
            // end

            // get data from app/context.php
            return array_merge($context, Config::getInstance()->getContext()) ;

        } );
        // if yoast ....
      
    }
}